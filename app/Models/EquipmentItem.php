<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentItem extends Model
{
    protected $fillable = [
        'equipment_category_id',
        'equipment_type_id',
        'name',
        'document_number',
        'control_number',
        'person_in_charge',
        'status',
        'last_status_date',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'last_status_date' => 'date',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            'good' => 'Good condition',
            'maintenance_pre' => 'Under maintenance (pre-repair)',
            'maintenance_post' => 'Under maintenance (post-repair)',
            'transferred' => 'Transferred',
            'condemned' => 'Condemned',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(EquipmentCategory::class, 'equipment_category_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(EquipmentType::class, 'equipment_type_id');
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(EquipmentStatusLog::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EquipmentDocument::class);
    }
}
