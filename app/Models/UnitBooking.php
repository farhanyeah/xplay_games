<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitBooking extends Model
{
    protected $table = 'units_booking';

    protected $fillable = [
        'jenis_unit_id',
        'kode_unit',
    ];

    public function jenisUnit() 
    {
        return $this->belongsTo(JenisUnitBooking::class, 'jenis_unit_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'unit_id');
    }
}
