{{-- VIEW LANDING PAGE: Halaman publik utama berisi profil layanan Bimbingan Konseling SMKN 2 Guguak --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIKS - Sistem Informasi Layanan Konseling Siswa SMKN 2 Guguak</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo_smk.png') }}">

    <!-- Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    <style>
        :root {
            --primary: #1B4D3E;
            --primary-dark: #0F2D24;
            --primary-light: #2D6A4F;
            --accent-gold: #D4AF37;
            --accent-gold-hover: #B89628;
            --bg-main: #F8FAFC;
            --card-bg: #FFFFFF;
            --text-dark: #0F172A;
            --text-muted: #64748B;
            --border-color: #E2E8F0;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 14px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        html {
            scroll-behavior: smooth;
        }

        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-main);
            color: var(--text-dark);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* Container */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }

        /* Navbar Header */
        .navbar {
            background-color: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 76px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            text-decoration: none;
        }

        .brand-img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.12));
            transition: transform 0.2s ease;
        }

        .brand:hover .brand-img {
            transform: scale(1.05);
        }

        .brand-title {
            font-weight: 800;
            font-size: 1.125rem;
            color: var(--primary-dark);
            line-height: 1.2;
            letter-spacing: -0.01em;
        }

        .brand-subtitle {
            font-size: 0.725rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
        }

        .nav-link {
            text-decoration: none;
            color: var(--text-dark);
            font-size: 0.875rem;
            font-weight: 600;
            transition: color 0.2s ease;
            position: relative;
            padding: 0.25rem 0;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary);
            transition: width 0.25s ease;
            border-radius: 2px;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.65rem 1.4rem;
            font-size: 0.875rem;
            font-weight: 700;
            border-radius: 0.5rem;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #FFFFFF;
            box-shadow: 0 2px 6px rgba(27, 77, 62, 0.2);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(27, 77, 62, 0.3);
        }

        .btn-gold {
            background-color: var(--accent-gold);
            color: #0F2D24;
            box-shadow: 0 2px 6px rgba(212, 175, 55, 0.3);
        }

        .btn-gold:hover {
            background-color: var(--accent-gold-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(212, 175, 55, 0.4);
        }

        .btn-outline {
            background-color: transparent;
            border-color: rgba(255, 255, 255, 0.35);
            color: #FFFFFF;
        }

        .btn-outline:hover {
            background-color: rgba(255, 255, 255, 0.12);
            border-color: #FFFFFF;
            transform: translateY(-1px);
        }

        .btn-lg {
            padding: 0.8rem 1.75rem;
            font-size: 0.95rem;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #0A241C 0%, var(--primary) 50%, var(--primary-light) 100%);
            color: #FFFFFF;
            padding: 5rem 0 6rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
            pointer-events: none;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 3.5rem;
            align-items: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(212, 175, 55, 0.18);
            color: #F3E5AB;
            border: 1px solid rgba(212, 175, 55, 0.4);
            padding: 0.4rem 1rem;
            border-radius: 9999px;
            font-size: 0.775rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(4px);
        }

        .hero-title {
            font-size: clamp(2.1rem, 4vw, 2.9rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.25rem;
            color: #FFFFFF;
            letter-spacing: -0.02em;
        }

        .hero-description {
            font-size: 1.05rem;
            color: rgba(255, 255, 255, 0.88);
            margin-bottom: 2.25rem;
            max-width: 620px;
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .hero-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        }

        .hero-card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #FFFFFF;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }

        .stat-box {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 0.75rem;
            padding: 1.25rem;
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .stat-box:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.1);
        }

        .stat-number {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--accent-gold);
            line-height: 1;
            margin-bottom: 0.35rem;
        }

        .stat-label {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.82);
            font-weight: 600;
        }

        /* Section General */
        .section {
            padding: 5rem 0;
        }

        .section-header {
            text-align: center;
            max-width: 720px;
            margin: 0 auto 3.5rem auto;
        }

        .section-tag {
            font-size: 0.775rem;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.6rem;
            display: inline-block;
            background: #E6F4EA;
            padding: 0.3rem 0.8rem;
            border-radius: 9999px;
        }

        .section-title {
            font-size: clamp(1.65rem, 3vw, 2.2rem);
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 0.875rem;
            letter-spacing: -0.01em;
        }

        .section-description {
            font-size: 1rem;
            color: var(--text-muted);
            line-height: 1.65;
        }

        /* Services Cards Grid */
        .grid-3 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.75rem;
        }

        .service-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.875rem;
            padding: 2rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow-sm);
        }

        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        .service-icon-box {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #E6F4EA 0%, #CEEAD6 100%);
            color: var(--primary);
            border-radius: 0.625rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.15rem;
            margin-bottom: 1.35rem;
            box-shadow: inset 0 0 0 1px rgba(27, 77, 62, 0.1);
        }

        .service-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.75rem;
        }

        .service-desc {
            font-size: 0.875rem;
            color: var(--text-muted);
            line-height: 1.65;
        }

        /* Workflow Steps */
        .workflow-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
        }

        .step-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.875rem;
            padding: 1.85rem;
            position: relative;
            border-top: 4px solid var(--primary);
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s ease;
        }

        .step-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .step-number {
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--accent-gold);
            background-color: var(--primary-dark);
            display: inline-block;
            padding: 0.25rem 0.7rem;
            border-radius: 0.375rem;
            margin-bottom: 1.1rem;
            letter-spacing: 0.03em;
        }

        .step-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }

        .step-desc {
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* Ethics Banner */
        .ethics-banner {
            background: linear-gradient(135deg, #FFFFFF 0%, #F1F5F9 100%);
            border: 1px solid var(--border-color);
            border-left: 5px solid var(--primary);
            border-radius: 0.875rem;
            padding: 2.25rem;
            margin-top: 3rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
            box-shadow: var(--shadow-md);
        }

        .ethics-text h4 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.4rem;
        }

        .ethics-text p {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* Footer */
        .footer {
            background-color: var(--primary-dark);
            color: #FFFFFF;
            padding: 4.5rem 0 2rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 3.5rem;
            margin-bottom: 3.5rem;
        }

        .footer-brand-title {
            font-size: 1.15rem;
            font-weight: 800;
            margin-bottom: 0.875rem;
            color: #FFFFFF;
            letter-spacing: -0.01em;
        }

        .footer-text {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.75);
            line-height: 1.7;
        }

        .footer-heading {
            font-size: 0.875rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--accent-gold);
            margin-bottom: 1.35rem;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.7rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            display: inline-block;
        }

        .footer-links a:hover {
            color: #FFFFFF;
            transform: translateX(3px);
        }

        .footer-bottom {
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            font-size: 0.825rem;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Mobile Navbar Hamburger & Offcanvas Menu */
        .hamburger-btn {
            display: none;
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.5rem 0.65rem;
            cursor: pointer;
            color: var(--primary-dark);
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s ease;
        }

        .hamburger-btn:hover {
            background-color: #F1F5F9;
        }

        /* Mobile Responsive Breakpoints (Android, Tablet, Small Desktop) */
        @media (max-width: 991.98px) {
            .hero-grid { 
                grid-template-columns: 1fr; 
                gap: 2.5rem;
            }

            .footer-grid { 
                grid-template-columns: 1fr; 
                gap: 2.5rem;
            }

            .ethics-banner {
                flex-direction: column;
                align-items: flex-start;
                gap: 1.5rem;
            }
        }

        @media (max-width: 850px) {
            .hamburger-btn {
                display: inline-flex;
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 76px;
                left: 0;
                width: 100%;
                background-color: #FFFFFF;
                border-bottom: 1px solid var(--border-color);
                box-shadow: var(--shadow-lg);
                flex-direction: column;
                padding: 1.25rem 1.5rem;
                gap: 1.25rem;
                z-index: 999;
                align-items: flex-start;
            }

            .nav-links.show {
                display: flex;
                animation: slideDown 0.25s ease forwards;
            }

            .nav-link {
                width: 100%;
                font-size: 0.95rem;
                padding: 0.4rem 0;
            }
        }

        @media (max-width: 575.98px) {
            .hero {
                padding: 3.5rem 0 4.5rem 0;
            }

            .section {
                padding: 3.5rem 0;
            }

            .hero-actions {
                flex-direction: column;
                width: 100%;
            }

            .hero-actions .btn {
                width: 100%;
            }

            .grid-3 {
                grid-template-columns: 1fr;
            }

            .workflow-grid {
                grid-template-columns: 1fr;
            }

            .stat-grid {
                grid-template-columns: 1fr;
            }

            .brand-subtitle {
                display: none;
            }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <header class="navbar">
        <div class="container navbar-content">
            <a href="{{ route('landing') }}" class="brand">
                <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMKN 2 Guguak" class="brand-img">
                <div>
                    <div class="brand-title">SIKS SMKN 2 GUGUAK</div>
                    <div class="brand-subtitle">Sistem Informasi Layanan Konseling Siswa</div>
                </div>
            </a>

            <ul class="nav-links" id="nav-links">
                <li><a href="#beranda" class="nav-link" onclick="closeLandingNav()">Beranda</a></li>
                <li><a href="#layanan" class="nav-link" onclick="closeLandingNav()">Layanan BK</a></li>
                <li><a href="#alur" class="nav-link" onclick="closeLandingNav()">Alur Konseling</a></li>
                <li><a href="#kerahasiaan" class="nav-link" onclick="closeLandingNav()">Kerahasiaan</a></li>
                <li><a href="#kontak" class="nav-link" onclick="closeLandingNav()">Kontak</a></li>
            </ul>

            <div style="display:flex; align-items:center; gap:0.75rem;">
                <a href="{{ route('login') }}" class="btn btn-gold">Masuk ke Sistem</a>
                <button type="button" class="hamburger-btn" onclick="toggleLandingNav()" aria-label="Toggle Navigation">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="beranda">
        <div class="container hero-grid">
            <div>
                <span class="hero-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                    Layanan Terpadu Konseling Siswa
                </span>
                <h1 class="hero-title">Pendampingan Perkembangan &amp; Masa Depan Siswa</h1>
                <p class="hero-description">
                    SIKS SMKN 2 Guguak memberikan kemudahan akses pengajuan konseling secara mandiri maupun rujukan wali kelas, didukung integrasi notifikasi terpadu untuk mendukung kenyamanan serta keberhasilan akademik siswa.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('login') }}" class="btn btn-gold btn-lg">Ajukan Konseling Siswa</a>
                    <a href="#layanan" class="btn btn-outline btn-lg">Pelajari Layanan</a>
                </div>
            </div>

            <div class="hero-card">
                <div class="hero-card-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--accent-gold)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                    Statistik Ringkasan Layanan
                </div>
                <div class="stat-grid">
                    <div class="stat-box">
                        <div class="stat-number">6 Peran</div>
                        <div class="stat-label">Hak Akses Terintegrasi</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Terjaga Rahasia</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">Real-Time</div>
                        <div class="stat-label">Notifikasi WA System</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">4 Bidang</div>
                        <div class="stat-label">Layanan Konseling</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section" id="layanan">
        <div class="container">
            <div class="section-header">
                <div class="section-tag">Bidang Layanan Konseling</div>
                <h2 class="section-title">Layanan Utama Bagi Siswa SMKN 2 Guguak</h2>
                <p class="section-description">
                    Guru Konseling (BK) siap membantu siswa mengoptimalkan potensi diri, mengatasi masalah, dan merencanakan masa depan.
                </p>
            </div>

            <div class="grid-3">
                <div class="service-card">
                    <div class="service-icon-box">01</div>
                    <h3 class="service-title">Konseling Pribadi &amp; Emosional</h3>
                    <p class="service-desc">
                        Pendampingan intensif untuk membantu siswa memahami diri, menyelesaikan masalah pribadi, pengelolaan emosi, dan peningkatan rasa percaya diri.
                    </p>
                </div>

                <div class="service-card">
                    <div class="service-icon-box">02</div>
                    <h3 class="service-title">Konseling Sosial &amp; Hubungan</h3>
                    <p class="service-desc">
                        Membantu siswa beradaptasi dengan lingkungan sekolah, membina komunikasi positif dengan teman sebaya, serta menyelesaikan konflik sosial secara sehat.
                    </p>
                </div>

                <div class="service-card">
                    <div class="service-icon-box">03</div>
                    <h3 class="service-title">Konseling Belajar &amp; Akademik</h3>
                    <p class="service-desc">
                        Bantuan strategi belajar efektif, manajemen waktu konsentrasi, penanganan kesulitan belajar, dan peningkatan prestasi akademik di sekolah.
                    </p>
                </div>

                <div class="service-card">
                    <div class="service-icon-box">04</div>
                    <h3 class="service-title">Perencanaan Karir &amp; DUDI</h3>
                    <p class="service-desc">
                        Pendampingan pemilihan arah karir sesuai keahlian SMK, kesiapan kerja di Dunia Usaha / Dunia Industri (DUDI), serta persiapan studi lanjut.
                    </p>
                </div>

                <div class="service-card">
                    <div class="service-icon-box">05</div>
                    <h3 class="service-title">Rujukan Konseling Wali Kelas</h3>
                    <p class="service-desc">
                        Fasilitas penanganan rujukan siswa dari Wali Kelas ke Guru BK untuk pemantauan dini dan tindakan pencegahan masalah siswa binaan.
                    </p>
                </div>

                <div class="service-card">
                    <div class="service-icon-box">06</div>
                    <h3 class="service-title">Integrasi WhatsApp System</h3>
                    <p class="service-desc">
                        Pemberitahuan otomatis mengenai jadwal konseling, validasi pengajuan, serta pengiriman surat pemanggilan orang tua/wali secara tepat waktu.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Workflow Section -->
    <section class="section" id="alur" style="background-color:#F1F5F9;">
        <div class="container">
            <div class="section-header">
                <div class="section-tag">Prosedur Pengajuan</div>
                <h2 class="section-title">Alur Mudah Layanan Konseling Siswa</h2>
                <p class="section-description">
                    Empat langkah sederhana untuk mendapatkan jadwal konseling dengan Guru BK pilihan Anda.
                </p>
            </div>

            <div class="workflow-grid">
                <div class="step-card">
                    <span class="step-number">Langkah 1</span>
                    <h3 class="step-title">Login ke Akun Siswa</h3>
                    <p class="step-desc">Masuk ke sistem menggunakan username dan kata sandi siswa yang telah terdaftar di sekolah.</p>
                </div>

                <div class="step-card">
                    <span class="step-number">Langkah 2</span>
                    <h3 class="step-title">Pilih Slot &amp; Guru BK</h3>
                    <p class="step-desc">Pilih ketersediaan jadwal Guru BK pembimbing dan tentukan bidang konseling yang dibutuhkan.</p>
                </div>

                <div class="step-card">
                    <span class="step-number">Langkah 3</span>
                    <h3 class="step-title">Validasi Jadwal</h3>
                    <p class="step-desc">Guru BK menerima pengajuan dan melakukan konfirmasi jadwal serta memberikan notifikasi.</p>
                </div>

                <div class="step-card">
                    <span class="step-number">Langkah 4</span>
                    <h3 class="step-title">Pelaksanaan Sesi</h3>
                    <p class="step-desc">Mengikuti sesi konseling tatap muka sesuai ruang dan waktu yang telah disepakati bersama.</p>
                </div>
            </div>

            <!-- Ethics Banner -->
            <div class="ethics-banner" id="kerahasiaan">
                <div class="ethics-text">
                    <h4>Prinsip Kerahasiaan Konseling (Confidentiality)</h4>
                    <p>
                        Seluruh catatan hasil konseling pribadi siswa dijamin kerahasiaannya dan hanya diakses oleh Guru BK pembimbing sesuai Kode Etik Konseling Indonesia.
                    </p>
                </div>
                <div>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Masuk &amp; Konseling</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="kontak">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand-title">SMK Negeri 2 Guguak</div>
                    <p class="footer-text">
                        Sistem Informasi Layanan Konseling Siswa (SIKS) dikembangkan untuk mendukung manajemen konseling yang terstruktur, transparan, dan beretika di lingkungan SMKN 2 Guguak.
                    </p>
                </div>

                <div>
                    <div class="footer-heading">Informasi Sekolah</div>
                    <ul class="footer-links">
                        <li><span style="color:rgba(255,255,255,0.75);">AMPANG GADANG, Vii Koto Talago, Kec. Guguak, Kab. Lima Puluh Koto, Sumatera Barat.</span></li>
                        <li><span style="color:rgba(255,255,255,0.75);">Jam Layanan BK: Senin - Jumat (07.30 - 15.30 WIB)</span></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                &copy; {{ date('Y') }} SIKS SMKN 2 Guguak. Hak Cipta Dilindungi Undang-Undang.
            </div>
        </div>
    </footer>

    <script>
        function toggleLandingNav() {
            const navLinks = document.getElementById('nav-links');
            if (navLinks) {
                navLinks.classList.toggle('show');
            }
        }

        function closeLandingNav() {
            const navLinks = document.getElementById('nav-links');
            if (navLinks) {
                navLinks.classList.remove('show');
            }
        }
    </script>
</body>
</html>
