<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'time_in',
        'time_in_latitude',
        'time_in_longitude',
        'time_in_accuracy_meters',
        'time_in_distance_meters',
        'time_in_location_verified',
        'time_in_location_status',
        'time_in_ip',
        'time_in_user_agent',
        'time_out',
        'forced_time_out_by',
        'forced_time_out_at',
        'force_time_out_reason',
    ];

    protected function casts(): array
    {
        return [
            'time_in' => 'datetime',
            'time_out' => 'datetime',
            'forced_time_out_at' => 'datetime',
            'time_in_latitude' => 'float',
            'time_in_longitude' => 'float',
            'time_in_accuracy_meters' => 'float',
            'time_in_distance_meters' => 'float',
            'time_in_location_verified' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function forcedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'forced_time_out_by');
    }
}



