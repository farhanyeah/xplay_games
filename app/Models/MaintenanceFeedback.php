<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenanceFeedback extends Model
{
    use HasFactory;

    protected $table = 'maintenance_feedback';

    protected $fillable = [
        'maintenance_report_id',
        'feedback',
        'created_by',
    ];

    /*
    |----------------------------
    | RELATIONSHIP
    |----------------------------
    */

    // feedback milik 1 maintenance
    public function report()
    {
        return $this->belongsTo(MaintenanceReport::class, 'maintenance_report_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}