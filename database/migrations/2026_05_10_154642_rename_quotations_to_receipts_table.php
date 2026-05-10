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
        Schema::rename('quotations', 'receipts');
        Schema::rename('quotation_items', 'receipt_items');

        Schema::table('receipts', function (Blueprint $table) {
            $table->renameColumn('quotation_number', 'receipt_number');
            $table->renameColumn('tanggal_quotation', 'tanggal_receipt');
        });

        Schema::table('receipt_items', function (Blueprint $table) {
            $table->renameColumn('quotation_id', 'receipt_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipt_items', function (Blueprint $table) {
            $table->renameColumn('receipt_id', 'quotation_id');
        });

        Schema::table('receipts', function (Blueprint $table) {
            $table->renameColumn('receipt_number', 'quotation_number');
            $table->renameColumn('tanggal_receipt', 'tanggal_quotation');
        });

        Schema::rename('receipt_items', 'quotation_items');
        Schema::rename('receipts', 'quotations');
    }
};
