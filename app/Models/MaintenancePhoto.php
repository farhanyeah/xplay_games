<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenancePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_report_id',
        'file_path',
    ];

    /*
    |----------------------------
    | RELATIONSHIP
    |----------------------------
    */

    // foto milik 1 maintenance
    public function report()
    {
        return $this->belongsTo(MaintenanceReport::class, 'maintenance_report_id');
    }
}