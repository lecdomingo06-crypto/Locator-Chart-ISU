<?php

namespace App\Http\Controllers;

use App\Models\AcademicEvent;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AcademicEventController extends Controller
{
    public function index()
    {
        $now = now();

        $activeEvents = AcademicEvent::with('department')
            ->activeAt($now)
            ->orderBy('start_datetime')
            ->get();

        $upcomingEvents = AcademicEvent::with('department')
            ->upcomingFrom($now)
            ->orderBy('start_datetime')
            ->get();

        $pastEvents = AcademicEvent::with('department')
            ->where('end_datetime', '<', $now)
            ->orderByDesc('start_datetime')
            ->get();

        return view('academic_events.index', compact('activeEvents', 'upcomingEvents', 'pastEvents'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();

        return view('academic_events.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        AcademicEvent::create($validated);

        return redirect()->route('academic_events.index')->with('success', 'Academic event created successfully.');
    }

    public function edit(AcademicEvent $academicEvent)
    {
        $departments = Department::orderBy('name')->get();

        return view('academic_events.edit', compact('academicEvent', 'departments'));
    }

    public function update(Request $request, AcademicEvent $academicEvent)
    {
        $validated = $this->validatedData($request);

        $academicEvent->update($validated);

        return redirect()->route('academic_events.index')->with('success', 'Academic event updated successfully.');
    }

    public function destroy(AcademicEvent $academicEvent)
    {
        $academicEvent->delete();

        return redirect()->route('academic_events.index')->with('success', 'Academic event deleted successfully.');
    }

    protected function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(AcademicEvent::TYPE_OPTIONS)],
            'start_datetime' => ['required', 'date'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
            'scope' => ['required', Rule::in(AcademicEvent::SCOPE_OPTIONS)],
            'department_id' => [
                Rule::requiredIf(fn () => $request->scope === 'department'),
                'nullable',
                'exists:departments,id',
            ],
            'note' => ['nullable', 'string'],
        ]);

        if ($validated['scope'] !== 'department') {
            $validated['department_id'] = null;
        }

        return $validated;
    }
}
