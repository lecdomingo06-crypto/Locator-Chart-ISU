<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Teacher Tracker') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                color-scheme: light;
                --bg-top: #eef9f1;
                --bg-bottom: #dff1e4;
                --card: rgba(255, 255, 255, 0.74);
                --card-strong: rgba(255, 255, 255, 0.86);
                --card-border: rgba(255, 255, 255, 0.72);
                --text: #123524;
                --muted: #58705f;
                --green-900: #0c5c38;
                --green-800: #147247;
                --green-700: #198a52;
                --shadow: 0 30px 80px rgba(13, 72, 43, 0.15);
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
                    radial-gradient(circle at top left, rgba(118, 210, 149, 0.34), transparent 34%),
                    radial-gradient(circle at 82% 18%, rgba(51, 153, 97, 0.28), transparent 18%),
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
                width: 28rem;
                height: 28rem;
                top: -8rem;
                right: -7rem;
                background: rgba(42, 162, 90, 0.18);
            }

            body::after {
                width: 24rem;
                height: 24rem;
                left: -6rem;
                bottom: -8rem;
                background: rgba(15, 92, 56, 0.12);
            }

            .guest-page {
                position: relative;
                z-index: 1;
                min-height: 100vh;
                padding: 28px;
                overflow: hidden;
            }

            .guest-page.is-auth-focus {
                display: grid;
                place-items: center;
            }

            .guest-shell {
                max-width: 1220px;
                margin: 0 auto;
                min-height: calc(100vh - 56px);
                display: flex;
                flex-direction: column;
            }

            .guest-shell.is-auth-focus {
                position: relative;
                width: min(1280px, 100%);
                max-width: 1280px;
                min-height: calc(100vh - 56px);
                justify-content: center;
            }

            .auth-focus-scene {
                position: absolute;
                inset: 0;
                overflow: hidden;
                border-radius: 16px;
            }

            .auth-focus-scene::after {
                content: '';
                position: absolute;
                inset: 0;
                background: rgba(7, 24, 17, 0.42);
                backdrop-filter: blur(3px);
            }

            .auth-focus-scene-inner {
                position: absolute;
                inset: 0;
                display: grid;
                grid-template-rows: auto 1fr;
                gap: 24px;
                padding: 26px;
                filter: blur(7px) saturate(0.9);
                transform: scale(1.03);
                transform-origin: center;
            }

            .auth-focus-topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 18px;
                padding: 18px 22px;
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.72);
                border: 1px solid rgba(255, 255, 255, 0.82);
                box-shadow: 0 12px 36px rgba(16, 70, 45, 0.08);
            }

            .auth-focus-canvas {
                display: grid;
                grid-template-columns: minmax(0, 1.16fr) minmax(320px, 0.84fr);
                gap: 24px;
                min-height: 0;
            }

            .auth-focus-hero,
            .auth-focus-preview {
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 24px 56px rgba(14, 76, 46, 0.12);
            }

            .auth-focus-hero {
                padding: 34px;
                display: grid;
                align-content: start;
                gap: 18px;
                background:
                    linear-gradient(160deg, rgba(255, 255, 255, 0.82), rgba(239, 249, 242, 0.68)),
                    linear-gradient(180deg, #eef9f1, #dff1e4);
            }

            .auth-focus-hero-pill {
                width: fit-content;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.74);
                color: var(--green-900);
                font-size: 0.8rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .auth-focus-title {
                display: grid;
                gap: 8px;
            }

            .auth-focus-title span {
                display: block;
                font-size: clamp(2.6rem, 4vw, 4.8rem);
                line-height: 0.9;
                letter-spacing: -0.07em;
                font-weight: 800;
                color: #102219;
            }

            .auth-focus-title span:nth-child(2) {
                color: #148252;
            }

            .auth-focus-copy {
                max-width: 42ch;
                color: #5f6f67;
                font-size: 1rem;
                line-height: 1.65;
            }

            .auth-focus-actions {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 14px;
            }

            .auth-focus-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 46px;
                padding: 0 18px;
                border-radius: 8px;
                background: linear-gradient(135deg, #22bf70, #0f6f44);
                color: #ffffff;
                font-size: 0.94rem;
                font-weight: 700;
                box-shadow: 0 18px 34px rgba(15, 111, 68, 0.24);
            }

            .auth-focus-note {
                color: #0e5f3b;
                font-size: 0.92rem;
                font-weight: 600;
            }

            .auth-focus-preview {
                padding: 18px;
                display: grid;
                gap: 14px;
                align-content: start;
                background: linear-gradient(180deg, rgba(15, 93, 58, 0.96), rgba(11, 66, 41, 0.94));
            }

            .auth-focus-preview-pill {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                width: fit-content;
                padding: 8px 12px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.12);
                color: rgba(247, 252, 248, 0.86);
                font-size: 0.74rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .auth-focus-preview-pill::before {
                content: '';
                width: 8px;
                height: 8px;
                border-radius: 999px;
                background: #59ec98;
            }

            .auth-focus-preview-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .auth-focus-stat,
            .auth-focus-row {
                border-radius: 8px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                background: rgba(255, 255, 255, 0.08);
            }

            .auth-focus-stat {
                padding: 14px;
                min-height: 94px;
            }

            .auth-focus-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                padding: 14px;
            }

            .auth-focus-row-main {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .auth-focus-avatar {
                width: 40px;
                height: 40px;
                border-radius: 8px;
                background: linear-gradient(145deg, #f0fff5, #d2f0dc);
            }

            .auth-focus-lines {
                display: grid;
                gap: 7px;
            }

            .auth-focus-line {
                height: 10px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.22);
            }

            .auth-focus-line.is-short {
                width: 78px;
            }

            .auth-focus-line.is-medium {
                width: 126px;
            }

            .auth-focus-chip {
                width: 108px;
                height: 34px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.16);
            }

            .guest-topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 18px;
                padding: 18px 22px;
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.52);
                border: 1px solid rgba(255, 255, 255, 0.72);
                backdrop-filter: blur(18px);
                box-shadow: 0 12px 36px rgba(16, 70, 45, 0.08);
            }

            .guest-brand {
                display: inline-flex;
                align-items: center;
                gap: 16px;
            }

            .brand-mark {
                position: relative;
                width: 48px;
                height: 48px;
                border-radius: 8px;
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
                width: 12px;
                height: 12px;
                left: 10px;
                top: 11px;
                box-shadow: 15px 0 0 rgba(255, 255, 255, 0.95);
            }

            .brand-mark::after {
                width: 26px;
                height: 11px;
                left: 11px;
                bottom: 11px;
                border-radius: 999px 999px 8px 8px;
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
                font-size: 0.82rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .status-pill::before {
                content: '';
                width: 10px;
                height: 10px;
                border-radius: 999px;
                background: #22c55e;
                box-shadow: 0 0 0 6px rgba(34, 197, 94, 0.14);
            }

            .guest-hero {
                flex: 1;
                display: grid;
                grid-template-columns: minmax(0, 1fr) minmax(340px, 0.95fr);
                gap: 34px;
                align-items: start;
                padding: 34px 0 10px;
            }

            .guest-hero.is-auth-focus {
                grid-template-columns: minmax(0, 1fr);
                justify-content: center;
                align-items: center;
                min-height: calc(100vh - 56px);
                padding: 34px 18px;
                position: relative;
                z-index: 2;
            }

            .auth-card {
                padding: 26px;
                border-radius: 8px;
                background:
                    linear-gradient(180deg, rgba(255, 255, 255, 0.86), rgba(255, 255, 255, 0.72)),
                    rgba(255, 255, 255, 0.74);
                border: 1px solid var(--card-border);
                box-shadow: 0 24px 56px rgba(14, 76, 46, 0.1);
                backdrop-filter: blur(18px);
            }

            .guest-hero.is-auth-focus .auth-card {
                width: min(640px, 100%);
                margin: 0 auto;
                background:
                    linear-gradient(180deg, rgba(255, 255, 255, 0.94), rgba(255, 255, 255, 0.86)),
                    rgba(255, 255, 255, 0.88);
                box-shadow: 0 32px 80px rgba(9, 37, 25, 0.28);
            }

            .auth-card-body {
                padding: 12px;
            }

            .auth-stack {
                display: grid;
                gap: 24px;
            }

            .auth-heading {
                display: grid;
                gap: 14px;
            }

            .auth-campus-pill {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                width: fit-content;
                padding: 9px 15px;
                border-radius: 999px;
                border: 1px solid rgba(255, 255, 255, 0.72);
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.82), rgba(245, 252, 248, 0.72));
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72);
                color: var(--green-900);
                font-size: 0.78rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .auth-campus-icon {
                width: 24px;
                height: 24px;
                display: inline-grid;
                place-items: center;
                border-radius: 8px;
                background: rgba(20, 114, 71, 0.12);
            }

            .auth-campus-icon svg {
                width: 15px;
                height: 15px;
                stroke: currentColor;
                fill: none;
                stroke-width: 1.8;
                stroke-linecap: round;
                stroke-linejoin: round;
            }

            .auth-eyebrow {
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

            .auth-eyebrow::before {
                content: '';
                width: 28px;
                height: 1px;
                background: rgba(12, 92, 56, 0.32);
            }

            .auth-title {
                margin: 0;
                max-width: 12ch;
                font-size: clamp(2.2rem, 5vw, 3.6rem);
                line-height: 0.95;
                letter-spacing: -0.05em;
            }

            .auth-copy {
                margin: 0;
                color: var(--muted);
                font-size: 1rem;
                line-height: 1.68;
                max-width: 44ch;
            }

            .auth-form-grid {
                display: grid;
                gap: 18px;
            }

            .auth-group {
                display: grid;
                gap: 10px;
            }

            .auth-helper-row,
            .auth-actions,
            .auth-footer,
            .auth-check {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 14px;
                flex-wrap: wrap;
            }

            .auth-label {
                display: block;
                color: #214734;
                font-size: 0.95rem;
                font-weight: 600;
            }

            .auth-field {
                display: block;
                width: 100%;
                padding: 0.95rem 1.1rem;
                border-radius: 8px;
                border: 1px solid rgba(18, 53, 36, 0.12);
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.94), rgba(249, 253, 250, 0.9));
                color: var(--text);
                font-size: 1rem;
                box-shadow:
                    inset 0 1px 0 rgba(255, 255, 255, 0.82),
                    0 10px 24px rgba(13, 72, 43, 0.05);
                transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            }

            .auth-field::placeholder {
                color: #8ca192;
            }

            .auth-field:focus {
                outline: none;
                border-color: rgba(20, 114, 71, 0.65);
                box-shadow: 0 0 0 4px rgba(20, 114, 71, 0.12);
                background: rgba(255, 255, 255, 0.96);
            }

            .auth-select {
                appearance: none;
                background-image:
                    linear-gradient(45deg, transparent 50%, var(--green-900) 50%),
                    linear-gradient(135deg, var(--green-900) 50%, transparent 50%);
                background-position:
                    calc(100% - 18px) calc(50% - 3px),
                    calc(100% - 12px) calc(50% - 3px);
                background-size: 6px 6px, 6px 6px;
                background-repeat: no-repeat;
                padding-right: 2.8rem;
            }

            .auth-status {
                padding: 14px 16px;
                border-radius: 8px;
                background: rgba(217, 242, 226, 0.82);
                border: 1px solid rgba(25, 138, 82, 0.14);
                color: var(--green-900);
                font-weight: 600;
            }

            .auth-error {
                margin: 0;
                color: #c2410c;
            }

            .auth-link,
            .auth-text-link,
            .auth-inline-link {
                color: var(--green-800);
                font-weight: 600;
                text-decoration: none;
                transition: color 0.2s ease;
            }

            .auth-link:hover,
            .auth-text-link:hover,
            .auth-inline-link:hover {
                color: var(--green-900);
            }

            .auth-check-label {
                color: var(--muted);
                font-size: 0.95rem;
            }

            .auth-check-input {
                width: 1.1rem;
                height: 1.1rem;
                accent-color: var(--green-800);
            }

            .auth-button,
            .auth-button-secondary {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 15px 22px;
                border-radius: 8px;
                border: 0;
                font-size: 0.98rem;
                font-weight: 700;
                text-decoration: none;
                cursor: pointer;
                transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            }

            .auth-button:hover,
            .auth-button-secondary:hover {
                transform: translateY(-2px);
            }

            .auth-button {
                color: #fff;
                background: linear-gradient(135deg, var(--green-800), var(--green-900));
                box-shadow: 0 20px 36px rgba(15, 92, 56, 0.26);
            }

            .auth-button-secondary {
                color: var(--green-900);
                background: rgba(255, 255, 255, 0.78);
                border: 1px solid rgba(12, 92, 56, 0.16);
                box-shadow: 0 12px 24px rgba(14, 76, 46, 0.08);
            }

            .auth-button:focus-visible,
            .auth-button-secondary:focus-visible,
            .auth-link:focus-visible,
            .auth-text-link:focus-visible,
            .auth-inline-link:focus-visible {
                outline: 3px solid rgba(25, 138, 82, 0.28);
                outline-offset: 3px;
            }

            .auth-footer {
                color: var(--muted);
                font-size: 0.96rem;
            }

            .auth-footer-login {
                display: grid;
                gap: 6px;
                justify-items: start;
            }

            .auth-footer-login strong {
                display: block;
                font-size: 0.96rem;
                color: var(--text);
            }

            .auth-footer-login span {
                display: block;
                color: var(--muted);
                font-size: 0.94rem;
                line-height: 1.55;
            }

            .auth-form-grid > .auth-button,
            .auth-form-grid > .auth-button-secondary {
                width: 100%;
            }

            .guest-panel {
                position: relative;
                min-height: 540px;
                padding: 28px;
                border-radius: 8px;
                color: #eefcf2;
                background:
                    radial-gradient(circle at top right, rgba(74, 222, 128, 0.26), transparent 30%),
                    linear-gradient(180deg, #156941 0%, #0c5434 55%, #083924 100%);
                box-shadow: var(--shadow);
                overflow: hidden;
            }

            .guest-panel::before,
            .guest-panel::after {
                content: '';
                position: absolute;
                border-radius: 999px;
                pointer-events: none;
            }

            .guest-panel::before {
                width: 18rem;
                height: 18rem;
                top: -7rem;
                right: -4rem;
                background: rgba(219, 255, 228, 0.12);
            }

            .guest-panel::after {
                width: 16rem;
                height: 16rem;
                left: -5rem;
                bottom: -6rem;
                background: rgba(180, 250, 200, 0.1);
            }

            .panel-inner {
                position: relative;
                z-index: 1;
                display: grid;
                gap: 18px;
            }

            .panel-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 18px;
            }

            .panel-header h2 {
                margin: 0;
                font-size: 1.1rem;
                font-weight: 700;
                letter-spacing: -0.02em;
            }

            .live-tag {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 9px 14px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.1);
                font-size: 0.85rem;
                font-weight: 600;
            }

            .live-tag::before {
                content: '';
                width: 9px;
                height: 9px;
                border-radius: 999px;
                background: #4ade80;
            }

            .metric-band {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 14px;
            }

            .metric {
                padding: 18px;
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.12);
            }

            .metric span {
                display: block;
                font-size: 0.82rem;
                color: rgba(238, 252, 242, 0.72);
            }

            .metric strong {
                display: block;
                margin-top: 10px;
                font-size: 1.75rem;
                letter-spacing: -0.04em;
            }

            .panel-copy,
            .panel-feed {
                padding: 22px;
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.12);
            }

            .panel-copy strong,
            .panel-feed h3 {
                display: block;
                margin: 0 0 10px;
                font-size: 1.1rem;
                letter-spacing: -0.02em;
            }

            .panel-copy p {
                margin: 0;
                color: rgba(238, 252, 242, 0.76);
                line-height: 1.7;
            }

            .staff-list {
                display: grid;
                gap: 12px;
            }

            .staff-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                padding: 14px 16px;
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.08);
            }

            .staff-meta {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .staff-avatar {
                width: 40px;
                height: 40px;
                overflow: hidden;
                border-radius: 8px;
                display: grid;
                place-items: center;
                font-size: 0.9rem;
                font-weight: 700;
                color: #0d472c;
                background: linear-gradient(135deg, #effef4, #bff0cf);
            }

            .staff-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .staff-meta strong,
            .staff-meta span {
                display: block;
            }

            .staff-meta span,
            .staff-context {
                color: rgba(238, 252, 242, 0.72);
                font-size: 0.9rem;
            }

            .staff-side {
                display: grid;
                gap: 8px;
                justify-items: end;
                text-align: right;
            }

            .status-chip {
                padding: 7px 12px;
                border-radius: 999px;
                font-size: 0.76rem;
                font-weight: 700;
                letter-spacing: 0.04em;
                text-transform: uppercase;
                background: rgba(255, 255, 255, 0.12);
                color: #eefcf2;
            }

            .empty-state {
                padding: 18px;
                border-radius: 20px;
                background: rgba(255, 255, 255, 0.08);
                color: rgba(238, 252, 242, 0.78);
                line-height: 1.7;
            }

            @media (max-width: 980px) {
                .guest-hero {
                    grid-template-columns: 1fr;
                }

                .metric-band {
                    grid-template-columns: 1fr;
                }

                .auth-focus-canvas {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 720px) {
                .guest-page {
                    padding: 18px;
                }

                .guest-page.is-auth-focus {
                    padding: 12px;
                }

                .guest-shell {
                    min-height: calc(100vh - 36px);
                }

                .guest-topbar {
                    padding: 16px 18px;
                    border-radius: 8px;
                    align-items: flex-start;
                    flex-direction: column;
                }

                .guest-panel,
                .auth-card {
                    padding: 22px;
                    border-radius: 8px;
                }

                .auth-focus-scene-inner {
                    padding: 16px;
                    gap: 16px;
                }

                .auth-focus-topbar,
                .auth-focus-hero,
                .auth-focus-preview {
                    padding: 18px;
                }

                .panel-header {
                    flex-direction: column;
                    align-items: flex-start;
                }
            }

            @media (max-width: 540px) {
                .guest-brand {
                    gap: 12px;
                }

                .brand-mark {
                    width: 48px;
                    height: 48px;
                    border-radius: 16px;
                }

                .guest-hero {
                    gap: 22px;
                    padding-top: 24px;
                }

                .auth-focus-title span {
                    font-size: clamp(2.1rem, 9vw, 3rem);
                }

                .auth-focus-actions {
                    align-items: stretch;
                }

                .auth-focus-button {
                    width: 100%;
                }

                .auth-focus-preview-grid {
                    grid-template-columns: 1fr;
                }

                .auth-actions,
                .auth-helper-row,
                .auth-footer,
                .staff-row {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .auth-button,
                .auth-button-secondary {
                    width: 100%;
                }

                .staff-side {
                    justify-items: start;
                    text-align: left;
                }
            }
        </style>
    </head>
    <body>
        @php
            $showGuestPanel = ! request()->routeIs('login');
            $guestStatusStyles = [
                'Available' => 'background: rgba(74, 222, 128, 0.18); color: #dfffea;',
                'In Class' => 'background: rgba(96, 165, 250, 0.18); color: #dcecff;',
                'On Leave' => 'background: rgba(248, 113, 113, 0.18); color: #ffe0e0;',
                'Emergency' => 'background: rgba(251, 191, 36, 0.18); color: #fff1c7;',
                'On Meeting' => 'background: rgba(196, 181, 253, 0.18); color: #f0e8ff;',
            ];
        @endphp

        <div class="guest-page{{ $showGuestPanel ? '' : ' is-auth-focus' }}">
            <div class="guest-shell{{ $showGuestPanel ? '' : ' is-auth-focus' }}">
                @unless ($showGuestPanel)
                    <div class="auth-focus-scene" aria-hidden="true">
                        <div class="auth-focus-scene-inner">
                            <div class="auth-focus-topbar">
                                <div class="guest-brand">
                                    <div class="brand-mark" aria-hidden="true"></div>
                                    <div class="brand-copy">
                                        <strong>Teacher Tracking System</strong>
                                        <span>Live faculty visibility for classrooms, offices, and student support.</span>
                                    </div>
                                </div>

                                <div class="status-pill">Campus ready</div>
                            </div>

                            <div class="auth-focus-canvas">
                                <section class="auth-focus-hero">
                                    <div class="auth-focus-hero-pill">Isabela State University Cauayan Campus</div>

                                    <div class="auth-focus-title">
                                        <span>A smarter way to track</span>
                                        <span>teacher availability</span>
                                        <span>in real time.</span>
                                    </div>

                                    <div class="auth-focus-copy">
                                        Check who is available, where they are, and which team can help next without hallway searching, paper logs, or front desk guesswork.
                                    </div>

                                    <div class="auth-focus-actions">
                                        <div class="auth-focus-button">Log in</div>
                                        <div class="auth-focus-note">Trusted access for teachers, faculty, students, and admins.</div>
                                    </div>
                                </section>

                                <aside class="auth-focus-preview">
                                    <div class="auth-focus-preview-pill">Available teachers</div>

                                    <div class="auth-focus-preview-grid">
                                        <div class="auth-focus-stat"></div>
                                        <div class="auth-focus-stat"></div>
                                        <div class="auth-focus-stat"></div>
                                        <div class="auth-focus-stat"></div>
                                    </div>

                                    <div class="auth-focus-row">
                                        <div class="auth-focus-row-main">
                                            <div class="auth-focus-avatar"></div>
                                            <div class="auth-focus-lines">
                                                <div class="auth-focus-line is-medium"></div>
                                                <div class="auth-focus-line is-short"></div>
                                            </div>
                                        </div>

                                        <div class="auth-focus-chip"></div>
                                    </div>

                                    <div class="auth-focus-row">
                                        <div class="auth-focus-row-main">
                                            <div class="auth-focus-avatar"></div>
                                            <div class="auth-focus-lines">
                                                <div class="auth-focus-line is-medium"></div>
                                                <div class="auth-focus-line is-short"></div>
                                            </div>
                                        </div>

                                        <div class="auth-focus-chip"></div>
                                    </div>

                                    <div class="auth-focus-row">
                                        <div class="auth-focus-row-main">
                                            <div class="auth-focus-avatar"></div>
                                            <div class="auth-focus-lines">
                                                <div class="auth-focus-line is-medium"></div>
                                                <div class="auth-focus-line is-short"></div>
                                            </div>
                                        </div>

                                        <div class="auth-focus-chip"></div>
                                    </div>
                                </aside>
                            </div>
                        </div>
                    </div>
                @endunless

                @if ($showGuestPanel)
                    <header class="guest-topbar">
                        <div class="guest-brand">
                            <div class="brand-mark" aria-hidden="true"></div>
                            <div class="brand-copy">
                                <strong>Teacher Tracking System</strong>
                                <span>Live faculty visibility for classrooms, offices, and student support.</span>
                            </div>
                        </div>

                        <div class="status-pill">Campus ready</div>
                    </header>
                @endif

                <main class="guest-hero{{ $showGuestPanel ? '' : ' is-auth-focus' }}">
                    <section class="auth-card">
                        <div class="auth-card-body">
                            {{ $slot }}
                        </div>
                    </section>

                    @if ($showGuestPanel)
                        <aside class="guest-panel" aria-label="Teacher tracking snapshot">
                            <div class="panel-inner">
                                <div class="panel-header">
                                    <h2>Teacher Tracking Snapshot</h2>
                                    <div class="live-tag">{{ $guestSnapshotTotals['tracked'] }} tracked</div>
                                </div>

                                <div class="metric-band">
                                    <div class="metric">
                                        <span>Teachers</span>
                                        <strong>{{ $guestSnapshotTotals['teachers'] }}</strong>
                                    </div>
                                    <div class="metric">
                                        <span>Faculty</span>
                                        <strong>{{ $guestSnapshotTotals['faculty'] }}</strong>
                                    </div>
                                    <div class="metric">
                                        <span>Tracked Staff</span>
                                        <strong>{{ $guestSnapshotTotals['tracked'] }}</strong>
                                    </div>
                                </div>

                                <section class="panel-copy">
                                    <strong>Same green interface as the welcome page</strong>
                                    <p>
                                        Sign in or recover your access inside the same calm teacher-tracking
                                        experience. The auth portal now follows the same visual system as your landing page.
                                    </p>
                                </section>

                                <section class="panel-feed">
                                    <h3>Live staff feed</h3>

                                    <div class="staff-list">
                                        @forelse ($guestSnapshotStaff as $staffMember)
                                            <div class="staff-row">
                                                <div class="staff-meta">
                                                    <div class="staff-avatar">
                                                        @if (!empty($staffMember['profile_picture_url']))
                                                            <img src="{{ $staffMember['profile_picture_url'] }}" alt="{{ $staffMember['name'] }}">
                                                        @else
                                                            {{ $staffMember['initials'] }}
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <strong>{{ $staffMember['name'] }}</strong>
                                                        <span>{{ $staffMember['meta'] ?: 'Teacher tracking account' }}</span>
                                                    </div>
                                                </div>

                                                <div class="staff-side">
                                                    <div class="status-chip" style="{{ $guestStatusStyles[$staffMember['status']] ?? 'background: rgba(255, 255, 255, 0.12); color: #eefcf2;' }}">
                                                        {{ $staffMember['status'] }}
                                                    </div>
                                                    <div class="staff-context">{{ $staffMember['context'] }}</div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="empty-state">
                                                No teacher or faculty accounts are available yet. Once records are added,
                                                this panel will mirror the same live snapshot shown on the welcome page.
                                            </div>
                                        @endforelse
                                    </div>
                                </section>
                            </div>
                        </aside>
                    @endif
                </main>
            </div>
        </div>
    </body>
</html>
