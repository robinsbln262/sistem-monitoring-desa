<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pembangunan->nama_pembangunan }} | Desa Kutabuluh</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-kab-karo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo-kab-karo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo-kab-karo.png') }}" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #1e3c72;
            --primary-dark: #0f2748;
            --secondary: #2a5298;
            --accent: #667eea;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-primary: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1f2937;
            --light: #f8fafc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 88px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light);
            overflow-x: hidden;
            overflow-y: auto;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        /* =========================================================
           LOADING SCREEN
        ========================================================= */
        .loading-screen {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient-primary);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity .6s ease, visibility .6s ease;
        }

        .loading-screen.hide {
            opacity: 0;
            visibility: hidden;
        }

        .loader-ultimate {
            text-align: center;
            animation: fadeInScale .6s ease;
            padding: 40px;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .logo-wrapper {
            width: 100px;
            height: 100px;
            margin: 0 auto 25px;
            position: relative;
        }

        .logo-circle {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, .12);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .logo-icon {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulseScale 1.5s ease infinite;
        }

        .logo-icon img {
            width: 58px;
            height: 58px;
            object-fit: contain;
            display: block;
        }

        @keyframes pulseScale {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        .logo-ring {
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, .3);
            border-top-color: white;
            animation: spinRing 1s linear infinite;
        }

        @keyframes spinRing {
            to {
                transform: rotate(360deg);
            }
        }

        .loader-ultimate h3 {
            color: white;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #fff, #ffc107);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .loader-ultimate .subtitle {
            color: rgba(255, 255, 255, .7);
            font-size: .8rem;
            margin-bottom: 25px;
        }

        .progress-container {
            width: 250px;
            margin: 0 auto;
        }

        .progress-bar-custom {
            width: 100%;
            height: 3px;
            background: rgba(255, 255, 255, .2);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            width: 0%;
            height: 100%;
            background: linear-gradient(90deg, #fff, #ffc107);
            border-radius: 10px;
            animation: loadingProgress 1.5s ease infinite;
        }

        @keyframes loadingProgress {
            0% {
                width: 0%;
            }

            50% {
                width: 70%;
            }

            100% {
                width: 100%;
            }
        }

        .loading-text {
            margin-top: 18px;
            color: rgba(255, 255, 255, .7);
            font-size: .8rem;
        }

        .loading-dots {
            display: inline-block;
            width: 40px;
            text-align: left;
        }

        .loading-dots::after {
            content: '...';
            animation: dotsAnim 1.5s steps(4, end) infinite;
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

        /* =========================================================
           NAVBAR
        ========================================================= */
        .navbar-custom {
            position: sticky;
            top: 0;
            z-index: 1050;
            padding: 10px 0;
            background: rgba(255, 255, 255, .88);
            border-bottom: 1px solid rgba(30, 60, 114, .08);
            box-shadow: 0 4px 24px rgba(15, 39, 72, .06);
            backdrop-filter: blur(16px) saturate(150%);
            -webkit-backdrop-filter: blur(16px) saturate(150%);
            transition:
                padding .35s cubic-bezier(.4, 0, .2, 1),
                background .35s ease,
                box-shadow .35s ease,
                border-color .35s ease;
        }

        .navbar-custom.navbar-scrolled {
            padding: 6px 0;
            background: rgba(255, 255, 255, .96);
            border-bottom-color: rgba(30, 60, 114, .12);
            box-shadow: 0 10px 35px rgba(15, 39, 72, .12);
        }

        .navbar-custom > .container {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: space-between !important;
            min-height: 52px;
            width: 100%;
            gap: 15px;
        }

        .navbar-brand {
            position: relative;
            display: inline-flex !important;
            align-items: center;
            gap: 10px;
            padding: 4px 8px 4px 4px !important;
            border-radius: 14px;
            text-decoration: none;
            margin: 0 !important;
            flex-shrink: 0;
            transition: transform .3s ease, background .3s ease;
        }

        .navbar-brand:hover {
            transform: translateY(-1px);
            background: rgba(30, 60, 114, .045);
        }

        .navbar-brand .brand-logo {
            width: 44px;
            height: 44px;
            object-fit: contain;
            display: block;
            background: transparent !important;
            border: 0 !important;
            box-shadow: none !important;
            transition: width .35s ease, height .35s ease, transform .35s ease;
        }

        .navbar-brand:hover .brand-logo {
            transform: scale(1.04);
        }

        .navbar-scrolled .navbar-brand .brand-logo {
            width: 38px;
            height: 38px;
        }

        .navbar-brand span {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .navbar-brand strong {
            font-size: 1.08rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: .55px;
        }

        .navbar-brand small {
            margin-top: 3px;
            font-size: .68rem;
            font-weight: 500;
            color: #64748b;
        }

        .detail-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px;
            border-radius: 999px;
            background: rgba(30, 60, 114, .045);
            border: 1px solid rgba(30, 60, 114, .07);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .8);
            flex-shrink: 0;
            white-space: nowrap;
        }

        .detail-badge .badge {
            display: inline-flex;
            align-items: center;
            min-height: 34px;
            background: var(--gradient-primary) !important;
            box-shadow: 0 5px 14px rgba(30, 60, 114, .18);
            font-weight: 600;
            letter-spacing: .1px;
            transition: transform .3s ease, box-shadow .3s ease;
        }

        .detail-badge:hover .badge {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(30, 60, 114, .24);
        }

        /* =========================================================
           HERO
        ========================================================= */
        .hero-detail {
            background-image: url('{{ asset('images/hero-bg.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            padding: 60px 0;
            position: relative;
            overflow: hidden;
            min-height: 400px;
            display: flex;
            align-items: center;
        }

        .hero-detail::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(30, 60, 114, .4) 0%,
                rgba(42, 82, 152, .3) 100%
            );
            z-index: 1;
        }

        .hero-detail .container {
            position: relative;
            z-index: 2;
        }

        .hero-detail h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
        }

        /* =========================================================
           INFO CARD
        ========================================================= */
        .info-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
            overflow: hidden;
            margin-top: -40px;
            background: white;
        }

        .info-card .card-header {
            background: white;
            border-bottom: 1px solid #eef2f6;
            padding: 18px 25px;
        }

        .info-item {
            padding: 12px 0;
            border-bottom: 1px solid #f0f2f5;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #64748b;
            margin-bottom: 4px;
            font-size: .85rem;
        }

        .info-value {
            font-size: 1rem;
            color: #1e293b;
            font-weight: 500;
        }

        /* =========================================================
           DIMENSI PROYEK
        ========================================================= */
        .project-dimension-card {
            margin-top: 22px;
            padding: 20px;
            border-radius: 17px;
            background: linear-gradient(135deg, #f8fbff 0%, #eef6ff 100%);
            border: 1px solid #dbeafe;
        }

        .project-dimension-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .project-dimension-header > i {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            background: rgba(30, 60, 114, .10);
            color: var(--primary);
            flex-shrink: 0;
        }

        .project-dimension-header strong {
            display: block;
            color: var(--primary);
            font-size: 14px;
        }

        .project-dimension-header small {
            color: #64748b;
            font-size: 11px;
        }

        .dimension-item {
            background: #fff;
            border: 1px solid #e5edf7;
            border-radius: 14px;
            padding: 14px 8px;
            text-align: center;
            height: 100%;
            transition: all .25s ease;
        }

        .dimension-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(30, 60, 114, .08);
        }

        .dimension-icon {
            width: 32px;
            height: 32px;
            margin: 0 auto 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: #eff6ff;
            color: var(--primary);
            font-size: 12px;
        }

        .dimension-label {
            display: block;
            color: #64748b;
            font-size: 10px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .dimension-value {
            display: block;
            color: #1e293b;
            font-size: 16px;
            font-weight: 800;
        }

        .dimension-unit {
            color: #64748b;
            font-size: 10px;
            font-weight: 500;
        }

        .volume-result {
            margin-top: 13px;
            padding: 14px 16px;
            border-radius: 13px;
            background: linear-gradient(135deg, #ecfdf5 0%, #dcfce7 100%);
            border: 1px solid #bbf7d0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .volume-label {
            color: #166534;
            font-size: 12px;
            font-weight: 700;
        }

        .volume-value {
            color: #15803d;
            font-size: 19px;
            font-weight: 800;
            white-space: nowrap;
        }

        .volume-formula {
            margin-top: 8px;
            color: #64748b;
            font-size: 10px;
        }

        /* =========================================================
           PROGRESS
        ========================================================= */
        .progress-section {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 20px;
            padding: 25px;
            margin: 25px 0;
        }

        .progress-circle {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto;
        }

        .progress-circle svg {
            transform: rotate(-90deg);
        }

        .progress-circle circle {
            fill: none;
            stroke-width: 8;
        }

        .progress-circle .bg-circle {
            stroke: #e2e8f0;
        }

        .progress-circle .progress-circle-bar {
            stroke: url(#gradient);
            stroke-linecap: round;
            transition: stroke-dasharray 1s ease;
        }

        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .progress-text h2 {
            font-size: 2rem;
            font-weight: 800;
            margin: 0;
            color: var(--primary);
        }

        .progress-custom {
            height: 6px;
            border-radius: 10px;
            background: #e2e8f0;
        }

        .progress-custom .progress-bar {
            border-radius: 10px;
        }

        /* =========================================================
           TIMELINE / MONITORING
        ========================================================= */
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, #10b981, #3b82f6);
        }

        .timeline-item {
            position: relative;
            padding-bottom: 25px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -26px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #3b82f6;
            border: 2px solid white;
            box-shadow: 0 0 0 2px #3b82f6;
        }

        .timeline-item.completed::before {
            background: #10b981;
            box-shadow: 0 0 0 2px #10b981;
        }

        .monitoring-card {
            border: 1px solid #eef2f6;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all .3s ease;
            background: white;
        }

        .monitoring-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, .08);
            transform: translateY(-2px);
        }

        .monitoring-card .badge-progres {
            font-size: 1.1rem;
            padding: 8px 16px;
        }

        .monitoring-card .foto-thumb {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 12px;
            cursor: pointer;
            transition: transform .3s ease;
        }

        .monitoring-card .foto-thumb:hover {
            transform: scale(1.02);
        }

        .komentar-admin-box {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 12px 16px;
            border-radius: 8px;
            margin-top: 12px;
        }

        .komentar-admin-box i {
            color: #f59e0b;
        }

        /* =========================================================
           ANGGARAN
        ========================================================= */
        .anggaran-ringkasan {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #bbf7d0;
        }

        .anggaran-ringkasan .label {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .anggaran-ringkasan .value {
            font-size: 18px;
            font-weight: 700;
        }

        /* =========================================================
           GALLERY
        ========================================================= */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .gallery-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            aspect-ratio: 1 / 1;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.05);
        }

        .gallery-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity .3s ease;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-overlay i {
            color: white;
            font-size: 24px;
        }

        .gallery-date {
            position: absolute;
            bottom: 8px;
            right: 8px;
            background: rgba(0, 0, 0, .6);
            backdrop-filter: blur(4px);
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 500;
            z-index: 1;
        }

        .keterangan-foto {
            background: linear-gradient(135deg, #f0f9ff 0%, #e6f7ff 100%);
            border-radius: 16px;
            padding: 24px 28px;
            border-left: 4px solid var(--primary);
            margin-top: 8px;
        }

        .keterangan-foto .label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--primary);
            background: rgba(30, 60, 114, .12);
            padding: 6px 14px;
            border-radius: 30px;
            margin-bottom: 16px;
        }

        .keterangan-foto .text {
            font-size: 14px;
            color: #334155;
            line-height: 1.7;
        }

        .keterangan-foto .text p {
            margin-bottom: 14px;
        }

        .keterangan-foto .text strong,
        .keterangan-foto .text b {
            color: var(--primary);
            font-weight: 600;
        }

        .keterangan-foto .text ul,
        .keterangan-foto .text ol {
            margin: 12px 0;
            padding-left: 24px;
        }

        .keterangan-foto .text li {
            margin-bottom: 6px;
        }

        .keterangan-foto .text img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 12px 0;
        }

        /* =========================================================
           KOMENTAR MASYARAKAT
        ========================================================= */
        .komentar-item {
            transition: all .3s ease;
            border-radius: 8px;
        }

        .komentar-item:hover {
            background: #f8fafc;
        }

        .komentar-item:last-child {
            border-bottom: none !important;
        }

        .komentar-foto-thumb {
            max-height: 250px;
            object-fit: cover;
            border-radius: 12px;
            cursor: pointer;
            transition: transform .3s ease;
        }

        .komentar-foto-thumb:hover {
            transform: scale(1.02);
        }

        #formKomentarMasyarakat .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 60, 114, .15);
        }

        #btnKirimKomentarMasyarakat {
            transition: all .3s ease;
        }

        #btnKirimKomentarMasyarakat:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 60, 114, .3);
        }

        .komentar-alert {
            border-radius: 12px;
            border: none;
        }

        /* =========================================================
           EMPTY STATE
        ========================================================= */
        .empty-state {
            padding: 40px 20px;
            text-align: center;
        }

        .empty-state i {
            font-size: 48px;
            color: #cbd5e1;
            margin-bottom: 16px;
        }

        .empty-state h6 {
            color: #64748b;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #94a3b8;
            font-size: 14px;
            margin-bottom: 0;
        }

        /* =========================================================
           MODAL GALLERY
        ========================================================= */
        .modal-gallery {
            display: none;
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .95);
            z-index: 2000;
            cursor: pointer;
        }

        .modal-gallery.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-gallery img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }

        /* =========================================================
           BACK BUTTON
        ========================================================= */
        .btn-back {
            position: fixed;
            bottom: 30px;
            left: 30px;
            width: 48px;
            height: 48px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .2);
            transition: transform .3s cubic-bezier(.4, 0, .2, 1),
                        opacity .3s ease,
                        box-shadow .3s ease;
            z-index: 100;
            text-decoration: none;
        }

        .btn-back:hover {
            transform: translateY(-4px) scale(1.04);
            color: white;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */
        @media (max-width: 992px) {

            .gallery-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 12px;
            }

            .keterangan-foto {
                padding: 18px 22px;
            }
        }

        @media (max-width: 768px) {

            html {
                scroll-padding-top: 72px;
            }

            .navbar-custom {
                padding: 7px 0;
            }

            .navbar-custom.navbar-scrolled {
                padding: 5px 0;
            }

            .navbar-custom > .container {
                min-height: 46px;
                gap: 6px;
            }

            .navbar-brand {
                gap: 7px !important;
                padding-left: 2px !important;
            }

            .navbar-brand .brand-logo {
                width: 36px;
                height: 36px;
            }

            .navbar-scrolled .navbar-brand .brand-logo {
                width: 33px;
                height: 33px;
            }

            .navbar-brand strong {
                font-size: .94rem;
            }

            .navbar-brand small {
                font-size: .58rem;
            }

            .detail-badge {
                padding: 3px;
            }

            .detail-badge .badge {
                min-height: 30px;
                padding: 6px 9px !important;
                font-size: 9px;
            }

            .hero-detail {
                padding: 40px 0;
                min-height: 300px;
            }

            .hero-detail h1 {
                font-size: 1.5rem;
            }

            .hero-detail .lead {
                font-size: .9rem;
            }

            .info-card {
                margin-top: -20px;
            }

            .info-card .card-header {
                padding: 15px 20px;
            }

            .info-card .card-body {
                padding: 20px !important;
            }

            .info-value {
                font-size: .9rem;
            }

            .progress-section {
                padding: 20px;
                margin: 20px 0;
            }

            .progress-circle {
                width: 120px;
                height: 120px;
            }

            .progress-text h2 {
                font-size: 1.5rem;
            }

            .timeline {
                padding-left: 20px;
            }

            .timeline-item::before {
                left: -20px;
                width: 10px;
                height: 10px;
            }

            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }

            .keterangan-foto {
                padding: 16px;
            }

            .keterangan-foto .text {
                font-size: 13px;
            }

            .btn-back {
                width: 40px;
                height: 40px;
                bottom: 20px;
                left: 20px;
            }
        }

        @media (max-width: 576px) {

            .project-dimension-card {
                padding: 14px;
            }

            .dimension-item {
                padding: 10px 5px;
            }

            .dimension-value {
                font-size: 13px;
            }

            .volume-result {
                padding: 11px 12px;
            }

            .volume-value {
                font-size: 15px;
            }

            .volume-label {
                font-size: 10px;
            }
        }

        @media (max-width: 480px) {

            .navbar-brand .brand-logo {
                width: 32px;
                height: 32px;
            }

            .navbar-brand strong {
                font-size: .9rem;
            }

            .navbar-brand small {
                font-size: .55rem;
            }

            .detail-badge .badge {
                font-size: 8px;
                padding: 6px 8px !important;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            html {
                scroll-behavior: auto;
            }

            *,
            *::before {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

    <!-- =========================================================
         LOADING SCREEN
    ========================================================= -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loader-ultimate">
            <div class="logo-wrapper">
                <div class="logo-circle">
                    <div class="logo-icon">
                        <img src="{{ asset('images/logo-kab-karo.png') }}" alt="Logo Kabupaten Karo">
                    </div>
                    <div class="logo-ring"></div>
                </div>
            </div>

            <h3>{{ $pembangunan->nama_pembangunan }}</h3>
            <p class="subtitle">Memuat detail pembangunan</p>

            <div class="progress-container">
                <div class="progress-bar-custom">
                    <div class="progress-fill"></div>
                </div>
            </div>

            <div class="loading-text">
                <span id="loadingMessage">Menyiapkan informasi</span>
                <span class="loading-dots"></span>
            </div>
        </div>
    </div>


    <!-- =========================================================
         NAVBAR
    ========================================================= -->
    <nav class="navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo-kab-karo.png') }}" alt="Logo Kabupaten Karo" class="brand-logo">
                <span>
                    <strong>SISMOLEV</strong>
                    <small>Desa Kutabuluh</small>
                </span>
            </a>

            <div class="detail-badge">
                <span class="badge px-3 py-2 rounded-pill">
                    <i class="fas fa-info-circle me-1"></i>
                    Detail Pembangunan
                </span>
            </div>
        </div>
    </nav>


    <!-- =========================================================
         HERO
    ========================================================= -->
    <section class="hero-detail">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <i class="fas fa-building fa-3x mb-3 opacity-50"></i>
                    <h1 data-aos="fade-up">
                        {{ $pembangunan->nama_pembangunan }}
                    </h1>
                    <p class="lead opacity-75" data-aos="fade-up" data-aos-delay="100">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        {{ $pembangunan->lokasi }}
                    </p>
                </div>
            </div>
        </div>
    </section>


    <!-- =========================================================
         MAIN CONTENT
    ========================================================= -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <!-- =================================================
                     INFORMASI PEMBANGUNAN
                ================================================== -->
                <div class="info-card animate-slide-up" data-aos="fade-up">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Informasi Pembangunan
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            <!-- KIRI -->
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-calendar text-primary me-2"></i>
                                        Tahun Anggaran
                                    </div>
                                    <div class="info-value">
                                        {{ $pembangunan->tahun_anggaran }}
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-money-bill text-primary me-2"></i>
                                        Anggaran
                                    </div>
                                    <div class="info-value fw-bold text-success">
                                        Rp {{ number_format($pembangunan->anggaran, 0, ',', '.') }}
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-hand-holding-usd text-primary me-2"></i>
                                        Sumber Dana
                                    </div>
                                    <div class="info-value">
                                        {{ $pembangunan->sumber_dana }}
                                    </div>
                                </div>
                            </div>

                            <!-- KANAN -->
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-play-circle text-primary me-2"></i>
                                        Tanggal Mulai
                                    </div>
                                    <div class="info-value">
                                        {{ \Carbon\Carbon::parse($pembangunan->tanggal_mulai)->locale('id')->isoFormat('D MMMM Y') }}
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-check-circle text-primary me-2"></i>
                                        Tanggal Selesai
                                    </div>
                                    <div class="info-value">
                                        {{ \Carbon\Carbon::parse($pembangunan->tanggal_selesai)->locale('id')->isoFormat('D MMMM Y') }}
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-chart-line text-primary me-2"></i>
                                        Status
                                    </div>
                                    <div class="info-value">
                                        {!! $pembangunan->status_badge !!}
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- =================================================
                             DIMENSI PROYEK
                        ================================================== -->
                        @php
                            $panjang = $pembangunan->panjang ?? null;
                            $lebar = $pembangunan->lebar ?? null;
                            $ketebalanTinggi = $pembangunan->ketebalan_tinggi ?? null;

                            $ketebalanMeter = $ketebalanTinggi !== null ? (float) $ketebalanTinggi : null;
                            $ketebalanCm = $ketebalanMeter !== null ? $ketebalanMeter * 100 : null;
                            $hasDimension = $panjang !== null && $lebar !== null && $ketebalanTinggi !== null;

                            $volume = $pembangunan->volume_proyek ?? null;
                            if ($volume === null && $hasDimension) {
                                $volume = (float) $panjang * (float) $lebar * (float) $ketebalanTinggi;
                            }
                        @endphp

                        @if($hasDimension)
                            <div class="project-dimension-card">
                                <div class="project-dimension-header">
                                    <i class="fas fa-ruler-combined"></i>
                                    <div>
                                        <strong>Ukuran / Dimensi Proyek</strong>
                                        <small>Dimensi pekerjaan pembangunan</small>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-4">
                                        <div class="dimension-item">
                                            <div class="dimension-icon">
                                                <i class="fas fa-arrows-alt-h"></i>
                                            </div>
                                            <span class="dimension-label">Panjang</span>
                                            <span class="dimension-value">
                                                {{ rtrim(rtrim(number_format((float) $panjang, 2, ',', '.'), '0'), ',') }}
                                            </span>
                                            <span class="dimension-unit">meter</span>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="dimension-item">
                                            <div class="dimension-icon">
                                                <i class="fas fa-arrows-alt-h"></i>
                                            </div>
                                            <span class="dimension-label">Lebar</span>
                                            <span class="dimension-value">
                                                {{ rtrim(rtrim(number_format((float) $lebar, 2, ',', '.'), '0'), ',') }}
                                            </span>
                                            <span class="dimension-unit">meter</span>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="dimension-item">
                                            <div class="dimension-icon">
                                                <i class="fas fa-arrows-alt-v"></i>
                                            </div>
                                            <span class="dimension-label">Ketebalan / Tinggi</span>
                                            <span class="dimension-value">
                                                {{ rtrim(rtrim(number_format($ketebalanMeter, 2, ',', '.'), '0'), ',') }}
                                            </span>
                                            <span class="dimension-unit">
                                                meter
                                                <small class="d-block mt-1 text-muted">
                                                    ({{ rtrim(rtrim(number_format($ketebalanCm, 2, ',', '.'), '0'), ',') }} cm)
                                                </small>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                @if($volume !== null)
                                    <div class="volume-result">
                                        <div class="volume-label">
                                            <i class="fas fa-cube me-1"></i> Volume Pekerjaan
                                        </div>
                                        <div class="volume-value">
                                            {{ rtrim(rtrim(number_format((float) $volume, 2, ',', '.'), '0'), ',') }} m³
                                        </div>
                                    </div>
                                    <div class="volume-formula">
                                        <i class="fas fa-calculator me-1"></i> Volume = Panjang × Lebar × Ketebalan/Tinggi
                                        <span class="d-block mt-1">
                                            Ketebalan/tinggi yang disimpan dalam database menggunakan meter; nilai CM merupakan hasil konversi otomatis.
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @elseif($pembangunan->volume_proyek !== null)
                            <div class="project-dimension-card">
                                <div class="project-dimension-header">
                                    <i class="fas fa-ruler-combined"></i>
                                    <div>
                                        <strong>Volume Proyek</strong>
                                        <small>Informasi volume pekerjaan</small>
                                    </div>
                                </div>
                                <div class="volume-result mt-0">
                                    <div class="volume-label">
                                        <i class="fas fa-cube me-1"></i> Volume
                                    </div>
                                    <div class="volume-value">
                                        {{ rtrim(rtrim(number_format((float) $pembangunan->volume_proyek, 2, ',', '.'), '0'), ',') }}
                                        {{ $pembangunan->satuan_volume ?? 'm³' }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>


                <!-- =================================================
                     PROGRESS SECTION
                ================================================== -->
                <div class="progress-section" data-aos="fade-up" data-aos-delay="200">
                    <div class="row align-items-center">
                        <div class="col-md-5 text-center">
                            <div class="progress-circle">
                                <svg width="150" height="150">
                                    <defs>
                                        <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" style="stop-color:#667eea" />
                                            <stop offset="100%" style="stop-color:#764ba2" />
                                        </linearGradient>
                                    </defs>
                                    <circle class="bg-circle" cx="75" cy="75" r="65"></circle>
                                    <circle class="progress-circle-bar" cx="75" cy="75" r="65"
                                        stroke-dasharray="408.4"
                                        stroke-dashoffset="{{ 408.4 - (408.4 * $pembangunan->progress) / 100 }}"
                                        stroke="url(#gradient)">
                                    </circle>
                                </svg>
                                <div class="progress-text">
                                    <h2>{{ $pembangunan->progress }}<span style="font-size:1rem;">%</span></h2>
                                    <small class="text-muted">Progres</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            @if($pembangunan->status == 'direncanakan')
                                <div class="alert alert-secondary mb-0 border-0 rounded-3">
                                    <i class="fas fa-clock me-2"></i>
                                    Pembangunan masih dalam tahap perencanaan dan akan segera dimulai.
                                </div>
                            @elseif($pembangunan->status == 'berjalan')
                                <div class="alert alert-warning mb-0 border-0 rounded-3">
                                    <i class="fas fa-spinner fa-spin me-2"></i>
                                    Pembangunan sedang berjalan dengan progres {{ $pembangunan->progress }}%. Tetap pantau perkembangan terbaru.
                                </div>
                            @else
                                <div class="alert alert-success mb-0 border-0 rounded-3">
                                    <i class="fas fa-trophy me-2"></i>
                                    Pembangunan telah selesai dilaksanakan. Terima kasih atas dukungannya!
                                </div>
                            @endif
                        </div>
                    </div>
                </div>


                <!-- =================================================
                     RINGKASAN ANGGARAN
                ================================================== -->
                @php
                    $totalDanaTerpakai = $pembangunan->monitorings->where('status_verifikasi', 'approved')->sum('dana_terpakai');
                    $sisaAnggaran = $pembangunan->anggaran - $totalDanaTerpakai;
                    $approvedCount = $pembangunan->monitorings->where('status_verifikasi', 'approved')->count();
                @endphp

                @if($approvedCount > 0)
                    <div class="row mb-4" data-aos="fade-up" data-aos-delay="250">
                        <div class="col-md-4 mb-2">
                            <div class="anggaran-ringkasan text-center">
                                <div class="label">Total Anggaran</div>
                                <div class="value text-primary">
                                    Rp {{ number_format($pembangunan->anggaran, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <div class="anggaran-ringkasan text-center">
                                <div class="label">Dana Terpakai</div>
                                <div class="value text-success">
                                    Rp {{ number_format($totalDanaTerpakai, 0, ',', '.') }}
                                </div>
                                <small class="text-muted">{{ $approvedCount }} tahap terverifikasi</small>
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <div class="anggaran-ringkasan text-center {{ $sisaAnggaran > 0 ? '' : 'bg-danger bg-opacity-10 border-danger' }}">
                                <div class="label">Sisa Anggaran</div>
                                <div class="value {{ $sisaAnggaran > 0 ? 'text-warning' : 'text-danger' }}">
                                    Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}
                                </div>
                                <small class="text-muted">
                                    {{ $pembangunan->anggaran > 0 ? round(($totalDanaTerpakai / $pembangunan->anggaran) * 100, 1) : 0 }}% terpakai
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4" data-aos="fade-up" data-aos-delay="300">
                        @php
                            $persentaseAnggaran = $pembangunan->anggaran > 0 ? ($totalDanaTerpakai / $pembangunan->anggaran) * 100 : 0;
                            $classBar = $persentaseAnggaran >= 100 ? 'bg-danger' : ($persentaseAnggaran >= 80 ? 'bg-warning' : 'bg-success');
                        @endphp

                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-semibold small">Penggunaan Anggaran</span>
                            <span class="fw-semibold small text-primary">{{ round($persentaseAnggaran, 1) }}%</span>
                        </div>
                        <div class="progress" style="height:12px;">
                            <div class="progress-bar {{ $classBar }} fw-bold" style="width:{{ min($persentaseAnggaran, 100) }}%;">
                                {{ number_format($persentaseAnggaran, 1) }}%
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">Rp 0</small>
                            <small class="text-muted">Rp {{ number_format($pembangunan->anggaran, 0, ',', '.') }}</small>
                        </div>
                    </div>
                @endif


                <!-- =================================================
                     MONITORING
                ================================================== -->
                @php
                    $approvedMonitorings = $pembangunan->monitorings->where('status_verifikasi', 'approved');
                @endphp

                @if($approvedMonitorings && $approvedMonitorings->count() > 0)
                    <div class="info-card mt-4" data-aos="fade-up" data-aos-delay="350">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-clipboard-list text-primary me-2"></i>
                                Detail Monitoring & Progres
                                <span class="badge bg-success ms-2 rounded-pill">
                                    <i class="fas fa-check-circle me-1"></i> Terverifikasi
                                </span>
                            </h5>
                            <span class="badge bg-primary rounded-pill px-3 py-2">
                                <i class="fas fa-list me-1"></i> {{ $approvedMonitorings->count() }} Tahap
                            </span>
                        </div>

                        <div class="card-body p-4">
                            @foreach($approvedMonitorings->sortBy('tanggal_monitoring') as $index => $m)
                                <div class="monitoring-card" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                                        <div>
                                            <h6 class="mb-1">
                                                <span class="badge bg-primary rounded-pill me-2 badge-progres">
                                                    <i class="fas fa-percent me-1"></i> {{ $m->progres }}%
                                                </span>
                                                <span class="fw-semibold">{{ $m->status_progres }}</span>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ \Carbon\Carbon::parse($m->tanggal_monitoring)->locale('id')->translatedFormat('d F Y') }}
                                            </small>
                                        </div>

                                        <div>
                                            @if($m->dana_terpakai)
                                                <span class="badge bg-success px-3 py-2">
                                                    <i class="fas fa-money-bill-wave me-1"></i>
                                                    Rp {{ number_format($m->dana_terpakai, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="progress-custom" style="height:8px;">
                                            <div class="progress-bar bg-success" style="width:{{ $m->progres }}%;"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <small class="text-muted">0%</small>
                                            <small class="text-muted">100%</small>
                                        </div>
                                    </div>

                                    @if($m->keterangan)
                                        <div class="mb-2">
                                            <small class="text-muted"><i class="fas fa-align-left me-1"></i> Keterangan:</small>
                                            <p class="mb-0 small">{{ strip_tags($m->keterangan) }}</p>
                                        </div>
                                    @endif

                                    @if($m->komentar_admin)
                                        <div class="komentar-admin-box">
                                            <small><i class="fas fa-user-shield me-1"></i> <strong>Komentar Admin:</strong></small>
                                            <p class="mb-0 mt-1">{{ $m->komentar_admin }}</p>
                                        </div>
                                    @endif

                                    @if($m->foto_before || $m->foto_after)
                                        <div class="row mt-3 g-3">
                                            @if($m->foto_before)
                                                <div class="col-md-6">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-header bg-secondary text-white py-1">
                                                            <small><i class="fas fa-camera me-1"></i> SEBELUM (Before)</small>
                                                        </div>
                                                        <img src="{{ $m->foto_before_url }}" class="foto-thumb"
                                                            onclick="showLightbox('{{ $m->foto_before_url }}', 'Foto Sebelum - {{ $m->status_progres }}')"
                                                            alt="Foto Sebelum" onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">
                                                        <div class="card-footer bg-transparent text-center py-1">
                                                            <small class="text-muted">Klik untuk memperbesar</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($m->foto_after)
                                                <div class="col-md-6">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-header bg-success text-white py-1">
                                                            <small><i class="fas fa-camera me-1"></i> SESUDAH (After)</small>
                                                        </div>
                                                        <img src="{{ $m->foto_after_url }}" class="foto-thumb"
                                                            onclick="showLightbox('{{ $m->foto_after_url }}', 'Foto Sesudah - {{ $m->status_progres }}')"
                                                            alt="Foto Sesudah" onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">
                                                        <div class="card-footer bg-transparent text-center py-1">
                                                            <small class="text-muted">Klik untuk memperbesar</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i> Dilaporkan pada:
                                            {{ \Carbon\Carbon::parse($m->created_at)->locale('id')->translatedFormat('d F Y H:i') }}
                                        </small>
                                    </div>

                                    @if(!$loop->last)
                                        <hr class="my-4">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="info-card mt-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-clipboard-list text-primary me-2"></i> Detail Monitoring & Progres
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="empty-state">
                                <i class="fas fa-clipboard-list"></i>
                                <h6>Belum Ada Data Monitoring Terverifikasi</h6>
                                <p>Monitoring akan muncul setelah diverifikasi oleh Admin.</p>
                            </div>
                        </div>
                    </div>
                @endif


                <!-- =================================================
                     REKANAN / MANDOR
                ================================================== -->
                @php
                    $rekananTerpilih = null;
                    if($pembangunan->rekanan_terpilih) {
                        $rekananTerpilih = App\Models\User::find($pembangunan->rekanan_terpilih);
                    }

                    $hargaDeal = null;
                    if($pembangunan->rekanan_terpilih) {
                        $rekananProyek = App\Models\RekananProyek::where('id_proyek', $pembangunan->id_pembangunan)
                            ->where('id_rekanan', $pembangunan->rekanan_terpilih)
                            ->first();

                        if($rekananProyek) {
                            $hargaDeal = $rekananProyek->harga_penawaran_rekanan;
                        }
                    }
                @endphp

                @if($rekananTerpilih)
                    <div class="info-card mt-4" data-aos="fade-up" data-aos-delay="400">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-user-tie me-2"></i> Rekanan / Mandor Pelaksana
                            </h5>
                        </div>

                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td width="40%" class="text-muted">
                                                <i class="fas fa-user text-primary me-2"></i> Nama
                                            </td>
                                            <td class="fw-bold">{{ $rekananTerpilih->nama_user }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <i class="fas fa-phone text-primary me-2"></i> Telepon
                                            </td>
                                            <td>{{ $rekananTerpilih->no_telp ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <i class="fas fa-tools text-primary me-2"></i> Bidang Keahlian
                                            </td>
                                            <td>{{ $rekananTerpilih->bidang_keahlian ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <i class="fas fa-map-marker-alt text-primary me-2"></i> Alamat
                                            </td>
                                            <td>{{ $rekananTerpilih->alamat ?? '-' }}</td>
                                        </tr>
                                        @if($hargaDeal)
                                            <tr>
                                                <td class="text-muted">
                                                    <i class="fas fa-handshake text-primary me-2"></i> Harga Deal
                                                </td>
                                                <td class="fw-bold text-success">
                                                    Rp {{ number_format($hargaDeal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    @if($rekananTerpilih->cv)
                                        <div class="alert alert-info border-0 rounded-3 h-100 d-flex flex-column justify-content-center">
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="fas fa-file-pdf fa-3x text-danger me-3"></i>
                                                <div>
                                                    <strong>Curriculum Vitae (CV)</strong><br>
                                                    <small class="text-muted">Klik tombol di bawah untuk melihat CV lengkap rekanan</small>
                                                </div>
                                            </div>
                                            <a href="{{ asset('uploads/cv_rekanan/' . basename($rekananTerpilih->cv)) }}"
                                               target="_blank" rel="noopener noreferrer" class="btn btn-danger w-100 rounded-3">
                                                <i class="fas fa-file-pdf me-2"></i> Lihat CV / Profil Rekanan
                                            </a>
                                        </div>
                                    @else
                                        <div class="alert alert-secondary border-0 rounded-3 h-100 d-flex align-items-center justify-content-center">
                                            <div>
                                                <i class="fas fa-info-circle fa-2x text-muted mb-2 d-block"></i>
                                                CV rekanan belum diupload
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-secondary border-0 rounded-3 mt-4">
                        <i class="fas fa-info-circle me-2"></i> Belum ada rekanan yang ditunjuk untuk proyek ini
                    </div>
                @endif


                <!-- =================================================
                     GALLERY
                ================================================== -->
                @if($pembangunan->dokumentasis && $pembangunan->dokumentasis->count() > 0)
                    <div class="info-card mt-4" data-aos="fade-up" data-aos-delay="450">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-camera text-primary me-2"></i> Galeri Dokumentasi
                                <span class="badge bg-primary rounded-pill ms-2">
                                    {{ $pembangunan->dokumentasis->count() }} Foto
                                </span>
                            </h5>
                        </div>

                        <div class="card-body p-4">
                            <div class="gallery-grid">
                                @foreach($pembangunan->dokumentasis as $dok)
                                    <div class="gallery-item" onclick="openGallery('{{ asset('storage/' . $dok->foto) }}')">
                                        <img src="{{ asset('storage/' . $dok->foto) }}" alt="Dokumentasi" loading="lazy">
                                        <div class="gallery-date">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ \Carbon\Carbon::parse($dok->tanggal_upload)->format('d/m/Y') }}
                                        </div>
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @php
                                $firstDok = $pembangunan->dokumentasis->first();
                                $keterangan = $firstDok ? $firstDok->keterangan : null;
                                $allowedTags = '<p><br><b><strong><i><em><u><ul><ol><li><h1><h2><h3><h4><h5><h6><span><div><img><a></td><thead><tbody><tr><td><th><font><hr><blockquote><code><pre>';
                            @endphp

                            @if($keterangan)
                                <div class="keterangan-foto">
                                    <div class="label">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Keterangan Foto</span>
                                    </div>
                                    <div class="text">
                                        {!! strip_tags($keterangan, $allowedTags) !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif


                <!-- =================================================
                     KOMENTAR MASYARAKAT
                ================================================== -->
                <div class="info-card mt-4" data-aos="fade-up" data-aos-delay="500" id="komentar-section">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-comments text-primary me-2"></i> Komentar Masyarakat
                            <span class="badge bg-primary rounded-pill ms-2" id="komentarCount">
                                {{ $pembangunan->komentarMasyarakatApproved->count() }}
                            </span>
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        @if(session('success_komentar'))
                            <div class="alert alert-success komentar-alert alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success_komentar') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error_komentar'))
                            <div class="alert alert-danger komentar-alert alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error_komentar') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger komentar-alert alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif


                        <!-- FORM KOMENTAR -->
                        <div class="mb-4 p-3 bg-light rounded-3">
                            <h6 class="mb-3">
                                <i class="fas fa-pen text-primary me-2"></i> Beri Komentar
                            </h6>

                            <form action="{{ route('komentar-masyarakat.store') }}" method="POST" id="formKomentarMasyarakat" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id_pembangunan" value="{{ $pembangunan->id_pembangunan }}">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">
                                            Nama Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="nama" class="form-control rounded-3 @error('nama') is-invalid @enderror"
                                            placeholder="Masukkan nama Anda" required value="{{ old('nama') }}">
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Email (Opsional)</label>
                                        <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror"
                                            placeholder="email@example.com" value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold small">
                                            Komentar <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="komentar" class="form-control rounded-3 @error('komentar') is-invalid @enderror"
                                            rows="3" placeholder="Tulis komentar atau saran Anda..." required>{{ old('komentar') }}</textarea>
                                        @error('komentar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- INPUT FITUR UPLOAD FOTO DOKUMENTASI -->
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small">
                                            Upload Foto Bukti/Dokumentasi Lapangan (Opsional)
                                        </label>
                                        <input type="file" name="foto" class="form-control rounded-3 @error('foto') is-invalid @enderror" accept="image/*">
                                        @error('foto')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted d-block mt-1">
                                            <i class="fas fa-info-circle me-1"></i> Format: JPG, JPEG, PNG | Maksimal 5MB
                                        </small>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary rounded-3 px-4" id="btnKirimKomentarMasyarakat">
                                            <i class="fas fa-paper-plane me-2"></i> Kirim Komentar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>


                        <!-- DAFTAR KOMENTAR -->
                        <div id="daftarKomentar">
                            @if($pembangunan->komentarMasyarakatApproved && $pembangunan->komentarMasyarakatApproved->count() > 0)
                                @foreach($pembangunan->komentarMasyarakatApproved as $km)
                                    <div class="komentar-item p-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong class="text-primary">
                                                    <i class="fas fa-user-circle me-1"></i> {{ $km->nama }}
                                                </strong>
                                                <span class="badge bg-success ms-2">✓ Terverifikasi</span>
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $km->tanggal_komentar->locale('id')->translatedFormat('d F Y H:i') }}
                                            </small>
                                        </div>

                                        <p class="mb-0 mt-2">
                                            {{ $km->komentar }}
                                        </p>

                                        <!-- TAMPILKAN FOTO BUKTI DOKUMENTASI JIKA ADA -->
                                        @if($km->foto)
                                            <div class="mt-3">
                                                <img src="{{ asset('uploads/komentar_masyarakat/' . $km->foto) }}"
                                                     alt="Foto Bukti Masyarakat"
                                                     class="img-fluid rounded-3 shadow-sm komentar-foto-thumb"
                                                     onclick="showLightbox('{{ asset('uploads/komentar_masyarakat/' . $km->foto) }}', 'Bukti Lapangan oleh: {{ $km->nama }}')">
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-comment-slash fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">Belum ada komentar dari masyarakat.</p>
                                    <small class="text-muted">Jadilah yang pertama memberikan komentar!</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- =========================================================
         MODAL GALLERY
    ========================================================= -->
    <div class="modal-gallery" id="galleryModal" onclick="closeGallery()">
        <img id="galleryImage" src="" alt="Gallery">
        <button class="btn btn-light rounded-circle position-absolute top-50 end-0 translate-middle-y me-3"
            onclick="event.stopPropagation(); closeGallery()" style="width:40px;height:40px;">
            <i class="fas fa-times"></i>
        </button>
    </div>


    <!-- =========================================================
         BACK BUTTON
    ========================================================= -->
    <a href="{{ route('home') }}" class="btn-back" title="Kembali ke Beranda">
        <i class="fas fa-arrow-left"></i>
    </a>


    <!-- =========================================================
         SCRIPTS
    ========================================================= -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <script>
        $(document).ready(function () {
            /* =====================================================
               NAVBAR SCROLL
            ===================================================== */
            const navbar = document.querySelector('.navbar-custom');

            function updateNavbar() {
                if (!navbar) return;
                navbar.classList.toggle('navbar-scrolled', window.scrollY > 18);
            }

            let navTicking = false;
            window.addEventListener('scroll', function () {
                if (!navTicking) {
                    window.requestAnimationFrame(function () {
                        updateNavbar();
                        navTicking = false;
                    });
                    navTicking = true;
                }
            }, { passive: true });

            updateNavbar();


            /* =====================================================
               SMOOTH SCROLL
            ===================================================== */
            document.querySelectorAll('a[href^="#"]').forEach(function (link) {
                link.addEventListener('click', function (e) {
                    const targetId = this.getAttribute('href');
                    if (!targetId || targetId === '#') return;

                    const target = document.querySelector(targetId);
                    if (!target) return;

                    e.preventDefault();
                    const navHeight = navbar ? navbar.offsetHeight : 0;
                    const targetTop = target.getBoundingClientRect().top + window.pageYOffset - navHeight - 14;

                    window.scrollTo({
                        top: Math.max(0, targetTop),
                        behavior: 'smooth'
                    });
                });
            });


            /* =====================================================
               AOS
            ===================================================== */
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    once: true
                });
            }


            /* =====================================================
               LOADING SCREEN
            ===================================================== */
            const loadingScreen = document.getElementById('loadingScreen');
            const loadingMessage = document.getElementById('loadingMessage');
            const messages = [
                'Menyiapkan informasi',
                'Memuat data pembangunan',
                'Menampilkan dokumentasi'
            ];

            let msgIndex = 0;
            const msgInterval = setInterval(function () {
                msgIndex++;
                if (msgIndex < messages.length && loadingMessage) {
                    loadingMessage.textContent = messages[msgIndex];
                }
            }, 600);

            $(window).on('load', function () {
                setTimeout(function () {
                    clearInterval(msgInterval);
                    if (loadingScreen) {
                        loadingScreen.classList.add('hide');
                    }
                }, 1800);
            });


            /* =====================================================
               PROGRESS CIRCLE
            ===================================================== */
            $(window).on('load', function () {
                $('.progress-circle-bar').each(function () {
                    const dashoffset = $(this).attr('stroke-dashoffset');
                    $(this).css('stroke-dashoffset', dashoffset);
                });
            });


            /* =====================================================
               AUTO CLOSE ALERT
            ===================================================== */
            setTimeout(function () {
                $('.komentar-alert').fadeOut('slow');
            }, 5000);
        });


        /* =========================================================
           GALLERY
        ========================================================= */
        function openGallery(src) {
            $('#galleryImage').attr('src', src);
            $('#galleryModal').addClass('active');
            $('body').css('overflow', 'hidden');
        }

        function closeGallery() {
            $('#galleryModal').removeClass('active');
            $('body').css('overflow', 'auto');
        }


        /* =========================================================
           LIGHTBOX MONITORING / FOTO BUKTI
        ========================================================= */
        function showLightbox(imageUrl, caption) {
            const modalHtml = `
                <div class="modal fade" id="lightboxModal" tabindex="-1">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content bg-transparent border-0">
                            <div class="modal-body text-center p-0">
                                <button type="button" class="btn btn-light btn-lg rounded-circle position-absolute top-0 end-0 m-3"
                                    data-bs-dismiss="modal" style="z-index:1060; width:45px; height:45px;">
                                    <i class="fas fa-times"></i>
                                </button>
                                <img src="${imageUrl}" alt="Full Size" style="max-width:95%; max-height:85vh; border-radius:16px; box-shadow:0 25px 50px rgba(0,0,0,.3);">
                                <p class="text-white mt-3 fs-5">${caption || ''}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            $('#lightboxModal').remove();
            $('body').append(modalHtml);
            $('#lightboxModal').modal('show');
        }


        /* =========================================================
           ESCAPE KEY
        ========================================================= */
        $(document).on('keydown', function (e) {
            if (e.key === 'Escape' && $('#galleryModal').hasClass('active')) {
                closeGallery();
            }

            if (e.key === 'Escape' && $('#lightboxModal').hasClass('show')) {
                $('#lightboxModal').modal('hide');
            }
        });
    </script>
</body>

</html>