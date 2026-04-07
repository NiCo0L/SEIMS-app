<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplyTransaction extends Model
{
    protected $fillable = [
        'supply_item_id',
        'user_id',
        'transaction_type',
        'quantity',
        'balance_before',
        'balance_after',
        'document_number',
        'reference_date',
        'recipient_name',
        'person_in_charge',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'balance_before' => 'decimal:2',
            'balance_after' => 'decimal:2',
            'reference_date' => 'date',
        ];
    }

    public function supplyItem(): BelongsTo
    {
        return $this->belongsTo(SupplyItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
