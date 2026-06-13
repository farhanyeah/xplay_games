<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('kategori', ['makanan', 'minuman', 'lainnya']);
            $table->bigInteger('harga');
            $table->integer('stok')->default(0);
            $table->enum('satuan', ['pcs', 'botol', 'kaleng', 'bungkus', 'pak']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok');
    }
};