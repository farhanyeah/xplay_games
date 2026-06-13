<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecksheetDetail extends Model
{
    protected $fillable = [
        'checksheet_header_id',
        'checksheet_item_id',
        'status',
    ];

    public function header(): BelongsTo
    {
        return $this->belongsTo(ChecksheetHeader::class, 'checksheet_header_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ChecksheetItem::class, 'checksheet_item_id');
    }
}