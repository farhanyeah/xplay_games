<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketHargaBooking extends Model
{
    protected $table = 'paket_harga_booking';

    protected $fillable = [
        'jenis_unit_id',
        'jumlah_jam',
        'harga',
    ];

    public function jenisUnit()
    {
        return $this->belongsTo(JenisUnitBooking::class, 'jenis_unit_id');
    }

     public function bookings()
    {
        return $this->hasMany(Booking::class, 'paket_id');
    }
}
