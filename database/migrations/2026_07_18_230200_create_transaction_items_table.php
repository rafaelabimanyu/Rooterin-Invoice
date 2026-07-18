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
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->string('description');
            $table->decimal('qty', 10, 2);
            $table->decimal('price', 15, 2);
            $table->decimal('total', 15, 2);
            $table->timestamps();
        });

        // Migrate line items from existing invoice_items
        if (Schema::hasTable('invoice_items')) {
            $items = DB::table('invoice_items')->get();
            foreach ($items as $item) {
                // Check if the transaction exists to avoid foreign key violations
                $txnExists = DB::table('transactions')->where('id', $item->invoice_id)->exists();

                if ($txnExists) {
                    DB::table('transaction_items')->insert([
                        'id' => $item->id,
                        'transaction_id' => $item->invoice_id,
                        'description' => $item->deskripsi,
                        'qty' => $item->qty,
                        'price' => $item->harga,
                        'total' => $item->total,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
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
        Schema::dropIfExists('transaction_items');
    }
};
