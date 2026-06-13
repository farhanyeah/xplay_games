<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenanceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'created_by',
        'status',
        'resolved_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    /*
    |----------------------------
    | RELATIONSHIPS
    |----------------------------
    */

    // 1 maintenance punya banyak foto
    public function photos()
    {
        return $this->hasMany(MaintenancePhoto::class, 'maintenance_report_id');
    }

    // 1 maintenance hanya punya 1 feedback owner
    public function feedback()
    {
        return $this->hasOne(MaintenanceFeedback::class, 'maintenance_report_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}