<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'start_datetime',
        'end_datetime',
        'set_by_admin_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'set_by_admin_id');
    }
}