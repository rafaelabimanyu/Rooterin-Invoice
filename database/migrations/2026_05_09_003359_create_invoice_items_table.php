<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $blueprint->string('deskripsi');
            $blueprint->decimal('qty', 10, 2);
            $blueprint->decimal('harga', 15, 2);
            $blueprint->decimal('total', 15, 2);
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
