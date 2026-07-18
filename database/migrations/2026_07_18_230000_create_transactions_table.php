<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->enum('mode', ['invoice', 'receipt'])->default('invoice');
            $table->enum('status', ['draft', 'unpaid', 'paid'])->default('draft')->index();
            $table->foreignId('business_unit_id')->constrained('business_units')->onDelete('restrict');
            $table->foreignId('client_id')->constrained('clients')->onDelete('restrict');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('discount', 15, 2)->default(0.00);
            $table->decimal('total', 15, 2);
            $table->date('due_date')->nullable()->index();
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('deletion_reason')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Data migration: Migrate existing invoices and receipts to the new transactions table
        if (Schema::hasTable('invoices')) {
            $invoices = DB::table('invoices')->get();
            foreach ($invoices as $invoice) {
                // Determine if there is a receipt for this invoice
                $receipt = null;
                if (Schema::hasTable('receipts')) {
                    $receipt = DB::table('receipts')->where('invoice_id', $invoice->id)->first();
                }

                $mode = $receipt ? 'receipt' : 'invoice';
                $status = $invoice->status;
                if ($status === 'paid' && !$receipt) {
                    // If paid but no receipt in DB, default to receipt mode or paid invoice status
                    $mode = 'invoice';
                }

                $paymentDate = null;
                $paymentMethod = null;
                if ($receipt) {
                    $paymentDate = $receipt->payment_date;
                    $paymentMethod = 'Transfer'; // Default for migrated receipts
                } elseif ($status === 'paid') {
                    $paymentDate = $invoice->updated_at;
                }

                DB::table('transactions')->insert([
                    'id' => $invoice->id, // Maintain ID integrity
                    'transaction_number' => $invoice->invoice_number,
                    'mode' => $mode,
                    'status' => $status === 'sent' || $status === 'pending' ? 'unpaid' : ($status === 'paid' ? 'paid' : 'draft'),
                    'business_unit_id' => $invoice->business_unit_id,
                    'client_id' => $invoice->client_id,
                    'subtotal' => $invoice->subtotal,
                    'discount' => $invoice->discount,
                    'total' => $invoice->total,
                    'due_date' => $invoice->due_date,
                    'payment_date' => $paymentDate,
                    'payment_method' => $paymentMethod,
                    'notes' => $invoice->notes,
                    'created_by' => $invoice->created_by ?? null,
                    'deleted_by' => $invoice->deleted_by ?? null,
                    'deletion_reason' => $invoice->deletion_reason ?? null,
                    'deleted_at' => $invoice->deleted_at ?? null,
                    'created_at' => $invoice->created_at,
                    'updated_at' => $invoice->updated_at,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
