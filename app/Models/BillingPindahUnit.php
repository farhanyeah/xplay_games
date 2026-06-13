<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingPindahUnit extends Model
{

    protected $table = 'billing_pindah_unit';
    protected $fillable = [
        'billing_id',
        'dari_unit_id',
        'ke_unit_id',
        'alasan',
        'created_by',
        'updated_by',
    ];

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }

    public function dariUnit()
    {
        return $this->belongsTo(BillingUnit::class, 'dari_unit_id');
    }

    public function keUnit()
    {
        return $this->belongsTo(BillingUnit::class, 'ke_unit_id');
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