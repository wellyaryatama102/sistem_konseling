<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JadwalKonseling;
use App\Services\WhatsApp\WhatsAppNotificationService;
use Carbon\Carbon;

/**
 * FUNGSI FILE INI:
 * Menjalankan otomatisasi pengiriman pesan pengingat (Reminder H-1) via WhatsApp kepada siswa secara berkala.
 */
class SendCounselingReminderCommand extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Kirim notifikasi pengingat H-1 jadwal konseling siswa via WhatsApp';

    public function handle(WhatsAppNotificationService $waService): int
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $jadwals = JadwalKonseling::whereDate('tanggal', $tomorrow)
            ->where('status_slot', 'terisi')
            ->with(['guruBk', 'pengajuan.siswa.user'])
            ->get();

        $count = 0;
        foreach ($jadwals as $jadwal) {
            $pengajuan = $jadwal->pengajuan;
            if (!$pengajuan || $pengajuan->status_validasi !== 'disetujui') {
                continue;
            }

            $siswa = $pengajuan->siswa;
            if (!$siswa || !$siswa->no_wa_siswa) {
                continue;
            }

            // Check if reminder was already sent for this specific date to prevent duplicates
            $dateStr = Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y');
            $formattedPhone = $waService->formatPhoneNumber($siswa->no_wa_siswa);
            $alreadySent = \App\Models\WaLog::where('no_wa', $formattedPhone)
                ->where('jenis_notifikasi', 'pengingat_jadwal')
                ->where('isi_pesan', 'like', "%Tanggal: {$dateStr}%")
                ->where('status', 'sent')
                ->exists();

            if ($alreadySent) {
                continue;
            }

            $message = "Pengingat Konseling SMKN 2 Guguak\n\n"
                . "Halo " . $siswa->user->name . ",\n"
                . "Anda memiliki jadwal konseling besok:\n"
                . "Tanggal: " . $dateStr . "\n"
                . "Waktu: " . substr($jadwal->waktu_mulai, 0, 5) . " - " . substr($jadwal->waktu_selesai, 0, 5) . " WIB\n"
                . "Guru BK: " . ($jadwal->guruBk->name ?? 'Guru BK') . "\n\n"
                . "Mohon hadir tepat waktu sesuai jadwal.";

            $waService->send('siswa', $siswa->user->name, $siswa->no_wa_siswa, 'pengingat_jadwal', $message);
            $count++;
        }

        $this->info("Pengingat H-1 berhasil dikirim untuk {$count} siswa.");
        return 0;
    }
}
