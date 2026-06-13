<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = [
        'tanggal',
        'pendapatan_billing',
        'pendapatan_sewa',
        'pendapatan_booking',
        'pendapatan_penjualan',
        'total_pendapatan',
        'pengeluaran_part_time',
        'pengeluaran_gestun',
        'pengeluaran_lain',
        'keterangan_pengeluaran',
        'saldo_midtrans',
        'buka_kas',
        'tutup_kas',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}