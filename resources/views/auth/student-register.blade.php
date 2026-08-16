<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Student Registration - {{ config('app.name', 'Professor Tracking System') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
            --green-100: #e5f5eb;
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

        button,
        input,
        select {
            font: inherit;
        }

        .page-shell {
            width: min(1120px, calc(100% - 40px));
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
            color: inherit;
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

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .header-link,
        .primary-button,
        .secondary-button {
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

        .header-link,
        .secondary-button {
            color: var(--green-900);
            border: 1px solid var(--border-strong);
            background: #fff;
        }

        .primary-button {
            border: 1px solid var(--green-800);
            color: #fff;
            background: var(--green-800);
            box-shadow: 0 12px 28px rgba(13, 113, 69, 0.16);
            cursor: pointer;
        }

        .register-grid {
            display: grid;
            grid-template-columns: minmax(280px, 0.74fr) minmax(0, 1.26fr);
            gap: 22px;
            align-items: start;
        }

        .intro-panel,
        .form-panel {
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
            box-shadow: var(--shadow);
        }

        .intro-panel {
            padding: 28px;
            display: grid;
            gap: 24px;
        }

        .kicker,
        .field-label,
        .step-label {
            color: var(--green-900);
            font-size: 0.76rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .intro-panel h1 {
            margin: 6px 0 0;
            font-size: 2.4rem;
            line-height: 1.04;
            font-weight: 800;
        }

        .intro-copy {
            margin: 12px 0 0;
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.65;
        }

        .approval-list {
            display: grid;
            gap: 10px;
        }

        .approval-step {
            display: grid;
            grid-template-columns: 32px minmax(0, 1fr);
            gap: 12px;
            padding: 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fbfdfb;
        }

        .step-number {
            width: 32px;
            height: 32px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            color: var(--green-900);
            background: var(--green-100);
            font-weight: 800;
        }

        .approval-step p {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .form-panel {
            overflow: hidden;
        }

        .form-head {
            padding: 28px 30px 20px;
            border-bottom: 1px solid var(--border);
        }

        .form-head h2 {
            margin: 6px 0 0;
            font-size: 1.8rem;
            line-height: 1.12;
            font-weight: 800;
        }

        .form-head p {
            margin: 8px 0 0;
            color: var(--muted);
            line-height: 1.55;
        }

        .registration-form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            padding: 28px 30px 30px;
        }

        .field {
            display: grid;
            gap: 7px;
            min-width: 0;
        }

        .field.full {
            grid-column: 1 / -1;
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

        .field-hint {
            margin: 0;
            color: var(--muted);
            font-size: 0.82rem;
            line-height: 1.4;
        }

        .field-error {
            margin: 0;
            color: var(--red-700);
            font-size: 0.82rem;
            font-weight: 700;
        }

        .field-error li {
            list-style: none;
        }

        .form-actions {
            grid-column: 1 / -1;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 4px;
        }

        @media (max-width: 900px) {
            .register-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 680px) {
            .page-shell {
                width: min(100% - 24px, 1120px);
                padding-top: 14px;
            }

            .site-header {
                align-items: stretch;
                flex-direction: column;
                padding-bottom: 18px;
            }

            .header-actions {
                justify-content: stretch;
            }

            .header-actions > * {
                flex: 1 1 140px;
            }

            .intro-panel,
            .form-head,
            .registration-form {
                padding-left: 18px;
                padding-right: 18px;
            }

            .intro-panel h1 {
                font-size: 2rem;
            }

            .registration-form {
                grid-template-columns: 1fr;
            }

            .form-actions {
                align-items: stretch;
                flex-direction: column-reverse;
            }
        }
    </style>
    <x-minimal-ui />
</head>
<body>
    <x-auth-session-status :status="session('status')" />

    <div class="page-shell">
        <header class="site-header">
            <a href="{{ url('/') }}" class="brand" aria-label="Back to Professor Tracking System home">
                <img src="{{ asset('images/isulogo.jpg') }}" alt="ISU logo" class="brand-logo">
                <span class="brand-copy">
                    <strong>Professor Tracking System</strong>
                    <span>Student account request</span>
                </span>
            </a>

            <div class="header-actions">
                <a href="{{ url('/') }}" class="header-link">Back to home</a>
                <a href="{{ route('login') }}" class="primary-button">Log in</a>
            </div>
        </header>

        <main class="register-grid">
            <section class="intro-panel" aria-labelledby="register-title">
                <div>
                    <span class="kicker">Student registration</span>
                    <h1 id="register-title">Request your student account.</h1>
                    <p class="intro-copy">Submit your information for admin review. Once approved, you can sign in with your student ID and the password you created.</p>
                </div>

                <div class="approval-list" aria-label="Registration flow">
                    <div class="approval-step">
                        <span class="step-number">1</span>
                        <span>
                            <strong class="step-label">Submit details</strong>
                            <p>Use your real student ID, full name, email, and department.</p>
                        </span>
                    </div>
                    <div class="approval-step">
                        <span class="step-number">2</span>
                        <span>
                            <strong class="step-label">Admin review</strong>
                            <p>Your request stays pending until the administrator approves or declines it.</p>
                        </span>
                    </div>
                    <div class="approval-step">
                        <span class="step-number">3</span>
                        <span>
                            <strong class="step-label">Sign in</strong>
                            <p>After approval, use your student ID and password to open the viewer.</p>
                        </span>
                    </div>
                </div>
            </section>

            <section class="form-panel" aria-labelledby="form-title">
                <div class="form-head">
                    <span class="kicker">Account details</span>
                    <h2 id="form-title">Student request form</h2>
                    <p>All fields are required.</p>
                </div>

                <form method="POST" action="{{ route('student.register.store') }}" class="registration-form" autocomplete="on">
                    @csrf

                    <div class="field">
                        <label for="student_id" class="field-label">Student ID</label>
                        <input id="student_id" class="field-input" type="text" name="student_id" value="{{ old('student_id') }}" required autofocus autocomplete="username" autocapitalize="none" spellcheck="false">
                        @error('student_id')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="full_name" class="field-label">Full name</label>
                        <input id="full_name" class="field-input" type="text" name="full_name" value="{{ old('full_name') }}" required autocomplete="name">
                        @error('full_name')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="email" class="field-label">Email</label>
                        <input id="email" class="field-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="department_id" class="field-label">Department</label>
                        <select id="department_id" name="department_id" class="field-input" required>
                            <option value="">Select department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password" class="field-label">Password</label>
                        <input id="password" class="field-input" type="password" name="password" required autocomplete="new-password" autocapitalize="none" spellcheck="false">
                        <x-password-strength for="password" confirmation="password_confirmation" />
                        @error('password')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation" class="field-label">Confirm password</label>
                        <input id="password_confirmation" class="field-input" type="password" name="password_confirmation" required autocomplete="new-password" autocapitalize="none" spellcheck="false">
                        @error('password_confirmation')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <a href="{{ url('/') }}" class="secondary-button">Cancel</a>
                        <button type="submit" class="primary-button">Submit request</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
