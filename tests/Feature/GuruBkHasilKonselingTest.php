<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\SesiKonseling;
use Carbon\Carbon;

class GuruBkHasilKonselingTest extends TestCase
{
    use RefreshDatabase;

    protected User $guruBk;
    protected User $siswaUser;
    protected Siswa $siswa;
    protected Kelas $kelas;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->guruBk = User::where('role', 'guru_bk')->first();
        $this->siswaUser = User::where('role', 'siswa')->first();
        $this->siswa = $this->siswaUser->siswa;
        $this->kelas = $this->siswa->kelas;
    }

    public function test_guru_bk_can_view_input_hasil_menu_data_siswa(): void
    {
        $response = $this->actingAs($this->guruBk)->get(route('guru.layanan.index'));
        $response->assertStatus(200);
        $response->assertSee('Pelayanan Sesi Konseling Siswa');
    }

    public function test_guru_bk_can_open_form_input_hasil(): void
    {
        $sesi = SesiKonseling::first();

        $response = $this->actingAs($this->guruBk)->get(route('guru.siswa.input-hasil', $sesi));
        $response->assertStatus(200);
        $response->assertSee('Input Hasil');
        $response->assertSee('Hasil Konseling');
        $response->assertSee('Catatan Rahasia Guru BK');
    }

    public function test_guru_bk_validation_fails_when_fields_are_missing(): void
    {
        $sesi = SesiKonseling::first();

        $response = $this->actingAs($this->guruBk)->post(route('guru.siswa.simpan-hasil', $sesi), [
            'hasil_konseling' => '',
            'status_kehadiran' => '',
            'opsi_tindak_lanjut' => '',
        ]);

        $response->assertSessionHasErrors(['hasil_konseling', 'status_kehadiran', 'opsi_tindak_lanjut']);
    }

    public function test_guru_bk_can_save_hasil_konseling(): void
    {
        $sesi = SesiKonseling::first();

        $response = $this->actingAs($this->guruBk)->post(route('guru.siswa.simpan-hasil', $sesi), [
            'status_kehadiran' => 'hadir',
            'hasil_konseling' => 'Siswa berhasil dibimbing terkait perencanaan karir lanjutan.',
            'rencana_tindak_lanjut' => 'Mengikuti konseling individu lanjutan.',
            'catatan_untuk_siswa' => 'Tetap semangat belajar!',
            'catatan_rahasia' => 'Catatan privat: Siswa memiliki potensi akademis tinggi.',
            'opsi_tindak_lanjut' => 'selesai',
            'catatan_tindak_lanjut' => 'Selesai tuntas.',
        ]);

        $response->assertRedirect(route('guru.layanan.index'));
        $response->assertSessionHas('success', 'Hasil konseling dan tindak lanjut berhasil disimpan.');

        $sesi->refresh();
        $this->assertEquals('selesai', $sesi->status_sesi);
        $this->assertEquals('Siswa berhasil dibimbing terkait perencanaan karir lanjutan.', $sesi->hasil_konseling);
        $this->assertEquals('Catatan privat: Siswa memiliki potensi akademis tinggi.', $sesi->catatan_rahasia);
    }

    public function test_detail_rekap_siswa_displays_complete_profile_summary_and_history(): void
    {
        $response = $this->actingAs($this->guruBk)->get(route('guru.siswa.show', $this->siswa));
        $response->assertStatus(200);
        $response->assertSee($this->siswa->nama_siswa);
    }

    public function test_siswa_cannot_see_catatan_rahasia(): void
    {
        $response = $this->actingAs($this->siswaUser)->get(route('siswa.konseling.index'));
        $response->assertStatus(200);
        $response->assertDontSee('catatan_rahasia');
        $response->assertDontSee('Catatan Rahasia Guru BK');
    }
}
