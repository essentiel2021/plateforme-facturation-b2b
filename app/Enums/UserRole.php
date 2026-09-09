<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case ACCOUNTANT = 'accountant';
    case CLIENT = 'client';

    /**
     * Retourne les libellés lisibles pour chaque rôle.
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrateur',
            self::ACCOUNTANT => 'Comptable',
            self::CLIENT => 'Client B2B',
        };
    }
}
