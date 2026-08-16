<style data-pwa-responsive-ui>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    html {
        -webkit-text-size-adjust: 100%;
        text-size-adjust: 100%;
    }

    body {
        min-width: 0 !important;
        overflow-x: hidden;
    }

    img,
    picture,
    video,
    canvas,
    svg {
        max-width: 100%;
    }

    input,
    select,
    textarea,
    button {
        max-width: 100%;
        font: inherit;
    }

    :where(.admin-main, .workspace-main, .student-content, .profile-main, .content-area, .dashboard-content, .app-main),
    :where(.page-shell, .content-shell, .dashboard-shell, .profile-shell, .student-shell, .admin-shell, .workspace-shell),
    :where(.panel, .panel-card, .form-panel, .form-card, .table-panel, .detail-panel, .calendar-card, .report-card, .schedule-card, .attendance-card, .viewer-card, .summary-card, .event-card) {
        min-width: 0 !important;
    }

    :where(.page-shell, .content-shell, .dashboard-shell, .profile-shell, .student-shell) {
        width: min(100%, 1240px) !important;
    }

    :where(.admin-shell, .workspace-shell) {
        width: 100% !important;
        max-width: none !important;
        margin-inline: 0 !important;
    }

    :where(.section-head, .page-head, .panel-head, .card-head, .form-head, .detail-head, .toolbar, .filter-bar, .filter-grid, .form-grid, .report-controls, .attendance-controls, .schedule-controls, .auth-actions) {
        min-width: 0 !important;
    }

    :where(.table-panel, .table-wrap, .table-scroll, .table-responsive, .report-table-wrap, .attendance-table-wrap, .schedule-table-wrap, .timetable-wrap, .weekly-table-wrap) {
        max-width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    :where(.page-copy, .section-copy, .panel-copy, .field-hint, .empty-copy, .sidebar-note, .student-sidebar-note, p) {
        overflow-wrap: anywhere;
    }

    :where(.badge, .status-pill, .status-badge, .snapshot-badge, .admin-chip, .workspace-chip) {
        max-width: 100%;
        white-space: normal !important;
    }

    @media (max-width: 1180px) {
        :where(.admin-main, .workspace-main, .student-content, .profile-main, .content-area, .dashboard-content) {
            padding-inline: clamp(16px, 3vw, 28px) !important;
        }

        :where(.content-grid, .dashboard-grid, .overview-grid, .summary-grid, .status-grid, .metric-grid, .event-grid, .viewer-grid, .cards-grid) {
            grid-template-columns: repeat(auto-fit, minmax(min(260px, 100%), 1fr)) !important;
        }

        :where(.hero, .feature-grid, .profile-edit-grid, .schedule-layout, .availability-layout, .attendance-layout, .report-layout, .detail-grid) {
            grid-template-columns: minmax(0, 1fr) !important;
        }
    }

    @media (max-width: 900px) {
        :where(.admin-main, .workspace-main, .student-content, .profile-main, .content-area, .dashboard-content) {
            padding: 18px !important;
        }

        :where(.filter-grid, .form-grid, .field-grid, .report-controls, .print-report-grid, .attendance-filter-grid, .schedule-form-grid, .profile-detail-grid) {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }

        :where(.admin-topbar, .workspace-topbar, .page-head, .section-head, .panel-head, .card-head, .detail-head) {
            gap: 12px !important;
        }

        :where(.map-card, .campus-map-card, .map-shell, .leaflet-container) {
            max-width: 100% !important;
        }
    }

    @media (max-width: 840px) {
        :where(.admin-layout, .workspace-layout, .student-workspace) {
            grid-template-columns: minmax(0, 1fr) !important;
        }

        :where(.page-shell, .content-shell, .dashboard-shell, .profile-shell, .student-shell, .admin-shell, .workspace-shell) {
            width: 100% !important;
            max-width: 100% !important;
            margin-inline: 0 !important;
        }

        :where(.admin-main, .workspace-main, .student-content, .profile-main, .content-area, .dashboard-content) {
            width: 100% !important;
            padding: 16px !important;
        }

        :where(.admin-topbar, .workspace-topbar) {
            width: 100% !important;
            max-width: 100% !important;
        }

        :where(.panel, .panel-card, .form-panel, .form-card, .table-panel, .detail-panel, .calendar-card, .report-card, .schedule-card, .attendance-card, .viewer-card, .summary-card, .event-card, .auth-card, .login-modal-card) {
            padding: clamp(14px, 4vw, 20px) !important;
        }

        :where(table) {
            display: block;
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        :where(thead, tbody, tr) {
            width: max-content;
            min-width: 100%;
        }

        :where(.schedule-table, .weekly-table, .timetable, .report-table, .attendance-table) {
            min-width: 680px !important;
        }

        :where(.login-modal-card, .auth-card, .auth-panel) {
            width: min(100% - 24px, 520px) !important;
            margin-inline: auto !important;
        }
    }

    @media (max-width: 640px) {
        :where(.admin-main, .workspace-main, .student-content, .profile-main, .content-area, .dashboard-content) {
            padding: 14px 12px !important;
        }

        :where(h1, .hero-title, .auth-title, .page-title h1, .content-heading h1, .dashboard-title, .profile-title) {
            font-size: clamp(1.55rem, 8vw, 2rem) !important;
            line-height: 1.08 !important;
        }

        :where(h2, .panel-head h2, .section-head h2, .form-head h2) {
            font-size: 1.12rem !important;
        }

        :where(.page-head, .section-head, .panel-head, .card-head, .form-head, .detail-head, .toolbar, .filter-bar, .form-actions, .auth-actions) {
            align-items: stretch !important;
            flex-direction: column !important;
        }

        :where(.filter-grid, .form-grid, .field-grid, .report-controls, .print-report-grid, .attendance-filter-grid, .schedule-form-grid, .profile-detail-grid, .detail-grid, .summary-grid, .status-grid, .metric-grid, .meta-grid) {
            grid-template-columns: minmax(0, 1fr) !important;
        }

        :where(.status-overview, .stats-grid) {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 8px !important;
        }

        :where(.status-item, .metric-card, .summary-card) {
            min-height: 66px !important;
            padding: 10px 12px !important;
        }

        :where(.form-actions > *, .filter-actions > *, .auth-actions > *, .toolbar > *, .page-actions > *, .section-actions > *, .primary-button, .secondary-button, .danger-button, .warning-button, .auth-button, .login-submit) {
            width: 100%;
        }

        :where(input:not([type="checkbox"]):not([type="radio"]), select, textarea) {
            width: 100% !important;
        }

        :where(.viewer-grid, .faculty-grid, .teacher-grid, .live-directory-grid) {
            grid-template-columns: minmax(0, 1fr) !important;
            gap: 12px !important;
        }

        :where(.viewer-card, .teacher-card, .faculty-card) {
            min-height: 0 !important;
            height: auto !important;
        }

        :where(.chatbot-panel, .ai-chat-panel, .campus-assistant-panel) {
            right: 12px !important;
            bottom: 86px !important;
            width: min(392px, calc(100vw - 24px)) !important;
            max-height: min(640px, calc(100dvh - 120px)) !important;
        }

        :where(.chatbot-toggle, .ai-chat-toggle, .campus-assistant-toggle) {
            right: 12px !important;
            bottom: 16px !important;
        }
    }

    @media (max-width: 460px) {
        :where(.admin-main, .workspace-main, .student-content, .profile-main, .content-area, .dashboard-content) {
            padding-inline: 10px !important;
        }

        :where(.status-overview, .stats-grid) {
            grid-template-columns: minmax(0, 1fr) !important;
        }

        :where(.admin-brand-copy strong, .workspace-brand-copy strong) {
            max-width: calc(100vw - 132px);
        }

        :where(.login-modal-card, .auth-card, .auth-panel) {
            width: min(100% - 16px, 520px) !important;
        }
    }
</style>
