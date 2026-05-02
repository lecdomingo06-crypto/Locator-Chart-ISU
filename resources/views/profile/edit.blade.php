<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Teacher Tracker') }}</title>

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
                radial-gradient(circle at top left, rgba(118, 210, 149, 0.34), transparent 34%),
                radial-gradient(circle at 82% 18%, rgba(51, 153, 97, 0.28), transparent 18%),
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
            max-width: 1180px;
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
            grid-template-columns: minmax(320px, 0.9fr) minmax(0, 1.1fr);
            gap: 28px;
            align-items: stretch;
        }

        .preview-card,
        .form-card {
            position: relative;
            overflow: hidden;
            border-radius: 34px;
            box-shadow: var(--shadow);
        }

        .preview-card {
            padding: 28px;
            color: #eefcf2;
            background:
                radial-gradient(circle at top right, rgba(74, 222, 128, 0.24), transparent 28%),
                linear-gradient(180deg, #156941 0%, #0c5434 55%, #083924 100%);
        }

        .preview-card::before,
        .preview-card::after {
            content: '';
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        .preview-card::before {
            width: 16rem;
            height: 16rem;
            top: -6rem;
            right: -4rem;
            background: rgba(219, 255, 228, 0.12);
        }

        .preview-card::after {
            width: 14rem;
            height: 14rem;
            left: -4rem;
            bottom: -5rem;
            background: rgba(180, 250, 200, 0.08);
        }

        .preview-inner,
        .form-inner {
            position: relative;
            z-index: 1;
            display: grid;
            gap: 20px;
        }

        .panel-tag {
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

        .panel-tag::before {
            content: '';
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: #4ade80;
        }

        .preview-card h1,
        .form-card h1 {
            margin: 0;
            font-size: clamp(2rem, 4vw, 3rem);
            line-height: 0.98;
            letter-spacing: -0.04em;
        }

        .preview-card p {
            margin: 0;
            color: rgba(238, 252, 242, 0.76);
            line-height: 1.78;
        }

        .photo-frame {
            display: grid;
            place-items: center;
            width: min(100%, 280px);
            aspect-ratio: 1 / 1;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.14);
            overflow: hidden;
            box-shadow: 0 22px 36px rgba(8, 57, 36, 0.18);
        }

        .photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            display: grid;
            place-items: center;
            width: 100%;
            height: 100%;
            color: #effcf3;
            font-size: 4.2rem;
            font-weight: 800;
            letter-spacing: -0.05em;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0.04));
        }

        .preview-note {
            display: grid;
            gap: 10px;
            padding: 18px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .preview-note span {
            color: rgba(238, 252, 242, 0.66);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .preview-note strong {
            font-size: 1rem;
            line-height: 1.6;
        }

        .form-card {
            padding: 32px;
            background: var(--card);
            border: 1px solid var(--card-border);
            backdrop-filter: blur(16px);
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

        .form-card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.75;
        }

        .alert-success,
        .alert-error {
            padding: 14px 16px;
            border-radius: 18px;
            font-size: 0.96rem;
            font-weight: 600;
        }

        .alert-success {
            color: #116537;
            background: rgba(217, 242, 226, 0.92);
            border: 1px solid rgba(25, 138, 82, 0.16);
        }

        .alert-error {
            color: #b42318;
            background: rgba(254, 228, 226, 0.92);
            border: 1px solid rgba(217, 45, 32, 0.12);
        }

        .upload-form {
            display: grid;
            gap: 20px;
        }

        .field {
            display: grid;
            gap: 10px;
        }

        .field label {
            color: var(--green-900);
            font-size: 0.84rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .file-input {
            width: 100%;
            padding: 12px;
            border-radius: 18px;
            border: 1px solid rgba(12, 92, 56, 0.14);
            background: rgba(255, 255, 255, 0.84);
            color: var(--text);
            font: inherit;
        }

        .file-input::file-selector-button {
            margin-right: 14px;
            padding: 10px 16px;
            border: 0;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--green-800), var(--green-900));
            color: #fff;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }

        .file-help {
            color: var(--muted);
            font-size: 0.94rem;
            line-height: 1.7;
        }

        .actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            align-items: center;
        }

        .primary-button,
        .secondary-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 54px;
            padding: 0 22px;
            border-radius: 18px;
            font: inherit;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .primary-button {
            border: 0;
            color: #fff;
            background: linear-gradient(135deg, var(--green-800), var(--green-900));
            box-shadow: 0 18px 30px rgba(12, 92, 56, 0.22);
            cursor: pointer;
        }

        .secondary-link {
            color: var(--green-900);
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(12, 92, 56, 0.14);
            box-shadow: 0 12px 24px rgba(14, 76, 46, 0.08);
        }

        .primary-button:hover,
        .secondary-link:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 980px) {
            .layout {
                grid-template-columns: 1fr;
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

            .preview-card,
            .form-card {
                padding: 22px;
                border-radius: 28px;
            }

            .actions {
                flex-direction: column;
                align-items: stretch;
            }

            .primary-button,
            .secondary-link {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="shell">
            <section class="topbar">
                <div class="brand">
                    <div class="brand-mark" aria-hidden="true"></div>
                    <div class="brand-copy">
                        <strong>Teacher Tracking System</strong>
                        <span>Profile settings for teachers and faculty</span>
                    </div>
                </div>

                <div class="status-pill">Profile Settings</div>
            </section>

            <main class="layout">
                <section class="preview-card">
                    <div class="preview-inner">
                        <div class="panel-tag">Current Profile</div>
                        <h1>Edit Profile Picture</h1>
                        <p>
                            Keep your profile picture updated so students and staff can recognize you more easily
                            across the viewer and dashboard.
                        </p>

                        <div class="photo-frame">
                            @if($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture">
                            @else
                                <div class="photo-placeholder" aria-hidden="true">
                                    {{ strtoupper(substr($user->full_name ?? auth()->user()->role ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="preview-note">
                            <span>Current Profile Picture</span>
                            <strong>Your current photo is shown here so you can review it before uploading a new one.</strong>
                        </div>
                    </div>
                </section>

                <section class="form-card">
                    <div class="form-inner">
                        <div class="eyebrow">Upload Photo</div>
                        <h1>Change your profile image</h1>
                        <p>Select a new profile picture and upload it using the same secure profile update flow.</p>

                        @if(session('success'))
                            <div class="alert-success">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="upload-form">
                            @csrf

                            <div class="field">
                                <label for="profile_picture">Choose Profile Picture</label>
                                <input type="file" name="profile_picture" id="profile_picture" class="file-input">
                                <div class="file-help">Use a clear image so your profile is easier to identify in the teacher viewer.</div>
                                @error('profile_picture')
                                    <div class="alert-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="actions">
                                <button type="submit" class="primary-button">Upload</button>

                                @if(auth()->user()->role === 'teacher')
                                    <a href="{{ route('teacher.dashboard') }}" class="secondary-link">Back to Teacher Dashboard</a>
                                @elseif(auth()->user()->role === 'faculty')
                                    <a href="{{ route('faculty.dashboard') }}" class="secondary-link">Back to Faculty Dashboard</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
