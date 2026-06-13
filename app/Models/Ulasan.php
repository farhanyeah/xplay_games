<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{

    protected $table = 'ulasan';

    protected $fillable = [
        'user_id',
        'rating',
        'pesan',
    ];

    // Relasi dengan User
    public  function user()
    {
        return $this->belongsTo(User::class);
    }
}
