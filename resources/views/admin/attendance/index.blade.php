@extends('layouts.admin-users')

@section('title', 'Attendance Dashboard')

@section('content')
@php
    $rangeLabel = $period === 'daily'
        ? $rangeStart->format('F j, Y')
        : $rangeStart->format('M j') . ' - ' . $rangeEnd->copy()->subSecond()->format('M j, Y');
    $initials = function ($name) {
        return collect(preg_split('/\s+/', trim($name)) ?: [])
            ->filter()
            ->take(2)
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->implode('');
    };
    $statusClass = fn ($status) => strtolower(str_replace(' ', '-', $status));
    $isDaily = $period === 'daily';
    $calendarHasReportContext = collect(request()->query())
        ->except('page')
        ->filter(fn ($value) => trim((string) $value) !== '')
        ->isNotEmpty();
    $weekdayLabels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    $calendarFirstDate = $calendarDays->first()['date'] ?? $rangeStart;
    $calendarLeadingEmptyDays = $calendarFirstDate->copy()->startOfDay()->dayOfWeek;
    $calendarCellCount = $calendarLeadingEmptyDays + $calendarDays->count();
    $calendarTrailingEmptyDays = (7 - ($calendarCellCount % 7)) % 7;
    $calendarTotals = [
        'present' => $calendarDays->sum('present'),
        'absent' => $calendarDays->sum('absent'),
        'excused' => $calendarDays->sum('excused'),
        'no_class' => $calendarDays->sum('no_class'),
    ];
@endphp

<style>
    .attendance-dashboard{display:grid;gap:16px}.sr-only{position:absolute!important;width:1px!important;height:1px!important;padding:0!important;margin:-1px!important;overflow:hidden!important;clip:rect(0,0,0,0)!important;white-space:nowrap!important;border:0!important}.attendance-summary{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:10px}.attendance-card{min-width:0;padding:16px;border:1px solid var(--border);border-radius:8px;background:#fff;box-shadow:var(--shadow)}.attendance-card span{display:block;color:var(--muted);font-size:.68rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase}.attendance-card strong{display:block;margin-top:7px;color:var(--text);font-size:1.32rem}.attendance-card small{display:block;margin-top:2px;color:var(--muted);font-size:.75rem}.attendance-card.warning strong{color:var(--warning)}.attendance-card.danger strong{color:var(--danger)}.report-range{display:inline-flex;align-items:center;min-height:34px;padding:0 12px;border:1px solid var(--border);border-radius:999px;color:var(--green-900);background:#fff;font-size:.8rem;font-weight:800}.attendance-filter-panel{padding:0;overflow:hidden}.attendance-filter-form{display:grid;gap:0}.filter-panel-head{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:16px 18px;border-bottom:1px solid var(--border);background:#fff}.filter-panel-head span{display:block;color:var(--green-800);font-size:.68rem;font-weight:900;letter-spacing:.08em;text-transform:uppercase}.filter-panel-head h2{margin:4px 0 0;color:var(--text);font-size:1.05rem}.filter-panel-head p{margin:4px 0 0;color:var(--muted);font-size:.78rem}.filter-actions{display:flex;align-items:center;justify-content:flex-end;gap:8px;flex-wrap:wrap}.filter-actions .primary-button,.filter-actions .secondary-button{min-width:84px}.print-button{border-color:var(--green-900);background:var(--green-900)}.filter-panel-body{display:grid;grid-template-columns:minmax(260px,.72fr) minmax(430px,1.18fr) minmax(260px,.8fr);background:#fff}.filter-group{display:block;padding:16px 18px;border:0;background:#fff}.filter-group+.filter-group{border-left:1px solid var(--border)}.filter-group-copy{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:12px}.filter-group-copy span{display:block;color:var(--green-800);font-size:.68rem;font-weight:900;letter-spacing:.08em;text-transform:uppercase}.filter-group-copy strong{color:var(--text);font-size:.92rem}.filter-group-fields{display:grid;gap:10px;align-items:end}.range-filter-group .filter-group-fields{grid-template-columns:minmax(130px,.75fr) minmax(160px,1fr)}.staff-filter-group .filter-group-fields{grid-template-columns:minmax(200px,1.1fr) minmax(150px,.8fr) minmax(190px,1fr)}.print-filter-group .filter-group-fields{grid-template-columns:1fr}.filter-group .field input,.filter-group .field select{width:100%;min-height:40px}.calendar-panel{overflow:hidden}.calendar-summary{display:grid;gap:12px;padding:18px 22px 4px}.calendar-summary-title{display:flex;align-items:end;justify-content:space-between;gap:14px}.calendar-summary-title span{color:var(--green-800);font-size:.72rem;font-weight:900;letter-spacing:.08em;text-transform:uppercase}.calendar-summary-title strong{color:var(--muted);font-size:.82rem;font-weight:800}.calendar-summary-grid{display:flex;flex-wrap:wrap;gap:8px}.calendar-metric{display:inline-flex;align-items:center;gap:9px;min-height:38px;padding:8px 11px;border:1px solid #dbece2;border-radius:999px;background:#fff}.calendar-metric span{display:inline-flex;align-items:center;gap:7px;color:var(--muted);font-size:.8rem;font-weight:800}.calendar-metric span::before{content:"";width:10px;height:10px;border-radius:999px;background:#edf2ee}.calendar-metric strong{display:inline-flex;align-items:center;justify-content:center;min-width:26px;min-height:24px;padding:0 7px;border-radius:999px;color:var(--green-950);background:#f3fbf6;font-size:.82rem;line-height:1}.calendar-metric.present span::before{background:#21a85a}.calendar-metric.present strong{color:#159447}.calendar-metric.absent span::before{background:#f03d3d}.calendar-metric.absent strong{color:#d12d2d}.calendar-metric.excused span::before{background:#7b61d1}.calendar-metric.excused strong{color:#6f4cc3}.calendar-metric.no-class span::before{background:#d97706}.attendance-calendar{display:grid;grid-template-columns:repeat(7,minmax(0,1fr));gap:10px 8px;width:min(100%,720px);margin:0 auto;padding:20px 18px 26px}.calendar-weekday{display:grid;place-items:center;min-height:24px;color:var(--muted);font-size:.64rem;font-weight:900;letter-spacing:.06em;text-transform:uppercase}.calendar-empty{min-height:48px}.calendar-day{position:relative;display:grid;justify-items:center;gap:5px;min-height:52px;padding:0;border:0;border-radius:999px;background:transparent}.calendar-day-number{display:grid;place-items:center;width:34px;height:34px;border-radius:999px;color:#183326;background:#edf2ee;font-size:.86rem;font-weight:900;line-height:1;box-shadow:inset 0 0 0 1px rgba(12,92,56,.05)}.calendar-day.present .calendar-day-number{color:#fff;background:#21a85a;box-shadow:0 7px 14px rgba(33,168,90,.18)}.calendar-day.absent .calendar-day-number{color:#fff;background:#f03d3d;box-shadow:0 7px 14px rgba(240,61,61,.16)}.calendar-day.excused .calendar-day-number{color:#fff;background:#7b61d1;box-shadow:0 7px 14px rgba(123,97,209,.16)}.calendar-day.no_class .calendar-day-number{color:#fff;background:#d97706;box-shadow:0 7px 14px rgba(217,119,6,.18)}.calendar-day:hover .calendar-day-number{transform:translateY(-1px)}.calendar-day-counts{display:flex;justify-content:center;gap:3px;min-height:14px}.calendar-day-counts i{display:inline-grid;place-items:center;min-width:14px;height:14px;padding:0 4px;border-radius:999px;color:#fff;font-size:.58rem;font-style:normal;font-weight:900;line-height:1}.calendar-day-counts .present{background:#21a85a}.calendar-day-counts .absent{background:#f03d3d}.calendar-day-counts .excused{background:#7b61d1}.calendar-day-counts .no-class{background:#d97706}.attendance-sections{display:grid;grid-template-columns:minmax(0,1fr) minmax(340px,.8fr);gap:16px}.force-form{display:grid;grid-template-columns:minmax(180px,1fr) auto;gap:8px;align-items:center}.force-form input{min-height:36px;padding:0 10px;border:1px solid var(--border);border-radius:6px}.force-form button{min-height:36px;padding:0 11px;border:1px solid #e8c69d;border-radius:6px;color:var(--warning);background:#fff8ed;font-size:.72rem;font-weight:800;cursor:pointer}.duration-cell{font-weight:900;color:var(--green-900)}.attendance-status{display:inline-flex;align-items:center;justify-content:center;min-height:25px;padding:0 9px;border-radius:999px;font-size:.7rem;font-weight:900}.attendance-status.present,.attendance-status.has-records{color:#087238;background:#dff5e8}.attendance-status.absent{color:var(--danger);background:#fff0ef}.attendance-status.pending,.attendance-status.no-records,.attendance-status.no-class{color:#5d6c63;background:#edf1ee}.attendance-status.excused{color:#6d3eb8;background:#f0e8ff}.subtle-table th{color:#55685b;background:#f4faf6}.table-wrap{max-height:520px;overflow:auto}.table-wrap table thead th{position:sticky;top:0;z-index:1}.forgot-list{display:grid;gap:10px;padding:16px}.forgot-card{display:grid;gap:7px;padding:13px;border:1px solid #e8c69d;border-radius:8px;background:#fff8ed}.forgot-card strong{font-size:.9rem}.forgot-card span{color:var(--muted);font-size:.77rem}.admin-note{margin:0;color:var(--muted);font-size:.82rem;line-height:1.5}.daily-report-note{display:inline-flex;align-items:center;min-height:28px;padding:0 10px;border:1px solid var(--border);border-radius:999px;color:var(--green-900);background:#f3fbf6;font-size:.72rem;font-weight:800}.live-attendance-tools{display:flex;align-items:center;justify-content:flex-end;gap:10px;flex-wrap:wrap}.live-attendance-search{width:min(100%,240px);min-height:36px;padding:0 11px;border:1px solid var(--border);border-radius:999px;outline:0;color:var(--text);background:#fff}.live-attendance-search:focus{border-color:var(--green-800);box-shadow:0 0 0 3px rgba(24,138,82,.1)}.live-attendance-wrap{max-height:340px}.live-attendance-empty[hidden]{display:none}@media(max-width:1280px){.attendance-summary{grid-template-columns:repeat(3,minmax(0,1fr))}.filter-panel-body{grid-template-columns:1fr}.filter-group+.filter-group{border-left:0;border-top:1px solid var(--border)}.attendance-sections{grid-template-columns:1fr}.staff-filter-group .filter-group-fields{grid-template-columns:repeat(3,minmax(0,1fr))}}@media(max-width:820px){.filter-panel-head{align-items:flex-start;flex-direction:column}.filter-actions,.filter-actions>*{width:100%}.range-filter-group .filter-group-fields,.staff-filter-group .filter-group-fields{grid-template-columns:1fr}.filter-group-copy{align-items:flex-start;flex-direction:column}.attendance-summary{grid-template-columns:1fr}.attendance-calendar{width:min(100%,390px);gap:8px 5px;padding-inline:8px}.calendar-empty,.calendar-day{min-height:46px}.calendar-day-number{width:31px;height:31px;font-size:.78rem}.calendar-day-counts i{min-width:12px;height:12px;font-size:.52rem}.force-form{grid-template-columns:1fr}.table-wrap{max-height:none}.live-attendance-tools{justify-content:flex-start;width:100%}.live-attendance-search{width:100%}}
</style>
<style>
    .filter-group-copy{display:grid;justify-content:start;gap:3px}
    .filter-group-copy small{display:block;color:var(--muted);font-size:.72rem;line-height:1.4}
    .filter-panel-body{grid-template-columns:minmax(260px,.75fr) minmax(520px,1.25fr)}
    .calendar-panel-body{display:grid;grid-template-columns:minmax(190px,.34fr) minmax(420px,.66fr);gap:22px;align-items:start;padding:18px 22px 24px}
    .calendar-panel .calendar-summary{padding:0}
    .calendar-summary-title{display:grid;gap:6px;align-items:start;justify-content:start}
    .calendar-summary-grid{display:grid;grid-template-columns:1fr;gap:8px;max-width:210px}
    .calendar-metric{justify-content:space-between;width:100%;min-height:34px;padding:7px 10px}
    .calendar-metric:not(.has-count) strong{display:none}
    .attendance-calendar{justify-self:end;width:min(100%,560px);margin:0;padding:0;gap:7px 6px}
    .calendar-empty,.calendar-day{min-height:41px}
    .calendar-day-number{width:32px;height:32px}
    .calendar-day-counts{min-height:12px}
    .calendar-day-counts:empty{display:none}
    .calendar-day-counts i{min-width:13px;height:13px;font-size:.5rem}
    @media(max-width:1080px){
        .calendar-panel-body{grid-template-columns:1fr;gap:16px}
        .calendar-summary-title{display:flex;align-items:end;justify-content:space-between}
        .calendar-summary-grid{grid-template-columns:repeat(4,minmax(0,1fr));max-width:none}
        .attendance-calendar{justify-self:center;width:min(100%,520px)}
    }
    @media(max-width:720px){
        .calendar-panel-body{padding:16px 14px 18px}
        .calendar-summary-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
        .calendar-empty,.calendar-day{min-height:36px}
        .calendar-day-number{width:29px;height:29px;font-size:.75rem}
    }
</style>

<section class="page-head">
    <div class="page-title">
        <span>Time and Attendance</span>
        <h1>Attendance Dashboard</h1>
    </div>
    <div class="report-range">{{ $rangeLabel }}</div>
</section>

<div class="attendance-dashboard">
    <section class="attendance-summary" aria-label="Attendance summary">
        <div class="attendance-card"><span>Staff Shown</span><strong>{{ $summary['staff'] }}</strong><small>Filtered accounts</small></div>
        <div class="attendance-card"><span>Present Today</span><strong>{{ $summary['present_today'] }}</strong><small>Timed in at least once</small></div>
        <div class="attendance-card danger"><span>Absent Today</span><strong>{{ $summary['absent_today'] }}</strong><small>Only after 5:00 PM</small></div>
        <div class="attendance-card"><span>Timed In Now</span><strong>{{ $summary['timed_in'] }}</strong><small>Open sessions</small></div>
        <div class="attendance-card warning"><span>Forgot Time Out</span><strong>{{ $summary['forgot_time_out'] }}</strong><small>Needs admin review</small></div>
        <div class="attendance-card"><span>Total Hours</span><strong>{{ intdiv($summary['rendered_minutes'], 60) }}h {{ $summary['rendered_minutes'] % 60 }}m</strong><small>Selected report range</small></div>
    </section>

    <section class="toolbar attendance-filter-panel">
        <form method="GET" action="{{ route('admin.attendance.index') }}" class="attendance-filter-form">
            <div class="filter-panel-head">
                <div>
                    <span>Dashboard Filters</span>
                    <h2>Calendar and staff report controls</h2>
                    <p>Set the date range for the calendar, then narrow the staff report by name, role, or department.</p>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="primary-button">Apply</button>
                    <button type="submit" class="primary-button print-button" formaction="{{ route('admin.attendance.print') }}" formtarget="_blank" data-print-report-button>Print</button>
                    <a href="{{ route('admin.attendance.index') }}" class="secondary-button">Reset</a>
                </div>
            </div>
            <div class="filter-panel-body">
                <div class="filter-group range-filter-group">
                    <div class="filter-group-copy">
                        <span>Calendar Range</span>
                        <strong>{{ ucfirst($period) }} view</strong>
                        <small>Controls the calendar and report period.</small>
                    </div>
                    <div class="filter-group-fields">
                        <div class="field">
                            <label for="period">Report</label>
                            <select id="period" name="period">
                                <option value="daily" @selected($period === 'daily')>Daily</option>
                                <option value="weekly" @selected($period === 'weekly')>Weekly</option>
                                <option value="monthly" @selected($period === 'monthly')>Monthly</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="date">Date</label>
                            <input id="date" type="date" name="date" value="{{ $anchor->toDateString() }}">
                        </div>
                    </div>
                </div>
                <div class="filter-group staff-filter-group">
                    <div class="filter-group-copy">
                        <span>Staff Report</span>
                        <strong>{{ ucfirst($period) }} staff list</strong>
                        <small>Search the table and printable staff list from one field.</small>
                    </div>
                    <div class="filter-group-fields">
                        <div class="field">
                            <label for="q">Staff Search</label>
                            <input id="q" type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Name, username, or email" list="staff_search_options">
                            <input id="print_user_id" type="hidden" value="">
                            <datalist id="staff_search_options">
                                @foreach($printableStaff as $staffMember)
                                    @php
                                        $staffLabel = $staffMember->full_name ?: $staffMember->username;
                                        $staffSearchValue = strtolower($staffLabel . ' ' . $staffMember->username . ' ' . $staffMember->email . ' ' . $staffMember->role . ' ' . ($staffMember->department?->name ?? ''));
                                    @endphp
                                    <option value="{{ $staffLabel }}" data-user-id="{{ $staffMember->id }}" data-username="{{ strtolower($staffMember->username) }}" data-email="{{ strtolower($staffMember->email) }}" data-search="{{ $staffSearchValue }}">
                                        {{ ucfirst($staffMember->role) }} / {{ $staffMember->department?->name ?? 'No department' }}
                                    </option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="field">
                            <label for="role">Role</label>
                            <select id="role" name="role">
                                <option value="">Professor and Faculty</option>
                                <option value="professor" @selected(($filters['role'] ?? '') === 'professor')>Professor</option>
                                <option value="faculty" @selected(($filters['role'] ?? '') === 'faculty')>Faculty</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="department_id">Department</label>
                            <select id="department_id" name="department_id">
                                <option value="">All departments</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" @selected((string) ($filters['department_id'] ?? '') === (string) $department->id)>{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

    <section class="table-panel calendar-panel">
        <div class="panel-head">
            <div>
                <span class="panel-kicker">Calendar</span>
                <h2>Present and absent overview</h2>
            </div>
            <span class="page-meta">Absence starts after 5:00 PM</span>
        </div>
        <div class="panel-body calendar-panel-body">
            <div class="calendar-summary" aria-label="Attendance summary for {{ $rangeLabel }}">
                <div class="calendar-summary-title">
                    <span>Legend</span>
                    <strong>{{ $rangeLabel }}</strong>
                </div>
                <div class="calendar-summary-grid">
                    <div class="calendar-metric present @if($calendarHasReportContext) has-count @endif"><span>Present</span>@if($calendarHasReportContext)<strong>{{ $calendarTotals['present'] }}</strong><em class="sr-only">{{ $calendarTotals['present'] }} present</em>@endif</div>
                    <div class="calendar-metric absent @if($calendarHasReportContext) has-count @endif"><span>Absent</span>@if($calendarHasReportContext)<strong>{{ $calendarTotals['absent'] }}</strong><em class="sr-only">{{ $calendarTotals['absent'] }} absent</em>@endif</div>
                    <div class="calendar-metric excused @if($calendarHasReportContext) has-count @endif"><span>Excused</span>@if($calendarHasReportContext)<strong>{{ $calendarTotals['excused'] }}</strong><em class="sr-only">{{ $calendarTotals['excused'] }} excused</em>@endif</div>
                    <div class="calendar-metric no-class @if($calendarHasReportContext) has-count @endif"><span>No Class</span>@if($calendarHasReportContext)<strong>{{ $calendarTotals['no_class'] }}</strong><em class="sr-only">{{ $calendarTotals['no_class'] }} no class</em>@endif</div>
                </div>
            </div>
            <div class="attendance-calendar" aria-label="Attendance calendar for {{ $rangeLabel }}">
                @foreach($weekdayLabels as $weekday)
                    <div class="calendar-weekday" aria-hidden="true">{{ $weekday }}</div>
                @endforeach
                @for($i = 0; $i < $calendarLeadingEmptyDays; $i++)
                    <div class="calendar-empty" aria-hidden="true"></div>
                @endfor
                @foreach($calendarDays as $day)
                    @php
                        $dayState = 'pending';
                        $dayTitle = $day['date']->format('F j, Y');

                        if ($calendarHasReportContext) {
                            $dayState = $day['absent'] > 0
                                ? 'absent'
                                : ($day['present'] > 0
                                    ? 'present'
                                    : ($day['excused'] > 0
                                        ? 'excused'
                                        : ($day['no_class'] > 0 ? 'no_class' : 'pending')));
                            $dayTitle .= ': ' . $day['present'] . ' present, '
                                . $day['absent'] . ' absent, '
                                . $day['excused'] . ' excused, '
                                . $day['no_class'] . ' no class';
                        }
                    @endphp
                    <div class="calendar-day {{ $dayState }}" title="{{ $dayTitle }}" aria-label="{{ $dayTitle }}">
                        <span class="calendar-day-number">{{ $day['date']->format('j') }}</span>
                        <span class="calendar-day-counts" aria-hidden="true">
                            @if($calendarHasReportContext)
                                @if($day['present'] > 0)
                                    <i class="present">{{ $day['present'] }}</i>
                                @endif
                                @if($day['absent'] > 0)
                                    <i class="absent">{{ $day['absent'] }}</i>
                                @endif
                                @if($day['excused'] > 0)
                                    <i class="excused">{{ $day['excused'] }}</i>
                                @endif
                                @if($day['no_class'] > 0)
                                    <i class="no-class">{{ $day['no_class'] }}</i>
                                @endif
                            @endif
                        </span>
                    </div>
                @endforeach
                @for($i = 0; $i < $calendarTrailingEmptyDays; $i++)
                    <div class="calendar-empty" aria-hidden="true"></div>
                @endfor
            </div>
        </div>
    </section>

    <section class="table-panel">
        <div class="panel-head">
            <div>
                <span class="panel-kicker">Attendance Reports</span>
                <h2>{{ ucfirst($period) }} staff report</h2>
            </div>
            <div class="panel-actions">
                @if($isDaily)
                    <span class="daily-report-note">Daily view hides day totals</span>
                @endif
                <span class="page-meta">{{ $reportRows->count() }} staff</span>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Staff</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Sessions</th>
                        @unless($isDaily)
                            <th>Present Days</th>
                            <th>Absent Days</th>
                        @endunless
                        <th>Total Hours Rendered</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportRows as $row)
                        @php
                            $user = $row['user'];
                            $name = $user->full_name ?: $user->username;
                        @endphp
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar">
                                        @if($user->profile_picture)
                                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="">
                                        @else
                                            {{ $initials($name) }}
                                        @endif
                                    </div>
                                    <div class="user-copy">
                                        <strong>{{ $name }}</strong>
                                        <span>{{ $user->username }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td>{{ $user->department?->name ?? 'No department' }}</td>
                            <td><span class="attendance-status {{ $statusClass($row['attendance_status']) }}">{{ $row['attendance_status'] }}</span></td>
                            <td>{{ $row['sessions'] }}</td>
                            @unless($isDaily)
                                <td>{{ $row['present_days'] }}</td>
                                <td>{{ $row['absent_days'] }}</td>
                            @endunless
                            <td class="duration-cell">{{ $row['rendered_label'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ $isDaily ? 6 : 8 }}" class="empty-row">No staff accounts match this filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const staffSearch = document.getElementById('q');
        const staffUserId = document.getElementById('print_user_id');
        const staffOptions = Array.from(document.querySelectorAll('#staff_search_options option')).map((option) => ({
            id: option.dataset.userId,
            label: option.value.trim().toLowerCase(),
            username: option.dataset.username || '',
            email: option.dataset.email || '',
            search: option.dataset.search || option.value.trim().toLowerCase(),
        }));
        const printButton = document.querySelector('[data-print-report-button]');

        if (staffSearch && staffUserId) {
            const resolvePrintableStaff = () => {
                const query = staffSearch.value.trim().toLowerCase();

                staffUserId.removeAttribute('name');
                staffUserId.value = '';

                if (!query) {
                    return null;
                }

                const exactMatches = staffOptions.filter((staff) => (
                    staff.label === query ||
                    staff.username === query ||
                    staff.email === query
                ));
                const matches = exactMatches.length ? exactMatches : staffOptions.filter((staff) => staff.search.includes(query));

                if (matches.length !== 1) {
                    return null;
                }

                staffUserId.value = matches[0].id;

                return matches[0];
            };

            staffSearch.addEventListener('input', resolvePrintableStaff);
            resolvePrintableStaff();

            if (printButton) {
                printButton.addEventListener('click', (event) => {
                    const resolvedStaff = resolvePrintableStaff();

                    if (resolvedStaff) {
                        staffUserId.setAttribute('name', 'user_id');
                        return;
                    }

                    event.preventDefault();
                    staffSearch.focus();
                    staffSearch.setCustomValidity('Type one unique staff name, username, or email before printing.');
                    staffSearch.reportValidity();
                    staffSearch.setCustomValidity('');
                });
            }
        }

    });
</script>
@endsection
