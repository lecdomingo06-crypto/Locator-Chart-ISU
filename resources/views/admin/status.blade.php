<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Status Override</title>

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
            --green-950: #07391f;
            --green-900: #0c5c38;
            --green-800: #147247;
            --green-100: #e4f4e9;
            --danger: #b42318;
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
        select {
            font: inherit;
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

        .admin-brand-copy strong {
            font-size: 1rem;
            letter-spacing: -0.01em;
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

        .sidebar-copy strong {
            font-size: 1rem;
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

        .page {
            min-width: 0;
            padding: 24px 28px 32px 0;
        }

        .page-shell {
            display: grid;
            gap: 18px;
            max-width: 1160px;
        }

        .page-head,
        .form-card,
        .profile-card {
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

        .eyebrow,
        .section-label,
        .field label,
        .detail-label,
        .context-label {
            color: var(--green-900);
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        h1,
        h2,
        p {
            margin: 0;
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
            grid-template-columns: minmax(0, 1fr) minmax(320px, 0.82fr);
            gap: 18px;
            align-items: start;
        }

        .form-card,
        .profile-card {
            padding: 24px;
        }

        .form-inner,
        .profile-inner {
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
        .error-alert {
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

        .error-alert {
            color: var(--danger);
            background: #fff1f0;
            border: 1px solid #ffd2cf;
        }

        .error-alert ul {
            margin: 8px 0 0;
            padding-left: 18px;
        }

        .override-form {
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
        .user-picker-trigger,
        .user-picker-search {
            width: 100%;
            min-height: 48px;
            padding: 0 14px;
            border: 1px solid var(--border-strong);
            border-radius: 6px;
            background: #ffffff;
            color: var(--text);
            outline: none;
        }

        .field input:focus,
        .field select:focus,
        .user-picker-trigger:hover,
        .user-picker-trigger.is-open,
        .user-picker-search:focus {
            border-color: var(--green-800);
            box-shadow: 0 0 0 3px rgba(20, 114, 71, 0.13);
        }

        .picker-stack {
            position: relative;
            display: grid;
            gap: 8px;
        }

        .native-user-select {
            display: none;
        }

        .user-picker-trigger {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            text-align: left;
            cursor: pointer;
        }

        .user-picker-trigger span:first-child {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .picker-chevron {
            flex: 0 0 auto;
            color: var(--green-900);
            font-weight: 800;
            transition: transform 0.18s ease;
        }

        .user-picker-trigger.is-open .picker-chevron {
            transform: rotate(180deg);
        }

        .user-picker-panel {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            z-index: 25;
            display: grid;
            gap: 10px;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: #ffffff;
            box-shadow: 0 18px 36px rgba(8, 57, 36, 0.14);
        }

        .user-picker-panel[hidden],
        .user-picker-option[hidden],
        .user-picker-empty[hidden] {
            display: none;
        }

        .user-picker-list {
            display: grid;
            gap: 6px;
            max-height: 260px;
            overflow-y: auto;
            padding-right: 3px;
        }

        .user-picker-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            width: 100%;
            padding: 10px 12px;
            border: 1px solid transparent;
            border-radius: 6px;
            background: #f7faf7;
            color: var(--text);
            text-align: left;
            cursor: pointer;
        }

        .user-picker-option:hover,
        .user-picker-option.is-active {
            border-color: #c8dccf;
            background: var(--green-100);
        }

        .user-picker-option small {
            color: var(--muted);
            font-size: 0.78rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .user-picker-empty {
            margin: 0;
            padding: 8px 2px 2px;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 4px;
        }

        .primary-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 0 18px;
            border: 0;
            border-radius: 6px;
            color: #ffffff;
            background: var(--green-900);
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 12px 24px rgba(12, 92, 56, 0.18);
        }

        .primary-button:hover {
            background: var(--green-800);
        }

        .primary-button:disabled {
            cursor: not-allowed;
            opacity: 0.58;
        }

        .profile-card {
            position: sticky;
            top: 94px;
        }

        .profile-header {
            display: grid;
            grid-template-columns: 84px minmax(0, 1fr);
            gap: 16px;
            align-items: center;
            padding-bottom: 18px;
            border-bottom: 1px solid var(--border);
        }

        .profile-image,
        .profile-avatar {
            width: 84px;
            height: 84px;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .profile-image {
            object-fit: cover;
            background: var(--panel-soft);
        }

        .profile-image[hidden],
        .profile-avatar[hidden] {
            display: none;
        }

        .profile-avatar {
            display: grid;
            place-items: center;
            color: var(--green-900);
            background: var(--green-100);
            font-size: 1.55rem;
            font-weight: 800;
        }

        .profile-title {
            display: grid;
            gap: 8px;
            min-width: 0;
        }

        .profile-title h2 {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 1.24rem;
            letter-spacing: -0.02em;
        }

        .profile-title p {
            color: var(--muted);
            font-size: 0.92rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: fit-content;
            min-height: 30px;
            padding: 0 11px;
            border-radius: 999px;
            color: #ffffff;
            background: #6b7280;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .detail-card,
        .context-card {
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--panel-soft);
        }

        .detail-card {
            display: grid;
            gap: 7px;
            min-width: 0;
            padding: 12px;
        }

        .detail-value {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 0.95rem;
            font-weight: 800;
        }

        .context-card {
            display: grid;
            gap: 9px;
            padding: 14px;
        }

        .context-card p {
            color: var(--text);
            line-height: 1.55;
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

            .profile-card {
                position: static;
            }
        }

        @media (max-width: 820px) {
            .content,
            .field-grid,
            .detail-grid {
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
            .profile-card {
                padding: 18px;
            }

            .profile-header {
                grid-template-columns: 1fr;
            }

            .form-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .primary-button {
                width: 100%;
            }
        }
    </style>
    <x-minimal-ui />
</head>
<body>
@php
    $selectedUserId = (string) old('user_id', optional($users->first())->id);
    $statusColors = [
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
    $sourceLabels = [
        'admin_override' => 'Admin override',
        'academic_event' => 'Academic event',
        'special_schedule' => 'Special schedule',
        'special_schedule_extended' => 'Extended special schedule',
        'weekly_schedule' => 'Weekly schedule',
        'default' => 'Default availability',
    ];
    $formatTime = function ($time) {
        return $time ? \Carbon\Carbon::createFromFormat('H:i:s', $time)->format('g:i A') : null;
    };
    $formatDateTime = function ($value) {
        return $value ? \Carbon\Carbon::parse($value)->format('M j, Y g:i A') : null;
    };
    $userDetails = $users->mapWithKeys(function ($user) use ($statusColors, $sourceLabels, $formatTime, $formatDateTime) {
        $statusData = $user->live_status;
        $status = $statusData['status'] ?? 'Available';
        $statusKey = ($statusData['source'] ?? null) === 'academic_event'
            ? ($statusData['event_type'] ?? $status)
            : $status;
        $name = $user->full_name ?: $user->username;
        $parts = collect(preg_split('/\s+/', trim($name)) ?: [])->filter();
        $timeWindow = collect([
            $formatDateTime($statusData['status_start_datetime'] ?? null),
            $formatDateTime($statusData['status_end_datetime'] ?? null),
        ])->filter()->implode(' to ');
        $classWindow = collect([
            $formatTime($statusData['class_start_time'] ?? null),
            $formatTime($statusData['class_end_time'] ?? null),
        ])->filter()->implode(' to ');

        if (($statusData['source'] ?? null) === 'academic_event') {
            $context = collect([
                $statusData['event_type'] ?? null,
                $statusData['event_note'] ?? null,
                $statusData['event_purpose'] ?? null,
            ])->filter()->implode(' | ');
        } elseif (!empty($statusData['subject']) || !empty($statusData['room'])) {
            $context = collect([
                $statusData['subject'] ?? null,
                $statusData['room'] ?? null,
                $classWindow,
            ])->filter()->implode(' | ');
        } elseif ($timeWindow) {
            $context = $timeWindow;
        } else {
            $context = 'No active class, meeting, or event detail.';
        }

        return [
            (string) $user->id => [
                'id' => (string) $user->id,
                'name' => $name,
                'username' => $user->username ?: 'No username',
                'role' => ucfirst($user->role),
                'department' => $user->department?->name ?: 'No department',
                'initials' => $parts->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('') ?: 'TT',
                'profile_picture_url' => $user->profile_picture ? asset('storage/'.$user->profile_picture) : null,
                'status' => $status,
                'status_color' => $statusColors[$statusKey] ?? '#6b7280',
                'source' => $sourceLabels[$statusData['source'] ?? 'default'] ?? 'Status source',
                'context' => $context,
            ],
        ];
    });
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

                    <a href="{{ route('admin.status') }}" class="sidebar-link is-active">
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

                    <a href="{{ route('academic_events.create') }}" class="sidebar-link{{ request()->routeIs('academic_events.*') ? ' is-active' : '' }}">
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
                            <span class="eyebrow">Admin Control</span>
                            <h1>Status Override</h1>
                        </div>

                        <div class="page-meta">{{ $users->count() }} staff record{{ $users->count() === 1 ? '' : 's' }}</div>
                    </section>

                    <section class="content">
                        <section class="form-card">
                            <div class="form-inner">
                                <div class="section-heading">
                                    <span class="section-label">Override Form</span>
                                    <h2>Manual status entry</h2>
                                </div>

                                <x-flash-toast />

                                @if(isset($errors) && $errors->any())
                                    <div class="error-alert">
                                        Please review the highlighted fields.
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('admin.status.store') }}" class="override-form">
                                    @csrf

                                    <div class="field-grid">
                                        <div class="field full">
                                            <label for="user_id">User</label>
                                            <div class="picker-stack">
                                                <button
                                                    id="user_picker_trigger"
                                                    type="button"
                                                    class="user-picker-trigger"
                                                    aria-haspopup="listbox"
                                                    aria-expanded="false"
                                                    {{ $users->isEmpty() ? 'disabled' : '' }}
                                                >
                                                    <span id="user_picker_label">Select user</span>
                                                    <span class="picker-chevron">v</span>
                                                </button>

                                                <div id="user_picker_panel" class="user-picker-panel" hidden>
                                                    <input
                                                        id="user_picker_search"
                                                        type="text"
                                                        class="user-picker-search"
                                                        placeholder="Search name"
                                                        autocomplete="off"
                                                    >

                                                    <div id="user_picker_list" class="user-picker-list" role="listbox">
                                                        @foreach($users as $user)
                                                            <button
                                                                type="button"
                                                                class="user-picker-option"
                                                                data-value="{{ $user->id }}"
                                                                data-label="{{ $user->full_name ?: $user->username }}"
                                                            >
                                                                <span>{{ $user->full_name ?: $user->username }}</span>
                                                                <small>{{ ucfirst($user->role) }}</small>
                                                            </button>
                                                        @endforeach
                                                    </div>

                                                    <p id="user_picker_empty" class="user-picker-empty" hidden>No matching users found.</p>
                                                </div>

                                                <select id="user_id" name="user_id" class="native-user-select" required>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}" {{ (string) $user->id === $selectedUserId ? 'selected' : '' }}>
                                                            {{ $user->full_name ?: $user->username }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="field full">
                                            <label for="status">Status</label>
                                            <select id="status" name="status" required>
                                                <option value="" disabled {{ old('status') ? '' : 'selected' }}>Select status</option>
                                                <option value="Emergency" {{ old('status') === 'Emergency' ? 'selected' : '' }}>Emergency</option>
                                                <option value="On Meeting" {{ old('status') === 'On Meeting' ? 'selected' : '' }}>On Meeting</option>
                                                <option value="Private" {{ old('status') === 'Private' ? 'selected' : '' }}>Private</option>
                                            </select>
                                        </div>

                                        <div class="field">
                                            <label for="start_datetime">Start Date and Time</label>
                                            <input
                                                id="start_datetime"
                                                type="datetime-local"
                                                name="start_datetime"
                                                value="{{ old('start_datetime') }}"
                                                required
                                            >
                                        </div>

                                        <div class="field">
                                            <label for="end_datetime">End Date and Time</label>
                                            <input
                                                id="end_datetime"
                                                type="datetime-local"
                                                name="end_datetime"
                                                value="{{ old('end_datetime') }}"
                                                required
                                            >
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="primary-button" {{ $users->isEmpty() ? 'disabled' : '' }}>Apply Override</button>
                                    </div>
                                </form>
                            </div>
                        </section>

                        <aside class="profile-card" aria-label="Selected user details">
                            <div class="profile-inner">
                                <div class="section-heading">
                                    <span class="section-label">Selected User</span>
                                    <h2>Profile and current status</h2>
                                </div>

                                <div class="profile-header">
                                    <img id="profile_image" class="profile-image" src="" alt="" hidden>
                                    <div id="profile_avatar" class="profile-avatar" aria-hidden="true">TT</div>

                                    <div class="profile-title">
                                        <h2 id="profile_name">No user selected</h2>
                                        <p id="profile_subtitle">Select a staff member from the form.</p>
                                        <span id="profile_status" class="status-badge">No status</span>
                                    </div>
                                </div>

                                <div class="detail-grid">
                                    <div class="detail-card">
                                        <span class="detail-label">Role</span>
                                        <span id="detail_role" class="detail-value">Not set</span>
                                    </div>

                                    <div class="detail-card">
                                        <span class="detail-label">Department</span>
                                        <span id="detail_department" class="detail-value">Not set</span>
                                    </div>

                                    <div class="detail-card">
                                        <span class="detail-label">Username</span>
                                        <span id="detail_username" class="detail-value">Not set</span>
                                    </div>

                                    <div class="detail-card">
                                        <span class="detail-label">Source</span>
                                        <span id="detail_source" class="detail-value">Not set</span>
                                    </div>
                                </div>

                                <div class="context-card">
                                    <span class="context-label">Current Detail</span>
                                    <p id="detail_context">No active class, meeting, or event detail.</p>
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
        const users = @json($userDetails);
        const selectedUserId = @json($selectedUserId);
        const userPickerTrigger = document.getElementById('user_picker_trigger');
        const userPickerLabel = document.getElementById('user_picker_label');
        const userPickerPanel = document.getElementById('user_picker_panel');
        const userPickerSearch = document.getElementById('user_picker_search');
        const userPickerEmpty = document.getElementById('user_picker_empty');
        const userSelect = document.getElementById('user_id');
        const userOptions = Array.from(document.querySelectorAll('.user-picker-option'));
        const profileImage = document.getElementById('profile_image');
        const profileAvatar = document.getElementById('profile_avatar');
        const profileName = document.getElementById('profile_name');
        const profileSubtitle = document.getElementById('profile_subtitle');
        const profileStatus = document.getElementById('profile_status');
        const detailRole = document.getElementById('detail_role');
        const detailDepartment = document.getElementById('detail_department');
        const detailUsername = document.getElementById('detail_username');
        const detailSource = document.getElementById('detail_source');
        const detailContext = document.getElementById('detail_context');

        if (!userSelect || !userPickerTrigger || !userPickerLabel || !userPickerPanel || !userPickerSearch || !userPickerEmpty) {
            return;
        }

        function closePicker() {
            userPickerPanel.hidden = true;
            userPickerTrigger.classList.remove('is-open');
            userPickerTrigger.setAttribute('aria-expanded', 'false');
        }

        function openPicker() {
            if (userPickerTrigger.disabled) {
                return;
            }

            userPickerPanel.hidden = false;
            userPickerTrigger.classList.add('is-open');
            userPickerTrigger.setAttribute('aria-expanded', 'true');
            userPickerSearch.focus();
            userPickerSearch.select();
        }

        function filterPickerOptions() {
            const query = userPickerSearch.value.trim().toLowerCase();
            let visibleCount = 0;

            userOptions.forEach(function (optionButton) {
                const matches = optionButton.dataset.label.toLowerCase().includes(query);
                optionButton.hidden = !matches;

                if (matches) {
                    visibleCount += 1;
                }
            });

            userPickerEmpty.hidden = visibleCount !== 0;
        }

        function setActiveOption(userId) {
            userOptions.forEach(function (button) {
                button.classList.toggle('is-active', button.dataset.value === String(userId));
            });
        }

        function updateProfile(user) {
            if (!user) {
                return;
            }

            userPickerLabel.textContent = user.name;
            profileName.textContent = user.name;
            profileSubtitle.textContent = user.role + ' | ' + user.department;
            profileStatus.textContent = user.status;
            profileStatus.style.backgroundColor = user.status_color;
            detailRole.textContent = user.role;
            detailDepartment.textContent = user.department;
            detailUsername.textContent = user.username;
            detailSource.textContent = user.source;
            detailContext.textContent = user.context;

            if (user.profile_picture_url) {
                profileImage.src = user.profile_picture_url;
                profileImage.alt = 'Profile picture of ' + user.name;
                profileImage.hidden = false;
                profileAvatar.hidden = true;
            } else {
                profileImage.hidden = true;
                profileImage.removeAttribute('src');
                profileAvatar.textContent = user.initials;
                profileAvatar.hidden = false;
            }
        }

        function selectUser(userId) {
            const user = users[String(userId)];

            if (!user) {
                return;
            }

            userSelect.value = String(userId);
            setActiveOption(userId);
            updateProfile(user);
        }

        userOptions.forEach(function (optionButton) {
            optionButton.addEventListener('click', function () {
                selectUser(optionButton.dataset.value);
                closePicker();
            });
        });

        userPickerTrigger.addEventListener('click', function () {
            if (userPickerPanel.hidden) {
                openPicker();
                filterPickerOptions();
            } else {
                closePicker();
            }
        });

        userPickerSearch.addEventListener('input', filterPickerOptions);

        userSelect.addEventListener('change', function () {
            selectUser(userSelect.value);
        });

        document.addEventListener('click', function (event) {
            if (!event.target.closest('.picker-stack')) {
                closePicker();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closePicker();
            }
        });

        if (selectedUserId && users[String(selectedUserId)]) {
            selectUser(selectedUserId);
        } else if (userSelect.value) {
            selectUser(userSelect.value);
        }
    });
    </script>
</body>
</html>
