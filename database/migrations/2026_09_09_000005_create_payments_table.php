<?php

use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')
                  ->constrained('invoices')
                  ->cascadeOnDelete();

            $table->string('payment_method', 30);
            $table->string('status', 20)->default(PaymentStatus::PENDING->value)->index();
            $table->string('transaction_id', 100)->nullable()->unique(); // Rapprochement bancaire

            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('EUR');
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
