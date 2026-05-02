<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class AcademicEvent extends Model
{
    use HasFactory;

    public const TYPE_OPTIONS = [
        'Holiday',
        'Class Suspension',
        'No Classes',
        'University Event',
        'Department Activity',
    ];

    public const SCOPE_OPTIONS = [
        'all',
        'teachers',
        'faculty',
        'department',
    ];

    protected $fillable = [
        'title',
        'type',
        'start_datetime',
        'end_datetime',
        'scope',
        'department_id',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'start_datetime' => 'datetime',
            'end_datetime' => 'datetime',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function scopeActiveAt(Builder $query, Carbon $moment): Builder
    {
        return $query
            ->where('start_datetime', '<=', $moment)
            ->where('end_datetime', '>=', $moment);
    }

    public function scopeUpcomingFrom(Builder $query, Carbon $moment): Builder
    {
        return $query->where('start_datetime', '>', $moment);
    }

    public function getScopeLabelAttribute(): string
    {
        return match ($this->scope) {
            'all' => 'All teachers and faculty',
            'teachers' => 'Teachers only',
            'faculty' => 'Faculty only',
            'department' => $this->department?->name ? 'Department: ' . $this->department->name : 'Department only',
            default => ucfirst($this->scope),
        };
    }
}
