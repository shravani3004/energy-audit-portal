<?php

namespace Database\Seeders;

use App\Models\Appliance;
use App\Models\AuditReport;
use App\Models\Building;
use App\Models\SavingTip;
use Illuminate\Database\Seeder;

class SampleAuditDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSavingTips();

        $building = Building::create([
            'name' => 'Riverside Office Tower',
            'address' => '400 Riverside Drive, Austin, TX',
            'building_type' => 'Office',
            'square_footage' => 25000,
            'floors' => 6,
            'occupants' => 180,
        ]);

        $appliances = [
            ['name' => 'HVAC System',                  'category' => 'HVAC',              'wattage' => 5000, 'quantity' => 2,  'hours_per_day' => 10],
            ['name' => 'LED Lighting - Floors 1-3',     'category' => 'Lighting',          'wattage' => 40,   'quantity' => 120,'hours_per_day' => 12],
            ['name' => 'Legacy Fluorescent - Floors 4-6','category' => 'Lighting',         'wattage' => 75,   'quantity' => 90, 'hours_per_day' => 12],
            ['name' => 'Break Room Refrigerators',      'category' => 'Refrigeration',     'wattage' => 750,  'quantity' => 3,  'hours_per_day' => 24],
            ['name' => 'Water Heater',                  'category' => 'Water Heating',     'wattage' => 4000, 'quantity' => 1,  'hours_per_day' => 6],
            ['name' => 'Desktop Workstations',          'category' => 'Office Equipment',  'wattage' => 150,  'quantity' => 140,'hours_per_day' => 9],
            ['name' => 'Passenger Elevators',            'category' => 'Elevators',        'wattage' => 3000, 'quantity' => 2,  'hours_per_day' => 12],
            ['name' => 'Rooftop Ventilation Fans',       'category' => 'Ventilation',      'wattage' => 1000, 'quantity' => 4,  'hours_per_day' => 16],
            ['name' => 'Server Room',                    'category' => 'IT/Server',        'wattage' => 2000, 'quantity' => 1,  'hours_per_day' => 24],
        ];

        foreach ($appliances as $appliance) {
            Appliance::create(array_merge($appliance, [
                'building_id' => $building->id,
                'is_active' => true,
            ]));
        }

        AuditReport::generateForBuilding($building->fresh('appliances'), ratePerKwh: 0.14);

        $this->command?->info('Seeded sample building "Riverside Office Tower" with appliances and an audit report.');
    }

    protected function seedSavingTips(): void
    {
        $tips = [
            ['category' => 'HVAC', 'title' => 'Schedule setback temperatures after hours', 'description' => 'Programmable schedules that widen the setpoint outside occupied hours typically cut HVAC runtime without affecting comfort.', 'estimated_savings_percent' => 12, 'priority' => 1],
            ['category' => 'HVAC', 'title' => 'Service filters and coils quarterly', 'description' => 'Dirty filters and coils force systems to run longer to hit the same setpoint.', 'estimated_savings_percent' => 6, 'priority' => 2],
            ['category' => 'Lighting', 'title' => 'Convert remaining fixtures to LED', 'description' => 'Legacy fluorescent or halogen fixtures draw several times more power per lumen than LED equivalents.', 'estimated_savings_percent' => 15, 'priority' => 1],
            ['category' => 'Lighting', 'title' => 'Add occupancy sensors in low-traffic areas', 'description' => 'Storage rooms, stairwells, and restrooms rarely need continuous lighting.', 'estimated_savings_percent' => 8, 'priority' => 2],
            ['category' => 'Refrigeration', 'title' => 'Check door seals and defrost cycles', 'description' => 'Worn gaskets let cold air escape, forcing compressors to cycle more often.', 'estimated_savings_percent' => 5, 'priority' => 1],
            ['category' => 'Water Heating', 'title' => 'Lower setpoint to 120°F (49°C)', 'description' => 'Each 10°F reduction in setpoint saves meaningfully on standby losses.', 'estimated_savings_percent' => 7, 'priority' => 1],
            ['category' => 'Office Equipment', 'title' => 'Enforce sleep mode on idle workstations', 'description' => 'Fleet-wide power management settings on computers and monitors add up across a floor.', 'estimated_savings_percent' => 9, 'priority' => 1],
            ['category' => 'Elevators', 'title' => 'Enable standby / regenerative drive mode', 'description' => 'Modern drives can recover braking energy and idle down between calls.', 'estimated_savings_percent' => 10, 'priority' => 1],
            ['category' => 'Ventilation', 'title' => 'Install variable frequency drives on fans', 'description' => 'VFDs let fans match airflow to real demand instead of running at fixed speed.', 'estimated_savings_percent' => 14, 'priority' => 1],
            ['category' => 'Kitchen Equipment', 'title' => 'Replace aging units with ENERGY STAR models', 'description' => 'Commercial kitchen equipment is often the oldest, least efficient gear in a building.', 'estimated_savings_percent' => 11, 'priority' => 1],
            ['category' => 'IT/Server', 'title' => 'Consolidate or virtualize underused servers', 'description' => 'Idle physical servers draw near-full power for a fraction of the workload.', 'estimated_savings_percent' => 18, 'priority' => 1],
        ];

        foreach ($tips as $tip) {
            SavingTip::firstOrCreate(
                ['category' => $tip['category'], 'title' => $tip['title']],
                $tip
            );
        }
    }
}
