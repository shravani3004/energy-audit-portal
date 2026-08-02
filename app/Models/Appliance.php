<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appliance extends Model
{
    use HasFactory;

    // Keep this list in sync with the category select in the frontend
    // checklist and with the SavingTip seed categories.
    public const CATEGORIES = [
        'HVAC', 'Lighting', 'Refrigeration', 'Water Heating',
        'Office Equipment', 'Elevators', 'Ventilation',
        'Kitchen Equipment', 'IT/Server', 'Other',
    ];

    protected $fillable = [
        'building_id',
        'name',
        'category',
        'wattage',
        'quantity',
        'hours_per_day',
        'is_active',
    ];

    protected $casts = [
        'wattage' => 'float',
        'quantity' => 'integer',
        'hours_per_day' => 'float',
        'is_active' => 'boolean',
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Daily energy consumption in kWh for this appliance line:
     * (watts / 1000) * quantity * hours run per day.
     */
    public function dailyKwh(): float
    {
        if (! $this->is_active) {
            return 0.0;
        }

        return ($this->wattage / 1000) * $this->quantity * $this->hours_per_day;
    }

    /**
     * Monthly energy consumption in kWh, assuming a 30-day billing month.
     */
    public function monthlyKwh(): float
    {
        return $this->dailyKwh() * 30;
    }
}
