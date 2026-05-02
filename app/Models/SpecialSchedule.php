<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'start_datetime',
        'end_datetime',
        'note',
        'keep_until_schedule_end',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
