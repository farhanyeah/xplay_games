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
        Schema::create('sewa', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullonDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('unit_id')->constrained('units_sewa')->onDelete('cascade');
            $table->foreignId('paket_id')->nullable()->constrained('paket_harga_sewa')->nullOnDelete();
            $table->string('nama');
            $table->string('no_hp');
            $table->text('alamat');
            $table->enum('guarantee_type', ['KTP', 'SIM', 'KTM', 'Lainnya']);
            $table->string('guarantee_other')->nullable();
            $table->integer('durasi_custom')->nullable();
            $table->datetime('tanggal_mulai');
            $table->datetime('tanggal_selesai');
            $table->bigInteger('harga_sewa');
            $table->bigInteger('harga_jaminan');
            $table->bigInteger('total_harga');
            $table->enum('pembayaran', ['cash', 'midtrans']);
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->enum('status_sewa', ['pending', 'disewa', 'extended', 'completed', 'cancelled'])->default('pending');
            $table->bigInteger('jaminan_balik')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sewa');
    }
};
