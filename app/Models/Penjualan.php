<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';

    protected $fillable = [
        'transaction_code',
        'created_by',
        'total_harga',
        'metode_pembayaran',
        'payment_status',
        'midtrans_token',
        'midtrans_order_id',
    ];

    public function items()
    {
        return $this->hasMany(PenjualanItem::class, 'penjualan_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}