<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCategory;
use App\Models\EquipmentItem;
use App\Models\SupplyCategory;
use App\Models\SupplyItem;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('dashboard', [
            'supplyCategoryCount' => SupplyCategory::count(),
            'totalSupplyItems' => SupplyItem::count(),
            'lowStockItems' => SupplyItem::with(['category', 'unit', 'warehouse'])
                ->whereColumn('current_quantity', '<=', 'minimum_quantity')
                ->orderBy('current_quantity')
                ->get(),
            'equipmentCategoryCount' => EquipmentCategory::count(),
            'totalEquipmentItems' => EquipmentItem::count(),
            'equipmentStatusSummary' => EquipmentItem::query()
                ->selectRaw('status, COUNT(*) as aggregate')
                ->groupBy('status')
                ->pluck('aggregate', 'status'),
        ]);
    }
}
