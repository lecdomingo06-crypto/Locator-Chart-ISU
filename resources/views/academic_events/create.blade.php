<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Professor Tracker') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800" rel="stylesheet" />
    <style>
        :root {
            color-scheme: light;
            --bg: #f3f7f1;
            --panel: #ffffff;
            --panel-soft: #f8fbf7;
            --border: #d8e4dc;
            --border-strong: #bdd2c4;
            --text: #10291c;
            --muted: #607066;
            --green-900: #0c5c38;
            --green-800: #147247;
            --green-100: #e4f4e9;
            --danger: #b42318;
            --danger-soft: #fff1f0;
            --shadow: 0 14px 32px rgba(8, 57, 36, 0.09);
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
            background: var(--bg);
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        h1,
        h2,
        h3,
        p {
            margin: 0;
        }

        .admin-shell {
            min-height: 100vh;
        }

        .admin-topbar {
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

        .admin-brand,
        .admin-session,
        .admin-chip,
        .sidebar-brand,
        .sidebar-link,
        .logout-button {
            display: inline-flex;
            align-items: center;
        }

        .admin-brand {
            gap: 14px;
            color: inherit;
            text-decoration: none;
        }

        .admin-brand-mark,
        .sidebar-mark {
            display: grid;
            place-items: center;
            border-radius: 8px;
            font-weight: 800;
        }

        .admin-brand-mark {
            width: 48px;
            height: 48px;
            color: var(--green-900);
            background: rgba(255, 255, 255, 0.94);
            box-shadow: inset 0 0 0 1px rgba(12, 92, 56, 0.08);
        }

        .admin-brand-copy,
        .sidebar-copy {
            display: grid;
            gap: 3px;
        }

        .admin-brand-copy strong,
        .sidebar-copy strong {
            font-size: 1rem;
        }

        .admin-brand-copy span {
            color: rgba(239, 252, 243, 0.76);
            font-size: 0.86rem;
            font-weight: 600;
        }

        .admin-session {
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .admin-chip {
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

        .admin-layout {
            display: grid;
            grid-template-columns: 244px minmax(0, 1fr);
            gap: 26px;
            align-items: start;
        }

        .admin-sidebar {
            position: sticky;
            top: 94px;
            min-height: calc(100vh - 112px);
            margin-left: 16px;
            padding: 18px 14px;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(12, 92, 56, 0.1);
            border-left: 0;
            border-radius: 0 8px 8px 0;
            box-shadow: var(--shadow);
        }

        .sidebar-brand {
            gap: 12px;
            padding: 0 0 18px;
            border-bottom: 1px solid var(--border);
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
            font-size: 0.88rem;
            line-height: 1.5;
        }

        .sidebar-note {
            margin: 14px 0 18px;
        }

        .sidebar-section-label,
        .eyebrow,
        .section-label,
        .field label,
        .event-section span,
        .event-card span {
            color: var(--green-900);
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .sidebar-section-label {
            display: block;
            margin: 0 0 10px;
            color: #6c7d70;
            font-size: 0.72rem;
            letter-spacing: 0.14em;
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

        .page {
            min-width: 0;
            padding: 24px 28px 32px 0;
        }

        .page-shell {
            display: grid;
            gap: 18px;
            max-width: 1240px;
        }

        .page-head,
        .form-card,
        .events-panel {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .page-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px;
            padding: 22px 24px;
        }

        .page-title {
            display: grid;
            gap: 8px;
        }

        h1 {
            font-size: clamp(1.65rem, 3vw, 2.2rem);
            line-height: 1.08;
            letter-spacing: -0.03em;
        }

        .page-meta {
            display: inline-flex;
            align-items: center;
            min-height: 36px;
            padding: 0 13px;
            border-radius: 999px;
            color: var(--green-900);
            background: var(--green-100);
            border: 1px solid #c9e6d2;
            font-size: 0.86rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .content {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(360px, 0.9fr);
            gap: 18px;
            align-items: start;
        }

        .form-card,
        .events-panel {
            padding: 24px;
        }

        .form-inner,
        .events-inner {
            display: grid;
            gap: 20px;
        }

        .section-heading {
            display: grid;
            gap: 6px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .section-heading h2 {
            font-size: 1.18rem;
            letter-spacing: -0.02em;
        }

        .success-alert,
        .error-summary {
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 0.92rem;
            font-weight: 700;
        }

        .success-alert {
            color: #116537;
            background: #e8f6ed;
            border: 1px solid #bddfc8;
        }

        .error-summary {
            display: grid;
            gap: 8px;
            color: var(--danger);
            background: var(--danger-soft);
            border: 1px solid #ffd2cf;
        }

        .error-summary ul {
            margin: 0;
            padding-left: 18px;
        }

        .event-form {
            display: grid;
            gap: 18px;
        }

        .field-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .field {
            display: grid;
            gap: 8px;
            min-width: 0;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        .field input,
        .field select,
        .field textarea {
            width: 100%;
            min-height: 48px;
            padding: 0 14px;
            border: 1px solid var(--border-strong);
            border-radius: 6px;
            background: #ffffff;
            color: var(--text);
            outline: none;
        }

        .field textarea {
            min-height: 116px;
            padding-top: 12px;
            padding-bottom: 12px;
            resize: vertical;
        }

        .field input:focus,
        .field select:focus,
        .field textarea:focus {
            border-color: var(--green-800);
            box-shadow: 0 0 0 3px rgba(20, 114, 71, 0.13);
        }

        .field-error {
            margin: 0;
            color: var(--danger);
            font-size: 0.9rem;
            font-weight: 700;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 4px;
            flex-wrap: wrap;
        }

        .primary-button,
        .secondary-link,
        .event-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-weight: 800;
        }

        .primary-button,
        .secondary-link {
            min-height: 48px;
            padding: 0 18px;
            border-radius: 6px;
        }

        .primary-button {
            border: 0;
            color: #ffffff;
            background: var(--green-900);
            cursor: pointer;
            box-shadow: 0 12px 24px rgba(12, 92, 56, 0.18);
        }

        .primary-button:hover {
            background: var(--green-800);
        }

        .secondary-link {
            color: var(--green-900);
            background: #ffffff;
            border: 1px solid var(--border-strong);
        }

        .events-panel {
            position: sticky;
            top: 94px;
        }

        .event-sections {
            display: grid;
            gap: 16px;
            max-height: calc(100vh - 220px);
            overflow: auto;
            padding-right: 4px;
        }

        .event-section {
            display: grid;
            gap: 10px;
        }

        .event-section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .event-section h3 {
            font-size: 1rem;
            letter-spacing: -0.01em;
        }

        .count-pill {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 999px;
            color: var(--green-900);
            background: var(--green-100);
            border: 1px solid #c9e6d2;
            font-size: 0.78rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .event-list {
            display: grid;
            gap: 8px;
        }

        .event-card {
            display: grid;
            gap: 8px;
            padding: 12px;
            border-radius: 8px;
            background: var(--panel-soft);
            border: 1px solid var(--border);
        }

        .event-card h4 {
            margin: 0;
            font-size: 0.96rem;
        }

        .event-card p {
            color: var(--muted);
            font-size: 0.88rem;
            line-height: 1.45;
        }

        .event-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .event-link,
        .delete-button {
            width: fit-content;
            min-height: 34px;
            padding: 0 11px;
            border-radius: 6px;
            font-size: 0.86rem;
        }

        .event-link {
            color: var(--green-900);
            background: #ffffff;
            border: 1px solid var(--border-strong);
        }

        .delete-form {
            display: inline-flex;
            margin: 0;
        }

        .delete-button {
            border: 1px solid #ffc9c5;
            color: var(--danger);
            background: #fff7f6;
            font-weight: 800;
            cursor: pointer;
        }

        .empty-state {
            padding: 12px;
            border-radius: 8px;
            color: var(--muted);
            background: var(--panel-soft);
            border: 1px dashed var(--border-strong);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        @media (max-width: 1080px) {
            .admin-topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .admin-session {
                justify-content: flex-start;
            }

            .admin-layout {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .admin-sidebar {
                position: static;
                min-height: 0;
                margin: 16px 16px 0;
                border-left: 1px solid rgba(12, 92, 56, 0.1);
                border-radius: 8px;
            }

            .sidebar-nav {
                grid-template-columns: repeat(5, minmax(0, 1fr));
            }

            .page {
                padding: 0 16px 24px;
            }

            .page-shell {
                max-width: none;
            }

            .events-panel {
                position: static;
            }

            .event-sections {
                max-height: none;
                overflow: visible;
                padding-right: 0;
            }
        }

        @media (max-width: 860px) {
            .content,
            .field-grid {
                grid-template-columns: 1fr;
            }

            .page-head {
                align-items: flex-start;
                flex-direction: column;
            }

            .sidebar-nav {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 620px) {
            .admin-topbar {
                padding: 14px 16px;
            }

            .admin-brand-copy span {
                display: none;
            }

            .admin-session,
            .admin-chip,
            .logout-form,
            .logout-button {
                width: 100%;
            }

            .admin-sidebar {
                margin: 14px 14px 0;
                padding: 16px;
            }

            .sidebar-nav {
                grid-template-columns: 1fr;
            }

            .page {
                padding: 0 14px 20px;
            }

            .page-head,
            .form-card,
            .events-panel {
                padding: 18px;
            }

            .form-actions {
                align-items: stretch;
                flex-direction: column;
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
    $eventSections = [
        [
            'label' => 'Active Events',
            'title' => 'Running Right Now',
            'events' => $activeEvents,
            'empty' => 'No active academic events.',
        ],
        [
            'label' => 'Upcoming Events',
            'title' => 'Scheduled Next',
            'events' => $upcomingEvents,
            'empty' => 'No upcoming academic events.',
        ],
        [
            'label' => 'Past Events',
            'title' => 'Recently Finished',
            'events' => $pastEvents,
            'empty' => 'No recently finished academic events.',
        ],
    ];
@endphp

    <div class="admin-shell">
        <header class="admin-topbar">
            <a href="{{ route('admin.viewer') }}" class="admin-brand">
                <div class="admin-brand-mark" aria-hidden="true"><img src="{{ asset('images/isulogo.jpg') }}" alt=""></div>
                <div class="admin-brand-copy">
                    <strong>Professor Tracking System</strong>
                    <span>Admin dashboard</span>
                </div>
            </a>

            <div class="admin-session">
                <span class="admin-chip">Role: Admin</span>
                <span class="admin-chip">Signed in as {{ Auth::user()->full_name ?: Auth::user()->username }}</span>
            </div>
        </header>

        <div class="admin-layout">
            <aside class="admin-sidebar">
                <div class="sidebar-brand">
                    <div class="sidebar-mark" aria-hidden="true"><img src="{{ asset('images/isulogo.jpg') }}" alt=""></div>
                    <div class="sidebar-copy">
                        <strong>Admin Panel</strong>
                        <span>Professor and faculty monitor</span>
                    </div>
                </div>

                <span class="sidebar-section-label">Workspace</span>
                <nav class="sidebar-nav" aria-label="Admin workspace">
                    <a href="{{ route('admin.viewer') }}" class="sidebar-link{{ request()->routeIs('admin.viewer') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M2.5 12s3.5-5.5 9.5-5.5 9.5 5.5 9.5 5.5-3.5 5.5-9.5 5.5S2.5 12 2.5 12Z"></path>
                            <path d="M12 15a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z"></path>
                        </svg>
                        <span>Live Viewer</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="sidebar-link{{ request()->routeIs('admin.users.index', 'admin.users.edit') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M9 11a4 4 0 1 0 0-8a4 4 0 0 0 0 8Z"></path>
                            <path d="M2.5 20c.7-4 2.8-6 6.5-6s5.8 2 6.5 6"></path>
                            <path d="M17 8h4"></path>
                            <path d="M19 6v4"></path>
                        </svg>
                        <span>User Management</span>
                    </a>
                    <a href="{{ route('admin.attendance.index') }}" class="sidebar-link{{ request()->routeIs('admin.attendance.*') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 7v5l3 2"></path>
                            <path d="M7 21h10"></path>
                        </svg>
                        <span>Attendance</span>
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="sidebar-link{{ request()->routeIs('admin.users.create') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 12a4 4 0 1 0 0-8a4 4 0 0 0 0 8Z"></path>
                            <path d="M4.5 20c.8-3.8 3.4-5.8 7.5-5.8"></path>
                            <path d="M18 15v5"></path>
                            <path d="M20.5 17.5h-5"></path>
                        </svg>
                        <span>Create Account</span>
                    </a>

                    <a href="{{ route('admin.student_registrations.index') }}" class="sidebar-link{{ request()->routeIs('admin.student_registrations.*') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8 6.5h8"></path>
                            <path d="M8 11h8"></path>
                            <path d="M8 15.5h5"></path>
                            <path d="M5.5 3.5h13a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2v-13a2 2 0 0 1 2-2Z"></path>
                        </svg>
                        <span>Student Requests</span>
                    </a>

                    <a href="{{ route('admin.status') }}" class="sidebar-link{{ request()->routeIs('admin.status') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M4 7h10"></path>
                            <path d="M18 7h2"></path>
                            <path d="M4 17h2"></path>
                            <path d="M10 17h10"></path>
                            <path d="M14 5v4"></path>
                            <path d="M10 15v4"></path>
                        </svg>
                        <span>Status Override</span>
                    </a>

                    <a href="{{ route('academic_events.create') }}" class="sidebar-link is-active">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M7 3v4"></path>
                            <path d="M17 3v4"></path>
                            <path d="M4 8h16"></path>
                            <path d="M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"></path>
                            <path d="M8 13h8"></path>
                            <path d="M8 17h5"></path>
                        </svg>
                        <span>Academic Events</span>
                    </a>
                </nav>
            <x-sidebar-account-footer />
            </aside>
<x-responsive-sidebar-control />

            <main class="page">
                <div class="page-shell">
                    <section class="page-head">
                        <div class="page-title">
                            <span class="eyebrow">Academic Events</span>
                            <h1>Add Academic Event</h1>
                        </div>

                        <div class="page-meta">{{ $activeEvents->count() + $upcomingEvents->count() + $pastEvents->count() }} total event{{ ($activeEvents->count() + $upcomingEvents->count() + $pastEvents->count()) === 1 ? '' : 's' }}</div>
                    </section>

                    <section class="content">
                        <section class="form-card">
                            <div class="form-inner">
                                <div class="section-heading">
                                    <span class="section-label">Create Event</span>
                                    <h2>Academic event details</h2>
                                </div>

                                <x-flash-toast />

                                @if(isset($errors) && $errors->any())
                                    <section class="error-summary" aria-label="Validation errors">
                                        <strong>Please review the highlighted academic event details.</strong>
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </section>
                                @endif

                                <form method="POST" action="{{ route('academic_events.store') }}" class="event-form">
                                    @csrf
                                    <div class="field-grid">
                                        <div class="field full">
                                            <label for="title">Title</label>
                                            <input id="title" type="text" name="title" value="{{ old('title') }}" placeholder="Enter event title">
                                            @if(isset($errors) && $errors->has('title')) <p class="field-error">{{ $errors->first('title') }}</p> @endif
                                        </div>

                                        <div class="field">
                                            <label for="type">Type</label>
                                            <select id="type" name="type">
                                                <option value="">Select event type</option>
                                                @foreach(\App\Models\AcademicEvent::TYPE_OPTIONS as $type)
                                                    <option value="{{ $type }}" {{ old('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                                @endforeach
                                            </select>
                                            @if(isset($errors) && $errors->has('type')) <p class="field-error">{{ $errors->first('type') }}</p> @endif
                                        </div>

                                        <div class="field">
                                            <label for="scope">Scope</label>
                                            <select id="scope" name="scope">
                                                <option value="">Select scope</option>
                                                @foreach(\App\Models\AcademicEvent::SCOPE_OPTIONS as $scope)
                                                    <option value="{{ $scope }}" {{ old('scope') === $scope ? 'selected' : '' }}>{{ $scope === 'all' ? 'All professors and faculty' : ucfirst($scope) }}</option>
                                                @endforeach
                                            </select>
                                            @if(isset($errors) && $errors->has('scope')) <p class="field-error">{{ $errors->first('scope') }}</p> @endif
                                        </div>

                                        <div class="field">
                                            <label for="start_datetime">Start Date and Time</label>
                                            <input id="start_datetime" type="datetime-local" name="start_datetime" value="{{ old('start_datetime') }}">
                                            @if(isset($errors) && $errors->has('start_datetime')) <p class="field-error">{{ $errors->first('start_datetime') }}</p> @endif
                                        </div>

                                        <div class="field">
                                            <label for="end_datetime">End Date and Time</label>
                                            <input id="end_datetime" type="datetime-local" name="end_datetime" value="{{ old('end_datetime') }}">
                                            @if(isset($errors) && $errors->has('end_datetime')) <p class="field-error">{{ $errors->first('end_datetime') }}</p> @endif
                                        </div>

                                        <div class="field full" id="department_field_wrapper">
                                            <label for="department_id">Department</label>
                                            <select id="department_id" name="department_id">
                                                <option value="">Select department</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->id }}" {{ (string) old('department_id') === (string) $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                            @if(isset($errors) && $errors->has('department_id')) <p class="field-error">{{ $errors->first('department_id') }}</p> @endif
                                        </div>

                                        <div class="field full">
                                            <label for="note">Note</label>
                                            <textarea id="note" name="note" placeholder="Add an optional note or description">{{ old('note') }}</textarea>
                                            @if(isset($errors) && $errors->has('note')) <p class="field-error">{{ $errors->first('note') }}</p> @endif
                                        </div>

                                        <div class="field full">
                                            <label for="purpose">Purpose</label>
                                            <textarea id="purpose" name="purpose" placeholder="Add the purpose shown in the viewer">{{ old('purpose') }}</textarea>
                                            @if(isset($errors) && $errors->has('purpose')) <p class="field-error">{{ $errors->first('purpose') }}</p> @endif
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <a href="{{ route('academic_events.index') }}" class="secondary-link">View Full List</a>
                                        <button type="submit" class="primary-button">Save Academic Event</button>
                                    </div>
                                </form>
                            </div>
                        </section>

                        <aside class="events-panel">
                            <div class="events-inner">
                                <div class="section-heading">
                                    <span class="section-label">Event Overview</span>
                                    <h2>Current academic event board</h2>
                                </div>

                                <div class="event-sections">
                                    @foreach($eventSections as $section)
                                        <section class="event-section">
                                            <div class="event-section-head">
                                                <div>
                                                    <span>{{ $section['label'] }}</span>
                                                    <h3>{{ $section['title'] }}</h3>
                                                </div>
                                                <div class="count-pill">{{ $section['events']->count() }}</div>
                                            </div>

                                            <div class="event-list">
                                                @forelse($section['events']->take(3) as $event)
                                                    <article class="event-card">
                                                        <span>{{ $event->type }}</span>
                                                        <h4>{{ $event->title }}</h4>
                                                        <p>{{ $event->scope_label }}</p>
                                                        <p>{{ $event->start_datetime->format('M d, Y g:i A') }} to {{ $event->end_datetime->format('M d, Y g:i A') }}</p>
                                                        <div class="event-actions">
                                                            <a href="{{ route('academic_events.edit', $event) }}" class="event-link">Edit Event</a>
                                                            <form method="POST" action="{{ route('academic_events.destroy', $event) }}" class="delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="delete-button" onclick="return confirm('Delete this academic event?')">Delete</button>
                                                            </form>
                                                        </div>
                                                    </article>
                                                @empty
                                                    <p class="empty-state">{{ $section['empty'] }}</p>
                                                @endforelse
                                            </div>
                                        </section>
                                    @endforeach
                                </div>
                            </div>
                        </aside>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const scopeField = document.getElementById('scope');
        const departmentWrapper = document.getElementById('department_field_wrapper');
        const departmentField = document.getElementById('department_id');

        if (!scopeField || !departmentWrapper || !departmentField) {
            return;
        }

        function syncDepartmentField() {
            const needsDepartment = scopeField.value === 'department';
            departmentWrapper.style.display = needsDepartment ? 'grid' : 'none';
            departmentField.disabled = !needsDepartment;
        }

        syncDepartmentField();
        scopeField.addEventListener('change', syncDepartmentField);
    });
    </script>
</body>
</html>
