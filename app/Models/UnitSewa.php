<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitSewa extends Model
{
    protected $table = 'units_sewa';

    protected $fillable = [
        'jenis_unit_id',
        'kode_unit',
        'status',
    ];

    public function jenisUnit()
    {
        return $this->belongsTo(JenisUnit::class, 'jenis_unit_id');
    }

    public function sewas()
    {
        return $this->hasMany(Sewa::class, 'unit_id');
    }
}
