<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingTip extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'title',
        'description',
        'estimated_savings_percent',
        'priority',
    ];

    protected $casts = [
        'estimated_savings_percent' => 'float',
        'priority' => 'integer',
    ];
}
