<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        return redirect()->route('schedules.create');
    }

    public function create()
    {
        $schedules = $this->currentUserSchedules();

        return view('schedules.create', compact('schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'room' => 'required|string|max:255',
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'semester' => 'required|string|max:255',
            'school_year' => 'required|string|max:255',
        ]);

        Schedule::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'room' => $request->room,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'semester' => $request->semester,
            'school_year' => $request->school_year,
        ]);

        return redirect()->route('schedules.create')->with('success', 'Schedule added successfully.');
    }

    public function edit(Schedule $schedule)
    {
        if (! $this->belongsToCurrentUser($schedule)) {
            return redirect()
                ->route('schedules.create')
                ->with('error', 'That schedule is not available for this account.');
        }

        return view('schedules.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        if (! $this->belongsToCurrentUser($schedule)) {
            return redirect()
                ->route('schedules.create')
                ->with('error', 'That schedule is not available for this account.');
        }

        $request->validate([
            'subject' => 'required|string|max:255',
            'room' => 'required|string|max:255',
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'semester' => 'required|string|max:255',
            'school_year' => 'required|string|max:255',
        ]);

        $schedule->update($request->only([
            'subject',
            'room',
            'day_of_week',
            'start_time',
            'end_time',
            'semester',
            'school_year',
        ]));

        return redirect()->route('schedules.create')->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        if (! $this->belongsToCurrentUser($schedule)) {
            return redirect()
                ->route('schedules.create')
                ->with('error', 'That schedule is not available for this account.');
        }

        $schedule->delete();

        return redirect()->route('schedules.create')->with('success', 'Schedule deleted successfully.');
    }

    private function currentUserSchedules()
    {
        return Schedule::where('user_id', Auth::id())
            ->orderByRaw("FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->orderBy('start_time')
            ->get();
    }

    private function belongsToCurrentUser(Schedule $schedule): bool
    {
        return (string) $schedule->user_id === (string) Auth::id();
    }
}
