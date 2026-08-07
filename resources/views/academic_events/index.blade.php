<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Professor Tracker') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800" rel="stylesheet" />
    <style>
        :root { color-scheme: light; --bg-top:#eef9f1; --bg-bottom:#dff1e4; --card:rgba(255,255,255,.84); --card-border:rgba(255,255,255,.8); --text:#123524; --muted:#5a7261; --green-900:#0c5c38; --green-800:#147247; --shadow:0 22px 52px rgba(13,72,43,.12); }
        * { box-sizing: border-box; }
        html, body { margin:0; min-height:100%; }
        body { font-family:'Outfit',sans-serif; color:var(--text); background:radial-gradient(circle at top left, rgba(118,210,149,.3), transparent 30%), radial-gradient(circle at 82% 18%, rgba(51,153,97,.24), transparent 18%), linear-gradient(145deg,var(--bg-top),var(--bg-bottom)); }
        body::before, body::after { content:''; position:fixed; z-index:0; border-radius:999px; filter:blur(12px); pointer-events:none; }
        body::before { width:28rem; height:28rem; top:-8rem; right:-7rem; background:rgba(42,162,90,.16); }
        body::after { width:24rem; height:24rem; left:-6rem; bottom:-8rem; background:rgba(15,92,56,.1); }
        .page { position:relative; z-index:1; min-height:100vh; padding:28px; }
        .shell { max-width:1200px; margin:0 auto; display:grid; gap:22px; }
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
        .hero { display:grid; grid-template-columns:minmax(0,1.05fr) minmax(320px,.95fr); gap:24px; align-items:stretch; }
        .hero-card, .summary-card, .event-card, .empty-state { position:relative; overflow:hidden; border-radius:32px; box-shadow:var(--shadow); }
        .hero-card { padding:30px; background:var(--card); border:1px solid var(--card-border); backdrop-filter:blur(16px); }
        .hero-card::before { content:''; position:absolute; left:-8%; bottom:-14%; width:20rem; height:20rem; border-radius:46% 54% 58% 42%; background:linear-gradient(180deg, rgba(29,138,87,.14), rgba(12,92,56,.04)); }
        .hero-inner, .summary-inner { position:relative; z-index:1; display:grid; gap:20px; }
        .eyebrow { display:inline-flex; align-items:center; gap:10px; width:fit-content; padding:10px 16px; border-radius:999px; background:rgba(12,92,56,.08); color:var(--green-900); font-size:.84rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; }
        .eyebrow::before { content:''; width:28px; height:1px; background:rgba(12,92,56,.32); }
        h1 { margin:0; font-size:clamp(2.4rem, 5vw, 4rem); line-height:.96; letter-spacing:-.05em; }
        .hero-card p { margin:0; max-width:56ch; color:var(--muted); font-size:1rem; line-height:1.8; }
        .hero-actions, .card-actions, .badge-row, .section-meta { display:flex; gap:12px; flex-wrap:wrap; align-items:center; }
        .primary-link, .secondary-link, .edit-link, .delete-button { display:inline-flex; align-items:center; justify-content:center; min-height:48px; padding:0 20px; border-radius:16px; font:inherit; font-weight:700; text-decoration:none; transition:transform .2s ease, box-shadow .2s ease, background .2s ease; }
        .primary-link, .edit-link { color:#fff; background:linear-gradient(135deg,var(--green-800),var(--green-900)); box-shadow:0 18px 30px rgba(12,92,56,.18); }
        .secondary-link { color:var(--green-900); background:rgba(255,255,255,.82); border:1px solid rgba(12,92,56,.14); box-shadow:0 12px 24px rgba(14,76,46,.08); }
        .delete-button { border:1px solid rgba(185,28,28,.12); color:#991b1b; background:rgba(255,255,255,.84); box-shadow:0 12px 24px rgba(127,29,29,.08); cursor:pointer; }
        .primary-link:hover, .secondary-link:hover, .edit-link:hover, .delete-button:hover { transform:translateY(-2px); }
        .summary-card { padding:26px; color:#eefcf2; background:radial-gradient(circle at top right, rgba(74,222,128,.24), transparent 28%), linear-gradient(180deg,#156941 0%, #0c5434 55%, #083924 100%); }
        .summary-card::before, .summary-card::after { content:''; position:absolute; border-radius:999px; pointer-events:none; }
        .summary-card::before { width:16rem; height:16rem; top:-6rem; right:-4rem; background:rgba(219,255,228,.12); }
        .summary-card::after { width:14rem; height:14rem; left:-4rem; bottom:-5rem; background:rgba(180,250,200,.08); }
        .summary-tag { display:inline-flex; align-items:center; gap:8px; width:fit-content; padding:9px 14px; border-radius:999px; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.1); font-size:.84rem; font-weight:700; }
        .summary-tag::before { content:''; width:9px; height:9px; border-radius:999px; background:#4ade80; }
        .summary-card h2 { margin:0; font-size:clamp(1.8rem, 4vw, 2.8rem); line-height:1; letter-spacing:-.04em; }
        .summary-card p { margin:0; color:rgba(238,252,242,.78); line-height:1.72; }
        .summary-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; }
        .summary-item { padding:16px; border-radius:18px; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.12); }
        .summary-item span, .detail-card span, .section-copy span { display:block; color:rgba(238,252,242,.66); font-size:.8rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; }
        .summary-item strong { display:block; margin-top:8px; font-size:1rem; line-height:1.55; }
        .success-alert { padding:14px 16px; border-radius:18px; color:#116537; background:rgba(217,242,226,.92); border:1px solid rgba(25,138,82,.16); font-size:.96rem; font-weight:600; }
        .section-block { display:grid; gap:18px; }
        .section-head { display:flex; align-items:end; justify-content:space-between; gap:16px; padding:2px 2px 0; }
        .section-copy { display:grid; gap:6px; }
        .section-copy span { color:var(--green-800); font-size:.78rem; }
        .section-copy h2 { margin:0; font-size:1.2rem; letter-spacing:-.03em; }
        .section-pill { display:inline-flex; align-items:center; padding:8px 12px; border-radius:999px; background:rgba(221,244,228,.8); border:1px solid rgba(20,114,71,.1); color:var(--green-900); font-size:.82rem; font-weight:600; }
        .event-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:18px; }
        .event-card, .empty-state { border:1px solid var(--card-border); background:var(--card); backdrop-filter:blur(18px); }
        .event-card { display:grid; gap:18px; padding:22px; background:linear-gradient(180deg, rgba(255,255,255,.96), rgba(244,251,246,.96)); transition:transform .2s ease, box-shadow .2s ease; }
        .event-card::before { content:''; position:absolute; inset:0 0 auto 0; height:4px; background:linear-gradient(90deg,var(--green-800),#4dc97d); }
        .event-card:hover { transform:translateY(-2px); box-shadow:0 22px 44px rgba(12,92,56,.12); }
        .event-head { display:flex; align-items:flex-start; justify-content:space-between; gap:14px; }
        .event-copy { display:grid; gap:8px; }
        .event-copy h3 { margin:0; font-size:1.2rem; letter-spacing:-.03em; }
        .event-copy p { margin:0; color:var(--muted); line-height:1.55; }
        .type-badge, .scope-badge { display:inline-flex; align-items:center; justify-content:center; min-height:34px; padding:0 12px; border-radius:999px; color:#fff; font-size:.78rem; font-weight:700; letter-spacing:.05em; text-transform:uppercase; }
        .detail-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px; }
        .detail-card { padding:14px; border-radius:16px; background:rgba(221,244,228,.52); border:1px solid rgba(20,114,71,.1); }
        .detail-card.full { grid-column:1 / -1; }
        .detail-card span { color:var(--muted); font-size:.78rem; }
        .detail-card strong { display:block; margin-top:6px; font-size:.96rem; letter-spacing:-.02em; line-height:1.6; }
        .delete-form { display:inline-flex; }
        .empty-state { padding:34px; text-align:center; }
        .empty-state h3 { margin:0 0 10px; font-size:1.45rem; letter-spacing:-.03em; }
        .empty-state p { margin:0; color:var(--muted); line-height:1.8; }
        @media (max-width:980px) { .hero { grid-template-columns:1fr; } }
        @media (max-width:860px) { .section-head { align-items:flex-start; flex-direction:column; } .section-meta { justify-content:flex-start; } }
        @media (max-width:720px) { .page { padding:18px; } .topbar { padding:16px 18px; border-radius:24px; flex-direction:column; align-items:flex-start; } .hero-card, .summary-card { padding:22px; border-radius:28px; } .hero-actions, .card-actions { flex-direction:column; align-items:stretch; } .primary-link, .secondary-link, .edit-link, .delete-button { width:100%; } .detail-grid, .summary-grid { grid-template-columns:1fr; } .event-head { flex-direction:column; align-items:flex-start; } }
    </style>
    <x-minimal-ui />
</head>
<body>
@php
    $sections = [
        ['label' => 'Active Events', 'title' => 'Running Right Now', 'events' => $activeEvents, 'meta' => 'Live event priority window', 'empty_title' => 'No active academic events.', 'empty_text' => 'Active school-wide or scoped events will appear here as soon as their start time begins.'],
        ['label' => 'Upcoming Events', 'title' => 'Scheduled Next', 'events' => $upcomingEvents, 'meta' => 'Future academic event queue', 'empty_title' => 'No upcoming academic events.', 'empty_text' => 'Create an event when you need to plan holidays, suspensions, or scoped department activities.'],
        ['label' => 'Past Events', 'title' => 'Recently Finished', 'events' => $pastEvents, 'meta' => 'Completed event history', 'empty_title' => 'No past academic events yet.', 'empty_text' => 'Finished events will appear here after their end time passes.'],
    ];
    $typeStyles = ['Holiday' => 'background: linear-gradient(135deg, #b45309, #d97706);', 'Class Suspension' => 'background: linear-gradient(135deg, #b91c1c, #dc2626);', 'No Classes' => 'background: linear-gradient(135deg, #0f766e, #0891b2);', 'University Event' => 'background: linear-gradient(135deg, #1d4ed8, #2563eb);', 'Department Activity' => 'background: linear-gradient(135deg, #6d28d9, #7c3aed);'];
    $scopeStyles = ['all' => 'background: linear-gradient(135deg, #147247, #0c5c38);', 'professors' => 'background: linear-gradient(135deg, #0f766e, #147247);', 'faculty' => 'background: linear-gradient(135deg, #2563eb, #1d4ed8);', 'department' => 'background: linear-gradient(135deg, #7c3aed, #5b21b6);'];
@endphp
    <div class="page">
        <div class="shell">
            <section class="topbar">
                <div class="brand">
                    <div class="brand-mark" aria-hidden="true"></div>
                    <div class="brand-copy">
                        <strong>Professor Tracking System</strong>
                        <span>Global academic event manager</span>
                    </div>
                </div>
                <div class="status-pill">Academic Events Module</div>
            </section>
            <section class="hero">
                <section class="hero-card">
                    <div class="hero-inner">
                        <div class="eyebrow">Academic Events</div>
                        <h1>Global events that affect live status.</h1>
                        <p>Manage holidays, class suspensions, no-classes periods, university events, and scoped department activities from one clear admin page.</p>
                        <x-flash-toast />
                        <div class="hero-actions">
                            <a href="{{ route('academic_events.create') }}" class="primary-link">Add Academic Event</a>
                            <a href="{{ route('admin.dashboard') }}" class="secondary-link">Back to Admin Dashboard</a>
                        </div>
                    </div>
                </section>
                <aside class="summary-card">
                    <div class="summary-inner">
                        <div class="summary-tag">Event Overview</div>
                        <h2>School-wide status control, scheduled ahead.</h2>
                        <p>Active events automatically affect viewer status priority, while upcoming and past events stay easy to review from the same manager.</p>
                        <div class="summary-grid">
                            <div class="summary-item"><span>Active</span><strong>{{ $activeEvents->count() }} event{{ $activeEvents->count() === 1 ? '' : 's' }}</strong></div>
                            <div class="summary-item"><span>Upcoming</span><strong>{{ $upcomingEvents->count() }} planned</strong></div>
                            <div class="summary-item"><span>Past</span><strong>{{ $pastEvents->count() }} archived</strong></div>
                        </div>
                    </div>
                </aside>
            </section>
            @foreach($sections as $section)
                <section class="section-block">
                    <div class="section-head">
                        <div class="section-copy">
                            <span>{{ $section['label'] }}</span>
                            <h2>{{ $section['title'] }}</h2>
                        </div>
                        <div class="section-meta">
                            <div class="section-pill">{{ $section['events']->count() }} event{{ $section['events']->count() === 1 ? '' : 's' }}</div>
                            <div class="section-pill">{{ $section['meta'] }}</div>
                        </div>
                    </div>
                    <div class="event-grid">
                        @forelse($section['events'] as $event)
                            <article class="event-card">
                                <div class="event-head">
                                    <div class="event-copy">
                                        <h3>{{ $event->title }}</h3>
                                        <p>{{ $event->note ?: 'Academic event entry for viewer status priority.' }}</p>
                                    </div>
                                    <div class="badge-row">
                                        <div class="type-badge" style="{{ $typeStyles[$event->type] ?? 'background: linear-gradient(135deg, #147247, #0c5c38);' }}">{{ $event->type }}</div>
                                        <div class="scope-badge" style="{{ $scopeStyles[$event->scope] ?? 'background: linear-gradient(135deg, #147247, #0c5c38);' }}">{{ $event->scope === 'department' ? 'Department' : ucfirst($event->scope) }}</div>
                                    </div>
                                </div>
                                <div class="detail-grid">
                                    <div class="detail-card"><span>Start</span><strong>{{ $event->start_datetime->format('M d, Y g:i A') }}</strong></div>
                                    <div class="detail-card"><span>End</span><strong>{{ $event->end_datetime->format('M d, Y g:i A') }}</strong></div>
                                    <div class="detail-card"><span>Scope</span><strong>{{ $event->scope_label }}</strong></div>
                                    <div class="detail-card"><span>Department</span><strong>{{ $event->department?->name ?? 'Not department-specific' }}</strong></div>
                                    <div class="detail-card full"><span>Note</span><strong>{{ $event->note ?: 'No note provided for this academic event.' }}</strong></div>
                                    <div class="detail-card full"><span>Purpose</span><strong>{{ $event->purpose ?: 'No purpose provided for this academic event.' }}</strong></div>
                                </div>
                                <div class="card-actions">
                                    <a href="{{ route('academic_events.edit', $event) }}" class="edit-link">Edit</a>
                                    <form method="POST" action="{{ route('academic_events.destroy', $event) }}" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-button" onclick="return confirm('Delete this academic event?')">Delete</button>
                                    </form>
                                </div>
                            </article>
                        @empty
                            <section class="empty-state">
                                <h3>{{ $section['empty_title'] }}</h3>
                                <p>{{ $section['empty_text'] }}</p>
                            </section>
                        @endforelse
                    </div>
                </section>
            @endforeach
        </div>
    </div>
</body>
</html>
