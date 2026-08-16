<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Professor Tracker') }} - Special Schedule</title>

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

        .title-copy span,
        .panel-head span,
        .self-label {
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

        .title-copy h1 {
            font-size: 1.45rem;
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
            grid-template-columns: minmax(350px, 0.82fr) minmax(0, 1.18fr);
            gap: 18px;
            align-items: start;
        }

        .form-panel,
        .self-viewer {
            position: relative;
            overflow: hidden;
            border: 1px solid var(--card-border);
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(244, 251, 246, 0.96));
            box-shadow: var(--shadow);
        }

        .form-panel::before,
        .self-viewer::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 4px;
            background: linear-gradient(90deg, var(--green-900), var(--green-700));
        }

        .form-panel,
        .self-viewer {
            padding: 20px;
        }

        .panel-head {
            display: grid;
            gap: 6px;
            margin-bottom: 18px;
        }

        .panel-head h2 {
            font-size: 1.25rem;
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
        .field select,
        .field textarea {
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

        .field textarea {
            min-height: 112px;
            padding: 12px 13px;
            resize: vertical;
        }

        .field input:focus,
        .field select:focus,
        .field textarea:focus {
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

        .toggle-field {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px;
            border-radius: 14px;
            background: rgba(221, 244, 228, 0.52);
            border: 1px solid rgba(20, 114, 71, 0.1);
            cursor: pointer;
        }

        .toggle-field input {
            width: 18px;
            height: 18px;
            margin-top: 3px;
            accent-color: var(--green-800);
        }

        .toggle-copy {
            display: grid;
            gap: 4px;
        }

        .toggle-copy strong {
            color: var(--text);
        }

        .toggle-copy span {
            color: var(--muted);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .form-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .primary-button,
        .secondary-link {
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

        .secondary-link {
            color: var(--green-900);
            background: rgba(221, 244, 228, 0.72);
            border: 1px solid rgba(20, 114, 71, 0.16);
        }

        .self-head {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            min-width: 0;
        }

        .self-photo,
        .self-placeholder {
            flex: 0 0 86px;
            width: 86px;
            height: 86px;
            border-radius: 18px;
            border: 2px solid rgba(20, 114, 71, 0.08);
        }

        .self-photo {
            object-fit: cover;
        }

        .self-placeholder {
            display: grid;
            place-items: center;
            color: var(--green-900);
            background: var(--green-100);
            font-size: 2.2rem;
            font-weight: 800;
        }

        .self-copy {
            display: grid;
            gap: 5px;
            min-width: 0;
        }

        .self-copy h2 {
            margin: 0;
            font-size: 1.35rem;
            letter-spacing: -0.03em;
        }

        .self-copy p {
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
            border-radius: 16px;
            background: rgba(232, 245, 236, 0.55);
            border: 1px solid rgba(20, 114, 71, 0.08);
        }

        .status-card span {
            display: block;
            color: var(--muted);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .status-card strong {
            display: block;
            margin-top: 6px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            min-width: 118px;
            max-width: 150px;
            padding: 8px 14px;
            border-radius: 999px;
            color: #ffffff;
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            overflow: hidden;
            text-overflow: ellipsis;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .self-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 18px;
        }

        .self-tile {
            min-width: 0;
            min-height: 82px;
            padding: 14px;
            border-radius: 16px;
            background: rgba(221, 244, 228, 0.52);
            border: 1px solid rgba(20, 114, 71, 0.1);
        }

        .self-tile span {
            display: block;
            color: var(--muted);
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .self-tile strong {
            display: block;
            margin-top: 8px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .context-card {
            display: grid;
            gap: 8px;
            margin-top: 14px;
            padding: 14px;
            border-radius: 16px;
            background: #ffffff;
            border: 1px solid rgba(20, 114, 71, 0.1);
        }

        .context-card span {
            color: var(--green-800);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .context-line {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            color: var(--text);
            font-size: 0.92rem;
        }

        .context-line small {
            flex: 0 0 auto;
            color: var(--muted);
            font-size: inherit;
            font-weight: 700;
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
            .self-grid {
                grid-template-columns: 1fr;
            }

            .workspace-main .page {
                padding: 0 14px 20px;
            }

            .page-title,
            .self-head,
            .status-card,
            .context-line {
                align-items: flex-start;
                flex-direction: column;
            }

            .form-panel,
            .self-viewer {
                padding: 16px;
                border-radius: 18px;
            }

            .primary-button,
            .secondary-link {
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
    $departmentName = $user->department->name ?? 'No department assigned';
    $statusData = $user->live_status;
    $status = $statusData['status'];
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
                        <span>Status and schedule tools</span>
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
                        <section class="page-title" aria-label="Special schedule title">
                            <div class="title-copy">
                                <span>Special Schedule</span>
                                <h1>Create Special Entry</h1>
                            </div>

                            <div class="title-pill">Self status viewer</div>
                        </section>

                        <x-flash-toast />

                        <section class="content-grid">
                            <section class="form-panel">
                                <div class="panel-head">
                                    <span>Add Special Schedule</span>
                                    <h2>Temporary Status Details</h2>
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

                                <form method="POST" action="{{ route('special_schedules.store') }}" class="schedule-form">
                                    @csrf

                                    <div class="field-grid">
                                        <div class="field full">
                                            <label for="type">Type</label>
                                            <select id="type" name="type">
                                                <option value="">Select type</option>
                                                <option value="On Leave" {{ old('type') === 'On Leave' ? 'selected' : '' }}>On Leave</option>
                                                <option value="Emergency" {{ old('type') === 'Emergency' ? 'selected' : '' }}>Emergency</option>
                                                <option value="On Meeting" {{ old('type') === 'On Meeting' ? 'selected' : '' }}>On Meeting</option>
                                            </select>
                                            @error('type')
                                                <p class="field-error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="field">
                                            <label for="start_datetime">Start Date &amp; Time</label>
                                            <input id="start_datetime" type="datetime-local" name="start_datetime" value="{{ old('start_datetime') }}">
                                            @error('start_datetime')
                                                <p class="field-error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="field">
                                            <label for="end_datetime">End Date &amp; Time</label>
                                            <input id="end_datetime" type="datetime-local" name="end_datetime" value="{{ old('end_datetime') }}">
                                            @error('end_datetime')
                                                <p class="field-error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="field full">
                                            <label for="note">Note / Reason</label>
                                            <textarea id="note" name="note" placeholder="Add a short reason or context">{{ old('note') }}</textarea>
                                            @error('note')
                                                <p class="field-error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        @if($user->role === 'professor')
                                            <div
                                                id="keep_until_schedule_end_field"
                                                class="field full"
                                                style="{{ old('type') === 'On Meeting' ? '' : 'display: none;' }}"
                                                {{ old('type') === 'On Meeting' ? '' : 'hidden' }}>
                                                <label for="keep_until_schedule_end">If you will also miss the rest of your class</label>
                                                <label class="toggle-field" for="keep_until_schedule_end">
                                                    <input
                                                        id="keep_until_schedule_end"
                                                        type="checkbox"
                                                        name="keep_until_schedule_end"
                                                        value="1"
                                                        {{ old('keep_until_schedule_end') ? 'checked' : '' }}>
                                                    <div class="toggle-copy">
                                                        <strong>Keep this status until the current class period ends</strong>
                                                        <span>Use this when a meeting continues through the rest of the current class period.</span>
                                                    </div>
                                                </label>
                                                @error('keep_until_schedule_end')
                                                    <p class="field-error">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endif
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="primary-button">Save Special Schedule</button>
                                    </div>
                                </form>
                            </section>

                            <aside class="self-viewer">
                                <div class="self-head">
                                    @if($user->profile_picture)
                                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile picture of {{ $displayName }}" class="self-photo">
                                    @else
                                        <div class="self-placeholder" aria-hidden="true">{{ strtoupper(substr($displayName ?? 'U', 0, 1)) }}</div>
                                    @endif

                                    <div class="self-copy">
                                        <span class="self-label">Self Viewer</span>
                                        <h2>{{ $displayName }}</h2>
                                        <p>{{ ucfirst($user->role) }} availability profile</p>
                                    </div>
                                </div>

                                <div class="status-card">
                                    <div>
                                        <span>Current Status</span>
                                        <strong>Real-time availability</strong>
                                    </div>

                                    <div class="status-badge" style="background-color: {{ $badgeColor }};">
                                        {{ $status }}
                                    </div>
                                </div>

                                <div class="self-grid">
                                    <div class="self-tile">
                                        <span>Role</span>
                                        <strong>{{ ucfirst($user->role) }}</strong>
                                    </div>

                                    <div class="self-tile">
                                        <span>Department</span>
                                        <strong>{{ $departmentName }}</strong>
                                    </div>

                                    <div class="self-tile">
                                        <span>Username</span>
                                        <strong>{{ $user->username }}</strong>
                                    </div>

                                    <div class="self-tile">
                                        <span>Status Source</span>
                                        <strong>{{ ucwords(str_replace('_', ' ', $statusData['source'] ?? 'live status')) }}</strong>
                                    </div>
                                </div>

                                @if(!empty($statusData['subject']) || !empty($statusData['room']) || !empty($statusData['event_type']) || !empty($statusData['event_note']) || !empty($statusData['event_purpose']))
                                    <div class="context-card">
                                        <span>Status Context</span>
                                        @if(!empty($statusData['subject']))
                                            <div class="context-line"><small>Subject</small><strong>{{ $statusData['subject'] }}</strong></div>
                                        @endif
                                        @if(!empty($statusData['room']))
                                            <div class="context-line"><small>Room</small><strong>{{ $statusData['room'] }}</strong></div>
                                        @endif
                                        @if(!empty($statusData['event_type']))
                                            <div class="context-line"><small>Event</small><strong>{{ $statusData['event_type'] }}</strong></div>
                                        @endif
                                        @if(!empty($statusData['event_note']))
                                            <div class="context-line"><small>Note</small><strong>{{ $statusData['event_note'] }}</strong></div>
                                        @endif
                                        @if(!empty($statusData['event_purpose']))
                                            <div class="context-line"><small>Purpose</small><strong>{{ $statusData['event_purpose'] }}</strong></div>
                                        @endif
                                    </div>
                                @endif
                            </aside>
                        </section>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeField = document.getElementById('type');
        const carryField = document.getElementById('keep_until_schedule_end_field');
        const carryCheckbox = document.getElementById('keep_until_schedule_end');

        if (!typeField || !carryField || !carryCheckbox) {
            return;
        }

        function syncCarryField() {
            const showCarryField = typeField.value === 'On Meeting';

            carryField.hidden = !showCarryField;
            carryField.style.display = showCarryField ? '' : 'none';
            carryCheckbox.disabled = !showCarryField;

            if (!showCarryField) {
                carryCheckbox.checked = false;
            }
        }

        syncCarryField();
        typeField.addEventListener('change', syncCarryField);
    });
    </script>
</body>
</html>
