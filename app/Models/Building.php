<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'building_type',
        'square_footage',
        'floors',
        'occupants',
    ];

    protected $casts = [
        'square_footage' => 'float',
        'floors' => 'integer',
        'occupants' => 'integer',
    ];

    public function appliances(): HasMany
    {
        return $this->hasMany(Appliance::class);
    }

    public function auditReports(): HasMany
    {
        return $this->hasMany(AuditReport::class);
    }

    public function latestReport(): ?AuditReport
    {
        return $this->auditReports()->latest()->first();
    }
}
