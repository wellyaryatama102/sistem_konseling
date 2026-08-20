<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistem Informasi Konseling Siswa</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=inter:400,500,600,700"
        rel="stylesheet"
    />

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --bg: #f8fafc;
            --white: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
            --success-bg: #dcfce7;
            --success-text: #166534;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        /* Navbar */
        .navbar {
            height: 70px;
            padding: 0 1.5rem;
            background: var(--white);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-logo {
            width: 42px;
            height: 42px;
            object-fit: contain;
        }

        .brand-text {
            font-size: 1rem;
            font-weight: 700;
        }

        .brand-subtitle {
            margin-top: 2px;
            color: var(--muted);
            font-size: 0.75rem;
        }

        .status {
            padding: 0.4rem 0.75rem;
            border-radius: 999px;
            background: var(--success-bg);
            color: var(--success-text);
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Layout */
        .layout {
            display: flex;
            min-height: calc(100vh - 70px);
        }

        /* Sidebar */
        .sidebar {
            width: 230px;
            padding: 1.25rem 1rem;
            background: var(--white);
            border-right: 1px solid var(--border);
        }

        .sidebar-title {
            margin-bottom: 0.75rem;
            color: var(--muted);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .menu {
            display: block;
            padding: 0.7rem 0.8rem;
            margin-bottom: 0.25rem;
            border-radius: 0.5rem;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.85rem;
        }

        .menu:hover,
        .menu.active {
            background: #eff6ff;
            color: var(--primary);
        }

        /* Content */
        .content {
            flex: 1;
            padding: 2rem;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .page-title {
            margin-bottom: 1.5rem;
        }

        .page-title h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        .page-title p {
            margin: 0.4rem 0 0;
            color: var(--muted);
            font-size: 0.875rem;
        }

        /* Card */
        .card {
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
        }

        .version {
            padding: 0.35rem 0.65rem;
            border-radius: 999px;
            background: #e0f2fe;
            color: #075985;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .description {
            margin: 0;
            color: var(--muted);
            font-size: 0.875rem;
            line-height: 1.6;
        }

        /* Grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }

        /* Diagnostics */
        .diagnostic {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.7rem 0;
            border-bottom: 1px solid var(--border);
            font-size: 0.85rem;
        }

        .diagnostic:last-child {
            border-bottom: none;
        }

        .diagnostic span {
            color: var(--muted);
        }

        .diagnostic strong {
            text-align: right;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                padding: 0 1rem;
            }

            .brand-text {
                font-size: 0.9rem;
            }

            .brand-subtitle {
                display: none;
            }

            .status {
                display: none;
            }

            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid var(--border);
                padding: 0.75rem;
            }

            .sidebar-title {
                display: none;
            }

            .menu {
                display: inline-block;
                margin: 0.15rem;
            }

            .content {
                padding: 1rem;
            }

            .grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                height: 60px;
            }

            .brand-logo {
                width: 36px;
                height: 36px;
            }

            .brand-text {
                font-size: 0.8rem;
            }

            .content {
                padding: 0.75rem;
            }

            .page-title h1 {
                font-size: 1.25rem;
            }

            .diagnostic {
                flex-direction: column;
                gap: 0.25rem;
            }

            .diagnostic strong {
                text-align: left;
            }
        }
    </style>

    @livewireStyles
</head>

<body>

    <!-- Navbar -->
    <header class="navbar">

        <div class="brand">

            {{-- Logo sekolah --}}
            <img
                src="{{ asset('images/logo-sekolah.png') }}"
                alt="Logo Sekolah"
                class="brand-logo"
            >

            <div>
                <div class="brand-text">
                    Sistem Informasi Konseling Siswa
                </div>

                <div class="brand-subtitle">
                    SMK Negeri 2 Guguak
                </div>
            </div>

        </div>

        <span class="status">
            Sistem Aktif
        </span>

    </header>


    <div class="layout">

        <!-- Sidebar -->
        <aside class="sidebar">

            <div class="sidebar-title">
                Pengguna
            </div>

            <a href="#" class="menu active">
                Admin
            </a>

            <a href="#" class="menu">
                Guru BK
            </a>

            <a href="#" class="menu">
                Wali Kelas
            </a>

            <a href="#" class="menu">
                Siswa
            </a>

            <a href="#" class="menu">
                Wakasis
            </a>

            <a href="#" class="menu">
                Kepala Sekolah
            </a>

        </aside>


        <!-- Main Content -->
        <main class="content">

            <div class="container">

                <!-- Page Title -->
                <div class="page-title">

                    <h1>
                        Verifikasi Sistem
                    </h1>

                    <p>
                        Memastikan lingkungan aplikasi dan koneksi sistem berjalan dengan baik.
                    </p>

                </div>


                <!-- Information -->
                <div class="card">

                    <div class="card-header">

                        <span class="card-title">
                            Informasi Sistem
                        </span>

                        <span class="version">
                            Laravel {{ app()->version() }}
                        </span>

                    </div>

                    <p class="description">
                        Halaman ini digunakan untuk memastikan fondasi aplikasi,
                        Blade, Livewire, CSS, dan koneksi database telah berjalan
                        dengan baik sebelum pengembangan sistem dilanjutkan.
                    </p>

                </div>


                <!-- Diagnostics -->
                <div class="grid">

                    <div class="card">

                        <div class="card-header">
                            <span class="card-title">
                                Informasi Environment
                            </span>
                        </div>

                        <div class="diagnostic">
                            <span>PHP Version</span>
                            <strong>{{ PHP_VERSION }}</strong>
                        </div>

                        <div class="diagnostic">
                            <span>Framework</span>
                            <strong>Laravel {{ app()->version() }}</strong>
                        </div>

                        <div class="diagnostic">
                            <span>Environment</span>
                            <strong>{{ config('app.env') }}</strong>
                        </div>

                        <div class="diagnostic">
                            <span>Database</span>
                            <strong>{{ config('database.default') }}</strong>
                        </div>

                    </div>


                    <div class="card">
                        <div class="card-header">
                            <span class="card-title">
                                Status Sistem
                            </span>
                        </div>

                        <div class="diagnostic">
                            <span>Laravel</span>
                            <strong>Berjalan</strong>
                        </div>

                        <div class="diagnostic">
                            <span>Blade</span>
                            <strong>Berjalan</strong>
                        </div>

                        <div class="diagnostic">
                            <span>Livewire</span>
                            <strong>Aktif</strong>
                        </div>

                        <div class="diagnostic">
                            <span>Database</span>
                            <strong>Terhubung</strong>
                        </div>

                    </div>

                </div>

            </div>

        </main>

    </div>

    @livewireScripts

</body>

</html>