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
@endphp

<style>
    .attendance-dashboard{display:grid;gap:16px}.attendance-summary{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:10px}.attendance-card{min-width:0;padding:16px;border:1px solid var(--border);border-radius:8px;background:#fff;box-shadow:var(--shadow)}.attendance-card span,.mini-calendar-day span{display:block;color:var(--muted);font-size:.68rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase}.attendance-card strong{display:block;margin-top:7px;color:var(--text);font-size:1.32rem}.attendance-card small{display:block;margin-top:2px;color:var(--muted);font-size:.75rem}.attendance-card.warning strong{color:var(--warning)}.attendance-card.danger strong{color:var(--danger)}.report-range{display:inline-flex;align-items:center;min-height:34px;padding:0 12px;border:1px solid var(--border);border-radius:999px;color:var(--green-900);background:#fff;font-size:.8rem;font-weight:800}.print-report-panel{display:grid;gap:16px;padding:18px;border:1px solid var(--border);border-radius:8px;background:#fff;box-shadow:var(--shadow)}.print-report-copy{display:grid;grid-template-columns:minmax(0,1fr) minmax(280px,.78fr);gap:12px;align-items:end;padding-bottom:14px;border-bottom:1px solid var(--border)}.print-report-copy span{color:var(--green-800);font-size:.72rem;font-weight:900;letter-spacing:.08em;text-transform:uppercase}.print-report-copy h2{margin:4px 0 0;color:var(--text);font-size:1.08rem}.print-report-copy p{margin:0;color:var(--muted);font-size:.82rem;line-height:1.45}.print-report-form{display:grid;grid-template-columns:minmax(250px,1.25fr) minmax(145px,.7fr) repeat(3,minmax(160px,.75fr)) minmax(140px,auto);gap:12px;align-items:end}.print-report-form .field{min-width:0}.print-report-form select,.print-report-form input{width:100%;min-height:42px}.print-report-form button{width:100%;min-height:42px;white-space:nowrap}.attendance-calendar{display:grid;grid-template-columns:repeat(auto-fit,minmax(92px,1fr));gap:8px}.mini-calendar-day{padding:12px;border:1px solid var(--border);border-radius:8px;background:#fff}.mini-calendar-day strong{display:block;margin:4px 0 9px;font-size:1rem}.mini-calendar-day .counts{display:flex;gap:6px;flex-wrap:wrap}.calendar-count{display:inline-flex;align-items:center;min-height:24px;padding:0 8px;border-radius:999px;font-size:.7rem;font-weight:800}.calendar-count.present{color:#087238;background:#dff5e8}.calendar-count.absent{color:var(--danger);background:#fff0ef}.calendar-count.pending{color:#5d6c63;background:#edf1ee}.calendar-count.excused{color:#6d3eb8;background:#f0e8ff}.calendar-count.no-class{color:#57665d;background:#eef4f0}.attendance-sections{display:grid;grid-template-columns:minmax(0,1fr) minmax(340px,.8fr);gap:16px}.force-form{display:grid;grid-template-columns:minmax(180px,1fr) auto;gap:8px;align-items:center}.force-form input{min-height:36px;padding:0 10px;border:1px solid var(--border);border-radius:6px}.force-form button{min-height:36px;padding:0 11px;border:1px solid #e8c69d;border-radius:6px;color:var(--warning);background:#fff8ed;font-size:.72rem;font-weight:800;cursor:pointer}.duration-cell{font-weight:900;color:var(--green-900)}.attendance-status{display:inline-flex;align-items:center;justify-content:center;min-height:25px;padding:0 9px;border-radius:999px;font-size:.7rem;font-weight:900}.attendance-status.present,.attendance-status.has-records{color:#087238;background:#dff5e8}.attendance-status.absent{color:var(--danger);background:#fff0ef}.attendance-status.pending,.attendance-status.no-records,.attendance-status.no-class{color:#5d6c63;background:#edf1ee}.attendance-status.excused{color:#6d3eb8;background:#f0e8ff}.subtle-table th{color:#55685b;background:#f4faf6}.forgot-list{display:grid;gap:10px;padding:16px}.forgot-card{display:grid;gap:7px;padding:13px;border:1px solid #e8c69d;border-radius:8px;background:#fff8ed}.forgot-card strong{font-size:.9rem}.forgot-card span{color:var(--muted);font-size:.77rem}.admin-note{margin:0;color:var(--muted);font-size:.82rem;line-height:1.5}@media(max-width:1280px){.print-report-form{grid-template-columns:repeat(3,minmax(0,1fr))}.print-report-form button{grid-column:3}}@media(max-width:1180px){.attendance-summary{grid-template-columns:repeat(3,minmax(0,1fr))}.attendance-sections{grid-template-columns:1fr}.print-report-copy{grid-template-columns:1fr}}@media(max-width:680px){.attendance-summary,.filter-grid,.print-report-form{grid-template-columns:1fr}.print-report-form button{grid-column:auto}.force-form{grid-template-columns:1fr}.attendance-card strong{font-size:1.15rem}}
    .filter-grid{grid-template-columns:minmax(125px,.65fr) minmax(150px,.8fr) minmax(230px,1.2fr) minmax(150px,.8fr) minmax(180px,1fr) auto}.print-report-panel{padding:20px}.print-report-copy{display:block;padding-bottom:12px}.print-report-copy h2{margin:5px 0 0;font-size:1.18rem}.print-report-copy p{max-width:720px;margin-top:8px}.print-report-form{grid-template-columns:minmax(220px,1fr) minmax(260px,1.2fr) minmax(130px,.6fr) repeat(3,minmax(145px,.72fr)) minmax(130px,auto);gap:10px}.print-report-form label,.filter-grid label{font-size:.68rem;letter-spacing:.07em}.daily-report-note{display:inline-flex;align-items:center;min-height:28px;padding:0 10px;border:1px solid var(--border);border-radius:999px;color:var(--green-900);background:#f3fbf6;font-size:.72rem;font-weight:800}.table-wrap{max-height:520px;overflow:auto}.table-wrap table thead th{position:sticky;top:0;z-index:1}.live-attendance-tools{display:flex;align-items:center;justify-content:flex-end;gap:10px;flex-wrap:wrap}.live-attendance-search{width:min(100%,240px);min-height:36px;padding:0 11px;border:1px solid var(--border);border-radius:999px;outline:0;color:var(--text);background:#fff}.live-attendance-search:focus{border-color:var(--green-800);box-shadow:0 0 0 3px rgba(24,138,82,.1)}.live-attendance-wrap{max-height:340px}.live-attendance-empty[hidden]{display:none}@media(max-width:1420px){.print-report-form{grid-template-columns:repeat(4,minmax(0,1fr))}.print-report-form button{grid-column:4}}@media(max-width:1320px){.filter-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}@media(max-width:960px){.print-report-form{grid-template-columns:repeat(2,minmax(0,1fr))}.print-report-form button{grid-column:auto}}@media(max-width:760px){.filter-grid,.print-report-form{grid-template-columns:1fr}.table-wrap{max-height:none}.live-attendance-tools{justify-content:flex-start;width:100%}.live-attendance-search{width:100%}}
    .attendance-filter-panel{padding:14px}.attendance-filter-form{display:grid;grid-template-columns:minmax(300px,.75fr) minmax(480px,1.25fr) auto;gap:12px;align-items:stretch}.filter-group{display:grid;grid-template-columns:minmax(118px,auto) minmax(0,1fr);gap:12px;padding:12px;border:1px solid var(--border);border-radius:8px;background:#f8fcf9}.filter-group-copy span{display:block;color:var(--green-800);font-size:.67rem;font-weight:900;letter-spacing:.08em;text-transform:uppercase}.filter-group-copy strong{display:block;margin-top:5px;color:var(--text);font-size:.9rem;line-height:1.2}.filter-group-copy small{display:block;margin-top:4px;color:var(--muted);font-size:.72rem;line-height:1.25}.filter-group-fields{display:grid;gap:10px;align-items:end}.range-filter-group .filter-group-fields{grid-template-columns:minmax(120px,.75fr) minmax(155px,1fr)}.staff-filter-group .filter-group-fields{grid-template-columns:minmax(190px,1.2fr) minmax(135px,.7fr) minmax(170px,.9fr)}.attendance-filter-form .filter-actions{align-items:end;justify-content:flex-end}.attendance-filter-form .filter-actions .primary-button,.attendance-filter-form .filter-actions .secondary-button{min-width:72px}@media(max-width:1420px){.attendance-filter-form{grid-template-columns:1fr}.attendance-filter-form .filter-actions{justify-content:flex-start}.staff-filter-group .filter-group-fields{grid-template-columns:repeat(3,minmax(0,1fr))}}@media(max-width:820px){.filter-group{grid-template-columns:1fr}.range-filter-group .filter-group-fields,.staff-filter-group .filter-group-fields{grid-template-columns:1fr}.attendance-filter-form .filter-actions,.attendance-filter-form .filter-actions>*{width:100%}}
    .attendance-filter-panel{padding:0;overflow:hidden}.attendance-filter-form{grid-template-columns:1fr;gap:0}.filter-panel-head{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:16px 18px;border-bottom:1px solid var(--border);background:#fff}.filter-panel-head span{display:block;color:var(--green-800);font-size:.68rem;font-weight:900;letter-spacing:.08em;text-transform:uppercase}.filter-panel-head h2{margin:4px 0 0;color:var(--text);font-size:1.05rem}.filter-panel-head p{margin:4px 0 0;color:var(--muted);font-size:.78rem}.filter-panel-body{display:grid;grid-template-columns:minmax(260px,.7fr) minmax(420px,1.3fr);gap:0;background:#fff}.filter-group{display:block;padding:16px 18px;border:0;border-radius:0;background:#fff}.filter-group+.filter-group{border-left:1px solid var(--border)}.filter-group-copy{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:12px}.filter-group-copy span{font-size:.68rem}.filter-group-copy strong{margin:0;font-size:.92rem}.filter-group-copy small{display:none}.range-filter-group .filter-group-fields{grid-template-columns:minmax(130px,.75fr) minmax(160px,1fr)}.staff-filter-group .filter-group-fields{grid-template-columns:minmax(210px,1.25fr) minmax(150px,.75fr) minmax(190px,1fr)}.attendance-filter-form .filter-actions{flex:0 0 auto}.attendance-filter-form .filter-actions .primary-button,.attendance-filter-form .filter-actions .secondary-button{min-width:76px}.filter-group .field input,.filter-group .field select{min-height:40px}@media(max-width:1180px){.filter-panel-body{grid-template-columns:1fr}.filter-group+.filter-group{border-left:0;border-top:1px solid var(--border)}}@media(max-width:840px){.filter-panel-head{align-items:flex-start;flex-direction:column}.attendance-filter-form .filter-actions,.attendance-filter-form .filter-actions>*{width:100%}.range-filter-group .filter-group-fields,.staff-filter-group .filter-group-fields{grid-template-columns:1fr}.filter-group-copy{align-items:flex-start;flex-direction:column}}
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

    <section class="print-report-panel">
        <div class="print-report-copy">
            <span>Printable Reports</span>
            <h2>Generate professor/faculty report</h2>
            <p>Choose one staff member and print a clean attendance summary with daily status, sessions, rendered hours, and admin force time-out notes.</p>
        </div>
        <form method="GET" action="{{ route('admin.attendance.print') }}" class="print-report-form" target="_blank">
            <div class="field">
                <label for="print_staff_search">Search Staff</label>
                <input id="print_staff_search" type="search" placeholder="Type a name or department" autocomplete="off">
            </div>
            <div class="field">
                <label for="print_user_id">Staff</label>
                <select id="print_user_id" name="user_id" required>
                    <option value="">Select staff</option>
                    @foreach($printableStaff as $staffMember)
                        <option value="{{ $staffMember->id }}" data-search="{{ strtolower(($staffMember->full_name ?: $staffMember->username) . ' ' . $staffMember->username . ' ' . $staffMember->role . ' ' . ($staffMember->department?->name ?? '')) }}">
                            {{ $staffMember->full_name ?: $staffMember->username }} / {{ ucfirst($staffMember->role) }} / {{ $staffMember->department?->name ?? 'No department' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="print_period">Type</label>
                <select id="print_period" name="period">
                    <option value="monthly">Monthly</option>
                    <option value="weekly">Weekly</option>
                    <option value="daily">Daily</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
            <div class="field">
                <label for="print_date">Date</label>
                <input id="print_date" type="date" name="date" value="{{ now()->toDateString() }}">
            </div>
            <div class="field">
                <label for="print_start_date">Custom Start</label>
                <input id="print_start_date" type="date" name="start_date">
            </div>
            <div class="field">
                <label for="print_end_date">Custom End</label>
                <input id="print_end_date" type="date" name="end_date">
            </div>
            <button type="submit" class="primary-button">Open Report</button>
        </form>
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
                        <small>Search and filter the table below.</small>
                    </div>
                    <div class="filter-group-fields">
                        <div class="field">
                            <label for="q">Staff Search</label>
                            <input id="q" type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Name, username, or email">
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

    <section class="table-panel">
        <div class="panel-head">
            <div>
                <span class="panel-kicker">Calendar</span>
                <h2>Present and absent overview</h2>
            </div>
            <span class="page-meta">Absence starts after 5:00 PM</span>
        </div>
        <div class="panel-body">
            <div class="attendance-calendar">
                @foreach($calendarDays as $day)
                    <div class="mini-calendar-day">
                        <span>{{ $day['date']->format('D') }}</span>
                        <strong>{{ $day['date']->format('M j') }}</strong>
                        <div class="counts">
                            <span class="calendar-count present">{{ $day['present'] }} present</span>
                            @if($day['pending'] > 0)
                                <span class="calendar-count pending">{{ $day['pending'] }} pending</span>
                            @endif
                            @if($day['absent'] > 0)
                                <span class="calendar-count absent">{{ $day['absent'] }} absent</span>
                            @endif
                            @if($day['excused'] > 0)
                                <span class="calendar-count excused">{{ $day['excused'] }} excused</span>
                            @endif
                            @if($day['no_class'] > 0)
                                <span class="calendar-count no-class">{{ $day['no_class'] }} no class</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="attendance-sections">
        <section class="table-panel">
            <div class="panel-head">
                <div>
                    <span class="panel-kicker">Live Attendance</span>
                    <h2>Currently timed in</h2>
                </div>
                <div class="live-attendance-tools">
                    @if($openAttendances->isNotEmpty())
                        <input id="live_attendance_search" class="live-attendance-search" type="search" placeholder="Search timed in staff" autocomplete="off">
                    @endif
                    <span class="page-meta">{{ $openAttendances->count() }} open</span>
                </div>
            </div>
            <div class="table-wrap live-attendance-wrap">
                <table class="subtle-table">
                    <thead>
                        <tr>
                            <th>Staff</th>
                            <th>Time In</th>
                            <th>Duration</th>
                            <th>Admin Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($openAttendances as $attendance)
                            @php
                                $name = $attendance->user->full_name ?: $attendance->user->username;
                                $liveSearchText = strtolower($name . ' ' . $attendance->user->username . ' ' . $attendance->user->role . ' ' . ($attendance->user->department?->name ?? ''));
                            @endphp
                            <tr data-live-attendance-row data-live-search="{{ $liveSearchText }}">
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar">
                                            @if($attendance->user->profile_picture)
                                                <img src="{{ asset('storage/' . $attendance->user->profile_picture) }}" alt="">
                                            @else
                                                {{ $initials($name) }}
                                            @endif
                                        </div>
                                        <div class="user-copy">
                                            <strong>{{ $name }}</strong>
                                            <span>{{ ucfirst($attendance->user->role) }} / {{ $attendance->user->department?->name ?? 'No department' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $attendance->time_in->format('M j, g:i A') }}</td>
                                <td class="duration-cell">{{ $attendance->time_in->diffForHumans(now(), true) }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.attendance.force_time_out', $attendance->user) }}" class="force-form">
                                        @csrf
                                        <input type="text" name="reason" placeholder="Reason for force time-out" required>
                                        <button type="submit">Force Time Out</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="empty-row">No one is currently timed in.</td></tr>
                        @endforelse
                        @if($openAttendances->isNotEmpty())
                            <tr id="live_attendance_empty" class="live-attendance-empty" hidden>
                                <td colspan="4" class="empty-row">No timed-in staff match your search.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </section>

        <section class="table-panel">
            <div class="panel-head">
                <div>
                    <span class="panel-kicker">Review</span>
                    <h2>Forgot to time out</h2>
                </div>
                <span class="page-meta">{{ $forgotAttendances->count() }} flagged</span>
            </div>
            @if($forgotAttendances->isEmpty())
                <div class="empty-row">No forgotten time-out sessions right now.</div>
            @else
                <div class="forgot-list">
                    @foreach($forgotAttendances as $attendance)
                        @php $name = $attendance->user->full_name ?: $attendance->user->username; @endphp
                        <div class="forgot-card">
                            <strong>{{ $name }}</strong>
                            <span>Timed in {{ $attendance->time_in->format('M j, Y g:i A') }}</span>
                            <p class="admin-note">This session is still open after the attendance cutoff. Use force time-out if the staff member forgot to close the session.</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
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
        const staffSearch = document.getElementById('print_staff_search');
        const staffSelect = document.getElementById('print_user_id');

        if (!staffSearch || !staffSelect) {
            return;
        }

        const options = Array.from(staffSelect.options).slice(1);

        staffSearch.addEventListener('input', () => {
            const query = staffSearch.value.trim().toLowerCase();
            let firstVisible = null;

            options.forEach((option) => {
                const matches = !query || option.dataset.search.includes(query);
                option.hidden = !matches;

                if (matches && !firstVisible) {
                    firstVisible = option;
                }
            });

            if (staffSelect.selectedOptions[0]?.hidden) {
                staffSelect.value = firstVisible?.value || '';
            }
        });

        const liveAttendanceSearch = document.getElementById('live_attendance_search');
        const liveAttendanceRows = Array.from(document.querySelectorAll('[data-live-attendance-row]'));
        const liveAttendanceEmpty = document.getElementById('live_attendance_empty');

        if (liveAttendanceSearch && liveAttendanceRows.length) {
            liveAttendanceSearch.addEventListener('input', () => {
                const query = liveAttendanceSearch.value.trim().toLowerCase();
                let visibleRows = 0;

                liveAttendanceRows.forEach((row) => {
                    const matches = !query || row.dataset.liveSearch.includes(query);
                    row.hidden = !matches;

                    if (matches) {
                        visibleRows++;
                    }
                });

                if (liveAttendanceEmpty) {
                    liveAttendanceEmpty.hidden = visibleRows > 0;
                }
            });
        }
    });
</script>
@endsection
