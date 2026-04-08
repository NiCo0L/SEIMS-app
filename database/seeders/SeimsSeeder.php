<?php

namespace Database\Seeders;

use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Models\SupplyCategory;
use App\Models\Unit;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SeimsSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@seims.local'],
            ['name' => 'SEIMS Administrator', 'password' => Hash::make('password')]
        );

        foreach ([
            ['name' => 'BSP Warehouse', 'location' => 'BSP', 'description' => 'Field operations storage'],
            ['name' => 'NSP Warehouse', 'location' => 'NSP', 'description' => 'Primary agricultural supply storage'],
            ['name' => 'FTR Warehouse', 'location' => 'FTR', 'description' => 'Secondary agricultural supply storage'],
        ] as $warehouse) {
            Warehouse::updateOrCreate(['name' => $warehouse['name']], $warehouse);
        }

        foreach ([
            'Fertilizers',
            'Agricultural Supplies',
            'Chemicals',
            'Office Supplies',
        ] as $category) {
            SupplyCategory::updateOrCreate(['name' => $category], ['description' => $category.' stock group']);
        }

        foreach ([
            ['name' => 'Kilogram', 'symbol' => 'kg'],
            ['name' => 'Liter', 'symbol' => 'L'],
            ['name' => 'Milliliter', 'symbol' => 'mL'],
            ['name' => 'Grams', 'symbol' => 'g'],
            ['name' => 'Piece', 'symbol' => 'pc'],
            ['name' => 'Sack', 'symbol' => 'sack'],
            ['name' => 'Box', 'symbol' => 'box'],
        ] as $unit) {
            Unit::updateOrCreate(['name' => $unit['name']], $unit);
        }

        $equipmentMap = [
            'Field Machinery' => ['Tractor', 'Cutter', 'Harvester'],
            'Irrigation Equipment' => ['Water Pump', 'Sprayer'],
            'Seed Processing Equipment' => ['Moisture Meter', 'Caliper'],
        ];

        foreach ($equipmentMap as $categoryName => $types) {
            $category = EquipmentCategory::updateOrCreate(
                ['name' => $categoryName],
                ['description' => $categoryName.' inventory grouping']
            );

            foreach ($types as $typeName) {
                EquipmentType::updateOrCreate(
                    ['equipment_category_id' => $category->id, 'name' => $typeName],
                    ['description' => $typeName.' type']
                );
            }
        }
    }
}
