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
            --page: #f5f8f5;
            --surface: #ffffff;
            --border: #dbe8df;
            --text: #10251a;
            --muted: #617367;
            --green: #0d7145;
            --green-dark: #085b38;
            --shadow: 0 10px 28px rgba(12, 72, 43, 0.08);
        }

        * {
            box-sizing: border-box;
            letter-spacing: 0;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
        }

        body {
            color: var(--text);
            background: var(--page);
            font-family: 'Outfit', system-ui, sans-serif;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        .guest-page {
            min-height: 100vh;
            display: grid;
            grid-template-rows: auto 1fr;
        }

        .guest-header {
            width: min(1060px, calc(100% - 40px));
            margin: 0 auto;
            padding: 28px 0 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .guest-brand,
        .guest-link {
            color: inherit;
            text-decoration: none;
        }

        .guest-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .guest-logo {
            width: 48px;
            height: 48px;
            flex: 0 0 auto;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            object-fit: cover;
        }

        .guest-brand-copy {
            display: grid;
            gap: 2px;
            min-width: 0;
        }

        .guest-brand-copy strong {
            overflow: hidden;
            font-size: 1rem;
            font-weight: 800;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .guest-brand-copy span {
            color: var(--muted);
            font-size: 0.84rem;
            font-weight: 600;
        }

        .guest-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 0 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--green-dark);
            background: #fff;
            font-size: 0.88rem;
            font-weight: 800;
        }

        .guest-main {
            display: grid;
            place-items: start center;
            padding: 36px 20px 56px;
        }

        .auth-card {
            width: min(500px, 100%);
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
            box-shadow: var(--shadow);
        }

        .auth-card-body {
            padding: 30px;
        }

        .auth-stack,
        .auth-form-grid,
        .auth-heading {
            display: grid;
            gap: 16px;
        }

        .auth-campus-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: fit-content;
            color: var(--green-dark);
            font-size: 0.76rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .auth-campus-icon {
            display: grid;
            place-items: center;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: #edf6f1;
        }

        .auth-campus-icon svg {
            width: 16px;
            height: 16px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .auth-title {
            margin: 0;
            font-size: 2.45rem;
            line-height: 1;
            font-weight: 800;
        }

        .auth-copy {
            margin: 0;
            color: var(--muted);
            line-height: 1.58;
        }

        .auth-group {
            display: grid;
            gap: 7px;
        }

        .auth-label {
            color: var(--text);
            font-size: 0.88rem;
            font-weight: 800;
        }

        .auth-field {
            width: 100%;
            min-height: 46px;
            padding: 0 13px;
            border: 1px solid var(--border);
            border-radius: 8px;
            outline: 0;
            color: var(--text);
            background: #fff;
        }

        .auth-field:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(13, 113, 69, 0.12);
        }

        .auth-error {
            margin: 0;
            color: #b42318;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .auth-error li {
            list-style: none;
        }

        .auth-check,
        .auth-helper-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .auth-check-input {
            width: 17px;
            height: 17px;
            accent-color: var(--green);
        }

        .auth-check-label {
            color: var(--muted);
            font-size: 0.9rem;
            font-weight: 700;
        }

        .auth-button {
            min-height: 48px;
            border: 1px solid var(--green);
            border-radius: 8px;
            color: #fff;
            background: var(--green);
            font-weight: 800;
            cursor: pointer;
        }

        .auth-footer {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            color: var(--muted);
            font-size: 0.92rem;
        }

        .auth-footer-login {
            display: grid;
            gap: 4px;
        }

        .auth-footer strong {
            color: var(--text);
        }

        .auth-inline-link {
            color: var(--green-dark);
            font-weight: 800;
            text-decoration: none;
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
            color: #b42318;
        }

        @media (max-width: 640px) {
            .guest-header {
                width: min(100% - 24px, 1060px);
                align-items: stretch;
                flex-direction: column;
                padding-top: 16px;
            }

            .guest-link {
                width: 100%;
            }

            .guest-main {
                padding: 18px 12px 34px;
            }

            .auth-card-body {
                padding: 22px;
            }

            .auth-title {
                font-size: 2.1rem;
            }
        }
    </style>
    <x-minimal-ui />
</head>
<body>
    <div class="guest-page">
        <header class="guest-header">
            <a href="{{ url('/') }}" class="guest-brand" aria-label="Professor Tracking System home">
                <img src="{{ asset('images/isulogo.jpg') }}" alt="ISU logo" class="guest-logo">
                <span class="guest-brand-copy">
                    <strong>Professor Tracking System</strong>
                    <span>Isabela State University Cauayan Campus</span>
                </span>
            </a>

            <a href="{{ url('/') }}" class="guest-link">Back to home</a>
        </header>

        <main class="guest-main">
            <section class="auth-card">
                <div class="auth-card-body">
                    {{ $slot }}
                </div>
            </section>
        </main>
    </div>
</body>
</html>