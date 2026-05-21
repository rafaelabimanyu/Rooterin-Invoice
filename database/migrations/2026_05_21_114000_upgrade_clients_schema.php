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
        Schema::table('clients', function (Blueprint $table) {
            // Modify client_type to be a flexible string instead of limited enum
            $table->string('client_type')->default('individual')->change();
            
            // Add industry_sector as a nullable string
            $table->string('industry_sector')->nullable()->default('general')->after('client_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('industry_sector');
            // Revert column back to enum if necessary (recreating old structure)
            $table->enum('client_type', ['perusahaan', 'rumahan'])->default('rumahan')->change();
        });
    }
};
