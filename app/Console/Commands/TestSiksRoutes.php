<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class TestSiksRoutes extends Command
{
    protected $signature = 'siks:test';
    protected $description = 'Test all 6 roles and routes in SIKS';

    public function handle()
    {
        $this->info('Starting SIKS 6-Role System Verification...');
        view()->share('errors', new \Illuminate\Support\ViewErrorBag());

        $roleTests = [
            'admin' => [
                'username' => 'admin',
                'actions' => [
                    ['name' => 'Dashboard Admin', 'callable' => fn() => app(\App\Http\Controllers\Admin\UserController::class)->dashboard()],
                    ['name' => 'Users Index', 'callable' => fn() => app(\App\Http\Controllers\Admin\UserController::class)->index(request())],
                    ['name' => 'Siswa Index', 'callable' => fn() => app(\App\Http\Controllers\Admin\SiswaController::class)->index(request())],
                    ['name' => 'Kelas Index', 'callable' => fn() => app(\App\Http\Controllers\Admin\KelasController::class)->index(request())],
                    ['name' => 'Tahun Ajaran Index', 'callable' => fn() => app(\App\Http\Controllers\Admin\TahunAjaranController::class)->index()],
                    ['name' => 'Log Aktivitas Index', 'callable' => fn() => app(\App\Http\Controllers\Admin\LogAktivitasController::class)->index(request())],
                    ['name' => 'Pengaturan Index', 'callable' => fn() => app(\App\Http\Controllers\Admin\PengaturanController::class)->index()],
                    ['name' => 'Profile Edit', 'callable' => fn() => app(\App\Http\Controllers\ProfileController::class)->edit()],
                ]
            ],
            'guru_bk' => [
                'username' => 'gurubk',
                'actions' => [
                    ['name' => 'Dashboard Guru BK', 'callable' => fn() => app(\App\Http\Controllers\GuruBk\GuruBkController::class)->dashboard()],
                    ['name' => 'Pengajuan Index', 'callable' => fn() => app(\App\Http\Controllers\GuruBk\GuruBkController::class)->indexPengajuan(request())],
                    ['name' => 'Ketersediaan Index', 'callable' => fn() => app(\App\Http\Controllers\GuruBk\GuruBkController::class)->indexKetersediaan(request())],
                    ['name' => 'Jadwal Index', 'callable' => fn() => app(\App\Http\Controllers\GuruBk\GuruBkController::class)->indexJadwal(request())],
                    ['name' => 'Layanan Index', 'callable' => fn() => app(\App\Http\Controllers\GuruBk\GuruBkController::class)->indexLayanan(request())],
                    ['name' => 'Tindak Lanjut Index', 'callable' => fn() => app(\App\Http\Controllers\GuruBk\GuruBkController::class)->indexTindakLanjut(request())],
                    ['name' => 'Surat Index', 'callable' => fn() => app(\App\Http\Controllers\GuruBk\GuruBkController::class)->indexSurat(request())],
                    ['name' => 'Siswa Index', 'callable' => fn() => app(\App\Http\Controllers\GuruBk\GuruBkController::class)->indexSiswa(request())],
                    ['name' => 'Notifikasi Index', 'callable' => fn() => app(\App\Http\Controllers\GuruBk\GuruBkController::class)->indexNotifikasi(request())],
                    ['name' => 'Laporan Index', 'callable' => fn() => app(\App\Http\Controllers\GuruBk\GuruBkController::class)->laporan(request())],
                    ['name' => 'Profile Edit', 'callable' => fn() => app(\App\Http\Controllers\ProfileController::class)->edit()],
                ]
            ],
            'siswa' => [
                'username' => 'siswa1',
                'actions' => [
                    ['name' => 'Dashboard Siswa', 'callable' => fn() => app(\App\Http\Controllers\Siswa\SiswaController::class)->dashboard()],
                    ['name' => 'Jadwal Available', 'callable' => fn() => app(\App\Http\Controllers\Siswa\SiswaController::class)->indexJadwalAvailable(request())],
                    ['name' => 'Pengajuan Saya', 'callable' => fn() => app(\App\Http\Controllers\Siswa\SiswaController::class)->indexPengajuanSaya()],
                    ['name' => 'Hasil Konseling Saya', 'callable' => fn() => app(\App\Http\Controllers\Siswa\SiswaController::class)->indexHasilKonseling()],
                    ['name' => 'Profile Edit', 'callable' => fn() => app(\App\Http\Controllers\ProfileController::class)->edit()],
                ]
            ],
            'wali_kelas' => [
                'username' => 'walikelas1',
                'actions' => [
                    ['name' => 'Dashboard Wali Kelas', 'callable' => fn() => app(\App\Http\Controllers\WaliKelas\WaliKelasController::class)->dashboard(request())],
                    ['name' => 'Siswa Index', 'callable' => fn() => app(\App\Http\Controllers\WaliKelas\WaliKelasController::class)->indexSiswa(request())],
                    ['name' => 'Monitoring Index', 'callable' => fn() => app(\App\Http\Controllers\WaliKelas\WaliKelasController::class)->indexMonitoring(request())],
                    ['name' => 'Rujukan Create', 'callable' => fn() => app(\App\Http\Controllers\WaliKelas\WaliKelasController::class)->createRujukan(request())],
                    ['name' => 'Jadwal Index', 'callable' => fn() => app(\App\Http\Controllers\WaliKelas\WaliKelasController::class)->indexJadwal(request())],
                    ['name' => 'Profile Edit', 'callable' => fn() => app(\App\Http\Controllers\ProfileController::class)->edit()],
                ]
            ],
            'wakasis' => [
                'username' => 'wakasis',
                'actions' => [
                    ['name' => 'Dashboard Wakasis', 'callable' => fn() => app(\App\Http\Controllers\Wakasis\WakasisController::class)->dashboard()],
                    ['name' => 'Rekapitulasi Index', 'callable' => fn() => app(\App\Http\Controllers\Wakasis\WakasisController::class)->indexRekapitulasi(request())],
                    ['name' => 'Siswa Index', 'callable' => fn() => app(\App\Http\Controllers\Wakasis\WakasisController::class)->indexSiswa(request())],
                    ['name' => 'Profile Edit', 'callable' => fn() => app(\App\Http\Controllers\ProfileController::class)->edit()],
                ]
            ],
            'kepala_sekolah' => [
                'username' => 'kepsek',
                'actions' => [
                    ['name' => 'Dashboard Kepsek', 'callable' => fn() => app(\App\Http\Controllers\KepalaSekolah\KepalaSekolahController::class)->dashboard()],
                    ['name' => 'Kinerja Guru BK', 'callable' => fn() => app(\App\Http\Controllers\KepalaSekolah\KepalaSekolahController::class)->kinerjaGuruBk(request())],
                    ['name' => 'Pemetaan Bidang', 'callable' => fn() => app(\App\Http\Controllers\KepalaSekolah\KepalaSekolahController::class)->pemetaanBidang(request())],
                    ['name' => 'Laporan Index', 'callable' => fn() => app(\App\Http\Controllers\KepalaSekolah\KepalaSekolahController::class)->indexLaporan(request())],
                    ['name' => 'Siswa Index', 'callable' => fn() => app(\App\Http\Controllers\KepalaSekolah\KepalaSekolahController::class)->indexSiswa(request())],
                    ['name' => 'Profile Edit', 'callable' => fn() => app(\App\Http\Controllers\ProfileController::class)->edit()],
                ]
            ]
        ];

        $passed = 0;
        $failed = 0;

        foreach ($roleTests as $role => $config) {
            $this->line("\n------------------------------------------------");
            $this->info("AUTHENTICATING AS: [{$role}] (Username: {$config['username']})");
            $this->line("------------------------------------------------");

            $user = User::where('username', $config['username'])->first();
            if (!$user) {
                $this->error("User {$config['username']} not found in database!");
                $failed++;
                continue;
            }

            Auth::login($user);

            foreach ($config['actions'] as $act) {
                try {
                    $result = ($act['callable'])();
                    if ($result instanceof \Illuminate\View\View) {
                        // Render view to detect any Blade syntax or variable errors
                        $html = $result->render();
                        $this->info(" [PASS] {$act['name']} (Rendered View: {$result->name()})");
                        $passed++;
                    } else {
                        $this->info(" [PASS] {$act['name']}");
                        $passed++;
                    }
                } catch (\Throwable $e) {
                    $this->error(" [FAIL] {$act['name']}: " . $e->getMessage());
                    $this->error("        File: " . $e->getFile() . " Line: " . $e->getLine());
                    $failed++;
                }
            }
        }

        $this->line("\n================================================");
        $this->info("RESULTS: {$passed} PASSED, {$failed} FAILED");
        $this->line("================================================");

        return $failed === 0 ? 0 : 1;
    }
}
