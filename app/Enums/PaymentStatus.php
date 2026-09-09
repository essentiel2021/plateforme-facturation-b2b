<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';     // En attente de confirmation bancaire
    case COMPLETED = 'completed'; // Validé avec succès
    case FAILED = 'failed';       // Échoué (fonds insuffisants, etc.)
    case REFUNDED = 'refunded';   // Remboursé

    public function label(): string
    {
        return match ($this) {
            self::PENDING   => 'En attente',
            self::COMPLETED => 'Validé',
            self::FAILED    => 'Échoué',
            self::REFUNDED  => 'Remboursé',
        };
    }
}
