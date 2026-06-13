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
        Schema::create('paket_harga_sewa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_unit_id')->constrained('jenis_unit')->onDelete('cascade');
            $table->integer('durasi_hari');
            $table->bigInteger('harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_harga_sewa');
    }
};
