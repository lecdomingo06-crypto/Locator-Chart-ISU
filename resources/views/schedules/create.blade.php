<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Professor Tracker') }} - Weekly Schedule</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800" rel="stylesheet" />

    <style>
        :root {
            color-scheme: light;
            --bg: #edf7f0;
            --card: rgba(255, 255, 255, 0.94);
            --card-soft: #f3fbf5;
            --card-border: rgba(12, 92, 56, 0.12);
            --text: #113322;
            --muted: #607766;
            --green-900: #0c5c38;
            --green-800: #147247;
            --green-700: #1b8a53;
            --green-100: #ddf4e4;
            --danger: #b91c1c;
            --danger-soft: #fff1f1;
            --shadow: 0 18px 40px rgba(12, 92, 56, 0.1);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
        }

        body {
            font-family: 'Outfit', sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(90, 193, 125, 0.24), transparent 22%),
                radial-gradient(circle at 88% 14%, rgba(32, 153, 90, 0.18), transparent 18%),
                linear-gradient(180deg, #f7fcf8 0%, var(--bg) 100%);
        }

        .workspace-shell {
            min-height: 100vh;
        }

        .workspace-topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            min-height: 76px;
            padding: 14px 28px;
            color: #effcf3;
            background: linear-gradient(135deg, #094629 0%, #0c5c38 56%, #147247 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 16px 34px rgba(8, 58, 35, 0.2);
        }

        .workspace-brand,
        .workspace-session,
        .workspace-chip,
        .sidebar-brand,
        .sidebar-link,
        .logout-button {
            display: inline-flex;
            align-items: center;
        }

        .workspace-brand {
            gap: 14px;
            color: inherit;
            text-decoration: none;
        }

        .workspace-brand-mark,
        .sidebar-mark {
            display: grid;
            place-items: center;
            border-radius: 8px;
            font-weight: 800;
        }

        .workspace-brand-mark {
            width: 48px;
            height: 48px;
            color: var(--green-900);
            background: rgba(255, 255, 255, 0.94);
            box-shadow: inset 0 0 0 1px rgba(12, 92, 56, 0.08);
        }

        .workspace-brand-copy,
        .sidebar-copy {
            display: grid;
            gap: 3px;
        }

        .workspace-brand-copy strong,
        .sidebar-copy strong {
            font-size: 1rem;
        }

        .workspace-brand-copy span,
        .sidebar-copy span,
        .sidebar-note {
            font-size: 0.88rem;
            line-height: 1.5;
        }

        .workspace-brand-copy span {
            color: rgba(239, 252, 243, 0.76);
            font-weight: 600;
        }

        .workspace-session {
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .workspace-chip {
            min-height: 34px;
            padding: 0 13px;
            border-radius: 999px;
            color: rgba(239, 252, 243, 0.95);
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.12);
            font-size: 0.86rem;
            font-weight: 700;
        }

        .logout-form {
            margin: 0;
        }

        .logout-button {
            justify-content: center;
            min-height: 38px;
            padding: 0 16px;
            border: 0;
            border-radius: 999px;
            color: var(--green-900);
            background: #ffffff;
            font-size: 0.86rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 12px 22px rgba(5, 51, 30, 0.16);
        }

        .workspace-layout {
            display: grid;
            grid-template-columns: 244px minmax(0, 1fr);
            gap: 26px;
            align-items: start;
        }

        .workspace-sidebar {
            position: sticky;
            top: 94px;
            min-height: calc(100vh - 112px);
            margin-left: 16px;
            padding: 18px 14px;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid var(--card-border);
            border-left: 0;
            border-radius: 0 8px 8px 0;
            box-shadow: var(--shadow);
        }

        .sidebar-brand {
            gap: 12px;
            padding: 0 0 18px;
            border-bottom: 1px solid rgba(12, 92, 56, 0.1);
        }

        .sidebar-mark {
            flex: 0 0 54px;
            width: 54px;
            height: 54px;
            color: #ffffff;
            background: linear-gradient(145deg, var(--green-800), var(--green-900));
        }

        .sidebar-copy span,
        .sidebar-note {
            color: var(--muted);
        }

        .sidebar-note {
            margin: 14px 0 18px;
        }

        .sidebar-section-label {
            display: block;
            margin: 0 0 10px;
            color: #6c7d70;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .sidebar-nav {
            display: grid;
            gap: 8px;
        }

        .sidebar-link {
            gap: 11px;
            min-height: 44px;
            padding: 0 12px;
            border-radius: 8px;
            color: var(--text);
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 800;
        }

        .sidebar-link svg {
            width: 18px;
            height: 18px;
            flex: 0 0 auto;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .sidebar-link:hover {
            color: var(--green-900);
            background: var(--green-100);
        }

        .sidebar-link.is-active {
            color: #ffffff;
            background: linear-gradient(135deg, var(--green-800), var(--green-900));
            box-shadow: 0 12px 24px rgba(12, 92, 56, 0.16);
        }

        .workspace-main .page {
            padding: 24px 28px 32px 0;
        }

        .shell {
            display: grid;
            gap: 18px;
        }

        .page-title {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 16px;
            padding: 2px 2px 0;
        }

        .title-copy {
            display: grid;
            gap: 6px;
        }

        .title-copy span {
            color: var(--green-800);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .title-copy h1 {
            margin: 0;
            font-size: 1.45rem;
            letter-spacing: -0.03em;
        }

        .title-pill {
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 999px;
            color: var(--green-900);
            background: rgba(221, 244, 228, 0.8);
            border: 1px solid rgba(20, 114, 71, 0.1);
            font-size: 0.82rem;
            font-weight: 700;
        }

        .content-grid {
            display: grid;
            grid-template-columns: minmax(330px, 0.72fr) minmax(0, 1.28fr);
            gap: 18px;
            align-items: start;
        }

        .form-panel,
        .table-panel,
        .empty-state {
            position: relative;
            overflow: hidden;
            border: 1px solid var(--card-border);
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(244, 251, 246, 0.96));
            box-shadow: var(--shadow);
        }

        .form-panel::before,
        .table-panel::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 4px;
            background: linear-gradient(90deg, var(--green-900), var(--green-700));
        }

        .form-panel,
        .table-panel {
            padding: 20px;
        }

        .panel-head {
            display: grid;
            gap: 6px;
            margin-bottom: 18px;
        }

        .panel-head span {
            color: var(--green-800);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .panel-head h2 {
            margin: 0;
            font-size: 1.25rem;
            letter-spacing: -0.03em;
        }

        .schedule-form {
            display: grid;
            gap: 14px;
        }

        .field-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        .field {
            display: grid;
            gap: 8px;
            min-width: 0;
        }

        .field label {
            color: var(--green-900);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .field input,
        .field select {
            width: 100%;
            min-height: 44px;
            padding: 0 13px;
            border: 1px solid var(--card-border);
            border-radius: 12px;
            color: var(--text);
            background: rgba(255, 255, 255, 0.96);
            font: inherit;
            outline: none;
        }

        .field input:focus,
        .field select:focus {
            border-color: rgba(20, 114, 71, 0.42);
            box-shadow: 0 0 0 4px rgba(20, 114, 71, 0.12);
        }

        .field-error {
            margin: 0;
            color: var(--danger);
            font-size: 0.82rem;
            font-weight: 700;
        }

        .error-summary {
            display: grid;
            gap: 8px;
            margin-bottom: 16px;
            padding: 14px;
            border-radius: 14px;
            color: var(--danger);
            background: var(--danger-soft);
            border: 1px solid rgba(185, 28, 28, 0.14);
            font-weight: 700;
        }

        .error-summary ul {
            margin: 0;
            padding-left: 18px;
        }

        .form-actions,
        .modal-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .primary-button,
        .secondary-link,
        .danger-button,
        .ghost-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 0 16px;
            border-radius: 12px;
            font: inherit;
            font-size: 0.86rem;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
        }

        .primary-button {
            border: 0;
            color: #ffffff;
            background: linear-gradient(135deg, var(--green-800), var(--green-900));
            box-shadow: 0 14px 28px rgba(20, 114, 71, 0.2);
        }

        .secondary-link,
        .ghost-button {
            color: var(--green-900);
            background: rgba(221, 244, 228, 0.72);
            border: 1px solid rgba(20, 114, 71, 0.16);
        }

        .danger-button {
            border: 0;
            color: #ffffff;
            background: var(--danger);
        }

        .schedule-table-wrap {
            overflow-x: auto;
            border: 1px solid rgba(20, 114, 71, 0.1);
            border-radius: 16px;
            background: #ffffff;
        }

        .weekly-table {
            width: 100%;
            min-width: 780px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .weekly-table th,
        .weekly-table td {
            border: 1px solid rgba(20, 114, 71, 0.12);
            vertical-align: top;
        }

        .weekly-table th {
            height: 42px;
            padding: 10px;
            color: var(--green-900);
            background: rgba(221, 244, 228, 0.72);
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .weekly-table .time-col {
            width: 132px;
            color: var(--muted);
            background: rgba(247, 252, 248, 0.9);
        }

        .weekly-table td {
            min-height: 74px;
            padding: 8px;
            background: rgba(255, 255, 255, 0.88);
            overflow: hidden;
        }

        .time-slot {
            display: grid;
            gap: 4px;
            color: var(--text);
            font-size: 0.78rem;
            font-weight: 800;
        }

        .time-slot span {
            color: var(--muted);
            font-size: 0.72rem;
            font-weight: 700;
        }

        .schedule-cell {
            display: grid;
            gap: 6px;
            min-width: 0;
        }

        .schedule-chip {
            display: grid;
            gap: 4px;
            width: 100%;
            min-width: 0;
            max-width: 100%;
            min-height: 58px;
            padding: 9px 10px;
            border: 1px solid rgba(20, 114, 71, 0.18);
            border-radius: 12px;
            color: var(--text);
            background: linear-gradient(180deg, rgba(221, 244, 228, 0.9), rgba(232, 245, 236, 0.72));
            font: inherit;
            text-align: left;
            cursor: pointer;
        }

        .schedule-chip:hover {
            border-color: rgba(20, 114, 71, 0.32);
            box-shadow: 0 12px 24px rgba(12, 92, 56, 0.1);
        }

        .schedule-chip strong {
            font-size: 0.88rem;
            line-height: 1.25;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .schedule-chip span {
            color: var(--muted);
            font-size: 0.76rem;
            font-weight: 700;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .empty-cell {
            min-height: 58px;
            border-radius: 10px;
            background: repeating-linear-gradient(
                135deg,
                rgba(20, 114, 71, 0.025),
                rgba(20, 114, 71, 0.025) 6px,
                rgba(20, 114, 71, 0.055) 7px
            );
        }

        .empty-state {
            padding: 22px;
            text-align: center;
        }

        .empty-state h3 {
            margin: 0 0 8px;
            font-size: 1.15rem;
        }

        .empty-state p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
        }

        .schedule-modal {
            position: fixed;
            inset: 0;
            z-index: 90;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(8, 41, 25, 0.42);
        }

        .schedule-modal.is-open {
            display: flex;
        }

        .modal-dialog {
            width: min(500px, 100%);
            overflow: hidden;
            border: 1px solid rgba(20, 114, 71, 0.14);
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 28px 70px rgba(8, 41, 25, 0.28);
        }

        .modal-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 22px;
            border-bottom: 1px solid rgba(20, 114, 71, 0.1);
        }

        .modal-head span {
            color: var(--green-800);
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .modal-head h3 {
            margin: 5px 0 0;
            font-size: 1.25rem;
            letter-spacing: -0.03em;
        }

        .modal-close {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border: 1px solid rgba(20, 114, 71, 0.14);
            border-radius: 999px;
            color: var(--green-900);
            background: rgba(221, 244, 228, 0.72);
            font: inherit;
            font-weight: 800;
            cursor: pointer;
        }

        .modal-body {
            display: grid;
            gap: 16px;
            padding: 20px 22px 22px;
        }

        .modal-details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .modal-detail {
            padding: 12px;
            border-radius: 14px;
            background: rgba(221, 244, 228, 0.52);
            border: 1px solid rgba(20, 114, 71, 0.1);
        }

        .modal-detail span {
            display: block;
            color: var(--muted);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .modal-detail strong {
            display: block;
            margin-top: 6px;
        }

        .delete-form {
            display: contents;
        }

        @media (max-width: 1180px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 1024px) {
            .workspace-topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .workspace-session {
                justify-content: flex-start;
            }

            .workspace-layout {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .workspace-sidebar {
                position: static;
                min-height: 0;
                margin: 16px 16px 0;
                border-left: 1px solid var(--card-border);
                border-radius: 8px;
            }

            .sidebar-nav {
                grid-template-columns: repeat(5, minmax(0, 1fr));
            }

            .workspace-main .page {
                padding: 0 16px 24px;
            }
        }

        @media (max-width: 720px) {
            .workspace-topbar {
                padding: 14px 16px;
            }

            .workspace-brand-copy span {
                display: none;
            }

            .workspace-session,
            .workspace-chip,
            .logout-form,
            .logout-button {
                width: 100%;
            }

            .workspace-sidebar {
                margin: 14px 14px 0;
                padding: 16px;
            }

            .sidebar-nav,
            .field-grid,
            .modal-details {
                grid-template-columns: 1fr;
            }

            .workspace-main .page {
                padding: 0 14px 20px;
            }

            .page-title {
                align-items: flex-start;
                flex-direction: column;
            }

            .form-panel,
            .table-panel {
                padding: 16px;
                border-radius: 18px;
            }

            .primary-button,
            .secondary-link,
            .danger-button,
            .ghost-button {
                width: 100%;
            }
        }
    </style>
    <x-minimal-ui />
</head>
<body>
@php
    $user = auth()->user();
    $displayName = $user->full_name ?: $user->username;
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag;
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $timeSlots = $schedules
        ->map(fn ($schedule) => [
            'key' => $schedule->start_time . '|' . $schedule->end_time,
            'start' => $schedule->start_time,
            'end' => $schedule->end_time,
        ])
        ->unique('key')
        ->sortBy('start')
        ->values();
    $schedulesBySlotDay = $schedules->groupBy(
        fn ($schedule) => $schedule->start_time . '|' . $schedule->end_time . '|' . $schedule->day_of_week
    );
    $formatTime = fn ($time) => \Carbon\Carbon::parse($time)->format('g:i A');
@endphp

    <div class="workspace-shell">
        <header class="workspace-topbar">
            <a href="{{ route('staff.viewer') }}" class="workspace-brand">
                <div class="workspace-brand-mark" aria-hidden="true"><img src="{{ asset('images/isulogo.jpg') }}" alt=""></div>
                <div class="workspace-brand-copy">
                    <strong>Professor Tracking System</strong>
                    <span>Professor workspace</span>
                </div>
            </a>

            <div class="workspace-session">
                <span class="workspace-chip">Role: {{ ucfirst($user->role) }}</span>
                <span class="workspace-chip">Signed in as {{ $displayName }}</span>
            </div>
        </header>

        <div class="workspace-layout">
            <aside class="workspace-sidebar">
                <div class="sidebar-brand">
                    <div class="sidebar-mark" aria-hidden="true"><img src="{{ asset('images/isulogo.jpg') }}" alt=""></div>
                    <div class="sidebar-copy">
                        <strong>Professor Panel</strong>
                        <span>Schedule and profile tools</span>
                    </div>
                </div>

                <p class="sidebar-note">Create regular class schedules and review saved weekly entries.</p>

                <span class="sidebar-section-label">Workspace</span>
                <nav class="sidebar-nav" aria-label="Professor workspace">
                    <a href="{{ route('staff.viewer') }}" class="sidebar-link{{ request()->routeIs('staff.viewer') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M2.5 12s3.5-5.5 9.5-5.5 9.5 5.5 9.5 5.5-3.5 5.5-9.5 5.5S2.5 12 2.5 12Z"></path>
                            <path d="M12 15a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z"></path>
                        </svg>
                        <span>Live Viewer</span>
                    </a>

                    <a href="{{ route('attendance.show') }}" class="sidebar-link{{ request()->routeIs('attendance.*') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 7v5l3 2"></path>
                        </svg>
                        <span>Attendance</span>
                    </a>
                    <a href="{{ route('availability.show') }}" class="sidebar-link{{ request()->routeIs('availability.*') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 2v4"></path>
                            <path d="M12 18v4"></path>
                            <path d="M4.9 4.9l2.8 2.8"></path>
                            <path d="M16.3 16.3l2.8 2.8"></path>
                            <path d="M2 12h4"></path>
                            <path d="M18 12h4"></path>
                            <path d="M4.9 19.1l2.8-2.8"></path>
                            <path d="M16.3 7.7l2.8-2.8"></path>
                        </svg>
                        <span>Availability</span>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="sidebar-link{{ request()->routeIs('profile.edit') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 12a4 4 0 1 0 0-8a4 4 0 0 0 0 8Z"></path>
                            <path d="M4.5 20c.8-3.8 3.4-5.8 7.5-5.8s6.7 2 7.5 5.8"></path>
                        </svg>
                        <span>Profile</span>
                    </a>

                    <a href="{{ route('schedules.create') }}" class="sidebar-link{{ request()->routeIs('schedules.*') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M7 3v4"></path>
                            <path d="M17 3v4"></path>
                            <path d="M4 8h16"></path>
                            <path d="M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"></path>
                            <path d="M8 13h8"></path>
                            <path d="M8 17h5"></path>
                        </svg>
                        <span>Weekly Schedule</span>
                    </a>

                    <a href="{{ route('special_schedules.create') }}" class="sidebar-link{{ request()->routeIs('special_schedules.*') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 6l1.6 4.4L18 12l-4.4 1.6L12 18l-1.6-4.4L6 12l4.4-1.6L12 6Z"></path>
                            <path d="M19 4v4"></path>
                            <path d="M21 6h-4"></path>
                        </svg>
                        <span>Special Schedule</span>
                    </a>
                </nav>
            <x-sidebar-account-footer />
            </aside>
<x-responsive-sidebar-control />

            <main class="workspace-main">
                <div class="page">
                    <div class="shell">
                        <section class="page-title" aria-label="Weekly schedule title">
                            <div class="title-copy">
                                <span>Weekly Schedule</span>
                                <h1>Create Schedule Entry</h1>
                            </div>

                            <div class="title-pill">{{ $schedules->count() }} saved schedule{{ $schedules->count() === 1 ? '' : 's' }}</div>
                        </section>

                        <x-flash-toast />

                        <section class="content-grid">
                            <section class="form-panel">
                                <div class="panel-head">
                                    <span>Add Schedule</span>
                                    <h2>Class Details</h2>
                                </div>

                                @if($errors->any())
                                    <section class="error-summary" aria-label="Validation errors">
                                        <strong>Please review the highlighted schedule details.</strong>
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </section>
                                @endif

                                <form method="POST" action="{{ route('schedules.store') }}" class="schedule-form">
                                    @csrf

                                    <div class="field-grid">
                                        <div class="field">
                                            <label for="subject">Subject</label>
                                            <input id="subject" type="text" name="subject" value="{{ old('subject') }}" placeholder="Enter subject">
                                            @error('subject')
                                                <p class="field-error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="field">
                                            <label for="room">Room</label>
                                            <input id="room" type="text" name="room" value="{{ old('room') }}" placeholder="Enter room">
                                            @error('room')
                                                <p class="field-error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="field full">
                                            <label for="day_of_week">Day</label>
                                            <select id="day_of_week" name="day_of_week">
                                                <option value="">Select day</option>
                                                @foreach($days as $day)
                                                    <option value="{{ $day }}" {{ old('day_of_week') === $day ? 'selected' : '' }}>{{ $day }}</option>
                                                @endforeach
                                            </select>
                                            @error('day_of_week')
                                                <p class="field-error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="field">
                                            <label for="start_time">Start</label>
                                            <input id="start_time" type="time" name="start_time" value="{{ old('start_time') }}">
                                            @error('start_time')
                                                <p class="field-error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="field">
                                            <label for="end_time">End</label>
                                            <input id="end_time" type="time" name="end_time" value="{{ old('end_time') }}">
                                            @error('end_time')
                                                <p class="field-error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="field">
                                            <label for="semester">Semester</label>
                                            <input id="semester" type="text" name="semester" value="{{ old('semester') }}" placeholder="Enter semester">
                                            @error('semester')
                                                <p class="field-error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="field">
                                            <label for="school_year">School Year</label>
                                            <input id="school_year" type="text" name="school_year" value="{{ old('school_year') }}" placeholder="Enter school year">
                                            @error('school_year')
                                                <p class="field-error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="primary-button">Save Schedule</button>
                                    </div>
                                </form>
                            </section>

                            <section class="table-panel">
                                <div class="panel-head">
                                    <span>Schedule Saved</span>
                                    <h2>Weekly Timetable</h2>
                                </div>

                                @if($schedules->isEmpty())
                                    <section class="empty-state">
                                        <h3>No schedules saved yet.</h3>
                                        <p>Add your first class entry to build your weekly timetable.</p>
                                    </section>
                                @else
                                    <div class="schedule-table-wrap">
                                        <table class="weekly-table">
                                            <thead>
                                                <tr>
                                                    <th class="time-col">Time</th>
                                                    @foreach($days as $day)
                                                        <th>{{ $day }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($timeSlots as $slot)
                                                    <tr>
                                                        <td class="time-col">
                                                            <div class="time-slot">
                                                                {{ $formatTime($slot['start']) }}
                                                                <span>{{ $formatTime($slot['end']) }}</span>
                                                            </div>
                                                        </td>

                                                        @foreach($days as $day)
                                                            @php
                                                                $cellKey = $slot['key'] . '|' . $day;
                                                                $cellSchedules = $schedulesBySlotDay->get($cellKey, collect());
                                                            @endphp
                                                            <td>
                                                                @if($cellSchedules->isNotEmpty())
                                                                    <div class="schedule-cell">
                                                                        @foreach($cellSchedules as $schedule)
                                                                            <button type="button" class="schedule-chip" data-schedule-open="schedule-modal-{{ $schedule->id }}">
                                                                                <strong>{{ $schedule->subject }}</strong>
                                                                                <span>{{ $schedule->room }}</span>
                                                                            </button>
                                                                        @endforeach
                                                                    </div>
                                                                @else
                                                                    <div class="empty-cell" aria-hidden="true"></div>
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </section>
                        </section>
                    </div>
                </div>
            </main>
        </div>
    </div>

    @foreach($schedules as $schedule)
        <div id="schedule-modal-{{ $schedule->id }}" class="schedule-modal" aria-hidden="true">
            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="schedule-title-{{ $schedule->id }}">
                <div class="modal-head">
                    <div>
                        <span>Schedule Details</span>
                        <h3 id="schedule-title-{{ $schedule->id }}">{{ $schedule->subject }}</h3>
                    </div>
                    <button type="button" class="modal-close" data-schedule-close aria-label="Close schedule details">x</button>
                </div>

                <div class="modal-body">
                    <div class="modal-details">
                        <div class="modal-detail">
                            <span>Day</span>
                            <strong>{{ $schedule->day_of_week }}</strong>
                        </div>
                        <div class="modal-detail">
                            <span>Time</span>
                            <strong>{{ $formatTime($schedule->start_time) }} - {{ $formatTime($schedule->end_time) }}</strong>
                        </div>
                        <div class="modal-detail">
                            <span>Room</span>
                            <strong>{{ $schedule->room }}</strong>
                        </div>
                        <div class="modal-detail">
                            <span>Term</span>
                            <strong>{{ $schedule->semester }} / {{ $schedule->school_year }}</strong>
                        </div>
                    </div>

                    <div class="modal-actions">
                        <a href="{{ route('schedules.edit', $schedule) }}" class="primary-button">Edit Schedule</a>
                        <form method="POST" action="{{ route('schedules.destroy.post', $schedule) }}" class="delete-form">
                            @csrf
                            <button type="submit" class="danger-button">Permanently Delete</button>
                        </form>
                        <button type="button" class="ghost-button" data-schedule-close>Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        document.querySelectorAll('[data-schedule-open]').forEach(function (button) {
            button.addEventListener('click', function () {
                const modal = document.getElementById(button.dataset.scheduleOpen);

                if (!modal) {
                    return;
                }

                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
            });
        });

        function closeScheduleModal(modal) {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
        }

        document.querySelectorAll('.schedule-modal').forEach(function (modal) {
            modal.addEventListener('click', function (event) {
                if (event.target === modal || event.target.closest('[data-schedule-close]')) {
                    closeScheduleModal(modal);
                }
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key !== 'Escape') {
                return;
            }

            document.querySelectorAll('.schedule-modal.is-open').forEach(closeScheduleModal);
        });
    </script>
</body>
</html>
