<?php

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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')
                  ->constrained('invoices')
                  ->cascadeOnDelete();

            $table->string('description');
            $table->decimal('quantity', 10, 2)->default(1.00);   // Quantité ou heures (ex: 1.5)
            $table->decimal('unit_price', 15, 2);                // Prix unitaire HT
            $table->decimal('tax_rate', 5, 2)->default(20.00);   // TVA en % (ex: 20.00)
            $table->decimal('total', 15, 2);                     // Total de la ligne HT
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
