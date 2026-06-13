<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sewa extends Model
{
    use SoftDeletes;

    protected $table = 'sewa';

    protected $fillable = [
        'transaction_code',
        'user_id',
        'created_by',
        'updated_by',
        'unit_id',
        'paket_id',
        'nama',
        'no_hp',
        'alamat',
        'guarantee_type',
        'guarantee_other',
        'durasi_custom',
        'tanggal_mulai',
        'tanggal_selesai',
        'harga_sewa',
        'harga_jaminan',
        'jaminan_balik',
        'keterangan',
        'total_harga',
        'pembayaran',
        'payment_status',
        'status_sewa',
        'midtrans_token',
        'midtrans_order_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unitSewa()
    {
        return $this->belongsTo(UnitSewa::class, 'unit_id');
    }

    public function paket()
    {
        return $this->belongsTo(PaketHargaSewa::class, 'paket_id');
    }

    public function tambahDurasi()
    {
        return $this->hasMany(TambahDurasi::class, 'sewa_id');
    }
}