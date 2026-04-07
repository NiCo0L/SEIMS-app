<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Models\SupplyCategory;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index', [
            'warehouses' => Warehouse::orderBy('name')->get(),
            'supplyCategories' => SupplyCategory::orderBy('name')->get(),
            'units' => Unit::orderBy('name')->get(),
            'equipmentCategories' => EquipmentCategory::with('types')->orderBy('name')->get(),
        ]);
    }

    public function storeWarehouse(Request $request)
    {
        Warehouse::create($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:warehouses,name'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]));

        return back()->with('status', 'Warehouse added successfully.');
    }

    public function storeSupplyCategory(Request $request)
    {
        SupplyCategory::create($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:supply_categories,name'],
            'description' => ['nullable', 'string'],
        ]));

        return back()->with('status', 'Supply category added successfully.');
    }

    public function storeUnit(Request $request)
    {
        Unit::create($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:units,name'],
            'symbol' => ['required', 'string', 'max:30'],
        ]));

        return back()->with('status', 'Unit added successfully.');
    }

    public function storeEquipmentCategory(Request $request)
    {
        EquipmentCategory::create($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:equipment_categories,name'],
            'description' => ['nullable', 'string'],
        ]));

        return back()->with('status', 'Equipment category added successfully.');
    }

    public function storeEquipmentType(Request $request)
    {
        EquipmentType::create($request->validate([
            'equipment_category_id' => ['required', 'integer', 'exists:equipment_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]));

        return back()->with('status', 'Equipment type added successfully.');
    }
}
