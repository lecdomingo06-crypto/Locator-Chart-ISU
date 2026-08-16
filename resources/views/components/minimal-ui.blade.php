<style data-minimal-ui>
    :root {
        color-scheme: light;
        --minimal-page: #f5f8f5;
        --minimal-surface: #ffffff;
        --minimal-soft: #f0f6f2;
        --minimal-border: #dbe8df;
        --minimal-border-strong: #bfd7c9;
        --minimal-text: #10251a;
        --minimal-muted: #617367;
        --minimal-green: #0d7145;
        --minimal-green-dark: #085b38;
        --minimal-danger: #b42318;
        --minimal-warning: #9a6400;
        --minimal-shadow: 0 10px 28px rgba(12, 72, 43, 0.08);
    }

    * {
        letter-spacing: 0 !important;
    }

    html,
    body {
        background: var(--minimal-page) !important;
    }

    body {
        color: var(--minimal-text) !important;
        font-family: 'Outfit', 'Figtree', system-ui, sans-serif !important;
    }

    body::before,
    body::after,
    .page::before,
    .page::after,
    .auth-focus-scene,
    .auth-focus-scene::after,
    .auth-focus-scene-inner {
        display: none !important;
    }

    .bg-gradient-to-br,
    .from-green-50,
    .via-white,
    .to-amber-50 {
        background-image: none !important;
        background-color: var(--minimal-page) !important;
    }

    .page,
    .admin-main,
    .workspace-main,
    .student-content,
    .content-area,
    .dashboard-content,
    .profile-content,
    .app-main,
    .min-h-screen {
        background: var(--minimal-page) !important;
    }

    .page,
    .admin-main,
    .workspace-main,
    .student-content {
        padding: 24px !important;
    }

    .shell,
    .page-shell,
    .content-shell,
    .student-shell,
    .dashboard-shell,
    .profile-shell {
        width: min(1240px, 100%) !important;
        max-width: 1240px !important;
        margin-inline: auto !important;
        gap: 18px !important;
    }

    .admin-topbar,
    .workspace-topbar {
        border-radius: 0 !important;
        background: #0b603c !important;
        box-shadow: none !important;
        border: 0 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.16) !important;
    }

    .site-header {
        border: 0 !important;
        border-radius: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
    }

    .admin-layout,
    .workspace-layout,
    .student-workspace {
        grid-template-columns: var(--shell-sidebar-width, 268px) minmax(0, 1fr) !important;
        background: var(--minimal-page) !important;
    }

    .admin-sidebar,
    .workspace-sidebar,
    .student-sidebar {
        width: var(--shell-sidebar-width, 268px) !important;
        min-width: var(--shell-sidebar-width, 268px) !important;
        padding: 24px 18px !important;
        border-radius: 0 !important;
        border-right: 1px solid var(--minimal-border) !important;
        background: var(--minimal-surface) !important;
        box-shadow: none !important;
    }

    .sidebar-brand,
    .student-sidebar-brand {
        gap: 12px !important;
        padding: 0 4px 28px !important;
        border-bottom: 1px solid var(--minimal-border) !important;
    }

    .admin-brand-mark,
    .workspace-brand-mark,
    .sidebar-mark,
    .student-sidebar-mark,
    .brand-logo,
    .brand-mark,
    .auth-campus-icon,
    .login-campus-icon {
        border-radius: 8px !important;
        box-shadow: none !important;
    }

    .brand-mark,
    .sidebar-mark,
    .student-sidebar-mark {
        background: var(--minimal-green) !important;
    }

    .sidebar-note,
    .student-sidebar-note,
    .auth-copy,
    .panel-copy,
    .page-copy,
    .section-copy,
    .empty-copy,
    .field-hint {
        color: var(--minimal-muted) !important;
        line-height: 1.55 !important;
    }

    .sidebar-note,
    .student-sidebar-note {
        margin: 16px 8px 20px !important;
        font-size: 0.8rem !important;
    }

    .sidebar-nav,
    .student-nav {
        gap: 10px !important;
    }

    .sidebar-link,
    .student-nav-link {
        min-height: 48px !important;
        padding: 0 15px !important;
        border-radius: 11px !important;
        color: var(--minimal-text) !important;
        background: transparent !important;
        font-size: 0.86rem !important;
        font-weight: 700 !important;
    }

    .sidebar-link:hover,
    .student-nav-link:hover {
        background: var(--minimal-soft) !important;
    }

    .sidebar-link.is-active,
    .student-nav-link.is-active,
    .nav-link.active,
    .nav-link.is-active {
        color: #ffffff !important;
        background: var(--minimal-green) !important;
    }

    .summary-card,
    .event-card,
    .empty-state,
    .toolbar,
    .table-panel,
    .detail-panel,
    .detail-head,
    .panel,
    .panel-card,
    .form-panel,
    .form-card,
    .profile-card,
    .overview-card,
    .info-card,
    .calendar-card,
    .report-card,
    .schedule-card,
    .attendance-card,
    .availability-card,
    .request-card,
    .auth-card,
    .auth-panel,
    .login-dialog,
    .login-modal-card,
    .rounded-2xl,
    .rounded-3xl,
    .rounded-xl,
    .rounded-lg {
        border-radius: 8px !important;
    }

    .summary-card,
    .event-card,
    .empty-state,
    .toolbar,
    .table-panel,
    .detail-panel,
    .detail-head,
    .panel,
    .panel-card,
    .form-panel,
    .form-card,
    .profile-card,
    .overview-card,
    .info-card,
    .calendar-card,
    .report-card,
    .schedule-card,
    .attendance-card,
    .availability-card,
    .request-card,
    .auth-card,
    .auth-panel,
    .login-dialog,
    .login-modal-card {
        border: 1px solid var(--minimal-border) !important;
        background: var(--minimal-surface) !important;
        box-shadow: var(--minimal-shadow) !important;
        backdrop-filter: none !important;
    }

    .shadow,
    .shadow-sm,
    .shadow-md,
    .shadow-lg,
    .shadow-xl,
    .shadow-2xl {
        box-shadow: var(--minimal-shadow) !important;
    }

    .border-green-100,
    .border-green-200,
    .border-emerald-100,
    .border-emerald-200,
    .border-gray-200,
    .border-slate-200 {
        border-color: var(--minimal-border) !important;
    }

    .panel-head,
    .card-head,
    .form-head,
    .section-head,
    .page-head,
    .detail-head {
        gap: 14px !important;
    }

    h1,
    .auth-title,
    .hero-title,
    .page-title h1,
    .content-heading h1,
    .dashboard-title,
    .profile-title {
        max-width: 780px !important;
        font-size: 2.35rem !important;
        line-height: 1.05 !important;
        letter-spacing: 0 !important;
    }

    h2,
    .panel-head h2,
    .section-head h2,
    .form-head h2 {
        font-size: 1.35rem !important;
        line-height: 1.18 !important;
        letter-spacing: 0 !important;
    }

    h3 {
        font-size: 1rem !important;
        line-height: 1.22 !important;
    }

    .eyebrow,
    .kicker,
    .section-label,
    .sidebar-section-label,
    .student-nav-label,
    .panel-kicker,
    .field-label,
    .field label,
    label {
        letter-spacing: 0 !important;
    }

    .text-green-900,
    .text-emerald-900,
    .text-slate-900,
    .text-gray-900 {
        color: var(--minimal-text) !important;
    }

    .text-gray-500,
    .text-gray-600,
    .text-slate-500,
    .text-slate-600,
    .text-green-700 {
        color: var(--minimal-muted) !important;
    }

    input:not([type="checkbox"]):not([type="radio"]),
    select,
    textarea,
    .field-input,
    .auth-field,
    .login-field,
    .login-modal-form input,
    .form-control {
        min-height: 42px !important;
        border: 1px solid var(--minimal-border) !important;
        border-radius: 8px !important;
        background: #ffffff !important;
        box-shadow: none !important;
        color: var(--minimal-text) !important;
    }

    input:focus,
    select:focus,
    textarea:focus,
    .field-input:focus,
    .auth-field:focus {
        border-color: var(--minimal-green) !important;
        box-shadow: 0 0 0 3px rgba(13, 113, 69, 0.12) !important;
        outline: 0 !important;
    }

    button,
    .button,
    .primary-button,
    .secondary-button,
    .danger-button,
    .warning-button,
    .primary-link,
    .secondary-link,
    .edit-link,
    .delete-button,
    .row-button,
    .icon-button,
    .auth-button,
    .login-submit,
    .logout-button,
    .sidebar-account-logout button,
    .nav-link,
    .header-link {
        border-radius: 8px !important;
        box-shadow: none !important;
        font-weight: 800 !important;
    }

    .primary-button,
    .button:not(.secondary),
    .primary-link,
    .auth-button,
    .login-submit,
    .row-button.approve {
        border-color: var(--minimal-green) !important;
        background: var(--minimal-green) !important;
        color: #ffffff !important;
    }

    .secondary-button,
    .secondary-link,
    .nav-link,
    .header-link {
        border-color: var(--minimal-border-strong) !important;
        background: #ffffff !important;
        color: var(--minimal-green-dark) !important;
    }

    .danger-button,
    .delete-button,
    .row-button.decline {
        border-color: #e5b6b1 !important;
        color: var(--minimal-danger) !important;
        background: #fff6f5 !important;
    }

    .badge,
    .status-pill,
    .snapshot-badge,
    .admin-chip,
    .page-meta,
    .status-badge {
        border-radius: 8px !important;
        box-shadow: none !important;
        letter-spacing: 0 !important;
    }

    table {
        border-collapse: collapse !important;
    }

    th,
    td {
        padding: 11px 13px !important;
        border-bottom: 1px solid var(--minimal-border) !important;
        white-space: nowrap;
    }

    th {
        background: var(--minimal-green-dark) !important;
        color: #ffffff !important;
        font-size: 0.7rem !important;
        letter-spacing: 0 !important;
    }

    tbody tr:hover {
        background: #f8fbf9 !important;
    }

    .grid,
    .stats-grid,
    .status-grid,
    .metric-grid,
    .dashboard-grid,
    .content-grid,
    .form-grid,
    .filter-grid {
        gap: 14px !important;
    }

    .flash-toast {
        border-radius: 8px !important;
        box-shadow: var(--minimal-shadow) !important;
    }

    @media (max-width: 840px) {
        .admin-layout,
        .workspace-layout,
        .student-workspace {
            grid-template-columns: minmax(0, 1fr) !important;
        }

        .page,
        .admin-main,
        .workspace-main,
        .student-content {
            padding: 16px !important;
        }

        h1,
        .auth-title,
        .hero-title,
        .page-title h1,
        .content-heading h1 {
            font-size: 1.8rem !important;
        }
    }
</style>
