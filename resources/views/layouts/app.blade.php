{{-- LAYOUT UTAMA APLIKASI: Induk kerangka tampilan (Sidebar, Topbar, Content Area) untuk seluruh portal --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistem Informasi Layanan Konseling Siswa') - SMKN 2 Guguak</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo_smk.png') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    <style>
        :root {
            /* Standar Warna Bab III */
            --primary: #1B4D3E;        /* Hijau Utama */
            --primary-dark: #12352B;   /* Hijau Gelap */
            --primary-light: #2D6A4F;  /* Hijau Sedang */
            --accent-gold: #D4AF37;    /* Gold Aksen */
            --bg-main: #F8FAFC;        /* Putih Latar */
            --card-bg: #FFFFFF;        /* Kartu Putih */
            --text-dark: #0F172A;      /* Hitam Teks */
            --text-muted: #64748B;     /* Abu-abu teks */
            --border-color: #E2E8F0;   /* Garis Batas */
            --danger: #EF4444;         /* Merah Peringatan */
            --info: #3B82F6;           /* Biru Info */
            --success: #10B981;        /* Hijau Sukses */
            --warning: #F59E0B;        /* Kuning Peringatan */
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-main);
            color: var(--text-dark);
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 270px;
            background-color: var(--primary);
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.08);
            z-index: 50;
        }

        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            font-size: 1rem;
            font-weight: 700;
            color: #FFFFFF;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-brand-badge {
            background-color: var(--accent-gold);
            color: #1B4D3E;
            padding: 0.2rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 800;
        }

        .sidebar-menu {
            padding: 1rem 0.875rem;
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            flex: 1;
            overflow: visible;
        }

        .sidebar-heading {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.075em;
            color: rgba(255, 255, 255, 0.6);
            padding: 0.875rem 0.75rem 0.25rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.875rem;
            border-radius: 0.375rem;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s ease-in-out;
        }

        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
        }

        .nav-item.active {
            background-color: var(--accent-gold);
            color: #12352B;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .user-profile-box {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            background-color: var(--primary-dark);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Main Content  */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            height: 100vh;
            overflow: hidden;
        }

        .topbar {
            background-color: #FFFFFF;
            border-bottom: 1px solid var(--border-color);
            padding: 0.875rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .content-area {
            padding: 2rem;
            flex: 1;
            overflow-y: auto;
        }

        /* UI Card Components */
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.625rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            margin-bottom: 1.5rem;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border-color);
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
        }

        .grid-4 { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; }
        .grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.25rem; }
        .grid-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(420px, 1fr)); gap: 1.5rem; }

        .stat-card {
            background-color: #FFFFFF;
            border: 1px solid var(--border-color);
            border-radius: 0.625rem;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            border-left: 4px solid var(--primary);
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }

        .stat-card.gold { border-left-color: var(--accent-gold); }
        .stat-card.blue { border-left-color: var(--info); }
        .stat-card.red  { border-left-color: var(--danger); }

        .stat-val { font-size: 1.85rem; font-weight: 800; color: var(--primary-dark); margin-top: 0.25rem; }
        .stat-lbl { font-size: 0.825rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }

        /* Tables & Badges */
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem; }
        th { background-color: #F8FAFC; padding: 0.875rem 1rem; font-weight: 700; color: var(--text-dark); border-bottom: 2px solid var(--border-color); }
        td { padding: 0.875rem 1rem; border-bottom: 1px solid var(--border-color); vertical-align: middle; }
        tr:hover td { background-color: #F8FAFC; }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-success { background-color: #DCFCE7; color: #166534; }
        .badge-warning { background-color: #FEF3C7; color: #92400E; }
        .badge-danger { background-color: #FEE2E2; color: #991B1B; }
        .badge-info { background-color: #E0F2FE; color: #075985; }
        .badge-gold { background-color: #FEF08A; color: #854D0E; }

        /* Forms & Buttons */
        .form-group { margin-bottom: 1.125rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: var(--text-dark); margin-bottom: 0.375rem; }
        .form-control {
            width: 100%;
            padding: 0.55rem 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            font-family: inherit;
            font-size: 0.875rem;
            color: var(--text-dark);
            background-color: #FFFFFF;
            transition: border-color 0.15s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(27, 77, 62, 0.15);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1.125rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 0.375rem;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-primary { background-color: var(--primary); color: #FFFFFF; }
        .btn-primary:hover { background-color: var(--primary-dark); }
        .btn-gold { background-color: var(--accent-gold); color: #12352B; }
        .btn-gold:hover { background-color: #C29D26; }
        .btn-secondary { background-color: #E2E8F0; color: #334155; }
        .btn-secondary:hover { background-color: #CBD5E1; }
        .btn-success { background-color: var(--success); color: white; }
        .btn-danger { background-color: var(--danger); color: white; }
        .btn-danger:hover { background-color: #DC2626; }
        .btn-sm { padding: 0.3rem 0.65rem; font-size: 0.75rem; }

        .alert {
            padding: 0.875rem 1.125rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
        }
        .alert-success { background-color: #DCFCE7; color: #166534; border: 1px solid #BBF7D0; }
        .alert-danger { background-color: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; }
        .alert-warning { background-color: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; }

        /* Responsive Hamburger & Offcanvas Sidebar */
        .hamburger-btn {
            display: none;
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            padding: 0.4rem 0.6rem;
            cursor: pointer;
            color: var(--primary-dark);
            align-items: center;
            justify-content: center;
            transition: background-color 0.15s ease;
        }

        .hamburger-btn:hover {
            background-color: #F1F5F9;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(2px);
            z-index: 99;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        @media (max-width: 991.98px) {
            .hamburger-btn {
                display: inline-flex;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 270px;
                z-index: 100;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                box-shadow: 4px 0 15px rgba(0, 0, 0, 0.2);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .topbar {
                padding: 0.75rem 1rem;
            }

            .content-area {
                padding: 1rem;
            }
        }

        @media (max-width: 575.98px) {
            .topbar-badges {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Overlay Gelap saat Mobile Sidebar Terbuka -->
    <div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar Navigasi Berdasarkan 33 Wireframe Bab III -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMKN 2 Guguak" style="width:34px; height:34px; object-fit:contain; filter:drop-shadow(0 2px 4px rgba(0,0,0,0.2));">
            <span>SIKS SMKN 2 GUGUAK</span>
        </div>

        <nav class="sidebar-menu">
            @if(auth()->user()->role === 'admin')
                <!-- Modul 2: Admin System -->
                <div class="sidebar-heading">Menu Administrator</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">Profil Saya</a>
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">Manajemen Pengguna</a>
                <a href="{{ route('admin.siswa.index') }}" class="nav-item {{ request()->routeIs('admin.siswa*') ? 'active' : '' }}">Data Siswa</a>
                <a href="{{ route('admin.kelas.index') }}" class="nav-item {{ request()->routeIs('admin.kelas*') ? 'active' : '' }}">Manajemen Kelas</a>
                <a href="{{ route('admin.jurusan.index') }}" class="nav-item {{ request()->routeIs('admin.jurusan*') ? 'active' : '' }}">Manajemen Jurusan</a>
                <a href="{{ route('admin.tahun-ajaran.index') }}" class="nav-item {{ request()->routeIs('admin.tahun-ajaran*') ? 'active' : '' }}">Tahun Ajaran</a>
                <a href="{{ route('admin.log-aktivitas.index') }}" class="nav-item {{ request()->routeIs('admin.log-aktivitas*') ? 'active' : '' }}">Log Aktivitas System</a>
                <a href="{{ route('admin.laporan.index') }}" class="nav-item {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">Laporan & Rekapitulasi</a>
                <a href="{{ route('admin.pengaturan.index') }}" class="nav-item {{ request()->routeIs('admin.pengaturan*') ? 'active' : '' }}">Pengaturan Sistem</a>


            @elseif(auth()->user()->role === 'guru_bk')
                <!-- Modul 3: Guru BK (7 Menu Terpadu) -->
                <div class="sidebar-heading">Layanan Konseling Siswa</div>
                <a href="{{ route('guru.dashboard') }}" class="nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">Profil Saya</a>
                <a href="{{ route('guru.pengajuan.index') }}" class="nav-item {{ request()->routeIs('guru.pengajuan*') ? 'active' : '' }}">Pengajuan &amp; Rujukan Siswa</a>
                <a href="{{ route('guru.jadwal.index') }}" class="nav-item {{ request()->routeIs('guru.jadwal*') || request()->routeIs('guru.ketersediaan*') ? 'active' : '' }}">Jadwal &amp; Agenda Konseling</a>
                <a href="{{ route('guru.layanan.index') }}" class="nav-item {{ request()->routeIs('guru.layanan*') || request()->routeIs('guru.konseling*') ? 'active' : '' }}">Pelaksanaan Konseling</a>
                <a href="{{ route('guru.tindak-lanjut.index') }}" class="nav-item {{ request()->routeIs('guru.tindak-lanjut*') || request()->routeIs('guru.surat*') ? 'active' : '' }}">Tindak Lanjut &amp; Surat Panggilan</a>
                <a href="{{ route('guru.laporan.index') }}" class="nav-item {{ request()->routeIs('guru.laporan*') ? 'active' : '' }}">Laporan &amp; Rekapitulasi</a>

            @elseif(auth()->user()->role === 'siswa')
                <!-- Modul 5: Siswa -->
                <div class="sidebar-heading">Layanan Siswa</div>
                <a href="{{ route('siswa.dashboard') }}" class="nav-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">Profil Siswa</a>
                <a href="{{ route('siswa.jadwal.available') }}" class="nav-item {{ request()->routeIs('siswa.jadwal*') ? 'active' : '' }}">Cari Slot Jadwal</a>
                <a href="{{ route('siswa.pengajuan.index') }}" class="nav-item {{ request()->routeIs('siswa.pengajuan*') ? 'active' : '' }}">Pengajuan Konseling</a>
                <a href="{{ route('siswa.konseling.index') }}" class="nav-item {{ request()->routeIs('siswa.konseling*') ? 'active' : '' }}">Riwayat & Arahan Konseling</a>

            @elseif(auth()->user()->role === 'wali_kelas')
                <!-- Modul 4: Wali Kelas -->
                <div class="sidebar-heading">Wali Kelas</div>
                <a href="{{ route('wali.dashboard') }}" class="nav-item {{ request()->routeIs('wali.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">Profil Saya</a>
                <a href="{{ route('wali.siswa.index') }}" class="nav-item {{ request()->routeIs('wali.siswa*') ? 'active' : '' }}">Data Siswa Binaan</a>
                <a href="{{ route('wali.rujukan.create') }}" class="nav-item {{ request()->routeIs('wali.rujukan*') ? 'active' : '' }}">Ajukan Rujukan ke BK</a>
                <a href="{{ route('wali.monitoring.index') }}" class="nav-item {{ request()->routeIs('wali.monitoring*') || request()->routeIs('wali.pemantauan*') ? 'active' : '' }}">Monitoring Siswa</a>
                <a href="{{ route('wali.jadwal.index') }}" class="nav-item {{ request()->routeIs('wali.jadwal*') ? 'active' : '' }}">Jadwal Konseling</a>

            @elseif(auth()->user()->role === 'wakasis')
                <!-- Modul 6: Wakasis -->
                <div class="sidebar-heading">Wakil Kesiswaan</div>
                <a href="{{ route('wakasis.dashboard') }}" class="nav-item {{ request()->routeIs('wakasis.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">Profil Saya</a>
                <a href="{{ route('wakasis.siswa.index') }}" class="nav-item {{ request()->routeIs('wakasis.siswa*') ? 'active' : '' }}">Data Siswa Sekolah</a>
                <a href="{{ route('wakasis.rekapitulasi.index') }}" class="nav-item {{ request()->routeIs('wakasis.rekapitulasi*') || request()->routeIs('wakasis.laporan*') ? 'active' : '' }}">Rekapitulasi & Laporan</a>

            @elseif(auth()->user()->role === 'kepala_sekolah')
                <!-- Modul7: Kepala Sekolah -->
                <div class="sidebar-heading">Kepala Sekolah</div>
                <a href="{{ route('kepsek.dashboard') }}" class="nav-item {{ request()->routeIs('kepsek.dashboard') ? 'active' : '' }}">Dashboard Eksekutif</a>
                <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">Profil Saya</a>
                <a href="{{ route('kepsek.kinerja.index') }}" class="nav-item {{ request()->routeIs('kepsek.kinerja*') ? 'active' : '' }}">Kinerja Guru BK</a>
                <a href="{{ route('kepsek.pemetaan.index') }}" class="nav-item {{ request()->routeIs('kepsek.pemetaan*') ? 'active' : '' }}">Pemetaan Layanan</a>
                <a href="{{ route('kepsek.siswa.index') }}" class="nav-item {{ request()->routeIs('kepsek.siswa*') ? 'active' : '' }}">Data Siswa Sekolah</a>
                <a href="{{ route('kepsek.laporan.index') }}" class="nav-item {{ request()->routeIs('kepsek.laporan*') ? 'active' : '' }}">Laporan Eksekutif</a>
            @endif
        </nav>

        <div class="user-profile-box">
            <div>
                <div style="font-weight:700; color:white; font-size:0.875rem;">{{ auth()->user()->name }}</div>
                <div style="font-size:0.725rem; color:var(--accent-gold); text-transform:uppercase; font-weight:600;">
                    {{ str_replace('_', ' ', auth()->user()->role) }}
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm" title="Keluar dari Sistem">Keluar</button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <header class="topbar">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <button type="button" class="hamburger-btn" onclick="toggleSidebar()" aria-label="Toggle Menu">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <div>
                    <div style="font-weight:700; font-size:1.125rem; color:var(--primary-dark);">SMK Negeri 2 Guguak</div>
                    <div style="font-size:0.75rem; color:var(--text-muted);">Sistem Informasi Layanan Konseling Siswa (SIKS)</div>
                </div>
            </div>
            <div class="topbar-badges" style="display:flex; align-items:center; gap:0.75rem;">
                <span class="badge badge-gold">Tahun Ajaran 2026/2027</span>
                <span class="badge badge-success">Aktif</span>
            </div>
        </header>

        <main class="content-area">
            @if(session('success'))
                <div class="alert alert-success">
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning">
                    <strong>Perhatian!</strong> {{ session('warning') }}
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi Kesalahan!</strong>
                    <ul style="margin:0.25rem 0 0 0; padding-left:1.25rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (sidebar && overlay) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            }
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                const sidebar = document.querySelector('.sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                if (sidebar) sidebar.classList.remove('show');
                if (overlay) overlay.classList.remove('show');
            }
        });

        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            
            const eyeOpen = btn.querySelector('.eye-open');
            const eyeClosed = btn.querySelector('.eye-closed');
            if (eyeOpen && eyeClosed) {
                eyeOpen.style.display = isPassword ? 'none' : 'block';
                eyeClosed.style.display = isPassword ? 'block' : 'none';
            }
        }
    </script>
</body>
</html>
