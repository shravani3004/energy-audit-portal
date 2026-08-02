<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'building' => [
                'id' => $this->building->id,
                'name' => $this->building->name,
                'square_footage' => $this->building->square_footage,
            ],
            'total_daily_kwh' => $this->total_daily_kwh,
            'total_monthly_kwh' => $this->total_monthly_kwh,
            'estimated_monthly_cost' => $this->estimated_monthly_cost,
            'energy_use_intensity' => $this->energy_use_intensity,
            'consumption_rating' => $this->consumption_rating,
            'rate_per_kwh' => $this->rate_per_kwh,
            'breakdown' => $this->breakdown,
            'recommended_tips' => $this->recommended_tips,
            'gauge' => $this->gaugePayload(),
            'generated_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
