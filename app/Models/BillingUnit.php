<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingUnit extends Model
{
    protected $fillable = [
        'lantai',
        'nama_unit',
        'jenis_unit_id',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function jenisUnit()
    {
        return $this->belongsTo(JenisUnitBooking::class, 'jenis_unit_id');
    }

    public function billings()
    {
        return $this->hasMany(Billing::class, 'billing_unit_id');
    }

    public function activeBilling()
    {
        return $this->hasOne(Billing::class, 'billing_unit_id')
            ->whereIn('status_sesi', ['available', 'active'])
            ->latestOfMany();
    }
}