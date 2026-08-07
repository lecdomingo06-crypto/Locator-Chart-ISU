<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Professor Tracker') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800" rel="stylesheet" />
        <link
            rel="stylesheet"
            href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                color-scheme: light;
                --bg-top: #eef9f1;
                --bg-bottom: #dff1e4;
                --card: rgba(255, 255, 255, 0.74);
                --card-border: rgba(255, 255, 255, 0.72);
                --text: #123524;
                --muted: #58705f;
                --green-900: #0c5c38;
                --green-800: #147247;
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

            .page {
                position: relative;
                z-index: 1;
                min-height: 100vh;
                padding: 28px;
                overflow: hidden;
            }

            .shell {
                max-width: 1220px;
                margin: 0 auto;
                min-height: calc(100vh - 56px);
                display: flex;
                flex-direction: column;
            }

            .topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 18px;
                padding: 18px 22px;
                border-radius: 28px;
                background: rgba(255, 255, 255, 0.52);
                border: 1px solid rgba(255, 255, 255, 0.72);
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

            .hero {
                flex: 1;
                display: grid;
                grid-template-columns: minmax(0, 1.05fr) minmax(320px, 0.95fr);
                gap: 34px;
                align-items: center;
                padding: 34px 0 10px;
            }

            .card {
                padding: 30px;
                border-radius: 34px;
                background: var(--card);
                border: 1px solid var(--card-border);
                box-shadow: 0 16px 40px rgba(14, 76, 46, 0.08);
                backdrop-filter: blur(14px);
            }

            .eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 12px;
                padding: 10px 16px;
                margin-bottom: 24px;
                border-radius: 999px;
                background: rgba(12, 92, 56, 0.08);
                color: var(--green-900);
                max-width: 100%;
                font-size: 0.82rem;
                font-weight: 700;
                letter-spacing: 0.03em;
                line-height: 1.35;
            }

            .eyebrow::before {
                content: '';
                flex: none;
                width: 18px;
                height: 18px;
                background: currentColor;
                opacity: 0.78;
                -webkit-mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='black' d='M12 3 2 8v2h20V8L12 3Zm-7 8v7H3v2h18v-2h-2v-7h-2v7h-2v-7h-2v7h-2v-7H9v7H7v-7H5Z'/%3E%3C/svg%3E");
                mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='black' d='M12 3 2 8v2h20V8L12 3Zm-7 8v7H3v2h18v-2h-2v-7h-2v7h-2v-7h-2v7h-2v-7H9v7H7v-7H5Z'/%3E%3C/svg%3E");
                -webkit-mask-repeat: no-repeat;
                mask-repeat: no-repeat;
                -webkit-mask-position: center;
                mask-position: center;
                -webkit-mask-size: contain;
                mask-size: contain;
            }

            h1 {
                margin: 0;
                font-size: clamp(2.7rem, 6vw, 4.8rem);
                line-height: 0.92;
                letter-spacing: -0.05em;
            }

            .lead {
                margin: 22px 0 0;
                max-width: 54ch;
                color: var(--muted);
                font-size: 1.05rem;
                line-height: 1.8;
            }

            .actions {
                display: flex;
                gap: 14px;
                flex-wrap: wrap;
                margin-top: 32px;
            }

            .button,
            .button-secondary {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 15px 22px;
                border-radius: 16px;
                border: 0;
                font-size: 0.98rem;
                font-weight: 700;
                text-decoration: none;
                cursor: pointer;
                transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            }

            .button:hover,
            .button-secondary:hover {
                transform: translateY(-2px);
            }

            .button {
                color: #fff;
                background: linear-gradient(135deg, var(--green-800), var(--green-900));
                box-shadow: 0 18px 30px rgba(15, 92, 56, 0.24);
            }

            .button-secondary {
                color: var(--green-900);
                background: rgba(255, 255, 255, 0.78);
                border: 1px solid rgba(12, 92, 56, 0.16);
                box-shadow: 0 12px 24px rgba(14, 76, 46, 0.08);
            }

            .button:focus-visible,
            .button-secondary:focus-visible {
                outline: 3px solid rgba(25, 138, 82, 0.28);
                outline-offset: 3px;
            }

            .grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 16px;
                margin-top: 34px;
            }

            .mini-card {
                padding: 18px;
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.72);
                border: 1px solid rgba(255, 255, 255, 0.72);
                box-shadow: 0 16px 40px rgba(14, 76, 46, 0.08);
            }

            .mini-card span {
                display: block;
                color: var(--muted);
                font-size: 0.84rem;
                text-transform: uppercase;
                letter-spacing: 0.08em;
            }

            .mini-card strong {
                display: block;
                margin-top: 10px;
                font-size: 1.15rem;
                letter-spacing: -0.03em;
            }

            .panel {
                position: relative;
                min-height: 520px;
                padding: 28px;
                border-radius: 34px;
                color: #eefcf2;
                background:
                    radial-gradient(circle at top right, rgba(74, 222, 128, 0.26), transparent 30%),
                    linear-gradient(180deg, #156941 0%, #0c5434 55%, #083924 100%);
                box-shadow: var(--shadow);
                overflow: hidden;
            }

            .panel::before,
            .panel::after {
                content: '';
                position: absolute;
                border-radius: 999px;
                pointer-events: none;
            }

            .panel::before {
                width: 18rem;
                height: 18rem;
                top: -7rem;
                right: -4rem;
                background: rgba(219, 255, 228, 0.12);
            }

            .panel::after {
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

            .panel-tag {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                width: fit-content;
                padding: 9px 14px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.1);
                font-size: 0.85rem;
                font-weight: 600;
            }

            .panel-tag::before {
                content: '';
                width: 9px;
                height: 9px;
                border-radius: 999px;
                background: #4ade80;
            }

            .panel h2 {
                margin: 0;
                font-size: clamp(2rem, 5vw, 3rem);
                line-height: 1;
                letter-spacing: -0.04em;
            }

            .panel p {
                margin: 0;
                color: rgba(238, 252, 242, 0.76);
                line-height: 1.75;
            }

            .panel-list {
                display: grid;
                gap: 14px;
            }

            .panel-item {
                padding: 18px;
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.12);
            }

            .panel-item strong {
                display: block;
                margin-bottom: 8px;
                font-size: 1rem;
            }

            .panel-item span {
                color: rgba(238, 252, 242, 0.72);
                font-size: 0.95rem;
                line-height: 1.65;
            }

            .masthead {
                display: grid;
                grid-template-columns: minmax(0, 1fr) auto;
                gap: 18px;
                align-items: center;
            }

            .brand-band {
                display: flex;
                align-items: center;
                gap: 18px;
                padding: 20px 24px;
                border-radius: 30px;
                background: rgba(255, 255, 255, 0.58);
                border: 1px solid rgba(255, 255, 255, 0.78);
                backdrop-filter: blur(14px);
                box-shadow: 0 14px 36px rgba(16, 70, 45, 0.08);
            }

            .studio {
                display: grid;
                grid-template-columns: minmax(0, 1fr);
                gap: 28px;
                margin-top: 14px;
                align-items: stretch;
            }

            .hero-panel {
                position: relative;
                overflow: hidden;
                padding: 34px;
                border-radius: 38px;
                background: linear-gradient(165deg, rgba(255, 255, 255, 0.93), rgba(238, 249, 242, 0.84));
                border: 1px solid rgba(255, 255, 255, 0.82);
                box-shadow: var(--shadow);
                min-height: 600px;
                height: 100%;
            }

            .hero-panel::before {
                content: '';
                position: absolute;
                width: 22rem;
                height: 22rem;
                left: -8%;
                bottom: -10%;
                border-radius: 46% 54% 58% 42%;
                background: linear-gradient(180deg, rgba(29, 138, 87, 0.16), rgba(12, 92, 56, 0.06));
            }

            .hero-panel::after {
                content: '';
                position: absolute;
                right: -3rem;
                top: -3rem;
                width: 14rem;
                height: 14rem;
                border-radius: 38% 62% 60% 40%;
                background: rgba(162, 227, 183, 0.34);
            }

            .hero-inner {
                position: relative;
                z-index: 1;
                display: grid;
                gap: 24px;
            }

            .hero-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 20px;
                align-items: start;
            }

            .hero-copy h1 {
                max-width: 9ch;
            }

            .hero-greeting {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                width: fit-content;
                padding: 10px 16px;
                border-radius: 999px;
                background: rgba(12, 92, 56, 0.08);
                border: 1px solid rgba(12, 92, 56, 0.1);
                color: var(--green-900);
                font-size: 0.9rem;
                font-weight: 700;
            }

            .hero-greeting::before {
                content: '';
                width: 10px;
                height: 10px;
                border-radius: 999px;
                background: #22c55e;
                box-shadow: 0 0 0 5px rgba(34, 197, 94, 0.12);
            }

            .hero-copy p {
                margin: 22px 0 0;
                max-width: 54ch;
                color: var(--muted);
                font-size: 1.04rem;
                line-height: 1.82;
            }

            .campus-map-card {
                position: relative;
                overflow: hidden;
                padding: 24px;
                border-radius: 30px;
                background: linear-gradient(180deg, rgba(9, 84, 51, 0.96), rgba(8, 57, 36, 0.94));
                color: #eefcf2;
                box-shadow: 0 24px 42px rgba(8, 57, 36, 0.18);
                display: grid;
                gap: 18px;
                align-content: start;
            }

            .campus-map-card--sidebar {
                height: 100%;
                min-height: 600px;
            }

            .campus-map-card::before {
                content: '';
                position: absolute;
                right: -3rem;
                top: -3rem;
                width: 12rem;
                height: 12rem;
                border-radius: 42% 58% 60% 40%;
                background: rgba(145, 225, 169, 0.14);
            }

            .campus-map-card::after {
                content: '';
                position: absolute;
                left: -4rem;
                bottom: -5rem;
                width: 14rem;
                height: 14rem;
                border-radius: 58% 42% 36% 64%;
                background: rgba(145, 225, 169, 0.08);
            }

            .campus-map-copy,
            .campus-map-layout {
                position: relative;
                z-index: 1;
            }

            .campus-map-copy {
                display: flex;
                align-items: end;
                justify-content: space-between;
                gap: 18px;
                margin-bottom: 0;
            }

            .campus-map-copy--stack {
                display: grid;
                gap: 14px;
                align-items: start;
            }

            .campus-map-kicker {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                width: fit-content;
                padding: 9px 14px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.1);
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .campus-map-kicker::before {
                content: '';
                width: 10px;
                height: 10px;
                border-radius: 999px;
                background: #4ade80;
            }

            .campus-map-copy h2 {
                margin: 12px 0 0;
                font-size: 1.7rem;
                letter-spacing: -0.04em;
            }

            .campus-map-copy p {
                max-width: 30rem;
                margin: 0;
                color: rgba(238, 252, 242, 0.74);
                line-height: 1.7;
            }

            .campus-map-layout {
                display: grid;
                grid-template-columns: minmax(0, 1.08fr) minmax(250px, 0.92fr);
                gap: 18px;
                align-items: start;
            }

            .campus-map-layout--sidebar {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .campus-map-surface {
                position: relative;
                height: 340px;
                min-height: 340px;
                border-radius: 28px;
                overflow: hidden;
                border: 1px solid rgba(255, 255, 255, 0.22);
                box-shadow: 0 18px 30px rgba(8, 57, 36, 0.14);
            }

            .campus-map-surface--sidebar {
                height: 420px;
                min-height: 420px;
            }

            .map-compass {
                position: absolute;
                top: 16px;
                right: 16px;
                z-index: 500;
                display: grid;
                place-items: center;
                width: 38px;
                height: 38px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.88);
                color: var(--green-900);
                font-size: 0.9rem;
                font-weight: 800;
                box-shadow: 0 10px 22px rgba(12, 92, 56, 0.12);
            }

            .campus-map-live {
                width: 100%;
                height: 100%;
                min-height: 0;
            }

            .campus-map-live .leaflet-control-zoom {
                border: 0;
                box-shadow: 0 14px 24px rgba(8, 57, 36, 0.12);
            }

            .campus-map-live .leaflet-control-zoom a {
                color: var(--green-900);
                border: 0;
            }

            .campus-map-live .leaflet-control-attribution {
                background: rgba(255, 255, 255, 0.82);
                color: #315844;
                border-radius: 12px 0 0 0;
                padding: 4px 8px;
            }

            .campus-map-tooltip {
                background: rgba(255, 255, 255, 0.96);
                color: var(--green-900);
                border: 1px solid rgba(12, 92, 56, 0.12);
                border-radius: 999px;
                box-shadow: 0 12px 24px rgba(8, 57, 36, 0.12);
                font-family: 'Outfit', sans-serif;
                font-size: 0.78rem;
                font-weight: 800;
                letter-spacing: 0.04em;
                padding: 7px 10px;
            }

            .campus-map-tooltip::before {
                border-top-color: rgba(255, 255, 255, 0.96);
            }

            .campus-map-carousel {
                position: relative;
                overflow: hidden;
                padding: 6px 2px 4px;
                z-index: 1;
                -webkit-mask-image: linear-gradient(90deg, transparent, #000 8%, #000 92%, transparent);
                mask-image: linear-gradient(90deg, transparent, #000 8%, #000 92%, transparent);
            }

            .campus-map-carousel-track {
                display: flex;
                width: max-content;
                will-change: transform;
                animation: campusLegendCarousel 26s linear infinite;
            }

            .campus-map-carousel:hover .campus-map-carousel-track {
                animation-play-state: paused;
            }

            .campus-map-carousel-group {
                display: flex;
                gap: 14px;
                padding-right: 14px;
            }

            .campus-map-legend-card {
                display: flex;
                align-items: center;
                gap: 12px;
                flex: 0 0 208px;
                min-height: 86px;
                padding: 16px 18px;
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.13);
                border: 1px solid rgba(255, 255, 255, 0.14);
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06);
            }

            .campus-map-legend-copy {
                display: grid;
                gap: 4px;
            }

            .campus-map-legend-card strong {
                margin: 0;
                font-size: 0.96rem;
                letter-spacing: 0.02em;
            }

            .campus-map-legend-dot {
                flex: none;
                width: 12px;
                height: 12px;
                border-radius: 999px;
                background: var(--pin-accent, #4ade80);
                box-shadow: 0 0 0 5px rgba(255, 255, 255, 0.08);
            }

            .campus-map-legend-copy span {
                color: rgba(238, 252, 242, 0.72);
                font-size: 0.88rem;
            }

            .campus-map-pin-dot {
                width: 14px;
                height: 14px;
                border-radius: 999px;
                background: var(--pin-accent, #22c55e);
                border: 2px solid rgba(255, 255, 255, 0.94);
                box-shadow: 0 10px 18px rgba(8, 57, 36, 0.2);
            }

            .campus-map-center-dot {
                content: '';
                width: 12px;
                height: 12px;
                border-radius: 999px;
                background: var(--pin-accent, #4ade80);
            }

            .map-stack {
                display: grid;
                align-self: stretch;
                height: 100%;
            }

            @media (min-width: 981px) {
                .campus-map-card--sidebar {
                    height: auto;
                    min-height: 820px;
                }

                .campus-map-surface--sidebar,
                .campus-map-live {
                    height: 620px;
                    min-height: 620px;
                }
            }

            @keyframes campusLegendCarousel {
                from {
                    transform: translateX(0);
                }

                to {
                    transform: translateX(-50%);
                }
            }

            @media (max-width: 980px) {
                .hero {
                    grid-template-columns: 1fr;
                }

                .grid {
                    grid-template-columns: 1fr;
                }

                .masthead,
                .studio,
                .hero-grid,
                .campus-map-layout {
                    grid-template-columns: 1fr;
                }

                .status-pill {
                    justify-self: start;
                }

                .campus-map-copy {
                    align-items: start;
                    flex-direction: column;
                }
            }

            @media (max-width: 720px) {
                .page {
                    padding: 18px;
                }

                .shell {
                    min-height: calc(100vh - 36px);
                }

                .topbar {
                    padding: 16px 18px;
                    border-radius: 24px;
                    align-items: flex-start;
                    flex-direction: column;
                }

                .card,
                .panel {
                    padding: 22px;
                    border-radius: 28px;
                }

                .brand-band,
                .hero-panel {
                    padding: 22px;
                    border-radius: 28px;
                }

                .campus-map-card {
                    padding: 20px;
                    border-radius: 24px;
                }

                .campus-map-surface {
                    height: 300px;
                    min-height: 300px;
                    border-radius: 24px;
                }

                .campus-map-surface--sidebar,
                .campus-map-live {
                    height: 300px;
                    min-height: 300px;
                }
            }

            @media (max-width: 540px) {
                .brand {
                    gap: 12px;
                }

                .brand-mark {
                    width: 48px;
                    height: 48px;
                    border-radius: 16px;
                }

                .hero {
                    gap: 22px;
                    padding-top: 24px;
                }

                .actions {
                    flex-direction: column;
                }

                .button,
                .button-secondary {
                    width: 100%;
                }

                .brand-band {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .campus-map-surface {
                    height: 270px;
                    min-height: 270px;
                }

                .campus-map-surface--sidebar,
                .campus-map-live {
                    height: 270px;
                    min-height: 270px;
                }

                .campus-map-legend-card {
                    flex-basis: 184px;
                }
            }

            @media (prefers-reduced-motion: reduce) {
                .campus-map-carousel-track {
                    animation: none;
                }
            }
        </style>
    <x-minimal-ui />
</head>
    <body>
        <div class="student-workspace">
            <x-student-sidebar active="map" />

            <main class="student-content">
                <div class="page">
            <div class="shell">
                                <div class="studio">
                                        <aside class="map-stack" aria-label="Student campus guide">
                        <section class="campus-map-card campus-map-card--sidebar" aria-label="Freshman campus guide">
                            <div class="campus-map-copy campus-map-copy--stack">
                                <div class="campus-map-kicker">Campus Map</div>
                            </div>

                            <div class="campus-map-layout campus-map-layout--sidebar">
                                <div class="campus-map-surface campus-map-surface--sidebar">
                                    <div class="map-compass">N</div>
                                    <div id="freshman-campus-map" class="campus-map-live" aria-label="Interactive campus department map"></div>
                                </div>

                                <div class="campus-map-carousel" aria-label="Campus department legends">
                                    <div class="campus-map-carousel-track">
                                        <div class="campus-map-carousel-group">
                                            <article class="campus-map-legend-card" style="--pin-accent: #a16207;">
                                                <span class="campus-map-legend-dot" aria-hidden="true"></span>
                                                <div class="campus-map-legend-copy">
                                                    <strong>AGRI</strong>
                                                    <span>Campus guide pin</span>
                                                </div>
                                            </article>

                                            <article class="campus-map-legend-card" style="--pin-accent: #f59e0b;">
                                                <span class="campus-map-legend-dot" aria-hidden="true"></span>
                                                <div class="campus-map-legend-copy">
                                                    <strong>CBM</strong>
                                                    <span>Campus guide pin</span>
                                                </div>
                                            </article>

                                            <article class="campus-map-legend-card" style="--pin-accent: #22c55e;">
                                                <span class="campus-map-legend-dot" aria-hidden="true"></span>
                                                <div class="campus-map-legend-copy">
                                                    <strong>CED</strong>
                                                    <span>Campus guide pin</span>
                                                </div>
                                            </article>

                                            <article class="campus-map-legend-card" style="--pin-accent: #ef4444;">
                                                <span class="campus-map-legend-dot" aria-hidden="true"></span>
                                                <div class="campus-map-legend-copy">
                                                    <strong>CCJE</strong>
                                                    <span>Campus guide pin</span>
                                                </div>
                                            </article>

                                            <article class="campus-map-legend-card" style="--pin-accent: #0891b2;">
                                                <span class="campus-map-legend-dot" aria-hidden="true"></span>
                                                <div class="campus-map-legend-copy">
                                                    <strong>CCSICT</strong>
                                                    <span>Campus guide pin</span>
                                                </div>
                                            </article>

                                            <article class="campus-map-legend-card" style="--pin-accent: #7c3aed;">
                                                <span class="campus-map-legend-dot" aria-hidden="true"></span>
                                                <div class="campus-map-legend-copy">
                                                    <strong>SAS</strong>
                                                    <span>Campus guide pin</span>
                                                </div>
                                            </article>

                                            <article class="campus-map-legend-card" style="--pin-accent: #84cc16;">
                                                <span class="campus-map-legend-dot" aria-hidden="true"></span>
                                                <div class="campus-map-legend-copy">
                                                    <strong>PS</strong>
                                                    <span>Campus guide pin</span>
                                                </div>
                                            </article>
                                        </div>

                                        <div class="campus-map-carousel-group" aria-hidden="true">
                                            <article class="campus-map-legend-card" style="--pin-accent: #a16207;">
                                                <span class="campus-map-legend-dot" aria-hidden="true"></span>
                                                <div class="campus-map-legend-copy">
                                                    <strong>AGRI</strong>
                                                    <span>Campus guide pin</span>
                                                </div>
                                            </article>

                                            <article class="campus-map-legend-card" style="--pin-accent: #f59e0b;">
                                                <span class="campus-map-legend-dot" aria-hidden="true"></span>
                                                <div class="campus-map-legend-copy">
                                                    <strong>CBM</strong>
                                                    <span>Campus guide pin</span>
                                                </div>
                                            </article>

                                            <article class="campus-map-legend-card" style="--pin-accent: #22c55e;">
                                                <span class="campus-map-legend-dot" aria-hidden="true"></span>
                                                <div class="campus-map-legend-copy">
                                                    <strong>CED</strong>
                                                    <span>Campus guide pin</span>
                                                </div>
                                            </article>

                                            <article class="campus-map-legend-card" style="--pin-accent: #ef4444;">
                                                <span class="campus-map-legend-dot" aria-hidden="true"></span>
                                                <div class="campus-map-legend-copy">
                                                    <strong>CCJE</strong>
                                                    <span>Campus guide pin</span>
                                                </div>
                                            </article>

                                            <article class="campus-map-legend-card" style="--pin-accent: #0891b2;">
                                                <span class="campus-map-legend-dot" aria-hidden="true"></span>
                                                <div class="campus-map-legend-copy">
                                                    <strong>CCSICT</strong>
                                                    <span>Campus guide pin</span>
                                                </div>
                                            </article>

                                            <article class="campus-map-legend-card" style="--pin-accent: #7c3aed;">
                                                <span class="campus-map-legend-dot" aria-hidden="true"></span>
                                                <div class="campus-map-legend-copy">
                                                    <strong>SAS</strong>
                                                    <span>Campus guide pin</span>
                                                </div>
                                            </article>

                                            <article class="campus-map-legend-card" style="--pin-accent: #84cc16;">
                                                <span class="campus-map-legend-dot" aria-hidden="true"></span>
                                                <div class="campus-map-legend-copy">
                                                    <strong>PS</strong>
                                                    <span>Campus guide pin</span>
                                                </div>
                                            </article>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </aside>
                </div>
            </div>
                </div>
            </main>
        </div>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const mapElement = document.getElementById('freshman-campus-map');

                if (!mapElement || typeof window.L === 'undefined') {
                    return;
                }

                // Freshman guide map with department markers.
                const departments = [
                    { code: 'AGRI', lat: 16.9401111, lng: 121.7648333, color: '#a16207' },
                    { code: 'CBM', lat: 16.9360000, lng: 121.7644167, color: '#f59e0b' },
                    { code: 'CED', lat: 16.9375833, lng: 121.7648333, color: '#22c55e' },
                    { code: 'CCJE', lat: 16.9390556, lng: 121.7651389, color: '#ef4444' },
                    { code: 'CCSICT', lat: 16.9381944, lng: 121.7641389, color: '#0891b2' },
                    { code: 'SAS', lat: 16.9373056, lng: 121.7638056, color: '#7c3aed' },
                    { code: 'PS', lat: 16.9386111, lng: 121.7642778, color: '#84cc16' },
                ];

                const map = L.map(mapElement, {
                    scrollWheelZoom: false,
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors',
                    maxZoom: 20,
                }).addTo(map);

                const bounds = [];

                departments.forEach((department) => {
                    const marker = L.circleMarker([department.lat, department.lng], {
                        radius: 8,
                        color: '#ffffff',
                        weight: 2,
                        fillColor: department.color,
                        fillOpacity: 1,
                    }).addTo(map);

                    marker.bindTooltip(department.code, {
                        permanent: true,
                        direction: 'top',
                        offset: [0, -8],
                        className: 'campus-map-tooltip',
                    });

                    marker.bindPopup(`<strong>${department.code}</strong><br>Department location pin`);

                    bounds.push([department.lat, department.lng]);
                });

                if (bounds.length) {
                    map.fitBounds(bounds, { padding: [36, 36] });
                }

                requestAnimationFrame(() => {
                    map.invalidateSize();
                });
            });
        </script>
    </body>
</html>
