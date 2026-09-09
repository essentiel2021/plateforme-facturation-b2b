<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Attributs assignables en masse.
     */
    protected $fillable = [
        'name',
        'tax_number',
        'email',
        'phone',
        'address',
        'is_active',
    ];

    /**
     * Typage automatique des attributs.
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relation : Une entreprise possède plusieurs utilisateurs (comptables, gestionnaires).
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relation : Une entreprise cliente possède plusieurs factures.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
