@php
    $statusClasses = [
        'Available' => 'status-available',
        'In Class' => 'status-class',
        'On Meeting' => 'status-meeting',
        'On Leave' => 'status-away',
        'Emergency' => 'status-alert',
        'Not Available' => 'status-muted',
    ];

    $totalTracked = $snapshotTotals['tracked'] ?? 0;
    $availableCount = $snapshotTotals['available'] ?? 0;
    $engagedCount = $snapshotTotals['engaged'] ?? 0;
    $attentionCount = $snapshotTotals['attention'] ?? 0;
    $activeContexts = $snapshotTotals['active_contexts'] ?? 0;
    $availableProfessorCount = $availableProfessorSnapshot->count();
    $loginError = $errors->first('username') ?: $errors->first('password') ?: $errors->first('h-captcha-response');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Professor Tracking System') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if (config('services.hcaptcha.sitekey'))
        <script>
            (function () {
                function isVisible(element) {
                    const style = window.getComputedStyle(element);

                    return element.offsetParent !== null
                        && style.visibility !== 'hidden'
                        && style.display !== 'none'
                        && element.getBoundingClientRect().width > 0;
                }

                function setCaptchaStatus(element, message, isError) {
                    const wrapper = element.closest('[data-captcha-wrap]');
                    const status = wrapper ? wrapper.querySelector('[data-captcha-status]') : null;

                    if (!status) {
                        return;
                    }

                    status.textContent = message || '';
                    status.hidden = !message;
                    status.classList.toggle('is-error', Boolean(isError));
                }

                window.renderHCaptchaWidgets = function () {
                    if (!window.hcaptcha) {
                        return;
                    }

                    document.querySelectorAll('.h-captcha[data-sitekey]').forEach(function (element) {
                        if (element.dataset.hcaptchaRendered === '1' || !isVisible(element)) {
                            return;
                        }

                        try {
                            const widgetId = window.hcaptcha.render(element, {
                                sitekey: element.dataset.sitekey,
                                theme: 'light',
                            });

                            element.dataset.hcaptchaRendered = '1';
                            element.dataset.hcaptchaWidgetId = widgetId;
                            setCaptchaStatus(element, '', false);
                        } catch (error) {
                            const message = String((error && error.message) || error || '').toLowerCase();

                            if (message.includes('already')) {
                                element.dataset.hcaptchaRendered = '1';
                                setCaptchaStatus(element, '', false);

                                return;
                            }

                            setCaptchaStatus(element, 'Robot check could not load. Refresh the page and try again.', true);
                        }
                    });
                };

                window.onHCaptchaLoad = function () {
                    window.renderHCaptchaWidgets();
                };

                document.addEventListener('DOMContentLoaded', function () {
                    window.renderHCaptchaWidgets();
                    window.setTimeout(window.renderHCaptchaWidgets, 250);
                });
            })();
        </script>
        <script src="https://js.hcaptcha.com/1/api.js?onload=onHCaptchaLoad&render=explicit" async defer></script>
    @endif

    <style>
        :root {
            color-scheme: light;
            --page: #f4f8f5;
            --surface: #ffffff;
            --surface-soft: #eef7f1;
            --border: #d8e6dd;
            --border-strong: #bcd8c7;
            --text: #10251a;
            --muted: #63766b;
            --green-900: #085b38;
            --green-800: #0d7145;
            --green-700: #138550;
            --green-100: #e5f5eb;
            --blue-100: #e9f2ff;
            --blue-700: #255ea8;
            --amber-100: #fff4d8;
            --amber-700: #8a5c00;
            --red-100: #fff0ec;
            --red-700: #b42318;
            --shadow: 0 18px 48px rgba(14, 77, 45, 0.1);
        }

        * {
            box-sizing: border-box;
            letter-spacing: 0;
        }

        html,
        body {
            min-height: 100%;
            margin: 0;
        }

        body {
            font-family: 'Outfit', system-ui, sans-serif;
            color: var(--text);
            background: var(--page);
        }

        body.modal-open {
            overflow: hidden;
        }

        button,
        input,
        select {
            font: inherit;
        }

        a {
            color: inherit;
        }

        .site-shell {
            width: min(1180px, calc(100% - 40px));
            margin: 0 auto;
            padding: 28px 0 48px;
        }

        .site-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 14px 0 34px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            text-decoration: none;
        }

        .brand-logo {
            width: 50px;
            height: 50px;
            border: 1px solid var(--border);
            border-radius: 8px;
            object-fit: cover;
            background: #fff;
        }

        .brand-copy {
            display: grid;
            gap: 2px;
            min-width: 0;
        }

        .brand-copy strong {
            font-size: 1rem;
            font-weight: 800;
        }

        .brand-copy span {
            color: var(--muted);
            font-size: 0.84rem;
            font-weight: 600;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .nav-link,
        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 0 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 800;
            text-decoration: none;
        }

        .nav-link {
            color: var(--green-900);
            border: 1px solid var(--border);
            background: #fff;
        }

        .button {
            border: 1px solid var(--green-800);
            color: #fff;
            background: var(--green-800);
            box-shadow: 0 12px 28px rgba(13, 113, 69, 0.16);
            cursor: pointer;
        }

        .button.secondary {
            color: var(--green-900);
            background: #fff;
            border-color: var(--border-strong);
            box-shadow: none;
        }

        .button:hover,
        .nav-link:hover {
            transform: translateY(-1px);
        }

        .hero-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.02fr) minmax(360px, 0.98fr);
            gap: 22px;
            align-items: stretch;
        }

        .hero-copy,
        .snapshot-panel,
        .info-card {
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
            box-shadow: var(--shadow);
        }

        .hero-copy {
            min-height: 580px;
            padding: 42px;
            display: grid;
            align-content: center;
            gap: 24px;
        }

        .eyebrow,
        .section-label,
        .metric-label,
        .field-label {
            color: var(--green-900);
            font-size: 0.76rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .hero-title {
            margin: 0;
            max-width: 720px;
            font-size: 4.2rem;
            line-height: 0.98;
            font-weight: 800;
        }

        .hero-text {
            margin: 0;
            max-width: 610px;
            color: var(--muted);
            font-size: 1.06rem;
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .metric-strip {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin: 10px 0 0;
        }

        .metric-card {
            min-height: 96px;
            display: grid;
            align-content: center;
            gap: 6px;
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface-soft);
        }

        .metric-value {
            font-size: 2rem;
            line-height: 1;
            font-weight: 800;
        }

        .snapshot-panel {
            padding: 26px;
            display: grid;
            grid-template-rows: auto auto minmax(0, 1fr);
            gap: 18px;
        }

        .snapshot-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .snapshot-head h2,
        .section-head h2,
        .info-card h3 {
            margin: 4px 0 0;
            font-size: 1.35rem;
            line-height: 1.15;
            font-weight: 800;
        }

        .snapshot-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 34px;
            padding: 0 12px;
            border: 1px solid var(--border-strong);
            border-radius: 8px;
            color: var(--green-900);
            background: var(--green-100);
            font-size: 0.8rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .status-card {
            min-height: 82px;
            display: grid;
            gap: 5px;
            padding: 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fbfdfb;
        }

        .status-card strong {
            font-size: 1.45rem;
            line-height: 1;
        }

        .staff-list {
            display: grid;
            gap: 10px;
            align-content: start;
        }

        .staff-row {
            display: grid;
            grid-template-columns: 48px minmax(0, 1fr) auto;
            align-items: center;
            gap: 12px;
            min-height: 76px;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
        }

        .avatar {
            width: 48px;
            height: 48px;
            display: grid;
            place-items: center;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--green-900);
            background: var(--green-100);
            font-size: 0.82rem;
            font-weight: 800;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .staff-name {
            min-width: 0;
        }

        .staff-name strong,
        .empty-title {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 0.98rem;
            font-weight: 800;
        }

        .staff-name span,
        .empty-copy {
            display: block;
            margin-top: 3px;
            color: var(--muted);
            font-size: 0.84rem;
            line-height: 1.4;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 30px;
            max-width: 146px;
            padding: 0 10px;
            border-radius: 8px;
            font-size: 0.72rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-available {
            color: #087238;
            background: #dff6e8;
        }

        .status-class,
        .status-meeting {
            color: var(--blue-700);
            background: var(--blue-100);
        }

        .status-away {
            color: var(--amber-700);
            background: var(--amber-100);
        }

        .status-alert {
            color: var(--red-700);
            background: var(--red-100);
        }

        .status-muted {
            color: #58665d;
            background: #edf2ef;
        }

        .empty-state {
            padding: 18px;
            border: 1px dashed var(--border-strong);
            border-radius: 8px;
            background: #fbfdfb;
        }

        .info-section {
            margin-top: 22px;
        }

        .section-head {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 18px;
            margin: 0 0 12px;
        }

        .section-head p {
            margin: 5px 0 0;
            color: var(--muted);
            line-height: 1.6;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .info-card {
            min-height: 180px;
            padding: 22px;
            display: grid;
            align-content: start;
            gap: 12px;
        }

        .info-card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
        }

        .login-modal {
            position: fixed;
            inset: 0;
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(13, 34, 23, 0.48);
            backdrop-filter: blur(5px);
        }

        .login-modal.is-open {
            display: flex;
        }

        .login-dialog {
            width: min(470px, 100%);
            max-height: calc(100vh - 40px);
            overflow: auto;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.22);
        }

        .login-header {
            display: flex;
            align-items: start;
            justify-content: space-between;
            gap: 18px;
            padding: 26px 28px 16px;
        }

        .login-header h2 {
            margin: 6px 0 0;
            font-size: 2.25rem;
            line-height: 1;
            font-weight: 800;
        }

        .login-header p {
            margin: 10px 0 0;
            color: var(--muted);
            line-height: 1.55;
        }

        .icon-button {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            flex: 0 0 auto;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--muted);
            background: #f7faf8;
            cursor: pointer;
        }

        .icon-button svg {
            width: 18px;
            height: 18px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
        }

        .login-form {
            display: grid;
            gap: 16px;
            padding: 0 28px 28px;
        }

        .field {
            display: grid;
            gap: 7px;
            min-width: 0;
        }

        .field-input {
            width: 100%;
            min-height: 48px;
            padding: 0 13px;
            border: 1px solid var(--border);
            border-radius: 8px;
            outline: 0;
            color: var(--text);
            background: #fff;
        }

        .field-input:focus {
            border-color: var(--green-800);
            box-shadow: 0 0 0 3px rgba(13, 113, 69, 0.12);
        }

        .login-error {
            margin: 0;
            color: var(--red-700);
            font-size: 0.82rem;
            font-weight: 700;
        }

        .login-error li {
            list-style: none;
        }

        .captcha-wrap {
            min-height: 78px;
            display: grid;
            align-items: center;
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fbfdfb;
            overflow: hidden;
        }

        .captcha-status {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 0.78rem;
            font-weight: 700;
        }

        .captcha-status.is-error {
            color: var(--red-700);
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
            font-size: 0.9rem;
            font-weight: 700;
        }

        .remember-row input {
            width: 17px;
            height: 17px;
            accent-color: var(--green-800);
        }

        .login-submit {
            width: 100%;
            min-height: 50px;
        }

        .login-foot {
            display: grid;
            gap: 5px;
            padding-top: 6px;
            color: var(--muted);
            font-size: 0.92rem;
        }

        .login-foot strong {
            color: var(--text);
        }

        .login-foot a {
            width: fit-content;
            color: var(--green-900);
            font-weight: 800;
            text-decoration: none;
        }

        @media (max-width: 980px) {
            .hero-grid,
            .info-grid {
                grid-template-columns: 1fr;
            }

            .hero-copy {
                min-height: auto;
                padding: 34px;
            }

            .hero-title {
                font-size: 3rem;
            }
        }

        @media (max-width: 680px) {
            .site-shell {
                width: min(100% - 24px, 1180px);
                padding-top: 14px;
            }

            .site-header {
                align-items: stretch;
                flex-direction: column;
                padding-bottom: 18px;
            }

            .nav-actions {
                justify-content: stretch;
            }

            .nav-actions > * {
                flex: 1 1 150px;
            }

            .hero-copy,
            .snapshot-panel,
            .info-card {
                padding: 18px;
            }

            .hero-title {
                font-size: 2.35rem;
            }

            .metric-strip,
            .status-grid {
                grid-template-columns: 1fr;
            }

            .staff-row {
                grid-template-columns: 42px minmax(0, 1fr);
            }

            .staff-row .status-pill {
                grid-column: 2;
                justify-self: start;
            }

            .login-header,
            .login-form {
                padding-left: 20px;
                padding-right: 20px;
            }
        }
    </style>
    <x-minimal-ui />
</head>
<body>
    @if ($loginError)
        <x-flash-toast :message="$loginError" type="error" />
    @endif

    <div class="site-shell">
        <header class="site-header">
            <a href="{{ url('/') }}" class="brand" aria-label="Professor Tracking System home">
                <img src="{{ asset('images/isulogo.jpg') }}" alt="ISU logo" class="brand-logo">
                <span class="brand-copy">
                    <strong>Professor Tracking System</strong>
                    <span>Isabela State University Cauayan Campus</span>
                </span>
            </a>

            <nav class="nav-actions" aria-label="Public actions">
                <a href="{{ route('student.register') }}" class="nav-link">Student registration</a>
                <button type="button" class="button" data-open-login>Log in</button>
            </nav>
        </header>

        <main>
            <section class="hero-grid" aria-labelledby="home-title">
                <div class="hero-copy">
                    <div class="eyebrow">Campus availability viewer</div>
                    <h1 id="home-title" class="hero-title">Find professor availability before you walk.</h1>
                    <p class="hero-text">Check professor and faculty status, schedule context, and department details from one clean campus dashboard.</p>

                    <div class="hero-actions">
                        <button type="button" class="button" data-open-login>Open dashboard</button>
                        <a href="{{ route('student.register') }}" class="button secondary">Request student account</a>
                    </div>

                    <div class="metric-strip" aria-label="Current campus summary">
                        <div class="metric-card">
                            <span class="metric-label">Tracked staff</span>
                            <strong class="metric-value">{{ $totalTracked }}</strong>
                        </div>
                        <div class="metric-card">
                            <span class="metric-label">Available now</span>
                            <strong class="metric-value">{{ $availableCount }}</strong>
                        </div>
                        <div class="metric-card">
                            <span class="metric-label">Active context</span>
                            <strong class="metric-value">{{ $activeContexts }}</strong>
                        </div>
                    </div>
                </div>

                <aside class="snapshot-panel" aria-labelledby="snapshot-title">
                    <div class="snapshot-head">
                        <div>
                            <span class="section-label">Live snapshot</span>
                            <h2 id="snapshot-title">Campus status</h2>
                        </div>
                        <span class="snapshot-badge">{{ $availableProfessorCount }} available</span>
                    </div>

                    <div class="status-grid">
                        <div class="status-card">
                            <span class="metric-label">Available</span>
                            <strong>{{ $availableCount }}</strong>
                        </div>
                        <div class="status-card">
                            <span class="metric-label">In class or busy</span>
                            <strong>{{ $engagedCount }}</strong>
                        </div>
                        <div class="status-card">
                            <span class="metric-label">Needs attention</span>
                            <strong>{{ $attentionCount }}</strong>
                        </div>
                        <div class="status-card">
                            <span class="metric-label">Total staff</span>
                            <strong>{{ $totalTracked }}</strong>
                        </div>
                    </div>

                    <div class="staff-list">
                        @forelse ($availableProfessorSnapshot->take(4) as $staff)
                            @php
                                $statusClass = $statusClasses[$staff['status']] ?? 'status-muted';
                            @endphp
                            <div class="staff-row">
                                <span class="avatar" aria-hidden="true">
                                    @if (! empty($staff['profile_picture_url']))
                                        <img src="{{ $staff['profile_picture_url'] }}" alt="">
                                    @else
                                        {{ $staff['initials'] }}
                                    @endif
                                </span>
                                <span class="staff-name">
                                    <strong>{{ $staff['name'] }}</strong>
                                    <span>{{ $staff['department'] }} - {{ $staff['role_label'] }}</span>
                                </span>
                                <span class="status-pill {{ $statusClass }}">{{ $staff['status'] }}</span>
                            </div>
                        @empty
                            <div class="empty-state">
                                <strong class="empty-title">No available professor yet</strong>
                                <span class="empty-copy">Log in to view the complete live directory and schedule details.</span>
                            </div>
                        @endforelse
                    </div>
                </aside>
            </section>

            <section class="info-section" aria-labelledby="system-title">
                <div class="section-head">
                    <div>
                        <span class="section-label">System overview</span>
                        <h2 id="system-title">Built for quick campus checks</h2>
                    </div>
                </div>

                <div class="info-grid">
                    <article class="info-card">
                        <span class="section-label">Live viewer</span>
                        <h3>Know who is available.</h3>
                        <p>Staff status updates appear in the viewer with department and current context.</p>
                    </article>
                    <article class="info-card">
                        <span class="section-label">Schedules</span>
                        <h3>See class context.</h3>
                        <p>Regular schedules, special schedules, and events help explain staff availability.</p>
                    </article>
                    <article class="info-card">
                        <span class="section-label">Student access</span>
                        <h3>Request once, wait for approval.</h3>
                        <p>Students can submit an account request and sign in after admin review.</p>
                    </article>
                </div>
            </section>
        </main>
    </div>

    <div class="login-modal{{ $loginError ? ' is-open' : '' }}" data-login-modal aria-hidden="{{ $loginError ? 'false' : 'true' }}">
        <section class="login-dialog" role="dialog" aria-modal="true" aria-labelledby="login-title">
            <div class="login-header">
                <div>
                    <span class="section-label">Secure access</span>
                    <h2 id="login-title">Log in</h2>
                    <p>Use your school account to open the professor and faculty viewer.</p>
                </div>
                <button type="button" class="icon-button" aria-label="Close login" data-close-login>
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M6 6l12 12"></path>
                        <path d="M18 6L6 18"></path>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf

                <div class="field">
                    <label for="username" class="field-label">Username / Student ID</label>
                    <input id="username" class="field-input" type="text" name="username" value="{{ old('username') }}" required autocomplete="username">
                    @error('username')
                        <p class="login-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password" class="field-label">Password</label>
                    <input id="password" class="field-input" type="password" name="password" required autocomplete="current-password">
                    @error('password')
                        <p class="login-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <span class="field-label">Robot verification</span>
                    <x-hcaptcha-field error-class="login-error" />
                </div>

                <label class="remember-row" for="remember_me">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>

                <button type="submit" class="button login-submit">Log in</button>

                <div class="login-foot">
                    <strong>Don't have an account?</strong>
                    <span>Students can request an account online.</span>
                    <a href="{{ route('student.register') }}">Student registration</a>
                </div>
            </form>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.querySelector('[data-login-modal]');
            const openers = document.querySelectorAll('[data-open-login]');
            const closers = document.querySelectorAll('[data-close-login]');

            function setModal(open) {
                if (!modal) {
                    return;
                }

                modal.classList.toggle('is-open', open);
                modal.setAttribute('aria-hidden', open ? 'false' : 'true');
                document.body.classList.toggle('modal-open', open);

                if (open) {
                    window.setTimeout(function () {
                        if (window.renderHCaptchaWidgets) {
                            window.renderHCaptchaWidgets();
                        }

                        const username = modal.querySelector('input[name="username"]');

                        if (username) {
                            username.focus({ preventScroll: true });
                        }
                    }, 120);
                }
            }

            openers.forEach(function (button) {
                button.addEventListener('click', function () {
                    setModal(true);
                });
            });

            closers.forEach(function (button) {
                button.addEventListener('click', function () {
                    setModal(false);
                });
            });

            if (modal) {
                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        setModal(false);
                    }
                });
            }

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    setModal(false);
                }
            });

            if (modal && modal.classList.contains('is-open')) {
                setModal(true);
            }
        });
    </script>
</body>
</html>