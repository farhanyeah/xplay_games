<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billing_unit_id')->constrained('billing_units')->onDelete('restrict');
            $table->string('nama_customer');
            $table->integer('jumlah_jam');

            $table->foreignId('paket_harga_id')->nullable()->constrained('paket_harga_booking')->nullOnDelete();
            $table->foreignId('paket_khusus_id')->nullable()->constrained('paket_khusus_booking')->nullOnDelete();

            $table->bigInteger('harga_awal');
            $table->bigInteger('harga_final');

            $table->enum('metode_bayar', ['cash', 'midtrans']);
            $table->string('snap_token')->nullable();
            $table->enum('status_bayar', ['pending', 'paid'])->default('pending');

            $table->enum('status_sesi', ['available', 'active', 'completed'])->default('available');

            $table->timestamp('jam_mulai')->nullable();
            $table->timestamp('jam_selesai')->nullable();

            $table->timestamp('pause_at')->nullable();
            $table->integer('total_pause_menit')->default(0);

            $table->text('catatan')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};