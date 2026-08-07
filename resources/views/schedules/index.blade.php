<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Professor Tracker') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800" rel="stylesheet" />

    <style>
        :root {
            color-scheme: light;
            --bg-top: #eef9f1;
            --bg-bottom: #dff1e4;
            --card: rgba(255, 255, 255, 0.84);
            --card-border: rgba(255, 255, 255, 0.8);
            --text: #123524;
            --muted: #5a7261;
            --green-900: #0c5c38;
            --green-800: #147247;
            --green-700: #1d8a54;
            --green-100: #e6f5ea;
            --shadow: 0 22px 52px rgba(13, 72, 43, 0.12);
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
                radial-gradient(circle at top left, rgba(118, 210, 149, 0.3), transparent 30%),
                radial-gradient(circle at 82% 18%, rgba(51, 153, 97, 0.24), transparent 18%),
                linear-gradient(145deg, var(--bg-top), var(--bg-bottom));
        }

        body::before,
        body::after {
            content: '';
            position: fixed;
            z-index: 0;
            border-radius: 999px;
            filter: blur(12px);
            pointer-events: none;
        }

        body::before {
            width: 28rem;
            height: 28rem;
            top: -8rem;
            right: -7rem;
            background: rgba(42, 162, 90, 0.16);
        }

        body::after {
            width: 24rem;
            height: 24rem;
            left: -6rem;
            bottom: -8rem;
            background: rgba(15, 92, 56, 0.1);
        }

        .page {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            padding: 28px;
        }

        .shell {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            gap: 22px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 18px 22px;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.56);
            border: 1px solid rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(18px);
            box-shadow: 0 12px 36px rgba(16, 70, 45, 0.08);
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 16px;
        }

        .brand-mark {
            position: relative;
            width: 54px;
            height: 54px;
            border-radius: 18px;
            background: linear-gradient(160deg, #25a760, #0c5c38);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.32);
        }

        .brand-mark::before,
        .brand-mark::after {
            content: '';
            position: absolute;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.95);
        }

        .brand-mark::before {
            width: 14px;
            height: 14px;
            left: 11px;
            top: 13px;
            box-shadow: 18px 0 0 rgba(255, 255, 255, 0.95);
        }

        .brand-mark::after {
            width: 30px;
            height: 12px;
            left: 12px;
            bottom: 13px;
            border-radius: 999px 999px 14px 14px;
        }

        .brand-copy {
            display: grid;
            gap: 4px;
        }

        .brand-copy strong {
            font-size: 1.15rem;
            letter-spacing: -0.02em;
        }

        .brand-copy span {
            color: var(--muted);
            font-size: 0.95rem;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            color: var(--green-900);
            background: rgba(217, 242, 226, 0.86);
            border: 1px solid rgba(25, 138, 82, 0.14);
            font-size: 0.95rem;
            font-weight: 600;
        }

        .status-pill::before {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: #22c55e;
            box-shadow: 0 0 0 6px rgba(34, 197, 94, 0.14);
        }

        .hero {
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(320px, 0.95fr);
            gap: 24px;
            align-items: stretch;
        }

        .hero-card,
        .summary-card {
            position: relative;
            overflow: hidden;
            border-radius: 32px;
            box-shadow: var(--shadow);
        }

        .hero-card {
            padding: 30px;
            background: var(--card);
            border: 1px solid var(--card-border);
            backdrop-filter: blur(16px);
        }

        .hero-card::before {
            content: '';
            position: absolute;
            left: -8%;
            bottom: -14%;
            width: 20rem;
            height: 20rem;
            border-radius: 46% 54% 58% 42%;
            background: linear-gradient(180deg, rgba(29, 138, 87, 0.14), rgba(12, 92, 56, 0.04));
        }

        .hero-inner,
        .summary-inner {
            position: relative;
            z-index: 1;
            display: grid;
            gap: 20px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            width: fit-content;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(12, 92, 56, 0.08);
            color: var(--green-900);
            font-size: 0.84rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .eyebrow::before {
            content: '';
            width: 28px;
            height: 1px;
            background: rgba(12, 92, 56, 0.32);
        }

        h1 {
            margin: 0;
            font-size: clamp(2.4rem, 5vw, 4rem);
            line-height: 0.96;
            letter-spacing: -0.05em;
        }

        .hero-card p {
            margin: 0;
            max-width: 56ch;
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.8;
        }

        .hero-actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            align-items: center;
        }

        .primary-link,
        .secondary-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 52px;
            padding: 0 22px;
            border-radius: 18px;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .primary-link {
            color: #fff;
            background: linear-gradient(135deg, var(--green-800), var(--green-900));
            box-shadow: 0 18px 30px rgba(12, 92, 56, 0.22);
        }

        .secondary-link {
            color: var(--green-900);
            background: rgba(255, 255, 255, 0.82);
            border: 1px solid rgba(12, 92, 56, 0.14);
            box-shadow: 0 12px 24px rgba(14, 76, 46, 0.08);
        }

        .primary-link:hover,
        .secondary-link:hover {
            transform: translateY(-2px);
        }

        .summary-card {
            padding: 26px;
            color: #eefcf2;
            background:
                radial-gradient(circle at top right, rgba(74, 222, 128, 0.24), transparent 28%),
                linear-gradient(180deg, #156941 0%, #0c5434 55%, #083924 100%);
        }

        .summary-card::before,
        .summary-card::after {
            content: '';
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        .summary-card::before {
            width: 16rem;
            height: 16rem;
            top: -6rem;
            right: -4rem;
            background: rgba(219, 255, 228, 0.12);
        }

        .summary-card::after {
            width: 14rem;
            height: 14rem;
            left: -4rem;
            bottom: -5rem;
            background: rgba(180, 250, 200, 0.08);
        }

        .summary-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: fit-content;
            padding: 9px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.84rem;
            font-weight: 700;
        }

        .summary-tag::before {
            content: '';
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: #4ade80;
        }

        .summary-card h2 {
            margin: 0;
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            line-height: 1;
            letter-spacing: -0.04em;
        }

        .summary-card p {
            margin: 0;
            color: rgba(238, 252, 242, 0.78);
            line-height: 1.72;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .summary-item {
            padding: 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .summary-item span {
            display: block;
            color: rgba(238, 252, 242, 0.66);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .summary-item strong {
            display: block;
            margin-top: 8px;
            font-size: 1rem;
            line-height: 1.55;
        }

        .success-alert {
            padding: 14px 16px;
            border-radius: 18px;
            color: #116537;
            background: rgba(217, 242, 226, 0.92);
            border: 1px solid rgba(25, 138, 82, 0.16);
            font-size: 0.96rem;
            font-weight: 600;
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

        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 18px;
        }

        .schedule-card,
        .empty-state {
            border: 1px solid var(--card-border);
            background: var(--card);
            backdrop-filter: blur(18px);
            box-shadow: var(--shadow);
            border-radius: 26px;
        }

        .schedule-card {
            position: relative;
            overflow: hidden;
            display: grid;
            gap: 18px;
            padding: 22px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(244, 251, 246, 0.96));
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .schedule-card::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 4px;
            background: linear-gradient(90deg, var(--green-800), #4dc97d);
        }

        .schedule-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 22px 44px rgba(12, 92, 56, 0.12);
        }

        .schedule-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
        }

        .subject-block {
            display: grid;
            gap: 8px;
        }

        .subject-block h3 {
            margin: 0;
            font-size: 1.2rem;
            letter-spacing: -0.03em;
        }

        .subject-block p {
            margin: 0;
            color: var(--muted);
            line-height: 1.5;
        }

        .day-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 104px;
            padding: 9px 14px;
            border-radius: 999px;
            color: #fff;
            background: linear-gradient(135deg, var(--green-800), var(--green-900));
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.14);
        }

        .time-banner {
            display: grid;
            gap: 4px;
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(232, 245, 236, 0.6);
            border: 1px solid rgba(20, 114, 71, 0.1);
        }

        .time-banner span {
            color: var(--muted);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .time-banner strong {
            font-size: 1.02rem;
            letter-spacing: -0.02em;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .detail-card {
            padding: 14px;
            border-radius: 16px;
            background: rgba(221, 244, 228, 0.52);
            border: 1px solid rgba(20, 114, 71, 0.1);
        }

        .detail-card span {
            display: block;
            color: var(--muted);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .detail-card strong {
            display: block;
            margin-top: 6px;
            font-size: 0.96rem;
            letter-spacing: -0.02em;
            line-height: 1.5;
        }

        .card-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .edit-link,
        .delete-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 0 18px;
            border-radius: 16px;
            font: inherit;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .edit-link {
            color: #fff;
            background: linear-gradient(135deg, var(--green-800), var(--green-900));
            box-shadow: 0 14px 24px rgba(12, 92, 56, 0.18);
        }

        .delete-button {
            border: 1px solid rgba(185, 28, 28, 0.12);
            color: #991b1b;
            background: rgba(255, 255, 255, 0.84);
            box-shadow: 0 12px 24px rgba(127, 29, 29, 0.08);
            cursor: pointer;
        }

        .edit-link:hover,
        .delete-button:hover {
            transform: translateY(-2px);
        }

        .delete-form {
            display: inline-flex;
        }

        .empty-state {
            padding: 34px;
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

        @media (max-width: 980px) {
            .hero {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 860px) {
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
                padding: 18px;
            }

            .topbar {
                padding: 16px 18px;
                border-radius: 24px;
                flex-direction: column;
                align-items: flex-start;
            }

            .hero-card,
            .summary-card {
                padding: 22px;
                border-radius: 28px;
            }

            .hero-actions,
            .card-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .primary-link,
            .secondary-link,
            .edit-link,
            .delete-button {
                width: 100%;
            }

            .detail-grid,
            .summary-grid {
                grid-template-columns: 1fr;
            }

            .schedule-head {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
    <x-minimal-ui />
</head>
<body>
    <div class="page">
        <div class="shell">
            <section class="topbar">
                <div class="brand">
                    <div class="brand-mark" aria-hidden="true"></div>
                    <div class="brand-copy">
                        <strong>Professor Tracking System</strong>
                        <span>Weekly teaching schedule manager</span>
                    </div>
                </div>

                <div class="status-pill">Sorted by Day and Time</div>
            </section>

            <section class="hero">
                <section class="hero-card">
                    <div class="hero-inner">
                        <div class="eyebrow">Weekly Schedule</div>
                        <h1>My Weekly Schedule</h1>
                        <p>
                            Review your classes in a cleaner weekly view and manage new entries from one focused page.
                        </p>

                        <x-flash-toast />

                        <div class="hero-actions">
                            <a href="{{ route('schedules.create') }}" class="primary-link">Add Schedule</a>
                            <a href="{{ route('professor.dashboard') }}" class="secondary-link">Back to Professor Dashboard</a>
                        </div>
                    </div>
                </section>

                <aside class="summary-card">
                    <div class="summary-inner">
                        <div class="summary-tag">Weekly Overview</div>
                        <h2>Built for a clean class flow.</h2>
                        <p>
                            Your weekly list is organized for faster scanning, with each schedule card showing day,
                            time, room, semester, and school year clearly.
                        </p>

                        <div class="summary-grid">
                            <div class="summary-item">
                                <span>Entries</span>
                                <strong>{{ $schedules->count() }} schedule{{ $schedules->count() === 1 ? '' : 's' }}</strong>
                            </div>

                            <div class="summary-item">
                                <span>Order</span>
                                <strong>Day first, then start time</strong>
                            </div>
                        </div>
                    </div>
                </aside>
            </section>

            <section class="results-head" aria-label="Schedule summary">
                <div class="results-copy">
                    <span>Schedule List</span>
                    <h2>All Weekly Classes</h2>
                </div>

                <div class="results-meta">
                    <div class="results-pill">{{ $schedules->count() }} total entries</div>
                    <div class="results-pill">Weekly teaching plan</div>
                </div>
            </section>

            <section class="schedule-grid">
                @forelse($schedules as $schedule)
                    <article class="schedule-card">
                        <div class="schedule-head">
                            <div class="subject-block">
                                <h3>{{ $schedule->subject }}</h3>
                                <p>{{ $schedule->room }}</p>
                            </div>

                            <div class="day-badge">{{ $schedule->day_of_week }}</div>
                        </div>

                        <div class="time-banner">
                            <span>Time</span>
                            <strong>{{ $schedule->start_time }} - {{ $schedule->end_time }}</strong>
                        </div>

                        <div class="detail-grid">
                            <div class="detail-card">
                                <span>Room</span>
                                <strong>{{ $schedule->room }}</strong>
                            </div>

                            <div class="detail-card">
                                <span>Semester</span>
                                <strong>{{ $schedule->semester }}</strong>
                            </div>

                            <div class="detail-card">
                                <span>School Year</span>
                                <strong>{{ $schedule->school_year }}</strong>
                            </div>

                            <div class="detail-card">
                                <span>Day</span>
                                <strong>{{ $schedule->day_of_week }}</strong>
                            </div>
                        </div>

                        <div class="card-actions">
                            <a href="{{ route('schedules.edit', $schedule) }}" class="edit-link">Edit</a>

                            <form method="POST" action="{{ route('schedules.destroy.post', $schedule) }}" class="delete-form">
                                @csrf
                                <button type="submit" class="delete-button" onclick="return confirm('Delete this schedule?')">Delete</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <section class="empty-state">
                        <h2>No schedules added yet.</h2>
                        <p>Add your first weekly class to start building your teaching schedule.</p>
                    </section>
                @endforelse
            </section>
        </div>
    </div>
</body>
</html>
