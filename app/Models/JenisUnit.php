<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisUnit extends Model
{
    protected $table = 'jenis_unit';
    
    protected $fillable = ['tipe'];

    public function unitsSewa()
    {
        return $this->hasMany(UnitSewa::class, 'jenis_unit_id');
    }

    public function paketHarga()
    {
        return $this->hasMany(PaketHargaSewa::class, 'jenis_unit_id');
    }
}
