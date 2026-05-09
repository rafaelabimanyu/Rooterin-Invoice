<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('invoice_number')->unique();
            $blueprint->foreignId('client_id')->constrained()->onDelete('cascade');
            $blueprint->date('tanggal_invoice');
            $blueprint->date('due_date');
            $blueprint->enum('status', ['draft', 'sent', 'pending', 'dp', 'paid', 'overdue', 'cancelled'])->default('draft');
            $blueprint->decimal('subtotal', 15, 2)->default(0);
            $blueprint->decimal('tax_percent', 5, 2)->default(0);
            $blueprint->decimal('discount_percent', 5, 2)->default(0);
            $blueprint->decimal('total', 15, 2)->default(0);
            $blueprint->text('notes_internal')->nullable();
            $blueprint->text('terms_condition')->nullable();
            $blueprint->foreignId('created_by')->constrained('users');
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
