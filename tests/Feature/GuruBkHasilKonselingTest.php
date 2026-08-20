<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Konseling;
use App\Models\RiwayatPerkembangan;
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

        $this->guruBk = User::where('username', 'gurubk')->first();
        $this->siswaUser = User::where('username', 'siswa1')->first();
        $this->siswa = $this->siswaUser->siswa;
        $this->kelas = $this->siswa->kelas;
    }

    public function test_guru_bk_can_view_input_hasil_menu_data_siswa(): void
    {
        $response = $this->actingAs($this->guruBk)->get(route('guru.siswa.index'));
        $response->assertStatus(200);
        $response->assertSeeText('Data Siswa & Input Hasil Konseling', false);
        $response->assertSee('Input Hasil');
    }

    public function test_guru_bk_can_open_form_input_hasil(): void
    {
        $konseling = Konseling::where('siswa_id', $this->siswa->id)->first();

        $response = $this->actingAs($this->guruBk)->get(route('guru.siswa.input-hasil', $konseling));
        $response->assertStatus(200);
        $response->assertSee('Form Input Hasil Konseling');
        $response->assertSee($this->siswa->user->name);
        $response->assertSee($this->siswa->nis);
        $response->assertSee('Status Perkembangan Siswa');
        $response->assertSee('Catatan Rahasia Guru BK');
    }

    public function test_guru_bk_validation_fails_when_fields_are_missing(): void
    {
        $konseling = Konseling::where('siswa_id', $this->siswa->id)->first();

        $response = $this->actingAs($this->guruBk)->post(route('guru.siswa.simpan-hasil', $konseling), [
            'hasil_konseling' => '',
            'kesimpulan' => '',
            'status_perkembangan' => '',
        ]);

        $response->assertSessionHasErrors(['hasil_konseling', 'kesimpulan', 'status_perkembangan']);
    }

    public function test_guru_bk_can_save_hasil_konseling_and_creates_history(): void
    {
        $konseling = Konseling::where('siswa_id', $this->siswa->id)->first();
        $initialHistoryCount = RiwayatPerkembangan::where('siswa_id', $this->siswa->id)->count();

        $response = $this->actingAs($this->guruBk)->post(route('guru.siswa.simpan-hasil', $konseling), [
            'hasil_konseling' => 'Siswa berhasil dibimbing terkait perencanaan karir lanjutan.',
            'kesimpulan' => 'Siswa akan mendaftar ke politeknik negeri.',
            'status_perkembangan' => 'membaik',
            'catatan_rahasia' => 'Catatan privat: Siswa memiliki potensi akademis tinggi namun perlu dorongan motivasi keluarga.',
        ]);

        $response->assertRedirect(route('guru.riwayat.index'));
        $response->assertSessionHas('success', 'Hasil konseling berhasil disimpan.');

        // Verify Konseling updated
        $konseling->refresh();
        $this->assertEquals('selesai', $konseling->status);
        $this->assertEquals('Siswa berhasil dibimbing terkait perencanaan karir lanjutan.', $konseling->hasil_konseling);
        $this->assertEquals('Siswa akan mendaftar ke politeknik negeri.', $konseling->kesimpulan);
        $this->assertEquals('Catatan privat: Siswa memiliki potensi akademis tinggi namun perlu dorongan motivasi keluarga.', $konseling->catatan_rahasia);

        // Verify RiwayatPerkembangan created without overwriting
        $this->assertDatabaseHas('riwayat_perkembangans', [
            'siswa_id' => $this->siswa->id,
            'guru_bk_id' => $this->guruBk->id,
            'konseling_id' => $konseling->id,
            'status_perkembangan' => 'membaik',
        ]);
        $this->assertEquals($initialHistoryCount + 1, RiwayatPerkembangan::where('siswa_id', $this->siswa->id)->count());
    }

    public function test_saved_counseling_appears_in_riwayat_index(): void
    {
        $konseling = Konseling::where('siswa_id', $this->siswa->id)->first();
        $this->actingAs($this->guruBk)->post(route('guru.siswa.simpan-hasil', $konseling), [
            'hasil_konseling' => 'Hasil konseling eksplorasi karir.',
            'kesimpulan' => 'Target tercapai dengan baik.',
            'status_perkembangan' => 'membaik',
        ]);

        $response = $this->actingAs($this->guruBk)->get(route('guru.riwayat.index'));
        $response->assertStatus(200);
        $response->assertSee($this->siswa->user->name);
        $response->assertSee($this->siswa->nis);
        $response->assertSee('Membaik');
        $response->assertSee('Detail');
    }

    public function test_detail_rekap_siswa_displays_complete_profile_summary_and_history(): void
    {
        $konseling = Konseling::where('siswa_id', $this->siswa->id)->first();
        $this->actingAs($this->guruBk)->post(route('guru.siswa.simpan-hasil', $konseling), [
            'hasil_konseling' => 'Hasil konseling eksplorasi karir.',
            'kesimpulan' => 'Target tercapai dengan baik.',
            'status_perkembangan' => 'membaik',
            'catatan_rahasia' => 'Catatan rahasia khusus Guru BK Andi.',
        ]);

        $response = $this->actingAs($this->guruBk)->get(route('guru.siswa.show', $this->siswa));
        $response->assertStatus(200);
        $response->assertSee('Detail Rekap Perkembangan Siswa');
        $response->assertSee($this->siswa->user->name);
        $response->assertSee($this->siswa->nis);
        $response->assertSee($this->siswa->nisn);
        $response->assertSee($this->kelas->nama_kelas);
        $response->assertSee('Total Sesi Konseling');
        $response->assertSee('Konseling Terakhir');
        $response->assertSee('Catatan Rahasia Guru BK');
        $response->assertSee('Catatan rahasia khusus Guru BK Andi.');
    }

    public function test_siswa_cannot_see_catatan_rahasia(): void
    {
        $response = $this->actingAs($this->siswaUser)->get(route('siswa.konseling.index'));
        $response->assertStatus(200);
        $response->assertDontSee('catatan_rahasia');
        $response->assertDontSee('Catatan Rahasia Guru BK');
    }
}
