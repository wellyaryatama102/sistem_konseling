<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Siswa;
use App\Models\GuruBk;
use App\Models\JadwalKetersediaan;
use App\Models\PengajuanKonseling;
use App\Models\SesiKonseling;
use App\Models\TindakLanjut;
use App\Models\SuratPanggilan;
use App\Services\WhatsApp\WhatsAppGatewayInterface;
use App\Services\WhatsApp\FonnteWhatsAppGateway;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class WhatsAppIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        config(['services.whatsapp.enabled' => true]);
        config(['services.whatsapp.provider' => 'fonnte']);
        config(['services.fonnte.token' => 'mocked-fonnte-token']);
    }

    public function test_gateway_interface_resolves_to_fonnte_gateway()
    {
        $gateway = app(WhatsAppGatewayInterface::class);
        $this->assertInstanceOf(FonnteWhatsAppGateway::class, $gateway);
    }

    public function test_laravel_to_fonnte_wa_sending()
    {
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true, 'detail' => 'sent'], 200),
        ]);

        $gateway = app(WhatsAppGatewayInterface::class);
        $result = $gateway->sendMessage('6281234567890', 'Hello Test');

        $this->assertTrue($result['success']);
        $this->assertEquals(['status' => true, 'detail' => 'sent'], $result['response']);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.fonnte.com/send'
                && $request->header('Authorization')[0] === 'mocked-fonnte-token'
                && $request['target'] === '6281234567890'
                && $request['message'] === 'Hello Test';
        });
    }

    public function test_siswa_scheduling_notifies_guru_bk()
    {
        $this->seed();
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true], 200),
        ]);

        $siswaUser = User::where('role', 'siswa')->first();
        $siswa = $siswaUser->siswa;
        $siswa->update(['no_wa_orang_tua_wali' => '081298765431']);
        
        PengajuanKonseling::query()->delete();

        $slot = JadwalKetersediaan::where('status_slot', 'tersedia')->first();

        $response = $this->actingAs($siswaUser)->post(route('siswa.jadwal.ajukan', $slot->id_jadwal), [
            'jenis_konseling' => 'individu',
            'alasan_pengajuan' => 'Butuh Konseling karir.',
        ]);

        $response->assertRedirect(route('siswa.pengajuan.index'));

        $this->assertDatabaseHas('wa_logs', [
            'penerima_tipe' => 'guru_bk',
            'status' => 'sent',
        ]);
    }

    public function test_guru_bk_validation_notifies_siswa()
    {
        $this->seed();
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true], 200),
        ]);

        $guruUser = User::where('role', 'guru_bk')->first();
        $pengajuan = PengajuanKonseling::first();
        $siswa = $pengajuan->siswa;
        $siswa->update(['no_wa_siswa' => '081234567891']);

        $response = $this->actingAs($guruUser)->post(route('guru.pengajuan.validasi', $pengajuan->id_pengajuan), [
            'action' => 'disetujui',
            'catatan_validasi' => 'Silakan datang tepat waktu.',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('wa_logs', [
            'penerima_tipe' => 'siswa',
            'status' => 'sent',
        ]);
    }

    public function test_guru_bk_sends_calling_letter_notifies_parents()
    {
        $this->seed();
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true], 200),
        ]);

        $guruUser = User::where('role', 'guru_bk')->first();
        
        $sesi = SesiKonseling::first();
        $tindakLanjut = TindakLanjut::create([
            'id_sesi' => $sesi->id_sesi,
            'jenis_aksi' => 'surat_ortu',
            'status_tindak_lanjut' => 'belum_ditindaklanjuti',
            'catatan' => 'Panggilan Ortu Test',
        ]);

        $guruBk = GuruBk::first();
        $surat = SuratPanggilan::create([
            'id_tindak_lanjut' => $tindakLanjut->id_tindak_lanjut,
            'id_guru_bk' => $guruBk->id_guru_bk,
            'nomor_surat' => '422/999/SMK.N 2-GG/VIII/2026',
            'perihal' => 'Panggilan Orang Tua',
            'isi_surat' => 'Mohon hadir ke sekolah.',
            'tanggal_terbit' => date('Y-m-d'),
            'tanggal_pertemuan' => date('Y-m-d', strtotime('+2 days')),
            'waktu_pertemuan' => '09:00:00',
            'tempat' => 'Ruang BK',
            'status_surat' => 'terbit',
            'status_kirim_wa' => 'pending',
        ]);

        $siswa = $sesi->pengajuan->siswa;
        $siswa->update(['no_wa_orang_tua_wali' => '081298765431']);

        $response = $this->actingAs($guruUser)->post(route('guru.surat.kirim-wa', $surat->id_surat));

        $response->assertRedirect();

        $this->assertEquals('terkirim', $surat->fresh()->status_kirim_wa);
    }
}
