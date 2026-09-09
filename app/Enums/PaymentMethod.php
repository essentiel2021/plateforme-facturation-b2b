<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CREDIT_CARD = 'credit_card';
    case BANK_TRANSFER = 'bank_transfer';
    case DIRECT_DEBIT = 'direct_debit';
    case CHECK = 'check';

    public function label(): string
    {
        return match ($this) {
            self::CREDIT_CARD   => 'Carte bancaire',
            self::BANK_TRANSFER => 'Virement bancaire',
            self::DIRECT_DEBIT  => 'Prélèvement SEPA',
            self::CHECK         => 'Chèque',
        };
    }
}
