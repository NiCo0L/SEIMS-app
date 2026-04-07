<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentCategory extends Model
{
    protected $fillable = ['name', 'description'];

    public function equipmentItems(): HasMany
    {
        return $this->hasMany(EquipmentItem::class);
    }

    public function types(): HasMany
    {
        return $this->hasMany(EquipmentType::class);
    }
}
