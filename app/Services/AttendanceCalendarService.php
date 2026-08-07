<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\SpecialSchedule;
use App\Models\StatusOverride;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class AttendanceCalendarService
{
    private const EXCUSED_SPECIAL_TYPES = ['On Leave', 'Emergency', 'On Meeting'];

    private const EXCUSED_ADMIN_STATUSES = ['Emergency', 'On Meeting'];

    public function aggregateDays(Collection $staff, Carbon $rangeStart, Carbon $rangeEnd, Carbon $now): Collection
    {
        return collect(CarbonPeriod::create($rangeStart, $rangeEnd->copy()->subSecond()))
            ->map(function (Carbon $date) use ($staff, $now) {
                $states = $staff
                    ->map(fn (User $user) => $this->stateForDate($user, $date, $user->attendanceRecords, $now))
                    ->countBy();

                return [
                    'date' => $date->copy(),
                    'present' => (int) ($states->get('present') ?? 0),
                    'absent' => (int) ($states->get('absent') ?? 0),
                    'pending' => (int) ($states->get('pending') ?? 0),
                    'excused' => (int) ($states->get('excused') ?? 0),
                    'no_class' => (int) ($states->get('no_class') ?? 0),
                ];
            })
            ->values();
    }

    public function daysForUser(User $user, Carbon $rangeStart, Carbon $rangeEnd, Carbon $now, ?Collection $records = null): Collection
    {
        $records ??= $user->attendanceRecords()
            ->where('time_in', '<', $rangeEnd)
            ->where(function ($query) use ($rangeStart) {
                $query
                    ->whereNull('time_out')
                    ->orWhere('time_out', '>=', $rangeStart);
            })
            ->orderBy('time_in')
            ->get();

        return collect(CarbonPeriod::create($rangeStart, $rangeEnd->copy()->subSecond()))
            ->map(fn (Carbon $date) => $this->dayForUser($user, $date, $records, $now))
            ->values();
    }

    public function dayForUser(User $user, Carbon $date, Collection $records, Carbon $now): array
    {
        $dayStart = $date->copy()->startOfDay();
        $dayEnd = $dayStart->copy()->addDay();
        $dayRecords = $this->recordsForDate($records, $dayStart, $dayEnd, $now);
        $state = $this->stateForDate($user, $dayStart, $records, $now);

        return [
            'date' => $dayStart,
            'records' => $dayRecords,
            'minutes' => $dayRecords->sum(fn (AttendanceRecord $attendance) => $this->renderedMinutes($attendance, $dayStart, $dayEnd, $now)),
            'state' => $state,
            'label' => $this->label($state),
        ];
    }

    public function stateForDate(User $user, Carbon $date, Collection $records, Carbon $now): string
    {
        $dayStart = $date->copy()->startOfDay();
        $dayEnd = $dayStart->copy()->addDay();

        if ($this->recordsForDate($records, $dayStart, $dayEnd, $now)->isNotEmpty()) {
            return 'present';
        }

        if ($this->hasExcusedStatus($user, $dayStart, $dayEnd)) {
            return 'excused';
        }

        if (! $this->isAttendanceExpected($user, $dayStart)) {
            return 'no_class';
        }

        return $this->attendanceCutoffPassed($dayStart, $now) ? 'absent' : 'pending';
    }

    public function label(string $state): string
    {
        return match ($state) {
            'present' => 'Present',
            'absent' => 'Absent',
            'excused' => 'Excused',
            'no_class' => 'No Class',
            default => 'Pending',
        };
    }

    public function attendanceCutoffPassed(Carbon $date, Carbon $now): bool
    {
        if ($date->isBefore($now->copy()->startOfDay())) {
            return true;
        }

        return $date->isSameDay($now) && $now->greaterThanOrEqualTo($date->copy()->setTime(17, 0));
    }

    private function recordsForDate(Collection $records, Carbon $dayStart, Carbon $dayEnd, Carbon $now): Collection
    {
        return $records
            ->filter(function (AttendanceRecord $attendance) use ($dayStart, $dayEnd, $now) {
                $attendanceEnd = $attendance->time_out ?? $now;

                return $attendance->time_in->lessThan($dayEnd)
                    && $attendanceEnd->greaterThanOrEqualTo($dayStart);
            })
            ->values();
    }

    private function isAttendanceExpected(User $user, Carbon $date): bool
    {
        if (! $date->isWeekend()) {
            return true;
        }

        return $this->hasWeeklyScheduleOn($user, $date);
    }

    private function hasWeeklyScheduleOn(User $user, Carbon $date): bool
    {
        $day = $date->format('l');

        if ($user->relationLoaded('schedules')) {
            return $user->schedules->contains('day_of_week', $day);
        }

        return $user->schedules()
            ->where('day_of_week', $day)
            ->exists();
    }

    private function hasExcusedStatus(User $user, Carbon $dayStart, Carbon $dayEnd): bool
    {
        return $this->hasOverlappingSpecialSchedule($user, $dayStart, $dayEnd)
            || $this->hasOverlappingStatusOverride($user, $dayStart, $dayEnd);
    }

    private function hasOverlappingSpecialSchedule(User $user, Carbon $dayStart, Carbon $dayEnd): bool
    {
        if ($user->relationLoaded('specialSchedules')) {
            return $user->specialSchedules
                ->contains(fn (SpecialSchedule $schedule) => in_array($schedule->type, self::EXCUSED_SPECIAL_TYPES, true)
                    && Carbon::parse($schedule->start_datetime)->lessThan($dayEnd)
                    && Carbon::parse($schedule->end_datetime)->greaterThanOrEqualTo($dayStart));
        }

        return $user->specialSchedules()
            ->whereIn('type', self::EXCUSED_SPECIAL_TYPES)
            ->where('start_datetime', '<', $dayEnd)
            ->where('end_datetime', '>=', $dayStart)
            ->exists();
    }

    private function hasOverlappingStatusOverride(User $user, Carbon $dayStart, Carbon $dayEnd): bool
    {
        if ($user->relationLoaded('statusOverrides')) {
            return $user->statusOverrides
                ->contains(fn (StatusOverride $override) => in_array($override->status, self::EXCUSED_ADMIN_STATUSES, true)
                    && Carbon::parse($override->start_datetime)->lessThan($dayEnd)
                    && Carbon::parse($override->end_datetime)->greaterThanOrEqualTo($dayStart));
        }

        return $user->statusOverrides()
            ->whereIn('status', self::EXCUSED_ADMIN_STATUSES)
            ->where('start_datetime', '<', $dayEnd)
            ->where('end_datetime', '>=', $dayStart)
            ->exists();
    }

    private function renderedMinutes(AttendanceRecord $attendance, Carbon $rangeStart, Carbon $rangeEnd, Carbon $now): int
    {
        $start = $attendance->time_in->greaterThan($rangeStart) ? $attendance->time_in : $rangeStart;
        $rawEnd = $attendance->time_out ?? $now;
        $end = $rawEnd->lessThan($rangeEnd) ? $rawEnd : $rangeEnd;

        if ($end->lessThanOrEqualTo($start)) {
            return 0;
        }

        return (int) $start->diffInMinutes($end);
    }
}
