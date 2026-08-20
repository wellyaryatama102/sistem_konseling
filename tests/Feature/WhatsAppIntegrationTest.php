<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Siswa;
use App\Models\JadwalKonseling;
use App\Models\PengajuanKonseling;
use App\Models\Kelas;
use App\Models\SuratPanggilan;
use App\Services\WhatsApp\WhatsAppGatewayInterface;
use App\Services\WhatsApp\FonnteWhatsAppGateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class WhatsAppIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure Fonnte is the active provider during tests
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
        
        // Clean up existing bookings so they can book
        PengajuanKonseling::query()->delete();

        // Ensure Guru BK has profile with no_hp
        $guru = User::where('role', 'guru_bk')->first();
        $guru->guruBkProfile()->create([
            'no_hp' => '08987654321',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Payakumbuh',
            'tanggal_lahir' => '1980-01-01',
            'alamat' => 'Payakumbuh',
            'pendidikan_terakhir' => 'S1'
        ]);

        $slot = JadwalKonseling::where('status_slot', 'tersedia')->first();

        $response = $this->actingAs($siswaUser)->post(route('siswa.jadwal.ajukan', $slot->id), [
            'alasan_pengajuan' => 'Butuh Konseling karir.',
        ]);

        $response->assertRedirect(route('siswa.pengajuan.index'));

        // Assert WA request sent to Guru BK
        Http::assertSent(function ($request) {
            return $request['target'] === '628987654321' // Dynamic phone number from profile
                && str_contains($request['message'], 'Pengajuan Konseling Baru!')
                && str_contains($request['message'], 'Butuh Konseling karir.');
        });

        // Assert WaLog is recorded
        $this->assertDatabaseHas('wa_logs', [
            'penerima_tipe' => 'guru_bk',
            'penerima_nama' => $guru->name,
            'no_wa' => '628987654321',
            'jenis_notifikasi' => 'pengajuan_baru',
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

        $response = $this->actingAs($guruUser)->post(route('guru.pengajuan.validasi', $pengajuan->id), [
            'action' => 'disetujui',
            'catatan_validasi' => 'Silakan datang tepat waktu.',
        ]);

        $response->assertRedirect();

        // Assert WA sent to Siswa
        Http::assertSent(function ($request) use ($siswa) {
            return $request['target'] === '62' . substr($siswa->no_wa_siswa, 1)
                && str_contains($request['message'], 'Pengajuan Konseling Disetujui!')
                && str_contains($request['message'], 'Silakan datang tepat waktu.');
        });
    }

    public function test_siswa_cancellation_notifies_guru_bk()
    {
        $this->seed();
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true], 200),
        ]);

        $siswaUser = User::where('role', 'siswa')->first();
        $pengajuan = PengajuanKonseling::first();
        $siswa = $pengajuan->siswa;
        
        // Match user ID to Siswa User
        $siswa->user_id = $siswaUser->id;
        $siswa->save();

        $guru = $pengajuan->jadwalSlot->guruBk;
        $guru->guruBkProfile()->create([
            'no_hp' => '08987654321',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Payakumbuh',
            'tanggal_lahir' => '1980-01-01',
            'alamat' => 'Payakumbuh',
            'pendidikan_terakhir' => 'S1'
        ]);

        // Mock future slot date to bypass past cancellation check
        $pengajuan->jadwalSlot->tanggal = Carbon::tomorrow()->toDateString();
        $pengajuan->jadwalSlot->save();

        $response = $this->actingAs($siswaUser)->post(route('siswa.pengajuan.batal', $pengajuan->id));

        $response->assertRedirect();

        // Assert WA sent to Guru BK
        Http::assertSent(function ($request) {
            return $request['target'] === '628987654321'
                && str_contains($request['message'], 'Pembatalan Konseling!')
                && str_contains($request['message'], 'Slot jadwal telah dikembalikan menjadi Tersedia.');
        });

        // Check slot status
        $this->assertEquals('tersedia', $pengajuan->jadwalSlot->fresh()->status_slot);
    }

    public function test_scheduler_h1_notifies_siswa_no_duplicates()
    {
        $this->seed();
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true], 200),
        ]);

        $pengajuan = PengajuanKonseling::first();
        $pengajuan->update(['status_validasi' => 'disetujui']);
        
        $slot = $pengajuan->jadwalSlot;
        $slot->update([
            'tanggal' => Carbon::tomorrow()->toDateString(),
            'status_slot' => 'terisi',
        ]);

        // Run reminders command
        $exitCode = Artisan::call('reminders:send');
        $this->assertEquals(0, $exitCode);

        // Assert WA sent
        Http::assertSent(function ($request) use ($pengajuan) {
            return $request['target'] === '62' . substr($pengajuan->siswa->no_wa_siswa, 1)
                && str_contains($request['message'], 'Pengingat Konseling SMKN 2 Guguak')
                && str_contains($request['message'], 'Anda memiliki jadwal konseling besok');
        });

        // Run again
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true], 200),
        ]);

        $exitCode2 = Artisan::call('reminders:send');
        $this->assertEquals(0, $exitCode2);

        // Should NOT send again since it's already logged as sent in wa_logs
        Http::assertNotSent('https://api.fonnte.com/send');
    }

    public function test_guru_bk_sends_calling_letter_notifies_parents()
    {
        $this->seed();
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true], 200),
        ]);

        $guruUser = User::where('role', 'guru_bk')->first();
        $surat = SuratPanggilan::first();
        
        // Make sure the file exists in surat calling record
        $surat->file_path = 'surat_panggilan/mock.pdf';
        $surat->save();

        $response = $this->actingAs($guruUser)->post(route('guru.surat.kirim-wa', $surat->id));

        $response->assertRedirect();

        // Assert WA sent to parents
        Http::assertSent(function ($request) use ($surat) {
            $siswa = $surat->siswa;
            return $request['target'] === '62' . substr($siswa->no_wa_ortu, 1)
                && str_contains($request['message'], 'surat panggilan orang tua/wali')
                && $request['url'] === asset('storage/' . $surat->file_path);
        });

        // Check calling letter status is terkirim
        $this->assertEquals('terkirim', $surat->fresh()->status_pengiriman);
    }
}
