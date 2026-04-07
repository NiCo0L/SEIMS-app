<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupplyItem extends Model
{
    protected $fillable = [
        'warehouse_id',
        'supply_category_id',
        'unit_id',
        'name',
        'document_number',
        'description',
        'current_quantity',
        'minimum_quantity',
    ];

    protected function casts(): array
    {
        return [
            'current_quantity' => 'decimal:2',
            'minimum_quantity' => 'decimal:2',
        ];
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SupplyCategory::class, 'supply_category_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(SupplyTransaction::class);
    }
}
