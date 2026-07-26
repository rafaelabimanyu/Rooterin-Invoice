<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Invoice;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite doesn't support easy column default modification, so we'll just migrate existing records.
        // Update all existing statuses that are not 'paid' to 'unpaid'
        Invoice::where('status', '!=', 'paid')->update(['status' => 'unpaid']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert all 'unpaid' statuses back to 'draft'
        Invoice::where('status', 'unpaid')->update(['status' => 'draft']);
    }
};
