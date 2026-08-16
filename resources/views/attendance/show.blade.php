<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Professor Tracker') }} - Attendance</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800" rel="stylesheet" />
    <style>
        :root {
            color-scheme: light;
            --green-950: #083b26;
            --green-900: #0c5c38;
            --green-800: #147247;
            --green-700: #188a52;
            --green-100: #e7f5ec;
            --green-50: #f2faf5;
            --surface: #ffffff;
            --border: #cfe3d6;
            --text: #10291c;
            --muted: #63766a;
            --danger: #b42318;
            --shadow: 0 18px 42px rgba(8, 59, 38, 0.1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--text);
            background: #eef8f1;
            font-family: 'Outfit', system-ui, sans-serif;
        }

        button,
        input {
            font: inherit;
        }

        .workspace-topbar {
            min-height: 78px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 12px 28px;
            color: #fff;
            background: var(--green-900);
            box-shadow: 0 8px 22px rgba(8, 59, 38, 0.16);
        }

        .workspace-brand,
        .workspace-session,
        .sidebar-brand,
        .sidebar-link,
        .attendance-state,
        .action-row {
            display: flex;
            align-items: center;
        }

        .workspace-brand {
            gap: 12px;
            color: inherit;
            text-decoration: none;
        }

        .workspace-brand-mark,
        .sidebar-mark {
            display: grid;
            place-items: center;
            flex: 0 0 auto;
            border-radius: 8px;
            font-weight: 900;
        }

        .workspace-brand-mark {
            width: 48px;
            height: 48px;
            color: var(--green-900);
            background: #fff;
        }

        .workspace-brand-copy,
        .sidebar-copy {
            display: grid;
            gap: 2px;
        }

        .workspace-brand-copy strong {
            font-size: 1rem;
        }

        .workspace-brand-copy span,
        .sidebar-copy span {
            font-size: 0.82rem;
            opacity: 0.82;
        }

        .workspace-session {
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .workspace-chip {
            padding: 8px 12px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.1);
            font-size: 0.8rem;
            font-weight: 700;
        }

        .logout-form {
            margin: 0;
        }

        .logout-button {
            min-height: 38px;
            padding: 0 17px;
            border: 0;
            border-radius: 999px;
            color: var(--green-900);
            background: #fff;
            font-weight: 800;
            cursor: pointer;
        }

        .workspace-layout {
            display: grid;
            grid-template-columns: var(--shell-sidebar-width, 268px) minmax(0, 1fr);
            min-height: calc(100vh - 78px);
        }

        .workspace-sidebar {
            padding: 24px 14px;
            border-right: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.92);
        }

        .sidebar-brand {
            gap: 12px;
            padding: 0 8px 20px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-mark {
            width: 46px;
            height: 46px;
            color: #fff;
            background: var(--green-800);
        }

        .sidebar-copy strong {
            font-size: 0.94rem;
        }

        .sidebar-note {
            margin: 18px 8px 24px;
            color: var(--muted);
            font-size: 0.82rem;
            line-height: 1.55;
        }

        .sidebar-section-label {
            display: block;
            margin: 0 8px 10px;
            color: #7b8c81;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .sidebar-nav {
            display: grid;
            gap: 5px;
        }

        .sidebar-link {
            gap: 11px;
            min-height: 44px;
            padding: 0 12px;
            border-radius: 7px;
            color: #1c3728;
            text-decoration: none;
            font-size: 0.87rem;
            font-weight: 700;
        }

        .sidebar-link:hover {
            background: var(--green-50);
        }

        .sidebar-link.is-active {
            color: #fff;
            background: var(--green-800);
        }

        .sidebar-link svg {
            width: 19px;
            height: 19px;
            flex: 0 0 auto;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .workspace-main {
            min-width: 0;
            padding: 30px;
        }

        .page {
            width: min(1050px, 100%);
            margin: 0 auto;
        }

        .page-title {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 18px;
        }

        .page-title span,
        .section-heading span,
        .metric span {
            color: var(--green-800);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .page-title h1,
        .section-heading h2 {
            margin: 4px 0 0;
            letter-spacing: 0;
        }

        .page-title h1 {
            font-size: clamp(1.6rem, 3vw, 2.25rem);
        }

        .date-chip {
            padding: 9px 13px;
            border: 1px solid var(--border);
            border-radius: 999px;
            color: var(--green-900);
            background: var(--surface);
            font-size: 0.82rem;
            font-weight: 700;
        }

        .attendance-panel,
        .history-panel {
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
            box-shadow: var(--shadow);
        }

        .attendance-panel {
            overflow: hidden;
            border-top: 0;
            max-width: 430px;
            margin: 0 auto;
            border: 0;
            border-radius: 18px;
            background: #fffdf8;
            box-shadow: 0 24px 48px rgba(8, 59, 38, 0.12);
        }

        .attendance-state {
            display: block;
            padding: 24px 14px 12px;
            border-bottom: 0;
            background: #ffe7ad;
            text-align: center;
        }

        .state-copy {
            min-width: 0;
            padding: 0;
            border: 0;
            border-radius: 0;
            background: transparent;
        }

        .state-copy > span {
            color: rgba(16, 41, 28, 0.62);
            font-size: 0.95rem;
            font-weight: 600;
            text-transform: none;
        }

        .state-copy h2 {
            margin: 5px 0 18px;
            font-size: 1.25rem;
            line-height: 1.2;
        }

        .state-copy p {
            margin: 0;
            color: var(--muted);
            line-height: 1.5;
        }

        .state-badge {
            display: none;
        }

        .clock-card {
            display: grid;
            justify-items: center;
            gap: 16px;
            padding: 16px 14px 22px;
            color: var(--text);
            background: #fffdf8;
            text-align: center;
        }

        .clock-card span,
        .clock-card small {
            color: rgba(16, 41, 28, 0.56);
            font-size: 0.92rem;
            font-weight: 800;
            letter-spacing: 0;
            text-transform: none;
        }

        .digital-clock {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
        }

        .clock-digit {
            display: grid;
            place-items: center;
            width: 50px;
            height: 52px;
            border-radius: 6px;
            color: var(--green-950);
            background: #f4f4f4;
            box-shadow: 0 3px 8px rgba(8, 59, 38, 0.08);
            font-size: 2rem;
            font-weight: 800;
        }

        .clock-separator {
            color: #575757;
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }

        .metrics {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
            padding: 0 14px 16px;
            background: #fffdf8;
        }

        .metric {
            display: flex;
            min-width: 0;
            min-height: 46px;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 14px;
            border: 1px solid #f4dfb6;
            border-radius: 4px;
            color: rgba(16, 41, 28, 0.56);
            background: #fffefb;
            box-shadow: 0 3px 9px rgba(8, 59, 38, 0.05);
        }

        .metric + .metric {
            border-left: 1px solid #f4dfb6;
        }

        .metric.is-hidden {
            display: none;
        }

        .metric span {
            color: inherit;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0;
            text-transform: none;
        }

        .metric strong {
            display: inline;
            margin-top: 0;
            color: inherit;
            overflow-wrap: anywhere;
            font-size: 0.9rem;
        }

        .action-row {
            justify-content: flex-end;
            gap: 10px;
            padding: 20px 24px;
        }

        .action-row form {
            margin: 0;
        }

        .attendance-button {
            width: 100%;
            min-height: 52px;
            padding: 0 20px;
            border: 1px solid transparent;
            border-radius: 5px;
            color: #4f3d18;
            font-size: 0.95rem;
            font-weight: 800;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
        }

        .attendance-button.time-in {
            background: #ffb52e;
            box-shadow: 0 8px 16px rgba(209, 132, 10, 0.2);
        }

        .attendance-button.time-out {
            color: #ffffff;
            background: #c94135;
            box-shadow: 0 8px 16px rgba(180, 35, 24, 0.14);
        }

        .attendance-button:hover:not(:disabled) {
            transform: translateY(-1px);
        }

        .attendance-button:disabled {
            cursor: not-allowed;
            opacity: 0.42;
            box-shadow: none;
        }

        .history-panel {
            margin-top: 20px;
            overflow: hidden;
        }

        .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 22px 16px;
            border-bottom: 1px solid var(--border);
        }

        .section-heading-copy {
            min-width: 0;
        }

        .section-heading h2 {
            font-size: 1.2rem;
        }

        .clear-sessions-form {
            flex: 0 0 auto;
            margin: 0;
        }

        .clear-sessions-button {
            min-height: 38px;
            padding: 0 14px;
            border: 1px solid #e3b7b3;
            border-radius: 7px;
            color: var(--danger);
            background: #fff7f6;
            font-size: 0.8rem;
            font-weight: 800;
            cursor: pointer;
        }

        .clear-sessions-button:hover:not(:disabled) {
            color: #fff;
            background: var(--danger);
        }

        .clear-sessions-button:disabled {
            cursor: not-allowed;
            opacity: 0.45;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px 20px;
            border-bottom: 1px solid #e5eee8;
            text-align: left;
            white-space: nowrap;
        }

        th {
            color: #5e7265;
            background: #f7fbf8;
            font-size: 0.72rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        td {
            font-size: 0.9rem;
            font-weight: 600;
        }

        tbody tr:last-child td {
            border-bottom: 0;
        }

        .session-status {
            display: inline-flex;
            padding: 6px 10px;
            border-radius: 999px;
            color: var(--green-900);
            background: var(--green-100);
            font-size: 0.75rem;
            font-weight: 800;
        }

        .session-status.complete {
            color: #53645a;
            background: #edf1ee;
        }

        .empty-state {
            padding: 28px 20px;
            color: var(--muted);
            text-align: center;
        }

        .attendance-controls {
            display: block;
            padding: 0 14px 22px;
            background: #fffdf8;
        }

        .control-card {
            display: grid;
            gap: 12px;
            min-height: auto;
            padding: 0;
            border: 0;
            border-radius: 0;
            background: transparent;
        }

        .control-card + .control-card {
            margin-top: 12px;
        }

        .control-card.is-disabled {
            display: none;
        }

        .control-card-header {
            display: none;
        }

        .control-icon {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            color: var(--green-900);
            background: var(--green-100);
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 0.04em;
        }

        .control-icon.time-out-icon {
            color: var(--danger);
            background: #fff0ee;
        }

        .control-card span,
        .control-card-header small {
            color: var(--muted);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .control-card h3 {
            margin: 2px 0 0;
            color: var(--text);
            font-size: 1.15rem;
            line-height: 1.1;
        }

        .control-card p {
            margin: -4px 0 0;
            color: var(--muted);
            font-size: 0.82rem;
            line-height: 1.4;
            text-align: center;
        }

        .control-card form {
            display: grid;
            gap: 10px;
        }

        .work-hours {
            margin: 14px 0 0;
            color: rgba(16, 41, 28, 0.42);
            font-size: 0.9rem;
            font-weight: 700;
            text-align: center;
        }

        .work-hours strong {
            color: inherit;
            font-weight: 800;
        }

        .geo-status {
            min-height: auto !important;
            margin: 0;
            color: var(--muted);
            font-size: 0.78rem;
            line-height: 1.35;
            text-align: center;
        }

        .geo-status.is-error {
            color: var(--danger);
            font-weight: 700;
        }

        .geo-note {
            display: inline-flex;
            width: fit-content;
            min-height: 28px;
            align-items: center;
            justify-self: center;
            padding: 0 10px;
            border: 1px solid var(--border);
            border-radius: 999px;
            color: var(--green-900);
            background: #f3fbf6;
            font-size: 0.72rem;
            font-weight: 800;
        }

        .control-card form,
        .month-form {
            margin: 0;
        }

        .calendar-panel {
            margin-top: 20px;
            overflow: hidden;
        }

        .month-form {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .month-form input {
            min-height: 38px;
            padding: 0 10px;
            border: 1px solid var(--border);
            border-radius: 7px;
            color: var(--text);
            background: #fff;
        }

        .month-form button {
            min-height: 38px;
            padding: 0 13px;
            border: 1px solid var(--green-800);
            border-radius: 7px;
            color: #fff;
            background: var(--green-800);
            font-weight: 800;
            cursor: pointer;
        }

        .calendar-summary {
            display: grid;
            gap: 12px;
            padding: 18px 22px 6px;
        }

        .calendar-summary-title {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 14px;
        }

        .calendar-summary-title span {
            color: var(--green-800);
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .calendar-summary-title strong {
            color: var(--muted);
            font-size: 0.82rem;
            font-weight: 800;
        }

        .calendar-summary-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .calendar-metric {
            display: inline-flex;
            min-height: 38px;
            align-items: center;
            gap: 10px;
            padding: 8px 11px;
            border: 1px solid #dbece2;
            border-radius: 999px;
            background: #ffffff;
        }

        .calendar-metric span {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--muted);
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0;
            text-transform: none;
        }

        .calendar-metric span::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: #edf2ee;
        }

        .calendar-metric strong {
            display: inline-flex;
            min-width: 26px;
            min-height: 24px;
            align-items: center;
            justify-content: center;
            padding: 0 7px;
            border-radius: 999px;
            color: var(--green-950);
            background: #f3fbf6;
            font-size: 0.82rem;
            line-height: 1;
        }

        .calendar-metric.present span::before {
            background: #21a85a;
        }

        .calendar-metric.present strong {
            color: #159447;
        }

        .calendar-metric.absent span::before {
            background: #f03d3d;
        }

        .calendar-metric.absent strong {
            color: #d12d2d;
        }

        .calendar-metric.excused span::before {
            background: #7b61d1;
        }

        .calendar-metric.excused strong {
            color: #6f4cc3;
        }

        .calendar-metric.no-class span::before {
            background: #d97706;
        }

        .calendar-metric.rendered strong {
            min-width: auto;
            font-size: 0.8rem;
        }

        .calendar-metric.rendered span::before {
            background: var(--green-800);
        }

        .attendance-calendar {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 10px 8px;
            width: min(100%, 520px);
            margin: 0 auto;
            padding: 22px 18px 26px;
        }

        .calendar-weekday {
            display: grid;
            place-items: center;
            min-height: 24px;
            color: var(--muted);
            font-size: 0.64rem;
            font-weight: 900;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .calendar-empty {
            min-height: 36px;
        }

        .calendar-day {
            position: relative;
            min-height: 36px;
            display: grid;
            place-items: center;
            padding: 0;
            border: 0;
            border-radius: 999px;
            background: transparent;
        }

        .calendar-day-number {
            display: grid;
            place-items: center;
            width: 34px;
            height: 34px;
            border-radius: 999px;
            color: #183326;
            background: #edf2ee;
            font-size: 0.86rem;
            font-weight: 900;
            line-height: 1;
            box-shadow: inset 0 0 0 1px rgba(12, 92, 56, 0.05);
        }

        .calendar-day-status {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
        }

        .calendar-day.present .calendar-day-number {
            color: #ffffff;
            background: #21a85a;
            box-shadow: 0 7px 14px rgba(33, 168, 90, 0.18);
        }

        .calendar-day.absent .calendar-day-number {
            color: #ffffff;
            background: #f03d3d;
            box-shadow: 0 7px 14px rgba(240, 61, 61, 0.16);
        }

        .calendar-day.pending .calendar-day-number {
            color: #253a2e;
            background: #f0f3f1;
        }

        .calendar-day.excused .calendar-day-number {
            color: #ffffff;
            background: #7b61d1;
            box-shadow: 0 7px 14px rgba(123, 97, 209, 0.16);
        }

        .calendar-day.no_class .calendar-day-number {
            color: #ffffff;
            background: #d97706;
            box-shadow: 0 7px 14px rgba(217, 119, 6, 0.18);
        }

        .calendar-day:hover .calendar-day-number {
            transform: translateY(-1px);
        }

        @media (max-width: 840px) {
            .workspace-layout {
                grid-template-columns: 1fr;
            }

            .workspace-sidebar {
                border-right: 0;
                border-bottom: 1px solid var(--border);
            }

            .sidebar-nav {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .sidebar-link {
                justify-content: center;
            }

            .sidebar-note,
            .sidebar-section-label {
                display: none;
            }

            .calendar-summary-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .workspace-topbar,
            .page-title,
            .attendance-state,
            .section-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .workspace-session {
                justify-content: flex-start;
            }

            .workspace-chip {
                display: none;
            }

            .workspace-main {
                padding: 20px 14px;
            }

            .attendance-state {
                grid-template-columns: 1fr;
                padding: 18px;
            }

            .clock-card {
                min-height: 128px;
                padding: 18px;
            }

            .sidebar-nav {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .metrics {
                grid-template-columns: 1fr;
            }

            .metric + .metric {
                border-top: 0;
                border-left: 1px solid #dbece2;
            }

            .attendance-controls {
                grid-template-columns: 1fr;
                padding: 16px;
            }

            .control-card {
                min-height: auto;
                padding: 16px;
            }

            .calendar-summary {
                padding: 16px 14px 2px;
            }

            .calendar-summary-title {
                align-items: flex-start;
                flex-direction: column;
                gap: 4px;
            }

            .calendar-summary-grid {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            .calendar-metric {
                min-height: 36px;
                padding: 8px 10px;
            }

            .attendance-calendar {
                grid-template-columns: repeat(7, minmax(0, 1fr));
                gap: 8px 4px;
                width: min(100%, 360px);
                padding: 16px 8px 20px;
            }

            .calendar-weekday {
                min-height: 20px;
                font-size: 0.56rem;
            }

            .calendar-empty,
            .calendar-day {
                min-height: 31px;
            }

            .calendar-day-number {
                width: 28px;
                height: 28px;
                font-size: 0.76rem;
            }

            .month-form {
                align-items: stretch;
                flex-direction: column;
                width: 100%;
            }

            .metric + .metric {
                border-top: 0;
                border-left: 1px solid #dbece2;
            }

            .action-row {
                align-items: stretch;
                flex-direction: column;
            }

            .attendance-button,
            .action-row form,
            .clear-sessions-form,
            .clear-sessions-button {
                width: 100%;
            }
        }
    </style>
    <x-minimal-ui />
</head>
<body>
@php
    $displayName = $user->full_name ?: $user->username;
    $liveStatus = $user->live_status;
    $latestAttendance = $todayAttendances->first();
    $completedAttendanceCount = $todayAttendances->whereNotNull('time_out')->count();
    $formatMinutes = function ($minutes) {
        $minutes = (int) $minutes;
        $hours = intdiv($minutes, 60);
        $remaining = $minutes % 60;

        return $hours > 0
            ? $hours . ' hr' . ($hours === 1 ? '' : 's') . ($remaining > 0 ? ' ' . $remaining . ' min' : '')
            : $remaining . ' min';
    };
@endphp

<div class="workspace-shell">
    <header class="workspace-topbar">
        <a href="{{ route('staff.viewer') }}" class="workspace-brand">
            <div class="workspace-brand-mark" aria-hidden="true"><img src="{{ asset('images/isulogo.jpg') }}" alt=""></div>
            <div class="workspace-brand-copy">
                <strong>Professor Tracking System</strong>
                <span>{{ ucfirst($user->role) }} workspace</span>
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
                    <strong>{{ ucfirst($user->role) }} Panel</strong>
                    <span>Attendance and schedule tools</span>
                </div>
            </div>

            <span class="sidebar-section-label">Workspace</span>
            <nav class="sidebar-nav" aria-label="{{ ucfirst($user->role) }} workspace">
                <a href="{{ route('staff.viewer') }}" class="sidebar-link">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-5.5 9.5-5.5 9.5 5.5 9.5 5.5-3.5 5.5-9.5 5.5S2.5 12 2.5 12Z"></path><path d="M12 15a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z"></path></svg>
                    <span>Live Viewer</span>
                </a>

                <a href="{{ route('attendance.show') }}" class="sidebar-link is-active">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path></svg>
                    <span>Attendance</span>
                </a>

                <a href="{{ route('availability.show') }}" class="sidebar-link">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2v4M12 18v4M2 12h4M18 12h4"></path><path d="m4.9 4.9 2.8 2.8m8.6 8.6 2.8 2.8m-14.2 0 2.8-2.8m8.6-8.6 2.8-2.8"></path></svg>
                    <span>Availability</span>
                </a>

                <a href="{{ route('profile.edit') }}" class="sidebar-link">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8a4 4 0 0 0 0 8Z"></path><path d="M4.5 20c.8-3.8 3.4-5.8 7.5-5.8s6.7 2 7.5 5.8"></path></svg>
                    <span>Profile</span>
                </a>

                @if($user->role === 'professor')
                    <a href="{{ route('schedules.create') }}" class="sidebar-link">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3v4M17 3v4M4 8h16"></path><path d="M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"></path></svg>
                        <span>Weekly Schedule</span>
                    </a>
                @endif

                <a href="{{ route('special_schedules.create') }}" class="sidebar-link">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 6l1.6 4.4L18 12l-4.4 1.6L12 18l-1.6-4.4L6 12l4.4-1.6L12 6Z"></path><path d="M19 4v4M21 6h-4"></path></svg>
                    <span>Special Schedule</span>
                </a>
            </nav>
        <x-sidebar-account-footer />
        </aside>
<x-responsive-sidebar-control />

        <main class="workspace-main">
            <div class="page">
                <div class="page-title">
                    <div>
                        <span>Daily Attendance</span>
                        <h1>Time In and Time Out</h1>
                    </div>
                    <div class="date-chip">{{ now()->format('l, F j, Y') }}</div>
                </div>

                <x-flash-toast />

                @php
                    $hour = now()->hour;
                    $attendanceGreeting = $hour < 12 ? 'Good Morning' : ($hour < 18 ? 'Good Afternoon' : 'Good Evening');
                    $clockDigits = str_split(now()->format('hi'));
                    $clockInTime = $activeAttendance?->time_in?->format('g:i A') ?? $latestAttendance?->time_in?->format('g:i A') ?? '00:00';
                    $clockOutTime = $latestAttendance?->time_out?->format('g:i A') ?? '00:00';
                    $workStartedAt = $activeAttendance?->time_in?->timestamp;
                @endphp

                <section class="attendance-panel">
                    <div class="attendance-state">
                        <div class="state-copy">
                            <div class="state-badge">{{ $activeAttendance ? 'Timed In' : 'Timed Out' }}</div>
                            <span>Daily Attendance</span>
                            <h2>{{ now()->format('l jS, Y') }}</h2>
                        </div>
                    </div>

                    <div class="clock-card" aria-label="Current time">
                        <span>{{ $attendanceGreeting }}</span>
                        <div class="digital-clock" data-digital-clock aria-live="polite">
                            <strong class="clock-digit" data-clock-digit="0">{{ $clockDigits[0] }}</strong>
                            <strong class="clock-digit" data-clock-digit="1">{{ $clockDigits[1] }}</strong>
                            <span class="clock-separator">:</span>
                            <strong class="clock-digit" data-clock-digit="2">{{ $clockDigits[2] }}</strong>
                            <strong class="clock-digit" data-clock-digit="3">{{ $clockDigits[3] }}</strong>
                        </div>
                    </div>

                    <div class="metrics">
                        <div class="metric">
                            <span>{{ $activeAttendance ? 'Clock In time' : 'Clock In time' }}</span>
                            <strong>{{ $clockInTime }}</strong>
                        </div>
                        <div class="metric {{ $activeAttendance ? 'is-hidden' : '' }}">
                            <span>Clock Out time</span>
                            <strong>{{ $clockOutTime }}</strong>
                        </div>
                        <div class="metric is-hidden">
                            <span>Live Status</span>
                            <strong>{{ $liveStatus['status'] }}</strong>
                        </div>
                    </div>

                    <div class="attendance-controls">
                        @if(! $activeAttendance)
                            <div class="control-card">
                                <p>Time in when you are physically available. This activates your live availability and scheduled class status.</p>
                                <form method="POST" action="{{ route('attendance.time_in') }}" data-time-in-form data-geofence-enabled="{{ $geofenceSettings['enabled'] ? 'true' : 'false' }}">
                                    @csrf
                                    <input type="hidden" name="latitude" data-geolocation-latitude>
                                    <input type="hidden" name="longitude" data-geolocation-longitude>
                                    <input type="hidden" name="accuracy" data-geolocation-accuracy>
                                    @if($geofenceSettings['enabled'])
                                        <span class="geo-note">Location required within {{ (int) $geofenceSettings['radius_meters'] }}m</span>
                                        <p class="geo-status" data-geolocation-status>Your browser will ask for location access.</p>
                                    @endif
                                    <button type="submit" class="attendance-button time-in" data-time-in-button>Clock In</button>
                                </form>
                            </div>
                        @else
                            <div class="control-card">
                                <p>Your attendance session is active. Clock out before leaving campus.</p>
                                <form method="POST" action="{{ route('attendance.time_out') }}">
                                    @csrf
                                    <button type="submit" class="attendance-button time-out">Clock Out</button>
                                </form>
                            </div>
                        @endif
                        <p class="work-hours">Work Hours: <strong data-work-hours data-work-start="{{ $workStartedAt ? $workStartedAt * 1000 : '' }}">{{ $activeAttendance ? '00:00:00' : '00:00:00' }}</strong></p>
                    </div>
                </section>

                <section class="history-panel calendar-panel">
                    <div class="section-heading">
                        <div class="section-heading-copy">
                            <span>Attendance Calendar</span>
                            <h2>{{ $calendarMonth->format('F Y') }}</h2>
                        </div>

                        <form method="GET" action="{{ route('attendance.show') }}" class="month-form">
                            <input type="month" name="month" value="{{ $calendarMonth->format('Y-m') }}">
                            <button type="submit">View Month</button>
                        </form>
                    </div>

                    @php
                        $weekdayLabels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                        $leadingEmptyDays = $calendarMonth->copy()->startOfMonth()->dayOfWeek;
                        $calendarCellCount = $leadingEmptyDays + $calendarDays->count();
                        $trailingEmptyDays = (7 - ($calendarCellCount % 7)) % 7;
                    @endphp

                    <div class="calendar-summary" aria-label="Monthly attendance summary">
                        <div class="calendar-summary-title">
                            <span>Monthly Summary</span>
                            <strong>{{ $calendarMonth->format('F Y') }}</strong>
                        </div>
                        <div class="calendar-summary-grid">
                            <div class="calendar-metric present">
                                <span>Present</span>
                                <strong>{{ $calendarSummary['present'] }}</strong>
                            </div>
                            <div class="calendar-metric absent">
                                <span>Absent</span>
                                <strong>{{ $calendarSummary['absent'] }}</strong>
                            </div>
                            <div class="calendar-metric excused">
                                <span>Excused</span>
                                <strong>{{ $calendarSummary['excused'] }}</strong>
                            </div>
                            <div class="calendar-metric no-class">
                                <span>No Class</span>
                                <strong>{{ $calendarSummary['no_class'] }}</strong>
                            </div>
                            <div class="calendar-metric rendered">
                                <span>Rendered</span>
                                <strong>{{ $formatMinutes($calendarSummary['rendered_minutes']) }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="attendance-calendar" aria-label="Attendance calendar for {{ $calendarMonth->format('F Y') }}">
                        @foreach($weekdayLabels as $weekday)
                            <div class="calendar-weekday" aria-hidden="true">{{ $weekday }}</div>
                        @endforeach

                        @for($i = 0; $i < $leadingEmptyDays; $i++)
                            <div class="calendar-empty" aria-hidden="true"></div>
                        @endfor

                        @foreach($calendarDays as $day)
                            <div
                                class="calendar-day {{ $day['state'] }}"
                                title="{{ $day['date']->format('F j, Y') }}: {{ $day['label'] }}"
                                aria-label="{{ $day['date']->format('F j, Y') }}: {{ $day['label'] }}"
                            >
                                <span class="calendar-day-number">{{ $day['date']->format('j') }}</span>
                                <span class="calendar-day-status">{{ $day['label'] }}</span>
                            </div>
                        @endforeach

                        @for($i = 0; $i < $trailingEmptyDays; $i++)
                            <div class="calendar-empty" aria-hidden="true"></div>
                        @endfor
                    </div>
                </section>

                <section class="history-panel">
                    <div class="section-heading">
                        <div class="section-heading-copy">
                            <span>Attendance Log</span>
                            <h2>Today's Sessions</h2>
                        </div>

                        <form method="POST" action="{{ route('attendance.clear_sessions') }}" class="clear-sessions-form" onsubmit="return confirm('Hide completed sessions from today\\'s log? Attendance records will stay saved.');">
                            @csrf
                            <button type="submit" class="clear-sessions-button" {{ $completedAttendanceCount > 0 ? '' : 'disabled' }}>
                                Hide Sessions
                            </button>
                        </form>
                    </div>

                    @if($todayAttendances->isEmpty())
                        <div class="empty-state">No sessions are shown for today.</div>
                    @else
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todayAttendances as $attendance)
                                        <tr>
                                            <td>{{ $attendance->time_in->format('g:i A') }}</td>
                                            <td>{{ $attendance->time_out?->format('g:i A') ?? 'Active' }}</td>
                                            <td>{{ $attendance->time_in->diffForHumans($attendance->time_out ?? now(), true) }}</td>
                                            <td><span class="session-status{{ $attendance->time_out ? ' complete' : '' }}">{{ $attendance->time_out ? 'Completed' : 'Active' }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>
            </div>
        </main>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const digitNodes = Array.from(document.querySelectorAll('[data-clock-digit]'));
        const workHours = document.querySelector('[data-work-hours]');

        const pad = function (value) {
            return String(value).padStart(2, '0');
        };

        const updateClock = function () {
            if (digitNodes.length) {
                const now = new Date();
                const hours = pad(now.getHours() % 12 || 12);
                const minutes = pad(now.getMinutes());
                `${hours}${minutes}`.split('').forEach(function (digit, index) {
                    if (digitNodes[index]) {
                        digitNodes[index].textContent = digit;
                    }
                });
            }

            if (workHours && workHours.dataset.workStart) {
                const elapsedSeconds = Math.max(0, Math.floor((Date.now() - Number(workHours.dataset.workStart)) / 1000));
                const hours = Math.floor(elapsedSeconds / 3600);
                const minutes = Math.floor((elapsedSeconds % 3600) / 60);
                const seconds = elapsedSeconds % 60;
                workHours.textContent = `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
            }
        };

        updateClock();
        window.setInterval(updateClock, 1000);
    });
</script>
@if($geofenceSettings['enabled'])
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('[data-time-in-form]');

        if (!form || form.dataset.geofenceEnabled !== 'true') {
            return;
        }

        const button = form.querySelector('[data-time-in-button]');
        const status = form.querySelector('[data-geolocation-status]');
        const latitudeInput = form.querySelector('[data-geolocation-latitude]');
        const longitudeInput = form.querySelector('[data-geolocation-longitude]');
        const accuracyInput = form.querySelector('[data-geolocation-accuracy]');
        const defaultButtonText = button ? button.textContent : 'Time In';
        let locationReady = false;

        const setStatus = function (message, isError) {
            if (!status) {
                return;
            }

            status.textContent = message;
            status.classList.toggle('is-error', Boolean(isError));
        };

        const resetButton = function () {
            if (!button) {
                return;
            }

            button.disabled = false;
            button.textContent = defaultButtonText;
        };

        form.addEventListener('submit', function (event) {
            if (locationReady || !button || button.disabled) {
                return;
            }

            event.preventDefault();

            if (!navigator.geolocation) {
                setStatus('This browser cannot share location. Use a phone or enable browser location support.', true);
                return;
            }

            button.disabled = true;
            button.textContent = 'Checking location...';
            setStatus('Checking your location...', false);

            navigator.geolocation.getCurrentPosition(function (position) {
                latitudeInput.value = position.coords.latitude;
                longitudeInput.value = position.coords.longitude;
                accuracyInput.value = position.coords.accuracy;
                locationReady = true;
                setStatus('Location captured. Submitting time in...', false);
                HTMLFormElement.prototype.submit.call(form);
            }, function (error) {
                const messages = {
                    1: 'Location permission was denied. Allow location access, then try again.',
                    2: 'Your location is unavailable right now. Turn on GPS or Wi-Fi, then try again.',
                    3: 'Location check timed out. Move near an open area, then try again.',
                };

                resetButton();
                setStatus(messages[error.code] || 'Location check failed. Please try again.', true);
            }, {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0,
            });
        });
    });
</script>
@endif
</body>
</html>
