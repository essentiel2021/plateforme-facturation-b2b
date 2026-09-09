<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'payment_method',
        'status',
        'transaction_id',
        'amount',
        'currency',
        'paid_at',
        'notes',
    ];

    /**
     * Typage automatique des colonnes.
     */
    protected function casts(): array
    {
        return [
            'payment_method' => PaymentMethod::class,
            'status'         => PaymentStatus::class,
            'amount'         => 'decimal:2',
            'paid_at'        => 'datetime',
        ];
    }

    /**
     * Relation : Un paiement est rattaché à une facture.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
