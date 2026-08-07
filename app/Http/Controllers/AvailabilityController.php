<?php

namespace App\Http\Controllers;

use App\Models\SpecialSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    private const AVAILABILITY_TYPES = [
        'On Break',
        'Not Available',
    ];

    public function show()
    {
        $user = Auth::user();
        $activeAvailability = $this->activeAvailabilitySchedule();

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

    public function available()
    {
        $activeAvailability = $this->activeAvailabilitySchedule();

        if (! $activeAvailability) {
            return redirect()
                ->route('availability.show')
                ->with('error', 'No break or not available status is active right now.');
        }

        SpecialSchedule::where('user_id', Auth::id())
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
        if (! Auth::user()->isTimedIn()) {
            return redirect()
                ->route('availability.show')
                ->with('error', 'Please time in before updating your availability.');
        }

        if (Auth::user()->live_status['status'] !== 'Available') {
            return redirect()
                ->route('availability.show')
                ->with('error', 'You can only start this status while you are currently available.');
        }

        $request->validate([
            'end_datetime' => ['required', 'date', 'after:now'],
        ]);

        SpecialSchedule::create([
            'user_id' => Auth::id(),
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

    private function activeAvailabilitySchedule(): ?SpecialSchedule
    {
        return SpecialSchedule::where('user_id', Auth::id())
            ->whereIn('type', self::AVAILABILITY_TYPES)
            ->where('start_datetime', '<=', now())
            ->where('end_datetime', '>=', now())
            ->latest()
            ->first();
    }
}
