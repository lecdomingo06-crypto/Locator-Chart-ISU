<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\SpecialSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    private const AVAILABILITY_TYPES = [
        'On Break',
        'Not Available',
    ];

    public function show(Request $request)
    {
        AttendanceRecord::autoTimeOutExpiredOpenSessions();

        $user = $request->user();
        $activeAvailability = $this->activeAvailabilitySchedule($user);

        return view('availability.show', compact('user', 'activeAvailability'));
    }

    public function startBreak(Request $request)
    {
        return $this->startAvailability($request, 'On Break', 'Break status started.');
    }

    public function startUnavailable(Request $request)
    {
        return $this->startAvailability($request, 'Not Available', 'Not available status started.');
    }

    public function available(Request $request)
    {
        $user = $request->user();
        $activeAvailability = $this->activeAvailabilitySchedule($user);

        if (! $activeAvailability) {
            return redirect()
                ->route('availability.show')
                ->with('error', 'No break or not available status is active right now.');
        }

        $user->specialSchedules()
            ->whereIn('type', self::AVAILABILITY_TYPES)
            ->where('start_datetime', '<=', now())
            ->where('end_datetime', '>=', now())
            ->update(['end_datetime' => now()->subSecond()]);

        return redirect()
            ->route('availability.show')
            ->with('success', 'You are available again.');
    }

    private function startAvailability(Request $request, string $type, string $message)
    {
        AttendanceRecord::autoTimeOutExpiredOpenSessions();

        $user = $request->user();

        if (! $user->isTimedIn()) {
            return redirect()
                ->route('availability.show')
                ->with('error', 'Please time in before updating your availability.');
        }

        if ($user->live_status['status'] !== 'Available') {
            return redirect()
                ->route('availability.show')
                ->with('error', 'You can only start this status while you are currently available.');
        }

        $request->validate([
            'end_datetime' => ['required', 'date', 'after:now'],
        ]);

        $user->specialSchedules()->create([
            'type' => $type,
            'start_datetime' => now()->toDateTimeString(),
            'end_datetime' => Carbon::parse($request->end_datetime)->toDateTimeString(),
            'note' => $type === 'On Break'
                ? 'Availability page: on break.'
                : 'Availability page: not available.',
            'keep_until_schedule_end' => false,
        ]);

        return redirect()
            ->route('availability.show')
            ->with('success', $message);
    }

    private function activeAvailabilitySchedule(User $user): ?SpecialSchedule
    {
        return $user->specialSchedules()
            ->whereIn('type', self::AVAILABILITY_TYPES)
            ->where('start_datetime', '<=', now())
            ->where('end_datetime', '>=', now())
            ->latest()
            ->first();
    }
}
