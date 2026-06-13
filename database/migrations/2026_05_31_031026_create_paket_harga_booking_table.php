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
        Schema::create('paket_harga_booking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_unit_id')->constrained('jenis_unit_booking')->onDelete('cascade');
            $table->integer('jumlah_jam');
            $table->bigInteger('harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_harga_booking');
    }
};
