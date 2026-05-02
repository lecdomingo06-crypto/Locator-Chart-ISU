<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Teacher Tracker') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800" rel="stylesheet" />
    <style>
        :root { color-scheme: light; --bg-top:#eef9f1; --bg-bottom:#dff1e4; --card:rgba(255,255,255,.84); --card-border:rgba(255,255,255,.8); --text:#123524; --muted:#5a7261; --green-900:#0c5c38; --green-800:#147247; --shadow:0 22px 52px rgba(13,72,43,.12); --danger:#b91c1c; --danger-soft:rgba(254,226,226,.9); }
        * { box-sizing:border-box; }
        html, body { margin:0; min-height:100%; }
        body { font-family:'Outfit',sans-serif; color:var(--text); background:radial-gradient(circle at top left, rgba(118,210,149,.3), transparent 30%), radial-gradient(circle at 82% 18%, rgba(51,153,97,.22), transparent 18%), linear-gradient(145deg,var(--bg-top),var(--bg-bottom)); }
        body::before, body::after { content:''; position:fixed; z-index:0; border-radius:999px; filter:blur(12px); pointer-events:none; }
        body::before { width:26rem; height:26rem; top:-8rem; right:-7rem; background:rgba(42,162,90,.16); }
        body::after { width:22rem; height:22rem; left:-6rem; bottom:-8rem; background:rgba(15,92,56,.1); }
        .page { position:relative; z-index:1; min-height:100vh; padding:28px; }
        .shell { max-width:1160px; margin:0 auto; display:grid; gap:22px; }
        .topbar { display:flex; align-items:center; justify-content:space-between; gap:18px; padding:18px 22px; border-radius:28px; background:rgba(255,255,255,.56); border:1px solid rgba(255,255,255,.78); backdrop-filter:blur(18px); box-shadow:0 12px 36px rgba(16,70,45,.08); }
        .brand { display:inline-flex; align-items:center; gap:16px; }
        .brand-mark { position:relative; width:54px; height:54px; border-radius:18px; background:linear-gradient(160deg,#25a760,#0c5c38); box-shadow:inset 0 1px 0 rgba(255,255,255,.32); }
        .brand-mark::before, .brand-mark::after { content:''; position:absolute; border-radius:999px; background:rgba(255,255,255,.95); }
        .brand-mark::before { width:14px; height:14px; left:11px; top:13px; box-shadow:18px 0 0 rgba(255,255,255,.95); }
        .brand-mark::after { width:30px; height:12px; left:12px; bottom:13px; border-radius:999px 999px 14px 14px; }
        .brand-copy { display:grid; gap:4px; }
        .brand-copy strong { font-size:1.15rem; letter-spacing:-.02em; }
        .brand-copy span { color:var(--muted); font-size:.95rem; }
        .status-pill { display:inline-flex; align-items:center; gap:10px; padding:10px 16px; border-radius:999px; color:var(--green-900); background:rgba(217,242,226,.86); border:1px solid rgba(25,138,82,.14); font-size:.95rem; font-weight:600; }
        .status-pill::before { content:''; width:10px; height:10px; border-radius:999px; background:#22c55e; box-shadow:0 0 0 6px rgba(34,197,94,.14); }
        .content { display:grid; grid-template-columns:minmax(0,1.08fr) minmax(300px,.92fr); gap:24px; align-items:start; }
        .form-card, .info-card { position:relative; overflow:hidden; border-radius:32px; box-shadow:var(--shadow); }
        .form-card { padding:30px; background:var(--card); border:1px solid var(--card-border); backdrop-filter:blur(16px); }
        .form-card::before { content:''; position:absolute; left:-8%; bottom:-14%; width:20rem; height:20rem; border-radius:46% 54% 58% 42%; background:linear-gradient(180deg, rgba(29,138,87,.14), rgba(12,92,56,.04)); }
        .form-inner, .info-inner { position:relative; z-index:1; display:grid; gap:20px; }
        .eyebrow { display:inline-flex; align-items:center; gap:10px; width:fit-content; padding:10px 16px; border-radius:999px; background:rgba(12,92,56,.08); color:var(--green-900); font-size:.84rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; }
        .eyebrow::before { content:''; width:28px; height:1px; background:rgba(12,92,56,.32); }
        h1 { margin:0; font-size:clamp(2.2rem, 4vw, 3.4rem); line-height:.98; letter-spacing:-.05em; }
        .intro { margin:0; max-width:50ch; color:var(--muted); font-size:1rem; line-height:1.75; }
        .error-summary { display:grid; gap:8px; padding:16px 18px; border-radius:20px; color:#7f1d1d; background:var(--danger-soft); border:1px solid rgba(185,28,28,.14); }
        .error-summary strong { font-size:.98rem; }
        .error-summary ul { margin:0; padding-left:18px; display:grid; gap:4px; }
        .event-form { display:grid; gap:18px; }
        .field-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px; }
        .field { display:grid; gap:8px; }
        .field.full { grid-column:1 / -1; }
        .field label { color:var(--green-900); font-size:.82rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; }
        .field input, .field select, .field textarea { width:100%; min-height:56px; padding:0 18px; border:1px solid rgba(18,53,36,.12); border-radius:18px; background:rgba(255,255,255,.92); color:var(--text); font:inherit; box-shadow:inset 0 1px 1px rgba(255,255,255,.6); transition:border-color .2s ease, box-shadow .2s ease; }
        .field textarea { min-height:132px; padding-top:14px; padding-bottom:14px; resize:vertical; }
        .field input:focus, .field select:focus, .field textarea:focus { outline:none; border-color:rgba(20,114,71,.44); box-shadow:0 0 0 4px rgba(29,138,84,.12); }
        .field-error { margin:0; color:var(--danger); font-size:.9rem; font-weight:600; }
        .form-actions { display:flex; gap:14px; flex-wrap:wrap; align-items:center; padding-top:6px; }
        .primary-button, .secondary-link { display:inline-flex; align-items:center; justify-content:center; min-height:52px; padding:0 22px; border-radius:18px; font:inherit; font-weight:700; text-decoration:none; transition:transform .2s ease, box-shadow .2s ease, background .2s ease; }
        .primary-button { border:none; color:#fff; cursor:pointer; background:linear-gradient(135deg,var(--green-800),var(--green-900)); box-shadow:0 18px 30px rgba(12,92,56,.22); }
        .secondary-link { color:var(--green-900); background:rgba(255,255,255,.82); border:1px solid rgba(12,92,56,.14); box-shadow:0 12px 24px rgba(14,76,46,.08); }
        .primary-button:hover, .secondary-link:hover { transform:translateY(-2px); }
        .info-card { padding:26px; color:#eefcf2; background:radial-gradient(circle at top right, rgba(74,222,128,.24), transparent 28%), linear-gradient(180deg,#156941 0%, #0c5434 55%, #083924 100%); }
        .info-card::before, .info-card::after { content:''; position:absolute; border-radius:999px; pointer-events:none; }
        .info-card::before { width:16rem; height:16rem; top:-6rem; right:-4rem; background:rgba(219,255,228,.12); }
        .info-card::after { width:14rem; height:14rem; left:-4rem; bottom:-5rem; background:rgba(180,250,200,.08); }
        .info-tag { display:inline-flex; align-items:center; gap:8px; width:fit-content; padding:9px 14px; border-radius:999px; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.1); font-size:.84rem; font-weight:700; }
        .info-tag::before { content:''; width:9px; height:9px; border-radius:999px; background:#4ade80; }
        .info-card h2 { margin:0; font-size:clamp(1.8rem, 4vw, 2.8rem); line-height:1; letter-spacing:-.04em; }
        .info-card p { margin:0; color:rgba(238,252,242,.78); line-height:1.72; }
        .info-grid { display:grid; gap:12px; }
        .info-item { padding:16px; border-radius:18px; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.12); }
        .info-item span { display:block; color:rgba(238,252,242,.66); font-size:.8rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; }
        .info-item strong { display:block; margin-top:8px; font-size:1rem; line-height:1.55; }
        @media (max-width:960px) { .content { grid-template-columns:1fr; } }
        @media (max-width:720px) { .page { padding:18px; } .topbar { padding:16px 18px; border-radius:24px; flex-direction:column; align-items:flex-start; } .form-card, .info-card { padding:22px; border-radius:28px; } .field-grid { grid-template-columns:1fr; } .form-actions { flex-direction:column; align-items:stretch; } .primary-button, .secondary-link { width:100%; } }
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
                        <span>Academic event manager</span>
                    </div>
                </div>
                <div class="status-pill">Edit academic event</div>
            </section>
            <section class="content">
                <section class="form-card">
                    <div class="form-inner">
                        <div class="eyebrow">Academic Events</div>
                        <h1>Edit Academic Event</h1>
                        <p class="intro">Update the event timing, scope, or label while keeping the same live-status workflow.</p>
                        @if($errors->any())
                            <section class="error-summary" aria-label="Validation errors">
                                <strong>Please review the highlighted academic event details.</strong>
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </section>
                        @endif
                        <form method="POST" action="{{ route('academic_events.update', $academicEvent) }}" class="event-form">
                            @csrf
                            @method('PUT')
                            <div class="field-grid">
                                <div class="field full">
                                    <label for="title">Title</label>
                                    <input id="title" type="text" name="title" value="{{ old('title', $academicEvent->title) }}" placeholder="Enter event title">
                                    @error('title') <p class="field-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="field">
                                    <label for="type">Type</label>
                                    <select id="type" name="type">
                                        <option value="">Select event type</option>
                                        @foreach(\App\Models\AcademicEvent::TYPE_OPTIONS as $type)
                                            <option value="{{ $type }}" {{ old('type', $academicEvent->type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                    @error('type') <p class="field-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="field">
                                    <label for="scope">Scope</label>
                                    <select id="scope" name="scope">
                                        <option value="">Select scope</option>
                                        @foreach(\App\Models\AcademicEvent::SCOPE_OPTIONS as $scope)
                                            <option value="{{ $scope }}" {{ old('scope', $academicEvent->scope) === $scope ? 'selected' : '' }}>{{ $scope === 'all' ? 'All teachers and faculty' : ucfirst($scope) }}</option>
                                        @endforeach
                                    </select>
                                    @error('scope') <p class="field-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="field">
                                    <label for="start_datetime">Start Date and Time</label>
                                    <input id="start_datetime" type="datetime-local" name="start_datetime" value="{{ old('start_datetime', $academicEvent->start_datetime?->format('Y-m-d\\TH:i')) }}">
                                    @error('start_datetime') <p class="field-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="field">
                                    <label for="end_datetime">End Date and Time</label>
                                    <input id="end_datetime" type="datetime-local" name="end_datetime" value="{{ old('end_datetime', $academicEvent->end_datetime?->format('Y-m-d\\TH:i')) }}">
                                    @error('end_datetime') <p class="field-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="field full" id="department_field_wrapper">
                                    <label for="department_id">Department</label>
                                    <select id="department_id" name="department_id">
                                        <option value="">Select department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ (string) old('department_id', $academicEvent->department_id) === (string) $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id') <p class="field-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="field full">
                                    <label for="note">Note</label>
                                    <textarea id="note" name="note" placeholder="Add an optional note or description">{{ old('note', $academicEvent->note) }}</textarea>
                                    @error('note') <p class="field-error">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="primary-button">Update Academic Event</button>
                                <a href="{{ route('academic_events.index') }}" class="secondary-link">Back to Academic Events</a>
                            </div>
                        </form>
                    </div>
                </section>
                <aside class="info-card">
                    <div class="info-inner">
                        <div class="info-tag">Edit Guide</div>
                        <h2>Refine the event without changing the module flow.</h2>
                        <p>Use this editor to adjust timing, scope, or notes when an academic event needs an update.</p>
                        <div class="info-grid">
                            <div class="info-item"><span>Current entry</span><strong>{{ $academicEvent->title }} is saved as a {{ strtolower($academicEvent->type) }} event.</strong></div>
                            <div class="info-item"><span>Scope target</span><strong>{{ $academicEvent->scope_label }}</strong></div>
                            <div class="info-item"><span>Live effect</span><strong>During its active window, matching users can automatically show this event in the viewer.</strong></div>
                        </div>
                    </div>
                </aside>
            </section>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const scopeField = document.getElementById('scope');
        const departmentWrapper = document.getElementById('department_field_wrapper');
        const departmentField = document.getElementById('department_id');
        if (!scopeField || !departmentWrapper || !departmentField) { return; }
        function syncDepartmentField() {
            const needsDepartment = scopeField.value === 'department';
            departmentWrapper.style.display = needsDepartment ? 'grid' : 'none';
            departmentField.disabled = !needsDepartment;
        }
        // Only show the department selector when the event targets one department.
        syncDepartmentField();
        scopeField.addEventListener('change', syncDepartmentField);
    });
    </script>
</body>
</html>
