<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Professor Tracker') }} - Edit Profile</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            color-scheme: light;
            --page: #edf7f0;
            --panel: #ffffff;
            --line: rgba(12, 92, 56, 0.12);
            --text: #113322;
            --muted: #607766;
            --accent: #147247;
            --accent-dark: #0c5c38;
            --accent-soft: #eef8f1;
            --green-900: #0c5c38;
            --green-800: #147247;
            --green-100: #ddf4e4;
            --shadow: 0 18px 42px rgba(12, 92, 56, 0.08);
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
                linear-gradient(180deg, #f7fcf8 0%, var(--page) 100%);
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

        .workspace-layout {
            display: grid;
            grid-template-columns: 244px minmax(0, 1fr);
            gap: 26px;
            align-items: start;
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

        .workspace-brand-mark {
            display: grid;
            place-items: center;
            width: 48px;
            height: 48px;
            border-radius: 8px;
            color: var(--green-900);
            background: rgba(255, 255, 255, 0.94);
            box-shadow: inset 0 0 0 1px rgba(12, 92, 56, 0.08);
            font-weight: 800;
        }

        .workspace-brand-copy {
            display: grid;
            gap: 3px;
        }

        .workspace-brand-copy strong {
            font-size: 1rem;
        }

        .workspace-brand-copy span {
            color: rgba(239, 252, 243, 0.76);
            font-size: 0.88rem;
            font-weight: 600;
            line-height: 1.5;
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

        .workspace-sidebar {
            position: sticky;
            top: 94px;
            display: grid;
            align-content: start;
            gap: 18px;
            min-height: calc(100vh - 112px);
            margin-left: 16px;
            padding: 18px 14px;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid var(--line);
            border-left: 0;
            border-radius: 0 8px 8px 0;
            box-shadow: 0 18px 40px rgba(12, 92, 56, 0.1);
        }

        .sidebar-brand {
            gap: 12px;
            padding: 0 8px 18px;
            border-bottom: 1px solid var(--line);
        }

        .sidebar-mark {
            display: grid;
            place-items: center;
            flex: 0 0 46px;
            width: 46px;
            height: 46px;
            border-radius: 8px;
            color: #ffffff;
            background: linear-gradient(145deg, var(--green-800), var(--green-900));
            font-weight: 800;
        }

        .sidebar-copy {
            display: grid;
            gap: 3px;
        }

        .sidebar-copy strong {
            color: #173524;
            font-size: 0.95rem;
        }

        .sidebar-copy span,
        .sidebar-note,
        .sidebar-user span {
            color: var(--muted);
            font-size: 0.82rem;
            line-height: 1.5;
        }

        .sidebar-note {
            margin: 0 8px;
        }

        .sidebar-section-label {
            margin: 4px 8px 0;
            color: #8b91a1;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.13em;
            text-transform: uppercase;
        }

        .sidebar-nav {
            display: grid;
            gap: 8px;
        }

        .sidebar-link {
            gap: 11px;
            min-height: 42px;
            padding: 0 12px;
            border-radius: 8px;
            color: #25352d;
            text-decoration: none;
            font-size: 0.9rem;
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
        }

        .sidebar-user {
            display: grid;
            gap: 3px;
            margin: 12px 8px 0;
            padding: 14px 0 0;
            border-top: 1px solid var(--line);
        }

        .sidebar-user strong {
            color: #173524;
            font-size: 0.9rem;
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
            font: inherit;
            font-size: 0.84rem;
            font-weight: 800;
            cursor: pointer;
        }

        .profile-main {
            display: grid;
            justify-items: center;
            align-content: start;
            padding: 24px 28px 32px 0;
        }

        .edit-heading {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            width: min(860px, 100%);
            margin-bottom: 22px;
            color: #565b6d;
            font-size: 0.84rem;
            font-weight: 700;
        }

        .edit-heading::before {
            content: '';
            width: 10px;
            height: 6px;
            border-radius: 999px;
            background: var(--accent);
        }

        .profile-editor {
            position: relative;
            overflow: hidden;
            width: min(860px, 100%);
            padding: 44px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--panel);
            box-shadow: var(--shadow);
        }

        .profile-editor::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-dark), var(--accent));
        }

        .profile-form {
            display: grid;
            grid-template-columns: 286px minmax(0, 1fr);
            gap: 48px;
            align-items: start;
        }

        .photo-column {
            display: grid;
            gap: 18px;
        }

        .photo-card {
            position: relative;
            display: grid;
            place-items: center;
            width: 100%;
            aspect-ratio: 1 / 0.88;
            overflow: hidden;
            border: 0;
            border-radius: 8px;
            background: var(--accent-soft);
            cursor: pointer;
        }

        .photo-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            display: grid;
            place-items: center;
            width: 100%;
            height: 100%;
            color: var(--accent-dark);
            font-size: 4rem;
            font-weight: 800;
        }

        .camera-icon {
            position: absolute;
            top: 14px;
            left: 14px;
            display: grid;
            place-items: center;
            width: 28px;
            height: 28px;
            border-radius: 999px;
            color: var(--accent-dark);
            background: rgba(255, 255, 255, 0.74);
        }

        .camera-icon svg,
        .upload-tile svg {
            width: 16px;
            height: 16px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .upload-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .upload-tile {
            display: grid;
            place-items: center;
            gap: 6px;
            min-height: 58px;
            padding: 8px;
            border: 1.5px dashed rgba(20, 114, 71, 0.35);
            border-radius: 8px;
            color: var(--green-800);
            background: #ffffff;
            font-size: 0.66rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-align: center;
            text-transform: uppercase;
            cursor: pointer;
        }

        .upload-tile.is-muted {
            cursor: default;
        }

        .file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
        }

        .details-column {
            display: grid;
            gap: 16px;
            padding-top: 6px;
        }

        .field-row {
            display: grid;
            grid-template-columns: 96px minmax(0, 1fr);
            gap: 18px;
            align-items: center;
        }

        .field-row label {
            color: #4d5264;
            font-size: 0.76rem;
            font-weight: 700;
        }

        .field-row input {
            width: 100%;
            min-height: 38px;
            padding: 0 14px;
            border: 1px solid transparent;
            border-radius: 5px;
            color: #1f2937;
            background: #fdfdff;
            font: inherit;
            font-size: 0.82rem;
            outline: none;
        }

        .field-row input[readonly] {
            cursor: default;
        }

        .field-control {
            display: grid;
            gap: 5px;
            min-width: 0;
        }

        .field-row input:not([readonly]) {
            border-color: rgba(20, 114, 71, 0.22);
            background: #ffffff;
        }

        .field-row input:not([readonly]):focus {
            border-color: var(--green-800);
            box-shadow: 0 0 0 3px rgba(20, 114, 71, 0.1);
        }

        .field-error {
            margin: 0;
            color: #b42318;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .form-actions {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 10px;
            margin-top: 10px;
            padding-left: 114px;
        }

        .primary-button,
        .secondary-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            border-radius: 7px;
            font: inherit;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-decoration: none;
            text-transform: uppercase;
        }

        .primary-button {
            border: 0;
            color: #ffffff;
            background: linear-gradient(135deg, var(--green-800), var(--green-900));
            cursor: pointer;
        }

        .secondary-link {
            color: var(--green-900);
            background: #ffffff;
            border: 1px solid rgba(20, 114, 71, 0.35);
        }

        .password-editor {
            margin-top: 18px;
            padding: 30px 34px;
        }

        .password-heading {
            margin-bottom: 22px;
        }

        .password-heading span {
            color: var(--green-800);
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .password-heading h2 {
            margin: 4px 0 0;
            font-size: 1.25rem;
        }

        .password-heading p {
            margin: 7px 0 0;
            color: var(--muted);
            font-size: 0.84rem;
            line-height: 1.55;
        }

        .password-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .password-field {
            display: grid;
            gap: 7px;
            min-width: 0;
        }

        .password-field label {
            color: #4d5264;
            font-size: 0.76rem;
            font-weight: 700;
        }

        .password-field input {
            width: 100%;
            min-height: 42px;
            padding: 0 12px;
            border: 1px solid rgba(20, 114, 71, 0.22);
            border-radius: 6px;
            color: var(--text);
            background: #ffffff;
            font: inherit;
            outline: none;
        }

        .password-field input:focus {
            border-color: var(--green-800);
            box-shadow: 0 0 0 3px rgba(20, 114, 71, 0.1);
        }

        .password-error {
            margin: 0;
            color: #b42318;
            font-size: 0.74rem;
            font-weight: 700;
        }

        .password-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 18px;
        }

        .password-actions .primary-button {
            min-width: 180px;
            padding: 0 18px;
        }
        .alert-error {
            padding: 12px 14px;
            border-radius: 8px;
            color: #b42318;
            background: #fff1f1;
            border: 1px solid #ffd2d2;
            font-size: 0.82rem;
            font-weight: 700;
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
            }

            .workspace-sidebar {
                position: static;
                min-height: 0;
                margin: 16px 16px 0;
                border-left: 1px solid var(--line);
                border-radius: 8px;
            }

            .sidebar-nav {
                grid-template-columns: repeat(5, minmax(0, 1fr));
            }

            .profile-main {
                padding: 26px 18px;
            }

            .profile-form {
                grid-template-columns: 1fr;
                gap: 28px;
            }

            .form-actions {
                padding-left: 0;
            }
        }

        @media (max-width: 640px) {
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
            .upload-row,
            .form-actions {
                grid-template-columns: 1fr;
            }

            .password-grid {
                grid-template-columns: 1fr;
            }

            .password-actions,
            .password-actions .primary-button {
                width: 100%;
            }
            .profile-editor {
                padding: 22px;
            }

            .field-row {
                grid-template-columns: 1fr;
                gap: 8px;
            }
        }
    </style>
    <x-minimal-ui />
</head>
<body>
@php
    $displayName = $user->full_name ?: $user->username;
    $departmentName = $user->department->name ?? 'No department assigned';
    $statusData = $user->live_status;
    $status = $statusData['status'];
    $profileErrors = $errors ?? new \Illuminate\Support\ViewErrorBag;
    $isStaff = in_array($user->role, ['professor', 'faculty'], true);
    $viewerRoute = $user->role === 'student' ? 'student.viewer' : 'staff.viewer';
@endphp

    <div class="workspace-shell">
        <header class="workspace-topbar">
            <a href="{{ route($viewerRoute) }}" class="workspace-brand">
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
                        <span>Profile and schedule tools</span>
                    </div>
                </div>

                <p class="sidebar-note">Monitor availability and manage your profile tools.</p>

                <span class="sidebar-section-label">Workspace</span>
                <nav class="sidebar-nav" aria-label="{{ ucfirst($user->role) }} workspace">
                    <a href="{{ route($viewerRoute) }}" class="sidebar-link{{ request()->routeIs('staff.viewer') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M2.5 12s3.5-5.5 9.5-5.5 9.5 5.5 9.5 5.5-3.5 5.5-9.5 5.5S2.5 12 2.5 12Z"></path>
                            <path d="M12 15a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z"></path>
                        </svg>
                        <span>Live Viewer</span>
                    </a>

                    @if($user->role === 'student')
                        <a href="{{ route('student.dashboard') }}" class="sidebar-link">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="m4 6 5-2 6 2 5-2v14l-5 2-6-2-5 2V6Z"></path>
                                <path d="M9 4v14M15 6v14"></path>
                            </svg>
                            <span>Campus Map</span>
                        </a>
                    @endif
                    @if($isStaff)
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

                    @endif

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

                    @if($isStaff)
                    <a href="{{ route('special_schedules.create') }}" class="sidebar-link{{ request()->routeIs('special_schedules.*') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 6l1.6 4.4L18 12l-4.4 1.6L12 18l-1.6-4.4L6 12l4.4-1.6L12 6Z"></path>
                            <path d="M19 4v4"></path>
                            <path d="M21 6h-4"></path>
                        </svg>
                        <span>Special Schedule</span>
                    </a>                    @endif

                </nav>
            <x-sidebar-account-footer />
            </aside>
<x-responsive-sidebar-control />

            <main class="profile-main">
            <div class="edit-heading">Edit Profile</div>

            <x-flash-toast />

            <section class="profile-editor">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="profile-form">
                    @csrf

                    <div class="photo-column">
                        <label for="profile_picture" class="photo-card">
                            <span class="camera-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path d="M4 8h3l1.5-2h7L17 8h3v11H4V8Z"></path>
                                    <path d="M12 16a3.5 3.5 0 1 0 0-7a3.5 3.5 0 0 0 0 7Z"></path>
                                </svg>
                            </span>

                            @if($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile picture of {{ $displayName }}">
                            @else
                                <span class="photo-placeholder">{{ strtoupper(substr($displayName ?? 'U', 0, 1)) }}</span>
                            @endif
                        </label>

                        <input type="file" name="profile_picture" id="profile_picture" class="file-input">

                        <div class="upload-row">
                            <label for="profile_picture" class="upload-tile">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12 16V4"></path>
                                    <path d="m7 9 5-5 5 5"></path>
                                    <path d="M5 20h14"></path>
                                </svg>
                                <span>Profile Photo</span>
                            </label>

                            <div class="upload-tile is-muted">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M7 3h7l4 4v14H7V3Z"></path>
                                    <path d="M14 3v5h5"></path>
                                    <path d="M9 13h6"></path>
                                    <path d="M9 17h4"></path>
                                </svg>
                                <span>Account Details</span>
                            </div>
                        </div>

                        @if($profileErrors->has('profile_picture'))
                            <div class="alert-error">{{ $profileErrors->first('profile_picture') }}</div>
                        @endif
                    </div>

                    <div class="details-column">
                        <div class="field-row">
                            <label for="profile_name">Name</label>
                            <div class="field-control">
                                <input id="profile_name" name="full_name" type="text" value="{{ old('full_name', $displayName) }}" autocomplete="name" required>
                                @error('full_name')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="field-row">
                            <label for="profile_role">Role</label>
                            <input id="profile_role" type="text" value="{{ ucfirst($user->role) }}" readonly>
                        </div>

                        @if($user->role === 'student' && $user->student_id)
                            <div class="field-row">
                                <label for="profile_student_id">Student ID</label>
                                <input id="profile_student_id" type="text" value="{{ $user->student_id }}" readonly>
                            </div>
                        @endif

                        <div class="field-row">
                            <label for="profile_department">Department</label>
                            <input id="profile_department" type="text" value="{{ $departmentName }}" readonly>
                        </div>

                        <div class="field-row">
                            <label for="profile_username">Username</label>
                            <div class="field-control">
                                <input id="profile_username" name="username" type="text" value="{{ old('username', $user->username) }}" autocomplete="username" required>
                                @error('username')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="field-row">
                            <label for="profile_email">Email</label>
                            <div class="field-control">
                                <input id="profile_email" name="email" type="email" value="{{ old('email', $user->email) }}" autocomplete="email" required>
                                @error('email')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="field-row">
                            <label for="profile_status">Status</label>
                            <input id="profile_status" type="text" value="{{ $status }}" readonly>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="primary-button">Save Changes</button>
                            <a href="{{ route($viewerRoute) }}" class="secondary-link">Cancel</a>
                        </div>
                    </div>
                </form>
            </section>

            <section class="profile-editor password-editor" id="change-password">
                <div class="password-heading">
                    <span>Account Security</span>
                    <h2>Change Password</h2>
                    <p>Enter your current password before choosing a new password for your account.</p>
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="password-form">
                    @csrf
                    @method('PUT')

                    <div class="password-grid">
                        <div class="password-field">
                            <label for="current_password">Current Password</label>
                            <input id="current_password" name="current_password" type="password" autocomplete="current-password" required>
                            @error('current_password', 'updatePassword')
                                <p class="password-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="password-field">
                            <label for="new_password">New Password</label>
                            <input id="new_password" name="password" type="password" autocomplete="new-password" required>
                            @error('password', 'updatePassword')
                                <p class="password-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="password-field">
                            <label for="new_password_confirmation">Confirm New Password</label>
                            <input id="new_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
                            @error('password_confirmation', 'updatePassword')
                                <p class="password-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="password-actions">
                        <button type="submit" class="primary-button">Change Password</button>
                    </div>
                </form>
            </section>
            </main>
        </div>
    </div>
</body>
</html>
