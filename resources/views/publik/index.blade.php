<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Desa Kutabuluh | Monitoring & Evaluasi Pembangunan</title>

    <!-- Favicon / Logo di title bar -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-kab-karo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo-kab-karo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo-kab-karo.png') }}" type="image/x-icon">

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link
        href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css"
        rel="stylesheet">

    <style>

        /* ========== VARIABLES ========== */

        :root {
            --primary: #1e3c72;
            --primary-dark: #0f2748;
            --secondary: #2a5298;
            --accent: #667eea;

            --gradient:
                linear-gradient(
                    135deg,
                    #667eea 0%,
                    #764ba2 100%
                );

            --gradient-primary:
                linear-gradient(
                    135deg,
                    #1e3c72 0%,
                    #2a5298 100%
                );

            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1f2937;
            --light: #f3f4f6;
        }


        /* ========== RESET & BASE ========== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        html {
            scroll-behavior: smooth;
            scroll-padding-top: 85px;
        }


        body {
            font-family: 'Inter', sans-serif;
            background: var(--light);
            overflow-x: hidden;
            overflow-y: auto;
        }


        ::selection {
            background: rgba(42, 82, 152, 0.18);
            color: var(--primary-dark);
        }


        @media (prefers-reduced-motion: reduce) {

            html {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }


        /* ========== LOADING SCREEN ULTIMATE ========== */

        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            background: var(--gradient-primary);

            display: flex;
            justify-content: center;
            align-items: center;

            z-index: 9999;

            transition:
                opacity 0.6s ease,
                visibility 0.6s ease;
        }


        .loading-screen.hide {
            opacity: 0;
            visibility: hidden;
        }


        .loader-ultimate {
            text-align: center;
            animation: fadeInScale 0.6s ease;
        }


        @keyframes fadeInScale {

            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }


        .logo-wrapper {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            position: relative;
        }


        .logo-circle {
            width: 120px;
            height: 120px;

            background: rgba(255, 255, 255, 0.12);

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            position: relative;

            backdrop-filter: blur(4px);
        }


        .logo-icon {
            width: 70px;
            height: 70px;

            background: rgba(255, 255, 255, 0.2);

            border-radius: 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            animation: iconPulse 1.5s ease infinite;
        }


        .logo-icon i {
            font-size: 40px;
            color: white;

            animation: iconRotate 2s ease infinite;
        }


        @keyframes iconPulse {

            0%,
            100% {
                transform: scale(1);
                background: rgba(255, 255, 255, 0.2);
            }

            50% {
                transform: scale(1.05);
                background: rgba(255, 255, 255, 0.3);
            }
        }


        @keyframes iconRotate {

            0% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(5deg);
            }

            75% {
                transform: rotate(-5deg);
            }

            100% {
                transform: rotate(0deg);
            }
        }


        .logo-ring {
            position: absolute;

            top: -8px;
            left: -8px;
            right: -8px;
            bottom: -8px;

            border-radius: 50%;

            border: 2px solid rgba(255, 255, 255, 0.3);

            border-top-color: white;

            animation: spinRing 1.5s linear infinite;
        }


        @keyframes spinRing {

            to {
                transform: rotate(360deg);
            }
        }


        .loader-ultimate h3 {
            color: white;

            font-size: 1.8rem;
            font-weight: 700;

            margin-bottom: 8px;

            letter-spacing: 2px;

            background:
                linear-gradient(
                    135deg,
                    #fff,
                    #ffc107
                );

            -webkit-background-clip: text;
            background-clip: text;

            color: transparent;
        }


        .loader-ultimate .subtitle {
            color: rgba(255, 255, 255, 0.7);

            font-size: 0.85rem;

            margin-bottom: 30px;
        }


        .progress-container {
            width: 280px;

            margin:
                0 auto 20px;
        }


        .progress-bar-custom {
            width: 100%;
            height: 4px;

            background: rgba(255, 255, 255, 0.2);

            border-radius: 10px;

            overflow: hidden;

            margin-bottom: 15px;
        }


        .progress-fill {
            width: 0%;
            height: 100%;

            background:
                linear-gradient(
                    90deg,
                    #fff,
                    #ffc107
                );

            border-radius: 10px;

            animation:
                loadingProgress 2s ease infinite;
        }


        @keyframes loadingProgress {

            0% {
                width: 0%;
            }

            30% {
                width: 30%;
            }

            60% {
                width: 70%;
            }

            100% {
                width: 100%;
            }
        }


        .progress-steps {
            display: flex;
            justify-content: space-between;

            padding: 0 10px;
        }


        .step {
            width: 30px;
            height: 30px;

            background: rgba(255, 255, 255, 0.15);

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 14px;

            transition: all 0.3s ease;
        }


        .step.active {
            background: rgba(255, 255, 255, 0.4);

            transform: scale(1.1);
        }


        .step.completed {
            background: #ffc107;
            color: #1e3c72;
        }


        .loading-text {
            margin-top: 20px;

            color: rgba(255, 255, 255, 0.8);

            font-size: 0.85rem;

            font-family: monospace;
        }


        .loading-dots {
            display: inline-block;

            width: 60px;

            text-align: left;
        }


        .loading-dots::after {
            content: '...';

            animation:
                dotsAnim 1.5s steps(4, end) infinite;
        }


        @keyframes dotsAnim {

            0%,
            20% {
                content: '.';
            }

            40% {
                content: '..';
            }

            60%,
            100% {
                content: '...';
            }
        }


        .loading-tips {
            margin-top: 30px;

            padding: 10px 20px;

            background: rgba(255, 255, 255, 0.1);

            border-radius: 30px;

            display: inline-block;

            font-size: 0.8rem;

            color: rgba(255, 255, 255, 0.8);

            animation:
                fadeInUpTip 0.6s ease 0.5s both;
        }


        @keyframes fadeInUpTip {

            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }


        .loading-tips i {
            margin-right: 8px;
            color: #ffc107;
        }


        /* ========== NAVBAR PRO / SMOOTH ========== */

        .navbar-custom {
            background: rgba(255, 255, 255, 0.78);

            -webkit-backdrop-filter:
                blur(18px) saturate(180%);

            backdrop-filter:
                blur(18px) saturate(180%);

            padding: 14px 0;

            position: sticky;

            top: 0;

            z-index: 1000;

            border-bottom:
                1px solid rgba(255, 255, 255, 0.65);

            box-shadow:
                0 8px 30px rgba(15, 39, 72, 0.06);

            transition:
                padding 0.45s cubic-bezier(0.22, 1, 0.36, 1),
                background 0.45s ease,
                box-shadow 0.45s ease,
                border-color 0.45s ease;
        }


        .navbar-custom.scrolled {
            padding: 9px 0;

            background:
                rgba(255, 255, 255, 0.94);

            border-bottom-color:
                rgba(30, 60, 114, 0.08);

            box-shadow:
                0 10px 35px rgba(15, 39, 72, 0.12);
        }


        .navbar-custom > .container {
            min-height: 48px;
        }


        .navbar-custom .d-flex {
            gap: 20px;
        }


        .navbar-brand {
            display: inline-flex !important;

            align-items: center;

            gap: 10px;

            font-weight: 800;

            font-size: 1.25rem;

            color: var(--primary) !important;

            text-decoration: none;

            margin: 0;

            line-height: 1;

            transition:
                transform 0.3s ease,
                opacity 0.3s ease;
        }


        .navbar-brand:hover {
            transform: translateY(-1px);
            opacity: 0.94;
        }


        .navbar-brand .brand-logo {
            width: 43px;
            height: 43px;

            object-fit: contain;

            display: block;

            flex: 0 0 43px;

            background: transparent !important;

            border: 0 !important;

            box-shadow: none !important;

            transition:
                width 0.45s ease,
                height 0.45s ease,
                transform 0.35s ease;
        }


        .navbar-custom.scrolled
        .navbar-brand
        .brand-logo {
            width: 37px;
            height: 37px;
            flex-basis: 37px;
        }


        .navbar-brand:hover
        .brand-logo {
            transform:
                rotate(-2deg)
                scale(1.04);
        }


        .navbar-brand span {
            display: flex;

            flex-direction: column;

            justify-content: center;

            line-height: 1.05;
        }


        .navbar-brand strong {
            font-size: 1.08rem;

            font-weight: 800;

            letter-spacing: 0.35px;

            color: var(--primary);
        }


        .navbar-brand small {
            margin-top: 4px;

            font-size: 0.64rem;

            font-weight: 500;

            letter-spacing: 0.15px;

            color: #64748b;
        }


        .nav-links {
            display: flex;

            align-items: center;

            justify-content: flex-end;

            gap: 9px;
        }


        .nav-links a.btn {
            position: relative;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            min-height: 38px;

            padding: 8px 15px;

            border-radius: 999px;

            font-size: 0.78rem;

            font-weight: 600;

            letter-spacing: 0.05px;

            border-width: 1px;

            transition:
                transform 0.3s cubic-bezier(0.22, 1, 0.36, 1),
                box-shadow 0.3s ease,
                background 0.3s ease,
                color 0.3s ease,
                border-color 0.3s ease;
        }


        .nav-links a.btn:hover {
            transform: translateY(-2px);
        }


        .nav-links a.btn-primary {
            border: 0;

            background:
                var(--gradient-primary);

            box-shadow:
                0 7px 18px
                rgba(30, 60, 114, 0.18);
        }


        .nav-links a.btn-primary:hover {
            box-shadow:
                0 10px 24px
                rgba(30, 60, 114, 0.27);
        }


        .nav-links a.btn-outline-primary {
            background:
                rgba(255, 255, 255, 0.55);

            border-color:
                rgba(30, 60, 114, 0.22);
        }


        .nav-links a.btn-outline-primary:hover {
            background: var(--primary);

            border-color: var(--primary);

            color: #fff;

            box-shadow:
                0 8px 20px
                rgba(30, 60, 114, 0.18);
        }


        .nav-links a i {
            transition:
                transform 0.3s ease;
        }


        .nav-links a:hover i {
            transform:
                translateY(-1px);
        }


        .mobile-menu-btn {
            display: none;

            width: 42px;
            height: 42px;

            align-items: center;
            justify-content: center;

            background:
                rgba(30, 60, 114, 0.06);

            border:
                1px solid rgba(30, 60, 114, 0.08);

            font-size: 18px;

            color: var(--primary);

            cursor: pointer;

            padding: 0;

            z-index: 1001;

            border-radius: 50%;

            transition:
                transform 0.3s ease,
                background 0.3s ease,
                box-shadow 0.3s ease;
        }


        .mobile-menu-btn:hover {
            transform: scale(1.05);

            background:
                rgba(30, 60, 114, 0.11);

            box-shadow:
                0 6px 16px
                rgba(30, 60, 114, 0.12);
        }


        .mobile-menu-btn:active {
            transform: scale(0.94);
        }


        .menu-overlay {
            display: none;
        }


        @media (max-width: 768px) {

            .navbar-custom {
                padding: 10px 0;
            }


            .navbar-custom.scrolled {
                padding: 8px 0;
            }


            .navbar-custom > .container {
                min-height: 44px;
            }


            .navbar-custom .d-flex {
                gap: 10px;
            }


            .navbar-brand {
                gap: 8px;

                font-size: 1rem;

                min-width: 0;
            }


            .navbar-brand .brand-logo,
            .navbar-custom.scrolled
            .navbar-brand .brand-logo {
                width: 36px;
                height: 36px;

                flex-basis: 36px;
            }


            .navbar-brand strong {
                font-size: 0.98rem;
            }


            .navbar-brand small {
                font-size: 0.58rem;
                margin-top: 3px;
            }


            .mobile-menu-btn {
                display: inline-flex;
                flex: 0 0 auto;
            }


            .nav-links {
                position: fixed;

                top: 0;
                right: 0;

                width: min(320px, 88vw);

                height: 100dvh;
                min-height: 100vh;

                background:
                    rgba(255, 255, 255, 0.96);

                -webkit-backdrop-filter:
                    blur(22px);

                backdrop-filter:
                    blur(22px);

                flex-direction: column;

                align-items: stretch;

                justify-content: flex-start;

                padding: 88px 20px 30px;

                box-shadow:
                    -18px 0 45px
                    rgba(15, 39, 72, 0.15);

                transform:
                    translateX(105%);

                visibility: hidden;

                opacity: 0;

                transition:
                    transform 0.45s cubic-bezier(0.22, 1, 0.36, 1),
                    opacity 0.3s ease,
                    visibility 0.45s ease;

                z-index: 1000;

                gap: 12px;

                overflow-y: auto;
            }


            .nav-links.active {
                transform:
                    translateX(0);

                visibility: visible;

                opacity: 1;
            }


            .nav-links a.btn {
                width: 100%;

                min-height: 46px;

                justify-content: flex-start;

                padding: 11px 16px;

                border-radius: 13px;

                font-size: 0.84rem;
            }


            .nav-links a.btn i {
                width: 22px;

                text-align: center;

                margin-right: 7px !important;
            }


            .menu-overlay {
                display: block;

                position: fixed;

                inset: 0;

                background:
                    rgba(8, 20, 40, 0.38);

                -webkit-backdrop-filter:
                    blur(2px);

                backdrop-filter:
                    blur(2px);

                z-index: 999;

                opacity: 0;

                visibility: hidden;

                pointer-events: none;

                transition:
                    opacity 0.35s ease,
                    visibility 0.35s ease;
            }


            .menu-overlay.active {
                opacity: 1;

                visibility: visible;

                pointer-events: auto;
            }
        }


        @media (max-width: 430px) {

            .navbar-brand .brand-logo,
            .navbar-custom.scrolled
            .navbar-brand .brand-logo {
                width: 32px;
                height: 32px;

                flex-basis: 32px;
            }


            .navbar-brand strong {
                font-size: 0.9rem;
            }


            .navbar-brand small {
                font-size: 0.54rem;
            }


            .mobile-menu-btn {
                width: 39px;
                height: 39px;

                font-size: 16px;
            }


            .nav-links {
                width: min(300px, 91vw);
            }
        }


        /* ========== HERO SECTION ========== */

        .hero-section {
            background-image:
                url('{{ asset('images/hero-bg.png') }}');

            background-size: cover;

            background-position: center;

            background-repeat: no-repeat;

            color: white;

            padding: 80px 0;

            position: relative;

            overflow: hidden;

            min-height: 500px;

            display: flex;

            align-items: center;
        }


        .hero-section::before {
            content: '';

            position: absolute;

            top: 0;
            left: 0;
            right: 0;
            bottom: 0;

            background:
                linear-gradient(
                    135deg,
                    rgba(30, 60, 114, 0.4) 0%,
                    rgba(42, 82, 152, 0.3) 100%
                );

            z-index: 1;
        }


        .hero-section .container {
            position: relative;
            z-index: 2;
        }


        .hero-section h1 {
            font-size: 3rem;

            font-weight: 800;

            margin-bottom: 20px;

            animation:
                fadeInUp 1s ease;
        }


        .hero-section .lead {
            font-size: 1.2rem;

            opacity: 0.9;

            animation:
                fadeInUp 1s ease 0.2s both;
        }


        .stat-card {
            background:
                rgba(255, 255, 255, 0.15);

            backdrop-filter:
                blur(10px);

            border-radius: 20px;

            padding: 20px;

            margin-top: 40px;

            animation:
                fadeInUp 1s ease 0.4s both;
        }


        .stat-card h3 {
            font-size: 2.5rem;

            font-weight: 800;

            margin: 0;
        }


        /* ========== SEARCH SECTION ========== */

        .search-section {
            margin-top: -40px;

            position: relative;

            z-index: 10;
        }


        .search-card {
            background: white;

            border-radius: 20px;

            padding: 20px;

            box-shadow:
                0 20px 40px
                rgba(0, 0, 0, 0.1);
        }


        .search-input {
            border:
                2px solid #e5e7eb;

            border-radius: 12px;

            padding: 12px 20px;

            transition: all 0.3s;
        }


        .search-input:focus {
            border-color:
                var(--secondary);

            box-shadow: none;
        }


        /* ========== LAYOUT PEMBANGUNAN: GRID / BARIS ========== */
        .layout-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 22px;
            flex-wrap: wrap;
        }

        .layout-toolbar-title {
            font-weight: 700;
            color: var(--dark);
            font-size: .95rem;
        }

        .layout-switcher {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 8px 22px rgba(15, 39, 72, .07);
        }

        .layout-btn {
            border: 0;
            background: transparent;
            color: #64748b;
            min-width: 42px;
            height: 36px;
            padding: 0 10px;
            border-radius: 10px;
            font-size: .78rem;
            font-weight: 700;
            transition: all .25s ease;
        }

        .layout-btn:hover {
            color: var(--primary);
            background: rgba(30, 60, 114, .07);
        }

        .layout-btn.active {
            color: #fff;
            background: var(--gradient-primary);
            box-shadow: 0 5px 14px rgba(30, 60, 114, .20);
        }

        #pembangunanContainer.layout-grid {
            --grid-columns: 3;
        }

        #pembangunanContainer.layout-grid .pembangunan-item {
            width: calc(100% / var(--grid-columns));
            flex: 0 0 calc(100% / var(--grid-columns));
            max-width: calc(100% / var(--grid-columns));
        }

        #pembangunanContainer.layout-grid.grid-4 {
            --grid-columns: 4;
        }

        #pembangunanContainer.layout-grid.grid-5 {
            --grid-columns: 5;
        }

        #pembangunanContainer.layout-list .pembangunan-item {
            width: 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }

        #pembangunanContainer.layout-list .card-pembangunan {
            display: flex;
            flex-direction: row;
            min-height: 220px;
        }

        #pembangunanContainer.layout-list .card-img-wrapper {
            width: 320px;
            min-width: 320px;
            height: 220px;
        }

        #pembangunanContainer.layout-list .card-body {
            flex: 1;
        }

        @media (max-width: 1199px) {
            #pembangunanContainer.layout-grid.grid-5 .pembangunan-item {
                width: 25%;
                flex-basis: 25%;
                max-width: 25%;
            }
        }

        @media (max-width: 991px) {
            #pembangunanContainer.layout-grid,
            #pembangunanContainer.layout-grid.grid-4,
            #pembangunanContainer.layout-grid.grid-5 {
                --grid-columns: 2;
            }

            #pembangunanContainer.layout-grid .pembangunan-item,
            #pembangunanContainer.layout-grid.grid-4 .pembangunan-item,
            #pembangunanContainer.layout-grid.grid-5 .pembangunan-item {
                width: 50%;
                flex-basis: 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 768px) {
            .layout-toolbar {
                align-items: stretch;
                flex-direction: column;
            }

            .layout-switcher {
                width: 100%;
            }

            .layout-btn {
                flex: 1;
            }

            #pembangunanContainer.layout-grid .pembangunan-item,
            #pembangunanContainer.layout-grid.grid-4 .pembangunan-item,
            #pembangunanContainer.layout-grid.grid-5 .pembangunan-item {
                width: 100%;
                flex-basis: 100%;
                max-width: 100%;
            }

            #pembangunanContainer.layout-list .card-pembangunan {
                display: block;
            }

            #pembangunanContainer.layout-list .card-img-wrapper {
                width: 100%;
                min-width: 100%;
                height: 200px;
            }
        }

        /* ========== CARD PEMBANGUNAN ========== */

        .card-pembangunan {
            border: none;

            border-radius: 20px;

            transition:
                all 0.4s
                cubic-bezier(0.4, 0, 0.2, 1);

            overflow: hidden;

            background: white;

            cursor: pointer;

            height: 100%;
        }


        .card-pembangunan:hover {
            transform:
                translateY(-10px);

            box-shadow:
                0 30px 50px
                rgba(0, 0, 0, 0.15);
        }


        .card-img-wrapper {
            position: relative;

            overflow: hidden;

            height: 220px;
        }


        .card-img-wrapper img {
            width: 100%;

            height: 100%;

            object-fit: cover;

            transition:
                transform 0.6s ease;
        }


        .card-pembangunan:hover
        .card-img-wrapper img {
            transform:
                scale(1.1);
        }


        .card-badge {
            position: absolute;

            top: 15px;
            right: 15px;

            padding: 5px 12px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: 600;

            z-index: 1;
        }


        .card-badge.plan {
            background: #6b7280;
            color: white;
        }


        .card-badge.progress {
            background: #f59e0b;
            color: white;
        }


        .card-badge.done {
            background: #10b981;
            color: white;
        }


        .card-overlay {
            position: absolute;

            bottom: 0;

            left: 0;
            right: 0;

            background:
                linear-gradient(
                    to top,
                    rgba(0, 0, 0, 0.7),
                    transparent
                );

            padding: 20px;

            color: white;
        }


        .progress-custom {
            height: 8px;

            border-radius: 10px;

            background: #e5e7eb;
        }


        .progress-custom .progress-bar {
            border-radius: 10px;

            transition:
                width 0.6s ease;
        }


        /* ========== PAGINATION ========== */

        .pagination-custom {
            display: flex;

            justify-content: center;

            gap: 10px;

            margin-top: 40px;

            flex-wrap: wrap;
        }


        .pagination-custom .page-link {
            border-radius: 12px;

            padding: 10px 16px;

            color: var(--primary);

            border: none;

            font-weight: 500;

            transition: all 0.3s;
        }


        .pagination-custom .page-link:hover {
            background:
                var(--primary);

            color: white;

            transform:
                translateY(-2px);
        }


        .pagination-custom .active .page-link {
            background:
                var(--gradient-primary);

            color: white;
        }


        /* ========== FOOTER ========== */

        .footer {
            background:
                var(--dark);

            color: #9ca3af;

            padding: 60px 0 30px;

            margin-top: 60px;
        }


        .footer h5 {
            color: white;

            margin-bottom: 20px;
        }


        /* ========== FLOATING BUTTONS ========== */

        .float-btn {
            position: fixed;

            bottom: 30px;
            right: 30px;

            width: 55px;
            height: 55px;

            background:
                var(--gradient-primary);

            border-radius: 50%;

            display: flex;

            align-items: center;
            justify-content: center;

            color: white;

            font-size: 24px;

            box-shadow:
                0 4px 20px
                rgba(0, 0, 0, 0.2);

            transition: all 0.3s;

            z-index: 100;

            text-decoration: none;
        }


        .float-btn:hover {
            transform:
                scale(1.1);

            color: white;
        }


        .back-to-top {
            position: fixed;

            bottom: 100px;
            right: 30px;

            width: 45px;
            height: 45px;

            background: white;

            border-radius: 50%;

            display: flex;

            align-items: center;
            justify-content: center;

            color: var(--primary);

            box-shadow:
                0 4px 15px
                rgba(0, 0, 0, 0.1);

            cursor: pointer;

            opacity: 0;

            visibility: hidden;

            transition: all 0.3s;

            z-index: 100;
        }


        .back-to-top.show {
            opacity: 1;

            visibility: visible;
        }


        .back-to-top:hover {
            background:
                var(--primary);

            color: white;

            transform:
                translateY(-5px);
        }


        /* ========== EMPTY STATE ========== */

        .empty-state {
            text-align: center;

            padding: 60px 20px;

            background: white;

            border-radius: 20px;
        }


        .empty-state i {
            font-size: 80px;

            color: #cbd5e1;

            margin-bottom: 20px;
        }


        .empty-state h4 {
            font-size: 1.5rem;

            margin-bottom: 10px;

            color: var(--dark);
        }


        /* ========== ANIMATIONS ========== */

        @keyframes fadeInUp {

            from {
                opacity: 0;

                transform:
                    translateY(30px);
            }

            to {
                opacity: 1;

                transform:
                    translateY(0);
            }
        }


        .fade-up {
            animation:
                fadeInUp 0.8s ease forwards;
        }


        @media (max-width: 768px) {

            .hero-section {
                padding: 50px 0;

                min-height: 400px;
            }


            .hero-section h1 {
                font-size: 1.8rem;
            }


            .hero-section .lead {
                font-size: 1rem;
            }


            .stat-card {
                margin-top: 20px;

                padding: 15px;
            }


            .stat-card h3 {
                font-size: 1.5rem;
            }


            .search-section {
                margin-top: -30px;
            }


            .search-card {
                padding: 15px;
            }


            .card-img-wrapper {
                height: 180px;
            }


            .card-overlay {
                padding: 12px;
            }


            .card-overlay h6 {
                font-size: 0.9rem;
            }


            .pagination-custom .page-link {
                padding: 6px 12px;

                font-size: 0.85rem;
            }


            .footer {
                padding: 40px 0 20px;

                margin-top: 40px;
            }


            .footer .col-md-4 {
                text-align: center;
            }


            .float-btn {
                width: 45px;
                height: 45px;

                font-size: 20px;

                bottom: 20px;
                right: 20px;
            }


            .back-to-top {
                bottom: 85px;
                right: 20px;

                width: 40px;
                height: 40px;
            }


            .container {
                padding-left: 15px;
                padding-right: 15px;
            }


            .empty-state {
                padding: 40px 20px;
            }


            .empty-state i {
                font-size: 60px;
            }


            .empty-state h4 {
                font-size: 1.2rem;
            }
        }


        @media (min-width: 769px)
        and (max-width: 1024px) {

            .hero-section h1 {
                font-size: 2.5rem;
            }


            .card-img-wrapper {
                height: 200px;
            }
        }


        body.menu-open {
            overflow: hidden;
        }


        @media (min-width: 769px) {

            body.menu-open {
                overflow-y: auto;
            }
        }


        /* =========================================================
           IDENTITAS LOGO SISMOLEV
        ========================================================= */

        .navbar-brand {
            display: inline-flex !important;

            align-items: center;

            gap: 10px;

            text-decoration: none;
        }


        .navbar-brand .brand-logo {
            width: 42px;
            height: 42px;

            object-fit: contain;

            display: block;

            background: transparent !important;

            border: 0 !important;

            box-shadow: none !important;
        }


        .navbar-brand span {
            display: flex;

            flex-direction: column;

            line-height: 1.1;
        }


        .navbar-brand strong {
            font-size: 1.15rem;

            font-weight: 800;

            color: var(--primary);

            letter-spacing: .4px;
        }


        .navbar-brand small {
            margin-top: 3px;

            font-size: .68rem;

            font-weight: 500;

            color: #64748b;
        }


        .loading-screen .logo-icon {
            background: transparent !important;

            border-radius: 0 !important;

            box-shadow: none !important;
        }


        .loading-screen .logo-icon img {
            width: 58px;
            height: 58px;

            object-fit: contain;

            display: block;

            background: transparent !important;
        }


        @media (max-width: 768px) {

            .navbar-brand .brand-logo {
                width: 36px;
                height: 36px;
            }


            .navbar-brand strong {
                font-size: 1rem;
            }


            .navbar-brand small {
                font-size: .6rem;
            }
        }

    </style>

</head>


<body>


    <!-- ========== LOADING SCREEN ULTIMATE ========== -->

    <div
        class="loading-screen"
        id="loadingScreen"
    >

        <div class="loader-ultimate">

            <div class="logo-wrapper">

                <div class="logo-circle">

                    <div class="logo-icon">

                        <img
                            src="{{ asset('images/logo-kab-karo.png') }}"
                            alt="Logo Kabupaten Karo"
                        >

                    </div>

                    <div class="logo-ring"></div>

                </div>

            </div>


            <h3>
                Desa Kutabuluh
            </h3>


            <p class="subtitle">
                Sistem Informasi Monitoring & Evaluasi Pembangunan
            </p>


            <div class="progress-container">

                <div class="progress-bar-custom">

                    <div class="progress-fill"></div>

                </div>


                <div class="progress-steps">

                    <div
                        class="step active"
                        id="step1"
                    >
                        ⚡
                    </div>

                    <div
                        class="step"
                        id="step2"
                    >
                        📊
                    </div>

                    <div
                        class="step"
                        id="step3"
                    >
                        ✅
                    </div>

                </div>

            </div>


            <div class="loading-text">

                <span id="loadingMessage">
                    Memuat data pembangunan
                </span>

                <span class="loading-dots"></span>

            </div>


            <div class="loading-tips">

                <i class="fas fa-lightbulb"></i>

                <span id="tipText">
                    Transparansi pembangunan untuk masyarakat
                </span>

            </div>

        </div>

    </div>


    <!-- ========== NAVBAR ========== -->

    <nav
        class="navbar-custom"
        id="navbar"
    >

        <div class="container">

            <div
                class="d-flex justify-content-between align-items-center w-100"
            >

                <a
                    class="navbar-brand d-flex align-items-center gap-2"
                    href="{{ route('home') }}"
                >

                    <img
                        src="{{ asset('images/logo-kab-karo.png') }}"
                        alt="Logo Kabupaten Karo"
                        class="brand-logo"
                    >

                    <span>

                        <strong>
                            SISMOLEV
                        </strong>

                        <small>
                            Desa Kutabuluh
                        </small>

                    </span>

                </a>


                <button
                    class="mobile-menu-btn"
                    id="mobileMenuBtn"
                    aria-label="Menu"
                >

                    <i class="fas fa-bars"></i>

                </button>


                <div
                    class="nav-links"
                    id="navLinks"
                >

                    <a
                        href="#informasi"
                        class="btn btn-primary btn-sm"
                        id="infoBtn"
                    >

                        <i class="fas fa-info-circle me-1"></i>

                        Informasi Pembangunan

                    </a>


                    <a
                        href="{{ route('login') }}"
                        class="btn btn-outline-primary btn-sm"
                    >

                        <i class="fas fa-lock me-1"></i>

                        Login

                    </a>

                </div>

            </div>

        </div>

    </nav>


    <div
        class="menu-overlay"
        id="menuOverlay"
    ></div>


    <!-- ========== HERO SECTION ========== -->

    <section class="hero-section">

        <div class="container">

            <div class="row justify-content-center text-center">

                <div class="col-lg-8">

                    <h1>

                        <i class="fas fa-chart-line me-3"></i>

                        Monitoring & Evaluasi Pembangunan

                    </h1>


                    <p class="lead">

                        Desa Kutabuluh -
                        Transparansi Pembangunan untuk Masyarakat

                    </p>


                    <div class="stat-card">

                        <div class="row">

                            <div class="col-6 col-md-4">

                                <i class="fas fa-building fa-2x mb-2"></i>

                                <h3
                                    class="counter"
                                    data-target="{{ $pembangunans->total() }}"
                                >
                                    0
                                </h3>

                                <small>
                                    Total Pembangunan
                                </small>

                            </div>


                            <div class="col-6 col-md-4">

                                <i class="fas fa-chart-line fa-2x mb-2"></i>

                                <h3
                                    class="counter"
                                    data-target="{{ $pembangunans->where('status', 'berjalan')->count() }}"
                                >
                                    0
                                </h3>

                                <small>
                                    Sedang Berjalan
                                </small>

                            </div>


                            <div
                                class="col-6 col-md-4 offset-3 offset-md-0 mt-3 mt-md-0"
                            >

                                <i class="fas fa-check-circle fa-2x mb-2"></i>

                                <h3
                                    class="counter"
                                    data-target="{{ $pembangunans->where('status', 'selesai')->count() }}"
                                >
                                    0
                                </h3>

                                <small>
                                    Telah Selesai
                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- ========== SEARCH SECTION ========== -->

    <div class="search-section">

        <div class="container">

            <div class="search-card">

                <div class="row g-2">

                    <div class="col-12 col-md-8">

                        <div class="input-group">

                            <span
                                class="input-group-text bg-white border-end-0"
                            >

                                <i class="fas fa-search text-muted"></i>

                            </span>


                            <input
                                type="text"
                                class="form-control search-input border-start-0"
                                id="searchInput"
                                placeholder="Cari pembangunan..."
                                autocomplete="off"
                            >

                        </div>

                    </div>


                    <div class="col-12 col-md-4">

                        <select
                            class="form-select search-input"
                            id="statusFilter"
                        >

                            <option value="">
                                Semua Status
                            </option>

                            <option value="direncanakan">
                                Direncanakan
                            </option>

                            <option value="berjalan">
                                Sedang Berjalan
                            </option>

                            <option value="selesai">
                                Selesai
                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ========== CONTENT SECTION ========== -->

    <div
        class="container py-5"
        id="informasi"
    >

        <!-- ========== PENGATURAN TAMPILAN KARTU ========== -->
        <div class="layout-toolbar">
            <div class="layout-toolbar-title">
                <i class="fas fa-th-large me-2 text-primary"></i>
                Tampilan Pembangunan
            </div>

            <div class="layout-switcher" role="group" aria-label="Pengaturan tampilan">
                <button type="button" class="layout-btn" data-layout="list" title="Tampilan baris">
                    <i class="fas fa-bars me-1"></i> Baris
                </button>
                <button type="button" class="layout-btn" data-layout="grid-3" title="3 kolom">
                    <i class="fas fa-th me-1"></i> 3
                </button>
                <button type="button" class="layout-btn active" data-layout="grid-4" title="4 kolom">
                    <i class="fas fa-th-large me-1"></i> 4
                </button>
                <button type="button" class="layout-btn" data-layout="grid-5" title="5 kolom">
                    <i class="fas fa-grip-horizontal me-1"></i> 5
                </button>
            </div>
        </div>

        <div
            class="row layout-grid grid-4"
            id="pembangunanContainer"
        >

            @forelse($pembangunans as $p)

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | PROGRESS
                    |--------------------------------------------------------------------------
                    */

                    $progress =
                        $p->monitorings->first()?->progres ?? 0;


                    /*
                    |--------------------------------------------------------------------------
                    | FOTO COVER
                    |--------------------------------------------------------------------------
                    |
                    | Prioritas:
                    |
                    | 1. foto dari dokumentasi
                    | 2. gambar dari keterangan dokumentasi
                    | 3. null
                    |
                    |--------------------------------------------------------------------------
                    */

                    $coverFoto = null;


                    /*
                    |--------------------------------------------------------------------------
                    | 1. CARI FOTO DOKUMENTASI
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $p->dokumentasis &&
                        $p->dokumentasis->count() > 0
                    ) {

                        foreach (
                            $p->dokumentasis as $dok
                        ) {

                            if (
                                !empty($dok->foto)
                            ) {

                                $fotoPath =
                                    trim(
                                        $dok->foto
                                    );


                                /*
                                |--------------------------------------------------------------------------
                                | Kalau sudah URL lengkap
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    str_starts_with(
                                        $fotoPath,
                                        'http://'
                                    ) ||
                                    str_starts_with(
                                        $fotoPath,
                                        'https://'
                                    )
                                ) {

                                    $coverFoto =
                                        $fotoPath;

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Kalau sudah /storage/...
                                |--------------------------------------------------------------------------
                                */

                                elseif (
                                    str_starts_with(
                                        $fotoPath,
                                        '/storage/'
                                    )
                                ) {

                                    $coverFoto =
                                        url($fotoPath);

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Kalau storage/...
                                |--------------------------------------------------------------------------
                                */

                                elseif (
                                    str_starts_with(
                                        $fotoPath,
                                        'storage/'
                                    )
                                ) {

                                    $coverFoto =
                                        asset($fotoPath);

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Path normal dari Storage::disk('public')
                                |--------------------------------------------------------------------------
                                */

                                else {

                                    $coverFoto =
                                        asset(
                                            'storage/' .
                                            ltrim(
                                                $fotoPath,
                                                '/'
                                            )
                                        );
                                }


                                if (
                                    $coverFoto
                                ) {

                                    break;
                                }
                            }
                        }
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | 2. KALAU FOTO DOKUMENTASI TIDAK ADA,
                    |    CARI GAMBAR DI KETERANGAN FOTO
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !$coverFoto &&
                        $p->dokumentasis &&
                        $p->dokumentasis->count() > 0
                    ) {

                        foreach (
                            $p->dokumentasis as $dok
                        ) {

                            if (
                                empty(
                                    $dok->keterangan
                                )
                            ) {

                                continue;
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Ambil semua <img src="...">
                            |--------------------------------------------------------------------------
                            */

                            preg_match_all(
                                '/<img\b[^>]*\bsrc\s*=\s*["\']([^"\']+)["\'][^>]*>/i',
                                $dok->keterangan,
                                $matches
                            );


                            if (
                                empty(
                                    $matches[1]
                                )
                            ) {

                                continue;
                            }


                            foreach (
                                $matches[1] as $gambar
                            ) {

                                $gambar =
                                    trim($gambar);


                                /*
                                |--------------------------------------------------------------------------
                                | Jangan gunakan base64
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    str_starts_with(
                                        $gambar,
                                        'data:image/'
                                    )
                                ) {

                                    continue;
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | URL HTTP / HTTPS
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    str_starts_with(
                                        $gambar,
                                        'http://'
                                    ) ||
                                    str_starts_with(
                                        $gambar,
                                        'https://'
                                    )
                                ) {

                                    $coverFoto =
                                        $gambar;

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | /storage/...
                                |--------------------------------------------------------------------------
                                */

                                elseif (
                                    str_starts_with(
                                        $gambar,
                                        '/storage/'
                                    )
                                ) {

                                    $coverFoto =
                                        url($gambar);

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | storage/...
                                |--------------------------------------------------------------------------
                                */

                                elseif (
                                    str_starts_with(
                                        $gambar,
                                        'storage/'
                                    )
                                ) {

                                    $coverFoto =
                                        asset($gambar);

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | Path relatif
                                |--------------------------------------------------------------------------
                                */

                                else {

                                    $coverFoto =
                                        asset(
                                            'storage/' .
                                            ltrim(
                                                $gambar,
                                                '/'
                                            )
                                        );
                                }


                                if (
                                    $coverFoto
                                ) {

                                    break;
                                }
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Sudah dapat gambar
                            |--------------------------------------------------------------------------
                            */

                            if (
                                $coverFoto
                            ) {

                                break;
                            }
                        }
                    }



                    /*
                     |--------------------------------------------------------------------------
                     | 3. FALLBACK FOTO LOKAL
                     |--------------------------------------------------------------------------
                     | Jika tidak ada foto dokumentasi, gunakan hero-bg.png
                     | yang sudah tersedia di public/images.
                     |--------------------------------------------------------------------------
                     */

                    if (!$coverFoto) {
                        $coverFoto = asset('images/hero-bg.png');
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | CEK HARGA DEAL
                    |--------------------------------------------------------------------------
                    */

                    $hargaDealCard = null;


                    if (
                        $p->rekanan_terpilih
                    ) {

                        $rekananProyek =
                            $p->rekananProyek
                                ->where(
                                    'id_rekanan',
                                    $p->rekanan_terpilih
                                )
                                ->first();


                        if (
                            $rekananProyek
                        ) {

                            $hargaDealCard =
                                $rekananProyek
                                    ->harga_penawaran_rekanan;
                        }
                    }

                @endphp


                <div
                    class="pembangunan-item mb-4"
                    data-name="{{ strtolower($p->nama_pembangunan) }}"
                    data-status="{{ $p->status }}"
                >

                    <div
                        class="card-pembangunan"
                        data-aos="fade-up"
                        data-aos-delay="{{ min($loop->index * 50, 300) }}"
                    >


                        <!-- =====================================================
                             FOTO PROYEK
                        ====================================================== -->

                        <div class="card-img-wrapper">


                            @if($coverFoto)

                                <img
                                    src="{{ $coverFoto }}"
                                    alt="{{ $p->nama_pembangunan }}"
                                    loading="lazy"
                                    onerror="
                                        this.onerror=null;
                                        this.src='https://placehold.co/600x400/e2e8f0/64748b?text=Belum+Ada+Foto';
                                    "
                                >

                            @else

                                <img
                                    src="https://placehold.co/600x400/e2e8f0/64748b?text=Belum+Ada+Foto"
                                    alt="Belum Ada Foto"
                                    loading="lazy"
                                >

                            @endif


                            <!-- STATUS -->

                            @if($p->status == 'direncanakan')

                                <div class="card-badge plan">

                                    <i class="fas fa-clock me-1"></i>

                                    Direncanakan

                                </div>

                            @elseif($p->status == 'berjalan')

                                <div class="card-badge progress">

                                    <i class="fas fa-spinner fa-pulse me-1"></i>

                                    Berjalan

                                </div>

                            @else

                                <div class="card-badge done">

                                    <i class="fas fa-check me-1"></i>

                                    Selesai

                                </div>

                            @endif


                            <!-- OVERLAY -->

                            <div class="card-overlay">

                                <h6 class="mb-1">

                                    {{ Str::limit(
                                        $p->nama_pembangunan,
                                        30
                                    ) }}

                                </h6>


                                <small>

                                    <i class="fas fa-map-marker-alt me-1"></i>

                                    {{ Str::limit(
                                        $p->lokasi,
                                        25
                                    ) }}

                                </small>

                            </div>

                        </div>


                        <!-- =====================================================
                             CARD BODY
                        ====================================================== -->

                        <div class="card-body p-3 p-md-4">


                            <!-- PROGRESS -->

                            <div class="mb-3">

                                <div
                                    class="d-flex justify-content-between mb-2"
                                >

                                    <small class="text-muted">
                                        Progres Pembangunan
                                    </small>

                                    <small class="fw-bold text-primary">
                                        {{ $progress }}%
                                    </small>

                                </div>


                                <div class="progress-custom">

                                    <div
                                        class="progress-bar {{ $progress == 100 ? 'bg-success' : 'bg-primary' }}"
                                        style="width: {{ $progress }}%"
                                    ></div>

                                </div>

                            </div>


                            <!-- TAHUN & ANGGARAN -->

                            <div class="row mb-3">

                                <div class="col-6">

                                    <small class="text-muted d-block">

                                        <i class="fas fa-calendar me-1"></i>

                                        Tahun

                                    </small>


                                    <small class="fw-bold">

                                        {{ $p->tahun_anggaran }}

                                    </small>

                                </div>


                                <div class="col-6">

                                    <small class="text-muted d-block">

                                        <i class="fas fa-money-bill me-1"></i>

                                        Anggaran

                                    </small>


                                    <small class="fw-bold text-success">

                                        @php

                                            $anggaran =
                                                $p->anggaran;


                                            if (
                                                $anggaran >=
                                                1000000000
                                            ) {

                                                echo 'Rp ' .
                                                    number_format(
                                                        $anggaran /
                                                        1000000000,
                                                        1,
                                                        ',',
                                                        '.'
                                                    ) .
                                                    ' M';

                                            }

                                            elseif (
                                                $anggaran >=
                                                1000000
                                            ) {

                                                echo 'Rp ' .
                                                    number_format(
                                                        $anggaran /
                                                        1000000,
                                                        1,
                                                        ',',
                                                        '.'
                                                    ) .
                                                    ' Jt';

                                            }

                                            else {

                                                echo 'Rp ' .
                                                    number_format(
                                                        $anggaran,
                                                        0,
                                                        ',',
                                                        '.'
                                                    );
                                            }

                                        @endphp

                                    </small>

                                </div>

                            </div>


                            <!-- =================================================
                                 HARGA DEAL
                            ================================================== -->

                            @if($hargaDealCard)

                                <div class="row mb-2">

                                    <div class="col-12">

                                        <hr class="my-1">


                                        <small
                                            class="text-muted d-block"
                                        >

                                            <i
                                                class="fas fa-handshake me-1"
                                            ></i>

                                            Harga Deal

                                        </small>


                                        <small
                                            class="fw-bold text-primary"
                                        >

                                            Rp
                                            {{ number_format(
                                                $hargaDealCard,
                                                0,
                                                ',',
                                                '.'
                                            ) }}

                                        </small>

                                    </div>

                                </div>

                            @endif


                            <!-- DETAIL -->

                            <a
                                href="{{ route(
                                    'publik.show',
                                    $p->id_pembangunan
                                ) }}"
                                class="btn btn-primary w-100 rounded-pill"
                            >

                                <i class="fas fa-search me-2"></i>

                                Lihat Detail

                                <i class="fas fa-arrow-right ms-2"></i>

                            </a>

                        </div>

                    </div>

                </div>


            @empty


                <div class="col-12">

                    <div
                        class="empty-state"
                        id="initialEmptyState"
                    >

                        <i class="fas fa-building"></i>


                        <h4>
                            Belum Ada Data Pembangunan
                        </h4>


                        <p class="text-muted">

                            Saat ini belum ada data pembangunan
                            yang tersedia.

                        </p>

                    </div>

                </div>


            @endforelse

        </div>


        <!-- ========== FILTER EMPTY STATE ========== -->

        <div
            class="row"
            id="filterEmptyState"
            style="display: none;"
        >

            <div class="col-12">

                <div class="empty-state">

                    <i class="fas fa-search"></i>


                    <h4>
                        Tidak Ada Data Ditemukan
                    </h4>


                    <p class="text-muted">

                        Maaf, pencarian
                        "<span id="searchKeyword"></span>"
                        tidak menemukan data pembangunan
                        yang sesuai.

                    </p>


                    <button
                        class="btn btn-primary mt-3"
                        id="resetFilterBtn"
                    >

                        <i class="fas fa-sync-alt me-2"></i>

                        Reset Pencarian

                    </button>

                </div>

            </div>

        </div>


        <!-- ========== PAGINATION ========== -->

        <div class="pagination-custom">

            {{ $pembangunans->links() }}

        </div>

    </div>


    <!-- ========== FOOTER ========== -->

    <footer class="footer">

        <div class="container">

            <div class="row">


                <div class="col-12 col-md-4">

                    <h5>

                        <i class="fas fa-building me-2"></i>

                        Desa Kutabuluh

                    </h5>


                    <p>

                        Sistem Informasi Monitoring dan Evaluasi
                        Pembangunan untuk mewujudkan tata kelola
                        pemerintahan desa yang transparan dan akuntabel.

                    </p>

                </div>


                <div class="col-12 col-md-4">

                    <h5>

                        <i class="fas fa-link me-2"></i>

                        Tautan

                    </h5>


                    <ul class="list-unstyled">

                        <li>

                            <a
                                href="#"
                                class="text-decoration-none text-secondary"
                            >
                                Beranda
                            </a>

                        </li>


                        <li>

                            <a
                                href="#informasi"
                                class="text-decoration-none text-secondary"
                            >
                                Informasi Pembangunan
                            </a>

                        </li>


                        <li>

                            <a
                                href="{{ route('login') }}"
                                class="text-decoration-none text-secondary"
                            >
                                Login Petugas
                            </a>

                        </li>

                    </ul>

                </div>


                <div class="col-12 col-md-4">

                    <h5>

                        <i class="fas fa-clock me-2"></i>

                        Jam Operasional

                    </h5>


                    <p>

                        Senin - Jumat:
                        08:00 - 16:00

                        <br>

                        Sabtu - Minggu:
                        Tutup

                    </p>

                </div>

            </div>


            <div
                class="text-center pt-4 mt-3 border-top border-secondary"
            >

                <small>

                    &copy; {{ date('Y') }}

                    Sistem Informasi Monitoring dan Evaluasi
                    Pembangunan Desa Kutabuluh.

                    All rights reserved.

                </small>

            </div>

        </div>

    </footer>


    <!-- ========== FLOATING BUTTONS ========== -->

    <a
        href="{{ route('login') }}"
        class="float-btn"
    >

        <i class="fas fa-lock"></i>

    </a>


    <div
        class="back-to-top"
        id="backToTop"
    >

        <i class="fas fa-arrow-up"></i>

    </div>


    <!-- ========== SCRIPTS ========== -->

    <script
        src="https://code.jquery.com/jquery-3.7.1.min.js"
    ></script>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    ></script>


    <script
        src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"
    ></script>


    <script>

        (function () {


            // =========================================================
            // DOM ELEMENTS
            // =========================================================

            const loadingScreen =
                document.getElementById(
                    'loadingScreen'
                );


            const navbar =
                document.getElementById(
                    'navbar'
                );


            const backToTop =
                document.getElementById(
                    'backToTop'
                );


            const mobileMenuBtn =
                document.getElementById(
                    'mobileMenuBtn'
                );


            const navLinks =
                document.getElementById(
                    'navLinks'
                );


            const menuOverlay =
                document.getElementById(
                    'menuOverlay'
                );


            const searchInput =
                document.getElementById(
                    'searchInput'
                );


            const statusFilter =
                document.getElementById(
                    'statusFilter'
                );


            const body =
                document.body;


            const pembangunanContainer =
                document.getElementById(
                    'pembangunanContainer'
                );


            const filterEmptyState =
                document.getElementById(
                    'filterEmptyState'
                );


            const initialEmptyState =
                document.getElementById(
                    'initialEmptyState'
                );


            const pagination =
                document.querySelector(
                    '.pagination-custom'
                );


            const searchKeywordSpan =
                document.getElementById(
                    'searchKeyword'
                );


            const loadingMessage =
                document.getElementById(
                    'loadingMessage'
                );


            const step1 =
                document.getElementById(
                    'step1'
                );


            const step2 =
                document.getElementById(
                    'step2'
                );


            const step3 =
                document.getElementById(
                    'step3'
                );


            const tipText =
                document.getElementById(
                    'tipText'
                );


            let isMenuOpen = false;


            // =========================================================
            // LOADING MESSAGES
            // =========================================================

            const messages = [

                'Memuat data pembangunan',

                'Menyiapkan galeri dokumentasi',

                'Hampir selesai'

            ];


            const tips = [

                'Transparansi pembangunan untuk masyarakat',

                'Pantau progres pembangunan secara real-time',

                'Lihat dokumentasi kegiatan pembangunan',

                'Informasi anggaran tersedia transparan',

                'Partisipasi masyarakat untuk desa lebih baik'

            ];


            let currentStep = 0;


            // =========================================================
            // LOADING STEPS
            // =========================================================

            const stepInterval =
                setInterval(
                    function () {

                        currentStep++;


                        if (
                            currentStep <= 3
                        ) {

                            if (
                                loadingMessage
                            ) {

                                loadingMessage.textContent =
                                    messages[
                                        currentStep - 1
                                    ];
                            }


                            if (
                                currentStep === 1
                            ) {

                                if (step1) {

                                    step1.classList.add(
                                        'completed'
                                    );

                                }


                                if (step2) {

                                    step2.classList.add(
                                        'active'
                                    );

                                }

                            }


                            else if (
                                currentStep === 2
                            ) {

                                if (step2) {

                                    step2.classList.remove(
                                        'active'
                                    );

                                    step2.classList.add(
                                        'completed'
                                    );

                                }


                                if (step3) {

                                    step3.classList.add(
                                        'active'
                                    );

                                }

                            }


                            if (
                                tipText
                            ) {

                                const randomTip =
                                    tips[
                                        Math.floor(
                                            Math.random() *
                                            tips.length
                                        )
                                    ];


                                tipText.textContent =
                                    randomTip;
                            }

                        }

                    },
                    600
                );


            // =========================================================
            // HIDE LOADING SCREEN
            // =========================================================

            function hideLoadingScreen() {

                setTimeout(
                    function () {

                        if (step1) {

                            step1.classList.add(
                                'completed'
                            );

                        }


                        if (step2) {

                            step2.classList.add(
                                'completed'
                            );

                        }


                        if (step3) {

                            step3.classList.add(
                                'completed'
                            );

                        }


                        setTimeout(
                            function () {

                                if (
                                    loadingScreen
                                ) {

                                    loadingScreen.classList.add(
                                        'hide'
                                    );

                                }


                                clearInterval(
                                    stepInterval
                                );

                            },
                            500
                        );

                    },
                    2000
                );

            }


            // =========================================================
            // TOGGLE MOBILE MENU
            // =========================================================

            function toggleMenu() {

                isMenuOpen =
                    !isMenuOpen;


                if (
                    isMenuOpen
                ) {

                    navLinks.classList.add(
                        'active'
                    );


                    if (
                        menuOverlay
                    ) {

                        menuOverlay.classList.add(
                            'active'
                        );

                    }


                    body.classList.add(
                        'menu-open'
                    );


                    mobileMenuBtn.innerHTML =
                        '<i class="fas fa-times"></i>';

                }

                else {

                    navLinks.classList.remove(
                        'active'
                    );


                    if (
                        menuOverlay
                    ) {

                        menuOverlay.classList.remove(
                            'active'
                        );

                    }


                    body.classList.remove(
                        'menu-open'
                    );


                    mobileMenuBtn.innerHTML =
                        '<i class="fas fa-bars"></i>';

                }

            }


            // =========================================================
            // CLOSE MENU
            // =========================================================

            function closeMenu() {

                if (
                    isMenuOpen
                ) {

                    isMenuOpen =
                        false;


                    navLinks.classList.remove(
                        'active'
                    );


                    if (
                        menuOverlay
                    ) {

                        menuOverlay.classList.remove(
                            'active'
                        );

                    }


                    body.classList.remove(
                        'menu-open'
                    );


                    mobileMenuBtn.innerHTML =
                        '<i class="fas fa-bars"></i>';

                }

            }


            // =========================================================
            // SCROLL
            // =========================================================

            let scrollTicking =
                false;


            function handleScroll() {

                if (
                    scrollTicking
                ) {

                    return;
                }


                scrollTicking =
                    true;


                window.requestAnimationFrame(
                    function () {

                        if (
                            navbar
                        ) {

                            navbar.classList.toggle(
                                'scrolled',
                                window.scrollY > 35
                            );

                        }


                        if (
                            backToTop
                        ) {

                            backToTop.classList.toggle(
                                'show',
                                window.scrollY > 300
                            );

                        }


                        scrollTicking =
                            false;

                    }
                );

            }


            // =========================================================
            // BACK TO TOP
            // =========================================================

            function handleBackToTop() {

                window.scrollTo({

                    top: 0,

                    behavior: 'smooth'

                });

            }


            // =========================================================
            // RESET FILTER
            // =========================================================

            function resetFilter() {

                if (
                    searchInput
                ) {

                    searchInput.value = '';

                }


                if (
                    statusFilter
                ) {

                    statusFilter.value = '';

                }


                filterPembangunan();

            }


            // =========================================================
            // FILTER
            // =========================================================

            let filterTimeout;


            function filterPembangunan() {

                const searchText =
                    searchInput
                        ? searchInput.value.toLowerCase()
                        : '';


                const statusFilterValue =
                    statusFilter
                        ? statusFilter.value
                        : '';


                const items =
                    document.querySelectorAll(
                        '.pembangunan-item'
                    );


                let visibleCount = 0;


                requestAnimationFrame(
                    () => {

                        items.forEach(
                            item => {

                                const name =
                                    item.dataset.name;


                                const status =
                                    item.dataset.status;


                                const matchSearch =
                                    name.indexOf(
                                        searchText
                                    ) > -1;


                                const matchStatus =
                                    statusFilterValue === ''
                                    ||
                                    status ===
                                    statusFilterValue;


                                if (
                                    matchSearch &&
                                    matchStatus
                                ) {

                                    item.style.display =
                                        '';

                                    visibleCount++;

                                }

                                else {

                                    item.style.display =
                                        'none';

                                }

                            }
                        );


                        const hasItems =
                            items.length > 0;


                        if (
                            visibleCount === 0 &&
                            hasItems
                        ) {

                            if (
                                filterEmptyState
                            ) {

                                if (
                                    searchKeywordSpan
                                ) {

                                    let keyword =
                                        searchText;


                                    if (
                                        statusFilterValue &&
                                        !searchText
                                    ) {

                                        keyword =
                                            statusFilterValue ===
                                            'direncanakan'
                                                ? 'Direncanakan'
                                                :
                                            (
                                                statusFilterValue ===
                                                'berjalan'
                                            )
                                                ? 'Sedang Berjalan'
                                                :
                                                'Selesai';

                                    }

                                    else if (
                                        statusFilterValue &&
                                        searchText
                                    ) {

                                        keyword =
                                            searchText +
                                            ' (Status: ' +
                                            (
                                                statusFilterValue ===
                                                'direncanakan'
                                                    ? 'Direncanakan'
                                                    :
                                                (
                                                    statusFilterValue ===
                                                    'berjalan'
                                                )
                                                    ? 'Sedang Berjalan'
                                                    :
                                                    'Selesai'
                                            ) +
                                            ')';

                                    }


                                    searchKeywordSpan.innerText =
                                        keyword || '-';

                                }


                                filterEmptyState.style.display =
                                    'block';


                                if (
                                    pembangunanContainer
                                ) {

                                    pembangunanContainer.style.display =
                                        'none';

                                }


                                if (
                                    pagination
                                ) {

                                    pagination.style.display =
                                        'none';

                                }

                            }

                        }

                        else {

                            if (
                                filterEmptyState
                            ) {

                                filterEmptyState.style.display =
                                    'none';

                            }


                            if (
                                pembangunanContainer
                            ) {

                                pembangunanContainer.style.display =
                                    'flex';

                            }


                            if (
                                pagination &&
                                hasItems
                            ) {

                                pagination.style.display =
                                    'flex';

                            }

                            else if (
                                pagination &&
                                !hasItems
                            ) {

                                pagination.style.display =
                                    'none';

                            }

                        }


                        if (
                            !hasItems
                        ) {

                            if (
                                initialEmptyState
                            ) {

                                initialEmptyState.style.display =
                                    'block';

                            }


                            if (
                                pagination
                            ) {

                                pagination.style.display =
                                    'none';

                            }

                        }

                        else {

                            if (
                                initialEmptyState
                            ) {

                                initialEmptyState.style.display =
                                    'none';

                            }

                        }

                    }
                );

            }


            // =========================================================
            // DEBOUNCED FILTER
            // =========================================================

            function debouncedFilter() {

                clearTimeout(
                    filterTimeout
                );


                filterTimeout =
                    setTimeout(
                        filterPembangunan,
                        200
                    );

            }


            // =========================================================
            // COUNTERS
            // =========================================================

            function initCounters() {

                const counters =
                    document.querySelectorAll(
                        '.counter'
                    );


                if (
                    counters.length === 0
                ) {

                    return;
                }


                const observerOptions = {

                    threshold: 0.3,

                    rootMargin: '0px'

                };


                const observer =
                    new IntersectionObserver(
                        (entries) => {

                            entries.forEach(
                                entry => {

                                    if (
                                        entry.isIntersecting
                                    ) {

                                        const counter =
                                            entry.target;


                                        const target =
                                            parseInt(
                                                counter.dataset.target
                                            );


                                        let current =
                                            0;


                                        const increment =
                                            target /
                                            40;


                                        counter.innerText =
                                            '0';


                                        const timer =
                                            setInterval(
                                                () => {

                                                    current +=
                                                        increment;


                                                    if (
                                                        current >=
                                                        target
                                                    ) {

                                                        counter.innerText =
                                                            target;

                                                        clearInterval(
                                                            timer
                                                        );

                                                    }

                                                    else {

                                                        counter.innerText =
                                                            Math.floor(
                                                                current
                                                            );

                                                    }

                                                },
                                                20
                                            );


                                        observer.unobserve(
                                            counter
                                        );

                                    }

                                }
                            );

                        },
                        observerOptions
                    );


                counters.forEach(
                    counter =>
                        observer.observe(
                            counter
                        )
                );

            }


            // =========================================================
            // SMOOTH SCROLL
            // =========================================================

            function initSmoothScroll() {

                document
                    .querySelectorAll(
                        'a[href^="#"]'
                    )
                    .forEach(
                        anchor => {

                            anchor.addEventListener(
                                'click',
                                function (e) {

                                    const targetId =
                                        this.getAttribute(
                                            'href'
                                        );


                                    if (
                                        targetId === '#'
                                    ) {

                                        return;
                                    }


                                    const target =
                                        document.querySelector(
                                            targetId
                                        );


                                    if (
                                        target
                                    ) {

                                        e.preventDefault();


                                        closeMenu();


                                        target.scrollIntoView({

                                            behavior:
                                                'smooth',

                                            block:
                                                'start'

                                        });

                                    }

                                }
                            );

                        }
                    );

            }


            // =========================================================
            // RESET FILTER BUTTON
            // =========================================================

            // =========================================================
            // PENGATURAN TAMPILAN: BARIS / 3 / 4 / 5 KOLOM
            // =========================================================
            function initLayoutSwitcher() {
                const container = document.getElementById('pembangunanContainer');
                const buttons = document.querySelectorAll('.layout-btn');

                if (!container || !buttons.length) return;

                const savedLayout =
                    localStorage.getItem('sismolev_pembangunan_layout') || 'grid-4';

                function applyLayout(layout) {
                    container.classList.remove(
                        'layout-grid',
                        'layout-list',
                        'grid-3',
                        'grid-4',
                        'grid-5'
                    );

                    if (layout === 'list') {
                        container.classList.add('layout-list');
                    } else {
                        container.classList.add('layout-grid', layout);
                    }

                    buttons.forEach(btn => {
                        btn.classList.toggle(
                            'active',
                            btn.dataset.layout === layout
                        );
                    });

                    localStorage.setItem(
                        'sismolev_pembangunan_layout',
                        layout
                    );

                    if (typeof AOS !== 'undefined') {
                        setTimeout(() => AOS.refresh(), 100);
                    }
                }

                buttons.forEach(button => {
                    button.addEventListener('click', function () {
                        applyLayout(this.dataset.layout);
                    });
                });

                applyLayout(savedLayout);
            }

            function initResetFilter() {

                const resetBtn =
                    document.getElementById(
                        'resetFilterBtn'
                    );


                if (
                    resetBtn
                ) {

                    resetBtn.addEventListener(
                        'click',
                        resetFilter
                    );

                }

            }


            // =========================================================
            // EVENT LISTENERS
            // =========================================================

            function initEventListeners() {

                window.addEventListener(
                    'load',
                    hideLoadingScreen
                );


                window.addEventListener(
                    'scroll',
                    handleScroll,
                    {
                        passive: true
                    }
                );


                window.addEventListener(
                    'resize',
                    function () {

                        if (
                            window.innerWidth > 768 &&
                            isMenuOpen
                        ) {

                            closeMenu();

                        }

                    }
                );


                document.addEventListener(
                    'keydown',
                    function (event) {

                        if (
                            event.key === 'Escape' &&
                            isMenuOpen
                        ) {

                            closeMenu();

                        }

                    }
                );


                if (
                    backToTop
                ) {

                    backToTop.addEventListener(
                        'click',
                        handleBackToTop
                    );

                }


                if (
                    mobileMenuBtn
                ) {

                    mobileMenuBtn.addEventListener(
                        'click',
                        toggleMenu
                    );

                }


                if (
                    menuOverlay
                ) {

                    menuOverlay.addEventListener(
                        'click',
                        closeMenu
                    );

                }


                if (
                    searchInput
                ) {

                    searchInput.addEventListener(
                        'input',
                        debouncedFilter
                    );

                }


                if (
                    statusFilter
                ) {

                    statusFilter.addEventListener(
                        'change',
                        filterPembangunan
                    );

                }


                const navLinksItems =
                    document.querySelectorAll(
                        '#navLinks a'
                    );


                navLinksItems.forEach(
                    link =>
                        link.addEventListener(
                            'click',
                            closeMenu
                        )
                );

            }


            // =========================================================
            // AOS
            // =========================================================

            function initAOS() {

                if (
                    typeof AOS !== 'undefined'
                ) {

                    AOS.init({

                        duration: 600,

                        once: true,

                        disable:
                            window.innerWidth < 768
                                ? 'mobile'
                                : false,

                        startEvent: 'load'

                    });

                }

            }


            // =========================================================
            // INIT
            // =========================================================

            function init() {

                initEventListeners();

                initCounters();

                initSmoothScroll();

                initAOS();

                initResetFilter();
                initLayoutSwitcher();

                handleScroll();

            }


            if (
                document.readyState ===
                'loading'
            ) {

                document.addEventListener(
                    'DOMContentLoaded',
                    init
                );

            }

            else {

                init();

            }

        })();

    </script>

</body>

</html>