<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case DRAFT = 'draft';         // Brouillon, non encore transmise
    case SENT = 'sent';           // Émise et envoyée au client
    case PAID = 'paid';           // Payée intégralement
    case OVERDUE = 'overdue';     // Date d'échéance dépassée
    case CANCELLED = 'cancelled'; // Annulée / Avoir

    /**
     * Libellé humain en français.
     */
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Brouillon',
            self::SENT => 'Envoyée',
            self::PAID => 'Payée',
            self::OVERDUE => 'En retard',
            self::CANCELLED => 'Annulée',
        };
    }

    /**
     * Indique si la facture attend un paiement.
     */
    public function isPendingPayment(): bool
    {
        return in_array($this, [self::SENT, self::OVERDUE], true);
    }
}
