<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketHargaSewa extends Model
{
    protected $table = 'paket_harga_sewa';

    protected $fillable = [
        'jenis_unit_id',
        'durasi_hari',
        'harga',
    ];

    public function jenisUnit()
    {
        return $this->belongsTo(JenisUnit::class, 'jenis_unit_id');
    }

    public function sewa()
    {
        return $this->hasMany(Sewa::class, 'paket_id');
    }
}
