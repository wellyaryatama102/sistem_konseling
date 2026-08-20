<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AdminLaporanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_all_report_tabs(): void
    {
        $this->seed();
        $admin = User::where('role', 'admin')->first();

        // 1. Rekap Layanan Konseling (Lengkap)
        $res1 = $this->actingAs($admin)->get(route('admin.laporan.index', ['tipe_rekap' => 'layanan_konseling']));
        $res1->assertStatus(200);
        $res1->assertSee('Laporan Rekapitulasi Pelayanan Bimbingan &amp; Konseling Lengkap', false);

        // 2. Rekap Data Kelas & Siswa (Lengkap)
        $res2 = $this->actingAs($admin)->get(route('admin.laporan.index', ['tipe_rekap' => 'siswa_kelas']));
        $res2->assertStatus(200);
        $res2->assertSee('Laporan Data Rombongan Belajar &amp; Siswa Per Kelas', false);
    }

    public function test_admin_can_download_pdf_and_excel_reports(): void
    {
        $this->seed();
        $admin = User::where('role', 'admin')->first();

        $pdfRes = $this->actingAs($admin)->get(route('admin.laporan.pdf', ['tipe_rekap' => 'layanan_konseling']));
        $pdfRes->assertStatus(200);
        $pdfRes->assertHeader('content-type', 'application/pdf');

        $excelRes = $this->actingAs($admin)->get(route('admin.laporan.excel', ['tipe_rekap' => 'siswa_kelas']));
        $excelRes->assertStatus(200);
    }
}
