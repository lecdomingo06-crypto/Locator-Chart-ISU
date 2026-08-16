<button type="button" class="mobile-nav-toggle" aria-label="Open navigation" aria-expanded="false" data-mobile-nav-toggle>
    <svg class="mobile-nav-open-icon" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M4 7h16M4 12h16M4 17h16"></path>
    </svg>
    <svg class="mobile-nav-close-icon" viewBox="0 0 24 24" aria-hidden="true">
        <path d="m6 6 12 12M18 6 6 18"></path>
    </svg>
</button>
<div class="mobile-nav-overlay" data-mobile-nav-overlay></div>

<style data-sidebar-consistency>
    :root {
        --shell-sidebar-bg: #ffffff;
        --shell-sidebar-active: #147247;
        --shell-sidebar-hover: #f4faf6;
        --shell-topbar-bg: #0c5c38;
        --shell-page-bg: #eef7f1;
        --shell-border: #d3e3d8;
        --shell-muted: #607766;
        --shell-sidebar-width: 268px;
    }

    .admin-shell,
    .workspace-shell {
        width: 100% !important;
        max-width: none !important;
        margin: 0 !important;
    }

    .workspace-layout,
    .admin-layout {
        min-height: 100vh !important;
    }

    .admin-topbar,
    .workspace-topbar {
        display: none !important;
    }

    .admin-brand,
    .workspace-brand,
    .admin-session,
    .workspace-session,
    .sidebar-brand,
    .student-sidebar-brand,
    .sidebar-link,
    .student-nav-link {
        display: flex !important;
        align-items: center !important;
    }

    .admin-brand,
    .workspace-brand {
        gap: 12px !important;
        color: inherit !important;
        text-decoration: none !important;
    }

    .admin-brand-mark,
    .workspace-brand-mark {
        display: grid !important;
        place-items: center !important;
        width: 44px !important;
        height: 44px !important;
        flex: 0 0 44px !important;
        overflow: hidden !important;
        border: 0 !important;
        border-radius: 12px !important;
        background: #ffffff !important;
        box-shadow: none !important;
    }

    .admin-brand-copy,
    .workspace-brand-copy,
    .sidebar-copy,
    .student-sidebar-copy {
        display: grid !important;
        gap: 2px !important;
        min-width: 0 !important;
    }

    .admin-brand-copy strong,
    .workspace-brand-copy strong {
        color: #ffffff !important;
        font-size: 1rem !important;
        line-height: 1.2 !important;
    }

    .admin-brand-copy span,
    .workspace-brand-copy span {
        color: rgba(255, 255, 255, 0.82) !important;
        font-size: 0.82rem !important;
        line-height: 1.25 !important;
        opacity: 1 !important;
    }

    .admin-session,
    .workspace-session {
        justify-content: flex-end !important;
        gap: 10px !important;
        flex-wrap: wrap !important;
    }

    .admin-chip,
    .workspace-chip {
        min-height: 36px !important;
        padding: 0 13px !important;
        border: 1px solid rgba(255, 255, 255, 0.18) !important;
        border-radius: 12px !important;
        color: #ffffff !important;
        background: rgba(255, 255, 255, 0.1) !important;
        font-size: 0.8rem !important;
        font-weight: 700 !important;
    }

    .admin-layout,
    .workspace-layout,
    .student-workspace {
        display: grid !important;
        grid-template-columns: var(--shell-sidebar-width) minmax(0, 1fr) !important;
        gap: 0 !important;
        background: var(--shell-page-bg) !important;
    }

    .admin-main,
    .workspace-main,
    .student-content,
    .profile-main {
        min-width: 0 !important;
    }

    .admin-sidebar,
    .workspace-sidebar,
    .student-sidebar {
        z-index: 20 !important;
        width: var(--shell-sidebar-width) !important;
        min-width: var(--shell-sidebar-width) !important;
        margin: 0 !important;
        padding: 24px 18px !important;
        border: 0 !important;
        border-right: 1px solid var(--shell-border) !important;
        border-radius: 0 !important;
        color: #1c3728 !important;
        background: var(--shell-sidebar-bg) !important;
        box-shadow: 12px 0 28px rgba(12, 92, 56, 0.06) !important;
    }

    .sidebar-brand,
    .student-sidebar-brand {
        gap: 12px !important;
        padding: 0 4px 28px !important;
        border-bottom: 1px solid var(--shell-border) !important;
    }

    .sidebar-mark,
    .student-sidebar-mark {
        display: grid !important;
        place-items: center !important;
        width: 48px !important;
        height: 48px !important;
        flex: 0 0 48px !important;
        overflow: hidden !important;
        border: 1px solid rgba(12, 92, 56, 0.08) !important;
        border-radius: 12px !important;
        color: #ffffff !important;
        background: #147247 !important;
        box-shadow: none !important;
    }

    .sidebar-copy strong,
    .student-sidebar-copy strong {
        overflow: hidden !important;
        color: #173524 !important;
        font-size: 0.94rem !important;
        line-height: 1.2 !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
    }

    .sidebar-copy span,
    .student-sidebar-copy span,
    .sidebar-note,
    .student-sidebar-note {
        color: var(--shell-muted) !important;
        font-size: 0.82rem !important;
        line-height: 1.55 !important;
        opacity: 1 !important;
    }

    .sidebar-note,
    .student-sidebar-note {
        margin: 18px 8px 24px !important;
    }

    .sidebar-section-label,
    .student-nav-label {
        display: block !important;
        margin: 30px 8px 16px !important;
        color: #7b8c81 !important;
        font-size: 0.7rem !important;
        font-weight: 800 !important;
        letter-spacing: 0.08em !important;
        text-transform: uppercase !important;
    }

    .sidebar-nav,
    .student-nav {
        display: grid !important;
        grid-template-columns: 1fr !important;
        gap: 10px !important;
        width: 100% !important;
    }

    .sidebar-link,
    .student-nav-link {
        justify-content: flex-start !important;
        gap: 11px !important;
        width: 100% !important;
        min-height: 48px !important;
        padding: 0 15px !important;
        border: 1px solid transparent !important;
        border-radius: 11px !important;
        color: #1c3728 !important;
        background: transparent !important;
        font: 700 0.87rem/1.2 'Outfit', system-ui, sans-serif !important;
        text-decoration: none !important;
        box-shadow: none !important;
    }

    .sidebar-link:hover,
    .student-nav-link:hover {
        color: #0c5c38 !important;
        background: var(--shell-sidebar-hover) !important;
    }

    .sidebar-link.is-active,
    .student-nav-link.is-active {
        color: #ffffff !important;
        border-color: rgba(20, 114, 71, 0.12) !important;
        background: var(--shell-sidebar-active) !important;
        box-shadow: 0 10px 20px rgba(12, 92, 56, 0.12) !important;
    }

    .sidebar-link svg,
    .student-nav-link svg {
        width: 19px !important;
        height: 19px !important;
        flex: 0 0 19px !important;
        fill: none !important;
        stroke: currentColor !important;
        stroke-width: 1.8 !important;
        stroke-linecap: round !important;
        stroke-linejoin: round !important;
    }

    .admin-brand-mark img,
    .workspace-brand-mark img,
    .sidebar-mark img,
    .student-sidebar-mark img {
        display: block;
        width: 100%;
        height: 100%;
        padding: 3px;
        border-radius: inherit;
        background: #ffffff;
        object-fit: contain;
    }

    .sidebar-account-footer {
        border-top-color: rgba(12, 92, 56, 0.16) !important;
        color: #173524 !important;
    }

    .sidebar-account-identity strong {
        color: #173524 !important;
    }

    .sidebar-account-identity span {
        color: var(--shell-muted) !important;
    }

    .sidebar-account-logout button {
        border-color: rgba(20, 114, 71, 0.32) !important;
        color: #0c5c38 !important;
        background: #ffffff !important;
    }

    .sidebar-account-logout button:hover {
        background: #eef8f1 !important;
    }

    .mobile-nav-toggle,
    .mobile-nav-overlay {
        display: none;
    }

    @media (min-width: 841px) {
        .admin-topbar,
        .workspace-topbar {
            box-sizing: border-box !important;
            width: calc(100% - var(--shell-sidebar-width)) !important;
            margin-left: var(--shell-sidebar-width) !important;
            justify-content: flex-end !important;
        }

        .admin-brand,
        .workspace-brand {
            display: none !important;
        }

        .admin-layout,
        .workspace-layout,
        .student-workspace {
            align-items: start !important;
        }

        .admin-sidebar,
        .workspace-sidebar,
        .student-sidebar {
            align-self: start !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 60 !important;
            display: flex !important;
            flex-direction: column !important;
            min-height: 0 !important;
            overflow-x: hidden !important;
            overflow-y: auto !important;
        }

        .admin-sidebar,
        .workspace-sidebar {
            height: 100vh !important;
            max-height: 100vh !important;
            margin-top: 0 !important;
        }

        .student-sidebar {
            height: 100vh !important;
            max-height: 100vh !important;
        }
    }

    @media (max-width: 840px) {
        body.mobile-nav-open {
            overflow: hidden;
        }

        .mobile-nav-toggle {
            position: fixed;
            top: 12px;
            left: 12px;
            z-index: 1201;
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            padding: 0;
            border: 1px solid rgba(255, 255, 255, 0.28);
            border-radius: 7px;
            color: #ffffff;
            background: #0c5c38;
            box-shadow: 0 10px 24px rgba(8, 58, 35, 0.24);
            cursor: pointer;
        }

        .mobile-nav-toggle svg {
            width: 21px;
            height: 21px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .mobile-nav-close-icon {
            display: none;
        }

        body.mobile-nav-open .mobile-nav-open-icon {
            display: none;
        }

        body.mobile-nav-open .mobile-nav-close-icon {
            display: block;
        }

        .mobile-nav-overlay {
            position: fixed;
            inset: 0;
            z-index: 1099;
            display: block;
            visibility: hidden;
            opacity: 0;
            background: rgba(7, 29, 18, 0.48);
            backdrop-filter: blur(2px);
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }

        body.mobile-nav-open .mobile-nav-overlay {
            visibility: visible;
            opacity: 1;
        }

        .admin-layout,
        .workspace-layout,
        .student-workspace {
            grid-template-columns: minmax(0, 1fr) !important;
        }

        .admin-sidebar,
        .workspace-sidebar,
        .student-sidebar {
            position: fixed !important;
            inset: 0 auto 0 0 !important;
            z-index: 1100 !important;
            display: flex !important;
            flex-direction: column !important;
            width: min(300px, 86vw) !important;
            height: 100dvh !important;
            min-height: 100dvh !important;
            margin: 0 !important;
            padding: 68px 16px 20px !important;
            overflow-x: hidden !important;
            overflow-y: auto !important;
            border: 0 !important;
            border-right: 1px solid rgba(12, 92, 56, 0.16) !important;
            border-radius: 0 !important;
            background: #ffffff !important;
            box-shadow: 18px 0 40px rgba(8, 58, 35, 0.2) !important;
            transform: translateX(-104%);
            transition: transform 0.22s ease;
        }

        body.mobile-nav-open .admin-sidebar,
        body.mobile-nav-open .workspace-sidebar,
        body.mobile-nav-open .student-sidebar {
            transform: translateX(0);
        }

        .sidebar-nav,
        .student-nav {
            display: grid !important;
            grid-template-columns: 1fr !important;
            width: 100%;
            gap: 6px !important;
            margin-top: 0 !important;
        }

        .sidebar-link,
        .student-nav-link {
            justify-content: flex-start !important;
            width: 100%;
        }

        .sidebar-note,
        .sidebar-section-label,
        .student-sidebar-note,
        .student-nav-label {
            display: block !important;
        }

        .student-sidebar-note {
            margin: 18px 8px 22px !important;
        }

        .student-account,
        .sidebar-account-footer {
            margin-top: auto !important;
        }

        .admin-topbar,
        .workspace-topbar {
            min-height: 66px !important;
            padding: 10px 14px 10px 68px !important;
            align-items: center !important;
            flex-direction: row !important;
        }

        .admin-session,
        .workspace-session {
            display: none !important;
        }

        .admin-brand,
        .workspace-brand {
            min-width: 0;
            gap: 11px !important;
        }

        .admin-brand-mark,
        .workspace-brand-mark {
            width: 42px !important;
            height: 42px !important;
            flex-basis: 42px !important;
        }

        .admin-brand-copy,
        .workspace-brand-copy {
            min-width: 0;
        }

        .admin-brand-copy strong,
        .workspace-brand-copy strong {
            display: block;
            overflow: hidden;
            font-size: 0.94rem !important;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .admin-brand-copy span,
        .workspace-brand-copy span {
            display: none !important;
        }

        .student-content {
            padding-top: 54px;
        }

        .status-overview {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 8px !important;
            padding: 12px !important;
            border-radius: 10px !important;
        }

        .status-item {
            min-height: 66px;
            align-content: center;
            gap: 4px !important;
            padding: 10px 12px !important;
            border-radius: 8px !important;
        }

        .status-item span {
            font-size: 0.66rem !important;
            letter-spacing: 0.04em !important;
            line-height: 1.2;
        }

        .status-item strong {
            font-size: 1.15rem !important;
            letter-spacing: 0 !important;
            line-height: 1;
        }

        .viewer-grid {
            grid-template-columns: 1fr !important;
            gap: 10px !important;
        }

        .viewer-card {
            grid-template-rows: none !important;
            height: auto !important;
            min-height: 0 !important;
            gap: 8px !important;
            padding: 12px !important;
            border-radius: 10px !important;
        }

        .viewer-head,
        .viewer-profile {
            align-items: center !important;
            flex-direction: row !important;
            gap: 10px !important;
            min-height: 48px;
        }

        .avatar,
        .avatar-placeholder {
            width: 48px !important;
            height: 48px !important;
            flex-basis: 48px !important;
            border-radius: 9px !important;
        }

        .avatar-placeholder {
            font-size: 1rem !important;
        }

        .viewer-title h3 {
            font-size: 0.98rem !important;
            letter-spacing: 0 !important;
            line-height: 1.2 !important;
        }

        .viewer-title p {
            margin-top: 2px !important;
            font-size: 0.76rem !important;
            line-height: 1.3 !important;
            -webkit-line-clamp: 1 !important;
        }

        .meta-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 6px !important;
        }

        .meta-card {
            min-height: 54px !important;
            padding: 8px 10px !important;
            border-radius: 8px !important;
        }

        .meta-card span {
            font-size: 0.64rem !important;
            letter-spacing: 0.04em !important;
        }

        .meta-card strong {
            margin-top: 3px !important;
            font-size: 0.8rem !important;
        }

        .status-row {
            align-items: center !important;
            flex-direction: row !important;
            min-height: 54px !important;
            gap: 8px !important;
            padding: 8px 10px !important;
            border-radius: 8px !important;
        }

        .status-row strong {
            font-size: 0.66rem !important;
            letter-spacing: 0.04em !important;
        }

        .status-row > div:first-child span {
            display: none !important;
        }

        .status-badge {
            min-width: 0 !important;
            max-width: 55% !important;
            padding: 7px 10px !important;
            font-size: 0.68rem !important;
            letter-spacing: 0.02em !important;
        }

        .detail-button {
            min-height: 36px !important;
            margin-top: 0 !important;
            border-radius: 8px !important;
            font-size: 0.76rem !important;
        }

        .viewer-card .class-card {
            padding: 8px 10px !important;
            border-radius: 8px !important;
        }

        .viewer-card .class-card > strong {
            margin-bottom: 4px !important;
            font-size: 0.68rem !important;
            letter-spacing: 0.04em !important;
        }

        .viewer-card .class-details {
            gap: 4px !important;
        }

        .viewer-card .class-line {
            align-items: center !important;
            flex-direction: row !important;
            font-size: 0.78rem !important;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .admin-sidebar,
        .workspace-sidebar,
        .student-sidebar,
        .mobile-nav-overlay {
            transition: none !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.querySelector('[data-mobile-nav-toggle]');
        const overlay = document.querySelector('[data-mobile-nav-overlay]');
        const sidebar = document.querySelector('.admin-sidebar, .workspace-sidebar, .student-sidebar');

        if (!toggle || !overlay || !sidebar) {
            return;
        }

        const setOpen = function (open) {
            document.body.classList.toggle('mobile-nav-open', open);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            toggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
        };

        toggle.addEventListener('click', function () {
            setOpen(!document.body.classList.contains('mobile-nav-open'));
        });

        overlay.addEventListener('click', function () {
            setOpen(false);
        });

        sidebar.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                setOpen(false);
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                setOpen(false);
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 840) {
                setOpen(false);
            }
        });
    });
</script>
