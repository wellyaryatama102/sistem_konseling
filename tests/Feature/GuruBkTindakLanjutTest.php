<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\SesiKonseling;
use App\Models\TindakLanjut;
use Carbon\Carbon;

class GuruBkTindakLanjutTest extends TestCase
{
    use RefreshDatabase;

    protected User $guruBk;
    protected SesiKonseling $sesi;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->guruBk = User::where('role', 'guru_bk')->first();
        $this->sesi = SesiKonseling::first();
    }

    public function test_guru_bk_can_access_tindak_lanjut_menu_without_error(): void
    {
        $response = $this->actingAs($this->guruBk)->get(route('guru.tindak-lanjut.index'));
        $response->assertStatus(200);
        $response->assertSee('Surat Panggilan Orang Tua', false);
        $response->assertSee('Rencana Tindak Lanjut (RTL)', false);
    }

    public function test_guru_bk_can_create_tindak_lanjut(): void
    {
        $response = $this->actingAs($this->guruBk)->post(route('guru.tindak-lanjut.store'), [
            'id_sesi' => $this->sesi->id_sesi,
            'jenis_aksi' => 'selesai',
            'catatan' => 'Siswa telah memahami arahan dan berkomitmen memperbaiki kedisiplinan.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Data tindak lanjut berhasil ditambahkan.');

        $this->assertDatabaseHas('tindak_lanjut', [
            'id_sesi' => $this->sesi->id_sesi,
            'jenis_aksi' => 'selesai',
        ]);
    }

    public function test_guru_bk_can_update_tindak_lanjut(): void
    {
        $tindakLanjut = TindakLanjut::create([
            'id_sesi' => $this->sesi->id_sesi,
            'jenis_aksi' => 'selesai',
            'status_tindak_lanjut' => 'belum_ditindaklanjuti',
            'catatan' => 'Catatan awal.',
        ]);

        $response = $this->actingAs($this->guruBk)->put(route('guru.tindak-lanjut.update', $tindakLanjut), [
            'status_tindak_lanjut' => 'selesai',
            'catatan' => 'Telah selesai tuntas.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Status tindak lanjut berhasil diperbarui.');

        $this->assertDatabaseHas('tindak_lanjut', [
            'id_tindak_lanjut' => $tindakLanjut->id_tindak_lanjut,
            'status_tindak_lanjut' => 'selesai',
        ]);
    }

    public function test_parent_accompanied_followup_counseling_flow(): void
    {
        $siswaUser = User::where('role', 'siswa')->first();
        $siswa = $siswaUser->siswa;
        $siswa->update(['no_wa_orang_tua_wali' => '08123456789']);

        // 1. Guru BK save hasil with opsi surat_ortu
        $response = $this->actingAs($this->guruBk)->post(route('guru.siswa.simpan-hasil', $this->sesi), [
            'status_kehadiran' => 'hadir',
            'hasil_konseling' => 'Siswa perlu bimbingan pendampingan ortu.',
            'opsi_tindak_lanjut' => 'surat_ortu',
            'catatan_tindak_lanjut' => 'Panggilan ortu & konseling lanjutan.',
        ]);

        $tindakLanjut = TindakLanjut::latest('id_tindak_lanjut')->first();
        $this->assertNotNull($tindakLanjut);
        $response->assertRedirect(route('guru.surat.create', ['tindak_lanjut_id' => $tindakLanjut->id_tindak_lanjut]));
        $this->assertEquals('belum_ditindaklanjuti', $tindakLanjut->status_tindak_lanjut);

        // 2. Siswa views dashboard & sees pending parent-accompanied instructions
        $dashResp = $this->actingAs($siswaUser)->get(route('siswa.dashboard'));
        $dashResp->assertStatus(200);
        $dashResp->assertSee('Pemanggilan Orang Tua &amp; Konseling Lanjutan', false);

        // 3. Siswa selects available slot
        $guruBkModel = \App\Models\GuruBk::first();
        $slot = \App\Models\JadwalKetersediaan::create([
            'id_guru_bk' => $guruBkModel->id_guru_bk,
            'tanggal_tersedia' => Carbon::tomorrow()->toDateString(),
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '10:00:00',
            'status_slot' => 'tersedia',
        ]);

        $bookResp = $this->actingAs($siswaUser)->post(route('siswa.jadwal.ajukan', $slot), [
            'jenis_konseling' => 'individu',
            'alasan_pengajuan' => 'Sesi Konseling Lanjutan Pendampingan Orang Tua',
            'tindak_lanjut_id' => $tindakLanjut->id_tindak_lanjut,
        ]);

        $bookResp->assertRedirect(route('siswa.pengajuan.index'));

        // Verify slot is booked & tindak_lanjut updated to terjadwal
        $slot->refresh();
        $tindakLanjut->refresh();
        $this->assertEquals('terisi', $slot->status_slot);
        $this->assertEquals('terjadwal', $tindakLanjut->status_tindak_lanjut);
        $this->assertEquals($slot->id_jadwal, $tindakLanjut->id_jadwal);
    }
}
