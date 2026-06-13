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
        Schema::create('paket_khusus_booking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_unit_id')->constrained('jenis_unit_booking')->onDelete('cascade');
            $table->string('nama_paket');
            $table->integer('jumlah_jam');
            $table->bigInteger('harga');
            $table->json('hari_berlaku')->nullable();
            $table->time('jam_mulai_berlaku');
            $table->time('jam_selesai_berlaku');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_khusus_booking');
    }
};
