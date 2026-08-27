<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WhatsApp\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

/**
 * FUNGSI FILE INI:
 * Mengonfigurasi parameter sistem sekolah, preferensi aplikasi, dan pengujian token Fonnte WA Gateway.
 */
class PengaturanController extends Controller
{
    /**
     * Menampilkan Form Pengaturan Sistem
     */
    public function index()
    {
        $token = config('services.fonnte.token', '');
        $statusGateway = 'Belum Dikonfigurasi';
        $statusType = 'warning';
        $deviceInfo = null;

        if (!empty($token)) {
            try {
                // 1. Cek menggunakan endpoint Device Token (https://api.fonnte.com/device)
                $response = Http::withoutVerifying()->timeout(8)->withHeaders([
                    'Authorization' => $token,
                ])->post('https://api.fonnte.com/device');

                $body = $response->json();

                if ($response->successful() && isset($body['status']) && $body['status'] === true) {
                    $deviceName = $body['name'] ?? ($body['device'] ?? 'Device Aktif');
                    $deviceStatus = $body['device_status'] ?? 'connect';
                    $statusGateway = 'Terhubung (' . $deviceName . ' - ' . ucfirst($deviceStatus) . ')';
                    $statusType = 'success';
                    $deviceInfo = $body;
                } else {
                    // 2. Fallback: Cek menggunakan endpoint Account Token (https://api.fonnte.com/get-devices)
                    $responseAcc = Http::withoutVerifying()->timeout(8)->withHeaders([
                        'Authorization' => $token,
                    ])->post('https://api.fonnte.com/get-devices');

                    $bodyAcc = $responseAcc->json();

                    if ($responseAcc->successful() && isset($bodyAcc['status']) && $bodyAcc['status'] === true) {
                        $device = $bodyAcc['data'][0] ?? null;
                        if ($device) {
                            $statusGateway = 'Terhubung (' . ($device['name'] ?? $device['device'] ?? 'Device Aktif') . ')';
                            $statusType = 'success';
                            $deviceInfo = $device;
                        } else {
                            $statusGateway = 'Terhubung (Akun Fonnte Valid)';
                            $statusType = 'success';
                        }
                    } elseif (isset($body['reason']) && $body['reason'] !== 'unknown user') {
                        $statusGateway = 'Token Tidak Valid (' . $body['reason'] . ')';
                        $statusType = 'danger';
                    } elseif (isset($bodyAcc['reason'])) {
                        $statusGateway = 'Token Tidak Valid (' . $bodyAcc['reason'] . ')';
                        $statusType = 'danger';
                    } else {
                        $statusGateway = 'Gagal Terhubung ke Gateway';
                        $statusType = 'danger';
                    }
                }
            } catch (\Throwable $e) {
                $statusGateway = 'Error Koneksi (' . $e->getMessage() . ')';
                $statusType = 'danger';
            }
        }

        $settings = [
            'nama_sekolah' => config('app.school_name', 'SMK Negeri 2 Guguak'),
            'tahun_ajaran_aktif' => config('app.tahun_ajaran', '2026/2027'),
            'wa_gateway_url' => config('services.fonnte.url', 'https://api.fonnte.com/send'),
            'wa_gateway_token' => $token,
            'status_gateway' => $statusGateway,
            'status_type' => $statusType,
            'device_info' => $deviceInfo,
        ];

        return view('admin.pengaturan.index', compact('settings'));
    }

    /**
     * Menyimpan Pengaturan Sistem & Update .env
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'tahun_ajaran_aktif' => 'required|string|max:20',
            'wa_gateway_url' => 'nullable|url',
            'wa_gateway_token' => 'nullable|string',
        ]);

        if ($request->has('wa_gateway_token')) {
            $newToken = trim($validated['wa_gateway_token'] ?? '');
            $this->updateEnvFile([
                'FONNTE_TOKEN' => $newToken,
                'FONNTE_API_URL' => $validated['wa_gateway_url'] ?? 'https://api.fonnte.com/send',
            ]);
        }

        return back()->with('success', 'Pengaturan sistem & WhatsApp Gateway berhasil diperbarui.');
    }

    /**
     * Tes Kirim Notifikasi WhatsApp ke Nomor Tertentu
     */
    public function testSend(Request $request, WhatsAppNotificationService $waService)
    {
        $request->validate([
            'no_wa_tujuan' => 'required|string|min:9',
            'pesan_tes' => 'nullable|string',
        ]);

        $pesan = $request->pesan_tes ?: "Halo! Ini adalah pesan uji coba integrasi WhatsApp Gateway dari Sistem Informasi Konseling Siswa (SIKS) SMKN 2 Guguak. Notifikasi berhasil terhubung dan aktif.";

        $log = $waService->send('admin_test', auth()->user()->name, $request->no_wa_tujuan, 'test_gateway', $pesan);

        if ($log->status === 'sent') {
            return back()->with('success', "Pesan uji coba berhasil terkirim ke WhatsApp nomor {$request->no_wa_tujuan}!");
        }

        return back()->with('danger', "Pengiriman pesan uji coba gagal: " . ($log->error_message ?: 'Respon error dari gateway.'));
    }

    /**
     * Helper to update .env variables safely
     */
    protected function updateEnvFile(array $data)
    {
        $envPath = base_path('.env');
        if (!File::exists($envPath)) {
            return;
        }

        $envContent = File::get($envPath);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=(.*)$/m";
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        File::put($envPath, $envContent);
    }
}
