/**
 * SIKS SMKN 2 GUGUAK - WIREFRAMES DATA
 * Berisi seluruh definisi wireframe SVGs per peran dan per layar interaktif.
 */

// Helper: Generate Standard Sidebar
function getSidebarSVG(roleType, activeMenuIndex) {
    const menus = {
        auth: ["Dashboard Utama", "Profil", "Edit Profil & Sandi"],
        admin: ["Dashboard", "Profil Saya", "Manajemen Pengguna", "Data Siswa", "Manajemen Kelas", "Tahun Ajaran", "Laporan & Rekapitulasi", "Pengaturan Sistem"],
        bk: ["Dashboard", "Profil Saya", "Pengajuan & Rujukan", "Jadwal & Agenda Konseling", "Pelaksanaan Konseling", "Tindak Lanjut & Surat Panggilan", "Laporan & Rekapitulasi"],
        walas: ["Dashboard Walas", "Ajukan Rujukan BK", "Data Siswa Binaan", "Monitoring Layanan", "Profil"],
        siswa: ["Beranda Siswa", "Jadwal Konseling", "Ajukan Konseling", "Riwayat & Arahan", "Profil"],
        wakasis: ["Dashboard Wakasis", "Rekapitulasi Sekolah", "Laporan Statistik", "Profil"],
        kepsek: ["Dashboard Eksekutif", "Kinerja Guru BK", "Pemetaan Bidang Konseling", "Laporan Kinerja", "Profil"]
    };

    const roleTitles = {
        auth: "NAVIGASI PROFIL",
        admin: "MENU ADMINISTRASI",
        bk: "MENU KONSELING",
        walas: "MENU WALI KELAS",
        siswa: "MENU SISWA",
        wakasis: "MENU WAKASIS",
        kepsek: "MENU EKSEKUTIF"
    };

    const currentMenus = menus[roleType] || [];
    let svg = `
        <rect x="10" y="60" width="210" height="570" class="wf-sidebar" />
        <text x="25" y="82" class="wf-text-muted">${roleTitles[roleType]}</text>
    `;

    currentMenus.forEach((m, idx) => {
        const y = 92 + (idx * 35);
        const isActive = (idx === activeMenuIndex);
        if (isActive) {
            svg += `
                <rect x="20" y="${y}" width="190" height="28" class="wf-btn-primary" />
                <text x="35" y="${y + 18}" class="wf-text-nav-active">${m}</text>
            `;
        } else {
            svg += `
                <rect x="20" y="${y}" width="190" height="28" class="wf-input" fill="#ffffff" />
                <text x="35" y="${y + 18}" class="wf-text-nav">${m}</text>
            `;
        }
    });

    return svg;
}

// Helper: Generate Standard Page Header
function getHeaderSVG(pageTitle, userName, userRole) {
    let rightText = "";
    if (userName && userRole) {
        rightText = `<text x="888" y="39" class="wf-text-body" style="font-weight:600;" text-anchor="end">${userName} (${userRole})</text>
                     <rect x="898" y="22" width="74" height="26" class="wf-btn-secondary" />
                     <text x="935" y="39" class="wf-text-btn-sec">Logout</text>`;
    }
    return `
        <rect x="10" y="10" width="980" height="620" class="wf-bg" rx="6" />
        <rect x="10" y="10" width="980" height="50" class="wf-header" />
        <text x="35" y="40" class="wf-text-title">SIKS SMKN 2 GUGUAK - ${pageTitle.toUpperCase()}</text>
        ${rightText}
    `;
}

// Helper: Wrap into full SVG
function wrapSVG(content) {
    return `<svg viewBox="0 0 1000 640" xmlns="http://www.w3.org/2000/svg">${content}</svg>`;
}

const roleData = [
    // =========================================================================
    // 1. AUTENTIKASI & PROFIL
    // =========================================================================
    {
        name: "1. Autentikasi & Profil",
        screens: [
            {
                id: "0-0",
                num: "1.1",
                title: "Halaman Login Sistem",
                desc: "Form Login Username & Password, Validasi Error, dan Informasi Pemulihan Akun via Admin",
                svgContent: wrapSVG(`
                    <rect x="10" y="10" width="980" height="620" class="wf-bg" rx="6" />
                    <rect x="10" y="10" width="980" height="36" fill="#f1f5f9" stroke="#000" stroke-width="1.2" />
                    <circle cx="30" cy="28" r="5" fill="#fff" stroke="#000" stroke-width="1" />
                    <circle cx="46" cy="28" r="5" fill="#fff" stroke="#000" stroke-width="1" />
                    <circle cx="62" cy="28" r="5" fill="#fff" stroke="#000" stroke-width="1" />
                    <rect x="90" y="18" width="600" height="20" fill="#fff" stroke="#000" stroke-width="1" rx="3" />
                    <text x="105" y="32" class="wf-text-muted">https://siks.smkn2guguak.sch.id/login</text>

                    <rect x="280" y="65" width="440" height="550" class="wf-card" stroke-width="1.8" />
                    <rect x="460" y="90" width="80" height="75" fill="#f8fafc" stroke="#000" stroke-width="1.5" stroke-dasharray="4,3" />
                    <text x="500" y="130" class="wf-text-body" text-anchor="middle">[ LOGO ]</text>
                    <text x="500" y="147" class="wf-text-muted" text-anchor="middle">SMKN 2 GUGUAK</text>

                    <text x="500" y="190" class="wf-text-title" text-anchor="middle">SIKS - SISTEM INFORMASI KONSELING SISWA</text>
                    <text x="500" y="210" class="wf-text-muted" text-anchor="middle">Silakan masukkan Username dan Password Anda</text>

                    <rect x="320" y="230" width="360" height="30" fill="#fff" stroke="#000" stroke-width="1.2" stroke-dasharray="3,3" />
                    <text x="500" y="250" class="wf-text-body" style="font-size:10px; font-weight:600;" text-anchor="middle">[ Peringatan ] Username atau Password yang dimasukkan salah.</text>

                    <text x="320" y="285" class="wf-text-body" style="font-weight:600;">Username</text>
                    <rect x="320" y="295" width="360" height="34" class="wf-input" />
                    <text x="335" y="317" class="wf-text-muted">Masukkan username terdaftar...</text>

                    <text x="320" y="350" class="wf-text-body" style="font-weight:600;">Password</text>
                    <rect x="320" y="360" width="360" height="34" class="wf-input" />
                    <text x="335" y="382" class="wf-text-muted">••••••••••••••••</text>

                    <rect x="320" y="415" width="360" height="38" class="wf-btn-primary" />
                    <text x="500" y="439" class="wf-text-btn-pri" style="font-size:12px;">MASUK KE SISTEM (LOGIN)</text>

                    <rect x="320" y="475" width="360" height="85" fill="#f8fafc" stroke="#000" stroke-width="1.2" rx="4" />
                    <text x="335" y="495" class="wf-text-body" style="font-weight:700;">Informasi Bantuan Akun:</text>
                    <text x="335" y="513" class="wf-text-muted">Jika Anda lupa Username atau Password, silakan hubungi</text>
                    <text x="335" y="528" class="wf-text-muted">Administrator Sekolah di Ruang Tata Usaha / IT Support.</text>
                    <text x="335" y="545" class="wf-text-muted" style="font-style:italic;">Perubahan sandi mandiri hanya dapat dilakukan setelah berhasil login.</text>

                    <text x="500" y="585" class="wf-text-muted" text-anchor="middle">&copy; 2026 BK SMK Negeri 2 Guguak - Hak Akses Terlindungi</text>
                `)
            },
            {
                id: "0-1",
                num: "1.2",
                title: "Halaman Profil Pengguna",
                desc: "Tampilan Identitas Pengguna Sesuai Hak Akses, Data Kontak WA & Status Jabatan",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("PROFIL PENGGUNA", "[Nama Guru]", "Guru BK")}
                    ${getSidebarSVG("auth", 1)}
                    <text x="245" y="95" class="wf-text-title">PROFIL SAYA</text>
                    <text x="245" y="115" class="wf-text-muted">Kelola data identitas pribadi, nomor kontak WhatsApp, dan keamanan kata sandi</text>

                    <rect x="245" y="135" width="220" height="340" class="wf-card" />
                    <rect x="295" y="160" width="120" height="120" fill="#f8fafc" stroke="#000" stroke-width="1.2" stroke-dasharray="3,3" />
                    <text x="355" y="225" class="wf-text-body" text-anchor="middle">[ FOTO PROFIL ]</text>
                    
                    <text x="355" y="310" class="wf-text-body" style="font-size:13px; font-weight:700;" text-anchor="middle">[Nama Guru]</text>
                    <rect x="305" y="325" width="100" height="22" class="wf-badge" />
                    <text x="355" y="340" class="wf-text-badge">GURU BK / ADMIN</text>
                    
                    <rect x="275" y="375" width="160" height="32" class="wf-btn-primary" />
                    <text x="355" y="395" class="wf-text-btn-pri">Edit Data Profil</text>
                    <rect x="275" y="415" width="160" height="32" class="wf-btn-secondary" />
                    <text x="355" y="435" class="wf-text-btn-sec">Ubah Kata Sandi</text>

                    <rect x="485" y="135" width="480" height="460" class="wf-card" />
                    <rect x="485" y="135" width="480" height="36" class="wf-card-header" />
                    <text x="505" y="158" class="wf-text-body" style="font-weight:700;">INFORMASI DETAIL PRIBADI</text>

                    <text x="505" y="195" class="wf-text-muted">NIP / NUPTK / NISN</text>
                    <text x="505" y="215" class="wf-text-body" style="font-weight:600;">19820315 200801 1 004</text>
                    <line x1="505" y1="225" x2="945" y2="225" stroke="#e2e8f0" stroke-width="1" />

                    <text x="505" y="245" class="wf-text-muted">NAMA LENGKAP &amp; GELAR</text>
                    <text x="505" y="265" class="wf-text-body" style="font-weight:600;">[Nama Guru]</text>
                    <line x1="505" y1="275" x2="945" y2="275" stroke="#e2e8f0" stroke-width="1" />

                    <text x="505" y="295" class="wf-text-muted">NOMOR WHATSAPP (AKTIF)</text>
                    <text x="505" y="315" class="wf-text-body" style="font-weight:600;">0812-6789-XXXX [Terverifikasi Gateway]</text>
                    <line x1="505" y1="325" x2="945" y2="325" stroke="#e2e8f0" stroke-width="1" />

                    <text x="505" y="345" class="wf-text-muted">EMAIL RESMI</text>
                    <text x="505" y="365" class="wf-text-body" style="font-weight:600;">nurhayati@smkn2guguak.sch.id</text>
                    <line x1="505" y1="375" x2="945" y2="375" stroke="#e2e8f0" stroke-width="1" />

                    <text x="505" y="395" class="wf-text-muted">JENIS KELAMIN &amp; AGAMA</text>
                    <text x="505" y="415" class="wf-text-body" style="font-weight:600;">Perempuan | Islam</text>
                    <line x1="505" y1="425" x2="945" y2="425" stroke="#e2e8f0" stroke-width="1" />

                    <text x="505" y="445" class="wf-text-muted">TEMPAT, TANGGAL LAHIR</text>
                    <text x="505" y="465" class="wf-text-body" style="font-weight:600;">Payakumbuh, 15 Maret 1982</text>
                    <line x1="505" y1="475" x2="945" y2="475" stroke="#e2e8f0" stroke-width="1" />

                    <text x="505" y="495" class="wf-text-muted">ALAMAT DOMISILI</text>
                    <text x="505" y="515" class="wf-text-body" style="font-weight:600;">Jl. Tan Malaka No. 45, Kec. Guguak, Kab. Lima Puluh Kota</text>
                `)
            },
            {
                id: "0-2",
                num: "1.3",
                title: "Form Edit Profil & Sandi",
                desc: "Form Pengkinian Data Mandiri & Keamanan Kata Sandi Setelah Berhasil Login",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("EDIT PROFIL & UBAH SANDI", "[Nama Guru]", "Guru BK")}
                    ${getSidebarSVG("auth", 2)}
                    <text x="245" y="95" class="wf-text-title">PENGATURAN AKUN &amp; KEAMANAN</text>

                    <rect x="245" y="115" width="350" height="495" class="wf-card" />
                    <rect x="245" y="115" width="350" height="34" class="wf-card-header" />
                    <text x="260" y="137" class="wf-text-body" style="font-weight:700;">FORMULIR PENGKINIAN DATA PROFIL</text>

                    <text x="265" y="172" class="wf-text-body">Nama Lengkap</text>
                    <rect x="265" y="180" width="310" height="32" class="wf-input" />
                    <text x="275" y="200" class="wf-text-body">[Nama Guru]</text>

                    <text x="265" y="232" class="wf-text-body">Nomor WhatsApp (Aktif)</text>
                    <rect x="265" y="240" width="310" height="32" class="wf-input" />
                    <text x="275" y="260" class="wf-text-body">081267890011</text>

                    <text x="265" y="292" class="wf-text-body">Alamat Email</text>
                    <rect x="265" y="300" width="310" height="32" class="wf-input" />
                    <text x="275" y="320" class="wf-text-body">nurhayati@smkn2guguak.sch.id</text>

                    <text x="265" y="352" class="wf-text-body">Alamat Domisili</text>
                    <rect x="265" y="360" width="310" height="50" class="wf-input" />
                    <text x="275" y="380" class="wf-text-body">Kec. Guguak, Kab. 50 Kota</text>

                    <text x="265" y="432" class="wf-text-body">Unggah Foto Baru</text>
                    <rect x="265" y="440" width="310" height="32" class="wf-input" stroke-dasharray="3,3" />
                    <text x="275" y="460" class="wf-text-muted">[ Browse File... ] format JPG/PNG max 2MB</text>

                    <rect x="265" y="550" width="310" height="36" class="wf-btn-primary" />
                    <text x="420" y="573" class="wf-text-btn-pri">SIMPAN PERUBAHAN PROFIL</text>

                    <rect x="615" y="115" width="350" height="380" class="wf-card" />
                    <rect x="615" y="115" width="350" height="34" class="wf-card-header" />
                    <text x="630" y="137" class="wf-text-body" style="font-weight:700;">UBAH KATA SANDI (SECURITY)</text>

                    <text x="635" y="172" class="wf-text-body">Kata Sandi Saat Ini (Lama)</text>
                    <rect x="635" y="180" width="310" height="32" class="wf-input" />
                    <text x="645" y="200" class="wf-text-muted">••••••••••••</text>

                    <text x="635" y="232" class="wf-text-body">Kata Sandi Baru</text>
                    <rect x="635" y="240" width="310" height="32" class="wf-input" />
                    <text x="645" y="260" class="wf-text-muted">Minimal 8 karakter (kombinasi huruf &amp; angka)</text>

                    <text x="635" y="292" class="wf-text-body">Konfirmasi Kata Sandi Baru</text>
                    <rect x="635" y="300" width="310" height="32" class="wf-input" />
                    <text x="645" y="320" class="wf-text-muted">Ulangi kata sandi baru...</text>

                    <rect x="635" y="420" width="310" height="36" class="wf-btn-primary" />
                    <text x="790" y="443" class="wf-text-btn-pri">PERBARUI KATA SANDI</text>
                `)
            }
        ]
    },

    // =========================================================================
    // 2. ADMIN
    // =========================================================================
    {
        name: "2. Admin",
        screens: [
            {
                id: "1-0",
                num: "2.1",
                title: "Dashboard Utama Admin",
                desc: "Statistik Akun Pengguna, Siswa, Kelas, Guru BK & Log Aktivitas Sistem",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("DASHBOARD UTAMA ADMIN", "[Nama Admin]", "Admin")}
                    ${getSidebarSVG("admin", 0)}
                    <text x="245" y="90" class="wf-text-title">RINGKASAN SISTEM (STATISTIK)</text>

                    <rect x="245" y="110" width="135" height="85" class="wf-card" />
                    <text x="260" y="132" class="wf-text-muted">USER AKTIF</text>
                    <text x="260" y="165" class="wf-text-title" style="font-size:24px;">1,248</text>
                    <text x="260" y="182" class="wf-text-muted">Akun Terdaftar</text>

                    <rect x="390" y="110" width="135" height="85" class="wf-card" />
                    <text x="405" y="132" class="wf-text-muted">USER NONAKTIF</text>
                    <text x="405" y="165" class="wf-text-title" style="font-size:24px;">12</text>
                    <text x="405" y="182" class="wf-text-muted">Perlu Verifikasi</text>

                    <rect x="535" y="110" width="135" height="85" class="wf-card" />
                    <text x="550" y="132" class="wf-text-muted">TOTAL SISWA</text>
                    <text x="550" y="165" class="wf-text-title" style="font-size:24px;">1,180</text>
                    <text x="550" y="182" class="wf-text-muted">Siswa Aktif</text>

                    <rect x="680" y="110" width="135" height="85" class="wf-card" />
                    <text x="695" y="132" class="wf-text-muted">JUMLAH KELAS</text>
                    <text x="695" y="165" class="wf-text-title" style="font-size:24px;">36</text>
                    <text x="695" y="182" class="wf-text-muted">Rombel Sekolah</text>

                    <rect x="825" y="110" width="140" height="85" class="wf-card" />
                    <text x="840" y="132" class="wf-text-muted">GURU BK</text>
                    <text x="840" y="165" class="wf-text-title" style="font-size:24px;">6</text>
                    <text x="840" y="182" class="wf-text-muted">Konselor Aktif</text>

                    <rect x="245" y="215" width="720" height="390" class="wf-card" />
                    <rect x="245" y="215" width="720" height="36" class="wf-card-header" />
                    <text x="265" y="238" class="wf-text-body" style="font-weight:700;">LOG AKTIVITAS SISTEM TERKINI</text>

                    <rect x="245" y="251" width="720" height="28" class="wf-table-header" />
                    <text x="265" y="269" class="wf-text-body" style="font-weight:600;">WAKTU</text>
                    <text x="375" y="269" class="wf-text-body" style="font-weight:600;">PENGGUNA / AKUN</text>
                    <text x="540" y="269" class="wf-text-body" style="font-weight:600;">ROLE</text>
                    <text x="640" y="269" class="wf-text-body" style="font-weight:600;">AKTIVITAS / PROSES</text>
                    <text x="880" y="269" class="wf-text-body" style="font-weight:600;">STATUS</text>

                    <rect x="245" y="279" width="720" height="30" class="wf-table-row" />
                    <text x="265" y="298" class="wf-text-muted">15/08 16:45</text>
                    <text x="375" y="298" class="wf-text-body">guru_bk_01</text>
                    <text x="540" y="298" class="wf-text-body">Guru BK</text>
                    <text x="640" y="298" class="wf-text-body">Validasi Pengajuan Konseling #PK-102</text>
                    <rect x="875" y="285" width="70" height="18" class="wf-badge" />
                    <text x="910" y="298" class="wf-text-badge">SUKSES</text>

                    <rect x="245" y="309" width="720" height="30" class="wf-table-row-alt" />
                    <text x="265" y="328" class="wf-text-muted">15/08 16:30</text>
                    <text x="375" y="328" class="wf-text-body">siswa_202401</text>
                    <text x="540" y="328" class="wf-text-body">Siswa</text>
                    <text x="640" y="328" class="wf-text-body">Pengajuan Konseling Mandiri</text>
                    <rect x="875" y="315" width="70" height="18" class="wf-badge" />
                    <text x="910" y="328" class="wf-text-badge">SUKSES</text>
                `)
            },
            {
                id: "1-1",
                num: "2.2",
                title: "Daftar Pengguna",
                desc: "Tabel Pengguna, Filter Jabatan, Status Akun & Tombol Aksi Tambah/Edit/Detail/Reset",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("MANAJEMEN PENGGUNA", "[Nama Admin]", "Admin")}
                    ${getSidebarSVG("admin", 1)}
                    <text x="245" y="90" class="wf-text-title">MANAJEMEN PENGGUNA</text>
                    <text x="245" y="110" class="wf-text-muted">Kelola akun pengguna, penetapan jabatan untuk hak akses, dan reset sandi jika user lupa</text>

                    <rect x="245" y="125" width="280" height="32" class="wf-input" />
                    <text x="255" y="146" class="wf-text-muted">Cari nama, username, NIP...</text>

                    <rect x="535" y="125" width="140" height="32" class="wf-input" />
                    <text x="545" y="146" class="wf-text-body">Semua Jabatan ▾</text>

                    <rect x="685" y="125" width="120" height="32" class="wf-input" />
                    <text x="695" y="146" class="wf-text-body">Status: Aktif ▾</text>

                    <rect x="825" y="125" width="140" height="32" class="wf-btn-primary" />
                    <text x="895" y="146" class="wf-text-btn-pri">+ Tambah Akun</text>

                    <rect x="245" y="170" width="720" height="420" class="wf-card" />
                    <rect x="245" y="170" width="720" height="32" class="wf-table-header" />
                    <text x="260" y="191" class="wf-text-body" style="font-weight:600;">NO</text>
                    <text x="295" y="191" class="wf-text-body" style="font-weight:600;">USERNAME</text>
                    <text x="410" y="191" class="wf-text-body" style="font-weight:600;">NAMA LENGKAP</text>
                    <text x="590" y="191" class="wf-text-body" style="font-weight:600;">JABATAN (ROLE)</text>
                    <text x="750" y="191" class="wf-text-body" style="font-weight:600;">STATUS</text>
                    <text x="830" y="191" class="wf-text-body" style="font-weight:600;">AKSI ADMINISTRATOR</text>

                    <rect x="245" y="202" width="720" height="36" class="wf-table-row" />
                    <text x="260" y="224" class="wf-text-body">1</text>
                    <text x="295" y="224" class="wf-text-body" style="font-weight:600;">admin_master</text>
                    <text x="410" y="224" class="wf-text-body">[Nama Admin]</text>
                    <text x="590" y="224" class="wf-text-body">Admin</text>
                    <rect x="745" y="210" width="55" height="20" class="wf-badge" />
                    <text x="772" y="223" class="wf-text-badge">Aktif</text>
                    <rect x="825" y="210" width="40" height="20" class="wf-btn-secondary" /><text x="845" y="223" class="wf-text-btn-sec" style="font-size:9px;">Edit</text>
                    <rect x="870" y="210" width="45" height="20" class="wf-btn-secondary" /><text x="892" y="223" class="wf-text-btn-sec" style="font-size:9px;">Detail</text>
                    <rect x="920" y="210" width="40" height="20" class="wf-btn-secondary" /><text x="940" y="223" class="wf-text-btn-sec" style="font-size:8.5px;">Reset</text>

                    <rect x="245" y="238" width="720" height="36" class="wf-table-row-alt" />
                    <text x="260" y="260" class="wf-text-body">2</text>
                    <text x="295" y="260" class="wf-text-body" style="font-weight:600;">guru_bk_01</text>
                    <text x="410" y="260" class="wf-text-body">[Nama Guru]</text>
                    <text x="590" y="260" class="wf-text-body">Guru BK</text>
                    <rect x="745" y="246" width="55" height="20" class="wf-badge" />
                    <text x="772" y="259" class="wf-text-badge">Aktif</text>
                    <rect x="825" y="246" width="40" height="20" class="wf-btn-secondary" /><text x="845" y="259" class="wf-text-btn-sec" style="font-size:9px;">Edit</text>
                    <rect x="870" y="246" width="45" height="20" class="wf-btn-secondary" /><text x="892" y="259" class="wf-text-btn-sec" style="font-size:9px;">Detail</text>
                    <rect x="920" y="246" width="40" height="20" class="wf-btn-secondary" /><text x="940" y="259" class="wf-text-btn-sec" style="font-size:8.5px;">Reset</text>

                    <rect x="245" y="274" width="720" height="36" class="wf-table-row" />
                    <text x="260" y="296" class="wf-text-body">3</text>
                    <text x="295" y="296" class="wf-text-body" style="font-weight:600;">walas_x_tkj1</text>
                    <text x="410" y="296" class="wf-text-body">[Nama Guru]</text>
                    <text x="590" y="296" class="wf-text-body">Wali Kelas</text>
                    <rect x="745" y="282" width="55" height="20" class="wf-badge" />
                    <text x="772" y="295" class="wf-text-badge">Aktif</text>
                    <rect x="825" y="282" width="40" height="20" class="wf-btn-secondary" /><text x="845" y="295" class="wf-text-btn-sec" style="font-size:9px;">Edit</text>
                    <rect x="870" y="282" width="45" height="20" class="wf-btn-secondary" /><text x="892" y="295" class="wf-text-btn-sec" style="font-size:9px;">Detail</text>
                    <rect x="920" y="282" width="40" height="20" class="wf-btn-secondary" /><text x="940" y="295" class="wf-text-btn-sec" style="font-size:8.5px;">Reset</text>
                `)
            },
            {
                id: "1-2",
                num: "2.3",
                title: "Form Tambah Pengguna",
                desc: "Input Data Akun Baru: Username, Password Awal, NIP/NISN, Nama Lengkap, No HP, Role & Status",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("PENDAFTARAN AKUN PENGGUNA", "[Nama Admin]", "Admin")}
                    ${getSidebarSVG("admin", 1)}
                    <rect x="220" y="60" width="770" height="570" class="wf-modal-overlay" />

                    <rect x="330" y="85" width="550" height="520" class="wf-modal" />
                    <rect x="330" y="85" width="550" height="40" class="wf-card-header" />
                    <text x="350" y="110" class="wf-text-body" style="font-weight:700;">FORM PENDAFTARAN AKUN PENGGUNA BARU</text>
                    <rect x="840" y="93" width="25" height="24" class="wf-btn-secondary" /><text x="852" y="109" class="wf-text-btn-sec">✕</text>

                    <text x="360" y="150" class="wf-text-body">Username</text>
                    <rect x="360" y="158" width="490" height="30" class="wf-input" />
                    <text x="375" y="178" class="wf-text-body">guru_bk_syahril</text>

                    <text x="360" y="208" class="wf-text-body">Password Default Awal</text>
                    <rect x="360" y="216" width="490" height="30" class="wf-input" />
                    <text x="375" y="236" class="wf-text-muted">••••••••••••</text>

                    <text x="360" y="266" class="wf-text-body">NIP / NUPTK / NISN</text>
                    <rect x="360" y="274" width="490" height="30" class="wf-input" />
                    <text x="375" y="294" class="wf-text-body">19850412 201101 1 009</text>

                    <text x="360" y="324" class="wf-text-body">Nama Lengkap &amp; Gelar</text>
                    <rect x="360" y="332" width="490" height="30" class="wf-input" />
                    <text x="375" y="352" class="wf-text-body">[Nama Guru]</text>

                    <text x="360" y="382" class="wf-text-body">Nomor WhatsApp Aktif</text>
                    <rect x="360" y="390" width="490" height="30" class="wf-input" />
                    <text x="375" y="410" class="wf-text-body">0812-7890-1234</text>

                    <text x="360" y="440" class="wf-text-body">Jabatan / Hak Akses</text>
                    <rect x="360" y="448" width="235" height="30" class="wf-input" />
                    <text x="375" y="468" class="wf-text-body">Guru BK ▾</text>

                    <text x="615" y="440" class="wf-text-body">Status Akun</text>
                    <rect x="615" y="448" width="235" height="30" class="wf-input" />
                    <text x="630" y="468" class="wf-text-body">Aktif ▾</text>

                    <rect x="635" y="540" width="100" height="36" class="wf-btn-secondary" />
                    <text x="685" y="563" class="wf-text-btn-sec">Batal</text>

                    <rect x="745" y="540" width="105" height="36" class="wf-btn-primary" />
                    <text x="797" y="563" class="wf-text-btn-pri">Simpan Akun</text>
                `)
            },
            {
                id: "1-2-edit",
                num: "2.4",
                title: "Form Edit Pengguna",
                desc: "Perubahan Data Pengguna, Hak Akses Jabatan, Nomor WA & Status Akun",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("EDIT DATA PENGGUNA", "[Nama Admin]", "Admin")}
                    ${getSidebarSVG("admin", 1)}
                    <rect x="220" y="60" width="770" height="570" class="wf-modal-overlay" />

                    <rect x="330" y="85" width="550" height="520" class="wf-modal" />
                    <rect x="330" y="85" width="550" height="40" class="wf-card-header" />
                    <text x="350" y="110" class="wf-text-body" style="font-weight:700;">FORM EDIT DATA PENGGUNA (#USR-102)</text>
                    <rect x="840" y="93" width="25" height="24" class="wf-btn-secondary" /><text x="852" y="109" class="wf-text-btn-sec">✕</text>

                    <text x="360" y="150" class="wf-text-body">Username (Readonly)</text>
                    <rect x="360" y="158" width="490" height="30" class="wf-input" fill="#f8fafc" />
                    <text x="375" y="178" class="wf-text-muted">guru_bk_01 [Terkunci]</text>

                    <text x="360" y="208" class="wf-text-body">NIP / NUPTK / NISN</text>
                    <rect x="360" y="216" width="490" height="30" class="wf-input" />
                    <text x="375" y="236" class="wf-text-body">19820315 200801 1 004</text>

                    <text x="360" y="266" class="wf-text-body">Nama Lengkap &amp; Gelar</text>
                    <rect x="360" y="274" width="490" height="30" class="wf-input" />
                    <text x="375" y="294" class="wf-text-body">[Nama Guru]</text>

                    <text x="360" y="324" class="wf-text-body">Nomor WhatsApp Aktif</text>
                    <rect x="360" y="332" width="490" height="30" class="wf-input" />
                    <text x="375" y="352" class="wf-text-body">0812-6789-0011</text>

                    <text x="360" y="382" class="wf-text-body">Jabatan / Hak Akses</text>
                    <rect x="360" y="390" width="490" height="30" class="wf-input" />
                    <text x="375" y="410" class="wf-text-body">Guru BK ▾</text>

                    <text x="360" y="440" class="wf-text-body">Status Akun</text>
                    <rect x="360" y="448" width="490" height="30" class="wf-input" />
                    <text x="375" y="468" class="wf-text-body">Aktif ▾</text>

                    <rect x="605" y="540" width="105" height="36" class="wf-btn-secondary" />
                    <text x="657" y="563" class="wf-text-btn-sec">Batal</text>

                    <rect x="720" y="540" width="130" height="36" class="wf-btn-primary" />
                    <text x="785" y="563" class="wf-text-btn-pri">Simpan Perubahan</text>
                `)
            },
            {
                id: "1-3",
                num: "2.5",
                title: "Manajemen Kelas & Rombel",
                desc: "Pengaturan Tahun Ajaran, Jurusan, Penetapan Wali Kelas & Plotting Siswa per Rombel",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("MANAJEMEN KELAS & ROMBEL", "[Nama Admin]", "Admin")}
                    ${getSidebarSVG("admin", 2)}
                    <text x="245" y="90" class="wf-text-title">MANAJEMEN KELAS &amp; PENEMPATAN SISWA</text>
                    <text x="245" y="110" class="wf-text-muted">Pengaturan Tahun Ajaran, Jurusan, Wali Kelas, dan Rombongan Belajar Siswa</text>

                    <rect x="245" y="125" width="180" height="32" class="wf-input" />
                    <text x="255" y="146" class="wf-text-body">Tahun Ajaran: 2025/2026 ▾</text>

                    <rect x="435" y="125" width="150" height="32" class="wf-input" />
                    <text x="445" y="146" class="wf-text-body">Tingkat: Semua ▾</text>

                    <rect x="595" y="125" width="180" height="32" class="wf-input" />
                    <text x="605" y="146" class="wf-text-body">Jurusan: Semua ▾</text>

                    <rect x="805" y="125" width="160" height="32" class="wf-btn-primary" />
                    <text x="885" y="146" class="wf-text-btn-pri">+ Tambah Kelas</text>

                    <rect x="245" y="170" width="720" height="420" class="wf-card" />
                    <rect x="245" y="170" width="720" height="32" class="wf-table-header" />
                    <text x="260" y="191" class="wf-text-body" style="font-weight:600;">KODE</text>
                    <text x="325" y="191" class="wf-text-body" style="font-weight:600;">NAMA KELAS</text>
                    <text x="435" y="191" class="wf-text-body" style="font-weight:600;">TINGKAT</text>
                    <text x="510" y="191" class="wf-text-body" style="font-weight:600;">JURUSAN</text>
                    <text x="650" y="191" class="wf-text-body" style="font-weight:600;">WALI KELAS</text>
                    <text x="810" y="191" class="wf-text-body" style="font-weight:600;">SISWA</text>
                    <text x="870" y="191" class="wf-text-body" style="font-weight:600;">AKSI</text>

                    <rect x="245" y="202" width="720" height="36" class="wf-table-row" />
                    <text x="260" y="224" class="wf-text-body">K-101</text>
                    <text x="325" y="224" class="wf-text-body" style="font-weight:600;">X TJKT 1</text>
                    <text x="435" y="224" class="wf-text-body">Kelas X</text>
                    <text x="510" y="224" class="wf-text-body">Teknik Jaringan Komputer &amp; Telekomunikasi (TJKT)</text>
                    <text x="650" y="224" class="wf-text-body">[Nama Guru]</text>
                    <text x="810" y="224" class="wf-text-body">34</text>
                    <rect x="860" y="210" width="40" height="20" class="wf-btn-secondary" /><text x="880" y="223" class="wf-text-btn-sec" style="font-size:9px;">Edit</text>
                    <rect x="905" y="210" width="55" height="20" class="wf-btn-secondary" /><text x="932" y="223" class="wf-text-btn-sec" style="font-size:8.5px;">Atur</text>

                    <rect x="245" y="238" width="720" height="36" class="wf-table-row-alt" />
                    <text x="260" y="260" class="wf-text-body">K-102</text>
                    <text x="325" y="260" class="wf-text-body" style="font-weight:600;">XI TJKT 2</text>
                    <text x="435" y="260" class="wf-text-body">Kelas XI</text>
                    <text x="510" y="260" class="wf-text-body">Teknik Jaringan Komputer &amp; Telekomunikasi (TJKT)</text>
                    <text x="650" y="260" class="wf-text-body">[Nama Guru]</text>
                    <text x="810" y="260" class="wf-text-body">32</text>
                    <rect x="860" y="246" width="40" height="20" class="wf-btn-secondary" /><text x="880" y="259" class="wf-text-btn-sec" style="font-size:9px;">Edit</text>
                    <rect x="905" y="246" width="55" height="20" class="wf-btn-secondary" /><text x="932" y="259" class="wf-text-btn-sec" style="font-size:8.5px;">Atur</text>
                `)
            },
            {
                id: "1-4",
                num: "2.6",
                title: "Form Tambah Kelas Baru",
                desc: "Input Data Kelas: Tahun Ajaran, Tingkat Kelas, Jurusan, Nama Kelas & Penetapan Wali Kelas",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("TAMBAH KELAS BARU", "[Nama Admin]", "Admin")}
                    ${getSidebarSVG("admin", 2)}
                    <rect x="220" y="60" width="770" height="570" class="wf-modal-overlay" />

                    <rect x="330" y="80" width="550" height="520" class="wf-modal" />
                    <rect x="330" y="80" width="550" height="40" class="wf-card-header" />
                    <text x="350" y="105" class="wf-text-body" style="font-weight:700;">FORM TAMBAH KELAS &amp; PENETAPAN WALI KELAS</text>
                    <rect x="840" y="88" width="25" height="24" class="wf-btn-secondary" /><text x="852" y="104" class="wf-text-btn-sec">✕</text>

                    <text x="360" y="145" class="wf-text-body">1. Tahun Ajaran Aktif</text>
                    <rect x="360" y="153" width="490" height="32" class="wf-input" />
                    <text x="375" y="174" class="wf-text-body">2025/2026 - Semester Genap ▾</text>

                    <text x="360" y="205" class="wf-text-body">2. Tingkat Kelas</text>
                    <rect x="360" y="213" width="490" height="32" class="wf-input" />
                    <text x="375" y="234" class="wf-text-body">Kelas X ▾</text>

                    <text x="360" y="265" class="wf-text-body">3. Program / Kompetensi Keahlian</text>
                    <rect x="360" y="273" width="490" height="32" class="wf-input" />
                    <text x="375" y="294" class="wf-text-body">Teknik Jaringan Komputer dan Telekomunikasi (TJKT) ▾</text>

                    <text x="360" y="325" class="wf-text-body">4. Nama Rombel Kelas</text>
                    <rect x="360" y="333" width="490" height="32" class="wf-input" />
                    <text x="375" y="354" class="wf-text-body">X TJKT 1</text>

                    <text x="360" y="385" class="wf-text-body">5. Penetapan Wali Kelas</text>
                    <rect x="360" y="393" width="490" height="32" class="wf-input" />
                    <text x="375" y="414" class="wf-text-body">[Nama Guru] - NIP 19800115 200501 1 008 ▾</text>

                    <text x="360" y="450" class="wf-text-muted" style="font-size:10px;">*Wali Kelas yang dipilih otomatis mendapatkan hak akses pemantauan khusus kelas ini.</text>

                    <rect x="625" y="535" width="105" height="38" class="wf-btn-secondary" />
                    <text x="677" y="559" class="wf-text-btn-sec">Batal</text>

                    <rect x="740" y="535" width="110" height="38" class="wf-btn-primary" />
                    <text x="795" y="559" class="wf-text-btn-pri">Simpan Kelas</text>
                `)
            },
            {
                id: "1-4-edit",
                num: "2.7",
                title: "Form Edit Kelas",
                desc: "Perubahan Data Rombel Kelas, Penetapan Wali Kelas & Tingkat Kelas",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("EDIT DATA KELAS", "[Nama Admin]", "Admin")}
                    ${getSidebarSVG("admin", 2)}
                    <rect x="220" y="60" width="770" height="570" class="wf-modal-overlay" />

                    <rect x="330" y="80" width="550" height="520" class="wf-modal" />
                    <rect x="330" y="80" width="550" height="40" class="wf-card-header" />
                    <text x="350" y="105" class="wf-text-body" style="font-weight:700;">FORM EDIT KELAS &amp; WALI KELAS (#K-101)</text>
                    <rect x="840" y="88" width="25" height="24" class="wf-btn-secondary" /><text x="852" y="104" class="wf-text-btn-sec">✕</text>

                    <text x="360" y="145" class="wf-text-body">1. Tahun Ajaran</text>
                    <rect x="360" y="153" width="490" height="32" class="wf-input" />
                    <text x="375" y="174" class="wf-text-body">2025/2026 - Semester Genap ▾</text>

                    <text x="360" y="205" class="wf-text-body">2. Tingkat Kelas</text>
                    <rect x="360" y="213" width="490" height="32" class="wf-input" />
                    <text x="375" y="234" class="wf-text-body">Kelas X ▾</text>

                    <text x="360" y="265" class="wf-text-body">3. Program / Kompetensi Keahlian</text>
                    <rect x="360" y="273" width="490" height="32" class="wf-input" />
                    <text x="375" y="294" class="wf-text-body">Teknik Jaringan Komputer dan Telekomunikasi (TJKT) ▾</text>

                    <text x="360" y="325" class="wf-text-body">4. Nama Rombel Kelas</text>
                    <rect x="360" y="333" width="490" height="32" class="wf-input" />
                    <text x="375" y="354" class="wf-text-body">X TJKT 1</text>

                    <text x="360" y="385" class="wf-text-body">5. Penetapan Wali Kelas</text>
                    <rect x="360" y="393" width="490" height="32" class="wf-input" />
                    <text x="375" y="414" class="wf-text-body">[Nama Guru] - NIP 19800115 200501 1 008 ▾</text>

                    <rect x="605" y="535" width="105" height="38" class="wf-btn-secondary" />
                    <text x="657" y="559" class="wf-text-btn-sec">Batal</text>

                    <rect x="720" y="535" width="130" height="38" class="wf-btn-primary" />
                    <text x="785" y="559" class="wf-text-btn-pri">Simpan Perubahan</text>
                `)
            },
            {
                id: "1-5",
                num: "2.8",
                title: "Data Siswa & Kontak Ortu",
                desc: "Pencarian Siswa, NISN, Kelas, Kontak WhatsApp Orang Tua / Wali & Status",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("DATA SISWA & KONTAK ORTU", "[Nama Admin]", "Admin")}
                    ${getSidebarSVG("admin", 3)}
                    <text x="245" y="90" class="wf-text-title">DATA SISWA SMK NEGERI 2 GUGUAK</text>
                    <text x="245" y="110" class="wf-text-muted">Master database siswa, kontak darurat orang tua/wali untuk notifikasi surat panggilan</text>

                    <rect x="245" y="125" width="280" height="32" class="wf-input" />
                    <text x="255" y="146" class="wf-text-muted">Cari nama siswa, NIS, NISN...</text>

                    <rect x="535" y="125" width="140" height="32" class="wf-input" />
                    <text x="545" y="146" class="wf-text-body">Semua Kelas ▾</text>

                    <rect x="685" y="125" width="120" height="32" class="wf-input" />
                    <text x="695" y="146" class="wf-text-body">Status: Aktif ▾</text>

                    <rect x="815" y="125" width="150" height="32" class="wf-btn-primary" />
                    <text x="890" y="146" class="wf-text-btn-pri">+ Tambah Siswa</text>

                    <rect x="245" y="170" width="720" height="420" class="wf-card" />
                    <rect x="245" y="170" width="720" height="32" class="wf-table-header" />
                    <text x="260" y="191" class="wf-text-body" style="font-weight:600;">NISN</text>
                    <text x="340" y="191" class="wf-text-body" style="font-weight:600;">NAMA SISWA</text>
                    <text x="490" y="191" class="wf-text-body" style="font-weight:600;">KELAS</text>
                    <text x="570" y="191" class="wf-text-body" style="font-weight:600;">NO WA SISWA</text>
                    <text x="680" y="191" class="wf-text-body" style="font-weight:600;">NO WA ORTU</text>
                    <text x="870" y="191" class="wf-text-body" style="font-weight:600;">AKSI</text>

                    <rect x="245" y="202" width="720" height="36" class="wf-table-row" />
                    <text x="260" y="224" class="wf-text-body">0071234567</text>
                    <text x="340" y="224" class="wf-text-body" style="font-weight:600;">[Nama Siswa]</text>
                    <text x="490" y="224" class="wf-text-body">XI TJKT 1</text>
                    <text x="570" y="224" class="wf-text-body">0812-1111-2222</text>
                    <text x="680" y="224" class="wf-text-body">0813-7456-9999</text>
                    <rect x="855" y="210" width="45" height="20" class="wf-btn-secondary" /><text x="877" y="223" class="wf-text-btn-sec" style="font-size:9px;">Edit</text>
                    <rect x="905" y="210" width="55" height="20" class="wf-btn-secondary" /><text x="932" y="223" class="wf-text-btn-sec" style="font-size:8.5px;">Detail</text>

                    <rect x="245" y="238" width="720" height="36" class="wf-table-row-alt" />
                    <text x="260" y="260" class="wf-text-body">0082345678</text>
                    <text x="340" y="260" class="wf-text-body" style="font-weight:600;">[Nama Siswa]</text>
                    <text x="490" y="260" class="wf-text-body">XI TJKT 1</text>
                    <text x="570" y="260" class="wf-text-body">0812-3333-4444</text>
                    <text x="680" y="260" class="wf-text-body">0812-6666-7777</text>
                    <rect x="855" y="246" width="45" height="20" class="wf-btn-secondary" /><text x="877" y="259" class="wf-text-btn-sec" style="font-size:9px;">Edit</text>
                    <rect x="905" y="246" width="55" height="20" class="wf-btn-secondary" /><text x="932" y="259" class="wf-text-btn-sec" style="font-size:8.5px;">Detail</text>
                `)
            },
            {
                id: "1-5-edit",
                num: "2.9",
                title: "Form Edit Data Siswa",
                desc: "Perubahan Biodata Siswa, Penempatan Kelas, Nomor WhatsApp Siswa & Orang Tua",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("EDIT DATA SISWA", "[Nama Admin]", "Admin")}
                    ${getSidebarSVG("admin", 3)}
                    <rect x="220" y="60" width="770" height="570" class="wf-modal-overlay" />

                    <rect x="330" y="70" width="550" height="540" class="wf-modal" />
                    <rect x="330" y="70" width="550" height="40" class="wf-card-header" />
                    <text x="350" y="95" class="wf-text-body" style="font-weight:700;">FORM EDIT DATA SISWA &amp; KONTAK (#SISWA-007123)</text>
                    <rect x="840" y="78" width="25" height="24" class="wf-btn-secondary" /><text x="852" y="94" class="wf-text-btn-sec">✕</text>

                    <text x="360" y="130" class="wf-text-body">NISN Siswa</text>
                    <rect x="360" y="138" width="490" height="30" class="wf-input" />
                    <text x="375" y="158" class="wf-text-body">0071234567</text>

                    <text x="360" y="185" class="wf-text-body">Nama Lengkap Siswa</text>
                    <rect x="360" y="193" width="490" height="30" class="wf-input" />
                    <text x="375" y="213" class="wf-text-body">[Nama Siswa]</text>

                    <text x="360" y="240" class="wf-text-body">Penempatan Kelas</text>
                    <rect x="360" y="248" width="490" height="30" class="wf-input" />
                    <text x="375" y="268" class="wf-text-body">XI TJKT 1 ▾</text>

                    <text x="360" y="295" class="wf-text-body">Nomor WhatsApp Siswa (Aktif)</text>
                    <rect x="360" y="303" width="490" height="30" class="wf-input" />
                    <text x="375" y="323" class="wf-text-body">0812-1111-2222</text>

                    <text x="360" y="350" class="wf-text-body">Nama Orang Tua / Wali</text>
                    <rect x="360" y="358" width="490" height="30" class="wf-input" />
                    <text x="375" y="378" class="wf-text-body">Hendra Wijaya</text>

                    <text x="360" y="405" class="wf-text-body">Nomor WhatsApp Orang Tua (Aktif)</text>
                    <rect x="360" y="413" width="490" height="30" class="wf-input" />
                    <text x="375" y="433" class="wf-text-body">0813-7456-9999</text>

                    <rect x="605" y="555" width="105" height="36" class="wf-btn-secondary" />
                    <text x="657" y="578" class="wf-text-btn-sec">Batal</text>

                    <rect x="720" y="555" width="130" height="36" class="wf-btn-primary" />
                    <text x="785" y="578" class="wf-text-btn-pri">Simpan Perubahan</text>
                `)
            },
            {
                id: "1-6",
                num: "2.10",
                title: "Pengaturan Sistem & WA",
                desc: "Konfigurasi Endpoint API Gateway, API Secret Token & Identitas Sekolah",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("PENGATURAN SISTEM & WA GATEWAY", "[Nama Admin]", "Admin")}
                    ${getSidebarSVG("admin", 4)}
                    <text x="245" y="90" class="wf-text-title">PENGATURAN SISTEM &amp; INTEGRASI GATEWAY</text>

                    <rect x="245" y="115" width="450" height="490" class="wf-card" />
                    <rect x="245" y="115" width="450" height="34" class="wf-card-header" />
                    <text x="260" y="137" class="wf-text-body" style="font-weight:700;">KONFIGURASI WHATSAPP GATEWAY</text>

                    <text x="265" y="172" class="wf-text-body">URL Endpoint API Gateway</text>
                    <rect x="265" y="180" width="410" height="32" class="wf-input" />
                    <text x="275" y="200" class="wf-text-body">https://api.gateway.sch.id/send</text>

                    <text x="265" y="232" class="wf-text-body">API Secret Token (Keamanan Terenkripsi)</text>
                    <rect x="265" y="240" width="410" height="32" class="wf-input" />
                    <text x="275" y="260" class="wf-text-muted">•••••••••••••••••••••••••••••••••••• [Tersimpan]</text>

                    <text x="265" y="292" class="wf-text-body">Nomor Pengirim WhatsApp Sekolah (Sender Number)</text>
                    <rect x="265" y="300" width="410" height="32" class="wf-input" />
                    <text x="275" y="320" class="wf-text-body">0852-1234-5678 [Status: Terhubung Online]</text>

                    <rect x="265" y="350" width="200" height="32" class="wf-btn-secondary" />
                    <text x="365" y="370" class="wf-text-btn-sec">Uji Koneksi Gateway</text>

                    <rect x="265" y="550" width="410" height="36" class="wf-btn-primary" />
                    <text x="470" y="573" class="wf-text-btn-pri">SIMPAN KONFIGURASI GATEWAY</text>

                    <rect x="715" y="115" width="250" height="490" class="wf-card" />
                    <rect x="715" y="115" width="250" height="34" class="wf-card-header" />
                    <text x="730" y="137" class="wf-text-body" style="font-weight:700;">IDENTITAS SEKOLAH</text>

                    <text x="730" y="172" class="wf-text-body">Nama Satuan Pendidikan</text>
                    <rect x="730" y="180" width="220" height="32" class="wf-input" />
                    <text x="740" y="200" class="wf-text-body" style="font-size:10px;">SMK Negeri 2 Guguak</text>

                    <text x="730" y="232" class="wf-text-body">Tahun Ajaran Aktif</text>
                    <rect x="730" y="240" width="220" height="32" class="wf-input" />
                    <text x="740" y="260" class="wf-text-body">2025/2026 - Genap</text>
                `)
            }
        ]
    },

    // =========================================================================
    // 3. GURU BK
    // =========================================================================
    {
        name: "3. Guru BK",
        screens: [
            {
                id: "2-0",
                num: "3.1",
                title: "Dashboard Guru BK",
                desc: "Statistik Slot Jadwal, Pengajuan Masuk, Sesi Terjadwal, Selesai & Notifikasi Konseling",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("DASHBOARD RUANG GURU BK", "[Nama Guru]", "Guru BK")}
                    ${getSidebarSVG("bk", 0)}
                    <text x="245" y="90" class="wf-text-title">RINGKASAN LAYANAN KONSELING</text>

                    <rect x="245" y="110" width="135" height="85" class="wf-card" />
                    <text x="260" y="132" class="wf-text-muted">SLOT TERSEDIA</text>
                    <text x="260" y="165" class="wf-text-title" style="font-size:24px;">8</text>
                    <text x="260" y="182" class="wf-text-muted">Minggu Ini</text>

                    <rect x="390" y="110" width="135" height="85" class="wf-card" />
                    <text x="405" y="132" class="wf-text-muted">PENGAJUAN MASUK</text>
                    <text x="405" y="165" class="wf-text-title" style="font-size:24px;">5</text>
                    <text x="405" y="182" class="wf-text-muted">Perlu Validasi</text>

                    <rect x="535" y="110" width="135" height="85" class="wf-card" />
                    <text x="550" y="132" class="wf-text-muted">SESI TERJADWAL</text>
                    <text x="550" y="165" class="wf-text-title" style="font-size:24px;">4</text>
                    <text x="550" y="182" class="wf-text-muted">Siap Dilaksanakan</text>

                    <rect x="680" y="110" width="135" height="85" class="wf-card" />
                    <text x="695" y="132" class="wf-text-muted">SESI SELESAI</text>
                    <text x="695" y="165" class="wf-text-title" style="font-size:24px;">42</text>
                    <text x="695" y="182" class="wf-text-muted">Konseling Tuntas</text>

                    <rect x="825" y="110" width="140" height="85" class="wf-card" />
                    <text x="840" y="132" class="wf-text-muted">SURAT PANGGILAN</text>
                    <text x="840" y="165" class="wf-text-title" style="font-size:24px;">3</text>
                    <text x="840" y="182" class="wf-text-muted">Terkirim ke Ortu</text>

                    <rect x="245" y="215" width="720" height="390" class="wf-card" />
                    <rect x="245" y="215" width="720" height="36" class="wf-card-header" />
                    <text x="265" y="238" class="wf-text-body" style="font-weight:700;">JADWAL KONSELING HARI INI &amp; MENDATANG</text>

                    <rect x="245" y="251" width="720" height="28" class="wf-table-header" />
                    <text x="260" y="269" class="wf-text-body" style="font-weight:600;">WAKTU / JAM</text>
                    <text x="370" y="269" class="wf-text-body" style="font-weight:600;">NAMA SISWA</text>
                    <text x="510" y="269" class="wf-text-body" style="font-weight:600;">KELAS</text>
                    <text x="600" y="269" class="wf-text-body" style="font-weight:600;">JENIS LAYANAN</text>
                    <text x="770" y="269" class="wf-text-body" style="font-weight:600;">STATUS</text>
                    <text x="875" y="269" class="wf-text-body" style="font-weight:600;">AKSI</text>

                    <rect x="245" y="279" width="720" height="34" class="wf-table-row" />
                    <text x="260" y="300" class="wf-text-body">Hari ini, 09:00</text>
                    <text x="370" y="300" class="wf-text-body" style="font-weight:600;">[Nama Siswa]</text>
                    <text x="510" y="300" class="wf-text-body">XI TJKT 1</text>
                    <text x="600" y="300" class="wf-text-body">Pribadi / Belajar</text>
                    <rect x="765" y="286" width="75" height="20" class="wf-badge" />
                    <text x="802" y="299" class="wf-text-badge">Terjadwal</text>
                    <rect x="865" y="286" width="85" height="20" class="wf-btn-primary" />
                    <text x="907" y="299" class="wf-text-btn-pri" style="font-size:9.5px;">Mulai Sesi</text>
                `)
            },
            {
                id: "2-1",
                num: "3.2",
                title: "Ketersediaan Jadwal",
                desc: "Tambah Jadwal Slot Konseling, Buka/Tutup Status Slot (Tersedia / Terisi / Ditutup)",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("KETERSEDIAAN JADWAL KONSELING", "[Nama Guru]", "Guru BK")}
                    ${getSidebarSVG("bk", 1)}
                    <text x="245" y="90" class="wf-text-title">PENGATURAN KETERSEDIAAN SLOT KONSELING</text>
                    <text x="245" y="110" class="wf-text-muted">Atur tanggal dan jam layanan yang dapat dipilih secara mandiri oleh siswa</text>

                    <rect x="245" y="125" width="180" height="32" class="wf-input" />
                    <text x="255" y="146" class="wf-text-body">Tanggal: Minggu Ini ▾</text>

                    <rect x="435" y="125" width="160" height="32" class="wf-input" />
                    <text x="445" y="146" class="wf-text-body">Status: Semua Slot ▾</text>

                    <rect x="805" y="125" width="160" height="32" class="wf-btn-primary" />
                    <text x="885" y="146" class="wf-text-btn-pri">+ Tambah Slot Jadwal</text>

                    <rect x="245" y="170" width="720" height="420" class="wf-card" />
                    <rect x="245" y="170" width="720" height="32" class="wf-table-header" />
                    <text x="260" y="191" class="wf-text-body" style="font-weight:600;">HARI / TANGGAL</text>
                    <text x="400" y="191" class="wf-text-body" style="font-weight:600;">JAM LAYANAN</text>
                    <text x="530" y="191" class="wf-text-body" style="font-weight:600;">STATUS SLOT</text>
                    <text x="660" y="191" class="wf-text-body" style="font-weight:600;">SISWA PEMILIH</text>
                    <text x="850" y="191" class="wf-text-body" style="font-weight:600;">AKSI</text>

                    <rect x="245" y="202" width="720" height="36" class="wf-table-row" />
                    <text x="260" y="224" class="wf-text-body">Senin, 18 Agustus 2026</text>
                    <text x="400" y="224" class="wf-text-body">09:00 - 09:45 WIB</text>
                    <rect x="525" y="210" width="75" height="20" class="wf-badge" />
                    <text x="562" y="223" class="wf-text-badge">Tersedia</text>
                    <text x="660" y="224" class="wf-text-muted">(Belum dipilih siswa)</text>
                    <rect x="840" y="210" width="45" height="20" class="wf-btn-secondary" /><text x="862" y="223" class="wf-text-btn-sec" style="font-size:9px;">Edit</text>
                    <rect x="890" y="210" width="65" height="20" class="wf-btn-secondary" /><text x="922" y="223" class="wf-text-btn-sec" style="font-size:9px;">Tutup</text>

                    <rect x="245" y="238" width="720" height="36" class="wf-table-row-alt" />
                    <text x="260" y="260" class="wf-text-body">Senin, 18 Agustus 2026</text>
                    <text x="400" y="260" class="wf-text-body">10:00 - 10:45 WIB</text>
                    <rect x="525" y="246" width="75" height="20" class="wf-badge" />
                    <text x="562" y="259" class="wf-text-badge">Terisi</text>
                    <text x="660" y="260" class="wf-text-body">[Nama Siswa] (XI TJKT 1)</text>
                    <rect x="840" y="246" width="45" height="20" class="wf-btn-secondary" /><text x="862" y="259" class="wf-text-btn-sec" style="font-size:9px;">Edit</text>
                    <rect x="890" y="246" width="65" height="20" class="wf-btn-secondary" /><text x="922" y="259" class="wf-text-btn-sec" style="font-size:9px;">Rincian</text>
                `)
            },
            {
                id: "2-2",
                num: "3.3",
                title: "Modal Tambah Slot Jadwal",
                desc: "Input Data Slot: Tanggal Tersedia, Jam Mulai, Jam Selesai & Status Slot Default (Tersedia)",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("BUKA SLOT JADWAL BARU", "[Nama Guru]", "Guru BK")}
                    ${getSidebarSVG("bk", 1)}
                    <rect x="220" y="60" width="770" height="570" class="wf-modal-overlay" />

                    <rect x="330" y="110" width="550" height="460" class="wf-modal" />
                    <rect x="330" y="110" width="550" height="40" class="wf-card-header" />
                    <text x="350" y="135" class="wf-text-body" style="font-weight:700;">BUKA SLOT KETERSEDIAAN JADWAL KONSELING</text>
                    <rect x="840" y="118" width="25" height="24" class="wf-btn-secondary" /><text x="852" y="134" class="wf-text-btn-sec">✕</text>

                    <text x="360" y="175" class="wf-text-body">Guru BK Konselor</text>
                    <rect x="360" y="183" width="490" height="32" class="wf-input" />
                    <text x="375" y="204" class="wf-text-body">[Nama Guru] (Sedang Login)</text>

                    <text x="360" y="240" class="wf-text-body">Tanggal Ketersediaan Layanan</text>
                    <rect x="360" y="248" width="490" height="32" class="wf-input" />
                    <text x="375" y="269" class="wf-text-body">YYYY-MM-DD (Pilih Kalender: 2026-08-19)</text>

                    <text x="360" y="305" class="wf-text-body">Jam Mulai</text>
                    <rect x="360" y="313" width="235" height="32" class="wf-input" />
                    <text x="375" y="334" class="wf-text-body">10:00 WIB</text>

                    <text x="615" y="305" class="wf-text-body">Jam Selesai</text>
                    <rect x="615" y="313" width="235" height="32" class="wf-input" />
                    <text x="630" y="334" class="wf-text-body">10:45 WIB</text>

                    <text x="360" y="375" class="wf-text-body">Status Awal Slot</text>
                    <rect x="360" y="383" width="490" height="32" class="wf-input" />
                    <text x="375" y="404" class="wf-text-body">Tersedia ▾</text>

                    <rect x="625" y="480" width="105" height="38" class="wf-btn-secondary" />
                    <text x="677" y="504" class="wf-text-btn-sec">Batal</text>

                    <rect x="740" y="480" width="110" height="38" class="wf-btn-primary" />
                    <text x="795" y="504" class="wf-text-btn-pri">Buka Slot</text>
                `)
            },
            {
                id: "2-2-edit",
                num: "3.4",
                title: "Form Edit Slot Jadwal",
                desc: "Perubahan Jam Mulai, Jam Selesai & Status Slot Ketersediaan Konseling",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("EDIT SLOT JADWAL KONSELING", "[Nama Guru]", "Guru BK")}
                    ${getSidebarSVG("bk", 1)}
                    <rect x="220" y="60" width="770" height="570" class="wf-modal-overlay" />

                    <rect x="330" y="110" width="550" height="460" class="wf-modal" />
                    <rect x="330" y="110" width="550" height="40" class="wf-card-header" />
                    <text x="350" y="135" class="wf-text-body" style="font-weight:700;">FORM EDIT SLOT JADWAL (#SLOT-108)</text>
                    <rect x="840" y="118" width="25" height="24" class="wf-btn-secondary" /><text x="852" y="134" class="wf-text-btn-sec">✕</text>

                    <text x="360" y="175" class="wf-text-body">Tanggal Ketersediaan</text>
                    <rect x="360" y="183" width="490" height="32" class="wf-input" />
                    <text x="375" y="204" class="wf-text-body">Senin, 18 Agustus 2026</text>

                    <text x="360" y="240" class="wf-text-body">Jam Mulai</text>
                    <rect x="360" y="248" width="235" height="32" class="wf-input" />
                    <text x="375" y="269" class="wf-text-body">09:00 WIB</text>

                    <text x="615" y="240" class="wf-text-body">Jam Selesai</text>
                    <rect x="615" y="248" width="235" height="32" class="wf-input" />
                    <text x="630" y="269" class="wf-text-body">09:45 WIB</text>

                    <text x="360" y="315" class="wf-text-body">Status Slot</text>
                    <rect x="360" y="323" width="490" height="32" class="wf-input" />
                    <text x="375" y="344" class="wf-text-body">Tersedia (Buka Slot) ▾</text>

                    <rect x="605" y="480" width="105" height="38" class="wf-btn-secondary" />
                    <text x="657" y="504" class="wf-text-btn-sec">Batal</text>

                    <rect x="720" y="480" width="130" height="38" class="wf-btn-primary" />
                    <text x="785" y="504" class="wf-text-btn-pri">Simpan Perubahan</text>
                `)
            },
            {
                id: "2-3",
                num: "3.5",
                title: "Modal Rincian Slot / Siswa",
                desc: "Data Lengkap Siswa Pemilih Slot, Jenis Konseling, Alasan Awal & Status Terkini",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("RINCIAN SLOT & DATA PEMILIH", "[Nama Guru]", "Guru BK")}
                    ${getSidebarSVG("bk", 1)}
                    <rect x="220" y="60" width="770" height="570" class="wf-modal-overlay" />

                    <rect x="330" y="90" width="550" height="500" class="wf-modal" />
                    <rect x="330" y="90" width="550" height="40" class="wf-card-header" />
                    <text x="350" y="115" class="wf-text-body" style="font-weight:700;">DETAIL RINCIAN SLOT KONSELING (#SLOT-2026-018)</text>
                    <rect x="840" y="98" width="25" height="24" class="wf-btn-secondary" /><text x="852" y="114" class="wf-text-btn-sec">✕</text>

                    <text x="360" y="150" class="wf-text-muted">WAKTU &amp; TANGGAL SLOT</text>
                    <text x="360" y="170" class="wf-text-body" style="font-weight:600;">Senin, 18 Agustus 2026 | Jam 10:00 - 10:45 WIB</text>
                    <line x1="360" y1="180" x2="850" y2="180" stroke="#e2e8f0" stroke-width="1" />

                    <text x="360" y="200" class="wf-text-muted">STATUS SLOT</text>
                    <rect x="360" y="210" width="75" height="20" class="wf-badge" />
                    <text x="397" y="223" class="wf-text-badge">Terisi</text>
                    <line x1="360" y1="240" x2="850" y2="240" stroke="#e2e8f0" stroke-width="1" />

                    <text x="360" y="260" class="wf-text-muted">NAMA SISWA PEMILIH / KELAS</text>
                    <text x="360" y="280" class="wf-text-body" style="font-weight:700;">[Nama Siswa] (NISN: 0071234567) - Kelas XI TJKT 1</text>
                    <text x="360" y="300" class="wf-text-muted">No. WA Siswa: 0812-1111-2222 | No. WA Ortu: 0813-7456-9999</text>
                    <line x1="360" y1="315" x2="850" y2="315" stroke="#e2e8f0" stroke-width="1" />

                    <text x="360" y="335" class="wf-text-muted">JENIS LAYANAN YANG DIAJUKAN</text>
                    <text x="360" y="355" class="wf-text-body" style="font-weight:600;">Konseling Pribadi &amp; Motivasi Belajar</text>

                    <text x="360" y="380" class="wf-text-muted">ALASAN PENGAJUAN AWAL DARI SISWA</text>
                    <rect x="360" y="390" width="490" height="60" class="wf-input" />
                    <text x="375" y="415" class="wf-text-body">"Saya merasa kesulitan membagi waktu antara kegiatan organisasi</text>
                    <text x="375" y="430" class="wf-text-body">dan persiapan ujian kompetensi keahlian."</text>

                    <rect x="580" y="525" width="120" height="38" class="wf-btn-secondary" />
                    <text x="640" y="549" class="wf-text-btn-sec">Tutup</text>

                    <rect x="710" y="525" width="140" height="38" class="wf-btn-primary" />
                    <text x="780" y="549" class="wf-text-btn-pri">Validasi Pengajuan</text>
                `)
            },
            {
                id: "2-4",
                num: "3.6",
                title: "Validasi Pengajuan Masuk",
                desc: "Pemeriksaan Data Siswa, Alasan Pengajuan, Aksi Setujui/Tolak & Input Catatan Validasi",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("VALIDASI PENGAJUAN KONSELING", "[Nama Guru]", "Guru BK")}
                    ${getSidebarSVG("bk", 2)}
                    <text x="245" y="90" class="wf-text-title">VALIDASI PENGAJUAN KONSELING (#PK-2026-089)</text>
                    <text x="245" y="110" class="wf-text-muted">Periksa informasi pengajuan dari siswa / rujukan wali kelas sebelum memberikan persetujuan</text>

                    <rect x="245" y="125" width="440" height="480" class="wf-card" />
                    <rect x="245" y="125" width="440" height="34" class="wf-card-header" />
                    <text x="260" y="147" class="wf-text-body" style="font-weight:700;">RINCIAN DATA SISWA &amp; PENGAJUAN</text>

                    <text x="265" y="180" class="wf-text-muted">NAMA SISWA / NISN</text>
                    <text x="265" y="200" class="wf-text-body" style="font-weight:600;">[Nama Siswa] (NISN: 0071234567)</text>

                    <text x="265" y="230" class="wf-text-muted">KELAS / JURUSAN</text>
                    <text x="265" y="250" class="wf-text-body" style="font-weight:600;">XI TJKT 1 / Teknik Jaringan Komputer &amp; Telekomunikasi (TJKT)</text>

                    <text x="265" y="280" class="wf-text-muted">SUMBER PENGAJUAN</text>
                    <text x="265" y="300" class="wf-text-body" style="font-weight:600;">Mandiri (Diajukan oleh Siswa Sendiri)</text>

                    <text x="265" y="330" class="wf-text-muted">PILIHAN JADWAL KONSELING</text>
                    <text x="265" y="350" class="wf-text-body" style="font-weight:600;">Senin, 18 Agustus 2026 | Jam 10:00 - 10:45 WIB</text>

                    <text x="265" y="380" class="wf-text-muted">JENIS LAYANAN KONSELING</text>
                    <text x="265" y="400" class="wf-text-body" style="font-weight:600;">Konseling Pribadi &amp; Motivasi Belajar</text>

                    <text x="265" y="430" class="wf-text-muted">ALASAN PENGAJUAN DARI SISWA</text>
                    <rect x="265" y="440" width="400" height="70" class="wf-input" />
                    <text x="275" y="465" class="wf-text-body">"Saya merasa kesulitan membagi waktu antara kegiatan organisasi</text>
                    <text x="275" y="480" class="wf-text-body">dan persiapan ujian kompetensi keahlian."</text>

                    <rect x="705" y="125" width="260" height="480" class="wf-card" />
                    <rect x="705" y="125" width="260" height="34" class="wf-card-header" />
                    <text x="720" y="147" class="wf-text-body" style="font-weight:700;">KEPUTUSAN VALIDASI</text>

                    <text x="720" y="185" class="wf-text-body" style="font-weight:600;">Catatan / Feedback Guru BK:</text>
                    <rect x="720" y="195" width="230" height="90" class="wf-input" />
                    <text x="730" y="220" class="wf-text-muted">Tuliskan arahan awal /</text>
                    <text x="730" y="235" class="wf-text-muted">alasan penolakan jika ditolak...</text>

                    <text x="720" y="315" class="wf-text-muted">Aksi Keputusan Guru BK:</text>

                    <rect x="720" y="335" width="230" height="40" class="wf-btn-primary" />
                    <text x="835" y="360" class="wf-text-btn-pri">SETUJUI PENGAJUAN</text>

                    <rect x="720" y="390" width="230" height="40" class="wf-btn-secondary" />
                    <text x="835" y="415" class="wf-text-btn-sec">TOLAK PENGAJUAN</text>

                    <text x="720" y="460" class="wf-text-muted" style="font-size:9.5px;">*Notifikasi WhatsApp akan otomatis dikirim ke nomor siswa setelah keputusan disimpan.</text>
                `)
            },
            {
                id: "2-5",
                num: "3.7",
                title: "Pelaksanaan Sesi & Hasil",
                desc: "Absensi Kehadiran Siswa, Catatan Analisis Rahasia Konselor, dan Arahan Umum Siswa",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("PELAKSANAAN SESI KONSELING", "[Nama Guru]", "Guru BK")}
                    ${getSidebarSVG("bk", 3)}
                    <text x="245" y="90" class="wf-text-title">PELAKSANAAN SESI KONSELING (#SESI-2026-042)</text>

                    <rect x="245" y="110" width="720" height="495" class="wf-card" />
                    <rect x="245" y="110" width="720" height="34" class="wf-card-header" />
                    <text x="260" y="132" class="wf-text-body" style="font-weight:700;">DOKUMENTASI REKAM LAYANAN KONSELING</text>

                    <text x="265" y="165" class="wf-text-body" style="font-weight:600;">Siswa: [Nama Siswa] (XI TJKT 1)</text>
                    <text x="560" y="165" class="wf-text-body">Tanggal Pelaksanaan: 18 Agustus 2026</text>

                    <text x="265" y="195" class="wf-text-body" style="font-weight:600;">Status Kehadiran Siswa:</text>
                    <rect x="265" y="205" width="140" height="30" class="wf-btn-primary" />
                    <text x="335" y="224" class="wf-text-btn-pri">Hadir di Ruang BK</text>
                    <rect x="415" y="205" width="140" height="30" class="wf-btn-secondary" />
                    <text x="485" y="224" class="wf-text-btn-sec">Tidak Hadir / Alpa</text>

                    <text x="265" y="260" class="wf-text-body" style="font-weight:600;">Hasil Konseling &amp; Analisis Masalah (Rahasia / Internal Guru BK):</text>
                    <rect x="265" y="270" width="680" height="90" class="wf-input" />
                    <text x="275" y="295" class="wf-text-body">Siswa mengalami stres akademik akibat overcommitment di ekstrakurikuler.</text>
                    <text x="275" y="315" class="wf-text-body">Diberikan teknik time management dan skala prioritas kegiatan harian.</text>

                    <text x="265" y="385" class="wf-text-body" style="font-weight:600;">Arahan Umum Tindak Lanjut (Dapat Dilihat oleh Siswa di Dashboardnya):</text>
                    <rect x="265" y="395" width="680" height="70" class="wf-input" />
                    <text x="275" y="420" class="wf-text-body">1. Membuat jadwal belajar mandiri terstruktur.</text>
                    <text x="275" y="440" class="wf-text-body">2. Melakukan evaluasi progres pada sesi konseling lanjutan.</text>

                    <rect x="265" y="550" width="220" height="38" class="wf-btn-primary" />
                    <text x="375" y="574" class="wf-text-btn-pri">SIMPAN HASIL "SELESAI"</text>

                    <rect x="495" y="550" width="220" height="38" class="wf-btn-secondary" />
                    <text x="605" y="574" class="wf-text-btn-sec">LANJUT KE TINDAK LANJUT</text>
                `)
            },
            {
                id: "2-6",
                num: "3.8",
                title: "Tindak Lanjut 1: Selesai",
                desc: "Form Penutupan Kasus Layanan Konseling & Penyimpanan Status 'Selesai'",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("TINDAK LANJUT - SELESAI TUNTAS", "[Nama Guru]", "Guru BK")}
                    ${getSidebarSVG("bk", 4)}
                    <text x="245" y="90" class="wf-text-title">TINDAK LANJUT: PENYELESAIAN LAYANAN TUNTAS</text>

                    <rect x="245" y="115" width="720" height="490" class="wf-card" />
                    <rect x="245" y="115" width="720" height="34" class="wf-card-header" />
                    <text x="260" y="137" class="wf-text-body" style="font-weight:700;">FORM PENUTUPAN STATUS LAYANAN KONSELING</text>

                    <text x="265" y="175" class="wf-text-body" style="font-weight:600;">Siswa: [Nama Siswa] (XI TJKT 1) - Sesi #SESI-2026-042</text>

                    <text x="265" y="215" class="wf-text-body" style="font-weight:600;">Keputusan Terpilih:</text>
                    <rect x="265" y="225" width="680" height="50" class="wf-card" stroke-width="1.8" />
                    <text x="285" y="255" class="wf-text-body" style="font-weight:700;">● Kasus Telah Terselesaikan Tuntas (Layanan Selesai)</text>

                    <text x="265" y="305" class="wf-text-body" style="font-weight:600;">Ringkasan Evaluasi &amp; Catatan Akhir Guru BK:</text>
                    <rect x="265" y="315" width="680" height="90" class="wf-input" />
                    <text x="280" y="340" class="wf-text-body">Siswa telah memahami langkah penyelesaian masalah dan telah mandiri mengatur waktu.</text>
                    <text x="280" y="360" class="wf-text-body">Tidak diperlukan pendampingan lanjutan atau pemanggilan orang tua.</text>

                    <text x="265" y="430" class="wf-text-body" style="font-weight:600;">Status Akhir Layanan yang Disimpan ke Database:</text>
                    <rect x="265" y="440" width="180" height="32" class="wf-input" />
                    <text x="280" y="461" class="wf-text-body">Status: Selesai</text>

                    <rect x="265" y="535" width="280" height="40" class="wf-btn-primary" />
                    <text x="405" y="560" class="wf-text-btn-pri">SIMPAN STATUS "LAYANAN SELESAI"</text>
                `)
            },
            {
                id: "2-7",
                num: "3.9",
                title: "Tindak Lanjut 2: Sesi Lanjutan",
                desc: "Form Pengaturan Jadwal Sesi Baru Siswa & Pembuatan Janji Temu 'Terjadwal'",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("TINDAK LANJUT - SESI LANJUTAN MANDIRI", "[Nama Guru]", "Guru BK")}
                    ${getSidebarSVG("bk", 4)}
                    <text x="245" y="90" class="wf-text-title">TINDAK LANJUT: ATUR JADWAL SESI KONSELING LANJUTAN</text>

                    <rect x="245" y="115" width="720" height="490" class="wf-card" />
                    <rect x="245" y="115" width="720" height="34" class="wf-card-header" />
                    <text x="260" y="137" class="wf-text-body" style="font-weight:700;">FORM PENJADWALAN SESI LANJUTAN MANDIRI</text>

                    <text x="265" y="175" class="wf-text-body" style="font-weight:600;">Siswa: [Nama Siswa] (XI TJKT 1) - Tindak Lanjut dari Sesi #SESI-2026-042</text>

                    <text x="265" y="215" class="wf-text-body">Pilih Tanggal Sesi Lanjutan</text>
                    <rect x="265" y="225" width="450" height="32" class="wf-input" />
                    <text x="280" y="246" class="wf-text-body">Senin, 25 Agustus 2026 (YYYY-MM-DD) ▾</text>

                    <text x="265" y="280" class="wf-text-body">Jam Pelaksanaan Sesi Lanjutan</text>
                    <rect x="265" y="290" width="450" height="32" class="wf-input" />
                    <text x="280" y="311" class="wf-text-body">10:00 - 10:45 WIB ▾</text>

                    <text x="265" y="345" class="wf-text-body">Fokus / Target Capaian Sesi Lanjutan</text>
                    <rect x="265" y="355" width="680" height="70" class="wf-input" />
                    <text x="280" y="380" class="wf-text-body">Evaluasi pelaksanaan time-management mandiri dan progres nilai tugas kejuruan.</text>

                    <text x="265" y="450" class="wf-text-muted" style="font-size:10px;">*Sistem akan otomatis membuat jadwal baru dengan status 'Terjadwal' dan mengirimkan pesan WA ke siswa.</text>

                    <rect x="265" y="535" width="290" height="40" class="wf-btn-primary" />
                    <text x="410" y="560" class="wf-text-btn-pri">SIMPAN JADWAL SESI LANJUTAN</text>
                `)
            },
            {
                id: "2-8",
                num: "3.10",
                title: "Tindak Lanjut 3: Surat Ortu",
                desc: "Penerbitan Surat Resmi Panggilan Orang Tua, Preview Dokumen PDF & WhatsApp Gateway",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("PENERBITAN SURAT PANGGILAN ORANG TUA", "[Nama Guru]", "Guru BK")}
                    ${getSidebarSVG("bk", 5)}
                    <text x="245" y="90" class="wf-text-title">PENERBITAN SURAT PANGGILAN ORANG TUA / WALI</text>

                    <rect x="245" y="110" width="400" height="495" class="wf-card" />
                    <rect x="245" y="110" width="400" height="34" class="wf-card-header" />
                    <text x="260" y="132" class="wf-text-body" style="font-weight:700;">FORMULIR SURAT RESMI</text>

                    <text x="265" y="165" class="wf-text-body">Nomor Surat Resmi</text>
                    <rect x="265" y="173" width="360" height="30" class="wf-input" />
                    <text x="275" y="193" class="wf-text-body">421.5/108/SMKN2-BK/2026</text>

                    <text x="265" y="220" class="wf-text-body">Siswa Terkait</text>
                    <rect x="265" y="228" width="360" height="30" class="wf-input" />
                    <text x="275" y="248" class="wf-text-body">[Nama Siswa] (XI TJKT 1)</text>

                    <text x="265" y="275" class="wf-text-body">Nomor WhatsApp Orang Tua</text>
                    <rect x="265" y="283" width="360" height="30" class="wf-input" />
                    <text x="275" y="303" class="wf-text-body">0813-7456-XXXX (Bpk. Hendra)</text>

                    <text x="265" y="330" class="wf-text-body">Hari / Tanggal Kehadiran di Sekolah</text>
                    <rect x="265" y="338" width="360" height="30" class="wf-input" />
                    <text x="275" y="358" class="wf-text-body">Kamis, 21 Agustus 2026 | 09.00 WIB</text>

                    <text x="265" y="385" class="wf-text-body">Perihal / Alasan Pemanggilan</text>
                    <rect x="265" y="393" width="360" height="60" class="wf-input" />
                    <text x="275" y="418" class="wf-text-body">Konsultasi Perkembangan Akademik dan</text>
                    <text x="275" y="435" class="wf-text-body">Pendampingan Belajar Siswa di Rumah.</text>

                    <rect x="265" y="540" width="360" height="38" class="wf-btn-primary" />
                    <text x="445" y="564" class="wf-text-btn-pri">TERBITKAN &amp; KIRIM NOTIFIKASI WA</text>

                    <rect x="665" y="110" width="300" height="495" class="wf-card" />
                    <rect x="665" y="110" width="300" height="34" class="wf-card-header" />
                    <text x="680" y="132" class="wf-text-body" style="font-weight:700;">PREVIEW SURAT (PDF FORMAT)</text>

                    <rect x="685" y="155" width="260" height="370" fill="#ffffff" stroke="#000" stroke-width="1.2" />
                    <text x="815" y="180" class="wf-text-body" style="font-size:9px; font-weight:700;" text-anchor="middle">PEMERINTAH PROVINSI SUMATERA BARAT</text>
                    <text x="815" y="195" class="wf-text-body" style="font-size:8.5px; font-weight:700;" text-anchor="middle">SMK NEGERI 2 GUGUAK</text>
                    <line x1="700" y1="205" x2="930" y2="205" stroke="#000" stroke-width="1.5" />
                    
                    <text x="705" y="225" class="wf-text-muted" style="font-size:8px;">Hal: Surat Panggilan Orang Tua</text>
                    <text x="705" y="245" class="wf-text-muted" style="font-size:8px;">Kepada Yth. Orang Tua / Wali dari:</text>
                    <text x="705" y="260" class="wf-text-body" style="font-size:9px; font-weight:600;">[Nama Siswa] (XI TJKT 1)</text>
                    <text x="705" y="280" class="wf-text-muted" style="font-size:8px;">Mengharap kehadiran Bapak/Ibu pada:</text>
                    <text x="705" y="295" class="wf-text-body" style="font-size:8.5px;">Hari/Tgl: Kamis, 21 Agustus 2026</text>
                    <text x="705" y="310" class="wf-text-body" style="font-size:8.5px;">Tempat: Ruang BK SMKN 2 Guguak</text>

                    <text x="860" y="380" class="wf-text-body" style="font-size:8px;" text-anchor="middle">Guru BK Konselor,</text>
                    <text x="860" y="420" class="wf-text-body" style="font-size:8.5px; font-weight:600;" text-anchor="middle">[Nama Guru]</text>

                    <rect x="685" y="540" width="260" height="38" class="wf-btn-secondary" />
                    <text x="815" y="564" class="wf-text-btn-sec">Unduh Berkas PDF Surat</text>
                `)
            },
            {
                id: "2-8-followup",
                num: "3.11",
                title: "Konseling Lanjutan Pasca Kirim Surat",
                desc: "Form Penjadwalan Sesi Konseling Lanjutan Bersama Orang Tua Setelah Surat Panggilan Terkirim",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("JADWAL KONSELING LANJUTAN BERSAMA ORANG TUA", "[Nama Guru]", "Guru BK")}
                    ${getSidebarSVG("bk", 5)}
                    <text x="245" y="90" class="wf-text-title">TINDAK LANJUT: ATUR JADWAL KONSELING LANJUTAN BERSAMA ORANG TUA</text>

                    <!-- Alert Box: Surat Panggilan Sudah Dikirim -->
                    <rect x="245" y="110" width="720" height="40" fill="#f8fafc" stroke="#000" stroke-width="1.4" stroke-dasharray="4,3" rx="4" />
                    <text x="260" y="127" class="wf-text-body" style="font-weight:700;">[ SURAT SUDAH DIKIRIM ] Surat Panggilan Resmi #421.5/108 telah terkirim via WhatsApp Gateway</text>
                    <text x="260" y="142" class="wf-text-muted">Penerima: Hendra Wijaya (Orang Tua [Nama Siswa]) - Nomor: 0813-7456-XXXX | Status: Sukses Terkirim</text>

                    <rect x="245" y="160" width="720" height="450" class="wf-card" />
                    <rect x="245" y="160" width="720" height="34" class="wf-card-header" />
                    <text x="260" y="182" class="wf-text-body" style="font-weight:700;">FORM PENJADWALAN SESI KONSELING LANJUTAN (GURU BK, SISWA &amp; ORANG TUA)</text>

                    <text x="265" y="215" class="wf-text-body" style="font-weight:600;">Siswa: [Nama Siswa] (XI TJKT 1) - Tindak Lanjut dari Sesi #SESI-2026-042</text>

                    <text x="265" y="250" class="wf-text-body">Pilih Tanggal Sesi Konseling Lanjutan (Sesuai Jadwal Surat Panggilan)</text>
                    <rect x="265" y="260" width="450" height="32" class="wf-input" />
                    <text x="280" y="281" class="wf-text-body">Kamis, 21 Agustus 2026 (YYYY-MM-DD) ▾</text>

                    <text x="265" y="315" class="wf-text-body">Jam Pelaksanaan Pertemuan di Ruang BK</text>
                    <rect x="265" y="325" width="450" height="32" class="wf-input" />
                    <text x="280" y="346" class="wf-text-body">09:00 - 09:45 WIB ▾</text>

                    <text x="265" y="380" class="wf-text-body">Fokus / Target Capaian Pertemuan Bersama Orang Tua &amp; Siswa</text>
                    <rect x="265" y="390" width="680" height="70" class="wf-input" />
                    <text x="280" y="415" class="wf-text-body">Konsultasi perkembangan akademik, koordinasi kedisiplinan dan pendampingan belajar di rumah.</text>

                    <text x="265" y="480" class="wf-text-muted" style="font-size:10px;">*Sistem otomatis membuat sesi baru dengan status 'Terjadwal'. Hasil pelaksanaan konseling lanjutan</text>
                    <text x="265" y="495" class="wf-text-muted" style="font-size:10px;">dan kesepakatan bersama akan diinputkan kembali pada menu "Pelaksanaan Konseling" saat sesi berlangsung.</text>

                    <rect x="265" y="535" width="310" height="40" class="wf-btn-primary" />
                    <text x="420" y="560" class="wf-text-btn-pri">SIMPAN JADWAL SESI LANJUTAN</text>
                `)
            },
            {
                id: "2-9",
                num: "3.12",
                title: "Log Notifikasi WhatsApp",
                desc: "Pengiriman Otomatis: Pengajuan Baru, Hasil Validasi, Pembatalan, Reminder & Surat Ortu",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("LOG NOTIFIKASI WHATSAPP", "[Nama Guru]", "Guru BK")}
                    ${getSidebarSVG("bk", 6)}
                    <text x="245" y="90" class="wf-text-title">LOG PENGIRIMAN NOTIFIKASI WHATSAPP</text>
                    <text x="245" y="110" class="wf-text-muted">Riwayat pengiriman notifikasi otomatis kepada siswa, guru BK, dan orang tua/wali</text>

                    <rect x="245" y="130" width="720" height="460" class="wf-card" />
                    <rect x="245" y="130" width="720" height="32" class="wf-table-header" />
                    <text x="260" y="151" class="wf-text-body" style="font-weight:600;">WAKTU KIRIM</text>
                    <text x="365" y="151" class="wf-text-body" style="font-weight:600;">PENERIMA</text>
                    <text x="490" y="151" class="wf-text-body" style="font-weight:600;">JENIS NOTIFIKASI</text>
                    <text x="680" y="151" class="wf-text-body" style="font-weight:600;">RINGKASAN PESAN</text>
                    <text x="890" y="151" class="wf-text-body" style="font-weight:600;">STATUS</text>

                    <rect x="245" y="162" width="720" height="42" class="wf-table-row" />
                    <text x="260" y="187" class="wf-text-muted">18/08 10:15</text>
                    <text x="365" y="187" class="wf-text-body">0813-7456-XXXX (Ortu)</text>
                    <text x="490" y="187" class="wf-text-body">Surat Panggilan Ortu</text>
                    <text x="680" y="187" class="wf-text-body" style="font-size:9.5px;">Surat panggilan konseling siswa...</text>
                    <rect x="885" y="173" width="65" height="20" class="wf-badge" />
                    <text x="917" y="186" class="wf-text-badge">Terkirim</text>

                    <rect x="245" y="204" width="720" height="42" class="wf-table-row-alt" />
                    <text x="260" y="229" class="wf-text-muted">18/08 09:30</text>
                    <text x="365" y="229" class="wf-text-body">0812-1111-XXXX (Siswa)</text>
                    <text x="490" y="229" class="wf-text-body">Hasil Validasi BK</text>
                    <text x="680" y="229" class="wf-text-body" style="font-size:9.5px;">Pengajuan konseling disetujui...</text>
                    <rect x="885" y="215" width="65" height="20" class="wf-badge" />
                    <text x="917" y="228" class="wf-text-badge">Terkirim</text>

                    <rect x="245" y="246" width="720" height="42" class="wf-table-row" />
                    <text x="260" y="271" class="wf-text-muted">18/08 08:00</text>
                    <text x="365" y="271" class="wf-text-body">0812-6789-XXXX (Guru BK)</text>
                    <text x="490" y="271" class="wf-text-body">Rujukan Wali Kelas</text>
                    <text x="680" y="271" class="wf-text-body" style="font-size:9.5px;">Rujukan baru siswa [Nama Siswa]...</text>
                    <rect x="885" y="257" width="65" height="20" class="wf-badge" />
                    <text x="917" y="270" class="wf-text-badge">Terkirim</text>
                `)
            }
        ]
    },

    // =========================================================================
    // 4. WALI KELAS
    // =========================================================================
    {
        name: "4. Wali Kelas",
        screens: [
            {
                id: "3-0",
                num: "4.1",
                title: "Dashboard Walas",
                desc: "Statistik Khusus Kelas Binaan, Progres Konseling & Pemantauan Siswa Binaan",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("DASHBOARD WALI KELAS", "[Nama Guru]", "Wali Kelas")}
                    ${getSidebarSVG("walas", 0)}
                    <text x="245" y="90" class="wf-text-title">KELAS BINAAN: XI TJKT 1 (34 SISWA)</text>

                    <rect x="245" y="105" width="225" height="75" class="wf-card" />
                    <text x="260" y="125" class="wf-text-muted">TOTAL SISWA BINAAN</text>
                    <text x="260" y="155" class="wf-text-title" style="font-size:22px;">34 Siswa</text>

                    <rect x="490" y="105" width="225" height="75" class="wf-card" />
                    <text x="505" y="125" class="wf-text-muted">RUJUKAN DIAJUKAN</text>
                    <text x="505" y="155" class="wf-text-title" style="font-size:22px;">6 Rujukan</text>

                    <rect x="735" y="105" width="230" height="75" class="wf-card" />
                    <text x="750" y="125" class="wf-text-muted">KONSELING SELESAI</text>
                    <text x="750" y="155" class="wf-text-title" style="font-size:22px;">5 Tuntas</text>

                    <rect x="245" y="195" width="720" height="410" class="wf-card" />
                    <rect x="245" y="195" width="720" height="34" class="wf-card-header" />
                    <text x="260" y="217" class="wf-text-body" style="font-weight:700;">DAFTAR STATUS RUJUKAN SISWA KELAS BINAAN</text>

                    <rect x="245" y="229" width="720" height="28" class="wf-table-header" />
                    <text x="260" y="247" class="wf-text-body" style="font-weight:600;">TANGGAL</text>
                    <text x="350" y="247" class="wf-text-body" style="font-weight:600;">NAMA SISWA</text>
                    <text x="490" y="247" class="wf-text-body" style="font-weight:600;">ALASAN RUJUKAN</text>
                    <text x="720" y="247" class="wf-text-body" style="font-weight:600;">GURU BK</text>
                    <text x="850" y="247" class="wf-text-body" style="font-weight:600;">PROGRES</text>

                    <rect x="245" y="257" width="720" height="36" class="wf-table-row" />
                    <text x="260" y="279" class="wf-text-muted">12/08/2026</text>
                    <text x="350" y="279" class="wf-text-body" style="font-weight:600;">[Nama Siswa]</text>
                    <text x="490" y="279" class="wf-text-body">Penurunan absensi &amp; sering terlambat</text>
                    <text x="720" y="279" class="wf-text-body">[Nama Guru]</text>
                    <rect x="840" y="265" width="80" height="20" class="wf-badge" />
                    <text x="880" y="278" class="wf-text-badge">Selesai</text>
                `)
            },
            {
                id: "3-1",
                num: "4.2",
                title: "Form Pengajuan Rujukan",
                desc: "Formulir Rujukan Khusus Siswa Kelas Binaan Saja (Siswa Luar Kelas Tidak Ditampilkan)",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("FORM PENGAJUAN RUJUKAN BK", "[Nama Guru]", "Wali Kelas")}
                    ${getSidebarSVG("walas", 1)}
                    <text x="245" y="90" class="wf-text-title">FORMULIR PENGAJUAN RUJUKAN SISWA KE GURU BK</text>
                    <text x="245" y="110" class="wf-text-muted">Ajukan siswa yang membutuhkan pendampingan konseling khusus dari Guru BK sekolah</text>

                    <rect x="245" y="125" width="720" height="480" class="wf-card" />
                    <rect x="245" y="125" width="720" height="34" class="wf-card-header" />
                    <text x="260" y="147" class="wf-text-body" style="font-weight:700;">DATA RUJUKAN KELAS BINAAN (XI TJKT 1)</text>

                    <text x="265" y="180" class="wf-text-body" style="font-weight:600;">1. Pilih Siswa Binaan yang Dirujuk (Hanya Siswa XI TJKT 1):</text>
                    <rect x="265" y="190" width="680" height="34" class="wf-input" />
                    <text x="275" y="212" class="wf-text-body">[Nama Siswa] (NISN: 0082345678) ▾</text>

                    <text x="265" y="245" class="wf-text-body" style="font-weight:600;">2. Jenis Layanan / Bidang Konseling:</text>
                    <rect x="265" y="255" width="680" height="34" class="wf-input" />
                    <text x="275" y="277" class="wf-text-body">Kedisiplinan &amp; Motivasi Belajar ▾</text>

                    <text x="265" y="310" class="wf-text-body" style="font-weight:600;">3. Alasan / Latar Belakang Rujukan dari Wali Kelas:</text>
                    <rect x="265" y="320" width="680" height="110" class="wf-input" />
                    <text x="275" y="345" class="wf-text-body">Siswa sering datang terlambat lebih dari 3 kali dalam seminggu dan nilai tugas menurun drastis.</text>
                    <text x="275" y="365" class="wf-text-body">Sudah ditegur secara personal oleh wali kelas namun membutuhkan pendampingan lebih lanjut oleh Guru BK.</text>

                    <text x="265" y="460" class="wf-text-muted" style="font-size:10px;">*Sistem akan otomatis mengirimkan notifikasi WhatsApp ke Guru BK yang mengampu kelas XI TJKT 1.</text>

                    <rect x="265" y="540" width="240" height="40" class="wf-btn-primary" />
                    <text x="385" y="565" class="wf-text-btn-pri">KIRIM RUJUKAN KE GURU BK</text>
                `)
            },
            {
                id: "3-2",
                num: "4.3",
                title: "Data Siswa Binaan",
                desc: "Daftar Seluruh Siswa di Kelas Binaan & Tombol Aksi Detail serta Riwayat Konseling",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("DATA SISWA KELAS BINAAN", "[Nama Guru]", "Wali Kelas")}
                    ${getSidebarSVG("walas", 2)}
                    <text x="245" y="90" class="wf-text-title">DAFTAR SISWA KELAS BINAAN (XI TJKT 1)</text>
                    <text x="245" y="110" class="wf-text-muted">Pantau data kontak, riwayat rujukan dan progres layanan konseling siswa</text>

                    <rect x="245" y="125" width="280" height="32" class="wf-input" />
                    <text x="255" y="146" class="wf-text-muted">Cari nama siswa binaan, NISN...</text>

                    <rect x="245" y="170" width="720" height="420" class="wf-card" />
                    <rect x="245" y="170" width="720" height="32" class="wf-table-header" />
                    <text x="260" y="191" class="wf-text-body" style="font-weight:600;">NISN</text>
                    <text x="340" y="191" class="wf-text-body" style="font-weight:600;">NAMA SISWA</text>
                    <text x="500" y="191" class="wf-text-body" style="font-weight:600;">KONTAK WA SISWA</text>
                    <text x="660" y="191" class="wf-text-body" style="font-weight:600;">STATUS KONSELING</text>
                    <text x="840" y="191" class="wf-text-body" style="font-weight:600;">AKSI WALI KELAS</text>

                    <rect x="245" y="202" width="720" height="36" class="wf-table-row" />
                    <text x="260" y="224" class="wf-text-body">0071234567</text>
                    <text x="340" y="224" class="wf-text-body" style="font-weight:600;">[Nama Siswa]</text>
                    <text x="500" y="224" class="wf-text-body">0812-1111-2222</text>
                    <rect x="655" y="210" width="90" height="20" class="wf-badge" />
                    <text x="700" y="223" class="wf-text-badge">Selesai</text>
                    <rect x="835" y="210" width="60" height="20" class="wf-btn-secondary" /><text x="865" y="223" class="wf-text-btn-sec" style="font-size:9px;">Detail</text>
                    <rect x="900" y="210" width="60" height="20" class="wf-btn-secondary" /><text x="930" y="223" class="wf-text-btn-sec" style="font-size:8.5px;">Rujukan</text>

                    <rect x="245" y="238" width="720" height="36" class="wf-table-row-alt" />
                    <text x="260" y="260" class="wf-text-body">0082345678</text>
                    <text x="340" y="260" class="wf-text-body" style="font-weight:600;">[Nama Siswa]</text>
                    <text x="500" y="260" class="wf-text-body">0812-3333-4444</text>
                    <rect x="655" y="246" width="90" height="20" class="wf-badge" />
                    <text x="700" y="259" class="wf-text-badge">Selesai</text>
                    <rect x="835" y="246" width="60" height="20" class="wf-btn-secondary" /><text x="865" y="259" class="wf-text-btn-sec" style="font-size:9px;">Detail</text>
                    <rect x="900" y="246" width="60" height="20" class="wf-btn-secondary" /><text x="930" y="259" class="wf-text-btn-sec" style="font-size:8.5px;">Rujukan</text>
                `)
            },
            {
                id: "3-3",
                num: "4.4",
                title: "Modal Detail Siswa Binaan",
                desc: "Tampilan Rincian Data Siswa, Kontak Orang Tua & Riwayat Konseling Kelas Binaan",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("DETAIL SISWA BINAAN", "[Nama Guru]", "Wali Kelas")}
                    ${getSidebarSVG("walas", 2)}
                    <rect x="220" y="60" width="770" height="570" class="wf-modal-overlay" />

                    <rect x="330" y="90" width="550" height="500" class="wf-modal" />
                    <rect x="330" y="90" width="550" height="40" class="wf-card-header" />
                    <text x="350" y="115" class="wf-text-body" style="font-weight:700;">PROFIL &amp; STATUS SISWA BINAAN</text>
                    <rect x="840" y="98" width="25" height="24" class="wf-btn-secondary" /><text x="852" y="114" class="wf-text-btn-sec">✕</text>

                    <text x="360" y="150" class="wf-text-muted">NAMA SISWA / NISN</text>
                    <text x="360" y="170" class="wf-text-body" style="font-weight:700;">[Nama Siswa] (NISN: 0071234567)</text>

                    <text x="360" y="200" class="wf-text-muted">KONTAK WHATSAPP SISWA &amp; ORANG TUA</text>
                    <text x="360" y="220" class="wf-text-body">Siswa: 0812-1111-2222 | Ortu: 0813-7456-9999 (Bpk. Hendra)</text>

                    <text x="360" y="250" class="wf-text-muted">RIWAYAT LAYANAN KONSELING TERAKHIR</text>
                    <rect x="360" y="260" width="490" height="120" class="wf-card" />
                    <text x="375" y="285" class="wf-text-body" style="font-weight:600;">Sesi Konseling #SESI-2026-042 (Tgl: 18/08/2026)</text>
                    <text x="375" y="305" class="wf-text-muted">Guru BK: [Nama Guru]</text>
                    <text x="375" y="325" class="wf-text-body">Status: Layanan Selesai Tuntas</text>
                    <text x="375" y="345" class="wf-text-muted">Hasil: Siswa telah membuat jadwal belajar mandiri terstruktur.</text>

                    <rect x="710" y="525" width="140" height="38" class="wf-btn-primary" />
                    <text x="780" y="549" class="wf-text-btn-pri">Tutup</text>
                `)
            },
            {
                id: "3-4",
                num: "4.5",
                title: "Monitoring Layanan Walas",
                desc: "Tabel Pemantauan Progres Layanan Konseling Seluruh Siswa Kelas Binaan",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("MONITORING LAYANAN KONSELING", "[Nama Guru]", "Wali Kelas")}
                    ${getSidebarSVG("walas", 3)}
                    <text x="245" y="90" class="wf-text-title">MONITORING PERKEMBANGAN KONSELING SISWA BINAAN</text>
                    <text x="245" y="110" class="wf-text-muted">Pantau seluruh status sesi yang sedang berlangsung dan telah tuntas ditangani oleh Guru BK</text>

                    <rect x="245" y="130" width="720" height="460" class="wf-card" />
                    <rect x="245" y="130" width="720" height="32" class="wf-table-header" />
                    <text x="260" y="151" class="wf-text-body" style="font-weight:600;">TANGGAL</text>
                    <text x="350" y="151" class="wf-text-body" style="font-weight:600;">NAMA SISWA</text>
                    <text x="490" y="151" class="wf-text-body" style="font-weight:600;">BIDANG KONSELING</text>
                    <text x="690" y="151" class="wf-text-body" style="font-weight:600;">GURU BK</text>
                    <text x="820" y="151" class="wf-text-body" style="font-weight:600;">STATUS</text>

                    <rect x="245" y="162" width="720" height="36" class="wf-table-row" />
                    <text x="260" y="184" class="wf-text-muted">18/08/2026</text>
                    <text x="350" y="184" class="wf-text-body" style="font-weight:600;">[Nama Siswa]</text>
                    <text x="490" y="184" class="wf-text-body">Pribadi &amp; Belajar</text>
                    <text x="690" y="184" class="wf-text-body">[Nama Guru]</text>
                    <rect x="815" y="170" width="80" height="20" class="wf-badge" /><text x="855" y="183" class="wf-text-badge">Selesai</text>

                    <rect x="245" y="198" width="720" height="36" class="wf-table-row-alt" />
                    <text x="260" y="220" class="wf-text-muted">12/08/2026</text>
                    <text x="350" y="220" class="wf-text-body" style="font-weight:600;">[Nama Siswa]</text>
                    <text x="490" y="220" class="wf-text-body">Kedisiplinan / Absensi</text>
                    <text x="690" y="220" class="wf-text-body">[Nama Guru]</text>
                    <rect x="815" y="206" width="80" height="20" class="wf-badge" /><text x="855" y="219" class="wf-text-badge">Selesai</text>
                `)
            }
        ]
    },

    // =========================================================================
    // 5. SISWA
    // =========================================================================
    {
        name: "5. Siswa",
        screens: [
            {
                id: "4-0",
                num: "5.1",
                title: "Beranda Siswa",
                desc: "Beranda Siswa: Sapaan Hangat, Status Konseling Terjadwal, Aksi Cepat & Info Edukasi BK",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("BERANDA SISWA", "[Nama Siswa]", "Siswa")}
                    ${getSidebarSVG("siswa", 0)}
                    
                    <!-- Welcome Hero Card -->
                    <rect x="245" y="80" width="720" height="110" class="wf-card" stroke-width="1.8" />
                    <text x="270" y="112" class="wf-text-title" style="font-size:17px;">Selamat Datang di SIKS, [Nama Siswa]!</text>
                    <text x="270" y="134" class="wf-text-muted">Ruang Layanan Konseling SMK Negeri 2 Guguak selalu siap mendampingi Anda.</text>
                    <text x="270" y="152" class="wf-text-muted">Guru BK Konselor Kelas XI TJKT: [Nama Guru]</text>
                    
                    <rect x="760" y="110" width="180" height="38" class="wf-btn-primary" />
                    <text x="850" y="134" class="wf-text-btn-pri" style="font-size:10.5px;">+ Ajukan Konseling</text>

                    <!-- Section: Status Jadwal / Pengajuan Aktif Siswa -->
                    <text x="245" y="215" class="wf-text-title">STATUS LAYANAN KONSELING SAYA</text>

                    <rect x="245" y="230" width="350" height="180" class="wf-card" />
                    <rect x="245" y="230" width="350" height="32" class="wf-card-header" />
                    <text x="260" y="251" class="wf-text-body" style="font-weight:700;">JADWAL KONSELING MENDATANG</text>

                    <text x="265" y="285" class="wf-text-muted">TANGGAL &amp; WAKTU</text>
                    <text x="265" y="305" class="wf-text-body" style="font-weight:700;">Senin, 18 Agustus 2026 (10:00 WIB)</text>

                    <text x="265" y="335" class="wf-text-muted">TEMPAT &amp; GURU BK</text>
                    <text x="265" y="355" class="wf-text-body">Ruang BK | [Nama Guru]</text>

                    <rect x="265" y="370" width="120" height="26" class="wf-btn-secondary" />
                    <text x="325" y="387" class="wf-text-btn-sec" style="font-size:9.5px;">Batalkan Jadwal</text>

                    <!-- Section: Edukasi Konseling BK -->
                    <rect x="615" y="230" width="350" height="180" class="wf-card" />
                    <rect x="615" y="230" width="350" height="32" class="wf-card-header" />
                    <text x="630" y="251" class="wf-text-body" style="font-weight:700;">EDUKASI &amp; TIPS PENGEMBANGAN DIRI</text>

                    <text x="630" y="280" class="wf-text-body" style="font-weight:600;">Asas Kerahasiaan BK Sekolah:</text>
                    <text x="630" y="300" class="wf-text-muted">Seluruh percakapan dan data konseling Anda dijamin</text>
                    <text x="630" y="318" class="wf-text-muted">kerahasiaannya oleh konselor sekolah sesuai kode etik.</text>

                    <text x="630" y="350" class="wf-text-body" style="font-weight:600;">Bidang Layanan:</text>
                    <text x="630" y="370" class="wf-text-muted">Pribadi • Sosial • Motivasi Belajar • Karier/PKL</text>

                    <!-- Riwayat Singkat -->
                    <rect x="245" y="430" width="720" height="175" class="wf-card" />
                    <rect x="245" y="430" width="720" height="32" class="wf-card-header" />
                    <text x="260" y="451" class="wf-text-body" style="font-weight:700;">RIWAYAT ARAHAN DARI GURU BK TERBARU</text>

                    <text x="265" y="485" class="wf-text-body" style="font-weight:600;">Sesi Konseling: 12 Agustus 2026 - Konseling Belajar &amp; Waktu</text>
                    <text x="265" y="510" class="wf-text-body">Arahan: "Membagi waktu kegiatan belajar di rumah dan latihan persiapan ujian kompetensi."</text>
                    <rect x="265" y="535" width="140" height="28" class="wf-btn-secondary" />
                    <text x="335" y="553" class="wf-text-btn-sec">Lihat Rincian Arahan</text>
                `)
            },
            {
                id: "4-1",
                num: "5.2",
                title: "Menu Jadwal Konseling",
                desc: "Daftar Seluruh Slot Waktu Guru BK yang Tersedia untuk Dipilih Siswa",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("JADWAL KONSELING TERSEDIA", "[Nama Siswa]", "Siswa")}
                    ${getSidebarSVG("siswa", 1)}
                    <text x="245" y="90" class="wf-text-title">PILIH JADWAL KONSELING YANG TERSEDIA</text>
                    <text x="245" y="110" class="wf-text-muted">Pilih tanggal dan jam layanan yang sesuai dengan waktu luang Anda</text>

                    <rect x="245" y="130" width="720" height="460" class="wf-card" />
                    <rect x="245" y="130" width="720" height="32" class="wf-table-header" />
                    <text x="260" y="151" class="wf-text-body" style="font-weight:600;">HARI / TANGGAL</text>
                    <text x="420" y="151" class="wf-text-body" style="font-weight:600;">JAM LAYANAN</text>
                    <text x="580" y="151" class="wf-text-body" style="font-weight:600;">GURU BK</text>
                    <text x="760" y="151" class="wf-text-body" style="font-weight:600;">STATUS</text>
                    <text x="870" y="151" class="wf-text-body" style="font-weight:600;">AKSI</text>

                    <rect x="245" y="162" width="720" height="38" class="wf-table-row" />
                    <text x="260" y="186" class="wf-text-body">Senin, 18 Agustus 2026</text>
                    <text x="420" y="186" class="wf-text-body">09:00 - 09:45 WIB</text>
                    <text x="580" y="186" class="wf-text-body">[Nama Guru]</text>
                    <rect x="750" y="172" width="75" height="20" class="wf-badge" /><text x="787" y="185" class="wf-text-badge">Tersedia</text>
                    <rect x="855" y="172" width="90" height="20" class="wf-btn-primary" /><text x="900" y="185" class="wf-text-btn-pri" style="font-size:9.5px;">Pilih Slot</text>

                    <rect x="245" y="200" width="720" height="38" class="wf-table-row-alt" />
                    <text x="260" y="224" class="wf-text-body">Selasa, 19 Agustus 2026</text>
                    <text x="420" y="224" class="wf-text-body">10:00 - 10:45 WIB</text>
                    <text x="580" y="224" class="wf-text-body">[Nama Guru]</text>
                    <rect x="750" y="210" width="75" height="20" class="wf-badge" /><text x="787" y="223" class="wf-text-badge">Tersedia</text>
                    <rect x="855" y="210" width="90" height="20" class="wf-btn-primary" /><text x="900" y="223" class="wf-text-btn-pri" style="font-size:9.5px;">Pilih Slot</text>
                `)
            },
            {
                id: "4-2",
                num: "5.3",
                title: "Form Pengajuan Konseling",
                desc: "Pemilihan Slot Jadwal Guru BK, Jenis Layanan Konseling & Input Alasan Pengajuan",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("FORM PENGAJUAN KONSELING MANDIRI", "[Nama Siswa]", "Siswa")}
                    ${getSidebarSVG("siswa", 2)}
                    <text x="245" y="90" class="wf-text-title">FORMULIR PENGAJUAN KONSELING MANDIRI</text>

                    <rect x="245" y="110" width="720" height="495" class="wf-card" />
                    <rect x="245" y="110" width="720" height="34" class="wf-card-header" />
                    <text x="260" y="132" class="wf-text-body" style="font-weight:700;">PILIH JADWAL DAN TENTUKAN KEBUTUHAN KONSELING</text>

                    <text x="265" y="170" class="wf-text-body" style="font-weight:600;">1. Pilih Slot Jadwal Guru BK yang Tersedia:</text>
                    
                    <rect x="265" y="185" width="210" height="50" class="wf-card" stroke-width="1.8" />
                    <text x="280" y="205" class="wf-text-body" style="font-weight:600;">Senin, 18 Agt 2026</text>
                    <text x="280" y="222" class="wf-text-muted">10:00 - 10:45 | [Nama Guru]</text>

                    <rect x="490" y="185" width="210" height="50" class="wf-input" />
                    <text x="505" y="205" class="wf-text-body">Selasa, 19 Agt 2026</text>
                    <text x="505" y="222" class="wf-text-muted">13:00 - 13:45 | [Nama Guru]</text>

                    <text x="265" y="265" class="wf-text-body" style="font-weight:600;">2. Jenis Layanan Konseling:</text>
                    <rect x="265" y="275" width="435" height="32" class="wf-input" />
                    <text x="280" y="295" class="wf-text-body">Konseling Pribadi &amp; Motivasi Belajar ▾</text>

                    <text x="265" y="335" class="wf-text-body" style="font-weight:600;">3. Alasan Pengajuan (Ceritakan singkat yang ingin dikonsultasikan):</text>
                    <rect x="265" y="345" width="680" height="80" class="wf-input" />
                    <text x="280" y="370" class="wf-text-muted">Tuliskan hal yang sedang dihadapi secara jujur dan terbuka...</text>

                    <text x="265" y="450" class="wf-text-muted" style="font-size:10.5px;">*Data pengajuan bersifat rahasia dan hanya dapat diakses oleh Guru BK yang bersangkutan.</text>

                    <rect x="265" y="540" width="240" height="40" class="wf-btn-primary" />
                    <text x="385" y="565" class="wf-text-btn-pri">KIRIM PENGAJUAN SEKARANG</text>
                `)
            },
            {
                id: "4-3",
                num: "5.4",
                title: "Modal Pembatalan Pengajuan",
                desc: "Modal Konfirmasi Pembatalan Pengajuan Mandiri yang Masih Menunggu Validasi",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("PEMBATALAN PENGAJUAN", "[Nama Siswa]", "Siswa")}
                    ${getSidebarSVG("siswa", 2)}
                    <rect x="220" y="60" width="770" height="570" class="wf-modal-overlay" />

                    <rect x="360" y="160" width="490" height="320" class="wf-modal" />
                    <rect x="360" y="160" width="490" height="40" class="wf-card-header" />
                    <text x="380" y="185" class="wf-text-body" style="font-weight:700;">KONFIRMASI PEMBATALAN PENGAJUAN KONSELING</text>

                    <text x="385" y="230" class="wf-text-body" style="font-weight:600;">Apakah Anda yakin ingin membatalkan pengajuan ini?</text>
                    
                    <text x="385" y="260" class="wf-text-muted">Kode Pengajuan: #PK-2026-089</text>
                    <text x="385" y="280" class="wf-text-muted">Jadwal: Senin, 18 Agustus 2026 | Jam 10:00 WIB</text>
                    <text x="385" y="300" class="wf-text-muted">Guru BK: [Nama Guru]</text>

                    <text x="385" y="335" class="wf-text-body" style="font-size:10px;">*Setelah dibatalkan, slot jadwal akan kembali tersedia untuk siswa lain.</text>

                    <rect x="585" y="415" width="110" height="36" class="wf-btn-secondary" />
                    <text x="640" y="438" class="wf-text-btn-sec">Kembali</text>

                    <rect x="705" y="415" width="130" height="36" class="wf-btn-primary" />
                    <text x="770" y="438" class="wf-text-btn-pri">Ya, Batalkan</text>
                `)
            },
            {
                id: "4-4",
                num: "5.5",
                title: "Riwayat & Arahan Umum",
                desc: "Halaman Hasil Arahan Umum Konseling Selesai (Catatan Rahasia Konselor Disembunyikan)",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("HASIL & ARAHAN KONSELING SISWA", "[Nama Siswa]", "Siswa")}
                    ${getSidebarSVG("siswa", 3)}
                    <text x="245" y="90" class="wf-text-title">HASIL &amp; ARAHAN TINDAK LANJUT KONSELING</text>

                    <rect x="245" y="115" width="720" height="490" class="wf-card" />
                    <rect x="245" y="115" width="720" height="34" class="wf-card-header" />
                    <text x="260" y="137" class="wf-text-body" style="font-weight:700;">REKAM SESI KONSELING SELESAI (#SESI-2026-042)</text>

                    <text x="265" y="175" class="wf-text-muted">TANGGAL SESI DILAKSANAKAN</text>
                    <text x="265" y="195" class="wf-text-body" style="font-weight:600;">18 Agustus 2026 | Jam 10:00 - 10:45 WIB</text>

                    <text x="265" y="230" class="wf-text-muted">GURU BK KONSELOR</text>
                    <text x="265" y="250" class="wf-text-body" style="font-weight:600;">[Nama Guru]</text>

                    <text x="265" y="285" class="wf-text-muted">STATUS LAYANAN</text>
                    <rect x="265" y="295" width="90" height="22" class="wf-badge" />
                    <text x="310" y="310" class="wf-text-badge">Selesai</text>

                    <text x="265" y="345" class="wf-text-body" style="font-weight:700;">ARAHAN &amp; CATATAN TINDAK LANJUT DARI GURU BK:</text>
                    <rect x="265" y="355" width="680" height="110" class="wf-input" />
                    <text x="280" y="380" class="wf-text-body">1. Siswa telah membuat komitmen pembagian waktu belajar dan kegiatan organisasi.</text>
                    <text x="280" y="400" class="wf-text-body">2. Memprioritaskan persiapan ujian kejuruan semester ini.</text>
                    <text x="280" y="420" class="wf-text-body">3. Melakukan evaluasi progres mandiri setiap akhir pekan.</text>

                    <text x="265" y="490" class="wf-text-muted" style="font-size:10px;">*Hasil konseling bersifat rahasia. Lembar ini hanya memuat rekomendasi umum untuk siswa.</text>
                `)
            }
        ]
    },

    // =========================================================================
    // 6. WAKASIS
    // =========================================================================
    {
        name: "6. Wakasis",
        screens: [
            {
                id: "5-0",
                num: "6.1",
                title: "Dashboard Wakasis",
                desc: "Statistik Konseling Tingkat Sekolah, Tren Bulanan & Distribusi Masalah Siswa",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("DASHBOARD WAKIL KEPALA BIDANG KESISWAAN", "[Nama Wakasis]", "Wakasis")}
                    ${getSidebarSVG("wakasis", 0)}
                    <text x="245" y="90" class="wf-text-title">RINGKASAN TINGKAT KESISWAAN SEKOLAH</text>

                    <rect x="245" y="110" width="225" height="80" class="wf-card" />
                    <text x="260" y="132" class="wf-text-muted">TOTAL LAYANAN KONSELING</text>
                    <text x="260" y="162" class="wf-text-title" style="font-size:24px;">156 Sesi</text>

                    <rect x="490" y="110" width="225" height="80" class="wf-card" />
                    <text x="505" y="132" class="wf-text-muted">PERSENTASE TUNTAS</text>
                    <text x="505" y="162" class="wf-text-title" style="font-size:24px;">94.2 %</text>

                    <rect x="735" y="110" width="230" height="80" class="wf-card" />
                    <text x="750" y="132" class="wf-text-muted">SURAT PANGGILAN ORTU</text>
                    <text x="750" y="162" class="wf-text-title" style="font-size:24px;">9 Terbit</text>

                    <rect x="245" y="205" width="720" height="200" class="wf-card" />
                    <rect x="245" y="205" width="720" height="30" class="wf-card-header" />
                    <text x="260" y="225" class="wf-text-body" style="font-weight:700;">GRAFIK TREN LAYANAN KONSELING PER BULAN</text>
                    
                    <line x1="280" y1="365" x2="930" y2="365" stroke="#000" stroke-width="1.5" />
                    <rect x="320" y="295" width="40" height="70" fill="#000" /><text x="340" y="380" class="wf-text-muted" text-anchor="middle">Juli</text>
                    <rect x="400" y="265" width="40" height="100" fill="#000" /><text x="420" y="380" class="wf-text-muted" text-anchor="middle">Agustus</text>
                    <rect x="480" y="285" width="40" height="80" fill="#000" /><text x="500" y="380" class="wf-text-muted" text-anchor="middle">September</text>
                    <rect x="560" y="245" width="40" height="120" fill="#000" /><text x="580" y="380" class="wf-text-muted" text-anchor="middle">Oktober</text>

                    <rect x="245" y="420" width="720" height="185" class="wf-card" />
                    <rect x="245" y="420" width="720" height="30" class="wf-card-header" />
                    <text x="260" y="440" class="wf-text-body" style="font-weight:700;">RINGKASAN PER TINGKAT KELAS</text>

                    <text x="265" y="475" class="wf-text-body">Kelas X: 48 Layanan (96% Selesai)</text>
                    <text x="265" y="505" class="wf-text-body">Kelas XI: 56 Layanan (92% Selesai)</text>
                    <text x="265" y="535" class="wf-text-body">Kelas XII: 52 Layanan (94% Selesai)</text>
                `)
            },
            {
                id: "5-1",
                num: "6.2",
                title: "Rekapitulasi Sekolah",
                desc: "Tabel Matriks Konseling Siswa per Jurusan & Tombol Aksi Unduh Rekap",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("REKAPITULASI SEKOLAH WAKASIS", "[Nama Wakasis]", "Wakasis")}
                    ${getSidebarSVG("wakasis", 1)}
                    <text x="245" y="90" class="wf-text-title">REKAPITULASI LAYANAN KONSELING TINGKAT SEKOLAH</text>

                    <rect x="245" y="105" width="180" height="32" class="wf-input" />
                    <text x="255" y="126" class="wf-text-body">Periode: Semester Ganjil ▾</text>

                    <rect x="435" y="105" width="180" height="32" class="wf-input" />
                    <text x="445" y="126" class="wf-text-body">Filter Jurusan: Semua ▾</text>

                    <rect x="805" y="105" width="160" height="32" class="wf-btn-primary" />
                    <text x="885" y="126" class="wf-text-btn-pri">Unduh Rekap PDF</text>

                    <rect x="245" y="150" width="720" height="450" class="wf-card" />
                    <rect x="245" y="150" width="720" height="30" class="wf-table-header" />
                    <text x="260" y="170" class="wf-text-body" style="font-weight:600;">JURUSAN / KEAHLIAN</text>
                    <text x="560" y="170" class="wf-text-body" style="font-weight:600;">TOTAL LAYANAN</text>
                    <text x="690" y="170" class="wf-text-body" style="font-weight:600;">SELESAI</text>
                    <text x="790" y="170" class="wf-text-body" style="font-weight:600;">SURAT ORTU</text>
                    <text x="890" y="170" class="wf-text-body" style="font-weight:600;">% TUNTAS</text>

                    <rect x="245" y="180" width="720" height="32" class="wf-table-row" />
                    <text x="260" y="201" class="wf-text-body">Teknik Jaringan Komputer &amp; Telekomunikasi (TJKT)</text>
                    <text x="560" y="201" class="wf-text-body">38 Layanan</text>
                    <text x="690" y="201" class="wf-text-body">36 Selesai</text>
                    <text x="790" y="201" class="wf-text-body">2 Surat</text>
                    <text x="890" y="201" class="wf-text-body" style="font-weight:600;">94.7 %</text>

                    <rect x="245" y="212" width="720" height="32" class="wf-table-row-alt" />
                    <text x="260" y="233" class="wf-text-body">Pengembangan Perangkat Lunak &amp; Gim (PPLG)</text>
                    <text x="560" y="233" class="wf-text-body">32 Layanan</text>
                    <text x="690" y="233" class="wf-text-body">30 Selesai</text>
                    <text x="790" y="233" class="wf-text-body">2 Surat</text>
                    <text x="890" y="233" class="wf-text-body" style="font-weight:600;">93.8 %</text>

                    <rect x="245" y="244" width="720" height="32" class="wf-table-row" />
                    <text x="260" y="265" class="wf-text-body">Manajemen Perkantoran &amp; Layanan Bisnis (MPLB)</text>
                    <text x="560" y="265" class="wf-text-body">30 Layanan</text>
                    <text x="690" y="265" class="wf-text-body">29 Selesai</text>
                    <text x="790" y="265" class="wf-text-body">1 Surat</text>
                    <text x="890" y="265" class="wf-text-body" style="font-weight:600;">96.7 %</text>

                    <rect x="245" y="276" width="720" height="32" class="wf-table-row-alt" />
                    <text x="260" y="297" class="wf-text-body">Akuntansi &amp; Keuangan Lembaga (AKL)</text>
                    <text x="560" y="297" class="wf-text-body">28 Layanan</text>
                    <text x="690" y="297" class="wf-text-body">27 Selesai</text>
                    <text x="790" y="297" class="wf-text-body">1 Surat</text>
                    <text x="890" y="297" class="wf-text-body" style="font-weight:600;">96.4 %</text>

                    <rect x="245" y="308" width="720" height="32" class="wf-table-row" />
                    <text x="260" y="329" class="wf-text-body">Desain Komunikasi Visual (DKV)</text>
                    <text x="560" y="329" class="wf-text-body">28 Layanan</text>
                    <text x="690" y="329" class="wf-text-body">25 Selesai</text>
                    <text x="790" y="329" class="wf-text-body">3 Surat</text>
                    <text x="890" y="329" class="wf-text-body" style="font-weight:600;">89.3 %</text>
                `)
            },
            {
                id: "5-2",
                num: "6.3",
                title: "Modal Unduh Laporan Wakasis",
                desc: "Modal Pemilihan Parameter Ekspor & Pratinjau Dokumen Rekapitulasi PDF Resmi",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("UNDUH LAPORAN REKAPITULASI", "[Nama Wakasis]", "Wakasis")}
                    ${getSidebarSVG("wakasis", 2)}
                    <rect x="220" y="60" width="770" height="570" class="wf-modal-overlay" />

                    <rect x="330" y="110" width="550" height="440" class="wf-modal" />
                    <rect x="330" y="110" width="550" height="40" class="wf-card-header" />
                    <text x="350" y="135" class="wf-text-body" style="font-weight:700;">EKSPOR REKAPITULASI KONSELING TINGKAT SEKOLAH</text>
                    <rect x="840" y="118" width="25" height="24" class="wf-btn-secondary" /><text x="852" y="134" class="wf-text-btn-sec">✕</text>

                    <text x="360" y="175" class="wf-text-body">1. Pilih Tahun Ajaran &amp; Semester</text>
                    <rect x="360" y="183" width="490" height="32" class="wf-input" />
                    <text x="375" y="204" class="wf-text-body">2025/2026 - Semester Genap ▾</text>

                    <text x="360" y="240" class="wf-text-body">2. Format Berkas Dokumen</text>
                    <rect x="360" y="248" width="490" height="32" class="wf-input" />
                    <text x="375" y="269" class="wf-text-body">Dokumen Resmi PDF (Ukuran A4) ▾</text>

                    <text x="360" y="305" class="wf-text-body">3. Pilihan Tanda Tangan Pengesahan</text>
                    <rect x="360" y="313" width="490" height="32" class="wf-input" />
                    <text x="375" y="334" class="wf-text-body">Wakil Kepala Sekolah Bidang Kesiswaan ▾</text>

                    <rect x="605" y="470" width="105" height="38" class="wf-btn-secondary" />
                    <text x="657" y="494" class="wf-text-btn-sec">Batal</text>

                    <rect x="720" y="470" width="130" height="38" class="wf-btn-primary" />
                    <text x="785" y="494" class="wf-text-btn-pri">Unduh Dokumen PDF</text>
                `)
            }
        ]
    },

    // =========================================================================
    // 7. KEPALA SEKOLAH
    // =========================================================================
    {
        name: "7. Kepala Sekolah",
        screens: [
            {
                id: "6-0",
                num: "7.1",
                title: "Dashboard Eksekutif",
                desc: "Dashboard Eksekutif Kepala Sekolah: KPI Layanan Konseling, Beban Kerja & Rasio Layanan",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("DASHBOARD EKSEKUTIF KEPALA SEKOLAH", "[Nama Kepsek]", "Kepala Sekolah")}
                    ${getSidebarSVG("kepsek", 0)}
                    <text x="245" y="90" class="wf-text-title">MONITORING KINERJA LAYANAN KONSELING</text>

                    <rect x="245" y="105" width="225" height="75" class="wf-card" />
                    <text x="260" y="125" class="wf-text-muted">TOTAL KONSELING TERTANGANI</text>
                    <text x="260" y="155" class="wf-text-title" style="font-size:22px;">156 Konseling</text>

                    <rect x="490" y="105" width="225" height="75" class="wf-card" />
                    <text x="505" y="125" class="wf-text-muted">PERSENTASE TUNTAS</text>
                    <text x="505" y="155" class="wf-text-title" style="font-size:22px;">94.2 %</text>

                    <rect x="735" y="105" width="230" height="75" class="wf-card" />
                    <text x="750" y="125" class="wf-text-muted">TOTAL GURU BK AKTIF</text>
                    <text x="750" y="155" class="wf-text-title" style="font-size:22px;">6 Konselor</text>

                    <rect x="245" y="195" width="720" height="200" class="wf-card" />
                    <rect x="245" y="195" width="720" height="32" class="wf-card-header" />
                    <text x="260" y="216" class="wf-text-body" style="font-weight:700;">EVALUASI BEBAN KERJA &amp; KINERJA GURU BK</text>

                    <rect x="245" y="227" width="720" height="28" class="wf-table-header" />
                    <text x="260" y="245" class="wf-text-body" style="font-weight:600;">NAMA GURU BK</text>
                    <text x="460" y="245" class="wf-text-body" style="font-weight:600;">KELAS BINAAN</text>
                    <text x="600" y="245" class="wf-text-body" style="font-weight:600;">SESI DITANGANI</text>
                    <text x="740" y="245" class="wf-text-body" style="font-weight:600;">SELESAI</text>
                    <text x="860" y="245" class="wf-text-body" style="font-weight:600;">% KINERJA</text>

                    <rect x="245" y="255" width="720" height="32" class="wf-table-row" />
                    <text x="260" y="275" class="wf-text-body" style="font-weight:600;">[Nama Guru]</text>
                    <text x="460" y="275" class="wf-text-body">Kelas X &amp; XI TJKT</text>
                    <text x="600" y="275" class="wf-text-body">48 Sesi</text>
                    <text x="740" y="275" class="wf-text-body">46 Konseling</text>
                    <text x="860" y="275" class="wf-text-body" style="font-weight:600;">95.8 %</text>

                    <rect x="245" y="287" width="720" height="32" class="wf-table-row-alt" />
                    <text x="260" y="307" class="wf-text-body" style="font-weight:600;">[Nama Guru]</text>
                    <text x="460" y="307" class="wf-text-body">Kelas XII Semua Jurusan</text>
                    <text x="600" y="307" class="wf-text-body">52 Sesi</text>
                    <text x="740" y="307" class="wf-text-body">49 Konseling</text>
                    <text x="860" y="307" class="wf-text-body" style="font-weight:600;">94.2 %</text>

                    <rect x="245" y="410" width="720" height="195" class="wf-card" />
                    <rect x="245" y="410" width="720" height="32" class="wf-card-header" />
                    <text x="260" y="431" class="wf-text-body" style="font-weight:700;">CETAK / EKSPOR LAPORAN EKSEKUTIF KEPALA SEKOLAH</text>
                    
                    <text x="265" y="465" class="wf-text-muted">Pilih parameter cetak berkas laporan kinerja layanan konseling sekolah:</text>
                    
                    <rect x="265" y="480" width="200" height="32" class="wf-input" />
                    <text x="275" y="501" class="wf-text-body">Tahun Ajaran: 2025/2026 ▾</text>

                    <rect x="480" y="480" width="200" height="32" class="wf-input" />
                    <text x="490" y="501" class="wf-text-body">Format Berkas: PDF Eksekutif ▾</text>

                    <rect x="265" y="540" width="280" height="38" class="wf-btn-primary" />
                    <text x="405" y="564" class="wf-text-btn-pri">UNDUH LAPORAN EKSEKUTIF RESMI</text>
                `)
            },
            {
                id: "6-1",
                num: "7.2",
                title: "Kinerja Guru BK",
                desc: "Tabel Evaluasi Kinerja, Rasio Siswa per Konselor & Tombol Aksi Detail Kinerja",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("KINERJA GURU BK KONSELOR", "[Nama Kepsek]", "Kepala Sekolah")}
                    ${getSidebarSVG("kepsek", 1)}
                    <text x="245" y="90" class="wf-text-title">EVALUASI BEBAN KERJA &amp; EFISIENSI GURU BK</text>
                    <text x="245" y="110" class="wf-text-muted">Pemantauan penyelesaian layanan konseling siswa oleh masing-masing konselor sekolah</text>

                    <rect x="245" y="130" width="720" height="460" class="wf-card" />
                    <rect x="245" y="130" width="720" height="32" class="wf-table-header" />
                    <text x="260" y="151" class="wf-text-body" style="font-weight:600;">NAMA GURU BK</text>
                    <text x="450" y="151" class="wf-text-body" style="font-weight:600;">ROMBEL BINAAN</text>
                    <text x="610" y="151" class="wf-text-body" style="font-weight:600;">TOTAL KONSELING</text>
                    <text x="760" y="151" class="wf-text-body" style="font-weight:600;">SELESAI</text>
                    <text x="870" y="151" class="wf-text-body" style="font-weight:600;">AKSI</text>

                    <rect x="245" y="162" width="720" height="38" class="wf-table-row" />
                    <text x="260" y="186" class="wf-text-body" style="font-weight:600;">[Nama Guru]</text>
                    <text x="450" y="186" class="wf-text-body">Kelas X &amp; XI TJKT</text>
                    <text x="610" y="186" class="wf-text-body">48 Layanan</text>
                    <text x="760" y="186" class="wf-text-body">46 (95.8%)</text>
                    <rect x="860" y="172" width="85" height="20" class="wf-btn-secondary" /><text x="902" y="185" class="wf-text-btn-sec" style="font-size:9px;">Detail Kinerja</text>

                    <rect x="245" y="200" width="720" height="38" class="wf-table-row-alt" />
                    <text x="260" y="224" class="wf-text-body" style="font-weight:600;">[Nama Guru]</text>
                    <text x="450" y="224" class="wf-text-body">Kelas XII Semua</text>
                    <text x="610" y="224" class="wf-text-body">52 Layanan</text>
                    <text x="760" y="224" class="wf-text-body">49 (94.2%)</text>
                    <rect x="860" y="210" width="85" height="20" class="wf-btn-secondary" /><text x="902" y="223" class="wf-text-btn-sec" style="font-size:9px;">Detail Kinerja</text>
                `)
            },
            {
                id: "6-1-detail",
                num: "7.3",
                title: "Modal Detail Evaluasi Kinerja Guru BK",
                desc: "Rincian Beban Kerja, Sebaran Bidang Layanan & Tingkat Kepuasan Siswa per Guru BK",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("DETAIL KINERJA GURU BK", "[Nama Kepsek]", "Kepala Sekolah")}
                    ${getSidebarSVG("kepsek", 1)}
                    <rect x="220" y="60" width="770" height="570" class="wf-modal-overlay" />

                    <rect x="330" y="80" width="550" height="520" class="wf-modal" />
                    <rect x="330" y="80" width="550" height="40" class="wf-card-header" />
                    <text x="350" y="105" class="wf-text-body" style="font-weight:700;">RINCIAN KINERJA: [Nama Guru]</text>
                    <rect x="840" y="88" width="25" height="24" class="wf-btn-secondary" /><text x="852" y="104" class="wf-text-btn-sec">✕</text>

                    <text x="360" y="145" class="wf-text-muted">TOTAL LAYANAN KONSELING DITANGANI</text>
                    <text x="360" y="165" class="wf-text-body" style="font-weight:700;">48 Layanan (Tuntas Selesai: 46 | Sesi Lanjutan: 2)</text>

                    <text x="360" y="200" class="wf-text-muted">SEBARAN BIDANG KONSELING</text>
                    <text x="360" y="220" class="wf-text-body">• Konseling Pribadi: 14 Layanan</text>
                    <text x="360" y="240" class="wf-text-body">• Konseling Belajar: 20 Layanan</text>
                    <text x="360" y="260" class="wf-text-body">• Konseling Sosial: 6 Layanan</text>
                    <text x="360" y="280" class="wf-text-body">• Konseling Karier: 8 Layanan</text>

                    <text x="360" y="320" class="wf-text-muted">SURAT PANGGILAN ORANG TUA DITERBITKAN</text>
                    <text x="360" y="340" class="wf-text-body">3 Surat Resmi Terkirim &amp; Terkoordinasi</text>

                    <rect x="710" y="535" width="140" height="38" class="wf-btn-primary" />
                    <text x="780" y="559" class="wf-text-btn-pri">Tutup</text>
                `)
            },
            {
                id: "6-2",
                num: "7.4",
                title: "Pemetaan Bidang Konseling",
                desc: "Pemetaan Agregasi Bidang Konseling: Matriks Masalah Pribadi, Belajar, Sosial & Karier per Jurusan",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("PEMETAAN BIDANG KONSELING", "[Nama Kepsek]", "Kepala Sekolah")}
                    ${getSidebarSVG("kepsek", 2)}
                    <text x="245" y="90" class="wf-text-title">PEMETAAN &amp; AGREGASI BIDANG KONSELING SISWA</text>
                    <text x="245" y="110" class="wf-text-muted">Pemetaan agregasi bidang konseling untuk perumusan kebijakan pembinaan kesiswaan</text>

                    <rect x="245" y="130" width="720" height="460" class="wf-card" />
                    <rect x="245" y="130" width="720" height="34" class="wf-card-header" />
                    <text x="260" y="152" class="wf-text-body" style="font-weight:700;">MATRIKS AGREGASI BIDANG KONSELING PER KOMPETENSI KEAHLIAN</text>

                    <rect x="245" y="164" width="720" height="30" class="wf-table-header" />
                    <text x="260" y="184" class="wf-text-body" style="font-weight:600;">JURUSAN / KEAHLIAN</text>
                    <text x="440" y="184" class="wf-text-body" style="font-weight:600;">PRIBADI</text>
                    <text x="560" y="184" class="wf-text-body" style="font-weight:600;">BELAJAR</text>
                    <text x="680" y="184" class="wf-text-body" style="font-weight:600;">SOSIAL</text>
                    <text x="800" y="184" class="wf-text-body" style="font-weight:600;">KARIER</text>
                    <text x="900" y="184" class="wf-text-body" style="font-weight:600;">TOTAL</text>

                    <rect x="245" y="194" width="720" height="34" class="wf-table-row" />
                    <text x="260" y="216" class="wf-text-body">Teknik Jaringan Komputer &amp; Telekomunikasi (TJKT)</text>
                    <text x="440" y="216" class="wf-text-body">10 Konseling</text>
                    <text x="560" y="216" class="wf-text-body">16 Konseling</text>
                    <text x="680" y="216" class="wf-text-body">4 Konseling</text>
                    <text x="800" y="216" class="wf-text-body">8 Konseling</text>
                    <text x="900" y="216" class="wf-text-body" style="font-weight:700;">38</text>

                    <rect x="245" y="228" width="720" height="34" class="wf-table-row-alt" />
                    <text x="260" y="250" class="wf-text-body">Pengembangan Perangkat Lunak &amp; Gim (PPLG)</text>
                    <text x="440" y="250" class="wf-text-body">8 Konseling</text>
                    <text x="560" y="250" class="wf-text-body">14 Konseling</text>
                    <text x="680" y="250" class="wf-text-body">3 Konseling</text>
                    <text x="800" y="250" class="wf-text-body">7 Konseling</text>
                    <text x="900" y="250" class="wf-text-body" style="font-weight:700;">32</text>

                    <rect x="245" y="262" width="720" height="34" class="wf-table-row" />
                    <text x="260" y="284" class="wf-text-body">Manajemen Perkantoran &amp; Layanan Bisnis (MPLB)</text>
                    <text x="440" y="284" class="wf-text-body">7 Konseling</text>
                    <text x="560" y="284" class="wf-text-body">12 Konseling</text>
                    <text x="680" y="284" class="wf-text-body">5 Konseling</text>
                    <text x="800" y="284" class="wf-text-body">6 Konseling</text>
                    <text x="900" y="284" class="wf-text-body" style="font-weight:700;">30</text>

                    <rect x="245" y="296" width="720" height="34" class="wf-table-row-alt" />
                    <text x="260" y="318" class="wf-text-body">Akuntansi &amp; Keuangan Lembaga (AKL)</text>
                    <text x="440" y="318" class="wf-text-body">6 Konseling</text>
                    <text x="560" y="318" class="wf-text-body">11 Konseling</text>
                    <text x="680" y="318" class="wf-text-body">4 Konseling</text>
                    <text x="800" y="318" class="wf-text-body">7 Konseling</text>
                    <text x="900" y="318" class="wf-text-body" style="font-weight:700;">28</text>

                    <rect x="245" y="330" width="720" height="34" class="wf-table-row" />
                    <text x="260" y="352" class="wf-text-body">Desain Komunikasi Visual (DKV)</text>
                    <text x="440" y="352" class="wf-text-body">9 Konseling</text>
                    <text x="560" y="352" class="wf-text-body">10 Konseling</text>
                    <text x="680" y="352" class="wf-text-body">3 Konseling</text>
                    <text x="800" y="352" class="wf-text-body">6 Konseling</text>
                    <text x="900" y="352" class="wf-text-body" style="font-weight:700;">28</text>

                    <rect x="265" y="530" width="280" height="38" class="wf-btn-primary" />
                    <text x="405" y="554" class="wf-text-btn-pri">UNDUH PEMETAAN KONSELING (PDF)</text>
                `)
            },
            {
                id: "6-3",
                num: "7.5",
                title: "Modal Unduh Dokumen Eksekutif",
                desc: "Pratinjau Dokumen Laporan Kinerja Resmi Kepala Sekolah & Pengesahan PDF",
                svgContent: wrapSVG(`
                    ${getHeaderSVG("UNDUH LAPORAN EKSEKUTIF KEPSEK", "[Nama Kepsek]", "Kepala Sekolah")}
                    ${getSidebarSVG("kepsek", 3)}
                    <rect x="220" y="60" width="770" height="570" class="wf-modal-overlay" />

                    <rect x="330" y="90" width="550" height="490" class="wf-modal" />
                    <rect x="330" y="90" width="550" height="40" class="wf-card-header" />
                    <text x="350" y="115" class="wf-text-body" style="font-weight:700;">PREVIEW LAPORAN EKSEKUTIF KEPALA SEKOLAH</text>
                    <rect x="840" y="98" width="25" height="24" class="wf-btn-secondary" /><text x="852" y="114" class="wf-text-btn-sec">✕</text>

                    <rect x="360" y="145" width="490" height="300" fill="#ffffff" stroke="#000" stroke-width="1.2" />
                    <text x="605" y="175" class="wf-text-body" style="font-size:11px; font-weight:700;" text-anchor="middle">PEMERINTAH PROVINSI SUMATERA BARAT</text>
                    <text x="605" y="195" class="wf-text-body" style="font-size:10px; font-weight:700;" text-anchor="middle">SMK NEGERI 2 GUGUAK</text>
                    <line x1="380" y1="205" x2="830" y2="205" stroke="#000" stroke-width="1.5" />
                    
                    <text x="605" y="230" class="wf-text-body" style="font-weight:700;" text-anchor="middle">LAPORAN EVALUASI LAYANAN KONSELING SISWA</text>
                    <text x="605" y="248" class="wf-text-muted" text-anchor="middle">Tahun Ajaran 2025/2026 - Semester Genap</text>

                    <text x="390" y="280" class="wf-text-body">• Total Konseling Sekolah: 156 Konseling (94.2% Tuntas)</text>
                    <text x="390" y="305" class="wf-text-body">• Jumlah Guru BK Konselor: 6 Personel Aktif</text>
                    <text x="390" y="330" class="wf-text-body">• Rekomendasi: Peningkatan pendampingan motivasi belajar kelas XII</text>

                    <text x="750" y="380" class="wf-text-body" style="font-size:9px;" text-anchor="middle">Kepala Sekolah,</text>
                    <text x="750" y="420" class="wf-text-body" style="font-size:9.5px; font-weight:700;" text-anchor="middle">[Nama Kepsek]</text>

                    <rect x="580" y="525" width="110" height="38" class="wf-btn-secondary" />
                    <text x="635" y="549" class="wf-text-btn-sec">Batal</text>

                    <rect x="705" y="525" width="145" height="38" class="wf-btn-primary" />
                    <text x="777" y="549" class="wf-text-btn-pri">Unduh PDF Resmi</text>
                `)
            }
        ]
    }
];
