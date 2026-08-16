<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Department;
use App\Models\User;
use App\Services\AttendanceCalendarService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminAttendanceController extends Controller
{
    private const STAFF_ROLES = ['professor', 'faculty'];

    public function __construct(private AttendanceCalendarService $attendanceCalendar)
    {
    }

    public function index(Request $request): View
    {
        AttendanceRecord::autoTimeOutExpiredOpenSessions();

        $filters = $request->validate([
            'period' => ['nullable', Rule::in(['daily', 'weekly', 'monthly'])],
            'date' => ['nullable', 'date'],
            'role' => ['nullable', Rule::in(self::STAFF_ROLES)],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $period = $filters['period'] ?? (isset($filters['date']) ? 'daily' : 'monthly');
        $anchor = Carbon::parse($filters['date'] ?? now())->startOfDay();
        [$rangeStart, $rangeEnd] = $this->reportRange($period, $anchor);
        $now = now();
        $todayStart = $now->copy()->startOfDay();
        $todayEnd = $todayStart->copy()->addDay();
        $statusLoadStart = $rangeStart->lessThan($todayStart) ? $rangeStart : $todayStart;
        $statusLoadEnd = $rangeEnd->greaterThan($todayEnd) ? $rangeEnd : $todayEnd;

        $staff = User::query()
            ->whereIn('role', self::STAFF_ROLES)
            ->where('is_suspended', false)
            ->with('department')
            ->with('schedules')
            ->with(['attendanceRecords' => function ($query) use ($rangeStart, $rangeEnd) {
                $query
                    ->where('time_in', '<', $rangeEnd)
                    ->where(function ($attendanceQuery) use ($rangeStart) {
                        $attendanceQuery
                            ->whereNull('time_out')
                            ->orWhere('time_out', '>=', $rangeStart);
                    })
                    ->orderBy('time_in');
            }])
            ->with(['specialSchedules' => function ($query) use ($statusLoadStart, $statusLoadEnd) {
                $query
                    ->where('start_datetime', '<', $statusLoadEnd)
                    ->where('end_datetime', '>=', $statusLoadStart);
            }])
            ->with(['statusOverrides' => function ($query) use ($statusLoadStart, $statusLoadEnd) {
                $query
                    ->where('start_datetime', '<', $statusLoadEnd)
                    ->where('end_datetime', '>=', $statusLoadStart);
            }])
            ->when($filters['role'] ?? null, fn ($query, $role) => $query->where('role', $role))
            ->when($filters['department_id'] ?? null, fn ($query, $departmentId) => $query->where('department_id', $departmentId))
            ->when($filters['q'] ?? null, function ($query, string $search) {
                $search = trim($search);

                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery
                        ->where('full_name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('full_name')
            ->get();

        $staffIds = $staff->pluck('id');
        $openAttendances = AttendanceRecord::query()
            ->with(['user.department'])
            ->whereIn('user_id', $staffIds)
            ->whereNull('time_out')
            ->latest('time_in')
            ->get();

        $forgotAttendances = $openAttendances
            ->filter(fn (AttendanceRecord $attendance) => $this->forgotToTimeOut($attendance, $now))
            ->values();

        $reportRows = $staff
            ->map(fn (User $user) => $this->reportRow($user, $rangeStart, $rangeEnd, $period, $now))
            ->values();

        $calendarDays = $this->attendanceCalendar->aggregateDays($staff, $rangeStart, $rangeEnd, $now);

        $presentToday = AttendanceRecord::query()
            ->whereIn('user_id', $staffIds)
            ->where('time_in', '>=', $todayStart)
            ->where('time_in', '<', $todayEnd)
            ->distinct('user_id')
            ->count('user_id');

        $absentToday = $staff
            ->filter(function (User $user) use ($todayStart, $todayEnd, $now) {
                $records = $user->attendanceRecords()
                    ->where('time_in', '<', $todayEnd)
                    ->where(function ($attendanceQuery) use ($todayStart) {
                        $attendanceQuery
                            ->whereNull('time_out')
                            ->orWhere('time_out', '>=', $todayStart);
                    })
                    ->get();

                return $this->attendanceCalendar->stateForDate($user, $todayStart, $records, $now) === 'absent';
            })
            ->count();

        $summary = [
            'staff' => $staff->count(),
            'present_today' => $presentToday,
            'absent_today' => $absentToday,
            'timed_in' => $openAttendances->count(),
            'forgot_time_out' => $forgotAttendances->count(),
            'rendered_minutes' => $reportRows->sum('rendered_minutes'),
        ];

        $departments = Department::orderBy('name')->get();
        $printableStaff = User::query()
            ->whereIn('role', self::STAFF_ROLES)
            ->where('is_suspended', false)
            ->with('department')
            ->orderBy('full_name')
            ->get();

        return view('admin.attendance.index', compact(
            'filters',
            'period',
            'anchor',
            'rangeStart',
            'rangeEnd',
            'departments',
            'openAttendances',
            'forgotAttendances',
            'reportRows',
            'calendarDays',
            'summary',
            'printableStaff',
        ));
    }

    public function printable(Request $request): View
    {
        AttendanceRecord::autoTimeOutExpiredOpenSessions();

        $filters = $request->validate([
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query->whereIn('role', self::STAFF_ROLES)),
            ],
            'period' => ['nullable', Rule::in(['daily', 'weekly', 'monthly', 'custom'])],
            'date' => ['nullable', 'date'],
            'start_date' => ['required_if:period,custom', 'nullable', 'date'],
            'end_date' => ['required_if:period,custom', 'nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $period = $filters['period'] ?? 'monthly';
        $anchor = Carbon::parse($filters['date'] ?? now())->startOfDay();

        if ($period === 'custom') {
            $rangeStart = Carbon::parse($filters['start_date'])->startOfDay();
            $rangeEnd = Carbon::parse($filters['end_date'])->startOfDay()->addDay();
        } else {
            [$rangeStart, $rangeEnd] = $this->reportRange($period, $anchor);
        }

        $now = now();
        $staff = User::query()
            ->whereIn('role', self::STAFF_ROLES)
            ->with('department')
            ->with('schedules')
            ->with(['specialSchedules' => function ($query) use ($rangeStart, $rangeEnd) {
                $query
                    ->where('start_datetime', '<', $rangeEnd)
                    ->where('end_datetime', '>=', $rangeStart);
            }])
            ->with(['statusOverrides' => function ($query) use ($rangeStart, $rangeEnd) {
                $query
                    ->where('start_datetime', '<', $rangeEnd)
                    ->where('end_datetime', '>=', $rangeStart);
            }])
            ->findOrFail($filters['user_id']);

        $records = $staff->attendanceRecords()
            ->with('forcedBy')
            ->where('time_in', '<', $rangeEnd)
            ->where(function ($attendanceQuery) use ($rangeStart) {
                $attendanceQuery
                    ->whereNull('time_out')
                    ->orWhere('time_out', '>=', $rangeStart);
            })
            ->orderBy('time_in')
            ->get();

        $dayRows = $this->attendanceCalendar
            ->daysForUser($staff, $rangeStart, $rangeEnd, $now, $records)
            ->map(fn (array $day) => [
                'date' => $day['date'],
                'records' => $day['records'],
                'status' => $day['label'],
                'rendered_minutes' => $day['minutes'],
                'rendered_label' => $this->formatMinutes($day['minutes']),
            ])
            ->values();

        $summary = [
            'sessions' => $records->count(),
            'present_days' => $dayRows->where('status', 'Present')->count(),
            'absent_days' => $dayRows->where('status', 'Absent')->count(),
            'pending_days' => $dayRows->where('status', 'Pending')->count(),
            'forced_time_outs' => $records->whereNotNull('forced_time_out_at')->count(),
            'rendered_minutes' => $dayRows->sum('rendered_minutes'),
        ];
        $summary['rendered_label'] = $this->formatMinutes($summary['rendered_minutes']);

        return view('admin.attendance.print', compact(
            'staff',
            'records',
            'dayRows',
            'summary',
            'period',
            'rangeStart',
            'rangeEnd',
        ));
    }

    public function forceTimeOut(Request $request, User $user): RedirectResponse
    {
        AttendanceRecord::autoTimeOutExpiredOpenSessions();

        abort_unless(in_array($user->role, self::STAFF_ROLES, true), 404);

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $attendance = $user->attendanceRecords()
            ->whereNull('time_out')
            ->latest('time_in')
            ->first();

        if (! $attendance) {
            return back()->with('error', 'This account is not currently timed in.');
        }

        $attendance->update([
            'time_out' => now(),
            'forced_time_out_by' => $request->user()->id,
            'forced_time_out_at' => now(),
            'force_time_out_reason' => $validated['reason'],
        ]);

        $user->specialSchedules()
            ->whereIn('type', ['On Break', 'Not Available'])
            ->where('start_datetime', '<=', now())
            ->where('end_datetime', '>=', now())
            ->update(['end_datetime' => now()->subSecond()]);

        return back()->with('success', "{$user->full_name} was timed out by admin.");
    }

    private function reportRange(string $period, Carbon $anchor): array
    {
        return match ($period) {
            'weekly' => [$anchor->copy()->startOfWeek(), $anchor->copy()->endOfWeek()->addSecond()],
            'monthly' => [$anchor->copy()->startOfMonth(), $anchor->copy()->endOfMonth()->addSecond()],
            default => [$anchor->copy()->startOfDay(), $anchor->copy()->addDay()],
        };
    }

    private function reportRow(User $user, Carbon $rangeStart, Carbon $rangeEnd, string $period, Carbon $now): array
    {
        $records = $user->attendanceRecords;
        $days = $this->attendanceCalendar->daysForUser($user, $rangeStart, $rangeEnd, $now, $records);

        $renderedMinutes = $records->sum(fn (AttendanceRecord $attendance) => $this->renderedMinutes($attendance, $rangeStart, $rangeEnd, $now));

        return [
            'user' => $user,
            'attendance_status' => $this->attendanceStatus($user, $rangeStart, $rangeEnd, $period, $now),
            'sessions' => $records->count(),
            'present_days' => $days->where('state', 'present')->count(),
            'absent_days' => $days->where('state', 'absent')->count(),
            'rendered_minutes' => $renderedMinutes,
            'rendered_label' => $this->formatMinutes($renderedMinutes),
        ];
    }

    private function attendanceStatus(User $user, Carbon $rangeStart, Carbon $rangeEnd, string $period, Carbon $now): string
    {
        if ($period !== 'daily') {
            return $user->attendanceRecords->isNotEmpty() ? 'Has Records' : 'No Records';
        }

        $state = $this->attendanceCalendar->stateForDate($user, $rangeStart, $user->attendanceRecords, $now);

        return $this->attendanceCalendar->label($state);
    }

    private function attendanceCutoffPassed(Carbon $date, Carbon $now): bool
    {
        return $this->attendanceCalendar->attendanceCutoffPassed($date, $now);
    }

    private function forgotToTimeOut(AttendanceRecord $attendance, Carbon $now): bool
    {
        if ($attendance->time_in->isBefore($now->copy()->startOfDay())) {
            return true;
        }

        return $now->greaterThanOrEqualTo($now->copy()->setTime(17, 0))
            && $attendance->time_in->lessThanOrEqualTo($now->copy()->setTime(17, 0));
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

    private function formatMinutes(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        if ($hours === 0) {
            return "{$remainingMinutes} min";
        }

        return "{$hours} hr" . ($hours === 1 ? '' : 's') . ($remainingMinutes > 0 ? " {$remainingMinutes} min" : '');
    }
}
