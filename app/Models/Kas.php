<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    protected $fillable = [
        'tanggal',
        'saldo_awal',
        'saldo_akhir',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}