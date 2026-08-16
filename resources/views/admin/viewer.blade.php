<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="15">
    <title>Admin Viewer</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800" rel="stylesheet" />

    <style>
        :root {
            color-scheme: light;
            --bg: #edf7f0;
            --card: rgba(255, 255, 255, 0.92);
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

        .admin-shell {
            min-height: 100vh;
        }

        .admin-topbar {
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
            background:
                radial-gradient(circle at 18% 0%, rgba(109, 212, 144, 0.22), transparent 32%),
                linear-gradient(135deg, #094629 0%, #0c5c38 54%, #147247 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 16px 34px rgba(8, 58, 35, 0.2);
        }

        .admin-brand,
        .admin-session,
        .admin-chip,
        .sidebar-brand,
        .sidebar-link,
        .logout-button {
            display: inline-flex;
            align-items: center;
        }

        .admin-brand {
            gap: 14px;
            color: inherit;
            text-decoration: none;
        }

        .admin-brand-mark,
        .sidebar-mark {
            display: grid;
            place-items: center;
            border-radius: 16px;
            color: var(--green-900);
            background: rgba(255, 255, 255, 0.92);
            font-weight: 800;
            box-shadow: inset 0 0 0 1px rgba(12, 92, 56, 0.08), 0 10px 24px rgba(4, 43, 25, 0.14);
        }

        .admin-brand-mark {
            width: 48px;
            height: 48px;
            font-size: 0.82rem;
        }

        .admin-brand-copy,
        .sidebar-copy {
            display: grid;
            gap: 3px;
        }

        .admin-brand-copy strong {
            font-size: 1rem;
            letter-spacing: -0.02em;
        }

        .admin-brand-copy span,
        .admin-chip,
        .logout-button {
            font-size: 0.86rem;
            font-weight: 700;
        }

        .admin-brand-copy span {
            color: rgba(239, 252, 243, 0.76);
        }

        .admin-session {
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .admin-chip {
            min-height: 34px;
            padding: 0 13px;
            border-radius: 999px;
            color: rgba(239, 252, 243, 0.95);
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
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
            font-family: inherit;
            cursor: pointer;
            box-shadow: 0 12px 22px rgba(5, 51, 30, 0.16);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .logout-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 26px rgba(5, 51, 30, 0.2);
        }

        .admin-layout {
            display: grid;
            grid-template-columns: 244px minmax(0, 1fr);
            gap: 26px;
            align-items: start;
        }

        .admin-sidebar {
            position: sticky;
            top: 94px;
            min-height: calc(100vh - 112px);
            margin-left: 16px;
            padding: 18px 14px;
            border-radius: 0 22px 22px 0;
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(12, 92, 56, 0.1);
            border-left: 0;
            box-shadow: 0 18px 40px rgba(12, 92, 56, 0.1);
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

        .sidebar-copy strong {
            font-size: 1rem;
            letter-spacing: -0.02em;
        }

        .sidebar-copy span,
        .sidebar-note {
            color: var(--muted);
            font-size: 0.88rem;
            line-height: 1.5;
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
            border-radius: 12px;
            color: var(--text);
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 800;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
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
            background: rgba(221, 244, 228, 0.7);
            transform: translateX(2px);
        }

        .sidebar-link.is-active {
            color: #ffffff;
            background: linear-gradient(135deg, var(--green-800), var(--green-900));
            box-shadow: 0 12px 24px rgba(12, 92, 56, 0.16);
        }

        .admin-main .page {
            padding: 24px 28px 32px 0;
        }

        .admin-main .shell {
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
            .admin-topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .admin-session {
                justify-content: flex-start;
            }

            .admin-layout {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .admin-sidebar {
                position: static;
                min-height: 0;
                margin: 16px 16px 0;
                border-left: 1px solid rgba(12, 92, 56, 0.1);
                border-radius: 20px;
            }

            .sidebar-nav {
                grid-template-columns: repeat(5, minmax(0, 1fr));
            }

            .admin-main .page {
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
            .admin-topbar {
                padding: 14px 16px;
            }

            .admin-brand-copy span {
                display: none;
            }

            .admin-session {
                width: 100%;
            }

            .admin-chip,
            .logout-form,
            .logout-button {
                width: 100%;
            }

            .logout-button {
                min-height: 42px;
            }

            .admin-sidebar {
                margin: 14px 14px 0;
                padding: 16px;
            }

            .sidebar-brand {
                align-items: flex-start;
            }

            .sidebar-nav {
                grid-template-columns: 1fr;
            }

            .admin-main .page {
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

    <div class="admin-shell">
        <header class="admin-topbar">
            <a href="{{ route('admin.viewer') }}" class="admin-brand">
                <div class="admin-brand-mark" aria-hidden="true"><img src="{{ asset('images/isulogo.jpg') }}" alt=""></div>
                <div class="admin-brand-copy">
                    <strong>Professor Tracking System</strong>
                    <span>Admin dashboard</span>
                </div>
            </a>

            <div class="admin-session">
                <span class="admin-chip">Role: Admin</span>
                <span class="admin-chip">Signed in as {{ Auth::user()->full_name ?: Auth::user()->username }}</span>
            </div>
        </header>

        <div class="admin-layout">
            <aside class="admin-sidebar">
                <div class="sidebar-brand">
                    <div class="sidebar-mark" aria-hidden="true"><img src="{{ asset('images/isulogo.jpg') }}" alt=""></div>
                    <div class="sidebar-copy">
                        <strong>Admin Panel</strong>
                        <span>Professor and faculty monitor</span>
                    </div>
                </div>

                <span class="sidebar-section-label">Workspace</span>
                <nav class="sidebar-nav" aria-label="Admin workspace">
                    <a href="{{ route('admin.viewer') }}" class="sidebar-link{{ request()->routeIs('admin.viewer') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M2.5 12s3.5-5.5 9.5-5.5 9.5 5.5 9.5 5.5-3.5 5.5-9.5 5.5S2.5 12 2.5 12Z"></path>
                            <path d="M12 15a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z"></path>
                        </svg>
                        <span>Live Viewer</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="sidebar-link{{ request()->routeIs('admin.users.index', 'admin.users.edit') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M9 11a4 4 0 1 0 0-8a4 4 0 0 0 0 8Z"></path>
                            <path d="M2.5 20c.7-4 2.8-6 6.5-6s5.8 2 6.5 6"></path>
                            <path d="M17 8h4"></path>
                            <path d="M19 6v4"></path>
                        </svg>
                        <span>User Management</span>
                    </a>
                    <a href="{{ route('admin.attendance.index') }}" class="sidebar-link{{ request()->routeIs('admin.attendance.*') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 7v5l3 2"></path>
                            <path d="M7 21h10"></path>
                        </svg>
                        <span>Attendance</span>
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="sidebar-link{{ request()->routeIs('admin.users.create') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 12a4 4 0 1 0 0-8a4 4 0 0 0 0 8Z"></path>
                            <path d="M4.5 20c.8-3.8 3.4-5.8 7.5-5.8"></path>
                            <path d="M18 15v5"></path>
                            <path d="M20.5 17.5h-5"></path>
                        </svg>
                        <span>Create Account</span>
                    </a>

                    <a href="{{ route('admin.student_registrations.index') }}" class="sidebar-link{{ request()->routeIs('admin.student_registrations.*') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8 6.5h8"></path>
                            <path d="M8 11h8"></path>
                            <path d="M8 15.5h5"></path>
                            <path d="M5.5 3.5h13a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2v-13a2 2 0 0 1 2-2Z"></path>
                        </svg>
                        <span>Student Requests</span>
                    </a>

                    <a href="{{ route('admin.status') }}" class="sidebar-link{{ request()->routeIs('admin.status') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M4 7h10"></path>
                            <path d="M18 7h2"></path>
                            <path d="M4 17h2"></path>
                            <path d="M10 17h10"></path>
                            <path d="M14 5v4"></path>
                            <path d="M10 15v4"></path>
                        </svg>
                        <span>Status Override</span>
                    </a>

                    <a href="{{ route('academic_events.create') }}" class="sidebar-link{{ request()->routeIs('academic_events.*') ? ' is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M7 3v4"></path>
                            <path d="M17 3v4"></path>
                            <path d="M4 8h16"></path>
                            <path d="M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"></path>
                            <path d="M8 13h8"></path>
                            <path d="M8 17h5"></path>
                        </svg>
                        <span>Academic Events</span>
                    </a>
                </nav>
            <x-sidebar-account-footer />
            </aside>
<x-responsive-sidebar-control />

            <main class="admin-main">
                <div class="page">
                    <div class="shell">
            <section class="topbar">
                <div class="hero-copy">
                    <div class="eyebrow">Admin Viewer</div>
                    <h1>Admin Live Viewer</h1>
                    <p>
                        A cleaner live directory for monitoring staff visibility, status changes, and current availability across departments.
                    </p>
                </div>

                <div class="hero-stats" aria-label="Viewer summary">
                    <div class="hero-stat hero-stat-wide">
                        <span>Live Monitoring Active</span>
                        <strong id="clock"></strong>
                        <small>The page refreshes automatically every 15 seconds.</small>
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
                    <p>Use the same viewer tools in a cleaner admin layout.</p>
                </div>

                <form method="GET" action="{{ route('admin.viewer') }}" class="filter-form">
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
                                <strong>{{ $user->department->name ?? 'No Dept' }}</strong>
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

                                            @if($statusData['event_note'])
                                                <div class="class-line">
                                                    <span>Note</span>
                                                    <div>{{ $statusData['event_note'] }}</div>
                                                </div>
                                            @endif

                                            @if($statusData['event_purpose'])
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

@if(false)

<h1>📊 Admin Live Viewer</h1>
<h3 id="clock"></h3>

<!-- 🔍 SEARCH + FILTER -->
<form method="GET" action="{{ route('admin.viewer') }}" style="margin-bottom:20px;">
    <input type="text" name="search" placeholder="Search name..." value="{{ $search }}">

    <button type="submit">Filter</button>
</form>

<!-- 📊 STATUS COUNTS -->
@php
    $counts = [
        'Available' => 0,
        'In Class' => 0,
        'On Leave' => 0,
        'Emergency' => 0,
        'On Meeting' => 0,
    ];

    foreach ($users as $u) {
        $counts[$u->live_status['status']]++;
    }
@endphp

<div style="margin-bottom:20px;">
    @foreach($counts as $key => $value)
        <span style="margin-right:15px;">
            <strong>{{ $key }}:</strong> {{ $value }}
        </span>
    @endforeach
</div>

<hr>

<!-- 👥 USER GRID -->
<div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(250px, 1fr)); gap:15px;">

@foreach($users as $user)

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

    <div style="background:white; border-radius:10px; padding:15px; box-shadow:0 2px 6px rgba(0,0,0,0.1);">

        <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">

    <!-- PROFILE PICTURE -->
    @if($user->profile_picture)
        <img src="{{ asset('storage/' . $user->profile_picture) }}"
             alt="Profile"
             style="
width:60px;
height:60px;
object-fit:cover;
border-radius:12px;
border:2px solid #eee;
box-shadow:0 2px 6px rgba(0,0,0,0.1);
">
    @else
        <div style="
            width:60px;
            height:60px;
            background:#ddd;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:8px;
            font-size:12px;
        ">
            No Image
        </div>
    @endif

    <!-- USER INFO -->
    <div>
        <p style="margin:0;"><strong>{{ $user->full_name }}</strong></p>
        <p style="margin:0; font-size:12px; color:gray;">
            {{ ucfirst($user->role) }} • {{ $user->username }}
        </p>
    </div>

</div>

        <span style="
            display:inline-block;
            padding:5px 10px;
            border-radius:15px;
            color:white;
            font-size:12px;
            background-color: {{ $badgeColor }};
        ">
            {{ $status }}
        </span>

        @if($status === 'In Class')
            <p style="margin-top:8px; font-size:13px;">
                📘 {{ $statusData['subject'] }}<br>
                🏫 {{ $statusData['room'] }}
            </p>
        @endif

    </div>

@endforeach

</div>

<script>
function updateClock() {
    const now = new Date();
    document.getElementById('clock').innerText = now.toLocaleString();
}
setInterval(updateClock, 1000);
updateClock();
</script>

@endif
</body>
</html>
