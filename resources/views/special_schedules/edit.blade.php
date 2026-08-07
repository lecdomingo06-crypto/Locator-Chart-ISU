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
            --shadow: 0 22px 52px rgba(13, 72, 43, 0.12);
            --danger: #b91c1c;
            --danger-soft: rgba(254, 226, 226, 0.9);
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
                radial-gradient(circle at 82% 18%, rgba(51, 153, 97, 0.22), transparent 18%),
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
            width: 26rem;
            height: 26rem;
            top: -8rem;
            right: -7rem;
            background: rgba(42, 162, 90, 0.16);
        }

        body::after {
            width: 22rem;
            height: 22rem;
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
            max-width: 1160px;
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

        .content {
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(300px, 0.92fr);
            gap: 24px;
            align-items: start;
        }

        .form-card,
        .info-card {
            position: relative;
            overflow: hidden;
            border-radius: 32px;
            box-shadow: var(--shadow);
        }

        .form-card {
            padding: 30px;
            background: var(--card);
            border: 1px solid var(--card-border);
            backdrop-filter: blur(16px);
        }

        .form-card::before {
            content: '';
            position: absolute;
            left: -8%;
            bottom: -14%;
            width: 20rem;
            height: 20rem;
            border-radius: 46% 54% 58% 42%;
            background: linear-gradient(180deg, rgba(29, 138, 87, 0.14), rgba(12, 92, 56, 0.04));
        }

        .form-inner,
        .info-inner {
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
            font-size: clamp(2.2rem, 4vw, 3.4rem);
            line-height: 0.98;
            letter-spacing: -0.05em;
        }

        .intro {
            margin: 0;
            max-width: 48ch;
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.75;
        }

        .error-summary {
            display: grid;
            gap: 8px;
            padding: 16px 18px;
            border-radius: 20px;
            color: #7f1d1d;
            background: var(--danger-soft);
            border: 1px solid rgba(185, 28, 28, 0.14);
        }

        .error-summary strong {
            font-size: 0.98rem;
        }

        .error-summary ul {
            margin: 0;
            padding-left: 18px;
            display: grid;
            gap: 4px;
        }

        .schedule-form {
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
        }

        .field.full {
            grid-column: 1 / -1;
        }

        .field[hidden] {
            display: none !important;
        }

        .field label {
            color: var(--green-900);
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .field input,
        .field select,
        .field textarea {
            width: 100%;
            min-height: 56px;
            padding: 14px 18px;
            border: 1px solid rgba(18, 53, 36, 0.12);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.92);
            color: var(--text);
            font: inherit;
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.6);
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .field textarea {
            min-height: 138px;
            resize: vertical;
        }

        .field input:focus,
        .field select:focus,
        .field textarea:focus {
            outline: none;
            border-color: rgba(20, 114, 71, 0.44);
            box-shadow: 0 0 0 4px rgba(29, 138, 84, 0.12);
        }

        .field-error {
            margin: 0;
            color: var(--danger);
            font-size: 0.9rem;
            font-weight: 600;
        }

        .toggle-field {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 18px;
            border: 1px solid rgba(18, 53, 36, 0.12);
            border-radius: 20px;
            background: rgba(232, 245, 236, 0.56);
        }

        .toggle-field input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-top: 2px;
            accent-color: var(--green-800);
            flex: 0 0 auto;
        }

        .toggle-copy {
            display: grid;
            gap: 6px;
        }

        .toggle-copy strong {
            font-size: 0.98rem;
            letter-spacing: -0.02em;
        }

        .toggle-copy span {
            color: var(--muted);
            font-size: 0.94rem;
            line-height: 1.6;
        }

        .form-actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            align-items: center;
            padding-top: 6px;
        }

        .primary-button,
        .secondary-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 52px;
            padding: 0 22px;
            border-radius: 18px;
            font: inherit;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .primary-button {
            border: none;
            color: #fff;
            cursor: pointer;
            background: linear-gradient(135deg, var(--green-800), var(--green-900));
            box-shadow: 0 18px 30px rgba(12, 92, 56, 0.22);
        }

        .secondary-link {
            color: var(--green-900);
            background: rgba(255, 255, 255, 0.82);
            border: 1px solid rgba(12, 92, 56, 0.14);
            box-shadow: 0 12px 24px rgba(14, 76, 46, 0.08);
        }

        .primary-button:hover,
        .secondary-link:hover {
            transform: translateY(-2px);
        }

        .info-card {
            padding: 26px;
            color: #eefcf2;
            background:
                radial-gradient(circle at top right, rgba(74, 222, 128, 0.24), transparent 28%),
                linear-gradient(180deg, #156941 0%, #0c5434 55%, #083924 100%);
        }

        .info-card::before,
        .info-card::after {
            content: '';
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        .info-card::before {
            width: 16rem;
            height: 16rem;
            top: -6rem;
            right: -4rem;
            background: rgba(219, 255, 228, 0.12);
        }

        .info-card::after {
            width: 14rem;
            height: 14rem;
            left: -4rem;
            bottom: -5rem;
            background: rgba(180, 250, 200, 0.08);
        }

        .info-tag {
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

        .info-tag::before {
            content: '';
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: #4ade80;
        }

        .info-card h2 {
            margin: 0;
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            line-height: 1;
            letter-spacing: -0.04em;
        }

        .info-card p {
            margin: 0;
            color: rgba(238, 252, 242, 0.78);
            line-height: 1.72;
        }

        .info-grid {
            display: grid;
            gap: 12px;
        }

        .info-item {
            padding: 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .info-item span {
            display: block;
            color: rgba(238, 252, 242, 0.66);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .info-item strong {
            display: block;
            margin-top: 8px;
            font-size: 1rem;
            line-height: 1.55;
        }

        @media (max-width: 960px) {
            .content {
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

            .form-card,
            .info-card {
                padding: 22px;
                border-radius: 28px;
            }

            .field-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .primary-button,
            .secondary-link {
                width: 100%;
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
                        <span>Special schedule manager</span>
                    </div>
                </div>

                <div class="status-pill">Edit special entry</div>
            </section>

            <section class="content">
                <section class="form-card">
                    <div class="form-inner">
                        <div class="eyebrow">Special Schedule</div>
                        <h1>Edit Special Schedule</h1>
                        <p class="intro">Refine the selected exception, adjust the time range, and keep the note clear so the availability view stays accurate.</p>

                        @if($errors->any())
                            <section class="error-summary" aria-label="Validation errors">
                                <strong>Please review the highlighted schedule details.</strong>
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </section>
                        @endif

                        <form method="POST" action="{{ route('special_schedules.update', $specialSchedule) }}" class="schedule-form">
                            @csrf
                            @method('PUT')

                            <div class="field-grid">
                                <div class="field full">
                                    <label for="type">Type</label>
                                    <select id="type" name="type">
                                        <option value="">Select Type</option>
                                        <option value="On Leave" {{ old('type', $specialSchedule->type) == 'On Leave' ? 'selected' : '' }}>On Leave</option>
                                        <option value="Emergency" {{ old('type', $specialSchedule->type) == 'Emergency' ? 'selected' : '' }}>Emergency</option>
                                        <option value="On Meeting" {{ old('type', $specialSchedule->type) == 'On Meeting' ? 'selected' : '' }}>On Meeting</option>
                                    </select>
                                    @error('type')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="field">
                                    <label for="start_datetime">Start Date &amp; Time</label>
                                    <input
                                        id="start_datetime"
                                        type="datetime-local"
                                        name="start_datetime"
                                        value="{{ old('start_datetime', \Carbon\Carbon::parse($specialSchedule->start_datetime)->format('Y-m-d\TH:i')) }}">
                                    @error('start_datetime')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="field">
                                    <label for="end_datetime">End Date &amp; Time</label>
                                    <input
                                        id="end_datetime"
                                        type="datetime-local"
                                        name="end_datetime"
                                        value="{{ old('end_datetime', \Carbon\Carbon::parse($specialSchedule->end_datetime)->format('Y-m-d\TH:i')) }}">
                                    @error('end_datetime')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="field full">
                                    <label for="note">Note / Reason</label>
                                    <textarea id="note" name="note" placeholder="Add a short reason or context">{{ old('note', $specialSchedule->note) }}</textarea>
                                    @error('note')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                @if(auth()->user()->role === 'professor')
                                    <div
                                        id="keep_until_schedule_end_field"
                                        class="field full"
                                        style="{{ old('type', $specialSchedule->type) === 'On Meeting' ? '' : 'display: none;' }}"
                                        {{ old('type', $specialSchedule->type) === 'On Meeting' ? '' : 'hidden' }}>
                                        <label for="keep_until_schedule_end">If you will also miss the rest of your class</label>
                                        <label class="toggle-field" for="keep_until_schedule_end">
                                            <input
                                                id="keep_until_schedule_end"
                                                type="checkbox"
                                                name="keep_until_schedule_end"
                                                value="1"
                                                {{ old('keep_until_schedule_end', $specialSchedule->keep_until_schedule_end) ? 'checked' : '' }}>
                                            <div class="toggle-copy">
                                                <strong>Keep this status until the current class period ends</strong>
                                                <span>Turn this on if you will not return to class after this meeting. If it overlaps your current class period, the system will extend the end time to the class end instead of switching back to <code>In Class</code>.</span>
                                            </div>
                                        </label>
                                        @error('keep_until_schedule_end')
                                            <p class="field-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="primary-button">Update Special Schedule</button>
                                <a href="{{ route('special_schedules.index') }}" class="secondary-link">Back to Special Schedules</a>
                            </div>
                        </form>
                    </div>
                </section>

                <aside class="info-card">
                    <div class="info-inner">
                        <div class="info-tag">Edit Guide</div>
                        <h2>Keep updates polished.</h2>
                        <p>Use this page to correct dates, change the exception type, or clarify the note without recreating the special schedule from scratch.</p>

                        <div class="info-grid">
                            <div class="info-item">
                                <span>Current type</span>
                                <strong>Switch between On Leave, Emergency, or On Meeting as plans change.</strong>
                            </div>

                            <div class="info-item">
                                <span>Time range</span>
                                <strong>Review the start and end date carefully so the availability status appears at the right time.</strong>
                            </div>

                            <div class="info-item">
                                <span>Note update</span>
                                <strong>Refresh the reason when a meeting, absence, or emergency detail needs more context.</strong>
                            </div>

                            @if(auth()->user()->role === 'professor')
                                <div class="info-item">
                                    <span>Missed class</span>
                                    <strong>Use the extra option when the professor will also be absent for the remaining part of the current class period.</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </aside>
            </section>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeField = document.getElementById('type');
        const carryField = document.getElementById('keep_until_schedule_end_field');
        const carryCheckbox = document.getElementById('keep_until_schedule_end');

        if (!typeField || !carryField || !carryCheckbox) {
            return;
        }

        function syncCarryField() {
            const showCarryField = typeField.value === 'On Meeting';

            carryField.hidden = !showCarryField;
            carryField.style.display = showCarryField ? '' : 'none';
            carryCheckbox.disabled = !showCarryField;

            if (!showCarryField) {
                carryCheckbox.checked = false;
            }
        }

        syncCarryField();
        typeField.addEventListener('change', syncCarryField);
    });
    </script>
</body>
</html>
