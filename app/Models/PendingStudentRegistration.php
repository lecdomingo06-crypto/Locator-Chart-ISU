<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendingStudentRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'full_name',
        'email',
        'department_id',
        'password',
        'status',
        'reviewed_by',
        'reviewed_at',
        'decline_reason',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
