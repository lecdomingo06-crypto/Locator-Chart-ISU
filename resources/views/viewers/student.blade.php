<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="30">
    <title>Student Viewer</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800" rel="stylesheet" />

    <style>
        :root {
            color-scheme: light;
            --bg: #edf7f0;
            --card: rgba(255, 255, 255, 0.92);
            --card-soft: #f3fbf5;
            --card-border: rgba(12, 92, 56, 0.12);
            --text: #113322;
            --muted: #607766;
            --green-900: #0c5c38;
            --green-800: #147247;
            --green-700: #1b8a53;
            --green-100: #ddf4e4;
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

        .student-workspace {
            display: grid;
            grid-template-columns: 228px minmax(0, 1fr);
            min-height: 100vh;
        }

        .student-sidebar {
            position: sticky;
            top: 0;
            z-index: 20;
            display: flex;
            flex-direction: column;
            height: 100vh;
            padding: 22px 14px;
            border-right: 1px solid var(--card-border);
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 12px 0 30px rgba(12, 92, 56, 0.06);
        }

        .student-sidebar-brand {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0 8px 20px;
            border-bottom: 1px solid var(--card-border);
        }

        .student-sidebar-mark {
            display: grid;
            place-items: center;
            width: 44px;
            height: 44px;
            flex: 0 0 auto;
            border-radius: 8px;
            color: #ffffff;
            background: var(--green-800);
            font-weight: 800;
        }

        .student-sidebar-copy {
            display: grid;
            gap: 2px;
            min-width: 0;
        }

        .student-sidebar-copy strong {
            font-size: 0.92rem;
        }

        .student-sidebar-copy span,
        .student-sidebar-note,
        .student-account span {
            color: var(--muted);
            font-size: 0.78rem;
            line-height: 1.45;
        }

        .student-sidebar-note {
            margin: 18px 8px 22px;
        }

        .student-nav-label {
            margin: 0 8px 9px;
            color: #7b8c81;
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .student-nav {
            display: grid;
            gap: 6px;
        }

        .student-nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 43px;
            padding: 0 12px;
            border-radius: 7px;
            color: var(--text);
            font-size: 0.86rem;
            font-weight: 700;
            text-decoration: none;
        }

        .student-nav-link:hover {
            background: var(--green-100);
        }

        .student-nav-link.is-active {
            color: #ffffff;
            background: var(--green-800);
        }

        .student-nav-link svg {
            width: 18px;
            height: 18px;
            flex: 0 0 auto;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .student-account {
            display: grid;
            gap: 3px;
            margin: auto 8px 12px;
            padding-top: 16px;
            border-top: 1px solid var(--card-border);
        }

        .student-account strong {
            overflow: hidden;
            font-size: 0.84rem;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .student-logout-form {
            margin: 0 8px;
        }

        .student-logout-button {
            width: 100%;
            min-height: 39px;
            border: 1px solid rgba(20, 114, 71, 0.28);
            border-radius: 7px;
            color: var(--green-900);
            background: #ffffff;
            font: inherit;
            font-size: 0.8rem;
            font-weight: 800;
            cursor: pointer;
        }

        .student-content {
            min-width: 0;
        }
        .page {
            position: relative;
            z-index: 1;
            padding: 24px;
        }

        .shell {
            max-width: 1180px;
            margin: 0 auto;
            display: grid;
            gap: 18px;
        }

        .topbar,
        .filters,
        .empty-state,
        .viewer-card {
            border: 1px solid var(--card-border);
            background: var(--card);
            backdrop-filter: blur(18px);
            box-shadow: var(--shadow);
        }

        .topbar {
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
            align-items: start;
            padding: 20px 22px;
            border-radius: 22px;
            border: none;
            background:
                radial-gradient(circle at 88% 18%, rgba(185, 255, 210, 0.18), transparent 22%),
                linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 58%, var(--green-700) 100%);
            box-shadow: 0 24px 48px rgba(12, 92, 56, 0.22);
        }

        .topbar::after {
            content: '';
            position: absolute;
            right: -4rem;
            bottom: -4rem;
            width: 13rem;
            height: 13rem;
            border-radius: 42% 58% 60% 40%;
            background: rgba(216, 255, 226, 0.12);
            pointer-events: none;
        }

        .hero-copy {
            position: relative;
            z-index: 1;
            display: grid;
            gap: 12px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            color: #effcf3;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .eyebrow::before {
            display: none;
        }

        h1 {
            margin: 0;
            font-size: clamp(2rem, 4vw, 2.9rem);
            line-height: 1.05;
            letter-spacing: -0.05em;
            color: #f5fff8;
        }

        .hero-copy p {
            margin: 0;
            max-width: 60ch;
            color: rgba(241, 255, 246, 0.82);
            font-size: 0.98rem;
            line-height: 1.7;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: minmax(280px, 1.4fr) repeat(2, minmax(170px, 1fr));
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .hero-stat {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 14px 16px;
            border-radius: 16px;
            background: rgba(8, 58, 35, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .hero-stat span {
            display: block;
            color: rgba(241, 255, 246, 0.68);
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .hero-stat strong {
            display: block;
            margin-top: 6px;
            font-size: 0.98rem;
            color: #ffffff;
        }

        .hero-stat small {
            display: block;
            margin-top: 8px;
            color: rgba(241, 255, 246, 0.74);
            font-size: 0.88rem;
            line-height: 1.5;
        }

        .hero-stat-wide {
            background: rgba(255, 255, 255, 0.14);
        }

        .hero-stat-wide strong {
            font-size: 1.65rem;
            line-height: 1.15;
        }

        .filters {
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-columns: 260px minmax(0, 1fr);
            gap: 18px;
            align-items: end;
            padding: 18px 20px;
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.95), rgba(244, 251, 246, 0.95));
        }

        .filters::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 4px;
            background: linear-gradient(90deg, var(--green-900), var(--green-700));
        }

        .filters-copy h2 {
            margin: 0;
            font-size: 1.1rem;
            letter-spacing: -0.03em;
        }

        .filters-copy p {
            margin: 6px 0 0;
            color: var(--muted);
            line-height: 1.6;
        }

        .results-head {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 16px;
            padding: 2px 2px 0;
        }

        .results-copy {
            display: grid;
            gap: 6px;
        }

        .results-copy span {
            color: var(--green-800);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .results-copy h2 {
            margin: 0;
            font-size: 1.2rem;
            letter-spacing: -0.03em;
        }

        .results-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: flex-end;
        }

        .results-pill {
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(221, 244, 228, 0.8);
            border: 1px solid rgba(20, 114, 71, 0.1);
            color: var(--green-900);
            font-size: 0.82rem;
            font-weight: 600;
        }

        .filter-form {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 220px 120px;
            gap: 14px;
            align-items: end;
        }

        .field {
            display: grid;
            gap: 8px;
            min-width: 0;
        }

        .field label {
            font-size: 0.84rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--green-900);
        }

        .field input,
        .field select {
            width: 100%;
            min-height: 48px;
            padding: 0 16px;
            border-radius: 14px;
            border: 1px solid var(--card-border);
            background: rgba(255, 255, 255, 0.96);
            color: var(--text);
            font: inherit;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .field input:focus,
        .field select:focus {
            border-color: rgba(20, 114, 71, 0.42);
            box-shadow: 0 0 0 4px rgba(20, 114, 71, 0.12);
        }

        .filter-button {
            min-height: 48px;
            padding: 0 20px;
            border: 0;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--green-800), var(--green-900));
            color: #fff;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 14px 28px rgba(20, 114, 71, 0.2);
            transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .filter-button:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, var(--green-700), var(--green-900));
            box-shadow: 0 18px 32px rgba(20, 114, 71, 0.24);
        }

        .viewer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 16px;
        }

        .viewer-card {
            position: relative;
            overflow: hidden;
            display: grid;
            gap: 16px;
            padding: 20px;
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(244, 251, 246, 0.96));
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .viewer-card::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 4px;
            background: linear-gradient(90deg, var(--green-800), #4dc97d);
        }

        .viewer-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 22px 44px rgba(12, 92, 56, 0.12);
        }

        .viewer-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
        }

        .viewer-profile {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .avatar,
        .avatar-placeholder {
            flex: 0 0 72px;
            width: 72px;
            height: 72px;
            border-radius: 18px;
        }

        .avatar {
            object-fit: cover;
            border: 2px solid rgba(20, 114, 71, 0.08);
            box-shadow: none;
        }

        .avatar-placeholder {
            display: grid;
            place-items: center;
            background: var(--green-100);
            color: var(--green-900);
            font-size: 1.35rem;
            font-weight: 800;
        }

        .viewer-title {
            min-width: 0;
        }

        .viewer-title h3 {
            margin: 0;
            font-size: 1.15rem;
            letter-spacing: -0.03em;
        }

        .viewer-title p {
            margin: 4px 0 0;
            color: var(--muted);
            line-height: 1.5;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .meta-card {
            padding: 14px;
            border-radius: 16px;
            background: rgba(221, 244, 228, 0.52);
            border: 1px solid rgba(20, 114, 71, 0.1);
        }

        .meta-card span {
            display: block;
            color: var(--muted);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .meta-card strong {
            display: block;
            margin-top: 6px;
            font-size: 0.96rem;
            letter-spacing: -0.02em;
        }

        .status-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 14px;
            background: rgba(232, 245, 236, 0.55);
            border: 1px solid rgba(20, 114, 71, 0.08);
        }

        .status-row strong {
            display: block;
            font-size: 0.84rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .status-row span {
            display: block;
            margin-top: 6px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 108px;
            padding: 8px 14px;
            border-radius: 999px;
            color: #fff;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.14);
        }

        .class-card {
            padding: 16px;
            border-radius: 16px;
            background: linear-gradient(180deg, rgba(20, 114, 71, 0.08), rgba(20, 114, 71, 0.03));
            border: 1px solid rgba(20, 114, 71, 0.12);
        }

        .class-card strong {
            display: block;
            margin-bottom: 10px;
            color: var(--green-900);
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .class-details {
            display: grid;
            gap: 8px;
        }

        .class-line {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            color: var(--text);
            font-size: 0.92rem;
        }

        .class-line span {
            color: var(--muted);
            font-weight: 600;
        }

        .legacy-hidden {
            display: none;
        }

        .empty-state {
            padding: 28px;
            border-radius: 20px;
            text-align: center;
        }

        .empty-state h2 {
            margin: 0 0 10px;
            font-size: 1.7rem;
            letter-spacing: -0.03em;
        }

        .empty-state p {
            margin: 0;
            color: var(--muted);
            line-height: 1.8;
        }

        @media (max-width: 840px) {
            .student-workspace {
                grid-template-columns: 1fr;
            }

            .student-sidebar {
                position: static;
                height: auto;
                padding: 16px;
                border-right: 0;
                border-bottom: 1px solid var(--card-border);
            }

            .student-sidebar-note,
            .student-nav-label {
                display: none;
            }

            .student-nav {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                margin-top: 14px;
            }

            .student-nav-link {
                justify-content: center;
            }

            .student-account {
                margin: 14px 8px 8px;
            }

            .student-logout-form {
                width: min(220px, 100%);
            }
        }
        @media (max-width: 1024px) {
            .hero-stats,
            .filter-form {
                grid-template-columns: 1fr;
            }

            .filters {
                grid-template-columns: 1fr;
            }

            .results-head {
                align-items: flex-start;
                flex-direction: column;
            }

            .results-meta {
                justify-content: flex-start;
            }
        }

        @media (max-width: 720px) {
            .page {
                padding: 16px;
            }

            .topbar,
            .filters,
            .viewer-card,
            .empty-state {
                padding: 16px;
                border-radius: 18px;
            }

            .viewer-head,
            .status-row,
            .class-line {
                align-items: flex-start;
                flex-direction: column;
            }

            .meta-grid {
                grid-template-columns: 1fr;
            }

            .field,
            .filter-button {
                width: 100%;
            }
        }
    </style>
    <x-minimal-ui />
    <style data-viewer-minimal-fix>
        .student-content .page {
            padding: 24px !important;
        }

        .student-content .shell {
            width: 100% !important;
            max-width: 1180px !important;
            margin-inline: auto !important;
        }

        .student-content .topbar h1 {
            font-size: 2.9rem !important;
            line-height: 1.05 !important;
            color: #f5fff8 !important;
        }

        .student-content .hero-copy p {
            color: rgba(241, 255, 246, 0.82) !important;
            line-height: 1.7 !important;
        }

        .student-content .viewer-grid {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)) !important;
            gap: 16px !important;
        }

        .student-content .viewer-card {
            gap: 16px !important;
            padding: 20px !important;
            border-radius: 20px !important;
        }

        .student-content .viewer-title h3 {
            font-size: 1.15rem !important;
            line-height: 1.25 !important;
        }

        @media (max-width: 720px) {
            .student-content .page {
                padding: 16px !important;
            }

            .student-content .topbar h1 {
                font-size: 1.8rem !important;
            }

            .student-content .viewer-grid {
                grid-template-columns: 1fr !important;
            }

            .student-content .viewer-card {
                padding: 16px !important;
                border-radius: 18px !important;
            }
        }
    </style>
</head>
<body>
    <div class="student-workspace">
        <x-student-sidebar active="viewer" />

        <main class="student-content">
            <div class="page">
        <div class="shell">
            <section class="topbar">
                <div class="hero-copy">
                    <div class="eyebrow">Student Viewer</div>
                    <h1>Professor and Faculty Availability</h1>
                    <p>
                        A simpler live viewer for students to check who is available right now.
                    </p>
                </div>

                <div class="hero-stats" aria-label="Viewer summary">
                    <div class="hero-stat hero-stat-wide">
                        <span>Live Monitoring Active</span>
                        <strong id="clock"></strong>
                        <small>The page refreshes automatically every 30 seconds.</small>
                    </div>

                    <div class="hero-stat">
                        <span>Visible Staff</span>
                        <strong>{{ $users->count() }} record{{ $users->count() === 1 ? '' : 's' }}</strong>
                    </div>

                    <div class="hero-stat">
                        <span>Department Scope</span>
                        <strong>{{ $department ? 'Filtered department' : 'All departments' }}</strong>
                    </div>
                </div>
            </section>

            <section class="filters">
                <div class="filters-copy">
                    <h2>Search and Filter</h2>
                    <p>Use the same viewer tools in a cleaner layout.</p>
                </div>

                <form method="GET" action="{{ route('student.viewer') }}" class="filter-form">
                    <div class="field">
                        <label for="search">Search Name</label>
                        <input id="search" type="text" name="search" placeholder="Search by name" value="{{ $search }}">
                    </div>

                    <div class="field">
                        <label for="department">Department</label>
                        <select id="department" name="department">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ $department == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="filter-button">Filter</button>
                </form>
            </section>

            <section class="results-head" aria-label="Results summary">
                <div class="results-copy">
                    <span>Live Directory</span>
                    <h2>Professor and Faculty List</h2>
                </div>

                <div class="results-meta">
                    <div class="results-pill">{{ $users->count() }} staff shown</div>
                    <div class="results-pill">{{ $department ? 'Department filtered' : 'All departments' }}</div>
                </div>
            </section>

            <section class="viewer-grid">

                @forelse($users as $user)
                    <article class="viewer-card">
                        @php
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
                        @endphp

                        <div class="viewer-head">
                            <div class="viewer-profile">
                                @if($user->profile_picture)
                                    <img
                                        src="{{ asset('storage/' . $user->profile_picture) }}"
                                        alt="Profile picture of {{ $user->full_name }}"
                                        class="avatar"
                                    >
                                @else
                                    <div class="avatar-placeholder" aria-hidden="true">
                                        {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif

                                <div class="viewer-title">
                                    <h3>{{ $user->full_name }}</h3>
                                    <p>{{ ucfirst($user->role) }} availability profile</p>
                                </div>
                            </div>
                        </div>

                        <div class="meta-grid">
                            <div class="meta-card">
                                <span>Role</span>
                                <strong>{{ ucfirst($user->role) }}</strong>
                            </div>

                            <div class="meta-card">
                                <span>Department</span>
                                <strong>{{ $user->department->name ?? 'No Department' }}</strong>
                            </div>
                        </div>

                        <div class="status-row">
                            <div>
                                <strong>Current Status</strong>
                                <span>Real-time availability</span>
                            </div>

                            <div class="status-badge" style="background-color: {{ $badgeColor }};">
                                {{ $status }}
                            </div>
                        </div>

                        @if(($statusData['source'] ?? null) === 'academic_event')
                            <div class="class-card">
                                <strong>Active Academic Event</strong>
                                <div class="class-details">
                                    <div class="class-line">
                                        <span>Type</span>
                                        <div>{{ $statusData['event_type'] }}</div>
                                    </div>

                                    @if($statusData['event_note'])
                                        <div class="class-line">
                                            <span>Note</span>
                                            <div>{{ $statusData['event_note'] }}</div>
                                        </div>
                                    @endif

                                    @if($statusData['event_purpose'])
                                        <div class="class-line">
                                            <span>Purpose</span>
                                            <div>{{ $statusData['event_purpose'] }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif($status === 'On Meeting' && !empty($statusData['status_start_datetime']) && !empty($statusData['status_end_datetime']))
                            @php
                                $meetingStart = \Carbon\Carbon::parse($statusData['status_start_datetime']);
                                $meetingEnd = \Carbon\Carbon::parse($statusData['status_end_datetime']);
                                $sameMeetingDay = $meetingStart->isSameDay($meetingEnd);
                            @endphp
                            <div class="class-card">
                                <strong>Meeting Time</strong>
                                <div class="class-details">
                                    <div class="class-line">
                                        <span>Starts</span>
                                        <div>{{ $sameMeetingDay ? $meetingStart->format('g:i A') : $meetingStart->format('M j, g:i A') }}</div>
                                    </div>
                                    <div class="class-line">
                                        <span>Ends</span>
                                        <div>{{ $sameMeetingDay ? $meetingEnd->format('g:i A') : $meetingEnd->format('M j, g:i A') }}</div>
                                    </div>
                                </div>
                            </div>
                        @elseif(in_array($status, ['On Break', 'Not Available'], true) && !empty($statusData['status_start_datetime']) && !empty($statusData['status_end_datetime']))
                            @php
                                $availabilityStart = \Carbon\Carbon::parse($statusData['status_start_datetime']);
                                $availabilityEnd = \Carbon\Carbon::parse($statusData['status_end_datetime']);
                                $sameAvailabilityDay = $availabilityStart->isSameDay($availabilityEnd);
                            @endphp
                            <div class="class-card">
                                <strong>Availability Time</strong>
                                <div class="class-details">
                                    <div class="class-line">
                                        <span>Starts</span>
                                        <div>{{ $sameAvailabilityDay ? $availabilityStart->format('g:i A') : $availabilityStart->format('M j, g:i A') }}</div>
                                    </div>
                                    <div class="class-line">
                                        <span>Ends</span>
                                        <div>{{ $sameAvailabilityDay ? $availabilityEnd->format('g:i A') : $availabilityEnd->format('M j, g:i A') }}</div>
                                    </div>
                                </div>
                            </div>
                        @elseif($status === 'In Class')
                            <div class="class-card">
                                <strong>Current Class Details</strong>
                                <div class="class-details">
                                    <div class="class-line">
                                        <span>Subject</span>
                                        <div>{{ $statusData['subject'] }}</div>
                                    </div>
                                    <div class="class-line">
                                        <span>Room</span>
                                        <div>{{ $statusData['room'] }}</div>
                                    </div>
                                    @if(!empty($statusData['class_start_time']) && !empty($statusData['class_end_time']))
                                        <div class="class-line">
                                            <span>Time</span>
                                            <div>{{ \Carbon\Carbon::createFromFormat('H:i:s', $statusData['class_start_time'])->format('g:i A') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $statusData['class_end_time'])->format('g:i A') }}</div>
                                        </div>
                                    @endif
                                    <span class="legacy-hidden">
                    📘 <strong>{{ $statusData['subject'] }}</strong><br>
                    🏫 Room: {{ $statusData['room'] }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </article>
                @empty
                    <section class="empty-state">
                        <h2>No professors or faculty found.</h2>
                        <p>Try adjusting the name search or department filter to load another set of staff records.</p>
                    </section>
                @endforelse
            </section>
        </div>
            </div>
        </main>
    </div>

    <script>
    function updateClock() {
        const now = new Date();
        document.getElementById('clock').innerText = now.toLocaleString();
    }

    setInterval(updateClock, 1000);
    updateClock();
    </script>
</body>
</html>
