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
        Schema::create('project_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->unique()->constrained('transactions')->onDelete('cascade');
            $table->string('technician_names')->default('Umum');
            $table->text('cause_of_clog')->nullable();
            $table->string('warranty_info')->default('Tidak Ada Garansi');
            $table->json('documentation_links')->nullable();
            $table->decimal('ppn_percentage', 5, 2)->default(0.00);
            $table->decimal('pph_percentage', 5, 2)->default(0.00);
            $table->decimal('ppn_amount', 15, 2)->default(0.00);
            $table->decimal('pph_amount', 15, 2)->default(0.00);
            $table->timestamps();
        });

        // Migrate technical and tax data from existing invoices
        if (Schema::hasTable('invoices')) {
            $invoices = DB::table('invoices')->get();
            foreach ($invoices as $invoice) {
                // Calculate PPN and PPh percentages for data migration
                $base = $invoice->subtotal - $invoice->discount;
                $ppnPercent = 0;
                $pphPercent = 0;

                if ($base > 0) {
                    $ppnPercent = round(($invoice->ppn / $base) * 100, 2);
                    $pphPercent = round(($invoice->pph / $base) * 100, 2);
                }

                // Retrieve attachments as documentation links if any exist
                $docLinks = [];
                if (Schema::hasTable('invoice_attachments')) {
                    $attachments = DB::table('invoice_attachments')->where('invoice_id', $invoice->id)->pluck('file_path')->toArray();
                    foreach ($attachments as $path) {
                        $docLinks[] = $path; // Storing relative asset paths
                    }
                }

                // Check if the transaction exists to avoid foreign key violations
                $txnExists = DB::table('transactions')->where('id', $invoice->id)->exists();

                if ($txnExists) {
                    DB::table('project_details')->insert([
                        'transaction_id' => $invoice->id,
                        'technician_names' => $invoice->technician_names ?: 'Umum',
                        'cause_of_clog' => $invoice->cause_of_problem ?: '-',
                        'warranty_info' => $invoice->warranty ?: 'Tidak Ada Garansi',
                        'documentation_links' => !empty($docLinks) ? json_encode($docLinks) : null,
                        'ppn_percentage' => $ppnPercent,
                        'pph_percentage' => $pphPercent,
                        'ppn_amount' => $invoice->ppn,
                        'pph_amount' => $invoice->pph,
                        'created_at' => $invoice->created_at,
                        'updated_at' => $invoice->updated_at,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_details');
    }
};
