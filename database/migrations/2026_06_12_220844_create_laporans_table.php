<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->decimal('pendapatan_billing', 15, 2)->default(0);
            $table->decimal('pendapatan_sewa', 15, 2)->default(0);
            $table->decimal('pendapatan_booking', 15, 2)->default(0);
            $table->decimal('pendapatan_penjualan', 15, 2)->default(0);
            $table->decimal('total_pendapatan', 15, 2)->default(0);
            $table->decimal('pengeluaran_part_time', 15, 2)->default(0);
            $table->decimal('pengeluaran_gestun', 15, 2)->default(0);
            $table->decimal('pengeluaran_lain', 15, 2)->default(0);
            $table->string('keterangan_pengeluaran')->nullable();
            $table->decimal('saldo_midtrans', 15, 2)->default(0);
            $table->decimal('buka_kas', 15, 2)->default(0);
            $table->decimal('tutup_kas', 15, 2)->default(0);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporans');
    }
};