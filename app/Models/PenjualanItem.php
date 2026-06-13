<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanItem extends Model
{
    protected $table = 'penjualan_items';

    protected $fillable = [
        'penjualan_id',
        'stok_id',
        'nama_item',
        'harga',
        'jumlah',
        'subtotal',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function stok()
    {
        return $this->belongsTo(Stok::class, 'stok_id');
    }
}