<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    protected $model = InvoiceItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 10);
        $unitPrice = (string) fake()->numberBetween(50000, 1500000); // Entre 50 000 et 1 500 000 XOF
        $total = bcmul((string) $quantity, $unitPrice, 2);

        return [
            'invoice_id'  => Invoice::factory(),
            'description' => fake()->randomElement([
                'Abonnement Plateforme SaaS Cloud Entreprise',
                'Prestation de développement backend Laravel & APIs',
                'Audit de sécurité et conformité des systèmes',
                'Configuration & Déploiement d\'infrastructure Docker/Nginx',
                'Optimisation des performances de base de données PostgreSQL',
                'Mise en place de files de traitement asynchrone Redis',
            ]),
            'quantity'    => $quantity,
            'unit_price'  => $unitPrice,
            'tax_rate'    => 18.00, // TVA standard zone UEMOA (18%)
            'total'       => $total,
        ];
    }
}
