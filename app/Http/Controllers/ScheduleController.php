<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        return redirect()->route('schedules.create');
    }

    public function create(Request $request)
    {
        $schedules = $this->currentUserSchedules($request);

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

        $request->user()->schedules()->create([
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

    public function edit(Request $request, Schedule $schedule)
    {
        if (! $this->belongsToCurrentUser($request, $schedule)) {
            return redirect()
                ->route('schedules.create')
                ->with('error', 'That schedule is not available for this account.');
        }

        return redirect()
            ->route('schedules.create')
            ->with('info', 'Open the schedule from the timetable to edit it in the popup.');
    }

    public function update(Request $request, Schedule $schedule)
    {
        if (! $this->belongsToCurrentUser($request, $schedule)) {
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

    public function destroy(Request $request, Schedule $schedule)
    {
        if (! $this->belongsToCurrentUser($request, $schedule)) {
            return redirect()
                ->route('schedules.create')
                ->with('error', 'That schedule is not available for this account.');
        }

        $schedule->delete();

        return redirect()->route('schedules.create')->with('success', 'Schedule deleted successfully.');
    }

    private function currentUserSchedules(Request $request)
    {
        return $request->user()
            ->schedules()
            ->orderByRaw("CASE day_of_week WHEN 'Monday' THEN 1 WHEN 'Tuesday' THEN 2 WHEN 'Wednesday' THEN 3 WHEN 'Thursday' THEN 4 WHEN 'Friday' THEN 5 WHEN 'Saturday' THEN 6 WHEN 'Sunday' THEN 7 ELSE 8 END")
            ->orderBy('start_time')
            ->get();
    }

    private function belongsToCurrentUser(Request $request, Schedule $schedule): bool
    {
        return $request->user()
            ->schedules()
            ->whereKey($schedule->getKey())
            ->exists();
    }
}
