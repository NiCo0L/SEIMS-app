<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupplyInventoryController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/warehouses', [SettingsController::class, 'storeWarehouse'])->name('settings.warehouses.store');
    Route::post('/settings/supply-categories', [SettingsController::class, 'storeSupplyCategory'])->name('settings.supply-categories.store');
    Route::post('/settings/units', [SettingsController::class, 'storeUnit'])->name('settings.units.store');
    Route::post('/settings/equipment-categories', [SettingsController::class, 'storeEquipmentCategory'])->name('settings.equipment-categories.store');
    Route::post('/settings/equipment-types', [SettingsController::class, 'storeEquipmentType'])->name('settings.equipment-types.store');

    Route::get('/supplies/export', [SupplyInventoryController::class, 'export'])->name('supplies.export');
    Route::post('/supplies/{supply}/transactions', [SupplyInventoryController::class, 'transaction'])->name('supplies.transaction');
    Route::resource('supplies', SupplyInventoryController::class)->except('destroy')->parameters(['supplies' => 'supply']);

    Route::get('/equipment/export', [EquipmentController::class, 'export'])->name('equipment.export');
    Route::post('/equipment/{equipment}/status', [EquipmentController::class, 'status'])->name('equipment.status');
    Route::post('/equipment/{equipment}/documents', [EquipmentController::class, 'document'])->name('equipment.document');
    Route::get('/documents/{document}/download', [EquipmentController::class, 'download'])->name('equipment.document.download');
    Route::resource('equipment', EquipmentController::class)->except('destroy')->parameters(['equipment' => 'equipment']);
});
