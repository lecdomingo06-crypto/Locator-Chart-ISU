<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="30">
    <title>Professor/Faculty Viewer</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800" rel="stylesheet" />

    <style>
        :root {
            color-scheme: light;
            --bg: #edf7f0;
            --card: rgba(255, 255, 255, 0.92);
            --card-soft: #f3fbf5;
            --card-border: rgba(12, 92, 56, 0.12);
            --text: #113322;
            --muted: #607766;
            --green-900: #0c5c38;
            --green-800: #147247;
            --green-700: #1b8a53;
            --green-100: #ddf4e4;
            --shadow: 0 18px 40px rgba(12, 92, 56, 0.1);
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
                radial-gradient(circle at top left, rgba(90, 193, 125, 0.24), transparent 22%),
                radial-gradient(circle at 88% 14%, rgba(32, 153, 90, 0.18), transparent 18%),
                linear-gradient(180deg, #f7fcf8 0%, var(--bg) 100%);
        }

        .page {
            position: relative;
            z-index: 1;
            padding: 24px;
        }

        .shell {
            max-width: 1180px;
            margin: 0 auto;
            display: grid;
            gap: 18px;
        }

        .workspace-shell {
            min-height: 100vh;
        }

        .workspace-topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            min-height: 76px;
            padding: 14px 28px;
            color: #effcf3;
            background: linear-gradient(135deg, #094629 0%, #0c5c38 56%, #147247 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 16px 34px rgba(8, 58, 35, 0.2);
        }

        .workspace-brand,
        .workspace-session,
        .workspace-chip,
        .sidebar-brand,
        .sidebar-link,
        .logout-button {
            display: inline-flex;
            align-items: center;
        }

        .workspace-brand {
            gap: 14px;
            color: inherit;
            text-decoration: none;
        }

        .workspace-brand-mark,
        .sidebar-mark {
            display: grid;
            place-items: center;
            border-radius: 8px;
            font-weight: 800;
        }

        .workspace-brand-mark {
            width: 48px;
            height: 48px;
            color: var(--green-900);
            background: rgba(255, 255, 255, 0.94);
            box-shadow: inset 0 0 0 1px rgba(12, 92, 56, 0.08);
        }

        .workspace-brand-copy,
        .sidebar-copy {
            display: grid;
            gap: 3px;
        }

        .workspace-brand-copy strong,
        .sidebar-copy strong {
            font-size: 1rem;
        }

        .workspace-brand-copy span,
        .sidebar-copy span,
        .sidebar-note {
            font-size: 0.88rem;
            line-height: 1.5;
        }

        .workspace-brand-copy span {
            color: rgba(239, 252, 243, 0.76);
            font-weight: 600;
        }

        .workspace-session {
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .workspace-chip {
            min-height: 34px;
            padding: 0 13px;
            border-radius: 999px;
            color: rgba(239, 252, 243, 0.95);
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.12);
            font-size: 0.86rem;
            font-weight: 700;
        }

        .logout-form {
            margin: 0;
        }

        .logout-button {
            justify-content: center;
            min-height: 38px;
            padding: 0 16px;
            border: 0;
            border-radius: 999px;
            color: var(--green-900);
            background: #ffffff;
            font-size: 0.86rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 12px 22px rgba(5, 51, 30, 0.16);
        }

        .workspace-layout {
            display: grid;
            grid-template-columns: 244px minmax(0, 1fr);
            gap: 26px;
            align-items: start;
        }

        .workspace-sidebar {
            position: sticky;
            top: 94px;
            min-height: calc(100vh - 112px);
            margin-left: 16px;
            padding: 18px 14px;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(12, 92, 56, 0.1);
            border-left: 0;
            border-radius: 0 8px 8px 0;
            box-shadow: var(--shadow);
        }

        .sidebar-brand {
            gap: 12px;
            padding: 0 0 18px;
            border-bottom: 1px solid rgba(12, 92, 56, 0.1);
        }

        .sidebar-mark {
            flex: 0 0 54px;
            width: 54px;
            height: 54px;
            color: #ffffff;
            background: linear-gradient(145deg, var(--green-800), var(--green-900));
        }

        .sidebar-copy span,
        .sidebar-note {
            color: var(--muted);
        }

        .sidebar-note {
            margin: 14px 0 18px;
        }

        .sidebar-section-label {
            display: block;
            margin: 0 0 10px;
            color: #6c7d70;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .sidebar-nav {
            display: grid;
            gap: 8px;
        }

        .sidebar-link {
            gap: 11px;
            min-height: 44px;
            padding: 0 12px;
            border-radius: 8px;
            color: var(--text);
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 800;
        }

        .sidebar-link svg {
            width: 18px;
            height: 18px;
            flex: 0 0 auto;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .sidebar-link:hover {
            color: var(--green-900);
            background: var(--green-100);
        }

        .sidebar-link.is-active {
            color: #ffffff;
            background: linear-gradient(135deg, var(--green-800), var(--green-900));
            box-shadow: 0 12px 24px rgba(12, 92, 56, 0.16);
        }

        .workspace-main .page {
            padding: 24px 28px 32px 0;
        }

        .workspace-main .shell {
            max-width: none;
            margin: 0;
        }

        .topbar,
        .filters,
        .status-overview,
        .empty-state,
        .viewer-card {
            border: 1px solid var(--card-border);
            background: var(--card);
            backdrop-filter: blur(18px);
            box-shadow: var(--shadow);
        }

        .topbar {
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
            align-items: start;
            padding: 20px 22px;
            border-radius: 22px;
            border: none;
            background:
                radial-gradient(circle at 88% 18%, rgba(185, 255, 210, 0.18), transparent 22%),
                linear-gradient(135deg, var(--green-900) 0%, var(--green-800) 58%, var(--green-700) 100%);
            box-shadow: 0 24px 48px rgba(12, 92, 56, 0.22);
        }

        .topbar::after {
            content: '';
            position: absolute;
            right: -4rem;
            bottom: -4rem;
            width: 13rem;
            height: 13rem;
            border-radius: 42% 58% 60% 40%;
            background: rgba(216, 255, 226, 0.12);
            pointer-events: none;
        }

        .hero-copy {
            position: relative;
            z-index: 1;
            display: grid;
            gap: 12px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            color: #effcf3;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .eyebrow::before {
            display: none;
        }

        h1 {
            margin: 0;
            font-size: clamp(2rem, 4vw, 2.9rem);
            line-height: 1.05;
            letter-spacing: -0.05em;
            color: #f5fff8;
        }

        .hero-copy p {
            margin: 0;
            max-width: 60ch;
            color: rgba(241, 255, 246, 0.82);
            font-size: 0.98rem;
            line-height: 1.7;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: minmax(280px, 1.4fr) repeat(2, minmax(170px, 1fr));
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .hero-stat {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 14px 16px;
            border-radius: 16px;
            background: rgba(8, 58, 35, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .hero-stat span {
            display: block;
            color: rgba(241, 255, 246, 0.68);
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .hero-stat strong {
            display: block;
            margin-top: 6px;
            font-size: 0.98rem;
            color: #ffffff;
        }

        .hero-stat small {
            display: block;
            margin-top: 8px;
            color: rgba(241, 255, 246, 0.74);
            font-size: 0.88rem;
            line-height: 1.5;
        }

        .hero-stat-wide {
            background: rgba(255, 255, 255, 0.14);
        }

        .hero-stat-wide strong {
            font-size: 1.65rem;
            line-height: 1.15;
        }

        .filters {
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-columns: 260px minmax(0, 1fr);
            gap: 18px;
            align-items: end;
            padding: 18px 20px;
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.95), rgba(244, 251, 246, 0.95));
        }

        .filters::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 4px;
            background: linear-gradient(90deg, var(--green-900), var(--green-700));
        }

        .filters-copy h2 {
            margin: 0;
            font-size: 1.1rem;
            letter-spacing: -0.03em;
        }

        .filters-copy p {
            margin: 6px 0 0;
            color: var(--muted);
            line-height: 1.6;
        }

        .results-head {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 16px;
            padding: 2px 2px 0;
        }

        .results-copy {
            display: grid;
            gap: 6px;
        }

        .results-copy span {
            color: var(--green-800);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .results-copy h2 {
            margin: 0;
            font-size: 1.2rem;
            letter-spacing: -0.03em;
        }

        .results-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: flex-end;
        }

        .results-pill {
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(221, 244, 228, 0.8);
            border: 1px solid rgba(20, 114, 71, 0.1);
            color: var(--green-900);
            font-size: 0.82rem;
            font-weight: 600;
        }

        .filter-form {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 220px 120px;
            gap: 14px;
            align-items: end;
        }

        .field {
            display: grid;
            gap: 8px;
            min-width: 0;
        }

        .field label {
            font-size: 0.84rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--green-900);
        }

        .field input,
        .field select {
            width: 100%;
            min-height: 48px;
            padding: 0 16px;
            border-radius: 14px;
            border: 1px solid var(--card-border);
            background: rgba(255, 255, 255, 0.96);
            color: var(--text);
            font: inherit;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .field input:focus,
        .field select:focus {
            border-color: rgba(20, 114, 71, 0.42);
            box-shadow: 0 0 0 4px rgba(20, 114, 71, 0.12);
        }

        .filter-button {
            min-height: 48px;
            padding: 0 20px;
            border: 0;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--green-800), var(--green-900));
            color: #fff;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 14px 28px rgba(20, 114, 71, 0.2);
            transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .filter-button:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, var(--green-700), var(--green-900));
            box-shadow: 0 18px 32px rgba(20, 114, 71, 0.24);
        }

        .status-overview {
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 12px;
            padding: 18px 20px;
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.95), rgba(244, 251, 246, 0.95));
        }

        .status-overview::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 4px;
            background: linear-gradient(90deg, var(--green-900), var(--green-700));
        }

        .status-item {
            position: relative;
            display: grid;
            gap: 8px;
            padding: 14px 16px;
            border-radius: 16px;
            background: rgba(221, 244, 228, 0.52);
            border: 1px solid rgba(20, 114, 71, 0.1);
        }

        .status-item span {
            color: var(--muted);
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .status-item strong {
            font-size: 1.4rem;
            letter-spacing: -0.04em;
            color: var(--text);
        }

        .viewer-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .viewer-card {
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-rows: 82px 92px 88px 48px;
            gap: 12px;
            height: 376px;
            padding: 18px;
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(244, 251, 246, 0.96));
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .viewer-card::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 4px;
            background: linear-gradient(90deg, var(--green-800), #4dc97d);
        }

        .viewer-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 22px 44px rgba(12, 92, 56, 0.12);
        }

        .viewer-head {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            min-width: 0;
            overflow: hidden;
        }

        .viewer-profile {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            min-width: 0;
        }

        .avatar,
        .avatar-placeholder {
            flex: 0 0 60px;
            width: 60px;
            height: 60px;
            border-radius: 16px;
        }

        .avatar {
            object-fit: cover;
            border: 2px solid rgba(20, 114, 71, 0.08);
            box-shadow: none;
        }

        .avatar-placeholder {
            display: grid;
            place-items: center;
            background: var(--green-100);
            color: var(--green-900);
            font-size: 1.35rem;
            font-weight: 800;
        }

        .viewer-title {
            min-width: 0;
        }

        .viewer-title h3 {
            margin: 0;
            font-size: 1.15rem;
            letter-spacing: -0.03em;
            line-height: 1.25;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .viewer-title p {
            margin: 4px 0 0;
            color: var(--muted);
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .meta-card {
            display: grid;
            align-content: start;
            padding: 12px;
            border-radius: 16px;
            background: rgba(221, 244, 228, 0.52);
            border: 1px solid rgba(20, 114, 71, 0.1);
            min-width: 0;
            min-height: 92px;
        }

        .meta-card span {
            display: block;
            color: var(--muted);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .meta-card strong {
            display: block;
            margin-top: 6px;
            font-size: 0.96rem;
            letter-spacing: -0.02em;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .status-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 14px;
            background: rgba(232, 245, 236, 0.55);
            border: 1px solid rgba(20, 114, 71, 0.08);
            min-width: 0;
            min-height: 88px;
        }

        .status-row > div:first-child {
            min-width: 0;
        }

        .status-row strong {
            display: block;
            font-size: 0.84rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .status-row span {
            display: block;
            margin-top: 6px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text);
            line-height: 1.35;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            min-width: 108px;
            max-width: 128px;
            padding: 8px 14px;
            border-radius: 999px;
            color: #fff;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.14);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .detail-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 40px;
            align-self: end;
            margin-top: 2px;
            border: 1px solid rgba(20, 114, 71, 0.16);
            border-radius: 14px;
            color: var(--green-900);
            background: rgba(221, 244, 228, 0.72);
            font: inherit;
            font-size: 0.86rem;
            font-weight: 800;
            cursor: pointer;
        }

        .detail-button:hover {
            background: rgba(221, 244, 228, 0.94);
        }

        .detail-modal {
            position: fixed;
            inset: 0;
            z-index: 90;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(8, 41, 25, 0.42);
        }

        .detail-modal.is-open {
            display: flex;
        }

        .detail-dialog {
            width: min(520px, 100%);
            max-height: calc(100vh - 48px);
            overflow: auto;
            border-radius: 20px;
            background: #ffffff;
            border: 1px solid rgba(20, 114, 71, 0.14);
            box-shadow: 0 28px 70px rgba(8, 41, 25, 0.28);
        }

        .detail-dialog-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 22px;
            border-bottom: 1px solid rgba(20, 114, 71, 0.1);
        }

        .detail-dialog-title {
            display: grid;
            gap: 6px;
            min-width: 0;
        }

        .detail-dialog-title span {
            color: var(--green-900);
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .detail-dialog-title h3 {
            margin: 0;
            font-size: 1.25rem;
            letter-spacing: -0.03em;
        }

        .detail-close {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            width: 36px;
            height: 36px;
            border: 1px solid rgba(20, 114, 71, 0.14);
            border-radius: 999px;
            color: var(--green-900);
            background: rgba(221, 244, 228, 0.72);
            font: inherit;
            font-weight: 800;
            cursor: pointer;
        }

        .detail-dialog-body {
            display: grid;
            gap: 14px;
            padding: 20px 22px 22px;
        }

        .class-card {
            min-height: 0;
            padding: 12px;
            border-radius: 16px;
            background: linear-gradient(180deg, rgba(20, 114, 71, 0.08), rgba(20, 114, 71, 0.03));
            border: 1px solid rgba(20, 114, 71, 0.12);
        }

        .class-card strong {
            display: block;
            margin-bottom: 8px;
            color: var(--green-900);
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .class-details {
            display: grid;
            gap: 6px;
        }

        .class-line {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            color: var(--text);
            font-size: 0.92rem;
        }

        .class-line span {
            color: var(--muted);
            font-weight: 600;
        }

        .empty-state {
            padding: 28px;
            border-radius: 20px;
            text-align: center;
        }

        .empty-state h2 {
            margin: 0 0 10px;
            font-size: 1.7rem;
            letter-spacing: -0.03em;
        }

        .empty-state p {
            margin: 0;
            color: var(--muted);
            line-height: 1.8;
        }

        @media (max-width: 1280px) {
            .viewer-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 1024px) {
            .workspace-topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .workspace-session {
                justify-content: flex-start;
            }

            .workspace-layout {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .workspace-sidebar {
                position: static;
                min-height: 0;
                margin: 16px 16px 0;
                border-left: 1px solid rgba(12, 92, 56, 0.1);
                border-radius: 8px;
            }

            .sidebar-nav {
                grid-template-columns: repeat(5, minmax(0, 1fr));
            }

            .workspace-main .page {
                padding: 0 16px 24px;
            }

            .hero-stats,
            .filter-form,
            .status-overview,
            .viewer-grid {
                grid-template-columns: 1fr;
            }

            .filters {
                grid-template-columns: 1fr;
            }

            .results-head {
                align-items: flex-start;
                flex-direction: column;
            }

            .results-meta {
                justify-content: flex-start;
            }
        }

        @media (max-width: 720px) {
            .workspace-topbar {
                padding: 14px 16px;
            }

            .workspace-brand-copy span {
                display: none;
            }

            .workspace-session,
            .workspace-chip,
            .logout-form,
            .logout-button {
                width: 100%;
            }

            .workspace-sidebar {
                margin: 14px 14px 0;
                padding: 16px;
            }

            .sidebar-nav {
                grid-template-columns: 1fr;
            }

            .workspace-main .page {
                padding: 0 14px 20px;
            }

            .page {
                padding: 16px;
            }

            .topbar,
            .filters,
            .status-overview,
            .viewer-card,
            .empty-state {
                padding: 16px;
                border-radius: 18px;
            }

            .viewer-head,
            .status-row,
            .class-line {
                align-items: flex-start;
                flex-direction: column;
            }

            .meta-grid {
                grid-template-columns: 1fr;
            }

            .viewer-card {
                grid-template-rows: none;
                height: auto;
            }

            .field,
            .filter-button {
                width: 100%;
            }
        }
    </style>
    <x-minimal-ui />
</head>
<body>
@php
    $counts = [
        'Available' => 0,
        'In Class' => 0,
        'On Leave' => 0,
        'Emergency' => 0,
        'On Meeting' => 0,
    ];
    $statusLabels = [
        'available' => 'Available',
        'in class' => 'In Class',
        'on leave' => 'On Leave',
        'emergency' => 'Emergency',
        'on meeting' => 'On Meeting',
        'on break' => 'On Break',
        'not available' => 'Not Available',
        'private' => 'Private',
        'privacy' => 'Private',
    ];

    foreach ($users as $u) {
        $statusData = $u->live_status;
        $statusKey = ($statusData['source'] ?? null) === 'academic_event'
            ? ($statusData['event_type'] ?? $statusData['status'])
            : $statusData['status'];
        $statusKey = trim((string) $statusKey);
        $statusKey = $statusLabels[strtolower($statusKey)] ?? $statusKey;

        $counts[$statusKey] = ($counts[$statusKey] ?? 0) + 1;
    }
@endphp

    <div class="workspace-shell">
        <header class="workspace-topbar">
            <a href="{{ route('staff.viewer') }}" class="workspace-brand">
                <div class="workspace-brand-mark" aria-hidden="true"><img src="{{ asset('images/isulogo.jpg') }}" alt=""></div>
                <div class="workspace-brand-copy">
                    <strong>Professor Tracking System</strong>
                    <span>{{ ucfirst(Auth::user()->role) }} workspace</span>
                </div>
            </a>

            <div class="workspace-session">
                <span class="workspace-chip">Role: {{ ucfirst(Auth::user()->role) }}</span>
                <span class="workspace-chip">Signed in as {{ Auth::user()->full_name ?: Auth::user()->username }}</span>
            </div>
        </header>

        <div class="workspace-layout">
            <aside class="workspace-sidebar">
                <div class="sidebar-brand">
                    <div class="sidebar-mark" aria-hidden="true"><img src="{{ asset('images/isulogo.jpg') }}" alt=""></div>
                    <div class="sidebar-copy">
                        <strong>{{ ucfirst(Auth::user()->role) }} Panel</strong>
                        <span>Viewer and schedule tools</span>
                    </div>
                </div>

                <span class="sidebar-section-label">Workspace</span>
                <nav class="sidebar-nav" aria-label="{{ ucfirst(Auth::user()->role) }} workspace">
                    <a href="{{ route('staff.viewer') }}" class="sidebar-link is-active">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M2.5 12s3.5-5.5 9.5-5.5 9.5 5.5 9.5 5.5-3.5 5.5-9.5 5.5S2.5 12 2.5 12Z"></path>
                            <path d="M12 15a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z"></path>
                        </svg>
                        <span>Live Viewer</span>
                    </a>

                    <a href="{{ route('attendance.show') }}" class="sidebar-link{{ request()->routeIs('attendance.*') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 7v5l3 2"></path>
                        </svg>
                        <span>Attendance</span>
                    </a>
                    <a href="{{ route('availability.show') }}" class="sidebar-link">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 2v4"></path>
                            <path d="M12 18v4"></path>
                            <path d="M4.9 4.9l2.8 2.8"></path>
                            <path d="M16.3 16.3l2.8 2.8"></path>
                            <path d="M2 12h4"></path>
                            <path d="M18 12h4"></path>
                            <path d="M4.9 19.1l2.8-2.8"></path>
                            <path d="M16.3 7.7l2.8-2.8"></path>
                        </svg>
                        <span>Availability</span>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="sidebar-link">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 12a4 4 0 1 0 0-8a4 4 0 0 0 0 8Z"></path>
                            <path d="M4.5 20c.8-3.8 3.4-5.8 7.5-5.8s6.7 2 7.5 5.8"></path>
                        </svg>
                        <span>Profile</span>
                    </a>

                    @if(Auth::user()->role === 'professor')
                        <a href="{{ route('schedules.create') }}" class="sidebar-link">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M7 3v4"></path>
                                <path d="M17 3v4"></path>
                                <path d="M4 8h16"></path>
                                <path d="M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"></path>
                                <path d="M8 13h8"></path>
                                <path d="M8 17h5"></path>
                            </svg>
                            <span>Weekly Schedule</span>
                        </a>
                    @endif

                    <a href="{{ route('special_schedules.create') }}" class="sidebar-link">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 6l1.6 4.4L18 12l-4.4 1.6L12 18l-1.6-4.4L6 12l4.4-1.6L12 6Z"></path>
                            <path d="M19 4v4"></path>
                            <path d="M21 6h-4"></path>
                        </svg>
                        <span>Special Schedule</span>
                    </a>
                </nav>
            <x-sidebar-account-footer />
            </aside>
<x-responsive-sidebar-control />

            <main class="workspace-main">
                <div class="page">
                    <div class="shell">
            <section class="topbar">
                <div class="hero-copy">
                    <div class="eyebrow">Staff Viewer</div>
                    <h1>Professor and Faculty Availability</h1>
                    <p>
                        A cleaner live directory for professors and faculty across every department.
                    </p>
                </div>

                <div class="hero-stats" aria-label="Viewer summary">
                    <div class="hero-stat hero-stat-wide">
                        <span>Live Monitoring Active</span>
                        <strong id="clock"></strong>
                        <small>The page refreshes automatically every 30 seconds.</small>
                    </div>

                    <div class="hero-stat">
                        <span>Visible Staff</span>
                        <strong>{{ $users->count() }} record{{ $users->count() === 1 ? '' : 's' }}</strong>
                    </div>

                    <div class="hero-stat">
                        <span>Department Scope</span>
                        <strong>{{ $department ? 'Filtered department' : 'All departments' }}</strong>
                    </div>
                </div>
            </section>

            <section class="filters">
                <div class="filters-copy">
                    <h2>Search and Filter</h2>
                    <p>Use the same viewer tools in a cleaner layout.</p>
                </div>

                <form method="GET" action="{{ route('staff.viewer') }}" class="filter-form">
                    <div class="field">
                        <label for="search">Search Name</label>
                        <input id="search" type="text" name="search" placeholder="Search by name" value="{{ $search }}">
                    </div>

                    <div class="field">
                        <label for="department">Department</label>
                        <select id="department" name="department">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ $department == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="filter-button">Filter</button>
                </form>
            </section>

            <section class="status-overview" aria-label="Status counts">
                @foreach($counts as $key => $value)
                    <div class="status-item">
                        <span>{{ $key }}</span>
                        <strong>{{ $value }}</strong>
                    </div>
                @endforeach
            </section>

            <section class="results-head" aria-label="Results summary">
                <div class="results-copy">
                    <span>Live Directory</span>
                    <h2>Professor and Faculty List</h2>
                </div>

                <div class="results-meta">
                    <div class="results-pill">{{ $users->count() }} staff shown</div>
                    <div class="results-pill">{{ $department ? 'Department filtered' : 'All departments' }}</div>
                </div>
            </section>

            <section class="viewer-grid">
                @forelse($users as $user)
                    @php
                        $statusData = $user->live_status;
                        $status = $statusData['status'];
                        $statusPalette = [
                            'Available' => '#16a34a',
                            'In Class' => '#2563eb',
                            'On Leave' => '#dc2626',
                            'Emergency' => '#f59e0b',
                            'On Meeting' => '#7c3aed',
                            'On Break' => '#d97706',
                            'Not Available' => '#6b7280',
                            'Holiday' => '#d97706',
                            'Class Suspension' => '#dc2626',
                            'No Classes' => '#0891b2',
                            'University Event' => '#0f766e',
                            'Department Activity' => '#7c3aed',
                        ];
                        $paletteKey = ($statusData['source'] ?? null) === 'academic_event'
                            ? ($statusData['event_type'] ?? $status)
                            : $status;
                        $badgeColor = $statusPalette[$paletteKey] ?? '#6b7280';
                    @endphp

                    <article class="viewer-card">
                        <div class="viewer-head">
                            <div class="viewer-profile">
                                @if($user->profile_picture)
                                    <img
                                        src="{{ asset('storage/' . $user->profile_picture) }}"
                                        alt="Profile picture of {{ $user->full_name }}"
                                        class="avatar"
                                    >
                                @else
                                    <div class="avatar-placeholder" aria-hidden="true">
                                        {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif

                                <div class="viewer-title">
                                    <h3>{{ $user->full_name }}</h3>
                                    <p>{{ ucfirst($user->role) }} availability profile</p>
                                </div>
                            </div>
                        </div>

                        <div class="meta-grid">
                            <div class="meta-card">
                                <span>Role</span>
                                <strong>{{ ucfirst($user->role) }}</strong>
                            </div>

                            <div class="meta-card">
                                <span>Department</span>
                                <strong>{{ $user->department->name ?? 'No Department' }}</strong>
                            </div>
                        </div>

                        <div class="status-row">
                            <div>
                                <strong>Current Status</strong>
                                <span>Real-time availability</span>
                            </div>

                            <div class="status-badge" style="background-color: {{ $badgeColor }};">
                                {{ $status }}
                            </div>
                        </div>

                        @if(($statusData['source'] ?? null) === 'academic_event')
                            <button type="button" class="detail-button" data-detail-open="detail-modal-{{ $user->id }}">
                                View event details
                            </button>
                        @elseif($status === 'On Meeting' && !empty($statusData['status_start_datetime']) && !empty($statusData['status_end_datetime']))
                            @php
                                $meetingStart = \Carbon\Carbon::parse($statusData['status_start_datetime']);
                                $meetingEnd = \Carbon\Carbon::parse($statusData['status_end_datetime']);
                                $sameMeetingDay = $meetingStart->isSameDay($meetingEnd);
                            @endphp
                            <button type="button" class="detail-button" data-detail-open="detail-modal-{{ $user->id }}">
                                View meeting details
                            </button>
                        @elseif(in_array($status, ['On Break', 'Not Available'], true) && !empty($statusData['status_start_datetime']) && !empty($statusData['status_end_datetime']))
                            @php
                                $availabilityStart = \Carbon\Carbon::parse($statusData['status_start_datetime']);
                                $availabilityEnd = \Carbon\Carbon::parse($statusData['status_end_datetime']);
                                $sameAvailabilityDay = $availabilityStart->isSameDay($availabilityEnd);
                            @endphp
                            <button type="button" class="detail-button" data-detail-open="detail-modal-{{ $user->id }}">
                                View availability details
                            </button>
                        @elseif($status === 'In Class')
                            <button type="button" class="detail-button" data-detail-open="detail-modal-{{ $user->id }}">
                                View class details
                            </button>
                        @endif
                    </article>

                    @if(($statusData['source'] ?? null) === 'academic_event')
                        <div id="detail-modal-{{ $user->id }}" class="detail-modal" aria-hidden="true">
                            <div class="detail-dialog" role="dialog" aria-modal="true" aria-labelledby="detail-title-{{ $user->id }}">
                                <div class="detail-dialog-head">
                                    <div class="detail-dialog-title">
                                        <span>{{ $user->full_name }}</span>
                                        <h3 id="detail-title-{{ $user->id }}">Active Academic Event</h3>
                                    </div>
                                    <button type="button" class="detail-close" data-detail-close aria-label="Close details">x</button>
                                </div>
                                <div class="detail-dialog-body">
                                    <div class="class-card">
                                        <strong>Event Details</strong>
                                        <div class="class-details">
                                            <div class="class-line">
                                                <span>Type</span>
                                                <div>{{ $statusData['event_type'] }}</div>
                                            </div>

                                            @if(!empty($statusData['event_note']))
                                                <div class="class-line">
                                                    <span>Note</span>
                                                    <div>{{ $statusData['event_note'] }}</div>
                                                </div>
                                            @endif

                                            @if(!empty($statusData['event_purpose']))
                                                <div class="class-line">
                                                    <span>Purpose</span>
                                                    <div>{{ $statusData['event_purpose'] }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($status === 'On Meeting' && !empty($statusData['status_start_datetime']) && !empty($statusData['status_end_datetime']))
                        <div id="detail-modal-{{ $user->id }}" class="detail-modal" aria-hidden="true">
                            <div class="detail-dialog" role="dialog" aria-modal="true" aria-labelledby="detail-title-{{ $user->id }}">
                                <div class="detail-dialog-head">
                                    <div class="detail-dialog-title">
                                        <span>{{ $user->full_name }}</span>
                                        <h3 id="detail-title-{{ $user->id }}">Meeting Time</h3>
                                    </div>
                                    <button type="button" class="detail-close" data-detail-close aria-label="Close details">x</button>
                                </div>
                                <div class="detail-dialog-body">
                                    <div class="class-card">
                                        <strong>Meeting Details</strong>
                                        <div class="class-details">
                                            <div class="class-line">
                                                <span>Starts</span>
                                                <div>{{ $sameMeetingDay ? $meetingStart->format('g:i A') : $meetingStart->format('M j, g:i A') }}</div>
                                            </div>
                                            <div class="class-line">
                                                <span>Ends</span>
                                                <div>{{ $sameMeetingDay ? $meetingEnd->format('g:i A') : $meetingEnd->format('M j, g:i A') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(in_array($status, ['On Break', 'Not Available'], true) && !empty($statusData['status_start_datetime']) && !empty($statusData['status_end_datetime']))
                        <div id="detail-modal-{{ $user->id }}" class="detail-modal" aria-hidden="true">
                            <div class="detail-dialog" role="dialog" aria-modal="true" aria-labelledby="detail-title-{{ $user->id }}">
                                <div class="detail-dialog-head">
                                    <div class="detail-dialog-title">
                                        <span>{{ $user->full_name }}</span>
                                        <h3 id="detail-title-{{ $user->id }}">{{ $status }} Time</h3>
                                    </div>
                                    <button type="button" class="detail-close" data-detail-close aria-label="Close details">x</button>
                                </div>
                                <div class="detail-dialog-body">
                                    <div class="class-card">
                                        <strong>Availability Details</strong>
                                        <div class="class-details">
                                            <div class="class-line">
                                                <span>Starts</span>
                                                <div>{{ $sameAvailabilityDay ? $availabilityStart->format('g:i A') : $availabilityStart->format('M j, g:i A') }}</div>
                                            </div>
                                            <div class="class-line">
                                                <span>Ends</span>
                                                <div>{{ $sameAvailabilityDay ? $availabilityEnd->format('g:i A') : $availabilityEnd->format('M j, g:i A') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($status === 'In Class')
                        <div id="detail-modal-{{ $user->id }}" class="detail-modal" aria-hidden="true">
                            <div class="detail-dialog" role="dialog" aria-modal="true" aria-labelledby="detail-title-{{ $user->id }}">
                                <div class="detail-dialog-head">
                                    <div class="detail-dialog-title">
                                        <span>{{ $user->full_name }}</span>
                                        <h3 id="detail-title-{{ $user->id }}">Current Class Details</h3>
                                    </div>
                                    <button type="button" class="detail-close" data-detail-close aria-label="Close details">x</button>
                                </div>
                                <div class="detail-dialog-body">
                                    <div class="class-card">
                                        <strong>Class Details</strong>
                                        <div class="class-details">
                                            <div class="class-line">
                                                <span>Subject</span>
                                                <div>{{ $statusData['subject'] }}</div>
                                            </div>
                                            <div class="class-line">
                                                <span>Room</span>
                                                <div>{{ $statusData['room'] }}</div>
                                            </div>
                                            @if(!empty($statusData['class_start_time']) && !empty($statusData['class_end_time']))
                                                <div class="class-line">
                                                    <span>Time</span>
                                                    <div>{{ \Carbon\Carbon::createFromFormat('H:i:s', $statusData['class_start_time'])->format('g:i A') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $statusData['class_end_time'])->format('g:i A') }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <section class="empty-state">
                        <h2>No professors or faculty found.</h2>
                        <p>Try adjusting the name search or department filter to load another set of staff records.</p>
                    </section>
                @endforelse

                @if(false)

    @forelse($users as $user)
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
            @if($user->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" width="100" style="display:block; margin-bottom:10px;">
            @else
                <p>No profile picture</p>
            @endif

            <p><strong>Name:</strong> {{ $user->full_name }}</p>
            <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
            <p><strong>Department:</strong> {{ $user->department->name ?? 'No Department' }}</p>

            @php
                $statusData = $user->live_status;
                $status = $statusData['status'];

                $badgeColor =
    $status == 'Available' ? '#16a34a' :
    ($status == 'In Class' ? '#2563eb' :
    ($status == 'On Leave' ? '#dc2626' :
    ($status == 'Emergency' ? '#f59e0b' :
    ($status == 'On Meeting' ? '#7c3aed' :
    '#6b7280'))));
            @endphp

            <p>
                <strong>Status:</strong>
                <span style="
                    display:inline-block;
                    padding:6px 12px;
                    border-radius:20px;
                    font-size:12px;
                    font-weight:bold;
                    color:white;
                    background-color: {{ $badgeColor }};
                ">
                    {{ $status }}
                </span>
            </p>

            @if($status === 'In Class')
                <p style="margin-top:5px;">
                    📘 <strong>{{ $statusData['subject'] }}</strong><br>
                    🏫 Room: {{ $statusData['room'] }}
                </p>
            @endif
        </div>
    @empty
        <p>No professors or faculty found.</p>
    @endforelse

                @endif
            </section>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
    function updateClock() {
        const now = new Date();
        document.getElementById('clock').innerText = now.toLocaleString();
    }

    setInterval(updateClock, 1000);
    updateClock();

    document.querySelectorAll('[data-detail-open]').forEach(function (button) {
        button.addEventListener('click', function () {
            const modal = document.getElementById(button.dataset.detailOpen);

            if (!modal) {
                return;
            }

            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
        });
    });

    function closeDetailModal(modal) {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    }

    document.querySelectorAll('.detail-modal').forEach(function (modal) {
        modal.addEventListener('click', function (event) {
            if (event.target === modal || event.target.closest('[data-detail-close]')) {
                closeDetailModal(modal);
            }
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') {
            return;
        }

        document.querySelectorAll('.detail-modal.is-open').forEach(closeDetailModal);
    });
    </script>
</body>
</html>
