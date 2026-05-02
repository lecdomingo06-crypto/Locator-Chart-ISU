@php
    $statusClasses = [
        'Available' => 'is-available',
        'In Class' => 'is-engaged',
        'On Meeting' => 'is-meeting',
        'On Leave' => 'is-away',
        'Emergency' => 'is-alert',
    ];

    $availableCount = $snapshotTotals['available'] ?? 0;
    $engagedCount = $snapshotTotals['engaged'] ?? 0;
    $attentionCount = $snapshotTotals['attention'] ?? 0;
    $activeContexts = $snapshotTotals['active_contexts'] ?? 0;
    $availableTeacherCount = $availableTeacherSnapshot->count();
    $shouldAnimateAvailableTeachers = $availableTeacherCount > 3;
    $welcomeJourneys = [
        [
            'icon' => 'pulse',
            'kicker' => 'Live visibility',
            'tab' => 'Status first',
            'title' => 'See availability before walking.',
            'summary' => 'Check availability, class time, meetings, and away states in one glance.',
            'glance' => [
                ['label' => 'Best use', 'value' => 'Quick checks'],
                ['label' => 'Helps with', 'value' => 'Timing'],
                ['label' => 'Main gain', 'value' => 'Less searching'],
            ],
            'cards' => [
                [
                    'icon' => 'eye',
                    'label' => 'Live check',
                    'title' => 'Instant status',
                    'copy' => 'Read staff state in seconds.',
                ],
                [
                    'icon' => 'users',
                    'label' => 'Front desk',
                    'title' => 'Faster answers',
                    'copy' => 'Guide students without hallway searching.',
                ],
            ],
            'points' => [
                'Quick status read',
                'Better timing',
                'Less hallway searching',
            ],
            'accent' => '#59ec98',
        ],
        [
            'icon' => 'pin',
            'kicker' => 'Room awareness',
            'tab' => 'Find the place',
            'title' => 'Find the room right away.',
            'summary' => 'Room and department details make the next step clearer.',
            'glance' => [
                ['label' => 'Best use', 'value' => 'Wayfinding'],
                ['label' => 'Helps with', 'value' => 'Routing'],
                ['label' => 'Main gain', 'value' => 'Clear stops'],
            ],
            'cards' => [
                [
                    'icon' => 'pin',
                    'label' => 'Location help',
                    'title' => 'Find the room',
                    'copy' => 'Keep office and department details clearer.',
                ],
                [
                    'icon' => 'route',
                    'label' => 'Best for',
                    'title' => 'Guide visitors',
                    'copy' => 'Point people to the right stop sooner.',
                ],
            ],
            'points' => [
                'Fewer wrong-room visits',
                'Simpler routing',
                'Clearer office guidance',
            ],
            'accent' => '#8fd8ff',
        ],
        [
            'icon' => 'spark',
            'kicker' => 'Student support',
            'tab' => 'Guide faster',
            'title' => 'Guide students with confidence.',
            'summary' => 'Status plus location helps support teams answer faster.',
            'glance' => [
                ['label' => 'Best use', 'value' => 'Front desks'],
                ['label' => 'Helps with', 'value' => 'Responses'],
                ['label' => 'Main gain', 'value' => 'Clear direction'],
            ],
            'cards' => [
                [
                    'icon' => 'spark',
                    'label' => 'Support flow',
                    'title' => 'Cut repeats',
                    'copy' => 'Reduce repeated desk-to-desk questions.',
                ],
                [
                    'icon' => 'chat',
                    'label' => 'Outcome',
                    'title' => 'Clear next step',
                    'copy' => 'Students get quicker direction on first contact.',
                ],
            ],
            'points' => [
                'Faster front desks',
                'Shorter waiting time',
                'Better-directed visits',
            ],
            'accent' => '#ffd36a',
        ],
        [
            'icon' => 'clock',
            'kicker' => 'Daily tracking',
            'tab' => 'Follow movement',
            'title' => 'Follow daily movement easily.',
            'summary' => 'A cleaner staff view keeps offices aligned all day.',
            'glance' => [
                ['label' => 'Best use', 'value' => 'Monitoring'],
                ['label' => 'Helps with', 'value' => 'Coordination'],
                ['label' => 'Main gain', 'value' => 'Consistency'],
            ],
            'cards' => [
                [
                    'icon' => 'clock',
                    'label' => 'Daily use',
                    'title' => 'Track changes',
                    'copy' => 'Notice status shifts sooner.',
                ],
                [
                    'icon' => 'grid',
                    'label' => 'Campus view',
                    'title' => 'Stay aligned',
                    'copy' => 'Keep teams on the same visibility view.',
                ],
            ],
            'points' => [
                'Smoother coordination',
                'Easier staff monitoring',
                'More consistent visibility',
            ],
            'accent' => '#cbadff',
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Teacher Tracker') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800" rel="stylesheet" />

        <style>
            :root {
                color-scheme: light;
                --page-top: #f5faf6;
                --page-bottom: #e0efe4;
                --surface: rgba(255, 255, 255, 0.68);
                --surface-strong: rgba(255, 255, 255, 0.84);
                --surface-dark: rgba(12, 63, 41, 0.9);
                --line: rgba(19, 45, 31, 0.08);
                --line-strong: rgba(255, 255, 255, 0.28);
                --ink: #102219;
                --muted: #627067;
                --green-700: #19975d;
                --green-800: #127c4b;
                --green-900: #0e5f3b;
                --mint: #dbf1e2;
                --mint-strong: #c8ead4;
                --ice: #edf7f0;
                --aqua: #d8f0e7;
                --gold: #f4dfb0;
                --shadow: 0 28px 70px rgba(17, 43, 31, 0.12);
                --shadow-soft: 0 18px 40px rgba(17, 43, 31, 0.08);
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
                position: relative;
                font-family: 'Outfit', sans-serif;
                color: var(--ink);
                background:
                    linear-gradient(180deg, rgba(25, 151, 93, 0.08), transparent 34%),
                    linear-gradient(145deg, var(--page-top), var(--page-bottom));
            }

            body::before,
            body::after {
                content: '';
                position: fixed;
                inset: 0;
                pointer-events: none;
                z-index: 0;
            }

            body::before {
                inset: -8% -10% auto -10%;
                height: 44vh;
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.14) 58%, transparent 82%);
                transform: skewY(-6deg);
            }

            body::after {
                inset: auto -8% -10% -8%;
                height: 34vh;
                background: linear-gradient(180deg, rgba(16, 84, 53, 0.03), rgba(16, 84, 53, 0.12));
                transform: skewY(-4deg);
            }

            img {
                display: block;
                max-width: 100%;
            }

            a {
                color: inherit;
            }

            .page {
                position: relative;
                z-index: 1;
                padding: clamp(14px, 2vw, 28px);
            }

            .app-shell {
                width: min(1380px, 100%);
                margin: 0 auto;
                display: grid;
                gap: clamp(18px, 2vw, 24px);
            }

            .topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                padding: 16px 18px;
                border-radius: 8px;
                border: 1px solid rgba(255, 255, 255, 0.72);
                background: rgba(255, 255, 255, 0.62);
                box-shadow: var(--shadow-soft);
                backdrop-filter: blur(24px);
            }

            .brand {
                display: inline-flex;
                align-items: center;
                gap: 14px;
                min-width: 0;
            }

            .brand-mark {
                position: relative;
                flex: 0 0 auto;
                width: 48px;
                height: 48px;
                border-radius: 8px;
                background: linear-gradient(145deg, #27b16b, #0e5f3b);
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.35);
            }

            .brand-mark::before,
            .brand-mark::after {
                content: '';
                position: absolute;
                background: rgba(255, 255, 255, 0.98);
            }

            .brand-mark::before {
                top: 11px;
                left: 9px;
                width: 12px;
                height: 12px;
                border-radius: 999px;
                box-shadow: 14px 0 0 rgba(255, 255, 255, 0.98);
            }

            .brand-mark::after {
                left: 10px;
                right: 10px;
                bottom: 10px;
                height: 11px;
                border-radius: 999px 999px 8px 8px;
            }

            .brand-copy {
                min-width: 0;
            }

            .brand-copy strong,
            .brand-copy span {
                display: block;
                min-width: 0;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .brand-copy strong {
                font-size: 1.08rem;
                letter-spacing: -0.03em;
            }

            .brand-copy span {
                margin-top: 3px;
                color: var(--muted);
                font-size: 0.92rem;
            }

            .status-pill {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 10px 14px;
                border-radius: 999px;
                border: 1px solid rgba(25, 151, 93, 0.14);
                background: rgba(219, 241, 226, 0.74);
                color: var(--green-900);
                font-size: 0.82rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                white-space: nowrap;
            }

            .status-pill::before {
                content: '';
                width: 9px;
                height: 9px;
                border-radius: 999px;
                background: #2dda78;
                box-shadow: 0 0 0 5px rgba(45, 218, 120, 0.16);
            }

            .hero {
                position: relative;
                overflow: hidden;
                display: grid;
                grid-template-columns: minmax(0, 1.05fr) minmax(340px, 0.95fr);
                gap: clamp(20px, 2.2vw, 28px);
                align-items: center;
                padding: clamp(22px, 3vw, 30px);
                border-radius: 8px;
                border: 1px solid rgba(255, 255, 255, 0.72);
                background:
                    linear-gradient(155deg, rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.28)),
                    linear-gradient(180deg, rgba(219, 241, 226, 0.72), rgba(255, 255, 255, 0.38));
                box-shadow: var(--shadow);
                backdrop-filter: blur(24px);
            }

            .hero::before {
                content: '';
                position: absolute;
                inset: 0;
                background:
                    linear-gradient(118deg, rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.12) 46%, transparent 72%),
                    linear-gradient(90deg, rgba(18, 124, 75, 0.04) 1px, transparent 1px);
                background-size: auto, 160px 100%;
                opacity: 0.9;
                pointer-events: none;
            }

            .hero-copy,
            .hero-preview {
                position: relative;
                z-index: 1;
            }

            .hero-copy {
                display: grid;
                gap: 16px;
                min-width: 0;
            }

            .eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 12px;
                width: fit-content;
                padding: 8px 14px;
                border-radius: 999px;
                border: 1px solid rgba(255, 255, 255, 0.72);
                background: rgba(255, 255, 255, 0.6);
                color: var(--green-900);
                font-size: 0.76rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .eyebrow-icon {
                width: 28px;
                height: 28px;
                display: inline-grid;
                place-items: center;
                border-radius: 8px;
                background: rgba(20, 130, 82, 0.12);
            }

            .eyebrow-icon svg {
                width: 17px;
                height: 17px;
                stroke: currentColor;
                fill: none;
                stroke-width: 1.8;
                stroke-linecap: round;
                stroke-linejoin: round;
            }

            .hero h1 {
                margin: 0;
                max-width: 11ch;
                font-size: clamp(2.7rem, 5vw, 5.2rem);
                line-height: 0.94;
                letter-spacing: -0.06em;
            }

            .hero h1 .accent {
                color: var(--green-800);
            }

            .hero p {
                max-width: 54ch;
                margin: 0;
                color: var(--muted);
                font-size: clamp(0.98rem, 1.08vw, 1.1rem);
                line-height: 1.58;
            }

            .action-row {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 14px;
                padding-top: 4px;
            }

            .primary-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 48px;
                padding: 0 20px;
                border-radius: 8px;
                background: linear-gradient(135deg, #22bf70, #0f6f44);
                color: #ffffff;
                text-decoration: none;
                font-size: 0.95rem;
                font-weight: 700;
                box-shadow: 0 18px 34px rgba(15, 111, 68, 0.24);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .primary-button:hover {
                transform: translateY(-1px);
                box-shadow: 0 22px 38px rgba(15, 111, 68, 0.28);
            }

            .primary-button:focus-visible {
                outline: 3px solid rgba(33, 191, 112, 0.24);
                outline-offset: 3px;
            }

            .action-note {
                color: var(--green-900);
                font-size: 0.92rem;
                font-weight: 600;
            }

            body.is-login-modal-open {
                overflow: hidden;
            }

            .login-modal {
                position: fixed;
                inset: 0;
                z-index: 20;
                display: grid;
                place-items: center;
                padding: 24px;
                background: rgba(7, 25, 17, 0.38);
                backdrop-filter: blur(14px);
            }

            .login-modal[hidden] {
                display: none;
            }

            .login-modal-card {
                position: relative;
                isolation: isolate;
                width: min(470px, 100%);
                padding: 20px;
                border-radius: 8px;
                border: 1px solid rgba(255, 255, 255, 0.78);
                background:
                    linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(247, 252, 249, 0.88)),
                    rgba(255, 255, 255, 0.92);
                box-shadow: 0 34px 90px rgba(10, 39, 27, 0.28);
            }

            .login-modal-close {
                position: absolute;
                top: 16px;
                right: 16px;
                z-index: 3;
                width: 38px;
                height: 38px;
                display: inline-grid;
                place-items: center;
                border: 0;
                border-radius: 8px;
                background: rgba(16, 34, 25, 0.06);
                color: #688173;
                cursor: pointer;
                transition: background 0.2s ease, color 0.2s ease;
            }

            .login-modal-close:hover {
                background: rgba(16, 34, 25, 0.1);
                color: var(--ink);
            }

            .login-modal-close:focus-visible {
                outline: 3px solid rgba(33, 191, 112, 0.24);
                outline-offset: 3px;
            }

            .login-modal-body {
                position: relative;
                z-index: 1;
                display: grid;
                gap: 22px;
                padding: 12px;
                padding-top: 28px;
            }

            .login-modal-head {
                display: grid;
                gap: 14px;
                justify-items: start;
            }

            .login-campus-pill {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                width: fit-content;
                padding: 9px 15px;
                border-radius: 999px;
                border: 1px solid rgba(255, 255, 255, 0.72);
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.84), rgba(245, 252, 248, 0.76));
                color: var(--green-900);
                font-size: 0.76rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.82);
            }

            .login-campus-icon {
                width: 24px;
                height: 24px;
                display: inline-grid;
                place-items: center;
                border-radius: 8px;
                background: rgba(20, 114, 71, 0.12);
            }

            .login-campus-icon svg {
                width: 15px;
                height: 15px;
                stroke: currentColor;
                fill: none;
                stroke-width: 1.8;
                stroke-linecap: round;
                stroke-linejoin: round;
            }

            .login-modal-title {
                margin: 0;
                max-width: 12ch;
                font-size: clamp(2rem, 5vw, 3rem);
                line-height: 0.95;
                letter-spacing: -0.05em;
            }

            .login-modal-copy {
                margin: 8px 0 0;
                max-width: 40ch;
                color: var(--muted);
                font-size: 0.98rem;
                line-height: 1.65;
            }

            .login-status {
                padding: 12px 14px;
                border-radius: 8px;
                background: rgba(217, 242, 226, 0.82);
                border: 1px solid rgba(25, 138, 82, 0.14);
                color: var(--green-900);
                font-size: 0.92rem;
                font-weight: 600;
            }

            .login-modal-form {
                display: grid;
                gap: 16px;
            }

            .login-field-group {
                display: grid;
                gap: 10px;
            }

            .login-label {
                color: #214734;
                font-size: 0.92rem;
                font-weight: 700;
            }

            .login-field {
                width: 100%;
                min-height: 50px;
                padding: 0 16px;
                border-radius: 8px;
                border: 1px solid rgba(18, 53, 36, 0.12);
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(249, 253, 250, 0.92));
                color: var(--ink);
                font: inherit;
                box-shadow:
                    inset 0 1px 0 rgba(255, 255, 255, 0.82),
                    0 10px 24px rgba(13, 72, 43, 0.05);
                transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            }

            .login-field::placeholder {
                color: #8ca192;
            }

            .login-field:focus {
                outline: none;
                border-color: rgba(20, 114, 71, 0.65);
                box-shadow: 0 0 0 4px rgba(20, 114, 71, 0.12);
                background: rgba(255, 255, 255, 0.98);
            }

            .login-error {
                color: #b63b52;
                font-size: 0.84rem;
                font-weight: 600;
            }

            .login-check-row {
                display: flex;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
            }

            .login-check-input {
                width: 16px;
                height: 16px;
                border-radius: 4px;
                accent-color: var(--green-800);
            }

            .login-check-label {
                color: var(--muted);
                font-size: 0.92rem;
                font-weight: 600;
            }

            .login-submit {
                width: 100%;
                min-height: 50px;
                border: 0;
                border-radius: 8px;
                background: linear-gradient(135deg, #22bf70, #0f6f44);
                color: #ffffff;
                font: inherit;
                font-size: 0.96rem;
                font-weight: 700;
                cursor: pointer;
                box-shadow: 0 18px 34px rgba(15, 111, 68, 0.22);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .login-submit:hover {
                transform: translateY(-1px);
                box-shadow: 0 22px 38px rgba(15, 111, 68, 0.28);
            }

            .login-submit:focus-visible {
                outline: 3px solid rgba(33, 191, 112, 0.24);
                outline-offset: 3px;
            }

            .login-support {
                display: grid;
                gap: 4px;
                padding-top: 4px;
                color: var(--muted);
                font-size: 0.92rem;
                line-height: 1.5;
            }

            .login-support strong {
                color: var(--ink);
                font-size: 0.94rem;
            }

            .hero-preview {
                min-width: 0;
            }

            .preview-shell {
                padding: 12px;
                border-radius: 8px;
                border: 1px solid rgba(255, 255, 255, 0.76);
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.54), rgba(255, 255, 255, 0.26));
                box-shadow: var(--shadow-soft);
                backdrop-filter: blur(20px);
            }

            .preview-surface {
                display: grid;
                gap: 14px;
                padding: 18px;
                border-radius: 8px;
                border: 1px solid rgba(255, 255, 255, 0.12);
                background: linear-gradient(180deg, rgba(12, 78, 49, 0.96), rgba(11, 67, 42, 0.94));
                color: #f7fcf8;
            }

            .preview-head {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 14px;
            }

            .preview-kicker {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 10px;
                color: rgba(247, 252, 248, 0.82);
                font-size: 0.72rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .preview-kicker::before {
                content: '';
                width: 8px;
                height: 8px;
                border-radius: 999px;
                background: #2ada78;
            }

            .preview-head strong {
                display: block;
                max-width: 16ch;
                font-size: 1.26rem;
                line-height: 1.03;
                letter-spacing: -0.04em;
            }

            .preview-head span {
                display: block;
                margin-top: 8px;
                color: rgba(247, 252, 248, 0.72);
                font-size: 0.88rem;
                line-height: 1.48;
            }

            .preview-orb {
                flex: 0 0 auto;
                width: 56px;
                height: 56px;
                border-radius: 50%;
                background:
                    radial-gradient(circle at center, rgba(77, 231, 141, 0.94) 0 28%, rgba(77, 231, 141, 0.24) 29% 52%, rgba(255, 255, 255, 0.08) 53% 100%);
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.22);
            }

            .preview-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .preview-card {
                min-width: 0;
                padding: 13px 14px;
                border-radius: 8px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                background: rgba(255, 255, 255, 0.08);
            }

            .preview-card span,
            .preview-card strong {
                display: block;
                min-width: 0;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .preview-card span {
                color: rgba(247, 252, 248, 0.64);
                font-size: 0.7rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .preview-card strong {
                margin-top: 8px;
                font-size: 1.22rem;
                letter-spacing: -0.04em;
            }

            .preview-list {
                display: grid;
                gap: 10px;
            }

            .preview-marquee {
                --preview-loop-gap: 10px;
                position: relative;
                overflow: hidden;
                height: 252px;
            }

            .preview-track {
                display: flex;
                flex-direction: column;
                gap: var(--preview-loop-gap);
                will-change: transform;
                animation: preview-scroll 22s linear infinite;
            }

            .preview-track.is-static {
                animation: none;
            }

            .preview-marquee:hover .preview-track {
                animation-play-state: paused;
            }

            .preview-group {
                display: grid;
                gap: 10px;
            }

            .preview-row {
                display: grid;
                grid-template-columns: minmax(0, 1fr) auto;
                gap: 10px;
                align-items: center;
                padding: 12px 13px;
                border-radius: 8px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                background: rgba(255, 255, 255, 0.08);
            }

            .preview-main {
                display: flex;
                align-items: center;
                gap: 10px;
                min-width: 0;
            }

            .preview-avatar {
                flex: 0 0 auto;
                width: 38px;
                height: 38px;
                display: grid;
                place-items: center;
                overflow: hidden;
                border-radius: 8px;
                background: linear-gradient(145deg, #f1fff5, #ccefd8);
                color: var(--green-900);
                font-size: 0.78rem;
                font-weight: 800;
            }

            .preview-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .preview-copy {
                min-width: 0;
            }

            .preview-copy strong,
            .preview-copy span {
                display: block;
                min-width: 0;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .preview-copy strong {
                font-size: 0.92rem;
                letter-spacing: -0.02em;
            }

            .preview-copy span {
                margin-top: 4px;
                color: rgba(247, 252, 248, 0.68);
                font-size: 0.79rem;
            }

            .preview-side {
                display: grid;
                gap: 6px;
                justify-items: end;
                text-align: right;
            }

            .preview-context {
                color: rgba(247, 252, 248, 0.68);
                font-size: 0.76rem;
                font-weight: 600;
                white-space: nowrap;
            }

            .preview-empty {
                padding: 14px;
                border-radius: 8px;
                border: 1px dashed rgba(255, 255, 255, 0.18);
                background: rgba(255, 255, 255, 0.04);
                color: rgba(247, 252, 248, 0.76);
                font-size: 0.88rem;
                line-height: 1.5;
            }

            @keyframes preview-scroll {
                from {
                    transform: translateY(0);
                }

                to {
                    transform: translateY(calc(-50% - (var(--preview-loop-gap) / 2)));
                }
            }

            .bottom-section {
                display: block;
            }

            .insight-panel {
                border-radius: 8px;
                box-shadow: var(--shadow-soft);
                overflow: hidden;
            }

            .status-chip {
                display: inline-flex;
                align-items: center;
                min-height: 30px;
                padding: 0 10px;
                border-radius: 999px;
                border: 1px solid rgba(255, 255, 255, 0.12);
                background: rgba(16, 34, 25, 0.08);
                color: var(--ink);
                font-size: 0.7rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .is-available {
                background: linear-gradient(135deg, #f0fff5, #d7f7e2);
                border-color: rgba(255, 255, 255, 0.24);
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
                color: #0d5c38;
            }

            .is-engaged {
                background: rgba(110, 185, 154, 0.18);
                color: #195c49;
            }

            .is-meeting {
                background: rgba(166, 197, 182, 0.22);
                color: #315b4a;
            }

            .is-away {
                background: rgba(239, 107, 134, 0.14);
                color: #93253c;
            }

            .is-alert {
                background: rgba(244, 223, 176, 0.44);
                color: #76571b;
            }

            .insight-panel {
                display: grid;
                grid-template-columns: minmax(320px, 0.88fr) minmax(0, 1.12fr);
                align-items: stretch;
                border: 1px solid rgba(255, 255, 255, 0.28);
                background: linear-gradient(180deg, rgba(15, 93, 58, 0.96), rgba(11, 66, 41, 0.94));
                color: #f7fcf8;
            }

            .insight-media {
                display: grid;
                align-content: start;
                gap: 18px;
                padding: 20px;
                border-right: 1px solid rgba(255, 255, 255, 0.1);
            }

            .guide-shell {
                display: grid;
                gap: 14px;
                min-height: 100%;
                padding: 16px;
                border-radius: 8px;
                border: 1px solid rgba(255, 255, 255, 0.14);
                background:
                    linear-gradient(180deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.04)),
                    radial-gradient(circle at top left, rgba(255, 255, 255, 0.14), transparent 34%);
            }

            .guide-badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                width: fit-content;
                padding: 7px 11px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.14);
                font-size: 0.74rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .guide-badge::before {
                content: '';
                width: 8px;
                height: 8px;
                border-radius: 999px;
                background: #59ec98;
            }

            .guide-copy h3 {
                margin: 0;
                font-size: clamp(1.34rem, 2vw, 1.8rem);
                line-height: 1.04;
                letter-spacing: -0.04em;
            }

            .guide-copy p {
                margin: 10px 0 0;
                color: rgba(243, 250, 246, 0.78);
                font-size: 0.88rem;
                line-height: 1.5;
            }

            .guide-switcher {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .guide-option {
                position: relative;
                overflow: hidden;
                width: 100%;
                display: grid;
                gap: 8px;
                padding: 12px 14px;
                border-radius: 8px;
                border: 1px solid rgba(255, 255, 255, 0.12);
                background: rgba(255, 255, 255, 0.08);
                color: inherit;
                text-align: left;
                cursor: pointer;
                transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
            }

            .guide-option::after {
                content: '';
                position: absolute;
                inset: auto 14px 0;
                height: 2px;
                border-radius: 999px;
                background: var(--story-accent, #59ec98);
                transform: scaleX(0);
                transform-origin: center;
                transition: transform 0.22s ease;
                opacity: 0.9;
            }

            .guide-option:hover {
                transform: translateY(-2px) scale(1.01);
                border-color: rgba(255, 255, 255, 0.2);
                background: rgba(255, 255, 255, 0.11);
            }

            .guide-option:focus-visible {
                outline: 3px solid rgba(89, 236, 152, 0.26);
                outline-offset: 3px;
            }

            .guide-option.is-active {
                border-color: var(--story-accent, #59ec98);
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.14), rgba(255, 255, 255, 0.08));
                box-shadow: 0 14px 32px rgba(6, 27, 17, 0.18);
            }

            .guide-option:hover::after,
            .guide-option.is-active::after {
                transform: scaleX(1);
            }

            .guide-option.is-active .guide-option-icon {
                background: var(--story-accent, #59ec98);
                color: #143223;
                transform: scale(1.06);
            }

            .guide-option-head {
                display: grid;
                justify-items: center;
                gap: 8px;
            }

            .guide-option-icon {
                width: 36px;
                height: 36px;
                display: inline-grid;
                place-items: center;
                flex: none;
                border-radius: 10px;
                background: rgba(255, 255, 255, 0.16);
                color: var(--story-accent, #59ec98);
                transition: transform 0.22s ease, background 0.22s ease, color 0.22s ease;
            }

            .guide-option-icon svg {
                width: 18px;
                height: 18px;
                display: block;
                stroke: currentColor;
                fill: none;
                stroke-width: 1.8;
                stroke-linecap: round;
                stroke-linejoin: round;
            }

            .guide-option-label {
                color: rgba(243, 250, 246, 0.74);
                font-size: 0.72rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                text-align: center;
            }

            .guide-option-copy {
                text-align: center;
            }

            .guide-option-copy strong {
                display: block;
                font-size: 0.92rem;
                letter-spacing: -0.02em;
                line-height: 1.35;
            }

            .guide-note {
                display: flex;
                align-items: center;
                gap: 10px;
                color: rgba(243, 250, 246, 0.7);
                font-size: 0.8rem;
                line-height: 1.5;
            }

            .guide-note::before {
                content: '';
                width: 8px;
                height: 8px;
                border-radius: 999px;
                background: #59ec98;
                box-shadow: 0 0 0 6px rgba(89, 236, 152, 0.12);
            }

            .insight-stage {
                display: grid;
                min-height: 100%;
            }

            .story-panel {
                display: none;
                gap: 14px;
            }

            .story-panel.is-active {
                display: grid;
                align-content: start;
                min-height: 100%;
                animation: storyPanelReveal 0.35s ease;
            }

            .story-kicker {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                width: fit-content;
                padding: 6px 10px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.12);
                color: rgba(243, 250, 246, 0.88);
                font-size: 0.72rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .story-kicker-icon {
                width: 20px;
                height: 20px;
                display: inline-grid;
                place-items: center;
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.1);
                color: var(--story-accent, #59ec98);
            }

            .story-kicker-icon svg {
                width: 13px;
                height: 13px;
                display: block;
                stroke: currentColor;
                fill: none;
                stroke-width: 1.9;
                stroke-linecap: round;
                stroke-linejoin: round;
            }

            .insight-body {
                display: grid;
                grid-template-rows: minmax(0, 1fr) auto;
                gap: 14px;
                align-content: stretch;
                padding: 20px;
            }

            .insight-copy h2 {
                margin: 0;
                font-size: clamp(1.22rem, 1.7vw, 1.56rem);
                line-height: 1.08;
                letter-spacing: -0.04em;
            }

            .insight-copy p {
                margin: 8px 0 0;
                color: rgba(243, 250, 246, 0.78);
                font-size: 0.88rem;
                line-height: 1.5;
            }

            .story-card-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .signal-card {
                position: relative;
                overflow: hidden;
                display: grid;
                justify-items: center;
                gap: 10px;
                align-items: start;
                min-width: 0;
                padding: 12px;
                border-radius: 8px;
                border: 1px solid rgba(255, 255, 255, 0.12);
                background: rgba(255, 255, 255, 0.08);
                transition: transform 0.22s ease, border-color 0.22s ease, background 0.22s ease;
            }

            .signal-card::after {
                content: '';
                position: absolute;
                inset: auto 0 0;
                height: 2px;
                background: var(--story-accent, #59ec98);
                opacity: 0;
                transition: opacity 0.22s ease;
            }

            .signal-card:hover {
                transform: translateY(-2px);
                border-color: rgba(255, 255, 255, 0.18);
                background: rgba(255, 255, 255, 0.1);
            }

            .signal-card:hover::after {
                opacity: 1;
            }

            .signal-card-icon {
                width: 38px;
                height: 38px;
                display: inline-grid;
                place-items: center;
                flex: none;
                border-radius: 12px;
                background: rgba(255, 255, 255, 0.12);
                color: var(--story-accent, #59ec98);
            }

            .signal-card-icon svg {
                width: 18px;
                height: 18px;
                display: block;
                stroke: currentColor;
                fill: none;
                stroke-width: 1.8;
                stroke-linecap: round;
                stroke-linejoin: round;
            }

            .signal-card-copy {
                text-align: center;
            }

            .signal-card-copy span,
            .signal-card-copy strong,
            .signal-card-copy p {
                display: block;
                min-width: 0;
            }

            .signal-card-copy span {
                color: rgba(243, 250, 246, 0.64);
                font-size: 0.72rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .signal-card-copy strong {
                margin-top: 4px;
                font-size: 0.98rem;
                letter-spacing: -0.03em;
            }

            .signal-card-copy p {
                margin: 4px 0 0;
                color: rgba(243, 250, 246, 0.76);
                font-size: 0.82rem;
                line-height: 1.45;
            }

            .story-points {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }

            .story-point {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                min-height: 36px;
                padding: 0 12px;
                border-radius: 999px;
                border: 1px solid rgba(255, 255, 255, 0.12);
                background: rgba(255, 255, 255, 0.08);
                color: rgba(243, 250, 246, 0.86);
                font-size: 0.82rem;
                font-weight: 700;
            }

            .story-point svg {
                width: 14px;
                height: 14px;
                stroke: var(--story-accent, #59ec98);
                fill: none;
                stroke-width: 2;
                stroke-linecap: round;
                stroke-linejoin: round;
            }

            .story-glance {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 10px;
                margin-top: auto;
                padding-top: 6px;
            }

            .story-glance-card {
                display: grid;
                gap: 5px;
                padding: 12px;
                border-radius: 8px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                background: rgba(255, 255, 255, 0.06);
            }

            .story-glance-card span {
                color: rgba(243, 250, 246, 0.62);
                font-size: 0.7rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .story-glance-card strong {
                font-size: 0.94rem;
                letter-spacing: -0.02em;
            }

            .story-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                flex-wrap: wrap;
            }

            .story-pager {
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }

            .story-dot {
                width: 10px;
                height: 10px;
                padding: 0;
                border: 0;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.22);
                cursor: pointer;
                transition: transform 0.2s ease, background 0.2s ease;
            }

            .story-dot.is-active {
                transform: scale(1.14);
                background: var(--story-accent, #59ec98);
            }

            .story-dot:focus-visible {
                outline: 3px solid rgba(89, 236, 152, 0.22);
                outline-offset: 3px;
            }

            .story-hint {
                color: rgba(243, 250, 246, 0.68);
                font-size: 0.8rem;
                font-weight: 600;
            }

            .story-progress {
                width: min(220px, 100%);
                height: 6px;
                overflow: hidden;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.08);
            }

            .story-progress-bar {
                width: 100%;
                height: 100%;
                border-radius: inherit;
                background: linear-gradient(90deg, var(--story-accent, #59ec98), rgba(255, 255, 255, 0.86));
                transform-origin: left center;
                animation: storyProgress 5.2s linear infinite;
            }

            @keyframes storyPanelReveal {
                from {
                    opacity: 0;
                    transform: translateY(8px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes storyProgress {
                from {
                    transform: scaleX(0);
                }

                to {
                    transform: scaleX(1);
                }
            }

            @media (max-width: 1120px) {
                .hero,
                .insight-panel {
                    grid-template-columns: 1fr;
                }

                .insight-media {
                    border-right: 0;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                }
            }

            @media (max-width: 900px) {
                .preview-grid,
                .story-card-grid,
                .story-glance {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (max-width: 720px) {
                .topbar {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .status-pill {
                    align-self: flex-start;
                }

                .hero {
                    padding: 20px;
                }

                .hero h1 {
                    max-width: none;
                }

                .login-modal {
                    padding: 16px;
                }

                .login-modal-card {
                    padding: 16px;
                }
            }

            @media (max-width: 560px) {
                .page {
                    padding: 12px;
                }

                .preview-grid,
                .story-card-grid,
                .story-glance {
                    grid-template-columns: 1fr;
                }

                .guide-switcher {
                    grid-template-columns: 1fr;
                }

                .brand-copy strong,
                .brand-copy span {
                    white-space: normal;
                }

                .action-row {
                    align-items: stretch;
                }

                .primary-button {
                    width: 100%;
                }

                .preview-row {
                    grid-template-columns: 1fr;
                }

                .preview-side {
                    justify-items: start;
                    text-align: left;
                }

                .preview-context {
                    white-space: normal;
                }

                .login-modal-body {
                    padding: 6px;
                }
            }

            @media (prefers-reduced-motion: reduce) {
                .preview-track {
                    animation: none !important;
                }

                .story-progress-bar {
                    animation: none !important;
                    transform: scaleX(1);
                }
            }
        </style>
    </head>
    <body>
        <div class="page">
            <div class="app-shell">
                <header class="topbar">
                    <div class="brand">
                        <div class="brand-mark" aria-hidden="true"></div>

                        <div class="brand-copy">
                            <strong>Teacher Tracking System</strong>
                            <span>Live faculty visibility for classrooms, offices, and student support.</span>
                        </div>
                    </div>

                    <div class="status-pill">Campus ready</div>
                </header>

                <section class="hero" aria-labelledby="welcome-title">
                    <div class="hero-copy">
                        <div class="eyebrow">
                            <span class="eyebrow-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path d="M4 10.5 12 6l8 4.5"></path>
                                    <path d="M6 10.5v7.5"></path>
                                    <path d="M10 10.5v7.5"></path>
                                    <path d="M14 10.5v7.5"></path>
                                    <path d="M18 10.5v7.5"></path>
                                    <path d="M4 18h16"></path>
                                </svg>
                            </span>
                            Isabela State University Cauayan Campus
                        </div>

                        <h1 id="welcome-title">
                            A smarter way to track teacher availability in real time.
                        </h1>

                        <p>
                            Check who is available, where they are, and which team can help next without hallway searching, paper logs, or front desk guesswork.
                        </p>

                        <div class="action-row">
                            @auth
                                <a href="{{ route('dashboard') }}" class="primary-button">Open dashboard</a>
                            @else
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}" class="primary-button" data-login-trigger>Log in</a>
                                @endif
                            @endauth

                            <span class="action-note">Trusted access for teachers, faculty, students, and admins.</span>
                        </div>

                    </div>

                    <div class="hero-preview" aria-label="Live database summary">
                        <div class="preview-shell">
                            <div class="preview-surface">
                                <div class="preview-head">
                                    <div>
                                        <div class="preview-kicker">Available now</div>
                                        <strong>{{ $availableTeacherCount }} {{ \Illuminate\Support\Str::plural('teacher', $availableTeacherCount) }} available right now.</strong>
                                        <span>Here are the teachers you can reach at the moment.</span>
                                    </div>

                                    <div class="preview-orb" aria-hidden="true"></div>
                                </div>

                                <div class="preview-grid">
                                    <div class="preview-card">
                                        <span>Tracked staff</span>
                                        <strong>{{ $snapshotTotals['tracked'] }}</strong>
                                    </div>

                                    <div class="preview-card">
                                        <span>Available now</span>
                                        <strong>{{ $availableCount }}</strong>
                                    </div>

                                    <div class="preview-card">
                                        <span>Active locations</span>
                                        <strong>{{ $activeContexts }}</strong>
                                    </div>

                                    <div class="preview-card">
                                        <span>Needs attention</span>
                                        <strong>{{ $attentionCount }}</strong>
                                    </div>
                                </div>

                                <div class="preview-list">
                                    @if ($availableTeacherCount > 0)
                                        <div class="preview-marquee" aria-label="Available teachers">
                                            <div class="preview-track {{ $shouldAnimateAvailableTeachers ? '' : 'is-static' }}">
                                                <div class="preview-group">
                                                    @foreach ($availableTeacherSnapshot as $staffMember)
                                                        <article class="preview-row">
                                                            <div class="preview-main">
                                                                <div class="preview-avatar">
                                                                    @if (!empty($staffMember['profile_picture_url']))
                                                                        <img src="{{ $staffMember['profile_picture_url'] }}" alt="{{ $staffMember['name'] }}">
                                                                    @else
                                                                        {{ $staffMember['initials'] }}
                                                                    @endif
                                                                </div>

                                                                <div class="preview-copy">
                                                                    <strong>{{ $staffMember['name'] }}</strong>
                                                                    <span>{{ $staffMember['meta'] ?: 'Teacher tracking account' }}</span>
                                                                </div>
                                                            </div>

                                                            <div class="preview-side">
                                                                <span class="status-chip {{ $statusClasses[$staffMember['status']] ?? '' }}">
                                                                    {{ $staffMember['status'] }}
                                                                </span>
                                                                <span class="preview-context">{{ $staffMember['context'] }}</span>
                                                            </div>
                                                        </article>
                                                    @endforeach
                                                </div>

                                                @if ($shouldAnimateAvailableTeachers)
                                                    <div class="preview-group" aria-hidden="true">
                                                        @foreach ($availableTeacherSnapshot as $staffMember)
                                                            <article class="preview-row">
                                                                <div class="preview-main">
                                                                    <div class="preview-avatar">
                                                                        @if (!empty($staffMember['profile_picture_url']))
                                                                            <img src="{{ $staffMember['profile_picture_url'] }}" alt="">
                                                                        @else
                                                                            {{ $staffMember['initials'] }}
                                                                        @endif
                                                                    </div>

                                                                    <div class="preview-copy">
                                                                        <strong>{{ $staffMember['name'] }}</strong>
                                                                        <span>{{ $staffMember['meta'] ?: 'Teacher tracking account' }}</span>
                                                                    </div>
                                                                </div>

                                                                <div class="preview-side">
                                                                    <span class="status-chip {{ $statusClasses[$staffMember['status']] ?? '' }}">
                                                                        {{ $staffMember['status'] }}
                                                                    </span>
                                                                    <span class="preview-context">{{ $staffMember['context'] }}</span>
                                                                </div>
                                                            </article>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="preview-empty">
                                            No teachers are currently marked available. When a teacher becomes available, they will appear here automatically.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="bottom-section">
                    <aside class="insight-panel" aria-label="Interactive system overview" data-story-explorer>
                        <div class="insight-media">
                            <div class="guide-shell">
                                <div class="guide-badge">System overview</div>

                                <div class="guide-copy">
                                    <h3>Explore how the system helps in real campus situations.</h3>
                                    <p>Choose a use case to see one focused view at a time instead of reading one long explanation.</p>
                                </div>

                                <div class="guide-switcher" aria-label="System overview stories">
                                    @foreach ($welcomeJourneys as $journey)
                                        <button
                                            type="button"
                                            class="guide-option{{ $loop->first ? ' is-active' : '' }}"
                                            data-story-button
                                            data-story-index="{{ $loop->index }}"
                                            aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                                            style="--story-accent: {{ $journey['accent'] }};">
                                            <div class="guide-option-head">
                                                <span class="guide-option-icon" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24">
                                                        @switch($journey['icon'])
                                                            @case('pulse')
                                                                <path d="M4 12h3l2-4 3 8 2-4h6"></path>
                                                                @break
                                                            @case('pin')
                                                                <path d="M12 20c3.5-4 5-6.7 5-9a5 5 0 1 0-10 0c0 2.3 1.5 5 5 9Z"></path>
                                                                <path d="M12 11.5a1.5 1.5 0 1 0 0-3a1.5 1.5 0 0 0 0 3Z"></path>
                                                                @break
                                                            @case('spark')
                                                                <path d="M12 6l1.6 4.4L18 12l-4.4 1.6L12 18l-1.6-4.4L6 12l4.4-1.6L12 6Z"></path>
                                                                @break
                                                            @case('clock')
                                                                <g transform="translate(0.4 0)">
                                                                    <path d="M12 7v5l3 2"></path>
                                                                    <path d="M21 12a9 9 0 1 1-18 0a9 9 0 0 1 18 0Z"></path>
                                                                </g>
                                                                @break
                                                        @endswitch
                                                    </svg>
                                                </span>
                                                <span class="guide-option-label">{{ $journey['kicker'] }}</span>
                                            </div>

                                            <div class="guide-option-copy">
                                                <strong>{{ $journey['tab'] }}</strong>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>

                                <div class="guide-note">Tap a card or let the overview rotate on its own.</div>
                            </div>
                        </div>

                        <div class="insight-body">
                            <div class="insight-stage">
                                @foreach ($welcomeJourneys as $journey)
                                    <section
                                        class="story-panel{{ $loop->first ? ' is-active' : '' }}"
                                        data-story-panel
                                        data-story-index="{{ $loop->index }}"
                                        style="--story-accent: {{ $journey['accent'] }};"
                                        @unless($loop->first) hidden @endunless>
                                        <div class="insight-copy">
                                            <div class="story-kicker">
                                                <span class="story-kicker-icon" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24">
                                                        @switch($journey['icon'])
                                                            @case('pulse')
                                                                <path d="M4 12h3l2-4 3 8 2-4h6"></path>
                                                                @break
                                                            @case('pin')
                                                                <path d="M12 20c3.5-4 5-6.7 5-9a5 5 0 1 0-10 0c0 2.3 1.5 5 5 9Z"></path>
                                                                <path d="M12 11.5a1.5 1.5 0 1 0 0-3a1.5 1.5 0 0 0 0 3Z"></path>
                                                                @break
                                                            @case('spark')
                                                                <path d="M12 6l1.6 4.4L18 12l-4.4 1.6L12 18l-1.6-4.4L6 12l4.4-1.6L12 6Z"></path>
                                                                @break
                                                            @case('clock')
                                                                <g transform="translate(0.4 0)">
                                                                    <path d="M12 7v5l3 2"></path>
                                                                    <path d="M21 12a9 9 0 1 1-18 0a9 9 0 0 1 18 0Z"></path>
                                                                </g>
                                                                @break
                                                        @endswitch
                                                    </svg>
                                                </span>
                                                {{ $journey['kicker'] }}
                                            </div>
                                            <h2>{{ $journey['title'] }}</h2>
                                            <p>{{ $journey['summary'] }}</p>
                                        </div>

                                        <div class="story-card-grid">
                                            @foreach ($journey['cards'] as $card)
                                                <article class="signal-card">
                                                    <span class="signal-card-icon" aria-hidden="true">
                                                        <svg viewBox="0 0 24 24">
                                                            @switch($card['icon'])
                                                                @case('eye')
                                                                    <path d="M2.5 12s3.5-5 9.5-5 9.5 5 9.5 5-3.5 5-9.5 5-9.5-5-9.5-5Z"></path>
                                                                    <path d="M12 15a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z"></path>
                                                                    @break
                                                                @case('users')
                                                                    <path d="M7.5 12.5a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z"></path>
                                                                    <path d="M16.5 11.5a2.5 2.5 0 1 0 0-5a2.5 2.5 0 0 0 0 5Z"></path>
                                                                    <path d="M3.5 19c.6-2.6 2.6-4 6-4s5.4 1.4 6 4"></path>
                                                                    <path d="M14.5 19c.5-1.8 1.9-2.8 4.2-2.8 1 0 2 .2 2.8.7"></path>
                                                                    @break
                                                                @case('pin')
                                                                    <path d="M12 20c3.5-4 5-6.7 5-9a5 5 0 1 0-10 0c0 2.3 1.5 5 5 9Z"></path>
                                                                    <path d="M12 11.5a1.5 1.5 0 1 0 0-3a1.5 1.5 0 0 0 0 3Z"></path>
                                                                    @break
                                                                @case('route')
                                                                    <path d="M5 6a2 2 0 1 1 4 0a2 2 0 0 1-4 0Z"></path>
                                                                    <path d="M15 18a2 2 0 1 1 4 0a2 2 0 0 1-4 0Z"></path>
                                                                    <path d="M9 6h4a3 3 0 0 1 3 3v7"></path>
                                                                @break
                                                                @case('spark')
                                                                    <path d="M12 6l1.6 4.4L18 12l-4.4 1.6L12 18l-1.6-4.4L6 12l4.4-1.6L12 6Z"></path>
                                                                    @break
                                                                @case('chat')
                                                                    <path d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v7a2.5 2.5 0 0 1-2.5 2.5H10l-4 3v-3H6.5A2.5 2.5 0 0 1 4 13.5v-7Z"></path>
                                                                    @break
                                                                @case('clock')
                                                                    <g transform="translate(0.4 0)">
                                                                        <path d="M12 7v5l3 2"></path>
                                                                        <path d="M21 12a9 9 0 1 1-18 0a9 9 0 0 1 18 0Z"></path>
                                                                    </g>
                                                                    @break
                                                                @case('grid')
                                                                    <path d="M4 4h7v7H4z"></path>
                                                                    <path d="M13 4h7v7h-7z"></path>
                                                                    <path d="M4 13h7v7H4z"></path>
                                                                    <path d="M13 13h7v7h-7z"></path>
                                                                    @break
                                                            @endswitch
                                                        </svg>
                                                    </span>
                                                    <div class="signal-card-copy">
                                                        <span>{{ $card['label'] }}</span>
                                                        <strong>{{ $card['title'] }}</strong>
                                                        <p>{{ $card['copy'] }}</p>
                                                    </div>
                                                </article>
                                            @endforeach
                                        </div>

                                        <div class="story-points">
                                            @foreach ($journey['points'] as $point)
                                                <span class="story-point">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                                        <path d="M5 12l4 4 10-10"></path>
                                                    </svg>
                                                    {{ $point }}
                                                </span>
                                            @endforeach
                                        </div>

                                        <div class="story-glance" aria-label="{{ $journey['kicker'] }} quick summary">
                                            @foreach ($journey['glance'] as $glance)
                                                <article class="story-glance-card">
                                                    <span>{{ $glance['label'] }}</span>
                                                    <strong>{{ $glance['value'] }}</strong>
                                                </article>
                                            @endforeach
                                        </div>
                                    </section>
                                @endforeach
                            </div>

                            <div class="story-footer">
                                <div class="story-pager" aria-label="Story navigation">
                                    @foreach ($welcomeJourneys as $journey)
                                        <button
                                            type="button"
                                            class="story-dot{{ $loop->first ? ' is-active' : '' }}"
                                            data-story-dot
                                            data-story-index="{{ $loop->index }}"
                                            style="--story-accent: {{ $journey['accent'] }};"
                                            aria-current="{{ $loop->first ? 'true' : 'false' }}"
                                            aria-label="Show {{ $journey['kicker'] }} story"></button>
                                    @endforeach
                                </div>

                                <div class="story-progress" aria-hidden="true">
                                    <div class="story-progress-bar" data-story-progress style="--story-accent: {{ $welcomeJourneys[0]['accent'] ?? '#59ec98' }};"></div>
                                </div>

                                <div class="story-hint">Interactive overview for students, faculty support, and offices.</div>
                            </div>
                        </div>
                    </aside>
                </section>
            </div>
        </div>

        @guest
            @if (Route::has('login'))
                <div class="login-modal"
                    data-login-modal
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="login-modal-title"
                    aria-hidden="true"
                    hidden>
                    <div class="login-modal-card">
                        <button type="button" class="login-modal-close" data-login-close aria-label="Close login">
                            <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
                                <path d="M6 6l12 12M18 6 6 18" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"></path>
                            </svg>
                        </button>

                        <div class="login-modal-body">
                            <div class="login-modal-head">
                                <div class="login-campus-pill">
                                    <span class="login-campus-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M4 10.5 12 6l8 4.5"></path>
                                            <path d="M6 10.5v7.5"></path>
                                            <path d="M10 10.5v7.5"></path>
                                            <path d="M14 10.5v7.5"></path>
                                            <path d="M18 10.5v7.5"></path>
                                            <path d="M4 18h16"></path>
                                        </svg>
                                    </span>
                                    Isabela State University Cauayan Campus
                                </div>

                                <div>
                                    <h2 id="login-modal-title" class="login-modal-title">Log in</h2>
                                    <p class="login-modal-copy">Use your school account to check teacher and faculty availability from one place.</p>
                                </div>
                            </div>

                            @if (session('status'))
                                <div class="login-status">{{ session('status') }}</div>
                            @endif

                            <form method="POST" action="{{ route('login') }}" class="login-modal-form" novalidate>
                                @csrf

                                <div class="login-field-group">
                                    <label class="login-label" for="welcome-login-username">Username</label>
                                    <input
                                        id="welcome-login-username"
                                        class="login-field"
                                        type="text"
                                        name="username"
                                        value="{{ old('username') }}"
                                        autocomplete="username"
                                        required>
                                    @error('username')
                                        <div class="login-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="login-field-group">
                                    <label class="login-label" for="welcome-login-password">Password</label>
                                    <input
                                        id="welcome-login-password"
                                        class="login-field"
                                        type="password"
                                        name="password"
                                        autocomplete="current-password"
                                        required>
                                    @error('password')
                                        <div class="login-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="login-check-row">
                                    <input id="welcome-remember-me" type="checkbox" class="login-check-input" name="remember" @checked(old('remember'))>
                                    <label for="welcome-remember-me" class="login-check-label">Remember me</label>
                                </div>

                                <button type="submit" class="login-submit">Log in</button>

                                <div class="login-support">
                                    <strong>Don't have an account?</strong>
                                    <span>Go to the registrar and admin will make an account for you.</span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endguest

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = document.querySelector('[data-login-modal]');
                if (!modal) {
                    return;
                }

                const openTriggers = document.querySelectorAll('[data-login-trigger]');
                const closeTriggers = modal.querySelectorAll('[data-login-close]');
                const usernameField = modal.querySelector('#welcome-login-username');

                const openModal = function () {
                    modal.hidden = false;
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('is-login-modal-open');

                    if (usernameField) {
                        window.setTimeout(function () {
                            usernameField.focus();
                        }, 40);
                    }
                };

                const closeModal = function () {
                    modal.hidden = true;
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('is-login-modal-open');
                };

                openTriggers.forEach(function (trigger) {
                    trigger.addEventListener('click', function (event) {
                        event.preventDefault();
                        openModal();
                    });
                });

                closeTriggers.forEach(function (trigger) {
                    trigger.addEventListener('click', closeModal);
                });

                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        closeModal();
                    }
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape' && !modal.hidden) {
                        closeModal();
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function () {
                const explorer = document.querySelector('[data-story-explorer]');
                if (!explorer) {
                    return;
                }

                const buttons = Array.from(explorer.querySelectorAll('[data-story-button]'));
                const dots = Array.from(explorer.querySelectorAll('[data-story-dot]'));
                const panels = Array.from(explorer.querySelectorAll('[data-story-panel]'));
                const progressBar = explorer.querySelector('[data-story-progress]');
                const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                let activeIndex = Math.max(0, panels.findIndex(function (panel) {
                    return panel.classList.contains('is-active');
                }));
                let autoRotate = null;

                const syncControls = function (index) {
                    buttons.forEach(function (button, buttonIndex) {
                        const isActive = buttonIndex === index;
                        button.classList.toggle('is-active', isActive);
                        button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                    });

                    dots.forEach(function (dot, dotIndex) {
                        const isActive = dotIndex === index;
                        dot.classList.toggle('is-active', isActive);
                        dot.setAttribute('aria-current', isActive ? 'true' : 'false');
                    });
                };

                const syncProgress = function (index) {
                    if (!progressBar) {
                        return;
                    }

                    const activePanel = panels[index];
                    const accent = activePanel ? activePanel.style.getPropertyValue('--story-accent') : '#59ec98';
                    progressBar.style.setProperty('--story-accent', accent || '#59ec98');

                    if (reducedMotion) {
                        return;
                    }

                    progressBar.style.animation = 'none';
                    void progressBar.offsetWidth;
                    progressBar.style.animation = '';
                    progressBar.style.animationPlayState = 'running';
                };

                const showPanel = function (index) {
                    activeIndex = index;

                    panels.forEach(function (panel, panelIndex) {
                        const isActive = panelIndex === index;
                        panel.classList.toggle('is-active', isActive);
                        panel.hidden = !isActive;
                    });

                    syncControls(index);
                    syncProgress(index);
                };

                const startAutoRotate = function () {
                    if (reducedMotion || panels.length < 2) {
                        return;
                    }

                    window.clearInterval(autoRotate);
                    if (progressBar) {
                        progressBar.style.animationPlayState = 'running';
                    }
                    autoRotate = window.setInterval(function () {
                        const nextIndex = (activeIndex + 1) % panels.length;
                        showPanel(nextIndex);
                    }, 5200);
                };

                const stopAutoRotate = function () {
                    window.clearInterval(autoRotate);
                    if (progressBar && !reducedMotion) {
                        progressBar.style.animationPlayState = 'paused';
                    }
                };

                buttons.forEach(function (button, index) {
                    button.addEventListener('click', function () {
                        showPanel(index);
                        startAutoRotate();
                    });

                    button.addEventListener('keydown', function (event) {
                        if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
                            event.preventDefault();
                            const nextIndex = (index + 1) % buttons.length;
                            buttons[nextIndex].focus();
                            showPanel(nextIndex);
                            startAutoRotate();
                        }

                        if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
                            event.preventDefault();
                            const previousIndex = (index - 1 + buttons.length) % buttons.length;
                            buttons[previousIndex].focus();
                            showPanel(previousIndex);
                            startAutoRotate();
                        }
                    });
                });

                dots.forEach(function (dot, index) {
                    dot.addEventListener('click', function () {
                        showPanel(index);
                        startAutoRotate();
                    });
                });

                explorer.addEventListener('mouseenter', stopAutoRotate);
                explorer.addEventListener('mouseleave', startAutoRotate);
                explorer.addEventListener('focusin', stopAutoRotate);
                explorer.addEventListener('focusout', function (event) {
                    if (!explorer.contains(event.relatedTarget)) {
                        startAutoRotate();
                    }
                });

                showPanel(activeIndex === -1 ? 0 : activeIndex);
                startAutoRotate();
            });
        </script>
    </body>
</html>
