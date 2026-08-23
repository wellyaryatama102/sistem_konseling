<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SiswaController as AdminSiswaController;
use App\Http\Controllers\Admin\KelasController as AdminKelasController;
use App\Http\Controllers\Admin\JurusanController as AdminJurusanController;
use App\Http\Controllers\Admin\TahunAjaranController as AdminTahunAjaranController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\LogAktivitasController as AdminLogAktivitasController;
use App\Http\Controllers\Admin\PengaturanController as AdminPengaturanController;
use App\Http\Controllers\GuruBk\GuruBkController;
use App\Http\Controllers\Siswa\SiswaController;
use App\Http\Controllers\WaliKelas\WaliKelasController;
use App\Http\Controllers\Wakasis\WakasisController;
use App\Http\Controllers\KepalaSekolah\KepalaSekolahController;

// Auth & Public Landing Routes
Route::get('/', function () {
    if (auth()->check()) {
        return (new LoginController)->redirectUser(auth()->user());
    }
    return view('landing');
})->name('landing');


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Unified Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// 1. ADMIN ROUTES 
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    // Manajemen Pengguna
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
    // Data Siswa
    Route::resource('siswa', AdminSiswaController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);

    // Manajemen Kelas & Jurusan
    Route::resource('kelas', AdminKelasController::class)->except(['show'])->parameters(['kelas' => 'kelas']);
    Route::resource('jurusan', AdminJurusanController::class)->except(['create', 'show'])->parameters(['jurusan' => 'jurusan']);

    // Tahun Ajaran
    Route::get('/tahun-ajaran', [AdminTahunAjaranController::class, 'index'])->name('tahun-ajaran.index');
    Route::post('/tahun-ajaran', [AdminTahunAjaranController::class, 'store'])->name('tahun-ajaran.store');
    Route::patch('/tahun-ajaran/{tahunAjaran}/status', [AdminTahunAjaranController::class, 'toggleStatus'])->name('tahun-ajaran.toggle-status');
    Route::delete('/tahun-ajaran/{tahunAjaran}', [AdminTahunAjaranController::class, 'destroy'])->name('tahun-ajaran.destroy');


    // Laporan & Rekapitulasi
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pdf', [AdminLaporanController::class, 'downloadPdf'])->name('laporan.pdf');
    Route::get('/laporan/excel', [AdminLaporanController::class, 'downloadExcel'])->name('laporan.excel');

    // Log Aktivitas
    Route::get('/log-aktivitas', [AdminLogAktivitasController::class, 'index'])->name('log-aktivitas.index');

    // Pengaturan Sistem
    Route::get('/pengaturan', [AdminPengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan', [AdminPengaturanController::class, 'update'])->name('pengaturan.update');
    Route::post('/pengaturan/test-wa', [AdminPengaturanController::class, 'testSend'])->name('pengaturan.test-wa');
});

// 2. GURU BK ROUTES 
Route::middleware(['auth', 'role:guru_bk'])->prefix('guru-bk')->name('guru.')->group(function () {
    // 1. Dashboard Guru BK
    Route::get('/dashboard', [GuruBkController::class, 'dashboard'])->name('guru.dashboard');

    // 2. Pengajuan Konseling
    Route::get('/pengajuan', [GuruBkController::class, 'indexPengajuan'])->name('pengajuan.index');
    Route::post('/pengajuan/{pengajuan}/validasi', [GuruBkController::class, 'validasiPengajuan'])->name('pengajuan.validasi');

    // 3. Jadwal Konseling
    Route::get('/jadwal', [GuruBkController::class, 'indexJadwal'])->name('jadwal.index');

    // 4. Layanan & Pelaksanaan Sesi Konseling
    Route::get('/layanan', [GuruBkController::class, 'indexLayanan'])->name('layanan.index');
    Route::get('/konseling', [GuruBkController::class, 'indexLayanan'])->name('konseling.index');

    // 5. Data Siswa & Input Hasil
    Route::get('/siswa', [GuruBkController::class, 'indexSiswa'])->name('siswa.index');
    Route::get('/siswa/{konseling}/input-hasil', [GuruBkController::class, 'inputHasil'])->name('siswa.input-hasil');
    Route::post('/siswa/{konseling}/simpan-hasil', [GuruBkController::class, 'simpanHasil'])->name('siswa.simpan-hasil');
    Route::get('/siswa/{siswa}', [GuruBkController::class, 'showSiswa'])->name('siswa.show');

    // 6. Ketersediaan Jadwal
    Route::get('/ketersediaan', [GuruBkController::class, 'indexKetersediaan'])->name('ketersediaan.index');
    Route::post('/ketersediaan', [GuruBkController::class, 'storeKetersediaan'])->name('ketersediaan.store');
    Route::delete('/ketersediaan/{jadwal}', [GuruBkController::class, 'destroyKetersediaan'])->name('ketersediaan.destroy');

    // 7. Riwayat Konseling
    Route::get('/riwayat', [GuruBkController::class, 'indexRiwayat'])->name('riwayat.index');

    // 8. Tindak Lanjut
    Route::get('/tindak-lanjut', [GuruBkController::class, 'indexTindakLanjut'])->name('tindak-lanjut.index');
    Route::post('/tindak-lanjut', [GuruBkController::class, 'storeTindakLanjut'])->name('tindak-lanjut.store');
    Route::put('/tindak-lanjut/{tindakLanjut}', [GuruBkController::class, 'updateTindakLanjut'])->name('tindak-lanjut.update');

    // 9. Surat Panggilan Orang Tua
    Route::get('/surat', [GuruBkController::class, 'indexSurat'])->name('surat.index');
    Route::get('/surat/create', [GuruBkController::class, 'createSurat'])->name('surat.create');
    Route::post('/surat', [GuruBkController::class, 'storeSurat'])->name('surat.store');
    Route::get('/surat/{surat}', [GuruBkController::class, 'showSurat'])->name('surat.show');
    Route::get('/surat/{surat}/pdf', [GuruBkController::class, 'downloadSuratPdf'])->name('surat.pdf');
    Route::post('/surat/{surat}/kirim-wa', [GuruBkController::class, 'kirimSuratWa'])->name('surat.kirim-wa');

    // 10. Notifikasi & Log WA
    Route::get('/notifikasi', [GuruBkController::class, 'indexNotifikasi'])->name('notifikasi.index');

    // 11. Laporan & Rekapitulasi BK
    Route::get('/laporan', [GuruBkController::class, 'laporan'])->name('laporan.index');
    Route::get('/laporan/pdf', [GuruBkController::class, 'downloadLaporanPdf'])->name('laporan.pdf');
    Route::get('/laporan/excel', [GuruBkController::class, 'downloadLaporanExcel'])->name('laporan.excel');

    // Data Kelas
    Route::get('/kelas', [GuruBkController::class, 'indexKelas'])->name('kelas.index');
});

// 3. SISWA ROUTES 
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');

    // Profil Siswa
    Route::get('/profile', [SiswaController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile', [SiswaController::class, 'updateProfile'])->name('profile.update');

    // Jadwal Tersedia & Ajukan Konseling
    Route::get('/jadwal-tersedia', [SiswaController::class, 'indexJadwalAvailable'])->name('jadwal.available');
    Route::post('/jadwal/{slot}/ajukan', [SiswaController::class, 'ajukanJadwal'])->name('jadwal.ajukan');

    // Pengajuan & Pembatalan
    Route::get('/pengajuan-saya', [SiswaController::class, 'indexPengajuanSaya'])->name('pengajuan.index');
    Route::post('/pengajuan/{pengajuan}/batal', [SiswaController::class, 'batalkanPengajuan'])->name('pengajuan.batal');

    // Riwayat & Arahan Hasil Konseling
    Route::get('/hasil-konseling-saya', [SiswaController::class, 'indexHasilKonseling'])->name('konseling.index');
});

// 4. WALI KELAS ROUTES 
Route::middleware(['auth', 'role:wali_kelas'])->prefix('wali-kelas')->name('wali.')->group(function () {
    // 1. Dashboard Walas
    Route::get('/dashboard', [WaliKelasController::class, 'dashboard'])->name('dashboard');

    // 2. Data Siswa Binaan
    Route::get('/siswa', [WaliKelasController::class, 'indexSiswa'])->name('siswa.index');
    Route::get('/siswa/{siswa}', [WaliKelasController::class, 'showSiswa'])->name('siswa.show');

    // 3. Ajukan Rujukan Konseling
    Route::get('/rujukan', [WaliKelasController::class, 'createRujukan'])->name('rujukan.create');
    Route::get('/rujukan/create', [WaliKelasController::class, 'createRujukan'])->name('rujukan.create-form');
    Route::post('/rujukan', [WaliKelasController::class, 'storeRujukan'])->name('rujukan.store');

    // 4. Monitoring Layanan Siswa Binaan
    Route::get('/monitoring', [WaliKelasController::class, 'indexMonitoring'])->name('monitoring.index');
    Route::get('/pemantauan', [WaliKelasController::class, 'indexMonitoring'])->name('pemantauan.index');

    // 5. Jadwal Konseling Siswa
    Route::get('/jadwal', [WaliKelasController::class, 'indexJadwal'])->name('jadwal.index');
});

// 5. WAKASIS ROUTES 
Route::middleware(['auth', 'role:wakasis'])->prefix('wakasis')->name('wakasis.')->group(function () {
    // 1. Dashboard Wakasis
    Route::get('/dashboard', [WakasisController::class, 'dashboard'])->name('dashboard');

    // 2. Data Siswa (School Wide Read-Only)
    Route::get('/siswa', [WakasisController::class, 'indexSiswa'])->name('siswa.index');

    // 3. Rekapitulasi Sekolah & Laporan Statistik
    Route::get('/rekapitulasi', [WakasisController::class, 'indexRekapitulasi'])->name('rekapitulasi.index');
    Route::get('/laporan', [WakasisController::class, 'indexRekapitulasi'])->name('laporan.index');
    Route::get('/laporan/pdf', [WakasisController::class, 'downloadLaporanPdf'])->name('laporan.pdf');
    Route::get('/laporan/excel', [WakasisController::class, 'downloadLaporanExcel'])->name('laporan.excel');
});

// 6. KEPALA SEKOLAH ROUTES 
Route::middleware(['auth', 'role:kepala_sekolah'])->prefix('kepala-sekolah')->name('kepsek.')->group(function () {
    // 1. Dashboard Eksekutif
    Route::get('/dashboard', [KepalaSekolahController::class, 'dashboard'])->name('dashboard');

    // 2. Kinerja Guru BK
    Route::get('/kinerja-guru-bk', [KepalaSekolahController::class, 'kinerjaGuruBk'])->name('kinerja.index');

    // 3. Pemetaan Bidang Konseling
    Route::get('/pemetaan-bidang', [KepalaSekolahController::class, 'pemetaanBidang'])->name('pemetaan.index');

    // 4. Data Siswa (School Wide Read-Only)
    Route::get('/siswa', [KepalaSekolahController::class, 'indexSiswa'])->name('siswa.index');

    // 5. Laporan Kinerja Eksekutif
    Route::get('/laporan', [KepalaSekolahController::class, 'indexLaporan'])->name('laporan.index');
    Route::get('/laporan/pdf', [KepalaSekolahController::class, 'downloadLaporanPdf'])->name('laporan.pdf');
    Route::get('/laporan/excel', [KepalaSekolahController::class, 'downloadLaporanExcel'])->name('laporan.excel');
});
