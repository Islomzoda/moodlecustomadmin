<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sell_id',
        'paid',
        'bank_name',
        'requisites',
        'files',
        'transfer_bank',
        'transfer_name',
        'paid_at'
    ];

    protected $casts = [
        'files' => 'array',
    ];
    public function sell(): BelongsTo
    {
        return $this->belongsTo(Sell::class);
    }
}
