@php
    $displayName = $staff->full_name ?: $staff->username;
    $rangeEndDisplay = $rangeEnd->copy()->subSecond();
    $rangeLabel = $rangeStart->isSameDay($rangeEndDisplay)
        ? $rangeStart->format('F j, Y')
        : $rangeStart->format('F j, Y') . ' to ' . $rangeEndDisplay->format('F j, Y');
    $statusClass = fn ($status) => strtolower(str_replace(' ', '-', $status));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance Report - {{ $displayName }}</title>
    <style>
        :root {
            --green: #0b6b43;
            --green-dark: #073f2b;
            --green-soft: #e7f6ed;
            --line: #d9e6de;
            --muted: #5e7166;
            --danger: #b42318;
            --ink: #10281c;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            color: var(--ink);
            background: #eef7f1;
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.45;
        }

        .report-shell {
            width: min(1120px, calc(100% - 32px));
            margin: 28px auto;
            padding: 28px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 18px 50px rgba(11, 84, 53, 0.13);
        }

        .print-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 18px;
        }

        .print-actions a,
        .print-actions button {
            min-height: 40px;
            padding: 0 16px;
            border: 1px solid var(--line);
            border-radius: 7px;
            color: var(--green-dark);
            background: #ffffff;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
        }

        .print-actions button {
            color: #ffffff;
            border-color: var(--green);
            background: var(--green);
        }

        .report-head {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            align-items: start;
            padding-bottom: 18px;
            border-bottom: 3px solid var(--green);
        }

        .kicker {
            display: block;
            color: var(--green);
            font-size: 0.75rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        h1,
        h2,
        h3,
        p { margin: 0; }

        h1 {
            margin-top: 6px;
            font-size: 2rem;
            line-height: 1.1;
        }

        .report-meta {
            display: grid;
            gap: 5px;
            min-width: 260px;
            padding: 14px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--green-soft);
            font-size: 0.88rem;
        }

        .report-meta strong {
            color: var(--green-dark);
        }

        .staff-card,
        .summary-grid,
        .report-section {
            margin-top: 18px;
        }

        .staff-card {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1px;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--line);
        }

        .info-cell {
            min-height: 74px;
            padding: 13px 14px;
            background: #ffffff;
        }

        .info-cell span,
        th {
            color: var(--muted);
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .info-cell strong {
            display: block;
            margin-top: 5px;
            font-size: 0.98rem;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 10px;
        }

        .summary-card {
            padding: 14px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #f8fcfa;
        }

        .summary-card span {
            color: var(--muted);
            font-size: 0.68rem;
            font-weight: 900;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .summary-card strong {
            display: block;
            margin-top: 7px;
            color: var(--green-dark);
            font-size: 1.24rem;
        }

        .report-section {
            border: 1px solid var(--line);
            border-radius: 8px;
            overflow: hidden;
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            padding: 15px 16px;
            border-bottom: 1px solid var(--line);
            background: #f6fbf8;
        }

        .section-head h2 {
            margin-top: 3px;
            font-size: 1.08rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }

        th,
        td {
            padding: 11px 12px;
            border-bottom: 1px solid var(--line);
            text-align: left;
            vertical-align: top;
        }

        tbody tr:last-child td {
            border-bottom: 0;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            min-height: 25px;
            padding: 0 9px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 900;
        }

        .status-pill.present {
            color: #087238;
            background: #dff5e8;
        }

        .status-pill.absent {
            color: var(--danger);
            background: #fff0ef;
        }

        .status-pill.pending {
            color: var(--muted);
            background: #edf1ee;
        }

        .status-pill.excused {
            color: #6d3eb8;
            background: #f0e8ff;
        }

        .status-pill.no-class {
            color: var(--muted);
            background: #edf1ee;
        }

        .session-list {
            display: grid;
            gap: 8px;
        }

        .session-item {
            display: grid;
            gap: 3px;
        }

        .session-note {
            color: var(--danger);
            font-size: 0.78rem;
            font-weight: 700;
        }

        .duration {
            color: var(--green-dark);
            font-weight: 900;
            white-space: nowrap;
        }

        .empty {
            color: var(--muted);
            font-style: italic;
        }

        @media print {
            body {
                background: #ffffff;
            }

            .report-shell {
                width: 100%;
                margin: 0;
                padding: 0;
                border: 0;
                border-radius: 0;
                box-shadow: none;
            }

            .print-actions {
                display: none;
            }

            .report-section,
            .summary-card,
            .info-cell {
                break-inside: avoid;
            }

            @page {
                margin: 14mm;
            }
        }

        @media (max-width: 760px) {
            .report-head,
            .staff-card,
            .summary-grid {
                grid-template-columns: 1fr;
            }

            .report-shell {
                width: min(100% - 18px, 1120px);
                padding: 18px;
            }

            table {
                min-width: 720px;
            }

            .table-scroll {
                overflow-x: auto;
            }
        }
    </style>
    <x-minimal-ui />
</head>
<body>
    <main class="report-shell">
        <div class="print-actions">
            <a href="{{ route('admin.attendance.index') }}">Back to Attendance</a>
            <button type="button" onclick="window.print()">Print Report</button>
        </div>

        <header class="report-head">
            <div>
                <span class="kicker">Professor/Faculty Attendance Report</span>
                <h1>{{ $displayName }}</h1>
            </div>
            <div class="report-meta">
                <div><strong>Report Type:</strong> {{ ucfirst($period) }}</div>
                <div><strong>Period:</strong> {{ $rangeLabel }}</div>
                <div><strong>Generated:</strong> {{ now()->format('F j, Y g:i A') }}</div>
            </div>
        </header>

        <section class="staff-card" aria-label="Staff details">
            <div class="info-cell"><span>Name</span><strong>{{ $displayName }}</strong></div>
            <div class="info-cell"><span>Role</span><strong>{{ ucfirst($staff->role) }}</strong></div>
            <div class="info-cell"><span>Department</span><strong>{{ $staff->department?->name ?? 'No department' }}</strong></div>
            <div class="info-cell"><span>Username</span><strong>{{ $staff->username }}</strong></div>
        </section>

        <section class="summary-grid" aria-label="Attendance summary">
            <div class="summary-card"><span>Present Days</span><strong>{{ $summary['present_days'] }}</strong></div>
            <div class="summary-card"><span>Absent Days</span><strong>{{ $summary['absent_days'] }}</strong></div>
            <div class="summary-card"><span>Pending Days</span><strong>{{ $summary['pending_days'] }}</strong></div>
            <div class="summary-card"><span>Sessions</span><strong>{{ $summary['sessions'] }}</strong></div>
            <div class="summary-card"><span>Total Rendered</span><strong>{{ $summary['rendered_label'] }}</strong></div>
        </section>

        <section class="report-section">
            <div class="section-head">
                <div>
                    <span class="kicker">Daily Breakdown</span>
                    <h2>Attendance by date</h2>
                </div>
                <strong>{{ $summary['forced_time_outs'] }} forced time-out{{ $summary['forced_time_outs'] === 1 ? '' : 's' }}</strong>
            </div>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Time In / Time Out</th>
                            <th>Rendered Time</th>
                            <th>Admin Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dayRows as $row)
                            <tr>
                                <td>
                                    <strong>{{ $row['date']->format('M j, Y') }}</strong><br>
                                    <span>{{ $row['date']->format('l') }}</span>
                                </td>
                                <td><span class="status-pill {{ $statusClass($row['status']) }}">{{ $row['status'] }}</span></td>
                                <td>
                                    @if($row['records']->isEmpty())
                                        <span class="empty">No attendance session</span>
                                    @else
                                        <div class="session-list">
                                            @foreach($row['records'] as $record)
                                                <div class="session-item">
                                                    <strong>
                                                        {{ $record->time_in->format('g:i A') }}
                                                        -
                                                        {{ $record->time_out?->format('g:i A') ?? 'Open' }}
                                                    </strong>
                                                    @if($record->forced_time_out_at)
                                                        <span class="session-note">Forced time-out by {{ $record->forcedBy?->full_name ?: $record->forcedBy?->username ?: 'admin' }}</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="duration">{{ $row['rendered_label'] }}</td>
                                <td>
                                    @php
                                        $notes = $row['records']
                                            ->pluck('force_time_out_reason')
                                            ->filter()
                                            ->values();
                                    @endphp
                                    @forelse($notes as $note)
                                        <div>{{ $note }}</div>
                                    @empty
                                        <span class="empty">No admin notes</span>
                                    @endforelse
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
