<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Registrasi Rekanan - Sistem Informasi Monitoring dan Evaluasi Pembangunan Desa Kutabuluh
    </title>


    <!-- FAVICON -->
    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="{{ asset('images/logo-kab-karo.png') }}"
    >

    <link
        rel="shortcut icon"
        href="{{ asset('images/logo-kab-karo.png') }}"
        type="image/x-icon"
    >


    <!-- BOOTSTRAP -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- FONT AWESOME -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >


    <!-- GOOGLE FONT -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <style>

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

            background: #f3f5f8;

            min-height: 100vh;

            overflow-x: hidden;
        }


        /* =====================================================
           WRAPPER
        ====================================================== */

        .register-wrapper {

            min-height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 25px;
        }


        /* =====================================================
           CARD
        ====================================================== */

        .register-card {

            width: 100%;

            max-width: 1100px;

            display: flex;

            background: #fff;

            border-radius: 24px;

            overflow: hidden;

            box-shadow:
                0 25px 60px rgba(0, 0, 0, .13);

            animation: fadeIn .6s ease;
        }


        /* =====================================================
           LEFT PANEL
        ====================================================== */

        .left-panel {

            width: 38%;

            position: relative;

            overflow: hidden;

            display: flex;

            align-items: center;

            justify-content: center;

            text-align: center;

            padding: 45px 35px;

            color: #fff;

            background:
                linear-gradient(
                    145deg,
                    #123b6d,
                    #255b97
                );
        }


        .left-panel::before {

            content: "";

            position: absolute;

            width: 350px;

            height: 350px;

            border-radius: 50%;

            background: rgba(255,255,255,.06);

            top: -180px;

            left: -180px;
        }


        .left-panel::after {

            content: "";

            position: absolute;

            width: 400px;

            height: 400px;

            border-radius: 50%;

            background: rgba(255,255,255,.05);

            right: -200px;

            bottom: -200px;
        }


        .brand-content {

            position: relative;

            z-index: 2;

            width: 100%;

            max-width: 350px;
        }


        /* =====================================================
           LOGO
        ====================================================== */

        .logo-wrapper {

            width: 125px;

            height: 125px;

            margin: 0 auto 22px;

            padding: 13px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #fff;

            border-radius: 50%;

            box-shadow:
                0 12px 30px rgba(0,0,0,.20);

            animation: floatLogo 3s ease-in-out infinite;
        }


        .logo-wrapper img {

            width: 100%;

            height: 100%;

            object-fit: contain;
        }


        /* =====================================================
           BRAND
        ====================================================== */

        .brand-content h1 {

            margin: 0;

            font-size: 34px;

            font-weight: 700;

            letter-spacing: 1.5px;
        }


        .brand-line {

            width: 60px;

            height: 4px;

            margin: 15px auto;

            border-radius: 20px;

            background: #fff;
        }


        .brand-content h2 {

            margin: 0;

            font-size: 15px;

            font-weight: 500;

            line-height: 1.6;
        }


        .brand-content p {

            margin-top: 17px;

            font-size: 11px;

            line-height: 1.8;

            opacity: .88;
        }


        .system-badge {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            margin-top: 18px;

            padding: 8px 15px;

            border-radius: 50px;

            border: 1px solid rgba(255,255,255,.3);

            background: rgba(255,255,255,.08);

            font-size: 10px;
        }


        /* =====================================================
           RIGHT PANEL
        ====================================================== */

        .right-panel {

            width: 62%;

            padding: 38px 50px;

            display: flex;

            align-items: center;

            justify-content: center;
        }


        .form-container {

            width: 100%;

            max-width: 620px;
        }


        /* =====================================================
           HEADER
        ====================================================== */

        .form-header {

            margin-bottom: 20px;
        }


        .form-header h3 {

            margin: 0 0 5px;

            color: #1e3c72;

            font-size: 25px;

            font-weight: 700;
        }


        .form-header p {

            margin: 0;

            color: #999;

            font-size: 11px;
        }


        /* =====================================================
           ALERT
        ====================================================== */

        .alert {

            border: none;

            border-radius: 10px;

            padding: 10px 12px;

            margin-bottom: 15px;

            font-size: 11px;
        }


        /* =====================================================
           GRID FORM
        ====================================================== */

        .form-grid {

            display: grid;

            grid-template-columns: 1fr 1fr;

            column-gap: 18px;

            row-gap: 12px;
        }


        .form-group {

            min-width: 0;
        }


        .full-width {

            grid-column: 1 / -1;
        }


        /* =====================================================
           LABEL
        ====================================================== */

        .form-label {

            display: block;

            margin-bottom: 5px;

            font-size: 10px;

            font-weight: 600;

            color: #333;
        }


        .form-label i {

            color: #2a5298;

            margin-right: 4px;
        }


        .required {

            color: #dc3545;
        }


        /* =====================================================
           INPUT
        ====================================================== */

        .input-group {

            border: 1px solid #ddd;

            border-radius: 9px;

            overflow: hidden;

            transition: .2s;

            background: #fff;
        }


        .input-group:focus-within {

            border-color: #2a5298;

            box-shadow:
                0 0 0 3px rgba(42,82,152,.08);
        }


        .input-group-text {

            border: none !important;

            background: transparent;

            color: #8d8d8d;

            padding: 0 10px;
        }


        .form-control {

            border: none !important;

            outline: none !important;

            box-shadow: none !important;

            background: transparent !important;

            padding: 9px 8px;

            font-size: 10px;

            min-height: 37px;
        }


        .form-control::placeholder {

            color: #b5b5b5;
        }


        textarea.form-control {

            min-height: 70px;

            resize: none;

            padding-top: 10px;
        }


        /* =====================================================
           PASSWORD
        ====================================================== */

        .password-toggle {

            border: none;

            background: transparent;

            color: #888;

            padding: 0 11px;

            cursor: pointer;
        }


        .password-toggle:hover {

            color: #2a5298;
        }


        /* =====================================================
           ERROR
        ====================================================== */

        .invalid-feedback {

            display: block;

            margin-top: 3px;

            font-size: 9px;
        }


        .text-muted-small {

            display: block;

            margin-top: 4px;

            color: #999;

            font-size: 8px;

            line-height: 1.4;
        }


        /* =====================================================
           BUTTON
        ====================================================== */

        .button-row {

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 12px;

            margin-top: 17px;
        }


        .btn-register,
        .btn-public {

            min-height: 42px;

            border-radius: 10px;

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            font-size: 11px;

            font-weight: 600;

            transition: .25s;
        }


        /* REGISTER */

        .btn-register {

            border: none;

            background:
                linear-gradient(
                    135deg,
                    #1e3c72,
                    #2a5298
                );

            color: #fff;

            box-shadow:
                0 7px 18px rgba(42,82,152,.20);
        }


        .btn-register:hover {

            color: #fff;

            transform: translateY(-2px);

            box-shadow:
                0 10px 22px rgba(42,82,152,.28);
        }


        /* PUBLIC */

        .btn-public {

            border: 1px solid #d9dfe8;

            background: #fff;

            color: #1e3c72;

            text-decoration: none;
        }


        .btn-public:hover {

            background: #f4f7fb;

            border-color: #2a5298;

            color: #2a5298;

            transform: translateY(-2px);
        }


        /* =====================================================
           LOGIN
        ====================================================== */

        .login-info {

            text-align: center;

            margin-top: 14px;

            font-size: 10px;

            color: #888;
        }


        .login-info a {

            color: #1e3c72;

            font-weight: 600;

            text-decoration: none;
        }


        .login-info a:hover {

            text-decoration: underline;
        }


        /* =====================================================
           ANIMATION
        ====================================================== */

        @keyframes fadeIn {

            from {

                opacity: 0;

                transform: translateY(20px);
            }

            to {

                opacity: 1;

                transform: translateY(0);
            }
        }


        @keyframes floatLogo {

            0%,
            100% {

                transform: translateY(0);
            }

            50% {

                transform: translateY(-6px);
            }
        }


        /* =====================================================
           TABLET
        ====================================================== */

        @media (max-width: 900px) {

            .register-card {

                max-width: 800px;
            }


            .left-panel {

                width: 35%;

                padding: 35px 22px;
            }


            .right-panel {

                width: 65%;

                padding: 35px 30px;
            }


            .logo-wrapper {

                width: 100px;

                height: 100px;
            }


            .brand-content h1 {

                font-size: 28px;
            }


            .brand-content h2 {

                font-size: 12px;
            }

        }


        /* =====================================================
           MOBILE
        ====================================================== */

        @media (max-width: 700px) {

            .register-wrapper {

                padding: 15px;
            }


            .register-card {

                flex-direction: column;

                border-radius: 20px;
            }


            .left-panel,
            .right-panel {

                width: 100%;
            }


            .left-panel {

                padding: 35px 20px;
            }


            .right-panel {

                padding: 30px 22px;
            }


            .logo-wrapper {

                width: 90px;

                height: 90px;

                margin-bottom: 17px;
            }


            .brand-content h1 {

                font-size: 26px;
            }


            .brand-content p {

                font-size: 10px;
            }


            .form-header h3 {

                font-size: 22px;
            }


            .form-grid {

                grid-template-columns: 1fr;
            }


            .full-width {

                grid-column: auto;
            }


            .button-row {

                grid-template-columns: 1fr;
            }

        }


        /* =====================================================
           SMALL MOBILE
        ====================================================== */

        @media (max-width: 400px) {

            .left-panel {

                padding: 28px 18px;
            }


            .right-panel {

                padding: 27px 18px;
            }


            .brand-content p {

                display: none;
            }


            .logo-wrapper {

                width: 80px;

                height: 80px;
            }

        }

    
        /* =====================================================
           PREMIUM ULTIMATE REGISTER EXPERIENCE
        ===================================================== */

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 24px;
        }

        body {
            background:
                radial-gradient(circle at 10% 10%, rgba(42,82,152,.08), transparent 30%),
                radial-gradient(circle at 90% 90%, rgba(30,60,114,.08), transparent 32%),
                #f3f5f8;
            overflow-x: hidden;
            overflow-y: auto;
        }

        body::before {
            content: "";
            position: fixed;
            inset: -20%;
            z-index: -1;
            pointer-events: none;
            background:
                radial-gradient(circle at 30% 35%, rgba(42,82,152,.06), transparent 25%),
                radial-gradient(circle at 75% 65%, rgba(18,59,109,.05), transparent 25%);
            filter: blur(35px);
            animation: ambientMove 14s ease-in-out infinite alternate;
        }

        .register-wrapper {
            position: relative;
            min-height: 100vh;
            padding: 32px 24px;
        }

        .register-card {
            position: relative;
            isolation: isolate;
            max-width: 1120px;
            min-height: 650px;
            border: 1px solid rgba(255,255,255,.7);
            box-shadow:
                0 35px 90px rgba(18,59,109,.14),
                0 10px 30px rgba(0,0,0,.06);
            animation: cardEnter .75s cubic-bezier(.22,1,.36,1) both;
            transform: translateZ(0);
        }

        .register-card::after {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            border-radius: inherit;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.9);
            z-index: 20;
        }

        .left-panel {
            background:
                radial-gradient(circle at 15% 15%, rgba(255,255,255,.12), transparent 24%),
                linear-gradient(145deg, #102f58 0%, #1e4e87 55%, #2a629e 100%);
        }

        .left-panel::before {
            animation: orbOne 12s ease-in-out infinite alternate;
        }

        .left-panel::after {
            animation: orbTwo 15s ease-in-out infinite alternate;
        }

        .brand-content {
            animation: contentEnter .9s .12s cubic-bezier(.22,1,.36,1) both;
        }

        .logo-wrapper {
            position: relative;
            width: 132px;
            height: 132px;
            padding: 11px;
            background: rgba(255,255,255,.96);
            border: 5px solid rgba(255,255,255,.16);
            box-shadow:
                0 20px 45px rgba(0,0,0,.22),
                0 0 0 10px rgba(255,255,255,.035);
            animation: floatLogo 4s ease-in-out infinite;
        }

        .logo-wrapper::after {
            content: "";
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.18);
            animation: logoRing 4s ease-in-out infinite;
        }

        .brand-content h1 {
            text-shadow: 0 4px 18px rgba(0,0,0,.18);
        }

        .system-badge {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background: rgba(255,255,255,.10);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.12);
        }

        .right-panel {
            position: relative;
            background:
                linear-gradient(180deg, rgba(255,255,255,.99), rgba(250,251,253,.98));
        }

        .form-container {
            animation: formEnter .8s .18s cubic-bezier(.22,1,.36,1) both;
        }

        .form-header h3 {
            letter-spacing: -.3px;
        }

        .form-header p {
            color: #8b94a3;
        }

        .alert {
            animation: alertEnter .35s ease both;
            box-shadow: 0 8px 20px rgba(0,0,0,.04);
        }

        .form-group {
            animation: fieldEnter .55s cubic-bezier(.22,1,.36,1) both;
        }

        .form-group:nth-child(1) { animation-delay: .08s; }
        .form-group:nth-child(2) { animation-delay: .12s; }
        .form-group:nth-child(3) { animation-delay: .16s; }
        .form-group:nth-child(4) { animation-delay: .20s; }
        .form-group:nth-child(5) { animation-delay: .24s; }
        .form-group:nth-child(6) { animation-delay: .28s; }
        .form-group:nth-child(7) { animation-delay: .32s; }
        .form-group:nth-child(8) { animation-delay: .36s; }

        .input-group {
            position: relative;
            border-color: #e1e5eb;
            background: rgba(255,255,255,.96);
            box-shadow: 0 3px 10px rgba(18,59,109,.025);
            transition:
                border-color .28s ease,
                box-shadow .28s ease,
                transform .28s ease,
                background .28s ease;
        }

        .input-group::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 2px;
            border-radius: 10px;
            background: linear-gradient(90deg, #1e3c72, #4f7fba);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .3s ease;
        }

        .input-group:focus-within {
            border-color: #2a5298;
            background: #fff;
            transform: translateY(-1px);
            box-shadow:
                0 10px 25px rgba(42,82,152,.09),
                0 0 0 4px rgba(42,82,152,.065);
        }

        .input-group:focus-within::after {
            transform: scaleX(1);
        }

        .input-group:focus-within .input-group-text {
            color: #2a5298;
        }

        .input-group-text {
            transition: color .25s ease;
        }

        .form-control {
            transition: color .2s ease;
        }

        .password-toggle {
            transition:
                color .2s ease,
                transform .2s ease;
        }

        .password-toggle:hover {
            transform: scale(1.08);
        }

        .button-row {
            position: relative;
        }

        .btn-register,
        .btn-public {
            position: relative;
            overflow: hidden;
            min-height: 46px;
            transition:
                transform .28s cubic-bezier(.22,1,.36,1),
                box-shadow .28s ease,
                border-color .28s ease,
                background .28s ease;
        }

        .btn-register::before,
        .btn-public::before {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            left: -80%;
            width: 45%;
            transform: skewX(-20deg);
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255,255,255,.35),
                transparent
            );
            transition: left .65s ease;
        }

        .btn-register:hover::before,
        .btn-public:hover::before {
            left: 140%;
        }

        .btn-register {
            background:
                linear-gradient(120deg, #173766, #2a5298, #356ca8, #1e3c72);
            background-size: 220% 100%;
            animation: buttonGradient 6s ease infinite;
            box-shadow:
                0 10px 24px rgba(42,82,152,.22);
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow:
                0 15px 30px rgba(42,82,152,.30);
        }

        .btn-register:active,
        .btn-public:active {
            transform: translateY(0) scale(.985);
        }

        .btn-public:hover {
            box-shadow: 0 10px 22px rgba(42,82,152,.08);
        }

        .login-info a {
            position: relative;
            display: inline-block;
        }

        .login-info a::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -2px;
            height: 1px;
            transform: scaleX(0);
            transform-origin: center;
            background: currentColor;
            transition: transform .25s ease;
        }

        .login-info a:hover::after {
            transform: scaleX(1);
        }

        .form-control:-webkit-autofill,
        .form-control:-webkit-autofill:hover,
        .form-control:-webkit-autofill:focus {
            -webkit-text-fill-color: #333;
            -webkit-box-shadow: 0 0 0 1000px #fff inset;
            transition: background-color 9999s ease-out;
        }

        @keyframes cardEnter {
            from {
                opacity: 0;
                transform: translateY(28px) scale(.985);
                filter: blur(4px);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
                filter: blur(0);
            }
        }

        @keyframes contentEnter {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes formEnter {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fieldEnter {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes alertEnter {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes ambientMove {
            0% { transform: translate3d(-2%, -1%, 0) scale(1); }
            100% { transform: translate3d(2%, 1%, 0) scale(1.05); }
        }

        @keyframes orbOne {
            from { transform: translate(0, 0) scale(1); }
            to { transform: translate(20px, 18px) scale(1.08); }
        }

        @keyframes orbTwo {
            from { transform: translate(0, 0) scale(1); }
            to { transform: translate(-18px, -20px) scale(1.06); }
        }

        @keyframes logoRing {
            0%, 100% { transform: scale(1); opacity: .65; }
            50% { transform: scale(1.05); opacity: .25; }
        }

        @keyframes buttonGradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Form lebih nyaman ketika tinggi layar kecil */
        @media (max-height: 760px) and (min-width: 701px) {
            .register-wrapper {
                align-items: flex-start;
                padding-top: 20px;
                padding-bottom: 20px;
            }

            .register-card {
                min-height: auto;
            }

            .left-panel {
                padding-top: 28px;
                padding-bottom: 28px;
            }

            .logo-wrapper {
                width: 105px;
                height: 105px;
                margin-bottom: 14px;
            }

            .brand-content p {
                margin-top: 10px;
            }

            .right-panel {
                padding-top: 28px;
                padding-bottom: 28px;
            }
        }

        @media (max-width: 700px) {
            .register-wrapper {
                align-items: flex-start;
                padding: 14px;
            }

            .register-card {
                margin: 0 auto;
            }

            .left-panel {
                padding: 34px 20px;
            }

            .right-panel {
                padding: 30px 20px 34px;
            }

            .form-grid {
                row-gap: 14px;
            }

            .btn-register,
            .btn-public {
                min-height: 48px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }

    </style>

</head>


<body>


<div class="register-wrapper">


    <div class="register-card">


        <!-- =================================================
             PANEL KIRI
        ================================================== -->

        <div class="left-panel">

            <div class="brand-content">


                <div class="logo-wrapper">

                    <img
                        src="{{ asset('images/logo-kab-karo.png') }}"
                        alt="Logo Kabupaten Karo"
                    >

                </div>


                <h1>
                    SISMOLEV
                </h1>


                <div class="brand-line"></div>


                <h2>
                    Registrasi Rekanan
                </h2>


                <p>

                    Daftarkan diri sebagai rekanan atau mandor
                    untuk mengikuti proyek pembangunan
                    Desa Kutabuluh.

                </p>


                <div class="system-badge">

                    <i class="fas fa-user-plus"></i>

                    Pendaftaran Rekanan

                </div>

            </div>

        </div>


        <!-- =================================================
             PANEL KANAN
        ================================================== -->

        <div class="right-panel">

            <div class="form-container">


                <!-- HEADER -->

                <div class="form-header">

                    <h3>
                        Buat Akun Rekanan
                    </h3>

                    <p>
                        Lengkapi data Anda untuk mendaftar
                    </p>

                </div>


                <!-- ERROR -->

                @if(session('error'))

                    <div class="alert alert-danger">

                        <i class="fas fa-exclamation-circle me-1"></i>

                        {{ session('error') }}

                    </div>

                @endif


                <!-- SUCCESS -->

                @if(session('success'))

                    <div class="alert alert-success">

                        <i class="fas fa-check-circle me-1"></i>

                        {{ session('success') }}

                    </div>

                @endif


                <!-- FORM -->

                <form
                    method="POST"
                    action="{{ route('register') }}"
                >

                    @csrf


                    <div class="form-grid">


                        <!-- NAMA -->

                        <div class="form-group">

                            <label class="form-label">

                                <i class="fas fa-user"></i>

                                Nama Lengkap

                                <span class="required">*</span>

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="fas fa-user"></i>

                                </span>


                                <input
                                    type="text"
                                    name="nama_user"
                                    class="form-control"
                                    placeholder="Masukkan nama lengkap"
                                    value="{{ old('nama_user') }}"
                                    required
                                >

                            </div>


                            @error('nama_user')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- USERNAME -->

                        <div class="form-group">

                            <label class="form-label">

                                <i class="fas fa-user-circle"></i>

                                Username

                                <span class="required">*</span>

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="fas fa-user-circle"></i>

                                </span>


                                <input
                                    type="text"
                                    name="username"
                                    class="form-control"
                                    placeholder="Pilih username"
                                    value="{{ old('username') }}"
                                    required
                                >

                            </div>


                            @error('username')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- EMAIL -->

                        <div class="form-group">

                            <label class="form-label">

                                <i class="fas fa-envelope"></i>

                                Email

                                <span class="required">*</span>

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="fas fa-envelope"></i>

                                </span>


                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="contoh@email.com"
                                    value="{{ old('email') }}"
                                    required
                                >

                            </div>


                            @error('email')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror


                            <small class="text-muted-small">

                                <i class="fas fa-info-circle"></i>

                                Digunakan untuk pemberitahuan status proyek.

                            </small>

                        </div>


                        <!-- NOMOR TELEPON -->

                        <div class="form-group">

                            <label class="form-label">

                                <i class="fas fa-phone"></i>

                                Nomor Telepon

                                <span class="required">*</span>

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="fas fa-phone"></i>

                                </span>


                                <input
                                    type="text"
                                    name="no_telp"
                                    class="form-control"
                                    placeholder="081234567890"
                                    value="{{ old('no_telp') }}"
                                    required
                                >

                            </div>


                            @error('no_telp')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- PASSWORD -->

                        <div class="form-group">

                            <label class="form-label">

                                <i class="fas fa-lock"></i>

                                Password

                                <span class="required">*</span>

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
                                    placeholder="Minimal 6 karakter"
                                    required
                                >


                                <button
                                    type="button"
                                    class="password-toggle"
                                    onclick="togglePassword(
                                        'password',
                                        'passwordIcon'
                                    )"
                                >

                                    <i
                                        class="fas fa-eye"
                                        id="passwordIcon"
                                    ></i>

                                </button>

                            </div>


                            @error('password')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- KONFIRMASI PASSWORD -->

                        <div class="form-group">

                            <label class="form-label">

                                <i class="fas fa-lock"></i>

                                Konfirmasi Password

                                <span class="required">*</span>

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="fas fa-lock"></i>

                                </span>


                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control"
                                    placeholder="Ulangi password"
                                    required
                                >


                                <button
                                    type="button"
                                    class="password-toggle"
                                    onclick="togglePassword(
                                        'password_confirmation',
                                        'confirmPasswordIcon'
                                    )"
                                >

                                    <i
                                        class="fas fa-eye"
                                        id="confirmPasswordIcon"
                                    ></i>

                                </button>

                            </div>

                        </div>


                        <!-- BIDANG KEAHLIAN -->

                        <div class="form-group">

                            <label class="form-label">

                                <i class="fas fa-tools"></i>

                                Bidang Keahlian

                                <span class="required">*</span>

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="fas fa-tools"></i>

                                </span>


                                <input
                                    type="text"
                                    name="bidang_keahlian"
                                    class="form-control"
                                    placeholder="Konstruksi, Jalan, Listrik, dll"
                                    value="{{ old('bidang_keahlian') }}"
                                    required
                                >

                            </div>


                            @error('bidang_keahlian')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- ALAMAT -->

                        <div class="form-group">

                            <label class="form-label">

                                <i class="fas fa-map-marker-alt"></i>

                                Alamat

                                <span class="required">*</span>

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="fas fa-map-marker-alt"></i>

                                </span>


                                <textarea
                                    name="alamat"
                                    class="form-control"
                                    rows="2"
                                    placeholder="Masukkan alamat lengkap"
                                    required
                                >{{ old('alamat') }}</textarea>

                            </div>


                            @error('alamat')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                    </div>


                    <!-- BUTTON -->

                    <div class="button-row">


                        <button
                            type="submit"
                            class="btn-register"
                        >

                            <i class="fas fa-user-plus"></i>

                            Daftar Sebagai Rekanan

                        </button>


                        <a
                            href="{{ route('home') }}"
                            class="btn-public"
                        >

                            <i class="fas fa-globe"></i>

                            Halaman Publik

                        </a>


                    </div>


                </form>


                <!-- LOGIN -->

                <div class="login-info">

                    Sudah punya akun?

                    <a href="{{ route('login') }}">
                        Login di sini
                    </a>

                </div>


            </div>

        </div>

    </div>

</div>


<!-- BOOTSTRAP -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
</script>


<!-- PASSWORD TOGGLE -->

<script>

    function togglePassword(inputId, iconId) {

        const input =
            document.getElementById(inputId);

        const icon =
            document.getElementById(iconId);


        if (input.type === 'password') {

            input.type = 'text';

            icon.classList.remove('fa-eye');

            icon.classList.add('fa-eye-slash');

        } else {

            input.type = 'password';

            icon.classList.remove('fa-eye-slash');

            icon.classList.add('fa-eye');

        }

    }

</script>



    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[action="{{ route('register') }}"]');
            const password = document.getElementById('password');
            const confirmation = document.getElementById('password_confirmation');
            const registerButton = document.querySelector('.btn-register');

            if (!form || !password || !confirmation || !registerButton) {
                return;
            }

            /* Password strength indicator */
            const passwordGroup = password.closest('.form-group');

            const strength = document.createElement('div');
            strength.className = 'password-strength';
            strength.innerHTML = `
                <div class="strength-track">
                    <span class="strength-bar"></span>
                </div>
                <span class="strength-text">Gunakan minimal 6 karakter.</span>
            `;

            passwordGroup.appendChild(strength);

            const strengthStyle = document.createElement('style');
            strengthStyle.textContent = `
                .password-strength {
                    margin-top: 5px;
                    font-size: 8px;
                    color: #8b94a3;
                }

                .strength-track {
                    width: 100%;
                    height: 3px;
                    margin-bottom: 3px;
                    overflow: hidden;
                    border-radius: 99px;
                    background: #edf0f4;
                }

                .strength-bar {
                    display: block;
                    width: 0;
                    height: 100%;
                    border-radius: inherit;
                    background: #dc3545;
                    transition: width .3s ease, background .3s ease;
                }

                .strength-text {
                    transition: color .25s ease;
                }

                .confirm-status {
                    margin-top: 4px;
                    font-size: 8px;
                    min-height: 12px;
                }
            `;
            document.head.appendChild(strengthStyle);

            const strengthBar = strength.querySelector('.strength-bar');
            const strengthText = strength.querySelector('.strength-text');

            function updateStrength() {
                const value = password.value || '';
                let score = 0;

                if (value.length >= 6) score++;
                if (value.length >= 8) score++;
                if (/[A-Z]/.test(value)) score++;
                if (/[0-9]/.test(value)) score++;
                if (/[^A-Za-z0-9]/.test(value)) score++;

                const states = [
                    { width: '0%', color: '#dc3545', text: 'Gunakan minimal 6 karakter.' },
                    { width: '25%', color: '#dc3545', text: 'Password terlalu lemah.' },
                    { width: '50%', color: '#f0a000', text: 'Password cukup, tambahkan variasi.' },
                    { width: '75%', color: '#2a5298', text: 'Password cukup kuat.' },
                    { width: '100%', color: '#198754', text: 'Password kuat.' }
                ];

                const state = states[Math.min(score, 4)];

                strengthBar.style.width = value ? state.width : '0%';
                strengthBar.style.background = state.color;
                strengthText.style.color = value ? state.color : '#8b94a3';
                strengthText.textContent = value ? state.text : states[0].text;
            }

            password.addEventListener('input', function () {
                updateStrength();
                validateConfirmation();
            });

            /* Password confirmation feedback */
            const confirmGroup = confirmation.closest('.form-group');
            const confirmStatus = document.createElement('div');
            confirmStatus.className = 'confirm-status';
            confirmGroup.appendChild(confirmStatus);

            function validateConfirmation() {
                if (!confirmation.value) {
                    confirmStatus.textContent = '';
                    return true;
                }

                if (confirmation.value === password.value) {
                    confirmStatus.innerHTML = '<span style="color:#198754;"><i class="fas fa-check-circle"></i> Password cocok.</span>';
                    return true;
                }

                confirmStatus.innerHTML = '<span style="color:#dc3545;"><i class="fas fa-circle-exclamation"></i> Password belum cocok.</span>';
                return false;
            }

            confirmation.addEventListener('input', validateConfirmation);

            /* Prevent accidental double submit + give immediate feedback */
            form.addEventListener('submit', function (event) {
                if (password.value !== confirmation.value) {
                    event.preventDefault();
                    validateConfirmation();
                    confirmation.focus();
                    return;
                }

                if (form.dataset.submitting === 'true') {
                    event.preventDefault();
                    return;
                }

                form.dataset.submitting = 'true';

                registerButton.disabled = true;
                registerButton.setAttribute('aria-busy', 'true');

                registerButton.innerHTML = `
                    <span
                        class="spinner-border spinner-border-sm"
                        role="status"
                        aria-hidden="true">
                    </span>
                    <span>Mendaftarkan...</span>
                `;

                registerButton.style.cursor = 'wait';
                registerButton.style.opacity = '.88';
            });

            /* Enter = smooth focus progression */
            const fields = Array.from(
                form.querySelectorAll('input:not([type="hidden"]), textarea')
            );

            fields.forEach((field, index) => {
                field.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter' && field.tagName !== 'TEXTAREA') {
                        event.preventDefault();

                        const next = fields[index + 1];

                        if (next) {
                            next.focus();
                        } else {
                            registerButton.focus();
                        }
                    }
                });
            });

            updateStrength();
        });
    </script>

</body>

</html>