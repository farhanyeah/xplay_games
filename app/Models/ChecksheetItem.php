<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChecksheetItem extends Model
{
    protected $fillable = [
        'name',
        'frequency',
        'shift',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(ChecksheetDetail::class);
    }
}