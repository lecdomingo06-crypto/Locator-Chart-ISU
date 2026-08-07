<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Professor Tracker') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                color-scheme: light;
                --bg-top: #eef9f1;
                --bg-bottom: #dff1e4;
                --card: rgba(255, 255, 255, 0.78);
                --card-border: rgba(255, 255, 255, 0.76);
                --text: #123524;
                --muted: #58705f;
                --green-900: #0c5c38;
                --green-800: #147247;
                --green-700: #1c8e55;
                --green-100: #e4f5ea;
                --sun: #f6c453;
                --mint: #7ee7ac;
                --aqua: #73d6d2;
                --shadow: 0 24px 68px rgba(13, 72, 43, 0.14);
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
                    linear-gradient(rgba(12, 92, 56, 0.035) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(12, 92, 56, 0.035) 1px, transparent 1px),
                    radial-gradient(circle at top left, rgba(118, 210, 149, 0.34), transparent 34%),
                    radial-gradient(circle at 82% 18%, rgba(51, 153, 97, 0.28), transparent 18%),
                    linear-gradient(145deg, var(--bg-top), var(--bg-bottom));
                background-size: 64px 64px, 64px 64px, auto, auto, auto;
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
                background: rgba(42, 162, 90, 0.18);
            }

            body::after {
                width: 24rem;
                height: 24rem;
                left: -6rem;
                bottom: -8rem;
                background: rgba(15, 92, 56, 0.12);
            }

            .page {
                position: relative;
                z-index: 1;
                min-height: 100vh;
                padding: 28px;
            }

            .shell {
                max-width: 1220px;
                margin: 0 auto;
                display: grid;
                gap: 26px;
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

            .topbar-actions {
                display: inline-flex;
                align-items: center;
                justify-content: flex-end;
                gap: 12px;
                flex-wrap: wrap;
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

            .layout {
                display: grid;
                grid-template-columns: minmax(0, 1fr);
                gap: 28px;
                align-items: stretch;
            }

            .hero-card {
                position: relative;
                overflow: hidden;
                border-radius: 34px;
                box-shadow: var(--shadow);
            }

            .hero-card {
                isolation: isolate;
                padding: 34px;
                background:
                    radial-gradient(circle at 72% 18%, rgba(246, 196, 83, 0.16), transparent 20%),
                    radial-gradient(circle at 96% 64%, rgba(115, 214, 210, 0.18), transparent 24%),
                    linear-gradient(110deg, rgba(255, 255, 255, 0.1), transparent 34%, rgba(12, 92, 56, 0.05) 35%, transparent 36%),
                    linear-gradient(135deg, rgba(255, 255, 255, 0.86), rgba(244, 252, 247, 0.66)),
                    var(--card);
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
                background: conic-gradient(from 120deg, rgba(126, 231, 172, 0.24), rgba(115, 214, 210, 0.14), rgba(246, 196, 83, 0.14), rgba(126, 231, 172, 0.24));
                animation: floatBlob 9s ease-in-out infinite;
            }

            .hero-card::after {
                content: '';
                position: absolute;
                inset: 18px;
                z-index: 0;
                border-radius: 28px;
                border: 1px solid rgba(12, 92, 56, 0.06);
                pointer-events: none;
            }

            .hero-glow {
                position: absolute;
                z-index: 0;
                width: 18rem;
                height: 18rem;
                top: 4rem;
                right: 9%;
                border-radius: 999px;
                background:
                    radial-gradient(circle, rgba(34, 197, 94, 0.16), transparent 60%),
                    repeating-conic-gradient(from 18deg, rgba(12, 92, 56, 0.06) 0 12deg, transparent 12deg 28deg);
                opacity: 0.72;
                filter: blur(0.2px);
                animation: slowSpin 22s linear infinite;
                pointer-events: none;
            }

            .hero-inner {
                position: relative;
                z-index: 1;
                display: grid;
                gap: 28px;
            }

            .hero-top {
                display: grid;
                grid-template-columns: minmax(0, 1fr) minmax(260px, 0.34fr);
                gap: 24px;
                align-items: end;
            }

            .hero-copy {
                display: grid;
                gap: 24px;
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
                width: 9px;
                height: 9px;
                border-radius: 999px;
                background: #22c55e;
                box-shadow: 0 0 0 6px rgba(34, 197, 94, 0.12);
            }

            h1 {
                margin: 0;
                max-width: 11ch;
                font-size: clamp(2.7rem, 5vw, 4.8rem);
                line-height: 0.92;
                letter-spacing: -0.05em;
            }

            .hero-card p {
                margin: 0;
                max-width: 54ch;
                color: var(--muted);
                font-size: 1.02rem;
                line-height: 1.8;
            }

            .quick-panel {
                position: relative;
                overflow: hidden;
                display: grid;
                gap: 16px;
                padding: 22px;
                border-radius: 26px;
                color: #effcf3;
                background:
                    radial-gradient(circle at top right, rgba(255, 255, 255, 0.2), transparent 34%),
                    linear-gradient(145deg, var(--green-800), var(--green-900));
                box-shadow: 0 24px 48px rgba(12, 92, 56, 0.2);
            }

            .quick-panel::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(120deg, transparent 20%, rgba(255, 255, 255, 0.14), transparent 52%);
                transform: translateX(-120%);
                animation: panelSweep 5.5s ease-in-out infinite;
            }

            .quick-panel::before {
                content: '';
                position: absolute;
                width: 9rem;
                height: 9rem;
                right: -4rem;
                top: -4rem;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.14);
            }

            .quick-panel span,
            .quick-panel strong {
                position: relative;
                z-index: 1;
            }

            .quick-label {
                color: rgba(239, 252, 243, 0.68);
                font-size: 0.76rem;
                font-weight: 800;
                letter-spacing: 0.1em;
                text-transform: uppercase;
            }

            .quick-panel strong {
                font-size: 1.65rem;
                line-height: 1;
                letter-spacing: -0.04em;
            }

            .quick-panel span:last-child {
                color: rgba(239, 252, 243, 0.78);
                line-height: 1.55;
            }

            .quick-bars {
                position: relative;
                z-index: 1;
                display: grid;
                gap: 8px;
            }

            .quick-bar {
                display: block;
                height: 7px;
                overflow: hidden;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.12);
            }

            .quick-bar::before {
                content: '';
                display: block;
                width: var(--fill, 70%);
                height: 100%;
                border-radius: inherit;
                background: linear-gradient(90deg, var(--sun), rgba(255, 255, 255, 0.84));
            }

            .hero-summary {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }

            .summary-pill {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                min-height: 38px;
                padding: 0 14px;
                border-radius: 999px;
                color: var(--green-900);
                background: rgba(255, 255, 255, 0.66);
                border: 1px solid rgba(12, 92, 56, 0.08);
                font-size: 0.88rem;
                font-weight: 700;
            }

            .summary-pill::before {
                content: '';
                width: 7px;
                height: 7px;
                border-radius: 999px;
                background: var(--green-700);
            }

            .action-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
                padding: 12px;
                border-radius: 30px;
                background: rgba(255, 255, 255, 0.42);
                border: 1px solid rgba(255, 255, 255, 0.7);
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72);
            }

            .action-card {
                position: relative;
                isolation: isolate;
                overflow: hidden;
                display: grid;
                grid-template-columns: auto minmax(0, 1fr) auto;
                align-items: center;
                gap: 16px;
                min-height: 112px;
                padding: 18px 20px;
                border-radius: 24px;
                text-decoration: none;
                color: var(--text);
                background:
                    linear-gradient(145deg, rgba(255, 255, 255, 0.92), rgba(246, 252, 248, 0.78));
                border: 1px solid rgba(12, 92, 56, 0.08);
                box-shadow: 0 12px 26px rgba(14, 76, 46, 0.07);
                cursor: pointer;
                transition: transform 0.22s ease, box-shadow 0.22s ease, background 0.22s ease, border-color 0.22s ease;
            }

            .action-card::before {
                content: '';
                position: absolute;
                inset: 18px auto 18px 0;
                width: 4px;
                border-radius: 0 999px 999px 0;
                background: linear-gradient(180deg, var(--green-700), var(--sun));
                opacity: 0.7;
                transition: opacity 0.22s ease, width 0.22s ease;
            }

            .action-card::after {
                content: '';
                position: absolute;
                z-index: -1;
                width: 12rem;
                height: 12rem;
                right: -7rem;
                top: 50%;
                border-radius: 999px;
                background: radial-gradient(circle, rgba(34, 197, 94, 0.16), transparent 66%);
                opacity: 0;
                transform: translateY(-50%) scale(0.8);
                transition: opacity 0.22s ease, transform 0.22s ease;
            }

            .action-card:hover {
                transform: translateX(4px);
                background: rgba(255, 255, 255, 0.92);
                border-color: rgba(28, 142, 85, 0.2);
                box-shadow: 0 18px 34px rgba(14, 76, 46, 0.12);
            }

            .action-card:hover::before {
                width: 7px;
                opacity: 1;
            }

            .action-card:hover::after {
                opacity: 1;
                transform: translateY(-50%) scale(1);
            }

            .action-card:focus-visible {
                outline: 3px solid rgba(34, 197, 94, 0.42);
                outline-offset: 4px;
            }

            .action-icon {
                display: inline-grid;
                place-items: center;
                width: 48px;
                height: 48px;
                border-radius: 16px;
                color: var(--green-900);
                background: linear-gradient(145deg, rgba(226, 246, 234, 0.92), rgba(255, 255, 255, 0.62));
                border: 1px solid rgba(12, 92, 56, 0.08);
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72);
                transition: transform 0.22s ease, background 0.22s ease, box-shadow 0.22s ease;
            }

            .action-icon svg {
                width: 22px;
                height: 22px;
                display: block;
                fill: none;
                stroke: currentColor;
                stroke-width: 1.9;
                stroke-linecap: round;
                stroke-linejoin: round;
            }

            .action-card:hover .action-icon {
                transform: scale(1.04);
                box-shadow: 0 14px 28px rgba(12, 92, 56, 0.12);
            }

            .action-copy {
                display: grid;
                gap: 10px;
            }

            .action-card strong {
                font-size: 1.08rem;
                letter-spacing: -0.02em;
            }

            .action-copy span {
                color: var(--muted);
                line-height: 1.5;
            }

            .action-label {
                color: var(--green-900);
                font-size: 0.76rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .action-arrow {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 2.4rem;
                height: 2.4rem;
                border-radius: 999px;
                background: rgba(20, 114, 71, 0.12);
                color: var(--green-900);
                transition: transform 0.2s ease, background 0.2s ease;
            }

            .action-arrow svg {
                width: 17px;
                height: 17px;
                display: block;
                fill: none;
                stroke: currentColor;
                stroke-width: 2;
                stroke-linecap: round;
                stroke-linejoin: round;
            }

            .action-card:hover .action-arrow {
                transform: translateX(3px);
                background: rgba(20, 114, 71, 0.18);
            }

            .action-card.featured {
                color: #effcf3;
                background:
                    radial-gradient(circle at 88% 20%, rgba(246, 196, 83, 0.22), transparent 24%),
                    linear-gradient(145deg, var(--green-800), var(--green-900));
                border: 0;
                box-shadow: 0 18px 34px rgba(12, 92, 56, 0.18);
            }

            .action-card.featured .action-copy span {
                color: rgba(239, 252, 243, 0.8);
            }

            .action-card.featured .action-icon {
                color: #effcf3;
                background: rgba(255, 255, 255, 0.14);
                border-color: rgba(255, 255, 255, 0.12);
            }

            .action-card.featured .action-label {
                color: rgba(239, 252, 243, 0.92);
            }

            .action-card.featured .action-arrow {
                background: rgba(255, 255, 255, 0.16);
                color: #effcf3;
            }

            .action-card.featured:hover .action-arrow {
                background: rgba(255, 255, 255, 0.22);
            }

            @keyframes floatBlob {
                0%,
                100% {
                    transform: translate3d(0, 0, 0) rotate(0deg);
                }

                50% {
                    transform: translate3d(1.5rem, -0.8rem, 0) rotate(10deg);
                }
            }

            @keyframes slowSpin {
                to {
                    transform: rotate(360deg);
                }
            }

            @keyframes panelSweep {
                0%,
                45% {
                    transform: translateX(-120%);
                }

                72%,
                100% {
                    transform: translateX(120%);
                }
            }

            @media (prefers-reduced-motion: reduce) {
                *,
                *::before,
                *::after {
                    animation-duration: 0.01ms !important;
                    animation-iteration-count: 1 !important;
                    scroll-behavior: auto !important;
                }
            }

            .logout-form {
                margin: 0;
            }

            .logout-button {
                min-height: 42px;
                padding: 0 18px;
                border: 1px solid rgba(12, 92, 56, 0.12);
                border-radius: 999px;
                color: #effcf3;
                background: linear-gradient(145deg, var(--green-800), var(--green-900));
                font: inherit;
                font-weight: 700;
                cursor: pointer;
                box-shadow: 0 12px 24px rgba(8, 57, 36, 0.12);
                transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
            }

            .logout-button:hover {
                transform: translateY(-2px);
                filter: brightness(1.04);
                box-shadow: 0 16px 28px rgba(8, 57, 36, 0.18);
            }

            @media (max-width: 980px) {
                .layout {
                    grid-template-columns: 1fr;
                }

                .hero-top {
                    grid-template-columns: 1fr;
                    align-items: stretch;
                }

                .hero-glow {
                    right: -4rem;
                    top: 8rem;
                }

                .action-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
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

                .topbar-actions {
                    width: 100%;
                    justify-content: space-between;
                }

                .hero-card {
                    padding: 22px;
                    border-radius: 28px;
                }

                .hero-card::after {
                    inset: 10px;
                    border-radius: 22px;
                }

                h1 {
                    max-width: none;
                }

                .quick-panel {
                    padding: 18px;
                    border-radius: 22px;
                }

                .hero-summary {
                    gap: 8px;
                }

                .summary-pill {
                    min-height: 34px;
                    padding: 0 12px;
                    font-size: 0.82rem;
                }

                .action-grid {
                    grid-template-columns: 1fr;
                }

                .action-card {
                    grid-column: span 1;
                    min-height: 166px;
                    padding: 18px;
                    border-radius: 22px;
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
                            <span>Admin workspace for status control and live monitoring</span>
                        </div>
                    </div>

                    <div class="topbar-actions">
                        <div class="status-pill">Admin Portal</div>
                        <form method="POST" action="{{ route('logout') }}" class="logout-form">
                            @csrf
                            <button type="submit" class="logout-button">Logout</button>
                        </form>
                    </div>
                </section>

                <main class="layout">
                    <section class="hero-card">
                        <div class="hero-glow" aria-hidden="true"></div>
                        <div class="hero-inner">
                            <div class="hero-top">
                                <div class="hero-copy">
                                    <div class="eyebrow">Admin Dashboard</div>
                                    <h1>Welcome back, Admin.</h1>
                                    <p>
                                        Manage availability overrides and monitor the live viewer from one cleaner control panel.
                                    </p>

                                    <div class="hero-summary" aria-label="Dashboard highlights">
                                        <span class="summary-pill">Status control</span>
                                        <span class="summary-pill">Live monitoring</span>
                                        <span class="summary-pill">Account tools</span>
                                    </div>
                                </div>

                                <div class="quick-panel" aria-label="Quick launch summary">
                                    <span class="quick-label">Quick Launch</span>
                                    <strong>4 tools ready</strong>
                                    <span>Status override, viewer, account creation, and academic events are grouped below.</span>
                                    <div class="quick-bars" aria-hidden="true">
                                        <span class="quick-bar" style="--fill: 88%;"></span>
                                        <span class="quick-bar" style="--fill: 72%;"></span>
                                        <span class="quick-bar" style="--fill: 80%;"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="action-grid">
                                <a href="{{ route('admin.status') }}" class="action-card featured">
                                    <span class="action-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M4 7h10"></path>
                                            <path d="M18 7h2"></path>
                                            <path d="M4 17h2"></path>
                                            <path d="M10 17h10"></path>
                                            <path d="M14 5v4"></path>
                                            <path d="M10 15v4"></path>
                                        </svg>
                                    </span>
                                    <div class="action-copy">
                                        <strong>Manage Status Override</strong>
                                        <span>Review and control status override tools for staff visibility.</span>
                                        <span class="action-label">Open Tool</span>
                                    </div>
                                    <span class="action-arrow" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M5 12h14"></path>
                                            <path d="m13 6 6 6-6 6"></path>
                                        </svg>
                                    </span>
                                </a>

                                <a href="{{ route('admin.viewer') }}" class="action-card">
                                    <span class="action-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M2.5 12s3.5-5.5 9.5-5.5 9.5 5.5 9.5 5.5-3.5 5.5-9.5 5.5S2.5 12 2.5 12Z"></path>
                                            <path d="M12 15a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z"></path>
                                        </svg>
                                    </span>
                                    <div class="action-copy">
                                        <strong>Live Viewer</strong>
                                        <span>Open the live availability view and monitor current staff visibility.</span>
                                        <span class="action-label">Open Tool</span>
                                    </div>
                                    <span class="action-arrow" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M5 12h14"></path>
                                            <path d="m13 6 6 6-6 6"></path>
                                        </svg>
                                    </span>
                                </a>

                                <a href="{{ route('admin.users.create') }}" class="action-card">
                                    <span class="action-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M12 12a4 4 0 1 0 0-8a4 4 0 0 0 0 8Z"></path>
                                            <path d="M4.5 20c.8-3.8 3.4-5.8 7.5-5.8"></path>
                                            <path d="M18 15v5"></path>
                                            <path d="M20.5 17.5h-5"></path>
                                        </svg>
                                    </span>
                                    <div class="action-copy">
                                        <strong>Create Account</strong>
                                        <span>Create new student, professor, and faculty accounts from the admin workspace.</span>
                                        <span class="action-label">Open Tool</span>
                                    </div>
                                    <span class="action-arrow" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M5 12h14"></path>
                                            <path d="m13 6 6 6-6 6"></path>
                                        </svg>
                                    </span>
                                </a>

                                <a href="{{ route('academic_events.create') }}" class="action-card">
                                    <span class="action-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M7 3v4"></path>
                                            <path d="M17 3v4"></path>
                                            <path d="M4 8h16"></path>
                                            <path d="M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"></path>
                                            <path d="M8 13h8"></path>
                                            <path d="M8 17h5"></path>
                                        </svg>
                                    </span>
                                    <div class="action-copy">
                                        <strong>Academic Events</strong>
                                        <span>Create and maintain holidays, suspensions, and scoped school activity windows.</span>
                                        <span class="action-label">Open Tool</span>
                                    </div>
                                    <span class="action-arrow" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M5 12h14"></path>
                                            <path d="m13 6 6 6-6 6"></path>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </body>
</html>
