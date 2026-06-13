<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketKhususBooking extends Model
{
    protected $table = 'paket_khusus_booking';

    protected $fillable = [
        'jenis_unit_id',
        'nama_paket',
        'jumlah_jam',
        'harga',
        'hari_berlaku',
        'jam_mulai_berlaku',
        'jam_selesai_berlaku',
    ];

    protected $casts = [
        'hari_berlaku' => 'array',
    ];
    
    public function jenisUnit()
    {
        return $this->belongsTo(JenisUnitBooking::class, 'jenis_unit_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'paket_khusus_id');
    }
}