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
}
