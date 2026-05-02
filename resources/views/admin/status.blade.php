<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Status Override</title>

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
            max-width: 50ch;
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.75;
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

        .override-form {
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

        .field label {
            color: var(--green-900);
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .field input,
        .field select {
            width: 100%;
            min-height: 56px;
            padding: 0 18px;
            border: 1px solid rgba(18, 53, 36, 0.12);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.92);
            color: var(--text);
            font: inherit;
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.6);
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .field input:focus,
        .field select:focus {
            outline: none;
            border-color: rgba(20, 114, 71, 0.44);
            box-shadow: 0 0 0 4px rgba(29, 138, 84, 0.12);
        }

        .picker-stack {
            position: relative;
            display: grid;
            gap: 10px;
        }

        .native-user-select {
            display: none;
        }

        .user-picker-trigger {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            width: 100%;
            min-height: 56px;
            padding: 0 18px;
            border: 1px solid rgba(18, 53, 36, 0.12);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.92);
            color: var(--text);
            font: inherit;
            text-align: left;
            cursor: pointer;
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.6);
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .user-picker-trigger:hover,
        .user-picker-trigger.is-open {
            border-color: rgba(20, 114, 71, 0.44);
            box-shadow: 0 0 0 4px rgba(29, 138, 84, 0.12);
        }

        .user-picker-trigger span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .picker-chevron {
            flex: 0 0 auto;
            color: var(--green-900);
            font-size: 0.9rem;
            transition: transform 0.2s ease;
        }

        .user-picker-trigger.is-open .picker-chevron {
            transform: rotate(180deg);
        }

        .user-picker-panel {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            z-index: 20;
            display: grid;
            gap: 10px;
            padding: 14px;
            border-radius: 20px;
            border: 1px solid rgba(18, 53, 36, 0.12);
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 22px 44px rgba(12, 92, 56, 0.16);
        }

        .user-picker-panel[hidden] {
            display: none;
        }

        .user-picker-search {
            width: 100%;
            min-height: 48px;
            padding: 0 16px;
            border: 1px solid rgba(18, 53, 36, 0.12);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.96);
            color: var(--text);
            font: inherit;
            outline: none;
        }

        .user-picker-search:focus {
            border-color: rgba(20, 114, 71, 0.44);
            box-shadow: 0 0 0 4px rgba(29, 138, 84, 0.12);
        }

        .user-picker-list {
            display: grid;
            gap: 8px;
            max-height: 240px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .user-picker-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            width: 100%;
            padding: 12px 14px;
            border: 1px solid rgba(20, 114, 71, 0.08);
            border-radius: 14px;
            background: rgba(221, 244, 228, 0.4);
            color: var(--text);
            font: inherit;
            text-align: left;
            cursor: pointer;
            transition: background 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
        }

        .user-picker-option:hover,
        .user-picker-option.is-active {
            background: rgba(221, 244, 228, 0.72);
            border-color: rgba(20, 114, 71, 0.18);
            transform: translateY(-1px);
        }

        .user-picker-option[hidden] {
            display: none;
        }

        .user-picker-empty {
            margin: 0;
            padding: 10px 4px 2px;
            color: var(--muted);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .user-picker-empty[hidden] {
            display: none;
        }

        .picker-hint {
            margin: 0;
            color: var(--muted);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .form-actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            align-items: center;
            padding-top: 6px;
        }

        .primary-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 52px;
            padding: 0 22px;
            border: none;
            border-radius: 18px;
            color: #fff;
            background: linear-gradient(135deg, var(--green-800), var(--green-900));
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 18px 30px rgba(12, 92, 56, 0.22);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .primary-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 22px 34px rgba(12, 92, 56, 0.26);
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

            .primary-button {
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
                        <span>Admin workspace for override control</span>
                    </div>
                </div>

                <div class="status-pill">Status Override</div>
            </section>

            <section class="content">
                <section class="form-card">
                    <div class="form-inner">
                        <div class="eyebrow">Admin Control</div>
                        <h1>Admin Status Override</h1>
                        <p class="intro">Apply a temporary manual status to a selected user from one cleaner control form.</p>

                        @if(session('success'))
                            <div class="success-alert">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('admin.status.store') }}" class="override-form">
                            @csrf

                            <div class="field-grid">
                                <div class="field full">
                                    <label for="user_id">Select User</label>
                                    <div class="picker-stack">
                                        <button
                                            id="user_picker_trigger"
                                            type="button"
                                            class="user-picker-trigger"
                                            aria-haspopup="listbox"
                                            aria-expanded="false"
                                        >
                                            <span id="user_picker_label">Select user</span>
                                            <span class="picker-chevron">v</span>
                                        </button>

                                        <div id="user_picker_panel" class="user-picker-panel" hidden>
                                            <input
                                                id="user_picker_search"
                                                type="text"
                                                class="user-picker-search"
                                                placeholder="Search teacher or faculty name"
                                                autocomplete="off"
                                            >

                                            <div id="user_picker_list" class="user-picker-list" role="listbox">
                                                @foreach($users as $user)
                                                    <button
                                                        type="button"
                                                        class="user-picker-option"
                                                        data-value="{{ $user->id }}"
                                                        data-label="{{ $user->full_name }}"
                                                    >
                                                        <span>{{ $user->full_name }}</span>
                                                    </button>
                                                @endforeach
                                            </div>

                                            <p id="user_picker_empty" class="user-picker-empty" hidden>No matching users found.</p>
                                        </div>

                                        <select id="user_id" name="user_id" class="native-user-select">
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                                            @endforeach
                                        </select>

                                        <p class="picker-hint">Open the dropdown and type to search inside the list.</p>
                                    </div>
                                </div>

                                <div class="field full">
                                    <label for="status">Status</label>
                                    <input id="status" type="text" name="status" placeholder="e.g. In Meeting">
                                </div>

                                <div class="field">
                                    <label for="start_datetime">Start</label>
                                    <input id="start_datetime" type="datetime-local" name="start_datetime">
                                </div>

                                <div class="field">
                                    <label for="end_datetime">End</label>
                                    <input id="end_datetime" type="datetime-local" name="end_datetime">
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="primary-button">Apply Override</button>
                            </div>
                        </form>
                    </div>
                </section>

                <aside class="info-card">
                    <div class="info-inner">
                        <div class="info-tag">Override Guide</div>
                        <h2>Adjust staff visibility clearly.</h2>
                        <p>Use this panel when an admin needs to temporarily control how a staff member appears in the live system.</p>

                        <div class="info-grid">
                            <div class="info-item">
                                <span>Select user</span>
                                <strong>Pick the teacher or faculty member who needs a manual override.</strong>
                            </div>

                            <div class="info-item">
                                <span>Set status</span>
                                <strong>Enter the temporary label you want the live viewer to show.</strong>
                            </div>

                            <div class="info-item">
                                <span>Time window</span>
                                <strong>Choose when the override starts and when it should end.</strong>
                            </div>
                        </div>
                    </div>
                </aside>
            </section>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const userPickerTrigger = document.getElementById('user_picker_trigger');
        const userPickerLabel = document.getElementById('user_picker_label');
        const userPickerPanel = document.getElementById('user_picker_panel');
        const userPickerSearch = document.getElementById('user_picker_search');
        const userPickerEmpty = document.getElementById('user_picker_empty');
        const userSelect = document.getElementById('user_id');
        const userOptions = Array.from(document.querySelectorAll('.user-picker-option'));

        if (!userPickerTrigger || !userPickerLabel || !userPickerPanel || !userPickerSearch || !userPickerEmpty || !userSelect || !userOptions.length) {
            return;
        }

        function syncLabel() {
            const selectedOption = userSelect.options[userSelect.selectedIndex];
            userPickerLabel.textContent = selectedOption ? selectedOption.textContent : 'Select user';
        }

        function closePicker() {
            userPickerPanel.hidden = true;
            userPickerTrigger.classList.remove('is-open');
            userPickerTrigger.setAttribute('aria-expanded', 'false');
        }

        function openPicker() {
            userPickerPanel.hidden = false;
            userPickerTrigger.classList.add('is-open');
            userPickerTrigger.setAttribute('aria-expanded', 'true');
            userPickerSearch.focus();
            userPickerSearch.select();
        }

        function filterPickerOptions() {
            const query = userPickerSearch.value.trim().toLowerCase();
            let visibleCount = 0;

            userOptions.forEach(function (optionButton) {
                const matches = optionButton.dataset.label.toLowerCase().includes(query);
                optionButton.hidden = !matches;

                if (matches) {
                    visibleCount += 1;
                }
            });

            userPickerEmpty.hidden = visibleCount !== 0;
        }

        userOptions.forEach(function (optionButton) {
            optionButton.addEventListener('click', function () {
                userSelect.value = optionButton.dataset.value;

                userOptions.forEach(function (button) {
                    button.classList.remove('is-active');
                });

                optionButton.classList.add('is-active');
                syncLabel();
                closePicker();
            });
        });

        userPickerTrigger.addEventListener('click', function () {
            if (userPickerPanel.hidden) {
                openPicker();
                filterPickerOptions();
            } else {
                closePicker();
            }
        });

        userPickerSearch.addEventListener('input', filterPickerOptions);

        document.addEventListener('click', function (event) {
            if (!event.target.closest('.picker-stack')) {
                closePicker();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closePicker();
            }
        });

        if (userSelect.options.length > 0 && !userSelect.value) {
            userSelect.selectedIndex = 0;
        }

        userOptions.forEach(function (optionButton) {
            if (optionButton.dataset.value === userSelect.value) {
                optionButton.classList.add('is-active');
            }
        });

        syncLabel();
    });
    </script>
</body>
</html>
