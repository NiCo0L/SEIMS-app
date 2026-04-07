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
            ['name' => 'Main Warehouse', 'location' => 'Central Campus', 'description' => 'Primary agricultural supply storage'],
            ['name' => 'Field Warehouse', 'location' => 'North Farm', 'description' => 'Field operations storage'],
            ['name' => 'Equipment Shed', 'location' => 'South Block', 'description' => 'Hand tools and small equipment storage'],
        ] as $warehouse) {
            Warehouse::updateOrCreate(['name' => $warehouse['name']], $warehouse);
        }

        foreach ([
            'Fertilizers',
            'Seeds',
            'Chemicals',
            'Irrigation Supplies',
            'Protective Gear',
        ] as $category) {
            SupplyCategory::updateOrCreate(['name' => $category], ['description' => $category.' stock group']);
        }

        foreach ([
            ['name' => 'Kilogram', 'symbol' => 'kg'],
            ['name' => 'Liter', 'symbol' => 'L'],
            ['name' => 'Piece', 'symbol' => 'pc'],
            ['name' => 'Sack', 'symbol' => 'sack'],
            ['name' => 'Box', 'symbol' => 'box'],
        ] as $unit) {
            Unit::updateOrCreate(['name' => $unit['name']], $unit);
        }

        $equipmentMap = [
            'Field Machinery' => ['Tractor', 'Power Tiller'],
            'Irrigation Equipment' => ['Water Pump', 'Sprayer'],
            'Workshop Tools' => ['Generator', 'Chainsaw'],
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
