<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisUnitBooking extends Model
{
    protected $table = 'jenis_unit_booking';

    protected $fillable = [
        'tipe',
    ];

    public function units()
    {
        return $this->hasMany(UnitBooking::class, 'jenis_unit_id');
    }

    public function paketHarga()
    {
        return $this->hasMany(PaketHargaBooking::class, 'jenis_unit_id');
    }
}
