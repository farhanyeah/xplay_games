<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = [
        'billing_unit_id',
        'nama_customer',
        'jumlah_jam',
        'paket_harga_id',
        'paket_khusus_id',
        'harga_awal',
        'harga_final',
        'metode_bayar',

        'midtrans_order_id',
        'midtrans_token',

        'status_bayar',
        'status_sesi',
        'jam_mulai',
        'jam_selesai',
        'pause_at',
        'total_pause_menit',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'jam_mulai'  => 'datetime',
        'jam_selesai' => 'datetime',
        'pause_at'   => 'datetime',
    ];

    public function unit()
    {
        return $this->belongsTo(BillingUnit::class, 'billing_unit_id');
    }

    public function paketHarga()
    {
        return $this->belongsTo(PaketHargaBooking::class, 'paket_harga_id');
    }

    public function paketKhusus()
    {
        return $this->belongsTo(PaketKhususBooking::class, 'paket_khusus_id');
    }

    public function extends()
    {
        return $this->hasMany(BillingExtend::class, 'billing_id');
    }

    public function refunds()
    {
        return $this->hasMany(BillingRefund::class, 'billing_id');
    }

    public function pindahUnit()
    {
        return $this->hasMany(BillingPindahUnit::class, 'billing_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getSisaDetikAttribute()
    {
        if (!$this->jam_selesai) {
            return null;
        }

        return now()->diffInSeconds(
            $this->jam_selesai,
            false
        );
    }

    public function getWarnaStatusAttribute()
    {
        if (!$this->jam_selesai) {
            return 'gray';
        }

        $sisaMenit = now()->diffInMinutes(
            $this->jam_selesai,
            false
        );

        if ($sisaMenit <= 0) {
            return 'danger';
        }

        if ($sisaMenit <= 5) {
            return 'warning';
        }

        return 'success';
    }
}