<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_id'     => Invoice::factory(),
            'payment_method' => fake()->randomElement([
                PaymentMethod::CREDIT_CARD,
                PaymentMethod::BANK_TRANSFER,
                PaymentMethod::DIRECT_DEBIT,
            ]),
            'status'         => PaymentStatus::COMPLETED,
            'transaction_id' => 'tx_' . fake()->unique()->uuid(),
            'amount'         => (string) fake()->numberBetween(100000, 3000000),
            'currency'       => 'XOF',
            'paid_at'        => fake()->dateTimeBetween('-1 month', 'now'),
            'notes'          => 'Paiement enregistré avec succès.',
        ];
    }
}
