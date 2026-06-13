<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $table = 'stok';

    protected $fillable = [
        'nama',
        'kategori',
        'harga',
        'stok',
        'satuan',
    ];
}