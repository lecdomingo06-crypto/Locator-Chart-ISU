<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Professor Tracker') }} - Availability</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800" rel="stylesheet" />

    <style>
        :root {
            color-scheme: light;
            --bg: #edf7f0;
            --card: rgba(255, 255, 255, 0.94);
            --card-border: rgba(12, 92, 56, 0.12);
            --text: #113322;
            --muted: #607766;
            --green-900: #0c5c38;
            --green-800: #147247;
            --green-700: #1b8a53;
            --green-100: #ddf4e4;
            --danger: #b91c1c;
            --warning: #d97706;
            --shadow: 0 18px 40px rgba(12, 92, 56, 0.1);
        }

        * { box-sizing: border-box; }

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

        .workspace-shell { min-height: 100vh; }

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

        .logout-form { margin: 0; }

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
        .sidebar-note { color: var(--muted); }

        .sidebar-note { margin: 14px 0 18px; }

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
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 42px 32px 40px 0;
        }

        .shell {
            display: grid;
            gap: 16px;
            width: min(100%, 1120px);
            padding: 24px;
            border: 1px solid var(--card-border);
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.92);
            box-shadow: 0 22px 50px rgba(12, 92, 56, 0.08);
        }

        .page-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 0 0 2px;
        }

        .title-copy,
        .panel-head {
            display: grid;
            gap: 6px;
        }

        .title-copy span,
        .panel-head span {
            color: var(--green-800);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .title-copy h1,
        .panel-head h2 {
            margin: 0;
            letter-spacing: -0.03em;
        }

        .title-copy h1 { font-size: clamp(1.8rem, 3vw, 2.45rem); }
        .panel-head h2 { font-size: 1.18rem; }

        .title-pill {
            display: inline-flex;
            align-items: center;
            min-height: 38px;
            padding: 0 14px;
            border-radius: 999px;
            color: var(--green-900);
            background: rgba(221, 244, 228, 0.8);
            border: 1px solid rgba(20, 114, 71, 0.1);
            font-size: 0.82rem;
            font-weight: 700;
        }

        .availability-grid {
            display: grid;
            grid-template-columns: 290px minmax(0, 1fr);
            gap: 18px;
            align-items: start;
        }

        .status-panel,
        .control-panel,
        .action-card {
            position: relative;
            overflow: hidden;
            border: 1px solid var(--card-border);
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 14px 34px rgba(12, 92, 56, 0.06);
        }

        .status-panel::before,
        .control-panel::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 0;
        }

        .status-panel,
        .control-panel {
            padding: 18px;
        }

        .control-panel {
            overflow: visible;
            padding: 0;
            border: 0;
            background: transparent;
            box-shadow: none;
        }

        .profile-head {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .profile-photo,
        .profile-placeholder {
            flex: 0 0 76px;
            width: 76px;
            height: 76px;
            border-radius: 16px;
            border: 2px solid rgba(20, 114, 71, 0.08);
        }

        .profile-photo { object-fit: cover; }

        .profile-placeholder {
            display: grid;
            place-items: center;
            color: var(--green-900);
            background: var(--green-100);
            font-size: 2.2rem;
            font-weight: 800;
        }

        .profile-copy {
            display: grid;
            gap: 5px;
            min-width: 0;
        }

        .profile-copy span {
            color: var(--green-800);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .profile-copy h2 {
            margin: 0;
            font-size: 1.14rem;
            letter-spacing: -0.03em;
        }

        .profile-copy p {
            margin: 0;
            color: var(--muted);
            line-height: 1.5;
        }

        .status-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 18px;
            padding: 14px;
            border-radius: 14px;
            color: var(--text);
            background: #f7fbf8;
            border: 1px solid rgba(20, 114, 71, 0.1);
            box-shadow: none;
        }

        .status-card span,
        .detail-tile span {
            display: block;
            color: var(--muted);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .status-card span {
            color: var(--muted);
        }

        .status-card strong,
        .detail-tile strong {
            display: block;
            margin-top: 6px;
        }

        .status-card strong {
            font-size: 1.08rem;
            line-height: 1.1;
            letter-spacing: -0.04em;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            min-width: 116px;
            max-width: 180px;
            padding: 8px 12px;
            border-radius: 999px;
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            overflow: hidden;
            text-overflow: ellipsis;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            margin-top: 12px;
        }

        .detail-tile {
            min-width: 0;
            padding: 12px;
            border-radius: 14px;
            background: #f7fbf8;
            border: 1px solid rgba(20, 114, 71, 0.1);
        }

        .detail-tile strong {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .panel-head {
            padding: 0 2px;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: end;
            margin-bottom: 14px;
        }

        .panel-state {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 34px;
            padding: 0 12px;
            border-radius: 999px;
            color: var(--green-900);
            background: rgba(221, 244, 228, 0.72);
            border: 1px solid rgba(20, 114, 71, 0.12);
            font-size: 0.78rem;
            font-weight: 800;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .action-card {
            display: grid;
            grid-template-rows: auto auto auto 1fr;
            gap: 12px;
            min-height: 192px;
            padding: 16px;
            box-shadow: none;
            transition: transform 160ms ease, border-color 160ms ease, box-shadow 160ms ease;
        }

        .action-card:hover {
            transform: translateY(-2px);
            border-color: rgba(20, 114, 71, 0.22);
            box-shadow: 0 12px 26px rgba(12, 92, 56, 0.07);
        }

        .action-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .action-icon {
            display: grid;
            place-items: center;
            flex: 0 0 42px;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            color: var(--green-900);
            background: var(--green-100);
        }

        .action-icon::before {
            content: '';
            width: 11px;
            height: 11px;
            border-radius: 999px;
            background: currentColor;
            box-shadow: 0 0 0 7px color-mix(in srgb, currentColor 14%, transparent);
        }

        .action-card.tone-break .action-icon {
            color: #d97706;
            background: #fff7ed;
        }

        .action-card.tone-unavailable .action-icon {
            color: #6b7280;
            background: #f3f4f6;
        }

        .action-card.tone-available .action-icon {
            color: var(--green-800);
            background: var(--green-100);
        }

        .action-card h3 {
            margin: 0;
            font-size: 1rem;
            letter-spacing: -0.02em;
        }

        .action-card p,
        .action-meta {
            margin: 0;
            color: var(--muted);
            font-size: 0.84rem;
            line-height: 1.45;
        }

        .action-meta {
            min-height: 20px;
        }

        .field {
            display: grid;
            gap: 8px;
        }

        .field label {
            color: var(--green-900);
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .field input {
            width: 100%;
            min-height: 40px;
            padding: 0 12px;
            border: 1px solid var(--card-border);
            border-radius: 12px;
            color: var(--text);
            background: #ffffff;
            font: inherit;
            outline: none;
        }

        .field input:focus {
            border-color: rgba(20, 114, 71, 0.45);
            box-shadow: 0 0 0 4px rgba(20, 114, 71, 0.08);
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 42px;
            padding: 0 14px;
            border: 0;
            border-radius: 12px;
            color: #ffffff;
            background: linear-gradient(135deg, var(--green-800), var(--green-900));
            font: inherit;
            font-size: 0.84rem;
            font-weight: 800;
            cursor: pointer;
            transition: transform 160ms ease, box-shadow 160ms ease, opacity 160ms ease;
        }

        .button:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 14px 22px rgba(12, 92, 56, 0.16);
        }

        .button.break {
            background: linear-gradient(135deg, #d97706, #92400e);
        }

        .button.unavailable {
            background: linear-gradient(135deg, #6b7280, #374151);
        }

        .button:disabled,
        .button.is-disabled {
            cursor: not-allowed;
            opacity: 0.48;
            filter: grayscale(0.2);
        }

        .helper-note,
        .field-error {
            margin: 0;
            color: var(--muted);
            font-size: 0.82rem;
            line-height: 1.5;
        }

        .field-error {
            color: var(--danger);
            font-weight: 700;
        }

        .notice {
            margin-bottom: 14px;
            padding: 10px 12px;
            border-radius: 14px;
            color: var(--green-900);
            background: rgba(221, 244, 228, 0.72);
            border: 1px solid rgba(20, 114, 71, 0.14);
            font-size: 0.86rem;
            font-weight: 700;
            line-height: 1.5;
        }

        @media (prefers-reduced-motion: reduce) {
            .action-card,
            .button {
                transition: none;
            }

            .action-card:hover,
            .button:hover:not(:disabled) {
                transform: none;
            }
        }

        @media (max-width: 1280px) {
            .action-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 1100px) {
            .availability-grid {
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
                align-items: flex-start;
                min-height: 0;
                padding: 0 16px 24px;
            }

            .shell {
                width: 100%;
                padding: 18px;
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
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .workspace-main .page {
                padding: 0 14px 20px;
            }

            .shell {
                padding: 16px;
                border-radius: 18px;
            }

            .page-title,
            .profile-head,
            .status-card,
            .panel-head {
                align-items: flex-start;
                flex-direction: column;
            }

            .status-panel,
            .control-panel {
                padding: 16px;
                border-radius: 18px;
            }

            .status-badge {
                width: 100%;
                max-width: none;
            }

            .panel-head {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <x-minimal-ui />
</head>
<body>
@php
    $displayName = $user->full_name ?: $user->username;
    $firstName = preg_split('/\s+/', trim($displayName))[0] ?? $displayName;
    $departmentName = $user->department->name ?? 'No department assigned';
    $statusData = $user->live_status;
    $status = $statusData['status'];
    $canStartAvailability = $status === 'Available';
    $canEndAvailability = filled($activeAvailability);
    $statusPalette = [
        'Available' => '#16a34a',
        'In Class' => '#2563eb',
        'On Leave' => '#dc2626',
        'Emergency' => '#f59e0b',
        'On Meeting' => '#7c3aed',
        'On Break' => '#d97706',
        'Not Available' => '#6b7280',
        'Holiday' => '#d97706',
        'Class Suspension' => '#dc2626',
        'No Classes' => '#0891b2',
        'University Event' => '#0f766e',
        'Department Activity' => '#7c3aed',
    ];
    $paletteKey = ($statusData['source'] ?? null) === 'academic_event'
        ? ($statusData['event_type'] ?? $status)
        : $status;
    $badgeColor = $statusPalette[$paletteKey] ?? '#6b7280';
    $defaultBreakEnd = old('end_datetime', now()->addMinutes(15)->format('Y-m-d\TH:i'));
    $defaultUnavailableEnd = old('end_datetime', now()->addMinutes(30)->format('Y-m-d\TH:i'));
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag;
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
                        <span>Viewer and schedule tools</span>
                    </div>
                </div>

                <span class="sidebar-section-label">Workspace</span>
                <nav class="sidebar-nav" aria-label="{{ ucfirst($user->role) }} workspace">
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
                            <path d="m4.9 4.9 2.8 2.8"></path>
                            <path d="m16.3 16.3 2.8 2.8"></path>
                            <path d="M2 12h4"></path>
                            <path d="M18 12h4"></path>
                            <path d="m4.9 19.1 2.8-2.8"></path>
                            <path d="m16.3 7.7 2.8-2.8"></path>
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

                    @if($user->role === 'professor')
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
                    @endif

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
                        <section class="page-title" aria-label="Availability title">
                            <div class="title-copy">
                                <span>Availability</span>
                                <h1>Welcome, {{ $firstName }}!</h1>
                            </div>

                            <div class="title-pill">{{ $canStartAvailability ? 'Ready to update' : 'Status locked' }}</div>
                        </section>

                        <x-flash-toast />

                        <section class="availability-grid">
                            <section class="status-panel">
                                <div class="profile-head">
                                    @if($user->profile_picture)
                                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile picture of {{ $displayName }}" class="profile-photo">
                                    @else
                                        <div class="profile-placeholder" aria-hidden="true">{{ strtoupper(substr($displayName ?? 'U', 0, 1)) }}</div>
                                    @endif

                                    <div class="profile-copy">
                                        <span>Signed in</span>
                                        <h2>{{ $displayName }}</h2>
                                        <p>{{ ucfirst($user->role) }} · {{ $departmentName }}</p>
                                    </div>
                                </div>

                                <div class="status-card">
                                    <div>
                                        <span>Live viewer status</span>
                                        <strong>{{ $status }}</strong>
                                    </div>

                                    <div class="status-badge" style="background-color: {{ $badgeColor }};">
                                        {{ $statusData['source'] === 'default' ? 'Manual' : ucwords(str_replace('_', ' ', $statusData['source'] ?? 'Live')) }}
                                    </div>
                                </div>

                                <div class="detail-grid">
                                    <div class="detail-tile">
                                        <span>Department</span>
                                        <strong>{{ $departmentName }}</strong>
                                    </div>

                                    <div class="detail-tile">
                                        <span>Username</span>
                                        <strong>{{ $user->username }}</strong>
                                    </div>

                                    <div class="detail-tile">
                                        <span>Actions</span>
                                        <strong>{{ $canStartAvailability ? 'Unlocked' : 'Locked' }}</strong>
                                    </div>

                                    <div class="detail-tile">
                                        <span>Timer</span>
                                        <strong>{{ $canEndAvailability ? 'Active' : 'None' }}</strong>
                                    </div>
                                </div>
                            </section>

                            <section class="control-panel">
                                <div class="panel-head">
                                    <div>
                                        <span>Quick Actions</span>
                                        <h2>Update your status</h2>
                                    </div>
                                    <div class="panel-state">{{ $canStartAvailability ? 'Available now' : 'Locked by status' }}</div>
                                </div>

                                @if(! $canStartAvailability && ! $canEndAvailability)
                                    <div class="notice">Temporary updates can start only when your live status is Available.</div>
                                @endif

                                <div class="action-grid">
                                    <form method="POST" action="{{ route('availability.break') }}" class="action-card tone-break">
                                        @csrf
                                        <div class="action-top">
                                            <h3>On Break</h3>
                                            <span class="action-icon" aria-hidden="true"></span>
                                        </div>
                                        <p class="action-meta">Short pause until a set time.</p>

                                        <div class="field">
                                            <label for="break_end_datetime">Until</label>
                                            <input id="break_end_datetime" type="datetime-local" name="end_datetime" value="{{ $defaultBreakEnd }}" {{ $canStartAvailability ? '' : 'disabled' }}>
                                            @error('end_datetime')
                                                <p class="field-error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <button type="submit" class="button break" {{ $canStartAvailability ? '' : 'disabled' }}>On Break</button>
                                    </form>

                                    <form method="POST" action="{{ route('availability.unavailable') }}" class="action-card tone-unavailable">
                                        @csrf
                                        <div class="action-top">
                                            <h3>Not Available</h3>
                                            <span class="action-icon" aria-hidden="true"></span>
                                        </div>
                                        <p class="action-meta">Block visits until a set time.</p>

                                        <div class="field">
                                            <label for="unavailable_end_datetime">Until</label>
                                            <input id="unavailable_end_datetime" type="datetime-local" name="end_datetime" value="{{ $defaultUnavailableEnd }}" {{ $canStartAvailability ? '' : 'disabled' }}>
                                        </div>

                                        <button type="submit" class="button unavailable" {{ $canStartAvailability ? '' : 'disabled' }}>Not Available</button>
                                    </form>

                                    <form method="POST" action="{{ route('availability.available') }}" class="action-card tone-available">
                                        @csrf
                                        <div class="action-top">
                                            <h3>Available</h3>
                                            <span class="action-icon" aria-hidden="true"></span>
                                        </div>

                                        @if($activeAvailability)
                                            <p class="action-meta">Timer ends {{ \Carbon\Carbon::parse($activeAvailability->end_datetime)->format('M j, g:i A') }}.</p>
                                        @else
                                            <p class="action-meta">No active temporary status.</p>
                                        @endif

                                        <button type="submit" class="button" {{ $canEndAvailability ? '' : 'disabled' }}>Available</button>
                                    </form>
                                </div>
                            </section>
                        </section>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
