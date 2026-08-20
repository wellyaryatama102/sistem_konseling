<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class GuruBkLaporanTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_bk_can_access_laporan_and_downloads(): void
    {
        $this->seed();

        $guruUser = User::where('role', 'guru_bk')->first();

        // 1. Index page (Laporan Pelayanan Konseling)
        $response = $this->actingAs($guruUser)->get(route('guru.laporan.index'));
        $response->assertStatus(200);
        $response->assertSee('Laporan Pelayanan Konseling', false);
        $response->assertSee('Laporan Surat Panggilan Orang Tua', false);

        // 2. Surat Panggilan report tab
        $responseSurat = $this->actingAs($guruUser)->get(route('guru.laporan.index', ['tipe_rekap' => 'surat_panggilan']));
        $responseSurat->assertStatus(200);
        $responseSurat->assertSee('Laporan Rekapitulasi Surat Panggilan Orang Tua', false);

        // 3. PDF download
        $responsePdf = $this->actingAs($guruUser)->get(route('guru.laporan.pdf', ['tipe_rekap' => 'layanan_konseling']));
        $responsePdf->assertStatus(200);

        $responsePdfSurat = $this->actingAs($guruUser)->get(route('guru.laporan.pdf', ['tipe_rekap' => 'surat_panggilan']));
        $responsePdfSurat->assertStatus(200);

        // 4. Excel download
        $responseExcel = $this->actingAs($guruUser)->get(route('guru.laporan.excel', ['tipe_rekap' => 'layanan_konseling']));
        $responseExcel->assertStatus(200);

        $responseExcelSurat = $this->actingAs($guruUser)->get(route('guru.laporan.excel', ['tipe_rekap' => 'surat_panggilan']));
        $responseExcelSurat->assertStatus(200);
    }
}
