<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('kode_client')->unique();
            $blueprint->string('nama_client');
            $blueprint->string('nama_perusahaan')->nullable();
            $blueprint->string('email')->nullable();
            $blueprint->string('no_hp')->nullable();
            $blueprint->string('npwp')->nullable();
            $blueprint->text('alamat')->nullable();
            $blueprint->string('kota')->nullable();
            $blueprint->string('provinsi')->nullable();
            $blueprint->text('catatan')->nullable();
            $blueprint->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
