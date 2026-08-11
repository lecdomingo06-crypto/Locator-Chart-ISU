<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\SpecialSchedule;
use App\Models\StatusOverride;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecialScheduleController extends Controller
{
    private const ALLOWED_TYPES = [
        'On Leave',
        'Emergency',
        'On Meeting',
    ];

    public function index()
    {
        return redirect()->route('special_schedules.create');
    }

    public function create()
    {
        return view('special_schedules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:' . implode(',', self::ALLOWED_TYPES),
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'note' => 'nullable|string',
            'keep_until_schedule_end' => 'nullable|boolean',
        ]);

        $user = $request->user();

        $specialSchedule = $user->specialSchedules()->create($this->specialSchedulePayload($request, $user->id, false));
        $this->replaceOverlappingAdminOverrides($specialSchedule);

        return redirect()->route('special_schedules.create')->with('success', 'Special schedule added successfully.');
    }

    public function edit(SpecialSchedule $specialSchedule)
    {
        if (! $this->belongsToCurrentUser($specialSchedule)) {
            abort(403);
        }

        return view('special_schedules.edit', compact('specialSchedule'));
    }

    public function update(Request $request, SpecialSchedule $specialSchedule)
    {
        if (! $this->belongsToCurrentUser($specialSchedule)) {
            abort(403);
        }

        $request->validate([
            'type' => 'required|in:' . implode(',', self::ALLOWED_TYPES),
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'note' => 'nullable|string',
            'keep_until_schedule_end' => 'nullable|boolean',
        ]);

        $specialSchedule->update($this->specialSchedulePayload($request, $request->user()->id, false));
        $this->replaceOverlappingAdminOverrides($specialSchedule);

        return redirect()->route('special_schedules.create')->with('success', 'Special schedule updated successfully.');
    }

    public function destroy(SpecialSchedule $specialSchedule)
    {
        if (! $this->belongsToCurrentUser($specialSchedule)) {
            abort(403);
        }

        $specialSchedule->delete();

        return redirect()->route('special_schedules.create')->with('success', 'Special schedule deleted successfully.');
    }

    private function specialSchedulePayload(Request $request, int $userId, bool $includeUserId = true): array
    {
        $keepUntilScheduleEnd = Auth::user()?->role === 'professor'
            && $request->type === 'On Meeting'
            && $request->boolean('keep_until_schedule_end');
        $resolvedEndDatetime = $this->resolveEndDatetime(
            $userId,
            $request->type,
            $request->start_datetime,
            $request->end_datetime,
            $keepUntilScheduleEnd,
        );

        $payload = [
            'type' => $request->type,
            'start_datetime' => Carbon::parse($request->start_datetime)->toDateTimeString(),
            'end_datetime' => $resolvedEndDatetime,
            'note' => $request->note,
            'keep_until_schedule_end' => $keepUntilScheduleEnd,
        ];

        if ($includeUserId) {
            $payload['user_id'] = $userId;
        }

        return $payload;
    }

    private function resolveEndDatetime(
        int $userId,
        string $type,
        string $startDatetime,
        string $endDatetime,
        bool $keepUntilScheduleEnd,
    ): string {
        if ($type !== 'On Meeting' || ! $keepUntilScheduleEnd) {
            return Carbon::parse($endDatetime)->toDateTimeString();
        }

        $startMoment = Carbon::parse($startDatetime);
        $endMoment = Carbon::parse($endDatetime);

        $matchingSchedule = Schedule::query()
            ->where('user_id', $userId)
            ->where('day_of_week', $endMoment->format('l'))
            ->whereTime('start_time', '<=', $endMoment->format('H:i:s'))
            ->whereTime('end_time', '>', $endMoment->format('H:i:s'))
            ->whereTime('end_time', '>', $startMoment->format('H:i:s'))
            ->orderBy('end_time')
            ->first();

        if (! $matchingSchedule) {
            return $endMoment->toDateTimeString();
        }

        $scheduleEnd = $endMoment->copy()->setTimeFromTimeString($matchingSchedule->end_time);

        return $scheduleEnd->greaterThan($endMoment)
            ? $scheduleEnd->toDateTimeString()
            : $endMoment->toDateTimeString();
    }

    private function belongsToCurrentUser(SpecialSchedule $specialSchedule): bool
    {
        return Auth::user()
            ?->specialSchedules()
            ->whereKey($specialSchedule->getKey())
            ->exists() ?? false;
    }

    private function replaceOverlappingAdminOverrides(SpecialSchedule $specialSchedule): void
    {
        $start = Carbon::parse($specialSchedule->start_datetime);
        $end = Carbon::parse($specialSchedule->end_datetime);

        StatusOverride::query()
            ->where('user_id', $specialSchedule->user_id)
            ->where('start_datetime', '<', $end)
            ->where('end_datetime', '>', $start)
            ->get()
            ->each(function (StatusOverride $override) use ($start) {
                $overrideStart = Carbon::parse($override->start_datetime);

                if ($overrideStart->lt($start)) {
                    $replacementEnd = $start->copy()->subSecond();

                    if ($replacementEnd->gt($overrideStart)) {
                        $override->update([
                            'end_datetime' => $replacementEnd->toDateTimeString(),
                        ]);

                        return;
                    }
                }

                $override->delete();
            });
    }
}
