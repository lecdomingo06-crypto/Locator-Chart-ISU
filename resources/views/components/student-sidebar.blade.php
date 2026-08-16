@props(['active' => 'viewer'])

<aside class="student-sidebar">
    <div class="student-sidebar-brand">
        <div class="student-sidebar-mark" aria-hidden="true">
            <img src="{{ asset('images/isulogo.jpg') }}" alt="">
        </div>
        <div class="student-sidebar-copy">
            <strong>Student Panel</strong>
            <span>Viewer and account tools</span>
        </div>
    </div>

    <span class="student-nav-label">Workspace</span>

    <nav class="student-nav" aria-label="Student workspace">
        <a href="{{ route('student.viewer') }}" class="student-nav-link{{ $active === 'viewer' ? ' is-active' : '' }}" @if($active === 'viewer') aria-current="page" @endif>
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M2.5 12s3.5-5.5 9.5-5.5 9.5 5.5 9.5 5.5-3.5 5.5-9.5 5.5S2.5 12 2.5 12Z"></path>
                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"></path>
            </svg>
            <span>Live Viewer</span>
        </a>

        <a href="{{ route('student.dashboard') }}" class="student-nav-link{{ $active === 'map' ? ' is-active' : '' }}" @if($active === 'map') aria-current="page" @endif>
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="m4 6 5-2 6 2 5-2v14l-5 2-6-2-5 2V6Z"></path>
                <path d="M9 4v14M15 6v14"></path>
            </svg>
            <span>Campus Map</span>
        </a>

        <a href="{{ route('profile.edit') }}" class="student-nav-link{{ $active === 'profile' ? ' is-active' : '' }}" @if($active === 'profile') aria-current="page" @endif>
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"></path>
                <path d="M4.5 20c.8-3.8 3.4-5.8 7.5-5.8s6.7 2 7.5 5.8"></path>
            </svg>
            <span>Profile</span>
        </a>
    </nav>

    <x-sidebar-account-footer />
</aside>
<x-responsive-sidebar-control />

<style>
    .student-workspace {
        display: grid;
        grid-template-columns: var(--shell-sidebar-width, 268px) minmax(0, 1fr);
        min-height: 100vh;
    }

    .student-sidebar {
        position: sticky;
        top: 0;
        z-index: 20;
        display: flex;
        flex-direction: column;
        height: 100vh;
        padding: 24px 18px;
        border-right: 1px solid #d3e3d8;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: none;
    }

    .student-sidebar-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 4px 24px;
        border-bottom: 1px solid #d3e3d8;
    }

    .student-sidebar-mark {
        display: grid;
        place-items: center;
        width: 48px;
        height: 48px;
        flex: 0 0 48px;
        overflow: hidden;
        border-radius: 8px;
        background: #147247;
    }

    .student-sidebar-copy {
        display: grid;
        gap: 2px;
        min-width: 0;
    }

    .student-sidebar-copy strong {
        color: #173524;
        font-size: 0.94rem;
    }

    .student-sidebar-copy span,
    .student-sidebar-note {
        color: #607766;
        font-size: 0.82rem;
        line-height: 1.55;
    }

    .student-sidebar-note {
        margin: 18px 8px 24px;
    }

    .student-nav-label {
        margin: 30px 8px 16px;
        color: #7b8c81;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .student-nav {
        display: grid;
        gap: 10px;
    }

    .student-nav-link {
        display: flex;
        align-items: center;
        gap: 11px;
        min-height: 48px;
        padding: 0 15px;
        border-radius: 11px;
        color: #1c3728;
        font: 700 0.87rem/1.2 'Outfit', system-ui, sans-serif;
        text-decoration: none;
    }

    .student-nav-link:hover {
        background: #f4faf6;
    }

    .student-nav-link.is-active {
        color: #ffffff;
        background: #147247;
    }

    .student-nav-link svg {
        width: 19px;
        height: 19px;
        flex: 0 0 19px;
        fill: none;
        stroke: currentColor;
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .student-content {
        min-width: 0;
    }
</style>
