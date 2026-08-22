<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIKS - Sistem Informasi Layanan Konseling Siswa SMKN 2 Guguak</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo_smk.png') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    <style>
        :root {
            --primary: #1B4D3E;
            --primary-dark: #12352B;
            --primary-light: #2D6A4F;
            --accent-gold: #D4AF37;
            --bg-main: #F8FAFC;
            --card-bg: #FFFFFF;
            --text-dark: #0F172A;
            --text-muted: #64748B;
            --border-color: #E2E8F0;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-main);
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Navbar Header */
        .navbar {
            background-color: #FFFFFF;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        }

        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 72px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            text-decoration: none;
        }

        .brand-title {
            font-weight: 800;
            font-size: 1.125rem;
            color: var(--primary-dark);
            line-height: 1.2;
        }

        .brand-subtitle {
            font-size: 0.75rem;
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
            transition: color 0.15s ease;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.65rem 1.35rem;
            font-size: 0.875rem;
            font-weight: 700;
            border-radius: 0.375rem;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #FFFFFF;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            box-shadow: 0 4px 12px rgba(27, 77, 62, 0.25);
        }

        .btn-gold {
            background-color: var(--accent-gold);
            color: #12352B;
        }

        .btn-gold:hover {
            background-color: #C29D26;
        }

        .btn-outline {
            background-color: transparent;
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background-color: var(--primary);
            color: #FFFFFF;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: #FFFFFF;
            padding: 5rem 0 6rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 3rem;
            align-items: center;
        }

        .hero-badge {
            display: inline-block;
            background-color: rgba(212, 175, 55, 0.2);
            color: var(--accent-gold);
            border: 1px solid var(--accent-gold);
            padding: 0.35rem 0.875rem;
            border-radius: 9999px;
            font-size: 0.775rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1.25rem;
        }

        .hero-title {
            font-size: 2.65rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.25rem;
            color: #FFFFFF;
        }

        .hero-description {
            font-size: 1.05rem;
            color: rgba(255, 255, 255, 0.88);
            margin-bottom: 2rem;
            max-width: 600px;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .hero-card {
            background-color: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 0.75rem;
            padding: 1.75rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }

        .stat-box {
            background-color: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 0.5rem;
            padding: 1.25rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--accent-gold);
            line-height: 1;
            margin-bottom: 0.35rem;
        }

        .stat-label {
            font-size: 0.825rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
        }

        /* Section Styling */
        .section {
            padding: 5rem 0;
        }

        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 3.5rem auto;
        }

        .section-tag {
            font-size: 0.775rem;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.5rem;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 0.75rem;
        }

        .section-description {
            font-size: 0.95rem;
            color: var(--text-muted);
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
            border-radius: 0.75rem;
            padding: 2rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            flex-direction: column;
        }

        .service-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.06);
            border-color: var(--primary-light);
        }

        .service-icon-box {
            width: 48px;
            height: 48px;
            background-color: #E6F4EA;
            color: var(--primary);
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.25rem;
            margin-bottom: 1.25rem;
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
            line-height: 1.6;
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
            border-radius: 0.625rem;
            padding: 1.75rem;
            position: relative;
            border-top: 4px solid var(--primary);
        }

        .step-number {
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--accent-gold);
            background-color: var(--primary-dark);
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }

        .step-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }

        .step-desc {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* Ethics Banner */
        .ethics-banner {
            background-color: #F1F5F9;
            border-left: 4px solid var(--primary);
            border-radius: 0.5rem;
            padding: 2rem;
            margin-top: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .ethics-text h4 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.35rem;
        }

        .ethics-text p {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        /* Footer */
        .footer {
            background-color: var(--primary-dark);
            color: #FFFFFF;
            padding: 4rem 0 2rem 0;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-brand-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .footer-text {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.7);
            line-height: 1.6;
        }

        .footer-heading {
            font-size: 0.875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--accent-gold);
            margin-bottom: 1.25rem;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.6rem;
        }

        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.15s ease;
        }

        .footer-links a:hover {
            color: #FFFFFF;
        }

        .footer-bottom {
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.6);
        }

        @media (max-width: 900px) {
            .hero-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <header class="navbar">
        <div class="container navbar-content">
            <a href="{{ route('landing') }}" class="brand">
                <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMKN 2 Guguak" style="width:40px; height:40px; object-fit:contain;">
                <div>
                    <div class="brand-title">SIKS SMKN 2 GUGUAK</div>
                    <div class="brand-subtitle">Sistem Informasi Layanan Konseling Siswa</div>
                </div>
            </a>

            <ul class="nav-links">
                <li><a href="#beranda" class="nav-link">Beranda</a></li>
                <li><a href="#layanan" class="nav-link">Layanan BK</a></li>
                <li><a href="#alur" class="nav-link">Alur Konseling</a></li>
                <li><a href="#kerahasiaan" class="nav-link">Kerahasiaan</a></li>
                <li><a href="#kontak" class="nav-link">Kontak</a></li>
            </ul>

            <div>
                <a href="{{ route('login') }}" class="btn btn-gold">Masuk ke Sistem</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="beranda">
        <div class="container hero-grid">
            <div>
                <span class="hero-badge">Layanan Terpadu Konseling Siswa</span>
                <h1 class="hero-title">Pendampingan Perkembangan &amp; Masa Depan Siswa</h1>
                <p class="hero-description">
                    SIKS SMKN 2 Guguak memberikan kemudahan akses pengajuan konseling secara mandiri maupun rujukan wali kelas, didukung integrasi notifikasi teradu untuk mendukung kenyamanan serta keberhasilan akademik siswa.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('login') }}" class="btn btn-gold">Ajukan Konseling Siswa</a>
                    <a href="#layanan" class="btn btn-outline" style="color:#FFFFFF; border-color:rgba(255,255,255,0.4);">Pelajari Layanan</a>
                </div>
            </div>

            <div class="hero-card">
                <h3 style="font-size:1.1rem; font-weight:700; color:#FFFFFF; margin-bottom:1.25rem;">Statistik Ringkasan Layanan</h3>
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
                    <a href="{{ route('login') }}" class="btn btn-primary">Masuk &amp; Konseling</a>
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
                    <div class="footer-heading">Navigasi Cepat</div>
                    <ul class="footer-links">
                        <li><a href="#beranda">Beranda</a></li>
                        <li><a href="#layanan">Layanan BK</a></li>
                        <li><a href="#alur">Alur Konseling</a></li>
                        <li><a href="{{ route('login') }}">Halaman Login</a></li>
                    </ul>
                </div>

                <div>
                    <div class="footer-heading">Informasi Sekolah</div>
                    <ul class="footer-links">
                        <li><span style="color:rgba(255,255,255,0.7);">Alamat: Ampang Gadang, Kec. Guguak, Kab. Lima Puluh Kota, Sumatera Barat</span></li>
                        <li><span style="color:rgba(255,255,255,0.7);">Jam Layanan BK: Senin - Jumat (07.30 - 15.30 WIB)</span></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                &copy; {{ date('Y') }} SIKS SMKN 2 Guguak. Hak Cipta Dilindungi Undang-Undang.
            </div>
        </div>
    </footer>

</body>
</html>
