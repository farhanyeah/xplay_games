<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TambahDurasi extends Model
{
    protected $table = 'tambah_durasi';

    protected $fillable = [
        'sewa_id',
        'created_by',
        'tambah_hari',
        'harga_tambah',
        'payment_status',
        'midtrans_token',
        'midtrans_order_id',
    ];

    public function sewa()
    {
        return $this->belongsTo(Sewa::class, 'sewa_id');
    }
}
