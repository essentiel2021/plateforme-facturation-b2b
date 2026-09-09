<?php

use App\Enums\InvoiceStatus;
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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')
                  ->constrained('organizations')
                  ->cascadeOnDelete();

            $table->string('invoice_number', 50)->unique();
            $table->string('status', 20)->default(InvoiceStatus::DRAFT->value)->index();

            $table->date('issue_date');
            $table->date('due_date');

            // Précision financière : 15 chiffres dont 2 après la virgule
            $table->decimal('subtotal', 15, 2)->default(0.00);    // Montant HT
            $table->decimal('tax_amount', 15, 2)->default(0.00);  // Montant TVA
            $table->decimal('total', 15, 2)->default(0.00);       // Montant TTC

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Archivage comptable obligatoire
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
