<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    protected static function booted(): void
    {
        static::saved(function (self $user) {
            if (! in_array($user->role, ['admin', 'student', 'professor', 'faculty'], true)) {
                return;
            }

            if (! Schema::hasTable('roles') ||
                ! Schema::hasTable('model_has_roles')) {
                return;
            }

            Role::findOrCreate($user->role, 'web');
            $user->syncRoles([$user->role]);
        });
    }

    public static function snapshotStaff(int $limit = 3): Collection
    {
        $users = static::with('department')
            ->whereIn('role', ['professor', 'faculty'])
            ->where('is_suspended', false)
            ->orderBy('full_name')
            ->take($limit)
            ->get();

        return static::formatSnapshotStaff($users);
    }

    public static function snapshotTotals(): array
    {
        $users = static::with('department')
            ->whereIn('role', ['professor', 'faculty'])
            ->where('is_suspended', false)
            ->orderBy('full_name')
            ->get();

        $staff = static::formatSnapshotStaff($users);
        $available = $staff->where('status', 'Available')->count();
        $engaged = $staff->filter(
            fn ($staffMember) => in_array($staffMember['status'], ['In Class', 'On Meeting'], true)
        )->count();
        $activeContexts = $staff->pluck('context')
            ->filter(fn ($context) => filled($context) && $context !== 'No department assigned')
            ->unique()
            ->count();

        return [
            'professors' => $users->where('role', 'professor')->count(),
            'faculty' => $users->where('role', 'faculty')->count(),
            'tracked' => $users->count(),
            'available' => $available,
            'engaged' => $engaged,
            'attention' => max($users->count() - $available - $engaged, 0),
            'active_contexts' => $activeContexts,
        ];
    }

    public static function availableProfessorSnapshot(): Collection
    {
        $professors = static::with('department')
            ->where('role', 'professor')
            ->where('is_suspended', false)
            ->orderBy('full_name')
            ->get();

        return static::formatSnapshotStaff($professors)
            ->where('status', 'Available')
            ->values();
    }

    protected static function formatSnapshotStaff(Collection $users): Collection
    {
        return $users->map(function (self $user) {
            $statusData = $user->live_status;
            $displayName = $user->full_name ?: $user->username;
            $nameParts = collect(preg_split('/\s+/', trim($displayName)) ?: [])->filter();

            return [
                'name' => $displayName,
                'role' => $user->role,
                'role_label' => ucfirst($user->role),
                'department' => $user->department?->name,
                'profile_picture_url' => $user->profile_picture ? asset('storage/'.$user->profile_picture) : null,
                'status' => $statusData['status'],
                'subject' => $statusData['subject'],
                'room' => $statusData['room'],
                'initials' => $nameParts->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('') ?: 'TT',
                'meta' => collect([ucfirst($user->role), $user->department?->name])->filter()->implode(' | '),
                'context' => collect([$statusData['subject'], $statusData['room']])->filter()->implode(' | ')
                    ?: ($user->department?->name ? $user->department->name.' Department' : 'No department assigned'),
            ];
        });
    }

    public function getLiveStatusAttribute()
    {
        $now = Carbon::now();

        $special = $this->specialSchedules()
            ->where('start_datetime', '<=', $now)
            ->where('end_datetime', '>=', $now)
            ->latest()
            ->first();

        if ($special) {
            $specialScheduleClass = $this->scheduleForSpecialScheduleExtension($special);

            return [
                'status' => $special->type,
                'subject' => null,
                'room' => null,
                'source' => 'special_schedule',
                'event_type' => null,
                'event_note' => null,
                'event_purpose' => null,
                'status_start_datetime' => $special->start_datetime,
                'status_end_datetime' => $this->resolvedSpecialStatusEndDatetime($special, $specialScheduleClass),
                'class_start_time' => $specialScheduleClass?->start_time,
                'class_end_time' => $specialScheduleClass?->end_time,
            ];
        }

        $override = $this->statusOverrides()
            ->where('start_datetime', '<=', $now)
            ->where('end_datetime', '>=', $now)
            ->latest()
            ->first();

        if ($override) {
            return [
                'status' => $override->status,
                'subject' => null,
                'room' => null,
                'source' => 'admin_override',
                'event_type' => null,
                'event_note' => null,
                'event_purpose' => null,
                'status_start_datetime' => $override->start_datetime,
                'status_end_datetime' => $override->end_datetime,
                'class_start_time' => null,
                'class_end_time' => null,
            ];
        }

        $academicEvent = $this->activeAcademicEvent($now);

        // Holidays remain visible campus-wide even when staff have not timed in.
        if ($academicEvent?->type === 'Holiday') {
            return $this->academicEventStatus($academicEvent);
        }

        if (in_array($this->role, ['professor', 'faculty'], true) && ! $this->isTimedIn($now)) {
            return [
                'status' => 'Not Available',
                'subject' => null,
                'room' => null,
                'source' => 'attendance',
                'event_type' => null,
                'event_note' => null,
                'event_purpose' => null,
                'status_start_datetime' => null,
                'status_end_datetime' => null,
                'class_start_time' => null,
                'class_end_time' => null,
            ];
        }

        if ($academicEvent) {
            return $this->academicEventStatus($academicEvent);
        }

        if ($this->role === 'professor') {
            $currentDay = $now->format('l');
            $currentTime = $now->format('H:i:s');

            $schedule = $this->schedules()
                ->where('day_of_week', $currentDay)
                ->whereTime('start_time', '<=', $currentTime)
                ->whereTime('end_time', '>=', $currentTime)
                ->first();

            if ($schedule) {
                $carriedSpecial = $this->carriedSpecialScheduleFor($schedule, $now);

                if ($carriedSpecial) {
                    return [
                        'status' => $carriedSpecial->type,
                        'subject' => null,
                        'room' => null,
                        'source' => 'special_schedule_extended',
                        'event_type' => null,
                        'event_note' => $carriedSpecial->note,
                        'event_purpose' => null,
                        'status_start_datetime' => $carriedSpecial->start_datetime,
                        'status_end_datetime' => $this->resolvedSpecialStatusEndDatetime($carriedSpecial, $schedule),
                        'class_start_time' => $schedule->start_time,
                        'class_end_time' => $schedule->end_time,
                    ];
                }

                return [
                    'status' => 'In Class',
                    'subject' => $schedule->subject,
                    'room' => $schedule->room,
                    'source' => 'weekly_schedule',
                    'event_type' => null,
                    'event_note' => null,
                    'event_purpose' => null,
                    'status_start_datetime' => null,
                    'status_end_datetime' => null,
                    'class_start_time' => $schedule->start_time,
                    'class_end_time' => $schedule->end_time,
                ];
            }
        }

        return [
            'status' => 'Available',
            'subject' => null,
            'room' => null,
            'source' => 'default',
            'event_type' => null,
            'event_note' => null,
            'event_purpose' => null,
            'status_start_datetime' => null,
            'status_end_datetime' => null,
            'class_start_time' => null,
            'class_end_time' => null,
        ];
    }

    protected function academicEventStatus(AcademicEvent $academicEvent): array
    {
        return [
            'status' => $academicEvent->title ?: $academicEvent->type,
            'subject' => null,
            'room' => null,
            'source' => 'academic_event',
            'event_type' => $academicEvent->type,
            'event_note' => $academicEvent->note,
            'event_purpose' => $academicEvent->purpose,
            'status_start_datetime' => null,
            'status_end_datetime' => null,
            'class_start_time' => null,
            'class_end_time' => null,
        ];
    }

    protected function carriedSpecialScheduleFor(Schedule $schedule, Carbon $moment): ?SpecialSchedule
    {
        $scheduleStart = $moment->copy()->setTimeFromTimeString($schedule->start_time);
        $scheduleEnd = $moment->copy()->setTimeFromTimeString($schedule->end_time);

        return $this->specialSchedules()
            ->where('keep_until_schedule_end', true)
            ->where('start_datetime', '<', $scheduleEnd)
            ->where('end_datetime', '>', $scheduleStart)
            ->where('end_datetime', '<', $moment)
            ->latest('end_datetime')
            ->first();
    }

    protected function scheduleForSpecialScheduleExtension(SpecialSchedule $special): ?Schedule
    {
        if ($this->role !== 'professor' || $special->type !== 'On Meeting' || ! $special->keep_until_schedule_end) {
            return null;
        }

        $startMoment = Carbon::parse($special->start_datetime);
        $endMoment = Carbon::parse($special->end_datetime);

        return $this->schedules()
            ->where('day_of_week', $endMoment->format('l'))
            ->whereTime('start_time', '<=', $endMoment->format('H:i:s'))
            ->whereTime('end_time', '>', $endMoment->format('H:i:s'))
            ->whereTime('end_time', '>', $startMoment->format('H:i:s'))
            ->orderBy('end_time')
            ->first();
    }

    protected function resolvedSpecialStatusEndDatetime(SpecialSchedule $special, ?Schedule $schedule = null): string
    {
        $endMoment = Carbon::parse($special->end_datetime);

        if (! $schedule) {
            return $endMoment->toDateTimeString();
        }

        $scheduleEnd = $endMoment->copy()->setTimeFromTimeString($schedule->end_time);

        return $scheduleEnd->greaterThan($endMoment)
            ? $scheduleEnd->toDateTimeString()
            : $endMoment->toDateTimeString();
    }

    public function activeAcademicEvent(?Carbon $moment = null): ?AcademicEvent
    {
        if (! in_array($this->role, ['professor', 'faculty'], true)) {
            return null;
        }

        $moment ??= Carbon::now();

        return AcademicEvent::query()
            ->activeAt($moment)
            ->where(function ($query) {
                $query->where('scope', 'all');

                if ($this->role === 'professor') {
                    $query->orWhere('scope', 'professors');
                }

                if ($this->role === 'faculty') {
                    $query->orWhere('scope', 'faculty');
                }

                if ($this->department_id) {
                    $query->orWhere(function ($departmentQuery) {
                        $departmentQuery
                            ->where('scope', 'department')
                            ->where('department_id', $this->department_id);
                    });
                }
            })
            ->orderByDesc('start_datetime')
            ->orderByDesc('id')
            ->first();
    }

    public function statusOverrides()
    {
        return $this->hasMany(StatusOverride::class);
    }

    public function specialSchedules()
    {
        return $this->hasMany(SpecialSchedule::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function currentAttendance(?Carbon $moment = null): ?AttendanceRecord
    {
        $moment ??= Carbon::now();

        return $this->attendanceRecords()
            ->where('time_in', '<=', $moment)
            ->where(function ($query) use ($moment) {
                $query->whereNull('time_out')
                    ->orWhere('time_out', '>', $moment);
            })
            ->latest('time_in')
            ->first();
    }

    public function isTimedIn(?Carbon $moment = null): bool
    {
        return filled($this->currentAttendance($moment));
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function suspendedBy(): BelongsTo
    {
        return $this->belongsTo(self::class, 'suspended_by');
    }

    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'full_name',
        'username',
        'student_id',
        'email',
        'password',
        'role',
        'is_suspended',
        'suspended_at',
        'suspended_by',
        'department_id',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_suspended' => 'boolean',
            'suspended_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
