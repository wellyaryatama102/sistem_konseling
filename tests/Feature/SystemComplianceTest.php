<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PengajuanKonseling;
use App\Models\SesiKonseling;
use App\Models\TindakLanjut;
use App\Models\SuratPanggilan;
use App\Models\JadwalKetersediaan;

use Illuminate\Foundation\Testing\RefreshDatabase;

class SystemComplianceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_redirected_to_login()
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_admin_access()
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $this->get(route('admin.dashboard'))->assertStatus(200);
        $this->get(route('admin.users.index'))->assertStatus(200);
        $this->get(route('admin.siswa.index'))->assertStatus(200);
        $this->get(route('admin.kelas.index'))->assertStatus(200);
        $this->get(route('admin.jurusan.index'))->assertStatus(200);
        $this->get(route('admin.tahun-ajaran.index'))->assertStatus(200);
        $this->get(route('admin.log-aktivitas.index'))->assertStatus(200);
        $this->get(route('admin.pengaturan.index'))->assertStatus(200);
        $this->get(route('profile.edit'))->assertStatus(200);
    }


    public function test_guru_bk_access()
    {
        $guru = User::where('role', 'guru_bk')->first();
        $this->actingAs($guru);

        $this->get(route('guru.dashboard'))->assertStatus(200);
        $this->get(route('guru.pengajuan.index'))->assertStatus(200);
        $this->get(route('guru.ketersediaan.index'))->assertStatus(200);
        $this->get(route('guru.jadwal.index'))->assertStatus(200);
        $this->get(route('guru.layanan.index'))->assertStatus(200);
        $this->get(route('guru.tindak-lanjut.index'))->assertStatus(200);
        $this->get(route('guru.surat.index'))->assertStatus(200);
        $this->get(route('guru.siswa.index'))->assertStatus(200);
        $this->get(route('guru.notifikasi.index'))->assertStatus(200);
        $this->get(route('guru.laporan.index'))->assertStatus(200);
        $this->get(route('profile.edit'))->assertStatus(200);
    }

    public function test_siswa_access()
    {
        $siswa = User::where('role', 'siswa')->first();
        $this->actingAs($siswa);

        $this->get(route('siswa.dashboard'))->assertStatus(200);
        $this->get(route('siswa.jadwal.available'))->assertStatus(200);
        $this->get(route('siswa.pengajuan.index'))->assertStatus(200);
        $this->get(route('siswa.konseling.index'))->assertStatus(200);
        $this->get(route('profile.edit'))->assertStatus(200);
    }

    public function test_wali_kelas_access()
    {
        $wali = User::where('role', 'wali_kelas')->first();
        $this->actingAs($wali);

        $this->get(route('wali.dashboard'))->assertStatus(200);
        $this->get(route('wali.siswa.index'))->assertStatus(200);
        $this->get(route('wali.monitoring.index'))->assertStatus(200);
        $this->get(route('wali.rujukan.create'))->assertStatus(200);
        $this->get(route('wali.jadwal.index'))->assertStatus(200);
        $this->get(route('profile.edit'))->assertStatus(200);
    }

    public function test_wakasis_access()
    {
        $wakasis = User::where('role', 'wakasis')->first();
        $this->actingAs($wakasis);

        $this->get(route('wakasis.dashboard'))->assertStatus(200);
        $this->get(route('wakasis.rekapitulasi.index'))->assertStatus(200);
        $this->get(route('wakasis.siswa.index'))->assertStatus(200);
        $this->get(route('profile.edit'))->assertStatus(200);
    }

    public function test_kepala_sekolah_access()
    {
        $kepsek = User::where('role', 'kepala_sekolah')->first();
        $this->actingAs($kepsek);

        $this->get(route('kepsek.dashboard'))->assertStatus(200);
        $this->get(route('kepsek.kinerja.index'))->assertStatus(200);
        $this->get(route('kepsek.pemetaan.index'))->assertStatus(200);
        $this->get(route('kepsek.laporan.index'))->assertStatus(200);
        $this->get(route('kepsek.siswa.index'))->assertStatus(200);
        $this->get(route('profile.edit'))->assertStatus(200);
    }
}
