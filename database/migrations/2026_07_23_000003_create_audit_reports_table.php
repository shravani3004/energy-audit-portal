<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_daily_kwh', 12, 2)->default(0);
            $table->decimal('total_monthly_kwh', 12, 2)->default(0);
            $table->decimal('estimated_monthly_cost', 12, 2)->default(0);
            $table->decimal('energy_use_intensity', 10, 3)->default(0);
            $table->enum('consumption_rating', ['low', 'moderate', 'high'])->default('low');
            $table->decimal('rate_per_kwh', 8, 4)->default(0.14);
            $table->json('breakdown')->nullable();
            $table->json('recommended_tips')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_reports');
    }
};
