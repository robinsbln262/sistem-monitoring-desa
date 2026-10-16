<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistem Informasi Monitoring dan Evaluasi Pembangunan Desa Kutabuluh</title>

    <!-- =====================================================
         FAVICON / LOGO
    ====================================================== -->

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-kab-karo.png') }}">

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo-kab-karo.png') }}">

    <link rel="shortcut icon" href="{{ asset('images/logo-kab-karo.png') }}" type="image/x-icon">


    <!-- =====================================================
         GOOGLE FONT
    ====================================================== -->

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">


    <!-- =====================================================
         BOOTSTRAP
    ====================================================== -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- =====================================================
         FONT AWESOME
    ====================================================== -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <!-- =====================================================
         DATATABLES
    ====================================================== -->

    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">


    <!-- =====================================================
         SUMMERNOTE
    ====================================================== -->

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.css" rel="stylesheet">


    <style>
        /* =====================================================
           VARIABLES
        ====================================================== */

        :root {

            --primary: #1e3c72;

            --primary-dark: #0f2748;

            --secondary: #2a5298;

            --accent: #667eea;

            --gradient-primary:
                linear-gradient(135deg,
                    #1e3c72 0%,
                    #2a5298 100%);

            --success: #10b981;

            --warning: #f59e0b;

            --danger: #ef4444;

            --dark: #1f2937;

            --light: #f3f4f6;

        }


        /* =====================================================
           RESET
        ====================================================== */

        * {

            margin: 0;

            padding: 0;

            box-sizing: border-box;

        }


        html {

            scroll-behavior: smooth;

        }


        body {

            font-family: 'Inter', sans-serif;

            background: #f4f6f9;

            overflow-x: hidden;

        }


        /* =====================================================
           SMOOTH ULTIMATE PRO MAX LOADING SCREEN
           Premium / Glass / Fluid / No Jank
        ====================================================== */

        .loading-screen {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            isolation: isolate;
            z-index: 99999;

            background:
                radial-gradient(circle at 50% 35%,
                    rgba(89, 145, 255, .20) 0%,
                    rgba(89, 145, 255, .07) 24%,
                    transparent 50%),
                radial-gradient(circle at 15% 85%,
                    rgba(255, 193, 7, .10) 0%,
                    transparent 38%),
                linear-gradient(135deg,
                    #122f61 0%,
                    #1e3c72 45%,
                    #2a5298 100%);

            opacity: 1;
            visibility: visible;
            pointer-events: auto;

            transform: translateZ(0);
            will-change: opacity, visibility, transform;

            transition:
                opacity .72s cubic-bezier(.22, 1, .36, 1),
                visibility .72s ease;
        }

        /* Soft moving light behind the loader */
        .loading-screen::before {
            content: "";
            position: absolute;
            width: 58vw;
            height: 58vw;
            min-width: 420px;
            min-height: 420px;
            max-width: 900px;
            max-height: 900px;
            left: 50%;
            top: 42%;
            transform: translate(-50%, -50%);
            border-radius: 50%;

            background:
                radial-gradient(circle,
                    rgba(255, 255, 255, .085) 0%,
                    rgba(255, 255, 255, .025) 34%,
                    transparent 68%);

            filter: blur(1px);
            animation: loaderAmbient 7s ease-in-out infinite;
            pointer-events: none;
            z-index: -2;
        }

        /* Fine glass/noise-like overlay */
        .loading-screen::after {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: -1;
            opacity: .45;

            background:
                linear-gradient(115deg,
                    transparent 0%,
                    rgba(255, 255, 255, .025) 35%,
                    transparent 50%,
                    rgba(255, 255, 255, .018) 68%,
                    transparent 100%);

            background-size: 220% 100%;
            animation: loaderShimmer 5.5s linear infinite;
        }

        .loading-screen.hide {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        /* While loading, prevent the page underneath from jumping */
        body.is-loading {
            overflow: hidden !important;
        }

        .loader-ultimate {
            width: min(92vw, 430px);
            padding: 32px 30px 29px;
            text-align: center;
            position: relative;
            transform: translate3d(0, 0, 0);
            animation: loaderContentIn .72s cubic-bezier(.22, 1, .36, 1) both;
        }

        /* =====================================================
           LOGO
        ====================================================== */

        .loader-ultimate .logo-wrapper {
            width: 132px;
            height: 132px;
            margin: 0 auto 25px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Outer orbit */
        .loader-ultimate .logo-wrapper::before,
        .loader-ultimate .logo-wrapper::after {
            content: "";
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            pointer-events: none;
        }

        .loader-ultimate .logo-wrapper::before {
            border: 1px solid rgba(255, 255, 255, .16);
            border-top-color: rgba(255, 255, 255, .72);
            border-right-color: rgba(255, 193, 7, .60);
            animation: loaderOrbit 2.8s cubic-bezier(.55, .05, .35, .95) infinite;
        }

        .loader-ultimate .logo-wrapper::after {
            inset: -18px;
            border: 1px solid rgba(255, 255, 255, .055);
            border-bottom-color: rgba(255, 255, 255, .25);
            animation: loaderOrbitReverse 4.5s linear infinite;
        }

        .loader-ultimate .logo-circle {
            width: 112px;
            height: 112px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;

            background:
                linear-gradient(145deg,
                    rgba(255, 255, 255, .17),
                    rgba(255, 255, 255, .055));

            border: 1px solid rgba(255, 255, 255, .18);

            box-shadow:
                0 22px 55px rgba(0, 0, 0, .18),
                inset 0 1px 0 rgba(255, 255, 255, .18),
                inset 0 -12px 30px rgba(0, 0, 0, .055);

            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);

            animation:
                loaderLogoFloat 3.2s ease-in-out infinite,
                loaderGlow 2.4s ease-in-out infinite;
        }

        /* Golden accent ring */
        .loader-ultimate .logo-circle::before {
            content: "";
            position: absolute;
            inset: 6px;
            border-radius: 50%;
            border: 1px solid rgba(255, 193, 7, .22);
            box-shadow:
                0 0 22px rgba(255, 193, 7, .08),
                inset 0 0 18px rgba(255, 255, 255, .04);
            pointer-events: none;
        }

        /* Rotating highlight */
        .loader-ultimate .logo-circle::after {
            content: "";
            position: absolute;
            inset: -2px;
            border-radius: 50%;
            background:
                conic-gradient(from 0deg,
                    transparent 0deg,
                    rgba(255, 255, 255, .0) 245deg,
                    rgba(255, 255, 255, .38) 292deg,
                    rgba(255, 193, 7, .22) 318deg,
                    transparent 345deg);
            -webkit-mask:
                radial-gradient(farthest-side,
                    transparent calc(100% - 2px),
                    #000 calc(100% - 1px));
            mask:
                radial-gradient(farthest-side,
                    transparent calc(100% - 2px),
                    #000 calc(100% - 1px));
            animation: loaderRingSpin 2.6s linear infinite;
            pointer-events: none;
        }

        .loader-ultimate .logo-circle img {
            width: 76px;
            height: 76px;
            object-fit: contain;
            position: relative;
            z-index: 3;
            background: transparent;
            border-radius: 0;
            filter:
                drop-shadow(0 8px 13px rgba(0, 0, 0, .22)) drop-shadow(0 0 12px rgba(255, 255, 255, .08));

            animation: loaderIconBreath 2.8s ease-in-out infinite;
        }

        /* =====================================================
           TEXT
        ====================================================== */

        .loader-ultimate h3 {
            color: #fff;
            font-size: 1.55rem;
            line-height: 1.15;
            font-weight: 800;
            margin: 0 0 9px;
            letter-spacing: 2.8px;
            text-shadow: 0 5px 22px rgba(0, 0, 0, .18);
            animation: loaderTextIn .7s .08s cubic-bezier(.22, 1, .36, 1) both;
        }

        .loader-ultimate p {
            color: rgba(255, 255, 255, .68);
            font-size: .84rem;
            line-height: 1.65;
            margin: 0 0 24px;
            animation: loaderTextIn .7s .14s cubic-bezier(.22, 1, .36, 1) both;
        }

        /* =====================================================
           PROGRESS
        ====================================================== */

        .loader-ultimate .progress-container {
            width: min(280px, 78vw);
            height: 5px;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
            border-radius: 999px;

            background: rgba(255, 255, 255, .11);
            border: 1px solid rgba(255, 255, 255, .08);
            box-shadow:
                inset 0 1px 3px rgba(0, 0, 0, .10),
                0 4px 18px rgba(0, 0, 0, .06);

            animation: loaderTextIn .7s .20s cubic-bezier(.22, 1, .36, 1) both;
        }

        .loader-ultimate .progress-fill {
            width: 0%;
            height: 100%;
            position: relative;
            overflow: hidden;
            border-radius: inherit;

            background:
                linear-gradient(90deg,
                    rgba(255, 255, 255, .95),
                    #fff,
                    #ffc107);

            box-shadow:
                0 0 12px rgba(255, 255, 255, .22),
                0 0 18px rgba(255, 193, 7, .18);

            animation:
                loadingProgress 2.35s cubic-bezier(.22, 1, .36, 1) infinite;
        }

        .loader-ultimate .progress-fill::after {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            width: 80px;
            left: -80px;
            background:
                linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, .72),
                    transparent);
            filter: blur(2px);
            animation: loaderProgressShine 1.35s ease-in-out infinite;
        }

        .loader-ultimate .loading-text {
            margin-top: 14px;
            color: rgba(255, 255, 255, .58);
            font-size: .73rem;
            letter-spacing: .25px;
            font-family: Inter, sans-serif;
            animation: loaderTextIn .7s .26s cubic-bezier(.22, 1, .36, 1) both;
        }

        .loader-ultimate .loading-dots {
            display: inline-block;
            width: 18px;
            text-align: left;
            color: rgba(255, 255, 255, .72);
        }

        .loader-ultimate .loading-dots::after {
            content: '';
            animation: dots 1.25s steps(4, end) infinite;
        }

        /* Small bottom status pill */
        .loader-ultimate::after {
            content: "SECURE â€¢ LOADING";
            display: block;
            width: fit-content;
            margin: 19px auto 0;
            padding: 5px 10px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .09);
            background: rgba(255, 255, 255, .045);
            color: rgba(255, 255, 255, .40);
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 1.4px;
            animation: loaderStatusPulse 2s ease-in-out infinite;
        }

        /* =====================================================
           ANIMATIONS
        ====================================================== */

        @keyframes loaderContentIn {
            0% {
                opacity: 0;
                transform: translate3d(0, 18px, 0) scale(.975);
            }

            100% {
                opacity: 1;
                transform: translate3d(0, 0, 0) scale(1);
            }
        }

        @keyframes loaderTextIn {
            0% {
                opacity: 0;
                transform: translate3d(0, 8px, 0);
            }

            100% {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        @keyframes loaderAmbient {

            0%,
            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: .72;
            }

            50% {
                transform: translate(-50%, -50%) scale(1.12);
                opacity: 1;
            }
        }

        @keyframes loaderShimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -20% 0;
            }
        }

        @keyframes loaderOrbit {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes loaderOrbitReverse {
            0% {
                transform: rotate(360deg);
            }

            100% {
                transform: rotate(0deg);
            }
        }

        @keyframes loaderRingSpin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes loaderLogoFloat {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-4px) scale(1.018);
            }
        }

        @keyframes loaderGlow {

            0%,
            100% {
                box-shadow:
                    0 22px 55px rgba(0, 0, 0, .18),
                    inset 0 1px 0 rgba(255, 255, 255, .18),
                    inset 0 -12px 30px rgba(0, 0, 0, .055);
            }

            50% {
                box-shadow:
                    0 25px 62px rgba(0, 0, 0, .22),
                    0 0 35px rgba(255, 255, 255, .07),
                    inset 0 1px 0 rgba(255, 255, 255, .22),
                    inset 0 -12px 30px rgba(0, 0, 0, .04);
            }
        }

        @keyframes loaderIconBreath {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.035);
            }
        }

        @keyframes loaderProgressShine {
            0% {
                left: -90px;
                opacity: 0;
            }

            20% {
                opacity: 1;
            }

            70% {
                opacity: 1;
            }

            100% {
                left: 110%;
                opacity: 0;
            }
        }

        @keyframes loaderStatusPulse {

            0%,
            100% {
                opacity: .52;
                transform: translateY(0);
            }

            50% {
                opacity: .85;
                transform: translateY(-1px);
            }
        }

        @keyframes loadingProgress {
            0% {
                width: 0%;
            }

            25% {
                width: 24%;
            }

            58% {
                width: 63%;
            }

            82% {
                width: 84%;
            }

            100% {
                width: 100%;
            }
        }

        @keyframes dots {

            0%,
            20% {
                content: '';
            }

            40% {
                content: '.';
            }

            60% {
                content: '..';
            }

            80%,
            100% {
                content: '...';
            }
        }

        @media (max-width: 575.98px) {
            .loader-ultimate {
                padding: 24px 18px;
            }

            .loader-ultimate .logo-wrapper {
                width: 104px;
                height: 104px;
                margin-bottom: 21px;
            }

            .loader-ultimate .logo-circle {
                width: 90px;
                height: 90px;
            }

            .loader-ultimate .logo-circle img {
                width: 61px;
                height: 61px;
            }

            .loader-ultimate h3 {
                font-size: 1.2rem;
                letter-spacing: 2px;
            }

            .loader-ultimate p {
                font-size: .76rem;
            }

            .loader-ultimate::after {
                margin-top: 16px;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .loading-screen *,
            .loading-screen::before,
            .loading-screen::after {
                animation: none !important;
            }
        }

        /* =====================================================
           SIDEBAR
           BERLAKU UNTUK SEMUA ROLE
        ====================================================== */

        .sidebar {

            width: 260px;

            height: 100vh !important;

            min-height: 100vh !important;

            max-height: 100vh !important;

            background:
                linear-gradient(135deg,
                    #1e3c72 0%,
                    #2a5298 100%);

            position: fixed;

            left: 0;

            top: 0;

            bottom: 0;

            z-index: 1000;

            box-shadow:
                2px 0 20px rgba(0, 0, 0, 0.1);

            display: flex;

            flex-direction: column;

            overflow: hidden !important;

        }


        /* =====================================================
           SIDEBAR BRAND
        ====================================================== */

        .sidebar .brand {

            flex: 0 0 auto !important;

            padding: 25px 20px;

            text-align: center;

            border-bottom:
                1px solid rgba(255, 255, 255, 0.1);

            margin: 0;

        }


        .sidebar .brand .logo-icon {

            width: 75px;

            height: 75px;

            background: transparent !important;

            border-radius: 0 !important;

            display: flex;

            align-items: center;

            justify-content: center;

            margin: 0 auto 12px;

            padding: 0;

            overflow: visible;

            box-shadow: none !important;

            transition:
                transform 0.3s ease;

        }


        .sidebar .brand .logo-icon img {

            width: 100%;

            height: 100%;

            object-fit: contain;

            display: block;

            background: transparent;

            border-radius: 0;

            box-shadow: none;

        }


        .sidebar .brand .logo-icon:hover {

            transform: scale(1.06);

        }


        .sidebar .brand h4 {

            color: white;

            margin: 0;

            font-size: 18px;

            font-weight: 700;

            letter-spacing: 1px;

        }


        .sidebar .brand small {

            color:
                rgba(255, 255, 255, 0.6);

            font-size: 11px;

        }


        /* =====================================================
           SIDEBAR NAVIGATION
           INI SATU-SATUNYA AREA YANG SCROLL
           ADMIN / REKANAN / KEPALA DESA SEMUA SAMA
        ====================================================== */

        .sidebar>nav.nav {

            /* Sidebar = fixed full height.
               Brand stays fixed; ONLY this nav scrolls. */
            flex: 1 1 0% !important;

            min-height: 0 !important;

            height: auto !important;

            max-height: none !important;

            width: 100% !important;

            display: flex !important;

            flex-direction: column !important;

            /* IMPORTANT:
               Bootstrap .nav defaults to flex-wrap: wrap.
               That was causing menu items to jump sideways. */
            flex-wrap: nowrap !important;

            align-items: stretch !important;

            align-content: stretch !important;

            overflow-y: auto !important;

            overflow-x: hidden !important;

            padding: 5px 0 30px !important;

            margin: 0 !important;

            overscroll-behavior: contain;

            -webkit-overflow-scrolling: touch;

            scroll-behavior: smooth;

            scrollbar-width: auto;

            scrollbar-color:
                rgba(255, 255, 255, .45) rgba(255, 255, 255, .06);

        }


        /* Bootstrap .nav + .flex-column safety override */
        .sidebar>nav.nav.flex-column {

            display: flex !important;

            flex-direction: column !important;

            flex-wrap: nowrap !important;

            align-items: stretch !important;

            align-content: stretch !important;

        }


        /* Every menu item must stay in ONE column */
        .sidebar>nav.nav>.nav-link {

            flex: 0 0 auto !important;

            width: 100% !important;

            max-width: 100% !important;

            min-width: 0 !important;

            box-sizing: border-box !important;

        }


        /* Chrome / Edge / Safari */

        .sidebar>nav.nav::-webkit-scrollbar {

            width: 9px;

        }


        .sidebar>nav.nav::-webkit-scrollbar-track {

            background:
                rgba(255, 255, 255, .06);

        }


        .sidebar>nav.nav::-webkit-scrollbar-thumb {

            background:
                rgba(255, 255, 255, .45);

            border-radius: 999px;

        }


        .sidebar>nav.nav::-webkit-scrollbar-thumb:hover {

            background:
                rgba(255, 255, 255, .70);

        }


        /* =====================================================
           NAV LINK
        ====================================================== */

        .sidebar .nav-link {

            color:
                rgba(255, 255, 255, 0.75);

            padding: 12px 25px;

            margin: 4px 0;

            transition: all 0.3s;

            border-radius: 0;

            font-weight: 500;

            font-size: 14px;

            white-space: nowrap;

            flex: 0 0 auto !important;

            min-height: 48px;

            display: flex;

            align-items: center;

        }


        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {

            background:
                rgba(255, 255, 255, 0.12);

            color: white;

            border-left:
                4px solid #ffc107;

        }


        .sidebar .nav-link i {

            margin-right: 12px;

            width: 20px;

            text-align: center;

            font-size: 16px;

            flex-shrink: 0;

        }


        /* Jangan biarkan item nav mengecil */

        .sidebar>nav.nav>* {

            flex: 0 0 auto !important;

        }


        /* =====================================================
           MAIN CONTENT
        ====================================================== */

        .main-content {

            margin-left: 260px;

            padding: 0;

            min-height: 100vh;

        }


        /* =====================================================
           NAVBAR
        ====================================================== */

        .navbar-custom {

            background: white;

            box-shadow:
                0 2px 15px rgba(0, 0, 0, 0.05);

            padding: 12px 25px;

            position: sticky;

            top: 0;

            z-index: 999;

        }


        .navbar-custom h5 {

            font-weight: 700;

            color: var(--primary);

        }


        /* =====================================================
           USER DROPDOWN
        ====================================================== */

        .user-dropdown {

            position: relative;

        }


        .user-avatar {

            width: 45px;

            height: 45px;

            background:
                linear-gradient(135deg,
                    #1e3c72 0%,
                    #2a5298 100%);

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            color: white;

            font-weight: bold;

            font-size: 18px;

            cursor: pointer;

            transition: all 0.3s ease;

            box-shadow:
                0 2px 8px rgba(0, 0, 0, 0.15);

        }


        .user-avatar:hover {

            transform: scale(1.05);

            box-shadow:
                0 4px 12px rgba(0, 0, 0, 0.2);

        }


        .dropdown-menu-user {

            background: white !important;

            border: none !important;

            border-radius: 12px !important;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.15) !important;

            min-width: 220px !important;

            padding: 8px 0 !important;

            margin-top: 10px !important;

        }


        .dropdown-menu-user .dropdown-item {

            padding: 10px 20px !important;

            font-size: 14px !important;

            transition: all 0.2s ease !important;

            color: #333 !important;

        }


        .dropdown-menu-user .dropdown-item:hover {

            background: #f8f9fa !important;

            padding-left: 25px !important;

        }


        .dropdown-menu-user .dropdown-item i {

            width: 20px;

            margin-right: 10px;

        }


        .dropdown-menu-user .dropdown-divider {

            margin: 8px 0 !important;

            border-top:
                1px solid #e9ecef !important;

        }


        .dropdown-menu-user .text-danger {

            color: #dc3545 !important;

        }


        .dropdown-menu-user .text-danger:hover {

            background: #fee2e2 !important;

            color: #b91c1c !important;

        }


        /* =====================================================
           CONTENT
        ====================================================== */

        .content-wrapper {

            padding: 25px;

            min-height:
                calc(100vh - 150px);

        }


        /* =====================================================
           CARDS
        ====================================================== */

        .card-stats {

            border: none;

            border-radius: 16px;

            transition: all 0.3s;

            overflow: hidden;

            position: relative;

        }


        .card-stats::before {

            content: '';

            position: absolute;

            top: 0;

            left: 0;

            right: 0;

            height: 4px;

            background:
                rgba(255, 255, 255, 0.3);

        }


        .card-stats:hover {

            transform: translateY(-5px);

            box-shadow:
                0 15px 30px rgba(0, 0, 0, 0.1);

        }


        .card-stats .card-body {

            padding: 20px;

        }


        .table-wrapper {

            background: white;

            border-radius: 16px;

            padding: 20px;

            box-shadow:
                0 2px 12px rgba(0, 0, 0, 0.05);

        }


        .btn-action {

            margin: 0 3px;

            padding: 6px 12px;

            border-radius: 8px;

            transition: all 0.2s;

        }


        .btn-action:hover {

            transform: translateY(-2px);

        }


        /* =====================================================
           MODAL
        ====================================================== */

        .modal-header {

            background:
                var(--gradient-primary);

            color: white;

            border: none;

            border-radius:
                16px 16px 0 0;

        }


        .modal-header .btn-close {

            filter:
                brightness(0) invert(1);

        }


        .modal-content {

            border: none;

            border-radius: 16px;

            overflow: hidden;

        }


        /* =====================================================
           PROGRESS
        ====================================================== */

        .progress {

            height: 8px;

            border-radius: 10px;

            background: #e5e7eb;

        }


        .badge-status {

            padding: 5px 12px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: 500;

        }


        /* =====================================================
           SUMMERNOTE
        ====================================================== */

        .note-editor.note-frame {

            border-radius: 10px;

            border:
                1px solid #e5e7eb;

        }


        .note-editor.note-frame .note-editing-area .note-editable {

            background: white;

            min-height: 250px;

        }


        .note-toolbar {

            background: #f8f9fa;

            border-radius:
                8px 8px 0 0;

            padding: 8px !important;

            border-bottom:
                1px solid #e5e7eb;

        }


        .note-btn {

            padding: 4px 8px !important;

            border-radius: 4px;

        }


        .note-btn:hover {

            background: #e9ecef;

        }


        .modal-open .note-editor {

            z-index: 1060;

        }


        .note-editor .note-dropdown-menu {

            z-index: 1070 !important;

        }


        /* =====================================================
           FOOTER
        ====================================================== */

        .footer {

            background: white;

            padding: 15px 25px;

            text-align: center;

            border-top:
                1px solid #e9ecef;

            margin-top: 30px;

        }


        /* =====================================================
           LOGOUT MODAL ANIMATION
        ====================================================== */

        #logoutModal .modal-content {

            animation:
                modalPopIn 0.3s ease;

        }


        @keyframes modalPopIn {

            0% {

                transform: scale(0.9);

                opacity: 0;

            }

            100% {

                transform: scale(1);

                opacity: 1;

            }

        }


        #logoutModal .btn-danger {

            transition: all 0.3s ease;

        }


        #logoutModal .btn-danger:hover {

            transform: translateY(-2px);

            box-shadow:
                0 5px 15px rgba(220, 53, 69, 0.3);

        }


        /* =====================================================
           RESPONSIVE
        ====================================================== */

        .mobile-menu-button-wrap {

            display: none;

            flex-shrink: 0;

            margin-right: 10px;

        }


        .mobile-menu-btn {

            width: 42px;

            height: 42px;

            border: 0;

            border-radius: 12px;

            background: #f3f6fb;

            color: var(--primary);

            display: inline-flex;

            align-items: center;

            justify-content: center;

            font-size: 18px;

            cursor: pointer;

            box-shadow:
                0 3px 12px rgba(0, 0, 0, .06);

        }


        .mobile-menu-btn:active {

            transform: scale(.96);

        }


        .sidebar-overlay {

            display: none;

            position: fixed;

            inset: 0;

            background:
                rgba(15, 23, 42, .48);

            backdrop-filter: blur(2px);

            -webkit-backdrop-filter: blur(2px);

            z-index: 999;

            opacity: 0;

            visibility: hidden;

            pointer-events: none;

            transition:
                opacity .25s ease,
                visibility .25s ease;

        }


        .sidebar-overlay.show {

            opacity: 1;

            visibility: visible;

            pointer-events: auto;

        }


        @media (max-width: 991.98px) {

            .mobile-menu-button-wrap {

                display: block;

            }


            .sidebar {

                width: 280px;

                max-width: 86vw;

                transform:
                    translateX(-105%);

                transition:
                    transform .28s ease;

                box-shadow:
                    8px 0 30px rgba(0, 0, 0, .18);

                z-index: 1100;

            }


            .sidebar.mobile-open {

                transform:
                    translateX(0);

            }


            .sidebar-overlay {

                display: block;

            }


            .sidebar>nav.nav {

                overflow-y: auto !important;

            }


            .main-content {

                margin-left: 0 !important;

                width: 100%;

            }


            .navbar-custom {

                min-height: 66px;

                padding: 10px 15px;

                position: sticky;

                top: 0;

                z-index: 900;

            }


            .navbar-custom h5 {

                font-size: 14px;

                line-height: 1.35;

            }


            .content-wrapper {

                padding: 18px;

            }

        }


        @media (max-width: 575.98px) {

            .sidebar {

                width:
                    min(290px, 88vw);

            }


            .navbar-custom {

                padding:
                    9px 12px;

            }


            .mobile-menu-btn {

                width: 40px;

                height: 40px;

            }


            .navbar-custom h5 {

                font-size: 12.5px;

            }


            .navbar-custom .text-end {

                display: none;

            }


            .user-avatar {

                width: 39px;

                height: 39px;

                font-size: 13px;

            }


            .content-wrapper {

                padding: 12px;

            }


            .table-wrapper {

                overflow-x: auto;

                -webkit-overflow-scrolling: touch;

            }


            .loader-ultimate .logo-wrapper {

                width: 78px;

                height: 78px;

            }


            .loader-ultimate .logo-circle {

                width: 78px;

                height: 78px;

            }


            .loader-ultimate .logo-circle img {

                width: 55px;

                height: 55px;

            }


            .loader-ultimate h3 {

                font-size: 1.15rem;

            }

        }


        @media (max-width: 360px) {

            .content-wrapper {

                padding: 10px;

            }


            .mobile-menu-btn,
            .user-avatar {

                width: 37px;

                height: 37px;

            }

        }


        @media (prefers-reduced-motion: reduce) {

            *,
            *::before {

                scroll-behavior: auto !important;

                animation-duration: .01ms !important;

                animation-iteration-count: 1 !important;

                transition-duration: .01ms !important;

            }

        }



        /* =====================================================
           FINAL SIDEBAR SCROLL RULE
           SEMUA ROLE
        ====================================================== */

        #appSidebar {

            height: 100vh !important;

            min-height: 100vh !important;

            max-height: 100vh !important;

            display: flex !important;

            flex-direction: column !important;

            overflow: hidden !important;

        }


        #appSidebar>.brand {

            flex: 0 0 auto !important;

        }


        #appSidebar>nav.nav {

            flex: 1 1 0% !important;

            min-height: 0 !important;

            height: auto !important;

            max-height: none !important;

            width: 100% !important;

            display: flex !important;

            flex-direction: column !important;

            flex-wrap: nowrap !important;

            align-items: stretch !important;

            align-content: stretch !important;

            overflow-y: auto !important;

            overflow-x: hidden !important;

            scrollbar-gutter: stable;

        }


        #appSidebar>nav.nav.flex-column {

            flex-direction: column !important;

            flex-wrap: nowrap !important;

            align-items: stretch !important;

            align-content: stretch !important;

        }


        #appSidebar>nav.nav>.nav-link {

            flex: 0 0 auto !important;

            width: 100% !important;

            max-width: 100% !important;

            min-width: 0 !important;

        }


        @media (max-width: 991.98px) {

            #appSidebar {

                height: 100vh !important;

                min-height: 100vh !important;

                max-height: 100vh !important;

            }


            #appSidebar>nav.nav {

                flex: 1 1 0% !important;

                min-height: 0 !important;

                height: auto !important;

                display: flex !important;

                flex-direction: column !important;

                flex-wrap: nowrap !important;

                align-items: stretch !important;

                overflow-y: auto !important;

                overflow-x: hidden !important;

            }

        }



        /* Never allow the page itself to create horizontal scrolling */
        html,
        body {

            max-width: 100%;

            overflow-x: hidden !important;

        }


        /* While mobile drawer is open, the page behind it must not scroll */
        body.sidebar-open {

            overflow: hidden !important;

            touch-action: none;

        }


        /* =====================================================
           ULTIMATE PRO MAX SIDEBAR
           Smooth / Premium / Responsive
           Tidak mengubah route, role, atau struktur menu
        ====================================================== */

        :root {
            --sidebar-width: 260px;
            --sidebar-blue-1: #17396f;
            --sidebar-blue-2: #24529a;
            --sidebar-blue-3: #2e5eaa;
            --sidebar-line: rgba(255, 255, 255, .09);
            --sidebar-text: rgba(255, 255, 255, .76);
            --sidebar-text-active: #ffffff;
            --sidebar-accent: #ffc107;
            --sidebar-ease: cubic-bezier(.22, 1, .36, 1);
        }

        /* Sidebar base */
        #appSidebar {
            width: var(--sidebar-width) !important;
            background:
                radial-gradient(circle at 15% 0%, rgba(255, 255, 255, .12), transparent 28%),
                radial-gradient(circle at 100% 30%, rgba(79, 140, 255, .12), transparent 32%),
                linear-gradient(145deg, var(--sidebar-blue-1) 0%, var(--sidebar-blue-2) 55%, #1d4788 100%) !important;
            border-right: 1px solid rgba(255, 255, 255, .08);
            box-shadow:
                10px 0 35px rgba(15, 39, 72, .16),
                inset -1px 0 0 rgba(255, 255, 255, .04);
            isolation: isolate;
        }

        #appSidebar::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: -1;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, .035), transparent 22%, rgba(0, 0, 0, .08) 100%);
        }

        /* Brand area */
        #appSidebar>.brand {
            position: relative;
            padding: 24px 18px 21px !important;
            border-bottom: 1px solid var(--sidebar-line) !important;
            background: linear-gradient(180deg,
                    rgba(255, 255, 255, .055),
                    rgba(255, 255, 255, .015));
        }

        #appSidebar>.brand::after {
            content: "";
            position: absolute;
            left: 24px;
            right: 24px;
            bottom: 0;
            height: 1px;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 193, 7, .42),
                    transparent);
        }

        #appSidebar .brand .logo-icon {
            position: relative;
            width: 78px;
            height: 78px;
            margin-bottom: 10px;
            transition:
                transform .55s var(--sidebar-ease),
                filter .55s var(--sidebar-ease);
        }

        #appSidebar .brand .logo-icon::before {
            content: "";
            position: absolute;
            inset: -9px;
            border-radius: 50%;
            background: radial-gradient(circle,
                    rgba(255, 255, 255, .12) 0%,
                    rgba(255, 255, 255, .04) 42%,
                    transparent 72%);
            opacity: .85;
            transition: transform .6s var(--sidebar-ease), opacity .4s ease;
        }

        #appSidebar .brand .logo-icon img {
            position: relative;
            z-index: 1;
            filter: drop-shadow(0 8px 13px rgba(0, 0, 0, .18));
            transition: transform .55s var(--sidebar-ease);
        }

        #appSidebar .brand:hover .logo-icon {
            transform: translateY(-2px);
            filter: drop-shadow(0 10px 18px rgba(0, 0, 0, .14));
        }

        #appSidebar .brand:hover .logo-icon::before {
            transform: scale(1.12);
            opacity: 1;
        }

        #appSidebar .brand:hover .logo-icon img {
            transform: scale(1.045);
        }

        #appSidebar .brand h4 {
            letter-spacing: 1.4px !important;
            font-size: 18px !important;
            text-shadow: 0 2px 12px rgba(0, 0, 0, .12);
        }

        #appSidebar .brand small {
            display: inline-block;
            margin-top: 3px;
            letter-spacing: .25px;
        }

        /* Navigation container */
        #appSidebar>nav.nav {
            padding: 12px 10px 24px !important;
            scrollbar-width: thin !important;
            scrollbar-color: rgba(255, 255, 255, .28) transparent !important;
            scroll-padding-top: 12px;
            scroll-padding-bottom: 24px;
        }

        #appSidebar>nav.nav::-webkit-scrollbar {
            width: 5px !important;
        }

        #appSidebar>nav.nav::-webkit-scrollbar-track {
            background: transparent !important;
        }

        #appSidebar>nav.nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .22) !important;
            border-radius: 999px !important;
            border: 1px solid transparent;
            background-clip: padding-box;
            transition: background .25s ease;
        }

        #appSidebar>nav.nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, .42) !important;
        }

        /* Premium menu item */
        #appSidebar>nav.nav>.nav-link {
            position: relative;
            isolation: isolate;
            min-height: 48px;
            margin: 3px 0 !important;
            padding: 11px 13px 11px 14px !important;
            border: 1px solid transparent;
            border-radius: 13px !important;
            color: var(--sidebar-text) !important;
            background: transparent;
            overflow: hidden;
            transform: translateZ(0);
            transition:
                color .28s var(--sidebar-ease),
                background .28s var(--sidebar-ease),
                border-color .28s var(--sidebar-ease),
                box-shadow .28s var(--sidebar-ease),
                transform .28s var(--sidebar-ease);
        }

        #appSidebar>nav.nav>.nav-link::before {
            content: "";
            position: absolute;
            left: 0;
            top: 8px;
            bottom: 8px;
            width: 3px;
            border-radius: 0 999px 999px 0;
            background: var(--sidebar-accent);
            transform: scaleY(0);
            transform-origin: center;
            opacity: 0;
            transition:
                transform .32s var(--sidebar-ease),
                opacity .25s ease;
        }

        #appSidebar>nav.nav>.nav-link::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            border-radius: inherit;
            background:
                linear-gradient(90deg,
                    rgba(255, 255, 255, .105),
                    rgba(255, 255, 255, .045));
            opacity: 0;
            transform: scale(.96);
            transition:
                opacity .28s var(--sidebar-ease),
                transform .28s var(--sidebar-ease);
        }

        /* Icon capsule */
        #appSidebar>nav.nav>.nav-link i {
            position: relative;
            width: 34px !important;
            height: 34px;
            margin-right: 11px !important;
            flex: 0 0 34px;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 14px !important;
            color: rgba(255, 255, 255, .72);
            background: rgba(255, 255, 255, .065);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .045);
            transition:
                color .28s var(--sidebar-ease),
                background .28s var(--sidebar-ease),
                transform .32s var(--sidebar-ease),
                box-shadow .28s ease;
        }

        #appSidebar>nav.nav>.nav-link span:not(.badge) {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Hover */
        #appSidebar>nav.nav>.nav-link:hover {
            color: #fff !important;
            border-color: rgba(255, 255, 255, .075);
            background: rgba(255, 255, 255, .045);
            transform: translateX(3px);
            box-shadow: 0 5px 18px rgba(0, 0, 0, .08);
        }

        #appSidebar>nav.nav>.nav-link:hover::after {
            opacity: 1;
            transform: scale(1);
        }

        #appSidebar>nav.nav>.nav-link:hover::before {
            opacity: .5;
            transform: scaleY(.55);
        }

        #appSidebar>nav.nav>.nav-link:hover i {
            color: #fff;
            background: rgba(255, 255, 255, .12);
            transform: scale(1.04);
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, .08),
                0 5px 12px rgba(0, 0, 0, .08);
        }

        /* Active */
        #appSidebar>nav.nav>.nav-link.active {
            color: var(--sidebar-text-active) !important;
            border-color: rgba(255, 255, 255, .095);
            background:
                linear-gradient(105deg,
                    rgba(255, 255, 255, .14),
                    rgba(255, 255, 255, .075)) !important;
            box-shadow:
                0 7px 20px rgba(5, 24, 52, .14),
                inset 0 1px 0 rgba(255, 255, 255, .06);
            transform: translateX(2px);
        }

        #appSidebar>nav.nav>.nav-link.active::before {
            opacity: 1;
            transform: scaleY(1);
        }

        #appSidebar>nav.nav>.nav-link.active::after {
            opacity: 1;
            transform: scale(1);
        }

        #appSidebar>nav.nav>.nav-link.active i {
            color: #fff;
            background:
                linear-gradient(135deg,
                    rgba(255, 193, 7, .98),
                    rgba(255, 215, 80, .72));
            box-shadow:
                0 5px 14px rgba(255, 193, 7, .20),
                inset 0 1px 0 rgba(255, 255, 255, .28);
            transform: scale(1.03);
        }

        /* Badge */
        #appSidebar>nav.nav>.nav-link .badge {
            margin-left: auto !important;
            min-width: 23px;
            height: 21px;
            padding: 2px 7px !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10px !important;
            font-weight: 800;
            border: 2px solid rgba(255, 255, 255, .10);
            box-shadow: 0 4px 10px rgba(0, 0, 0, .13);
            animation: sidebarBadgePulse 2.2s ease-in-out infinite;
        }

        @keyframes sidebarBadgePulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.055);
            }
        }

        /* Nice active reveal */
        @keyframes sidebarActiveReveal {
            from {
                opacity: 0;
                transform: translateX(-8px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        #appSidebar>nav.nav>.nav-link.active {
            animation: sidebarActiveReveal .42s var(--sidebar-ease) both;
        }

        /* Mobile drawer: smoother spring-like movement */
        @media (max-width: 991.98px) {
            #appSidebar {
                width: min(300px, 88vw) !important;
                transform: translateX(-105%) !important;
                transition:
                    transform .52s var(--sidebar-ease),
                    box-shadow .52s var(--sidebar-ease) !important;
                box-shadow:
                    14px 0 45px rgba(5, 24, 52, 0) !important;
                will-change: transform;
            }

            #appSidebar.mobile-open {
                transform: translateX(0) !important;
                box-shadow:
                    14px 0 45px rgba(5, 24, 52, .28) !important;
            }

            #appSidebar>.brand {
                padding-top: 22px !important;
            }

            #appSidebar>nav.nav {
                padding: 10px 10px 24px !important;
            }

            #appSidebar>nav.nav>.nav-link {
                min-height: 50px;
                border-radius: 14px !important;
            }

            #appSidebar>nav.nav>.nav-link:hover {
                transform: translateX(2px);
            }

            .sidebar-overlay {
                background: rgba(5, 18, 38, .52) !important;
                backdrop-filter: blur(5px) saturate(115%) !important;
                -webkit-backdrop-filter: blur(5px) saturate(115%) !important;
                transition:
                    opacity .42s var(--sidebar-ease),
                    visibility .42s var(--sidebar-ease) !important;
            }

            .mobile-menu-btn {
                transition:
                    transform .28s var(--sidebar-ease),
                    box-shadow .28s ease,
                    background .28s ease !important;
            }

            .mobile-menu-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 7px 18px rgba(30, 60, 114, .12) !important;
            }

            .mobile-menu-btn:active {
                transform: scale(.92) !important;
            }
        }

        /* Very small screens */
        @media (max-width: 575.98px) {
            #appSidebar {
                width: min(300px, 90vw) !important;
            }

            #appSidebar .brand .logo-icon {
                width: 72px;
                height: 72px;
            }
        }

        /* Accessibility: don't force animations for reduced-motion users */
        @media (prefers-reduced-motion: reduce) {

            #appSidebar,
            #appSidebar *,
            #appSidebar *::before,
            #appSidebar *::after {
                animation: none !important;
                transition-duration: .01ms !important;
            }
        }

        /* =========================================================
           REKANAN - NOTIFIKASI PROYEK TERBARU
           ========================================================= */
        #appSidebar > nav.nav > .nav-link .project-notification {
            position: relative;
            width: 31px;
            height: 31px;
            margin-left: auto;
            flex: 0 0 31px;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, .075);
            border: 1px solid rgba(255, 255, 255, .08);
            color: #ffc107;
            overflow: visible !important;
            transition: transform .3s ease, background .3s ease, box-shadow .3s ease;
        }

        #appSidebar > nav.nav > .nav-link .project-notification > i {
            width: auto !important;
            height: auto !important;
            margin: 0 !important;
            padding: 0 !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            background: transparent !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            color: #ffc107 !important;
            font-size: 12px !important;
            transform: none !important;
            animation: projectBellShake 3.2s ease-in-out infinite;
        }

        #appSidebar > nav.nav > .nav-link .project-notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            min-width: 16px;
            height: 16px;
            padding: 0 4px;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            background: #ef4444;
            color: #fff !important;
            border: 2px solid #24529a;
            border-radius: 999px;
            font-size: 8px !important;
            font-weight: 800;
            line-height: 1;
            white-space: nowrap;
            box-shadow: 0 3px 8px rgba(0, 0, 0, .18);
            animation: projectNotificationCount 2.2s ease-in-out infinite;
        }

        #appSidebar > nav.nav > .nav-link:hover .project-notification {
            background: rgba(255, 255, 255, .14);
            transform: translateY(-1px);
            box-shadow: 0 5px 14px rgba(0, 0, 0, .10);
        }

        #appSidebar > nav.nav > .nav-link.active .project-notification {
            background: rgba(255, 193, 7, .16);
            border-color: rgba(255, 193, 7, .22);
            box-shadow: 0 4px 12px rgba(255, 193, 7, .12);
        }

        @keyframes projectBellShake {
            0%, 78%, 100% { transform: rotate(0deg); }
            82% { transform: rotate(12deg); }
            85% { transform: rotate(-12deg); }
            88% { transform: rotate(8deg); }
            91% { transform: rotate(-8deg); }
            94% { transform: rotate(0deg); }
        }

        @keyframes projectNotificationCount {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.10); }
        }

        @media (max-width: 991.98px) {
            #appSidebar > nav.nav > .nav-link .project-notification {
                width: 30px;
                height: 30px;
                flex-basis: 30px;
            }
        }

        /* =====================================================
   CUSTOM PAGINATION
====================================================== */

.custom-pagination {
    gap: 4px;
}

.custom-pagination .page-item .page-link {
    border: 1px solid #e5e7eb;
    border-radius: 8px !important;
    padding: 6px 12px;
    font-size: 13px;
    font-weight: 500;
    color: #1e3c72;
    background: #ffffff;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    margin: 0 2px;
}

.custom-pagination .page-item .page-link:hover {
    background: #f0f4ff;
    border-color: #1e3c72;
    color: #1e3c72;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(30, 60, 114, 0.1);
}

.custom-pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    border-color: #1e3c72;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(30, 60, 114, 0.25);
    transform: none;
}

.custom-pagination .page-item.disabled .page-link {
    background: #f8f9fa;
    color: #adb5bd;
    border-color: #e5e7eb;
    cursor: not-allowed;
    opacity: 0.6;
}

.custom-pagination .page-item.disabled .page-link:hover {
    transform: none;
    box-shadow: none;
    background: #f8f9fa;
    color: #adb5bd;
    border-color: #e5e7eb;
}

/* Responsive: hide text on small screens */
@media (max-width: 575.98px) {
    .custom-pagination .page-link {
        padding: 6px 10px;
        min-width: 32px;
        height: 32px;
        font-size: 12px;
    }
}

    </style>

    @stack('styles')

</head>


<body>


    <!-- =====================================================
         LOADING SCREEN
    ====================================================== -->

    <div class="loading-screen" id="loadingScreen">

        <div class="loader-ultimate">

            <div class="logo-wrapper">

                <div class="logo-circle">

                    <img src="{{ asset('images/logo-kab-karo.png') }}" alt="Logo Kabupaten Karo">

                </div>

            </div>


            <h3>SISMOLEV</h3>


            <p>
                Sistem Informasi Monitoring & Evaluasi<br>
                Pembangunan Desa Kutabuluh
            </p>


            <div class="progress-container">

                <div class="progress-fill"></div>

            </div>


            <div class="loading-text">

                <span>Memuat dashboard</span>

                <span class="loading-dots"></span>

            </div>

        </div>

    </div>



    <!-- MOBILE SIDEBAR OVERLAY -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <div class="sidebar" id="appSidebar">


        <!-- =================================================
             BRAND
        ================================================== -->

        <div class="brand">

            <div class="logo-icon">

                <img src="{{ asset('images/logo-kab-karo.png') }}" alt="Logo Kabupaten Karo">

            </div>


            <h4>SISMOLEV</h4>


            <small>Desa Kutabuluh</small>

        </div>



        @php

            $level = Auth::user()->level;

        @endphp



        <!-- =================================================
             NAVIGATION - SCROLLABLE
        ================================================== -->

        <nav class="nav flex-column">


            <!-- =================================================
                 DASHBOARD
            ================================================== -->

            <a class="nav-link
                    {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                href="{{ route('dashboard') }}">

                <i class="fas fa-tachometer-alt"></i>

                <span>Dashboard</span>

            </a>



            <!-- =================================================
                 ADMIN
            ================================================== -->

            @if ($level == 'admin')


                <a class="nav-link
                        {{ request()->routeIs('proyek.*') ? 'active' : '' }}"
                    href="{{ route('proyek.index') }}">

                    <i class="fas fa-building"></i>

                    <span>Kelola Proyek</span>

                </a>


                <a class="nav-link
                        {{ request()->routeIs('admin.laporan-tahunan') ? 'active' : '' }}"
                    href="{{ route('admin.laporan-tahunan') }}">

                    <i class="fas fa-calendar-alt"></i>

                    <span>Laporan Tahunan</span>

                </a>


                <a class="nav-link
                        {{ request()->routeIs('admin.laporan-proyek', 'admin.laporan-progres', 'admin.cetak-laporan-proyek')
                            ? 'active'
                            : '' }}"
                    href="{{ route('admin.laporan-proyek') }}">

                    <i class="fas fa-file-alt"></i>

                    <span>Laporan Per Proyek</span>

                </a>


                <a class="nav-link
                        {{ request()->routeIs('admin.monitoring') ? 'active' : '' }}"
                    href="{{ route('admin.monitoring') }}">

                    <i class="fas fa-chart-line"></i>

                    <span>Monitoring</span>

                </a>


                <!-- VERIFIKASI MONITORING -->

                <a class="nav-link
                        {{ request()->routeIs('admin.monitoring.verifikasi') ? 'active' : '' }}"
                    href="{{ route('admin.monitoring.verifikasi') }}">

                    <i class="fas fa-shield-alt"></i>

                    <span>Verifikasi Progres</span>


                    @php

                        $pendingVerifikasi = App\Models\Monitoring::pending()->count();

                    @endphp


                    @if ($pendingVerifikasi > 0)
                        <span class="badge bg-danger ms-2 rounded-pill">

                            {{ $pendingVerifikasi }}

                        </span>
                    @endif

                </a>


                <a class="nav-link
                        {{ request()->routeIs('evaluasi.*') ? 'active' : '' }}"
                    href="{{ route('evaluasi.index') }}">

                    <i class="fas fa-check-circle"></i>

                    <span>Evaluasi</span>

                </a>


                {{-- <a class="nav-link
                        {{ request()->routeIs('dokumentasi.*') ? 'active' : '' }}"
                    href="{{ route('dokumentasi.index') }}">

                    <i class="fas fa-camera"></i>

                    <span>Dokumentasi</span>

                </a> --}}


                <a class="nav-link
                        {{ request()->routeIs('admin.komentar-masyarakat') ? 'active' : '' }}"
                    href="{{ route('admin.komentar-masyarakat') }}">

                    <i class="fas fa-comments"></i>

                    <span>Komentar Masyarakat</span>


                    @php

                        $pendingCount = App\Models\KomentarMasyarakat::pending()->count();

                    @endphp


                    @if ($pendingCount > 0)
                        <span class="badge bg-danger ms-2 rounded-pill">

                            {{ $pendingCount }}

                        </span>
                    @endif

                </a>


                {{-- EVALUASI REKANAN --}}
                <a class="nav-link {{ request()->routeIs('admin.evaluasi-rekanan', 'admin.evaluasi-rekanan.*', 'admin.kredibilitas-rekanan') ? 'active' : '' }}"
                    href="{{ route('admin.evaluasi-rekanan') }}">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Evaluasi Rekanan</span>
                    @php
                        $pendingEvaluasi = 0;
                        try {
                            if (class_exists(\App\Models\EvaluasiRekanan::class)) {
                                $pendingEvaluasi = \App\Models\EvaluasiRekanan::pending()->count();
                            }
                        } catch (\Throwable $e) { $pendingEvaluasi = 0; }
                    @endphp
                    @if ($pendingEvaluasi > 0)
                        <span class="badge bg-danger ms-2 rounded-pill">{{ $pendingEvaluasi > 99 ? '99+' : $pendingEvaluasi }}</span>
                    @endif
                </a>

                <a class="nav-link {{ request()->routeIs('admin.kredibilitas-rekanan') ? 'active' : '' }}"
                    href="{{ route('admin.kredibilitas-rekanan') }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Kredibilitas Rekanan</span>
                </a>

                <a class="nav-link
                        {{ request()->routeIs('users.*') ? 'active' : '' }}"
                    href="{{ route('users.index') }}">

                    <i class="fas fa-users"></i>

                    <span>Kelola User</span>

                </a>


            @endif



            <!-- =================================================
                 REKANAN
            ================================================== -->

            @if ($level == 'rekanan')


                <a class="nav-link
                        {{ request()->routeIs('rekanan.available') ? 'active' : '' }}"
                    href="{{ route('rekanan.available') }}">

                    <i class="fas fa-folder-open"></i>

                    <span class="menu-text">Proyek Tersedia</span>

                    @php
                        $lastSeenProjectTime = session(
                            'rekanan_last_seen_project_time',
                            now()->subYears(10)
                        );

                        $proyekBaruCount = \App\Models\Proyek::where('is_published', true)
                            ->where('status_penawaran', 'open')
                            ->where('created_at', '>', $lastSeenProjectTime)
                            ->count();
                    @endphp

                    @if ($proyekBaruCount > 0)
                        <span
                            class="project-notification"
                            title="{{ $proyekBaruCount }} proyek baru tersedia"
                            aria-label="{{ $proyekBaruCount }} proyek baru">
                            <i class="fas fa-bell"></i>
                            <span class="project-notification-count">
                                {{ $proyekBaruCount > 99 ? '99+' : $proyekBaruCount }}
                            </span>
                        </span>
                    @endif

                </a>


                <a class="nav-link
                        {{ request()->routeIs('rekanan.my-projects') ? 'active' : '' }}"
                    href="{{ route('rekanan.my-projects') }}">

                    <i class="fas fa-check-circle"></i>

                    <span>Proyek Saya</span>

                </a>


                <a class="nav-link
                        {{ request()->routeIs('monitoring.*') ? 'active' : '' }}"
                    href="{{ route('monitoring.index') }}">

                    <i class="fas fa-chart-line"></i>

                    <span>Monitoring</span>

                </a>


                {{-- EVALUASI PROYEK --}}
                <a class="nav-link {{ request()->routeIs('evaluasi-rekanan.*') ? 'active' : '' }}"
                    href="{{ route('evaluasi-rekanan.index') }}">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Evaluasi Proyek</span>
                </a>


            @endif



            <!-- =================================================
                 KEPALA DESA
            ================================================== -->

            @if ($level == 'kepala_desa')
                <a class="nav-link
                        {{ request()->routeIs('kades.proyek') ? 'active' : '' }}"
                    href="{{ route('kades.proyek') }}">

                    <i class="fas fa-building"></i>

                    <span>Semua Proyek</span>

                </a>


                <a class="nav-link
                        {{ request()->routeIs('kades.rekanan-status') ? 'active' : '' }}"
                    href="{{ route('kades.rekanan-status') }}">

                    <i class="fas fa-user-check"></i>

                    <span>Status Rekanan</span>

                </a>


                <a class="nav-link
                        {{ request()->routeIs('kades.laporan-proyek') ? 'active' : '' }}"
                    href="{{ route('kades.laporan-proyek') }}">

                    <i class="fas fa-file-alt"></i>

                    <span>Laporan Proyek</span>

                </a>


                                {{-- EVALUASI REKANAN --}}
                <a class="nav-link {{ request()->routeIs('admin.evaluasi-rekanan', 'admin.evaluasi-rekanan.*') ? 'active' : '' }}"
                    href="{{ route('admin.evaluasi-rekanan') }}">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Evaluasi Rekanan</span>
                </a>

                <a class="nav-link {{ request()->routeIs('admin.kredibilitas-rekanan') ? 'active' : '' }}"
                    href="{{ route('admin.kredibilitas-rekanan') }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Kredibilitas Rekanan</span>
                </a>

<a class="nav-link
                        {{ request()->routeIs('kades.laporan-tahunan') ? 'active' : '' }}"
                    href="{{ route('kades.laporan-tahunan') }}">

                    <i class="fas fa-calendar-alt"></i>

                    <span>Laporan Tahunan</span>

                </a>
            @endif


        </nav>

    </div>



    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->

    <div class="main-content">


        <!-- =================================================
             NAVBAR
        ================================================== -->

        <nav
            class="navbar-custom
                d-flex
                justify-content-between
                align-items-center">

            <div class="mobile-menu-button-wrap">
                <button type="button" class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Buka menu"
                    aria-controls="appSidebar" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div>

                <h5 class="mb-0">

                    <i
                        class="fas fa-chart-line
                            me-2
                            text-primary">
                    </i>

                    Sistem Informasi Monitoring dan Evaluasi
                    Pembangunan

                </h5>


                <small class="text-muted">

                    Desa Kutabuluh

                </small>

            </div>



            <div class="d-flex
                    align-items-center
                    gap-3">


                <div class="text-end">

                    <div class="fw-bold">

                        {{ Auth::user()->nama_user }}

                    </div>


                    <small class="text-muted">


                        @if ($level == 'admin')
                            <i
                                class="fas fa-shield-alt
                                    me-1
                                    text-danger">
                            </i>

                            Administrator
                        @elseif($level == 'rekanan')
                            <i
                                class="fas fa-user-check
                                    me-1
                                    text-primary">
                            </i>

                            Rekanan
                        @elseif($level == 'kepala_desa')
                            <i
                                class="fas fa-user-tie
                                    me-1
                                    text-success">
                            </i>

                            Kepala Desa
                        @endif


                    </small>

                </div>



                <!-- =================================================
                     NOTIFICATION - REKANAN
                ================================================== -->

                @if ($level == 'rekanan')


                    @php

                        $headerNotifications = \App\Models\Notification::where('user_id', Auth::id())
                            ->latest()
                            ->take(10)
                            ->get();

                        $unreadNotificationCount = \App\Models\Notification::where('user_id', Auth::id())
                            ->where('is_read', false)
                            ->count();

                    @endphp


                    <div class="dropdown">


                        {{-- <button type="button"
                            class="btn btn-light
                                position-relative
                                rounded-circle
                                d-flex
                                align-items-center
                                justify-content-center"
                            style="
                                width:42px;
                                height:42px;
                            "
                            data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">


                            <i class="fas fa-bell
                                    text-primary">
                            </i>


                            @if ($unreadNotificationCount > 0)
                                <span
                                    class="position-absolute
                                        top-0
                                        start-100
                                        translate-middle
                                        badge
                                        rounded-pill
                                        bg-danger"
                                    style="
                                        font-size:10px;
                                    ">

                                    {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}

                                </span>
                            @endif


                        </button> --}}



                        <div class="dropdown-menu
                                dropdown-menu-end
                                shadow
                                border-0
                                p-0"
                            style="
                                width:360px;
                                max-width:calc(100vw - 30px);
                                border-radius:14px;
                                overflow:hidden;
                            ">


                            <div
                                class="
                                    d-flex
                                    justify-content-between
                                    align-items-center
                                    px-3
                                    py-3
                                    border-bottom">


                                <div>

                                    <div class="fw-bold text-dark">

                                        <i
                                            class="fas fa-bell
                                                text-primary
                                                me-2">
                                        </i>

                                        Notifikasi

                                    </div>


                                    <small class="text-muted">

                                        {{ $unreadNotificationCount }}
                                        belum dibaca

                                    </small>

                                </div>


                                @if ($unreadNotificationCount > 0)
                                    <form action="{{ route('notifications.read-all') }}" method="POST">

                                        @csrf

                                        <button type="submit"
                                            class="btn btn-sm
                                                btn-link
                                                text-decoration-none
                                                p-0">

                                            Tandai semua dibaca

                                        </button>

                                    </form>
                                @endif


                            </div>



                            <div
                                style="
                                    max-height:380px;
                                    overflow-y:auto;
                                ">


                                @forelse ($headerNotifications
                                    as $notification)
                                    <form action="{{ route('notifications.read', $notification->id) }}"
                                        method="POST" class="m-0">

                                        @csrf


                                        <button type="submit"
                                            class="
                                                w-100
                                                text-start
                                                border-0
                                                px-3
                                                py-3
                                                {{ !$notification->is_read ? 'bg-light' : 'bg-white' }}"
                                            style="
                                                transition:
                                                    background .2s;
                                            ">


                                            <div
                                                class="
                                                    d-flex
                                                    gap-3">


                                                <div class="flex-shrink-0">

                                                    <div class="
                                                            rounded-circle
                                                            d-flex
                                                            align-items-center
                                                            justify-content-center
                                                            {{ !$notification->is_read ? 'bg-primary text-white' : 'bg-light text-primary' }}"
                                                        style="
                                                            width:40px;
                                                            height:40px;
                                                        ">

                                                        <i class="fas fa-building">
                                                        </i>

                                                    </div>

                                                </div>



                                                <div
                                                    class="
                                                        flex-grow-1">


                                                    <div
                                                        class="
                                                            d-flex
                                                            justify-content-between
                                                            gap-2">


                                                        <strong
                                                            class="
                                                                small
                                                                text-dark">

                                                            {{ $notification->title }}

                                                        </strong>


                                                        @if (!$notification->is_read)
                                                            <span
                                                                class="
                                                                    badge
                                                                    bg-danger
                                                                    rounded-pill"
                                                                style="
                                                                    font-size:9px;
                                                                ">

                                                                Baru

                                                            </span>
                                                        @endif


                                                    </div>


                                                    <div
                                                        class="
                                                            small
                                                            text-muted
                                                            mt-1">

                                                        {{ $notification->message }}

                                                    </div>


                                                    <div
                                                        class="
                                                            small
                                                            text-secondary
                                                            mt-1">

                                                        <i
                                                            class="
                                                                far
                                                                fa-clock
                                                                me-1">
                                                        </i>

                                                        {{ $notification->created_at->diffForHumans() }}

                                                    </div>


                                                </div>


                                            </div>


                                        </button>


                                    </form>


                                @empty


                                    <div
                                        class="
                                            text-center
                                            py-5
                                            px-3">

                                        <i
                                            class="
                                                far
                                                fa-bell-slash
                                                fa-2x
                                                text-muted
                                                mb-3">
                                        </i>


                                        <div
                                            class="
                                                fw-semibold
                                                text-muted">

                                            Belum ada notifikasi

                                        </div>


                                        <small class="text-muted">

                                            Notifikasi proyek baru
                                            akan muncul di sini.

                                        </small>

                                    </div>
                                @endforelse


                            </div>



                            <div
                                class="
                                    border-top
                                    text-center
                                    py-2">

                                <a href="{{ route('rekanan.available') }}"
                                    class="
                                        small
                                        text-decoration-none">

                                    <i
                                        class="
                                            fas
                                            fa-folder-open
                                            me-1">
                                    </i>

                                    Lihat Proyek Tersedia

                                </a>

                            </div>


                        </div>


                    </div>


                @endif



                <!-- =================================================
                     USER DROPDOWN
                ================================================== -->

                <div class="
                        user-dropdown
                        dropdown">


                    <div class="user-avatar" data-bs-toggle="dropdown" aria-expanded="false">

                        {{ strtoupper(substr(Auth::user()->nama_user, 0, 2)) }}

                    </div>


                    <ul
                        class="
                            dropdown-menu
                            dropdown-menu-user
                            dropdown-menu-end">


                        <li>

                            <a class="dropdown-item" href="{{ route('profile.index') ?? '#' }}">

                                <i
                                    class="
                                        fas
                                        fa-user-circle
                                        text-primary">
                                </i>

                                Profil Saya

                            </a>

                        </li>


                        <li>

                            <hr class="dropdown-divider">

                        </li>


                        <li>

                            <a class="
                                    dropdown-item
                                    text-danger"
                                href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">

                                <i
                                    class="
                                        fas
                                        fa-sign-out-alt">
                                </i>

                                Logout

                            </a>

                        </li>


                    </ul>


                </div>


            </div>


        </nav>



        <!-- =================================================
             CONTENT WRAPPER
        ================================================== -->

        <div class="content-wrapper">


            @if (session('success'))
                <div class="
                        alert
                        alert-success
                        alert-dismissible
                        fade
                        show"
                    role="alert">

                    <i
                        class="
                            fas
                            fa-check-circle
                            me-2">
                    </i>

                    {{ session('success') }}


                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>
            @endif



            @if (session('error'))
                <div class="
                        alert
                        alert-danger
                        alert-dismissible
                        fade
                        show"
                    role="alert">

                    <i
                        class="
                            fas
                            fa-exclamation-circle
                            me-2">
                    </i>

                    {{ session('error') }}


                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>
            @endif



            @yield('content')


        </div>



        <!-- =================================================
             FOOTER
        ================================================== -->

        <div class="footer">

            <small>

                <i
                    class="
                        fas
                        fa-copyright
                        me-1">
                </i>

                {{ date('Y') }}

                Sistem Informasi Monitoring dan Evaluasi
                Pembangunan Desa Kutabuluh.

                All rights reserved.

            </small>

        </div>


    </div>



    <!-- =====================================================
         LOGOUT MODAL
    ====================================================== -->

    <div class="modal fade" id="logoutModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">


        <div class="
                modal-dialog
                modal-dialog-centered">


            <div
                class="
                    modal-content
                    border-0
                    rounded-4
                    shadow-lg">


                <div
                    class="
                        modal-header
                        border-0
                        pb-0
                        pt-4
                        px-4">


                    <h5 class="
                            modal-title
                            fw-bold">

                        <i
                            class="
                                fas
                                fa-sign-out-alt
                                text-danger
                                me-2">
                        </i>

                        Konfirmasi Logout

                    </h5>


                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>


                </div>



                <div
                    class="
                        modal-body
                        text-center
                        px-4
                        py-4">


                    <div class="mb-4">


                        <div class="
                                mx-auto
                                d-flex
                                align-items-center
                                justify-content-center"
                            style="
                                width:85px;
                                height:85px;
                                background:
                                    rgba(239,68,68,0.1);
                                border-radius:50%;
                            ">

                            <i
                                class="
                                    fas
                                    fa-sign-out-alt
                                    fa-3x
                                    text-danger">
                            </i>

                        </div>


                    </div>


                    <h5 class="
                            fw-bold
                            mb-3">

                        Yakin ingin keluar dari sistem?

                    </h5>


                    <p class="
                            text-muted
                            mb-0">

                        Session Anda akan berakhir
                        dan Anda perlu login kembali
                        <br>
                        untuk mengakses sistem.

                    </p>


                </div>



                <div
                    class="
                        modal-footer
                        border-0
                        justify-content-center
                        gap-3
                        pb-4
                        pt-0
                        px-4">


                    <button type="button"
                        class="
                            btn
                            btn-secondary
                            px-4
                            py-2
                            rounded-3"
                        data-bs-dismiss="modal">

                        <i
                            class="
                                fas
                                fa-times
                                me-2">
                        </i>

                        Batal

                    </button>



                    <form action="{{ route('logout') }}" method="POST" id="logoutForm">

                        @csrf


                        <button type="submit"
                            class="
                                btn
                                btn-danger
                                px-4
                                py-2
                                rounded-3">

                            <i
                                class="
                                    fas
                                    fa-sign-out-alt
                                    me-2">
                            </i>

                            Ya, Logout

                        </button>


                    </form>


                </div>


            </div>


        </div>


    </div>



    <!-- =====================================================
         JQUERY
    ====================================================== -->

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>



    <!-- =====================================================
         BOOTSTRAP JS
    ====================================================== -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



    <!-- =====================================================
         DATATABLES
    ====================================================== -->

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>



    <!-- =====================================================
         CHART JS
    ====================================================== -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



    <!-- =====================================================
         SWEET ALERT
    ====================================================== -->

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <!-- =====================================================
         SUMMERNOTE
    ====================================================== -->

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-id-ID.min.js"></script>



    <!-- =====================================================
         JAVASCRIPT
    ====================================================== -->

    <script>
        /* =====================================================
                   SMOOTH ULTIMATE PRO MAX PAGE LOADING
                   Intelligent / Fast / No Flash / Smooth Navigation
                ====================================================== */

        (function() {
            const loadingScreen = document.getElementById('loadingScreen');
            const body = document.body;

            if (!loadingScreen) return;

            let loaderTimer = null;
            let navigationStarted = false;

            function hideLoader(delay = 180) {
                if (loaderTimer) {
                    clearTimeout(loaderTimer);
                }

                loaderTimer = setTimeout(function() {
                    loadingScreen.classList.remove('is-navigation');
                    loadingScreen.classList.add('hide');
                    body.classList.remove('is-loading');
                    navigationStarted = false;
                }, Math.max(0, delay));
            }

            function showLoader() {
                if (loaderTimer) {
                    clearTimeout(loaderTimer);
                    loaderTimer = null;
                }

                loadingScreen.classList.remove('hide');
                loadingScreen.classList.add('is-navigation');
                body.classList.add('is-loading');
                navigationStarted = true;
            }

            /*
             * First render:
             * jangan ditahan terlalu lama. Loader hanya memberi
             * smooth transition sambil browser menyelesaikan DOM.
             */
            function finishInitialLoad() {
                requestAnimationFrame(function() {
                    requestAnimationFrame(function() {
                        hideLoader(220);
                    });
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener(
                    'DOMContentLoaded',
                    finishInitialLoad, {
                        once: true
                    }
                );
            } else {
                finishInitialLoad();
            }

            /*
             * Back/Forward Cache:
             * tampilkan kembali secara halus tanpa flash.
             */
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    showLoader();
                    hideLoader(140);
                }
            });

            /*
             * Navigation:
             * hanya untuk link internal yang benar-benar berpindah halaman.
             */
            document.addEventListener('click', function(event) {
                const link = event.target.closest('a[href]');
                if (!link) return;

                const href = link.getAttribute('href') || '';
                const target = link.getAttribute('target');

                if (
                    link.hasAttribute('download') ||
                    target === '_blank' ||
                    href === '#' ||
                    href.startsWith('#') ||
                    href.startsWith('javascript:') ||
                    link.hasAttribute('data-bs-toggle') ||
                    link.hasAttribute('data-bs-target') ||
                    link.closest('.dropdown-menu') ||
                    link.closest('[data-no-page-loader]')
                ) {
                    return;
                }

                try {
                    const url = new URL(link.href, window.location.href);

                    if (url.origin !== window.location.origin) return;
                    if (url.href === window.location.href) return;

                    showLoader();
                } catch (e) {
                    // Jangan ganggu navigasi kalau URL gagal diproses.
                }
            });

            /*
             * Submit:
             * jangan aktifkan loader untuk form yang sengaja memakai
             * target baru atau form yang ditandai no-loader.
             */
            document.addEventListener('submit', function(event) {
                const form = event.target;

                if (!form) return;
                if (form.id === 'logoutForm') return;
                if (form.target === '_blank') return;
                if (form.hasAttribute('data-no-page-loader')) return;

                showLoader();
            });

            /*
             * Expose API agar halaman lain bisa memanggilnya.
             */
            window.showPageLoader = showLoader;
            window.hidePageLoader = hideLoader;
        })();

        /* =====================================================
           MOBILE SIDEBAR
        ====================================================== */

        (function() {
            const sidebar = document.getElementById('appSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const menuBtn = document.getElementById('mobileMenuBtn');

            if (!sidebar || !overlay || !menuBtn) return;

            function isMobile() {
                return window.matchMedia('(max-width: 991.98px)').matches;
            }

            function setMenu(open) {
                if (!isMobile()) {
                    sidebar.classList.remove('mobile-open');
                    overlay.classList.remove('show');
                    document.body.classList.remove('sidebar-open');
                    menuBtn.setAttribute('aria-expanded', 'false');
                    return;
                }

                sidebar.classList.toggle('mobile-open', open);
                document.body.classList.toggle('sidebar-open', open);
                overlay.classList.toggle('show', open);
                menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');

                const icon = menuBtn.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-bars', !open);
                    icon.classList.toggle('fa-times', open);
                }
            }

            menuBtn.addEventListener('click', function() {
                setMenu(!sidebar.classList.contains('mobile-open'));
            });

            overlay.addEventListener('click', function() {
                setMenu(false);
            });

            sidebar.querySelectorAll('a.nav-link').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (isMobile()) setMenu(false);
                });
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') setMenu(false);
            });

            window.addEventListener('resize', function() {
                if (!isMobile()) setMenu(false);
            });
        })();







        /* =====================================================
           SIDEBAR PREMIUM UX
           Pastikan menu aktif tetap terlihat
        ====================================================== */

        (function() {
            const sidebar = document.getElementById('appSidebar');
            const sidebarNav = sidebar?.querySelector(':scope > nav.nav');

            if (!sidebar || !sidebarNav) return;

            function revealActiveMenu() {
                const active = sidebarNav.querySelector('.nav-link.active');
                if (!active) return;

                requestAnimationFrame(function() {
                    const navRect = sidebarNav.getBoundingClientRect();
                    const itemRect = active.getBoundingClientRect();

                    if (
                        itemRect.top < navRect.top ||
                        itemRect.bottom > navRect.bottom
                    ) {
                        active.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest',
                            inline: 'nearest'
                        });
                    }
                });
            }

            window.addEventListener('pageshow', revealActiveMenu);

            if (document.readyState === 'loading') {
                document.addEventListener(
                    'DOMContentLoaded',
                    revealActiveMenu, {
                        once: true
                    }
                );
            } else {
                revealActiveMenu();
            }
        })();

        /* =====================================================
           AUTO CLOSE ALERT
        ====================================================== */

        setTimeout(
            function() {

                $('.alert').fadeOut('slow');

            },
            5000
        );



        /* =====================================================
           LOGOUT
        ====================================================== */

        document
            .getElementById('logoutForm')
            ?.addEventListener(
                'submit',
                function(e) {

                    // Modal sudah memberikan konfirmasi.

                }
            );



        /* =====================================================
           TOOLTIP
        ====================================================== */

        var tooltipTriggerList = [].slice.call(
            document.querySelectorAll(
                '[data-bs-toggle="tooltip"]'
            )
        );


        tooltipTriggerList.map(
            function(tooltipTriggerEl) {

                return new bootstrap.Tooltip(
                    tooltipTriggerEl
                );

            }
        );



        /* =====================================================
           SIDEBAR SCROLL POSITION
           
           Saat halaman berpindah, posisi scroll sidebar
           tetap disimpan.
        ====================================================== */

        const sidebarNav =
            document.querySelector(
                '.sidebar nav'
            );


        if (sidebarNav) {


            const savedScroll =
                sessionStorage.getItem(
                    'sidebarScrollPosition'
                );


            if (savedScroll !== null) {
                requestAnimationFrame(function() {
                    sidebarNav.scrollTop = parseInt(savedScroll, 10) || 0;
                });
            }


            sidebarNav.addEventListener(
                'scroll',
                function() {

                    sessionStorage.setItem(
                        'sidebarScrollPosition',
                        sidebarNav.scrollTop
                    );

                }
            );

        }


        /* =====================================================
           SIDEBAR SCROLL POSITION
           SEMUA ROLE
        ====================================================== */

        (function() {

            const sidebarNav =
                document.querySelector(
                    '#appSidebar > nav.nav'
                );

            if (!sidebarNav) return;


            const savedScroll =
                sessionStorage.getItem(
                    'sidebarScrollPosition'
                );


            if (savedScroll !== null) {

                requestAnimationFrame(function() {

                    sidebarNav.scrollTop =
                        parseInt(
                            savedScroll,
                            10
                        ) || 0;

                });

            }


            sidebarNav.addEventListener(
                'scroll',
                function() {

                    sessionStorage.setItem(
                        'sidebarScrollPosition',
                        String(
                            sidebarNav.scrollTop
                        )
                    );

                }, {
                    passive: true
                }
            );

        })();
    </script>



    @stack('scripts')


</body>

</html>