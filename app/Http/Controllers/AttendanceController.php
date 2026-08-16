<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Services\AttendanceCalendarService;
use App\Services\AttendanceGeofenceService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        private AttendanceCalendarService $attendanceCalendar,
        private AttendanceGeofenceService $geofence,
    ) {
    }

    public function show(Request $request): View
    {
        AttendanceRecord::autoTimeOutExpiredOpenSessions();

        $user = $request->user();
        $activeAttendance = $user->currentAttendance();
        $calendarMonth = Carbon::parse($request->query('month', now()->format('Y-m-01')))->startOfMonth();
        $calendarStart = $calendarMonth->copy()->startOfMonth();
        $calendarEnd = $calendarMonth->copy()->endOfMonth();
        $calendarRangeEnd = $calendarEnd->copy()->addDay();
        $user->load([
            'schedules',
            'specialSchedules' => function ($query) use ($calendarStart, $calendarRangeEnd) {
                $query
                    ->where('start_datetime', '<', $calendarRangeEnd)
                    ->where('end_datetime', '>=', $calendarStart);
            },
            'statusOverrides' => function ($query) use ($calendarStart, $calendarRangeEnd) {
                $query
                    ->where('start_datetime', '<', $calendarRangeEnd)
                    ->where('end_datetime', '>=', $calendarStart);
            },
        ]);
        $clearedSessionIds = collect($request->session()->get($this->clearedSessionsKey($user->id), []))
            ->map(fn ($id) => (int) $id);
        $todayAttendances = $user->attendanceRecords()
            ->whereDate('time_in', today())
            ->latest('time_in')
            ->get()
            ->reject(fn ($attendance) => $attendance->time_out && $clearedSessionIds->contains((int) $attendance->id))
            ->values();
        $calendarRecords = $user->attendanceRecords()
            ->whereBetween('time_in', [$calendarStart, $calendarEnd->copy()->endOfDay()])
            ->orderBy('time_in')
            ->get();
        $calendarDays = $this->attendanceCalendar->daysForUser($user, $calendarStart, $calendarRangeEnd, now(), $calendarRecords);
        $calendarSummary = [
            'present' => $calendarDays->where('state', 'present')->count(),
            'absent' => $calendarDays->where('state', 'absent')->count(),
            'pending' => $calendarDays->where('state', 'pending')->count(),
            'excused' => $calendarDays->where('state', 'excused')->count(),
            'no_class' => $calendarDays->where('state', 'no_class')->count(),
            'rendered_minutes' => $calendarDays->sum('minutes'),
        ];
        $geofenceSettings = $this->geofence->settings();

        return view('attendance.show', compact(
            'user',
            'activeAttendance',
            'todayAttendances',
            'calendarMonth',
            'calendarDays',
            'calendarSummary',
            'geofenceSettings',
        ));
    }

    public function timeIn(Request $request): RedirectResponse
    {
        AttendanceRecord::autoTimeOutExpiredOpenSessions();

        $validated = $request->validate([
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'accuracy' => ['nullable', 'numeric', 'min:0', 'max:100000'],
        ]);

        $user = $request->user();
        $latitude = array_key_exists('latitude', $validated) && $validated['latitude'] !== null ? (float) $validated['latitude'] : null;
        $longitude = array_key_exists('longitude', $validated) && $validated['longitude'] !== null ? (float) $validated['longitude'] : null;
        $accuracy = array_key_exists('accuracy', $validated) && $validated['accuracy'] !== null ? (float) $validated['accuracy'] : null;
        $locationCheck = $this->geofence->check($latitude, $longitude, $accuracy);

        if (! $locationCheck['allowed']) {
            return redirect()
                ->route('attendance.show')
                ->with('error', $locationCheck['message']);
        }

        $created = DB::transaction(function () use ($user, $request, $latitude, $longitude, $accuracy, $locationCheck) {
            $hasOpenAttendance = $user->attendanceRecords()
                ->whereNull('time_out')
                ->lockForUpdate()
                ->exists();

            if ($hasOpenAttendance) {
                return false;
            }

            $user->attendanceRecords()->create([
                'time_in' => now(),
                'time_in_latitude' => $latitude,
                'time_in_longitude' => $longitude,
                'time_in_accuracy_meters' => $accuracy,
                'time_in_distance_meters' => $locationCheck['distance_meters'],
                'time_in_location_verified' => $locationCheck['status'] === 'verified',
                'time_in_location_status' => $locationCheck['status'],
                'time_in_ip' => $request->ip(),
                'time_in_user_agent' => $request->userAgent(),
            ]);

            return true;
        });

        return redirect()
            ->route('attendance.show')
            ->with($created ? 'success' : 'error', $created
                ? 'Time in recorded. Your live status and weekly schedule are now active.'
                : 'You are already timed in.');
    }

    public function timeOut(Request $request): RedirectResponse
    {
        AttendanceRecord::autoTimeOutExpiredOpenSessions();

        $user = $request->user();

        $attendance = DB::transaction(function () use ($user) {
            $attendance = $user->attendanceRecords()
                ->whereNull('time_out')
                ->latest('time_in')
                ->lockForUpdate()
                ->first();

            if (! $attendance) {
                return null;
            }

            $attendance->update(['time_out' => now()]);

            return $attendance;
        });

        if (! $attendance) {
            return redirect()
                ->route('attendance.show')
                ->with('error', 'You are not currently timed in.');
        }

        $user->specialSchedules()
            ->whereIn('type', ['On Break', 'Not Available'])
            ->where('start_datetime', '<=', now())
            ->where('end_datetime', '>=', now())
            ->update(['end_datetime' => now()->subSecond()]);

        return redirect()
            ->route('attendance.show')
            ->with('success', 'Time out recorded. Your live status is now Not Available.');
    }

    public function clearSessions(Request $request): RedirectResponse
    {
        $user = $request->user();
        $sessionKey = $this->clearedSessionsKey($user->id);
        $alreadyClearedIds = collect($request->session()->get($sessionKey, []))
            ->map(fn ($id) => (int) $id)
            ->all();
        $clearedIds = $user
            ->attendanceRecords()
            ->whereDate('time_in', today())
            ->whereNotNull('time_out')
            ->whereNotIn('id', $alreadyClearedIds)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if ($clearedIds !== []) {
            $request->session()->put($sessionKey, array_values(array_unique([...$alreadyClearedIds, ...$clearedIds])));
        }

        return redirect()
            ->route('attendance.show')
            ->with($clearedIds !== [] ? 'success' : 'error', $clearedIds !== []
                ? 'Completed sessions were hidden from today\'s log. Attendance records remain saved.'
                : 'There are no visible completed sessions to hide.');
    }

    private function clearedSessionsKey(int $userId, ?Carbon $date = null): string
    {
        return sprintf('attendance.cleared_sessions.%d.%s', $userId, ($date ?? today())->toDateString());
    }
}
