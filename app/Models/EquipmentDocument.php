<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentDocument extends Model
{
    protected $fillable = [
        'equipment_item_id',
        'user_id',
        'document_type',
        'document_number',
        'original_name',
        'stored_path',
        'mime_type',
        'remarks',
    ];

    public function equipmentItem(): BelongsTo
    {
        return $this->belongsTo(EquipmentItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
