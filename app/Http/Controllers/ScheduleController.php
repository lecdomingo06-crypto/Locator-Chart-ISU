<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::where('user_id', Auth::id())
            ->orderByRaw("FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->orderBy('start_time')
            ->get();

        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('schedules.create');
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

        return redirect()->route('schedules.index')->with('success', 'Schedule added successfully.');
    }

    public function edit(Schedule $schedule)
    {
        if ($schedule->user_id !== Auth::id()) {
            abort(403);
        }

        return view('schedules.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        if ($schedule->user_id !== Auth::id()) {
            abort(403);
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

        return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        if ($schedule->user_id !== Auth::id()) {
            abort(403);
        }

        $schedule->delete();

        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}