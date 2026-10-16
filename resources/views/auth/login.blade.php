<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Login - Sistem Informasi Monitoring dan Evaluasi Pembangunan Desa Kutabuluh
    </title>

    <!-- =====================================================
         FAVICON / LOGO
    ====================================================== -->
    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="{{ asset('images/logo-kab-karo.png') }}"
    >

    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="{{ asset('images/logo-kab-karo.png') }}"
    >

    <link
        rel="shortcut icon"
        href="{{ asset('images/logo-kab-karo.png') }}"
        type="image/x-icon"
    >


    <!-- =====================================================
         BOOTSTRAP
    ====================================================== -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- =====================================================
         FONT AWESOME
    ====================================================== -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >


    <!-- =====================================================
         GOOGLE FONT
    ====================================================== -->
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <style>

        /* =====================================================
           RESET
        ====================================================== */

        * {
            box-sizing: border-box;
        }


        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }


        body {

            font-family: 'Poppins', sans-serif;

            background: #f4f6f9;

            min-height: 100vh;

            overflow-x: hidden;
        }


        /* =====================================================
           LOGIN WRAPPER
        ====================================================== */

        .login-wrapper {

            width: 100%;

            min-height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 30px;
        }


        /* =====================================================
           LOGIN CARD
        ====================================================== */

        .login-card {

            width: 100%;

            max-width: 1080px;

            min-height: 620px;

            display: flex;

            overflow: hidden;

            background: #ffffff;

            border-radius: 24px;

            box-shadow:
                0 25px 60px rgba(0, 0, 0, 0.14);

            animation: loginFade 0.7s ease;
        }


        /* =====================================================
           PANEL KIRI
        ====================================================== */

        .left-panel {

            width: 48%;

            position: relative;

            overflow: hidden;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 55px 45px;

            text-align: center;

            color: #ffffff;

            background:
                linear-gradient(
                    135deg,
                    #123b6d 0%,
                    #255b97 100%
                );
        }


        /* BULATAN DEKORASI */

        .left-panel::before {

            content: "";

            position: absolute;

            width: 380px;

            height: 380px;

            border-radius: 50%;

            background: rgba(255, 255, 255, 0.06);

            top: -190px;

            left: -190px;
        }


        .left-panel::after {

            content: "";

            position: absolute;

            width: 420px;

            height: 420px;

            border-radius: 50%;

            background: rgba(255, 255, 255, 0.05);

            right: -210px;

            bottom: -210px;
        }


        /* =====================================================
           BRAND CONTENT
        ====================================================== */

        .brand-content {

            position: relative;

            z-index: 5;

            width: 100%;

            max-width: 410px;
        }


        /* =====================================================
           LOGO
        ====================================================== */

        .logo-wrapper {

            width: 145px;

            height: 145px;

            margin: 0 auto 28px;

            padding: 15px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #ffffff;

            border-radius: 50%;

            box-shadow:
                0 15px 35px rgba(0, 0, 0, 0.20);

            animation: logoFloat 3s ease-in-out infinite;
        }


        .logo-wrapper img {

            width: 100%;

            height: 100%;

            object-fit: contain;

            display: block;
        }


        /* =====================================================
           SISMOLEV
        ====================================================== */

        .brand-content h1 {

            margin: 0;

            font-size: 38px;

            line-height: 1.2;

            font-weight: 700;

            letter-spacing: 1.5px;
        }


        .brand-line {

            width: 70px;

            height: 4px;

            margin: 18px auto;

            border-radius: 50px;

            background: #ffffff;
        }


        .brand-content h2 {

            margin: 0;

            font-size: 16px;

            line-height: 1.7;

            font-weight: 500;
        }


        .brand-content p {

            margin: 18px 0 0;

            font-size: 12px;

            line-height: 1.8;

            font-weight: 300;

            opacity: 0.88;
        }


        /* =====================================================
           BADGE
        ====================================================== */

        .system-badge {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            margin-top: 23px;

            padding: 9px 18px;

            border-radius: 50px;

            border: 1px solid rgba(255,255,255,0.35);

            background: rgba(255,255,255,0.08);

            font-size: 11px;

            font-weight: 400;
        }


        /* =====================================================
           PANEL KANAN
        ====================================================== */

        .right-panel {

            width: 52%;

            padding: 65px 75px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #ffffff;
        }


        .login-form-container {

            width: 100%;

            max-width: 420px;
        }


        /* =====================================================
           LOGIN TITLE
        ====================================================== */

        .login-title {

            margin-bottom: 32px;
        }


        .login-title h3 {

            margin: 0 0 8px;

            color: #1e3c72;

            font-size: 28px;

            line-height: 1.3;

            font-weight: 700;
        }


        .login-title p {

            margin: 0;

            color: #8c8c8c;

            font-size: 12px;
        }


        /* =====================================================
           ALERT
        ====================================================== */

        .alert {

            border: none;

            border-radius: 12px;

            padding: 13px 15px;

            margin-bottom: 22px;

            font-size: 12px;
        }


        /* =====================================================
           FORM GROUP
        ====================================================== */

        .form-group {

            margin-bottom: 21px;
        }


        .form-label {

            display: block;

            margin-bottom: 8px;

            color: #333333;

            font-size: 12px;

            font-weight: 600;
        }


        .form-label i {

            color: #2a5298;

            margin-right: 5px;
        }


        /* =====================================================
           INPUT
        ====================================================== */

        .input-group {

            border: 1px solid #dedede;

            border-radius: 12px;

            overflow: hidden;

            background: #ffffff;

            transition: all 0.25s ease;
        }


        .input-group:focus-within {

            border-color: #2a5298;

            box-shadow:
                0 0 0 4px rgba(42, 82, 152, 0.10);
        }


        .input-group-text {

            border: none !important;

            background: transparent;

            color: #8b8b8b;

            padding-left: 16px;

            padding-right: 8px;
        }


        .form-control {

            border: none !important;

            outline: none !important;

            box-shadow: none !important;

            background: transparent !important;

            padding: 13px 10px;

            font-size: 12px;

            color: #333333;
        }


        .form-control::placeholder {

            color: #b2b2b2;
        }


        /* =====================================================
           PASSWORD TOGGLE
        ====================================================== */

        .password-toggle {

            border: none;

            background: transparent;

            color: #888888;

            padding: 0 15px;

            cursor: pointer;

            transition: 0.2s;
        }


        .password-toggle:hover {

            color: #2a5298;
        }


        /* =====================================================
           BUTTON LOGIN
        ====================================================== */

        .btn-login {

            width: 100%;

            border: none;

            border-radius: 12px;

            padding: 14px;

            margin-top: 5px;

            background:
                linear-gradient(
                    135deg,
                    #1e3c72 0%,
                    #2a5298 100%
                );

            color: #ffffff;

            font-size: 13px;

            font-weight: 600;

            box-shadow:
                0 8px 20px rgba(42, 82, 152, 0.22);

            transition: all 0.25s ease;
        }


        .btn-login:hover {

            color: #ffffff;

            transform: translateY(-2px);

            box-shadow:
                0 12px 25px rgba(42, 82, 152, 0.30);
        }


        .btn-login:active {

            transform: translateY(0);
        }


        .btn-login i {

            margin-right: 7px;
        }


        /* =====================================================
           BUTTON HALAMAN PUBLIK
        ====================================================== */

        .btn-public {

            width: 100%;

            min-height: 46px;

            margin-top: 12px;

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 8px;

            border: 1px solid #d9dfe8;

            border-radius: 12px;

            background: #ffffff;

            color: #1e3c72;

            font-size: 12px;

            font-weight: 600;

            text-decoration: none;

            transition: all 0.25s ease;
        }


        .btn-public i {

            font-size: 12px;
        }


        .btn-public:hover {

            background: #f4f7fb;

            border-color: #2a5298;

            color: #2a5298;

            transform: translateY(-1px);
        }


        /* =====================================================
           INFO TEXT
        ====================================================== */

        .info-text {

            margin-top: 24px;

            text-align: center;

            color: #999999;

            font-size: 10px;

            line-height: 1.6;
        }


        .info-text i {

            color: #2a5298;
        }


        /* =====================================================
           REGISTER
        ====================================================== */

        .register-text {

            margin-top: 13px;

            text-align: center;

            color: #888888;

            font-size: 11px;
        }


        .register-text a {

            color: #1e3c72;

            text-decoration: none;

            font-weight: 600;
        }


        .register-text a:hover {

            text-decoration: underline;
        }


        /* =====================================================
           ANIMATION
        ====================================================== */

        @keyframes loginFade {

            from {

                opacity: 0;

                transform: translateY(25px);
            }

            to {

                opacity: 1;

                transform: translateY(0);
            }
        }


        @keyframes logoFloat {

            0%,
            100% {

                transform: translateY(0);
            }

            50% {

                transform: translateY(-7px);
            }
        }


        /* =====================================================
           TABLET
        ====================================================== */

        @media (max-width: 950px) {

            .login-card {

                max-width: 800px;
            }


            .left-panel {

                width: 45%;

                padding: 45px 25px;
            }


            .right-panel {

                width: 55%;

                padding: 45px 40px;
            }


            .brand-content h1 {

                font-size: 31px;
            }


            .brand-content h2 {

                font-size: 14px;
            }


            .logo-wrapper {

                width: 120px;

                height: 120px;
            }
        }


        /* =====================================================
           MOBILE
        ====================================================== */

        @media (max-width: 700px) {

            .login-wrapper {

                padding: 15px;
            }


            .login-card {

                width: 100%;

                min-height: auto;

                flex-direction: column;

                border-radius: 20px;
            }


            .left-panel,
            .right-panel {

                width: 100%;
            }


            .left-panel {

                padding: 40px 25px;
            }


            .right-panel {

                padding: 40px 25px;
            }


            .logo-wrapper {

                width: 100px;

                height: 100px;

                padding: 12px;

                margin-bottom: 20px;
            }


            .brand-content h1 {

                font-size: 28px;
            }


            .brand-content h2 {

                font-size: 13px;
            }


            .brand-content p {

                font-size: 11px;
            }


            .system-badge {

                margin-top: 16px;
            }


            .login-title {

                margin-bottom: 27px;
            }


            .login-title h3 {

                font-size: 24px;
            }
        }


        /* =====================================================
           SMALL MOBILE
        ====================================================== */

        @media (max-width: 400px) {

            .left-panel {

                padding: 30px 20px;
            }


            .right-panel {

                padding: 30px 20px;
            }


            .logo-wrapper {

                width: 85px;

                height: 85px;
            }


            .brand-content h1 {

                font-size: 25px;
            }


            .brand-content p {

                display: none;
            }
        }

    
        /* =========================================================
           SISMOLEV LOGIN — PREMIUM / SMOOTH EXPERIENCE
        ========================================================= */

        html {
            scroll-behavior: smooth;
        }

        body {
            position: relative;
            background:
                radial-gradient(circle at 10% 15%, rgba(42,82,152,.10), transparent 28%),
                radial-gradient(circle at 90% 85%, rgba(102,126,234,.10), transparent 30%),
                #f4f6f9;
        }

        /* Ambient background */
        body::before,
        body::after {
            content: "";
            position: fixed;
            z-index: -1;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(2px);
            animation: ambientFloat 12s ease-in-out infinite alternate;
        }

        body::before {
            width: 420px;
            height: 420px;
            top: -180px;
            left: -160px;
            background: rgba(42,82,152,.08);
        }

        body::after {
            width: 360px;
            height: 360px;
            right: -130px;
            bottom: -140px;
            background: rgba(102,126,234,.10);
            animation-delay: -4s;
        }

        .login-wrapper {
            position: relative;
            isolation: isolate;
            padding: 35px 24px;
        }

        .login-card {
            position: relative;
            border: 1px solid rgba(255,255,255,.75);
            box-shadow:
                0 35px 90px rgba(15,39,72,.16),
                0 10px 30px rgba(15,39,72,.07);
            animation: premiumEnter .8s cubic-bezier(.22,1,.36,1) both;
            transition:
                transform .45s cubic-bezier(.22,1,.36,1),
                box-shadow .45s ease;
        }

        .login-card:hover {
            transform: translateY(-3px);
            box-shadow:
                0 42px 105px rgba(15,39,72,.18),
                0 14px 34px rgba(15,39,72,.08);
        }

        .left-panel {
            background:
                radial-gradient(circle at 15% 15%, rgba(255,255,255,.13), transparent 28%),
                radial-gradient(circle at 85% 85%, rgba(255,193,7,.08), transparent 24%),
                linear-gradient(145deg, #123b6d 0%, #1e4e87 48%, #2a5298 100%);
        }

        .left-panel::before,
        .left-panel::after {
            animation: decorationFloat 14s ease-in-out infinite alternate;
        }

        .left-panel::after {
            animation-delay: -5s;
        }

        .brand-content {
            animation: contentReveal .9s .12s cubic-bezier(.22,1,.36,1) both;
        }

        .logo-wrapper {
            position: relative;
            border: 5px solid rgba(255,255,255,.12);
            box-shadow:
                0 20px 45px rgba(0,0,0,.22),
                0 0 0 10px rgba(255,255,255,.045);
            animation: logoFloat 4s ease-in-out infinite;
            transition:
                transform .4s cubic-bezier(.22,1,.36,1),
                box-shadow .4s ease;
        }

        .logo-wrapper:hover {
            transform: translateY(-5px) scale(1.025);
            box-shadow:
                0 25px 55px rgba(0,0,0,.25),
                0 0 0 12px rgba(255,255,255,.06);
        }

        .brand-line {
            background: linear-gradient(90deg, transparent, #fff, transparent);
            width: 90px;
            box-shadow: 0 0 15px rgba(255,255,255,.2);
        }

        .system-badge {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.12);
            transition: all .3s ease;
        }

        .system-badge:hover {
            background: rgba(255,255,255,.14);
            transform: translateY(-2px);
        }

        .right-panel {
            position: relative;
        }

        .right-panel::before {
            content: "";
            position: absolute;
            width: 180px;
            height: 180px;
            top: -100px;
            right: -90px;
            border-radius: 50%;
            background: rgba(42,82,152,.035);
            pointer-events: none;
        }

        .login-form-container {
            position: relative;
            z-index: 2;
            animation: formReveal .8s .2s cubic-bezier(.22,1,.36,1) both;
        }

        .login-title h3 {
            letter-spacing: -.4px;
        }

        .login-title p {
            color: #7b8798;
        }

        .alert {
            animation: alertReveal .45s ease both;
            box-shadow: 0 8px 22px rgba(239,68,68,.08);
        }

        .form-group {
            transition: transform .25s ease;
        }

        .form-group:focus-within {
            transform: translateY(-1px);
        }

        .form-label {
            transition: color .25s ease;
        }

        .form-group:focus-within .form-label {
            color: #1e3c72;
        }

        .input-group {
            position: relative;
            min-height: 50px;
            border-color: #e0e5ec;
            box-shadow: 0 4px 14px rgba(15,39,72,.025);
            transition:
                border-color .3s ease,
                box-shadow .3s ease,
                transform .3s ease,
                background .3s ease;
        }

        .input-group::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 2px;
            transform: scaleX(0);
            transform-origin: center;
            background: linear-gradient(90deg, #1e3c72, #667eea);
            transition: transform .3s ease;
            z-index: 2;
        }

        .input-group:focus-within {
            border-color: rgba(42,82,152,.55);
            background: #fff;
            box-shadow:
                0 10px 28px rgba(42,82,152,.08),
                0 0 0 4px rgba(42,82,152,.07);
        }

        .input-group:focus-within::after {
            transform: scaleX(1);
        }

        .input-group-text i {
            transition: transform .3s ease, color .3s ease;
        }

        .input-group:focus-within .input-group-text i {
            color: #2a5298;
            transform: scale(1.05);
        }

        .form-control {
            min-height: 48px;
        }

        .form-control:-webkit-autofill,
        .form-control:-webkit-autofill:hover,
        .form-control:-webkit-autofill:focus {
            -webkit-text-fill-color: #333;
            -webkit-box-shadow: 0 0 0 1000px #fff inset;
            transition: background-color 9999s ease-in-out 0s;
        }

        .password-toggle {
            position: relative;
            z-index: 4;
        }

        .password-toggle:focus-visible {
            outline: 2px solid rgba(42,82,152,.35);
            outline-offset: -3px;
            border-radius: 8px;
        }

        .btn-login {
            position: relative;
            min-height: 50px;
            overflow: hidden;
            isolation: isolate;
            background:
                linear-gradient(120deg, #123b6d, #1e3c72 45%, #2a5298);
            background-size: 180% 180%;
            animation: buttonGradient 5s ease infinite;
        }

        .btn-login::before {
            content: "";
            position: absolute;
            top: 0;
            left: -120%;
            width: 70%;
            height: 100%;
            transform: skewX(-18deg);
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255,255,255,.18),
                transparent
            );
            transition: left .7s ease;
            z-index: -1;
        }

        .btn-login:hover::before {
            left: 145%;
        }

        .btn-login:hover {
            transform: translateY(-2px) scale(1.005);
        }

        .btn-login:focus-visible,
        .btn-public:focus-visible {
            outline: 3px solid rgba(42,82,152,.22);
            outline-offset: 3px;
        }

        .btn-login.is-loading {
            cursor: wait;
            opacity: .92;
            pointer-events: none;
        }

        .btn-login .login-spinner {
            display: none;
        }

        .btn-login.is-loading .login-icon,
        .btn-login.is-loading .login-label {
            display: none;
        }

        .btn-login.is-loading .login-spinner {
            display: inline-block;
        }

        .btn-public {
            position: relative;
            overflow: hidden;
            background: rgba(255,255,255,.82);
            box-shadow: 0 5px 16px rgba(15,39,72,.035);
        }

        .btn-public::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(42,82,152,.05),
                transparent
            );
            transform: translateX(-100%);
            transition: transform .6s ease;
        }

        .btn-public:hover::before {
            transform: translateX(100%);
        }

        .register-text a {
            position: relative;
        }

        .register-text a::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -2px;
            height: 1px;
            transform: scaleX(0);
            transform-origin: right;
            background: currentColor;
            transition: transform .3s ease;
        }

        .register-text a:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        /* Page entrance overlay */
        .page-transition {
            position: fixed;
            inset: 0;
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            background: linear-gradient(135deg, #123b6d, #2a5298);
            opacity: 1;
            visibility: visible;
            transition:
                opacity .55s ease,
                visibility .55s ease;
        }

        .page-transition.done {
            opacity: 0;
            visibility: hidden;
        }

        .page-transition .transition-inner {
            text-align: center;
            color: #fff;
            transform: translateY(0) scale(1);
            transition: transform .55s cubic-bezier(.22,1,.36,1);
        }

        .page-transition.done .transition-inner {
            transform: translateY(-10px) scale(.98);
        }

        .page-transition img {
            width: 72px;
            height: 72px;
            object-fit: contain;
            padding: 8px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 15px 35px rgba(0,0,0,.18);
            margin-bottom: 12px;
        }

        .page-transition strong {
            display: block;
            font-size: 16px;
            letter-spacing: .8px;
        }

        .page-transition small {
            display: block;
            margin-top: 3px;
            opacity: .7;
            font-size: 10px;
        }

        /* Better mobile spacing */
        @media (max-width: 700px) {
            .login-wrapper {
                padding: 15px;
            }

            .login-card:hover {
                transform: none;
            }

            .left-panel {
                min-height: 360px;
            }

            .right-panel {
                min-height: 480px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
                scroll-behavior: auto !important;
            }
        }

        @keyframes premiumEnter {
            from {
                opacity: 0;
                transform: translateY(22px) scale(.985);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes contentReveal {
            from {
                opacity: 0;
                transform: translateX(-18px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes formReveal {
            from {
                opacity: 0;
                transform: translateX(18px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes alertReveal {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes ambientFloat {
            from { transform: translate3d(0,0,0) scale(1); }
            to { transform: translate3d(18px,14px,0) scale(1.06); }
        }

        @keyframes decorationFloat {
            from { transform: translate3d(0,0,0); }
            to { transform: translate3d(12px,-12px,0); }
        }

        @keyframes buttonGradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

    </style>

</head>


<body>

    <!-- =========================================================
         PREMIUM PAGE TRANSITION
    ========================================================== -->
    <div class="page-transition" id="pageTransition" aria-hidden="true">
        <div class="transition-inner">
            <img src="{{ asset('images/logo-kab-karo.png') }}" alt="Logo Kabupaten Karo">
            <strong>SISMOLEV</strong>
            <small>Desa Kutabuluh</small>
        </div>
    </div>




    <!-- =====================================================
         LOGIN WRAPPER
    ====================================================== -->

    <div class="login-wrapper">


        <!-- =================================================
             LOGIN CARD
        ================================================== -->

        <div class="login-card">


            <!-- =============================================
                 PANEL KIRI
            ============================================== -->

            <div class="left-panel">

                <div class="brand-content">


                    <!-- LOGO -->

                    <div class="logo-wrapper">

                        <img
                            src="{{ asset('images/logo-kab-karo.png') }}"
                            alt="Logo Kabupaten Karo"
                        >

                    </div>


                    <!-- NAMA SISTEM -->

                    <h1>
                        SISMOLEV
                    </h1>


                    <div class="brand-line"></div>


                    <!-- DESKRIPSI -->

                    <h2>

                        Sistem Informasi Monitoring dan Evaluasi
                        Pembangunan Desa Kutabuluh

                    </h2>


                    <p>

                        Sistem informasi untuk membantu proses monitoring,
                        evaluasi, dan pengelolaan pembangunan Desa Kutabuluh
                        secara terintegrasi.

                    </p>


                    <!-- BADGE -->

                    <div class="system-badge">

                        <i class="fas fa-shield-alt"></i>

                        Sistem Terintegrasi

                    </div>


                </div>

            </div>


            <!-- =============================================
                 PANEL KANAN
            ============================================== -->

            <div class="right-panel">


                <div class="login-form-container">


                    <!-- JUDUL -->

                    <div class="login-title">

                        <h3>
                            Selamat Datang 👋
                        </h3>

                        <p>
                            Silakan masuk untuk mengakses sistem
                        </p>

                    </div>


                    <!-- =====================================
                         ERROR
                    ====================================== -->

                    @if(session('error'))

                        <div class="alert alert-danger">

                            <i class="fas fa-exclamation-circle me-2"></i>

                            {{ session('error') }}

                        </div>

                    @endif


                    <!-- =====================================
                         FORM LOGIN
                    ====================================== -->

                    <form
                        method="POST"
                        action="{{ route('login') }}"
                    >

                        @csrf


                        <!-- USERNAME -->

                        <div class="form-group">

                            <label class="form-label">

                                <i class="fas fa-user"></i>

                                Username

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="fas fa-user"></i>

                                </span>


                                <input
                                    type="text"
                                    name="username"
                                    class="form-control"
                                    placeholder="Masukkan username"
                                    value="{{ old('username') }}"
                                    required
                                    autofocus
                                >

                            </div>

                        </div>


                        <!-- PASSWORD -->

                        <div class="form-group">

                            <label class="form-label">

                                <i class="fas fa-lock"></i>

                                Password

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="fas fa-lock"></i>

                                </span>


                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control"
                                    placeholder="Masukkan password"
                                    required
                                >


                                <button
                                    type="button"
                                    class="password-toggle"
                                    onclick="togglePassword()"
                                    title="Tampilkan password"
                                >

                                    <i
                                        class="fas fa-eye"
                                        id="passwordIcon"
                                    ></i>

                                </button>

                            </div>

                        </div>


                        <!-- =================================
                             LOGIN
                        ================================== -->

                        <button
                            type="submit"
                            class="btn btn-login"
                            id="loginButton"
                        >
                            <span class="login-spinner">
                                <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                                Memproses login...
                            </span>

                            <span class="login-icon">
                                <i class="fas fa-sign-in-alt"></i>
                            </span>

                            <span class="login-label">
                                Login
                            </span>
                        </button>


                        <!-- =================================
                             HALAMAN PUBLIK
                        ================================== -->

                        <a
                            href="{{ route('home') }}"
                            class="btn-public"
                        >

                            <i class="fas fa-globe"></i>

                            Kembali ke Halaman Publik

                        </a>


                    </form>


                    <!-- =====================================
                         INFO
                    ====================================== -->

                    <div class="info-text">

                        <i class="fas fa-shield-alt me-1"></i>

                        Sistem ini hanya dapat diakses oleh
                        pengguna terdaftar

                    </div>


                    <!-- =====================================
                         REGISTER
                    ====================================== -->

                    <div class="register-text">

                        Belum punya akun?

                        <a href="{{ route('register') }}">

                            Daftar sebagai Rekanan

                        </a>

                    </div>


                </div>

            </div>


        </div>

    </div>


    <!-- =====================================================
         BOOTSTRAP JS
    ====================================================== -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>


    <!-- =====================================================
         PASSWORD TOGGLE
    ====================================================== -->

    <script>

        (() => {
            'use strict';

            const password = document.getElementById('password');
            const icon = document.getElementById('passwordIcon');
            const loginForm = document.querySelector('form[action="{{ route('login') }}"]');
            const loginButton = document.getElementById('loginButton');
            const pageTransition = document.getElementById('pageTransition');

            window.addEventListener('load', () => {
                window.setTimeout(() => {
                    pageTransition?.classList.add('done');
                }, 220);
            });

            window.togglePassword = function () {
                if (!password || !icon) return;

                const showing = password.type === 'password';
                password.type = showing ? 'text' : 'password';

                icon.classList.toggle('fa-eye', !showing);
                icon.classList.toggle('fa-eye-slash', showing);

                const toggle = document.querySelector('.password-toggle');
                if (toggle) {
                    toggle.setAttribute(
                        'aria-label',
                        showing ? 'Sembunyikan password' : 'Tampilkan password'
                    );
                    toggle.setAttribute(
                        'title',
                        showing ? 'Sembunyikan password' : 'Tampilkan password'
                    );
                }
            };

            /* Backend tetap route login yang sama.
               Ini hanya memberi feedback visual dan mencegah double submit. */
            if (loginForm && loginButton) {
                loginForm.addEventListener('submit', function () {
                    if (!loginForm.checkValidity()) return;

                    loginButton.classList.add('is-loading');
                    loginButton.setAttribute('aria-disabled', 'true');
                    loginButton.disabled = true;
                });
            }

            [document.getElementById('username'), password].forEach((input) => {
                if (!input) return;

                input.addEventListener('input', () => {
                    input.closest('.input-group')?.classList.remove('is-invalid');
                });
            });
        })();

    </script>


</body>

</html>