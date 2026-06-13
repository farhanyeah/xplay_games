<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $table = 'bookings';

    protected $fillable = [
        'transaction_code',
        'user_id',
        'created_by',
        'updated_by',
        'unit_id',
        'paket_id',
        'paket_khusus_id',
        'nama',
        'no_hp',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'jumlah_jam',
        'harga',
        'pembayaran',
        'status_booking',
        'payment_status',
        'midtrans_token',
        'midtrans_order_id',
    ];

    public function unit()
    {
        return $this->belongsTo(UnitBooking::class, 'unit_id');
    }

    public function paket()
    {
        return $this->belongsTo(PaketHargaBooking::class, 'paket_id');
    }

    public function paketKhusus()
    {
        return $this->belongsTo(PaketKhususBooking::class, 'paket_khusus_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}