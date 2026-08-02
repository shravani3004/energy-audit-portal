<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_id',
        'total_daily_kwh',
        'total_monthly_kwh',
        'estimated_monthly_cost',
        'energy_use_intensity',
        'consumption_rating',
        'rate_per_kwh',
        'breakdown',
        'recommended_tips',
    ];

    protected $casts = [
        'total_daily_kwh' => 'float',
        'total_monthly_kwh' => 'float',
        'estimated_monthly_cost' => 'float',
        'energy_use_intensity' => 'float',
        'rate_per_kwh' => 'float',
        'breakdown' => 'array',
        'recommended_tips' => 'array',
    ];

    // Energy Use Intensity thresholds, in kWh per square foot per month.
    // These must match the gauge thresholds in the frontend (index.html).
    public const EUI_LOW_THRESHOLD = 1.2;
    public const EUI_MODERATE_THRESHOLD = 2.2;

    // A category is treated as "significant" for advisor recommendations
    // once it accounts for at least this share of total consumption.
    public const SIGNIFICANT_CATEGORY_THRESHOLD_PERCENT = 15;

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Build and persist a full audit report for a building based on its
     * currently associated (active) appliances.
     */
    public static function generateForBuilding(Building $building, float $ratePerKwh = 0.14): self
    {
        $appliances = $building->appliances()->where('is_active', true)->get();

        $totalDailyKwh = 0.0;
        $categoryTotals = [];

        foreach ($appliances as $appliance) {
            $dailyKwh = $appliance->dailyKwh();
            $totalDailyKwh += $dailyKwh;

            $category = $appliance->category ?: 'Other';
            $categoryTotals[$category] = ($categoryTotals[$category] ?? 0) + $dailyKwh;
        }

        $totalMonthlyKwh = $totalDailyKwh * 30;
        $estimatedMonthlyCost = round($totalMonthlyKwh * $ratePerKwh, 2);

        $squareFootage = $building->square_footage ?: 1;
        $eui = $totalMonthlyKwh / $squareFootage;
        $rating = self::ratingForEui($eui);

        $breakdown = self::buildBreakdown($categoryTotals, $totalDailyKwh);
        $recommendedTips = self::buildRecommendations($breakdown);

        return self::create([
            'building_id' => $building->id,
            'total_daily_kwh' => round($totalDailyKwh, 2),
            'total_monthly_kwh' => round($totalMonthlyKwh, 2),
            'estimated_monthly_cost' => $estimatedMonthlyCost,
            'energy_use_intensity' => round($eui, 3),
            'consumption_rating' => $rating,
            'rate_per_kwh' => $ratePerKwh,
            'breakdown' => $breakdown,
            'recommended_tips' => $recommendedTips,
        ]);
    }

    /**
     * Same calculation as generateForBuilding(), but from a raw payload
     * (building attrs + appliance rows) without touching the database.
     * Used by AuditController::store() to preview/validate before persisting.
     */
    public static function calculateFromPayload(array $buildingAttrs, array $applianceRows, float $ratePerKwh): array
    {
        $totalDailyKwh = 0.0;
        $categoryTotals = [];

        foreach ($applianceRows as $row) {
            if (empty($row['checked']) && ! array_key_exists('is_active', $row)) {
                // Frontend sends `checked`; API consumers may send `is_active` instead.
                $isActive = $row['is_active'] ?? false;
            } else {
                $isActive = $row['checked'] ?? ($row['is_active'] ?? true);
            }

            if (! $isActive) {
                continue;
            }

            $wattage = (float) ($row['wattage'] ?? 0);
            $quantity = (float) ($row['quantity'] ?? 0);
            $hours = (float) ($row['hours'] ?? $row['hours_per_day'] ?? 0);
            $category = $row['category'] ?: 'Other';

            $dailyKwh = ($wattage / 1000) * $quantity * $hours;
            $totalDailyKwh += $dailyKwh;
            $categoryTotals[$category] = ($categoryTotals[$category] ?? 0) + $dailyKwh;
        }

        $totalMonthlyKwh = $totalDailyKwh * 30;
        $estimatedMonthlyCost = round($totalMonthlyKwh * $ratePerKwh, 2);

        $squareFootage = (float) ($buildingAttrs['square_footage'] ?? 0) ?: 1;
        $eui = $totalMonthlyKwh / $squareFootage;
        $rating = self::ratingForEui($eui);

        $breakdown = self::buildBreakdown($categoryTotals, $totalDailyKwh);
        $recommendedTips = self::buildRecommendations($breakdown);

        return [
            'total_daily_kwh' => round($totalDailyKwh, 2),
            'total_monthly_kwh' => round($totalMonthlyKwh, 2),
            'estimated_monthly_cost' => $estimatedMonthlyCost,
            'energy_use_intensity' => round($eui, 3),
            'consumption_rating' => $rating,
            'rate_per_kwh' => $ratePerKwh,
            'breakdown' => $breakdown,
            'recommended_tips' => $recommendedTips,
        ];
    }

    protected static function buildBreakdown(array $categoryTotals, float $totalDailyKwh): array
    {
        $breakdown = [];
        foreach ($categoryTotals as $category => $dailyKwh) {
            $breakdown[] = [
                'category' => $category,
                'daily_kwh' => round($dailyKwh, 2),
                'monthly_kwh' => round($dailyKwh * 30, 2),
                'percent_of_total' => $totalDailyKwh > 0
                    ? round(($dailyKwh / $totalDailyKwh) * 100, 1)
                    : 0,
            ];
        }

        // Highest-impact categories first, so advisor cards and the savings
        // chart surface the biggest levers up top.
        usort($breakdown, fn ($a, $b) => $b['daily_kwh'] <=> $a['daily_kwh']);

        return $breakdown;
    }

    protected static function ratingForEui(float $eui): string
    {
        if ($eui <= self::EUI_LOW_THRESHOLD) {
            return 'low';
        }

        if ($eui <= self::EUI_MODERATE_THRESHOLD) {
            return 'moderate';
        }

        return 'high';
    }

    /**
     * Pull matching SavingTip rows for the highest-impact categories.
     * Falls back to the top 3 categories by consumption if nothing crosses
     * the significance threshold.
     */
    protected static function buildRecommendations(array $breakdown): array
    {
        $significantCategories = array_map(
            fn ($row) => $row['category'],
            array_filter(
                $breakdown,
                fn ($row) => $row['percent_of_total'] >= self::SIGNIFICANT_CATEGORY_THRESHOLD_PERCENT
            )
        );

        if (empty($significantCategories)) {
            $significantCategories = array_map(fn ($row) => $row['category'], array_slice($breakdown, 0, 3));
        }

        if (empty($significantCategories)) {
            return [];
        }

        return SavingTip::whereIn('category', $significantCategories)
            ->orderBy('priority')
            ->get()
            ->unique('category')
            ->map(fn ($tip) => [
                'category' => $tip->category,
                'title' => $tip->title,
                'description' => $tip->description,
                'estimated_savings_percent' => $tip->estimated_savings_percent,
            ])
            ->values()
            ->all();
    }

    /**
     * Gauge-ready payload for the frontend SVG gauge.
     */
    public function gaugePayload(): array
    {
        return [
            'value' => $this->energy_use_intensity,
            'min' => 0,
            'max' => 4.0,
            'low_threshold' => self::EUI_LOW_THRESHOLD,
            'moderate_threshold' => self::EUI_MODERATE_THRESHOLD,
            'rating' => $this->consumption_rating,
        ];
    }
}
