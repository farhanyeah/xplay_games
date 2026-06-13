<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingExtend extends Model
{
    protected $fillable = [
        'billing_id',
        'jumlah_jam_tambah',
        'harga_tambah',
        'metode_bayar',
        'snap_token',
        'status_bayar',
        'created_by',
        'updated_by',
        'midtrans_order_id',
        'midtrans_token',
    ];

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}