<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Schedule;
use App\Models\SpecialSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class CampusChatbotService
{
    private const DAY_ORDER = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday',
    ];

    public function answer(string $message, User $viewer): array
    {
        $message = trim($message);
        $intent = $this->parseIntent($message);
        $staff = $this->findStaff($message, $intent['professor_name'] ?? null);
        $department = $this->findDepartment($message, $intent['department'] ?? null);
        $intentName = $intent['intent'] ?? $this->fallbackIntent($message);

        if (! $staff && $department && in_array($intentName, ['staff_search', 'department_staff', 'department_membership', 'unknown'], true)) {
            return $this->departmentStaffAnswer($department);
        }

        if (! $staff && in_array($intentName, ['current_status', 'status_end', 'next_class', 'next_break', 'today_schedule', 'availability', 'staff_search'], true)) {
            return $this->response(
                "I could not find that professor or faculty member. Try the full name, like \"King Nool next class\".",
                $intentName
            );
        }

        if ($staff && $intentName === 'unknown') {
            return $this->staffDetailsAnswer($staff);
        }

        return match ($intentName) {
            'current_status' => $this->currentStatusAnswer($staff),
            'status_end' => $this->statusEndAnswer($staff),
            'next_class' => $this->nextClassAnswer($staff),
            'next_break' => $this->nextBreakAnswer($staff),
            'today_schedule' => $this->todayScheduleAnswer($staff),
            'availability' => $this->availabilityAnswer($staff),
            'department_available', 'available_staff' => $this->availableStaffAnswer($department),
            'department_staff' => $this->departmentStaffAnswer($department),
            'department_membership' => $this->departmentMembershipAnswer($staff, $department),
            'staff_search' => $this->staffDetailsAnswer($staff),
            default => $this->openDatabaseAnswer($message),
        };
    }

    private function parseIntent(string $message): array
    {
        $fallback = [
            'intent' => $this->fallbackIntent($message),
            'professor_name' => null,
            'department' => null,
        ];

        $apiKey = config('services.gemini.key');
        $model = config('services.gemini.model', 'gemini-3-flash-preview');

        if (! $apiKey) {
            return $fallback;
        }

        $prompt = <<<'PROMPT'
Extract the user's intent for a professor/faculty availability chatbot.
Return only JSON with these keys:
intent: one of current_status,status_end,next_class,next_break,today_schedule,availability,department_available,available_staff,department_staff,department_membership,staff_search,unknown
professor_name: professor or faculty name if mentioned, otherwise null
department: department if mentioned, otherwise null

Examples:
"Professor King Nool next class" => {"intent":"next_class","professor_name":"King Nool","department":null}
"Who is available in CCSICT?" => {"intent":"department_available","professor_name":null,"department":"CCSICT"}
"Where is King Nool now?" => {"intent":"current_status","professor_name":"King Nool","department":null}
"When is Jimmy on leave end?" => {"intent":"status_end","professor_name":"Jimmy","department":null}
"Is King Nool from CCSICT department?" => {"intent":"department_membership","professor_name":"King Nool","department":"CCSICT"}
PROMPT;

        try {
            $response = Http::timeout(12)
                ->retry(1, 250)
                ->withHeaders([
                    'x-goog-api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                    'contents' => [[
                        'parts' => [[
                            'text' => $prompt."\n\nUser question: ".$message,
                        ]],
                    ]],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                    ],
                ]);

            if (! $response->successful()) {
                return $fallback;
            }

            $text = data_get($response->json(), 'candidates.0.content.parts.0.text');

            if (! is_string($text) || $text === '') {
                return $fallback;
            }

            $decoded = json_decode($text, true);

            if (! is_array($decoded)) {
                return $fallback;
            }

            return array_merge($fallback, array_intersect_key($decoded, $fallback));
        } catch (Throwable $exception) {
            report($exception);

            return $fallback;
        }
    }

    private function fallbackIntent(string $message): string
    {
        $text = Str::lower($message);

        if (
            Str::contains($text, ['until', 'end', 'ending', 'finish', 'finished', 'over', 'done', 'return', 'back'])
            && Str::contains($text, ['leave', 'meeting', 'emergency', 'break', 'not available', 'status'])
        ) {
            return 'status_end';
        }

        if (Str::contains($text, ['from', 'belong', 'belongs', 'member', 'under']) && Str::contains($text, ['department', 'agri', 'ccsict', 'ced', 'sas', 'ccje', 'ps'])) {
            return 'department_membership';
        }

        if (Str::contains($text, ['who', 'list', 'belong', 'belongs', 'member', 'members', 'staff']) && Str::contains($text, ['department', 'from', 'under', 'agri', 'ccsict', 'ced', 'sas', 'ccje', 'ps'])) {
            return 'department_staff';
        }

        if (Str::contains($text, ['who is available', 'available in', 'available professors', 'available faculty'])) {
            return Str::contains($text, ['department', 'ccsict', 'ced', 'sas', 'agri', 'ccje', 'ps'])
                ? 'department_available'
                : 'available_staff';
        }

        if (Str::contains($text, ['next break', 'break time', 'free time'])) {
            return 'next_break';
        }

        if (Str::contains($text, ['next class', 'next subject'])) {
            return 'next_class';
        }

        if (
            Str::contains($text, ['today schedule', 'schedule today', 'classes today', 'schedule'])
            || (
                Str::contains($text, ['today'])
                && Str::contains($text, ['anything', 'happening', 'doing', 'busy', 'class', 'classes', 'activity'])
            )
        ) {
            return 'today_schedule';
        }

        if (Str::contains($text, ['where', 'now', 'current', 'status'])) {
            return 'current_status';
        }

        if (Str::contains($text, ['available', 'free'])) {
            return 'availability';
        }

        return 'unknown';
    }

    private function findStaff(string $message, ?string $candidateName = null): ?User
    {
        $needle = $this->normalizeText(trim((string) $candidateName) ?: $message);
        $messageNeedle = $this->normalizeText($message);

        return User::with(['department', 'schedules'])
            ->whereIn('role', ['professor', 'faculty'])
            ->where('is_suspended', false)
            ->orderByDesc('full_name')
            ->get()
            ->first(function (User $user) use ($needle, $messageNeedle) {
                $name = $this->normalizeText($user->full_name ?: $user->name ?: $user->username);
                $parts = collect(explode(' ', $name))->filter(fn ($part) => strlen($part) >= 3);

                return str_contains($needle, $name)
                    || str_contains($messageNeedle, $name)
                    || $parts->every(fn ($part) => str_contains($messageNeedle, $part));
            });
    }

    private function findDepartment(string $message, ?string $candidateDepartment = null): ?Department
    {
        $text = $this->normalizeText(trim((string) $candidateDepartment).' '.$message);

        return Department::orderBy('name')
            ->get()
            ->first(function (Department $department) use ($text) {
                return str_contains($text, $this->normalizeText($department->name));
            });
    }

    private function currentStatusAnswer(?User $staff): array
    {
        $statusData = $staff->live_status;
        $name = $this->displayName($staff);
        $details = [];

        if (! empty($statusData['subject'])) {
            $details[] = $statusData['subject'];
        }

        if (! empty($statusData['room'])) {
            $details[] = 'Room '.$statusData['room'];
        }

        if (! empty($statusData['status_end_datetime'])) {
            $details[] = 'until '.Carbon::parse($statusData['status_end_datetime'])->format('M j, Y \a\t g:i A');
        }

        $extra = $details ? ' '.implode(' | ', $details) : '';

        return $this->response("{$name} is currently {$statusData['status']}{$extra}.", 'current_status');
    }

    private function statusEndAnswer(?User $staff): array
    {
        $statusData = $staff->live_status;
        $name = $this->displayName($staff);

        if ($statusData['status'] === 'Available') {
            return $this->response("{$name} is available right now, so there is no active status end time.", 'status_end');
        }

        if (! empty($statusData['status_end_datetime'])) {
            $end = Carbon::parse($statusData['status_end_datetime']);

            return $this->response("{$name}'s {$statusData['status']} status is set to end on ".$end->format('M j, Y').' at '.$end->format('g:i A').'.', 'status_end');
        }

        if (! empty($statusData['class_end_time']) && $statusData['status'] === 'In Class') {
            return $this->response("{$name}'s current class is set to end at ".$this->formatTime($statusData['class_end_time']).'.', 'status_end');
        }

        return $this->response("{$name} is currently {$statusData['status']}, but no end time is saved for that status.", 'status_end');
    }

    private function nextClassAnswer(?User $staff): array
    {
        if ($staff->role !== 'professor') {
            return $this->response($this->displayName($staff).' is faculty, so there is no weekly class schedule saved for this account.', 'next_class');
        }

        $next = $this->nextSchedule($staff);

        if (! $next) {
            return $this->response('No upcoming class schedule is saved for '.$this->displayName($staff).'.', 'next_class');
        }

        return $this->response(sprintf(
            "%s's next class is %s on %s, %s to %s, in room %s.",
            $this->displayName($staff),
            $next['schedule']->subject,
            $next['starts_at']->format('l'),
            $next['starts_at']->format('g:i A'),
            $next['ends_at']->format('g:i A'),
            $next['schedule']->room
        ), 'next_class');
    }

    private function nextBreakAnswer(?User $staff): array
    {
        if ($staff->role !== 'professor') {
            return $this->response($this->displayName($staff).' is faculty, so break time is based on current availability status only.', 'next_break');
        }

        $now = Carbon::now();
        $activeBreak = SpecialSchedule::where('user_id', $staff->id)
            ->where('type', 'On Break')
            ->where('start_datetime', '<=', $now)
            ->where('end_datetime', '>=', $now)
            ->latest()
            ->first();

        if ($activeBreak) {
            return $this->response($this->displayName($staff).' is on break now until '.Carbon::parse($activeBreak->end_datetime)->format('g:i A').'.', 'next_break');
        }

        $todaySchedules = $this->schedulesForDay($staff, $now->format('l'));
        $current = $todaySchedules->first(fn (Schedule $schedule) => $this->scheduleWindow($schedule, $now)->contains($now));

        if ($current) {
            $window = $this->scheduleWindow($current, $now);
            $nextClass = $todaySchedules->first(fn (Schedule $schedule) => $this->scheduleWindow($schedule, $now)->start->greaterThan($window->end));
            $until = $nextClass ? ' until '.$this->formatTime($nextClass->start_time) : ' for the rest of today';

            return $this->response($this->displayName($staff).' can break after '.$current->subject.' at '.$window->end->format('g:i A').$until.'.', 'next_break');
        }

        $nextToday = $todaySchedules->first(fn (Schedule $schedule) => $this->scheduleWindow($schedule, $now)->start->greaterThan($now));

        if ($nextToday) {
            return $this->response($this->displayName($staff).' is free now until the next class at '.$this->formatTime($nextToday->start_time).'.', 'next_break');
        }

        return $this->response($this->displayName($staff).' has no more saved classes today.', 'next_break');
    }

    private function todayScheduleAnswer(?User $staff): array
    {
        if ($staff->role !== 'professor') {
            return $this->response($this->displayName($staff).' is faculty, so no weekly class timetable is saved.', 'today_schedule');
        }

        $today = Carbon::now()->format('l');
        $schedules = $this->schedulesForDay($staff, $today);

        if ($schedules->isEmpty()) {
            return $this->response($this->displayName($staff).' has no saved classes today.', 'today_schedule');
        }

        $lines = $schedules
            ->map(fn (Schedule $schedule) => $this->formatTime($schedule->start_time).' - '.$this->formatTime($schedule->end_time).': '.$schedule->subject.' in '.$schedule->room)
            ->implode("\n");

        return $this->response($this->displayName($staff)."'s schedule today:\n".$lines, 'today_schedule');
    }

    private function availabilityAnswer(?User $staff): array
    {
        $status = $staff->live_status;

        if ($status['status'] === 'Available') {
            $next = $staff->role === 'professor' ? $this->nextSchedule($staff) : null;
            $suffix = $next ? ' Next class: '.$next['schedule']->subject.' at '.$next['starts_at']->format('g:i A').'.' : '';

            return $this->response($this->displayName($staff).' is available now.'.$suffix, 'availability');
        }

        return $this->currentStatusAnswer($staff);
    }

    private function availableStaffAnswer(?Department $department): array
    {
        $staff = User::with('department')
            ->whereIn('role', ['professor', 'faculty'])
            ->where('is_suspended', false)
            ->when($department, fn ($query) => $query->where('department_id', $department->id))
            ->orderBy('full_name')
            ->get()
            ->filter(fn (User $user) => $user->live_status['status'] === 'Available')
            ->take(8)
            ->values();

        $scope = $department ? ' in '.$department->name : '';

        if ($staff->isEmpty()) {
            return $this->response('No professor or faculty member is available'.$scope.' right now.', 'available_staff');
        }

        return $this->response('Available now'.$scope.': '.$staff->map(fn (User $user) => $this->displayName($user))->implode(', ').'.', 'available_staff');
    }

    private function staffDetailsAnswer(?User $staff): array
    {
        $status = $staff->live_status;
        $department = $staff->department?->name ?: 'No department';

        return $this->response(sprintf(
            '%s is %s from %s. Current status: %s.',
            $this->displayName($staff),
            ucfirst($staff->role),
            $department,
            $status['status']
        ), 'staff_search');
    }

    private function departmentStaffAnswer(?Department $department): array
    {
        if (! $department) {
            return $this->openDatabaseAnswer('List professor and faculty by department.');
        }

        $staff = User::whereIn('role', ['professor', 'faculty'])
            ->where('is_suspended', false)
            ->where('department_id', $department->id)
            ->orderBy('full_name')
            ->get();

        if ($staff->isEmpty()) {
            return $this->response('No professor or faculty records are saved under '.$department->name.'.', 'department_staff');
        }

        return $this->response($department->name.' staff: '.$staff->map(fn (User $user) => $this->displayName($user))->implode(', ').'.', 'department_staff');
    }

    private function departmentMembershipAnswer(?User $staff, ?Department $department): array
    {
        if (! $department) {
            return $this->response($this->displayName($staff).' is from '.($staff->department?->name ?: 'no saved department').'.', 'department_membership');
        }

        $actualDepartment = $staff->department?->name;

        if ($staff->department_id === $department->id) {
            return $this->response('Yes, '.$this->displayName($staff).' is from '.$department->name.'.', 'department_membership');
        }

        return $this->response('No, '.$this->displayName($staff).' is from '.($actualDepartment ?: 'no saved department').', not '.$department->name.'.', 'department_membership');
    }

    private function helpAnswer(): array
    {
        return $this->response("You can ask things like:\n- King Nool next class\n- Where is King Nool now?\n- King Nool next break\n- Who is available in CCSICT?", 'unknown');
    }

    private function openDatabaseAnswer(string $message): array
    {
        $fallback = $this->simpleDatabaseAnswer($message);

        $apiKey = config('services.gemini.key');
        $model = config('services.gemini.model', 'gemini-3-flash-preview');

        if (! $apiKey) {
            return $fallback;
        }

        $facts = $this->databaseFactsForPrompt();
        $prompt = <<<'PROMPT'
You are the Professor Tracking System campus assistant.
Answer the user's question using ONLY the FACTS below.
Rules:
- Do not invent professors, rooms, schedules, times, departments, or statuses.
- If the facts do not contain the answer, say: "I do not have that information in the system yet."
- Keep the answer short and helpful.
- If the user asks for a count or list, answer from the facts.
- Current time matters when interpreting "now", "today", "next", and "available".
PROMPT;

        try {
            $response = Http::timeout(14)
                ->retry(1, 250)
                ->withHeaders([
                    'x-goog-api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                    'contents' => [[
                        'parts' => [[
                            'text' => $prompt."\n\nFACTS:\n".$facts."\n\nUSER QUESTION:\n".$message,
                        ]],
                    ]],
                ]);

            if (! $response->successful()) {
                return $fallback;
            }

            $answer = trim((string) data_get($response->json(), 'candidates.0.content.parts.0.text'));

            if ($answer === '') {
                return $fallback;
            }

            return $this->response($answer, 'open_database');
        } catch (Throwable $exception) {
            report($exception);

            return $fallback;
        }
    }

    private function simpleDatabaseAnswer(string $message): array
    {
        $text = $this->normalizeText($message);
        $staff = User::with('department')
            ->whereIn('role', ['professor', 'faculty'])
            ->where('is_suspended', false)
            ->orderBy('full_name')
            ->get();

        if (Str::contains($text, ['how many', 'count', 'number'])) {
            if (Str::contains($text, ['professor'])) {
                return $this->response('There are '.$staff->where('role', 'professor')->count().' professors in the system.', 'open_database');
            }

            if (Str::contains($text, ['faculty'])) {
                return $this->response('There are '.$staff->where('role', 'faculty')->count().' faculty members in the system.', 'open_database');
            }

            return $this->response('There are '.$staff->count().' professor/faculty records in the system.', 'open_database');
        }

        if (Str::contains($text, ['list', 'show all', 'who are'])) {
            $names = $staff->map(fn (User $user) => $this->displayName($user))->take(12)->implode(', ');

            return $this->response($names ? 'Professor/faculty records: '.$names.'.' : 'No professor/faculty records are saved yet.', 'open_database');
        }

        if (Str::contains($text, ['tell me', 'about', 'named', 'who is', 'describe', 'profile'])) {
            return $this->response('I do not have that professor or faculty member in the system yet.', 'open_database');
        }

        return $this->helpAnswer();
    }

    private function databaseFactsForPrompt(): string
    {
        $now = Carbon::now();
        $lines = [
            'Current datetime: '.$now->format('Y-m-d H:i:s l'),
            'Departments: '.Department::orderBy('name')->pluck('name')->implode(', '),
        ];

        $staffMembers = User::with(['department', 'schedules'])
            ->whereIn('role', ['professor', 'faculty'])
            ->where('is_suspended', false)
            ->orderBy('full_name')
            ->get();

        foreach ($staffMembers as $staff) {
            $status = $staff->live_status;
            $statusParts = [
                'status='.$status['status'],
                'source='.$status['source'],
            ];

            if (! empty($status['subject'])) {
                $statusParts[] = 'subject='.$status['subject'];
            }

            if (! empty($status['room'])) {
                $statusParts[] = 'room='.$status['room'];
            }

            if (! empty($status['status_end_datetime'])) {
                $statusParts[] = 'status_ends='.Carbon::parse($status['status_end_datetime'])->format('Y-m-d H:i:s l');
            }

            if (! empty($status['class_start_time']) && ! empty($status['class_end_time'])) {
                $statusParts[] = 'class_time='.$this->formatTime($status['class_start_time']).'-'.$this->formatTime($status['class_end_time']);
            }

            $lines[] = sprintf(
                'Staff: %s | role=%s | department=%s | %s',
                $this->displayName($staff),
                $staff->role,
                $staff->department?->name ?: 'No department',
                implode(' | ', $statusParts)
            );

            $schedules = $staff->schedules
                ->sortBy(fn (Schedule $schedule) => array_search($schedule->day_of_week, self::DAY_ORDER, true).$schedule->start_time)
                ->map(fn (Schedule $schedule) => $schedule->day_of_week.' '.$this->formatTime($schedule->start_time).'-'.$this->formatTime($schedule->end_time).' '.$schedule->subject.' room '.$schedule->room)
                ->values();

            $lines[] = 'Weekly schedule for '.$this->displayName($staff).': '.($schedules->isEmpty() ? 'None saved' : $schedules->implode('; '));
        }

        $specialSchedules = SpecialSchedule::with('user')
            ->where('end_datetime', '>=', $now)
            ->orderBy('start_datetime')
            ->take(20)
            ->get()
            ->map(function (SpecialSchedule $schedule) {
                return sprintf(
                    '%s: %s from %s to %s',
                    $this->displayName($schedule->user),
                    $schedule->type,
                    Carbon::parse($schedule->start_datetime)->format('Y-m-d H:i:s l'),
                    Carbon::parse($schedule->end_datetime)->format('Y-m-d H:i:s l')
                );
            });

        $lines[] = 'Upcoming/active special schedules: '.($specialSchedules->isEmpty() ? 'None saved' : $specialSchedules->implode('; '));

        return implode("\n", $lines);
    }

    private function nextSchedule(User $staff): ?array
    {
        $now = Carbon::now();
        $schedules = $staff->schedules()->get();
        $candidates = collect();

        foreach (range(0, 7) as $offset) {
            $date = $now->copy()->addDays($offset);
            $daySchedules = $schedules
                ->where('day_of_week', $date->format('l'))
                ->sortBy('start_time');

            foreach ($daySchedules as $schedule) {
                $startsAt = $date->copy()->setTimeFromTimeString($schedule->start_time);
                $endsAt = $date->copy()->setTimeFromTimeString($schedule->end_time);

                if ($startsAt->greaterThan($now)) {
                    $candidates->push(compact('schedule', 'startsAt', 'endsAt'));
                }
            }
        }

        $next = $candidates->sortBy('startsAt')->first();

        if (! $next) {
            return null;
        }

        return [
            'schedule' => $next['schedule'],
            'starts_at' => $next['startsAt'],
            'ends_at' => $next['endsAt'],
        ];
    }

    private function schedulesForDay(User $staff, string $day): Collection
    {
        return $staff->schedules()
            ->where('day_of_week', $day)
            ->orderBy('start_time')
            ->get();
    }

    private function scheduleWindow(Schedule $schedule, Carbon $date): object
    {
        return new class($date->copy()->setTimeFromTimeString($schedule->start_time), $date->copy()->setTimeFromTimeString($schedule->end_time))
        {
            public function __construct(public Carbon $start, public Carbon $end)
            {
            }

            public function contains(Carbon $moment): bool
            {
                return $this->start->lessThanOrEqualTo($moment) && $this->end->greaterThanOrEqualTo($moment);
            }
        };
    }

    private function displayName(User $user): string
    {
        return $user->full_name ?: $user->name ?: $user->username;
    }

    private function normalizeText(string $value): string
    {
        return Str::of($value)
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', ' ')
            ->squish()
            ->toString();
    }

    private function formatTime(string $time): string
    {
        return Carbon::parse($time)->format('g:i A');
    }

    private function response(string $answer, string $intent): array
    {
        return [
            'answer' => $answer,
            'intent' => $intent,
        ];
    }
}
