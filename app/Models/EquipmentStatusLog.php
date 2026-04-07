<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentStatusLog extends Model
{
    protected $fillable = [
        'equipment_item_id',
        'user_id',
        'status',
        'status_date',
        'document_number',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'status_date' => 'date',
        ];
    }

    public function equipmentItem(): BelongsTo
    {
        return $this->belongsTo(EquipmentItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
