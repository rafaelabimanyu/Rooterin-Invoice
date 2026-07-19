<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('kategori_invoice', ['reguler', 'kemitraan'])->default('reguler')->index()->after('status');
            $table->string('periode_kontrak')->nullable()->after('kategori_invoice');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['kategori_invoice', 'periode_kontrak']);
        });
    }
};
