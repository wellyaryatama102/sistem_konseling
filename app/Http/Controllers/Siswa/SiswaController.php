<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\JadwalKetersediaan;
use App\Models\PengajuanKonseling;
use App\Models\SesiKonseling;
use App\Models\Notifikasi;
use App\Services\WhatsApp\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * FUNGSI FILE INI:
 * Menangani akses portal siswa untuk pengajuan jadwal konseling mandiri, memilih slot lanjutan pendampingan ortu, dan memantau sesi.
 */
class SiswaController extends Controller
{
    protected WhatsAppNotificationService $waService;

    public function __construct(WhatsAppNotificationService $waService)
    {
        $this->waService = $waService;
    }

    private function getCurrentSiswa(): Siswa
    {
        $user = auth()->user();
        return Siswa::firstOrCreate(
            ['user_id' => $user->id],
            [
                'username' => $user->username,
                'password' => $user->password,
                'nama_siswa' => $user->name,
                'status_siswa' => 'aktif',
            ]
        );
    }

    /**
     * 1. BERANDA SISWA
     */
   
    private function getPendingTindakLanjutOrtu(Siswa $siswa)
    {
        return \App\Models\TindakLanjut::with(['sesiKonseling.pengajuan.siswa', 'suratPanggilans'])
            ->where('jenis_aksi', 'surat_ortu')
            ->where('status_tindak_lanjut', 'belum_ditindaklanjuti')
            ->whereNull('id_jadwal')
            ->whereHas('sesiKonseling.pengajuan', function ($q) use ($siswa) {
                $q->where('id_siswa', $siswa->id_siswa);
            })
            ->first();
    }

    /**
     * 1. BERANDA SISWA
     */
    public function dashboard()
    {
        $siswa = $this->getCurrentSiswa();
        $today = Carbon::today()->toDateString();

        $stats = [
            'total_pengajuan' => PengajuanKonseling::where('id_siswa', $siswa->id_siswa)->count(),
            'disetujui' => PengajuanKonseling::where('id_siswa', $siswa->id_siswa)->where('status_pengajuan', 'disetujui')->count(),
            'menunggu' => PengajuanKonseling::where('id_siswa', $siswa->id_siswa)->where('status_pengajuan', 'menunggu_validasi')->count(),
        ];

        // Mengambil jadwal terdekat yang telah disetujui
        $jadwalTerdekat = PengajuanKonseling::with(['jadwal.guruBk', 'sesiKonseling'])
            ->where('id_siswa', $siswa->id_siswa)
            ->where('status_pengajuan', 'disetujui')
            ->whereHas('jadwal', function ($q) use ($today) {
                $q->where('tanggal_tersedia', '>=', $today);
            })
            ->first();

        // Notifikasi terbaru untuk siswa
        $notifikasis = Notifikasi::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        // Cek instruksi Tindak Lanjut Pemanggilan Orang Tua & Konseling Lanjutan
        $pendingTindakLanjutOrtu = $this->getPendingTindakLanjutOrtu($siswa);

        return view('siswa.dashboard', compact('siswa', 'stats', 'jadwalTerdekat', 'notifikasis', 'pendingTindakLanjutOrtu'));
    }

    /**
     * 2. PROFIL SISWA
     */
    public function editProfile()
    {
        $siswa = $this->getCurrentSiswa();
        $kelases = Kelas::with('jurusan')->orderBy('nama_kelas')->get();
        return view('siswa.profile', compact('siswa', 'kelases'));
    }

    public function updateProfile(Request $request)
    {
        $siswa = $this->getCurrentSiswa();

        $validated = $request->validate([
            'nis' => 'required|string|max:50|unique:siswa,nis,' . $siswa->id_siswa . ',id_siswa',
            'nisn' => 'required|string|max:50|unique:siswa,nisn,' . $siswa->id_siswa . ',id_siswa',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'agama' => 'nullable|string|max:50',
            'alamat' => 'required|string',
            'no_wa_siswa' => 'required|string|max:20',
            'nama_orang_tua_wali' => 'required|string|max:255',
            'no_wa_orang_tua_wali' => 'required|string|max:20',
            'id_kelas' => 'required|exists:kelas,id_kelas',
        ]);

        $siswa->update($validated);

        return redirect()->route('siswa.dashboard')->with('success', 'Profil siswa dan nomor kontak telah berhasil diperbarui.');
    }

    /**
     * 3. MENU JADWAL KONSELING 
     */
    public function indexJadwalAvailable()
    {
        $siswa = $this->getCurrentSiswa();
        $today = Carbon::today()->toDateString();
        $slots = JadwalKetersediaan::with('guruBk')
            ->where('tanggal_tersedia', '>=', $today)
            ->where('status_slot', 'tersedia')
            ->orderBy('tanggal_tersedia', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->paginate(12);

        $pendingTindakLanjutOrtu = $this->getPendingTindakLanjutOrtu($siswa);

        return view('siswa.jadwal.available', compact('slots', 'pendingTindakLanjutOrtu'));
    }

    /**
     * 4. AJUKAN KONSELING MANDIRI / KONSELING LANJUTAN PENDAMPINGAN ORTU
     */
    public function ajukanJadwal(Request $request, JadwalKetersediaan $slot)
    {
        $siswa = $this->getCurrentSiswa();

        if (!$siswa->no_wa_orang_tua_wali) {
            return back()->withErrors(['wa' => 'Anda wajib melengkapi nomor WhatsApp Orang Tua di profil sebelum mengajukan jadwal konseling.']);
        }

        if ($slot->status_slot !== 'tersedia') {
            return back()->withErrors(['slot' => 'Slot jadwal yang dipilih sudah tidak tersedia. Silakan pilih slot lainnya.']);
        }

        $validated = $request->validate([
            'jenis_konseling' => 'required|in:individu,kelompok,insidental',
            'alasan_pengajuan' => 'required|string|min:5',
            'tindak_lanjut_id' => 'nullable|exists:tindak_lanjut,id_tindak_lanjut',
        ]);

        $pendingTindakLanjutOrtu = null;
        if ($request->filled('tindak_lanjut_id')) {
            $pendingTindakLanjutOrtu = \App\Models\TindakLanjut::find($request->tindak_lanjut_id);
        } else {
            $pendingTindakLanjutOrtu = $this->getPendingTindakLanjutOrtu($siswa);
        }

        // Jika ini adalah pengajuan konseling lanjutan pendampingan orang tua
        if ($pendingTindakLanjutOrtu) {
            $slot->update(['status_slot' => 'terisi']);

            $pengajuan = PengajuanKonseling::create([
                'id_siswa' => $siswa->id_siswa,
                'id_jadwal' => $slot->id_jadwal,
                'jenis_konseling' => $validated['jenis_konseling'] ?? 'individu',
                'alasan_pengajuan' => 'Sesi Konseling Lanjutan (Pendampingan Orang Tua): ' . $validated['alasan_pengajuan'],
                'sumber_pengajuan' => 'guru_bk',
                'status_pengajuan' => 'disetujui',
                'tanggal_pengajuan' => Carbon::now(),
                'catatan_validasi' => 'Sesi konseling lanjutan pendampingan orang tua dijadwalkan oleh siswa.',
            ]);

            SesiKonseling::create([
                'id_pengajuan' => $pengajuan->id_pengajuan,
                'status_sesi' => 'terjadwal',
                'tanggal_pelaksanaan' => $slot->tanggal_tersedia,
                'status_kehadiran' => 'menunggu',
                'catatan_untuk_siswa' => 'Sesi Konseling Lanjutan Pendampingan Orang Tua. Mohon hadir tepat waktu bersama Orang Tua/Wali di ruang BK.',
            ]);

            $pendingTindakLanjutOrtu->update([
                'id_jadwal' => $slot->id_jadwal,
                'status_tindak_lanjut' => 'terjadwal',
                'catatan' => ($pendingTindakLanjutOrtu->catatan ?? '') . ' | Slot lanjutan terpilih: ' . Carbon::parse($slot->tanggal_tersedia)->format('d-m-Y') . ' (' . substr($slot->jam_mulai, 0, 5) . ' WIB)',
            ]);

            // Sinkronisasi tanggal & waktu pertemuan jika Surat Panggilan sudah pernah dibuat
            foreach ($pendingTindakLanjutOrtu->suratPanggilans as $surat) {
                $surat->update([
                    'tanggal_pertemuan' => $slot->tanggal_tersedia,
                    'waktu_pertemuan' => $slot->jam_mulai,
                ]);
            }
            
            // PENGIRIMAN NOTIFIKASI WHATSAPP KE GURU BK (SISWA MEMILIH JADWAL LANJUTAN ORTUA)
            $guru = $slot->guruBk;
            if ($guru && $guru->no_hp) {
                $msg = "Jadwal Konseling Lanjutan Pendampingan Ortua Dipilih!\n\n"
                    . "Siswa: " . $siswa->nama_siswa . " (" . ($siswa->kelas->nama_kelas ?? '-') . ")\n"
                    . "Telah memilih slot konseling lanjutan pendampingan orang tua:\n"
                    . "Tanggal: " . Carbon::parse($slot->tanggal_tersedia)->format('d-m-Y') . "\n"
                    . "Jam: " . substr($slot->jam_mulai, 0, 5) . " WIB\n\n"
                    . "Jadwal telah otomatis terisi dan terkonfirmasi pada sistem.";
                $this->waService->send('guru_bk', $guru->nama_lengkap, $guru->no_hp, 'persetujuan', $msg);
            }

            return redirect()->route('siswa.pengajuan.index')->with('success', 'Jadwal Konseling Lanjutan Pendampingan Orang Tua berhasil ditetapkan untuk tanggal ' . Carbon::parse($slot->tanggal_tersedia)->format('d F Y') . '.');
        }

        // Cek pengajuan aktif biasa
        $hasActive = PengajuanKonseling::where('id_siswa', $siswa->id_siswa)
            ->whereIn('status_pengajuan', ['menunggu_validasi', 'disetujui'])
            ->whereHas('jadwal', function ($q) {
                $q->where('tanggal_tersedia', '>=', Carbon::today()->toDateString());
            })->exists();

        if ($hasActive) {
            return back()->withErrors(['pengajuan' => 'Anda masih memiliki jadwal pengajuan konseling yang sedang aktif atau menunggu konfirmasi.']);
        }

        $pengajuan = PengajuanKonseling::create([
            'id_siswa' => $siswa->id_siswa,
            'id_jadwal' => $slot->id_jadwal,
            'jenis_konseling' => $validated['jenis_konseling'],
            'alasan_pengajuan' => $validated['alasan_pengajuan'],
            'sumber_pengajuan' => 'siswa',
            'status_pengajuan' => 'menunggu_validasi',
            'tanggal_pengajuan' => Carbon::now(),
        ]);

        // =========================================================================
        // PENGIRIMAN NOTIFIKASI WHATSAPP KE GURU BK (PENGAJUAN KONSELING MANDIRI SISWA)
        // =========================================================================
        $guru = $slot->guruBk;
        if ($guru && $guru->no_hp) {
            $msg = "Pengajuan Konseling Baru Masuk!\n\n"
                . "Terdapat pengajuan jadwal konseling baru dari siswa: " . $siswa->nama_siswa . " (" . ($siswa->kelas->nama_kelas ?? '-') . ")\n"
                . "Tanggal: " . Carbon::parse($slot->tanggal_tersedia)->format('d-m-Y') . "\n"
                . "Waktu: " . substr($slot->jam_mulai, 0, 5) . " WIB\n"
                . "Jenis Layanan: " . ucfirst($validated['jenis_konseling']) . "\n"
                . "Alasan: " . $validated['alasan_pengajuan'] . "\n\n"
                . "Silakan buka aplikasi SIKS untuk memeriksa dan memvalidasi pengajuan ini.";

            $this->waService->send('guru_bk', $guru->nama_lengkap, $guru->no_hp, 'pengajuan_baru', $msg);
        }

        return redirect()->route('siswa.pengajuan.index')->with('success', 'Pengajuan jadwal konseling berhasil dikirim. Menunggu validasi dari Guru BK.');
    }

    /**
     * 5. DAFTAR & STATUS PENGAJUAN SAYA 
     */
    public function indexPengajuanSaya()
    {
        $siswa = $this->getCurrentSiswa();
        $pengajuans = PengajuanKonseling::with(['jadwal.guruBk', 'sesiKonseling'])
            ->where('id_siswa', $siswa->id_siswa)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('siswa.pengajuan.index', compact('pengajuans', 'siswa'));
    }

    /**
     * 6. PEMBATALAN PENGAJUAN / JADWAL 
     */
    public function batalkanPengajuan(PengajuanKonseling $pengajuan)
    {
        $siswa = $this->getCurrentSiswa();

        if ($pengajuan->id_siswa !== $siswa->id_siswa) {
            abort(403);
        }

        if ($pengajuan->jadwal && Carbon::parse($pengajuan->jadwal->tanggal_tersedia)->isPast()) {
            return back()->withErrors(['batal' => 'Jadwal sesi konseling yang sudah lewat tanggalnya tidak dapat dibatalkan.']);
        }

        $pengajuan->update([
            'status_pengajuan' => 'dibatalkan',
            'tanggal_pembatalan' => Carbon::now(),
        ]);

        if ($pengajuan->jadwal) {
            $pengajuan->jadwal->update(['status_slot' => 'tersedia']);
        }

        // Notifikasi WA ke Guru BK mengenai pembatalan
        $slot = $pengajuan->jadwal;
        $guru = $slot ? $slot->guruBk : null;
        if ($guru && $guru->no_hp) {
            $msg = "Pemberitahuan Pembatalan Konseling!\n\n"
                . "Siswa " . $siswa->nama_siswa . " telah membatalkan pengajuan jadwal konseling.\n"
                . "Tanggal: " . ($slot ? Carbon::parse($slot->tanggal_tersedia)->format('d-m-Y') : '-') . "\n"
                . "Waktu: " . ($slot ? substr($slot->jam_mulai, 0, 5) . " WIB" : '-') . "\n\n"
                . "Slot ketersediaan jadwal telah otomatis dikembalikan menjadi TERSEDIA.";

            $this->waService->send('guru_bk', $guru->nama_lengkap, $guru->no_hp, 'pembatalan_jadwal', $msg);
        }

        return back()->with('success', 'Pengajuan konseling telah berhasil dibatalkan.');
    }

    /**
     * 7. RIWAYAT & ARAHAN HASIL KONSELING 
     */
    public function indexHasilKonseling()
    {
        $siswa = $this->getCurrentSiswa();

        // Hanya mengambil field umum dan arahan siswa (catatan_rahasia & internal BK dilindungi)
        $sesiList = SesiKonseling::query()
            ->select('id_sesi', 'id_pengajuan', 'status_sesi', 'tanggal_pelaksanaan', 'status_kehadiran', 'catatan_untuk_siswa')
            ->whereHas('pengajuan', function ($q) use ($siswa) {
                $q->where('id_siswa', $siswa->id_siswa);
            })
            ->with(['pengajuan.jadwal.guruBk'])
            ->orderBy('tanggal_pelaksanaan', 'desc')
            ->paginate(10);

        return view('siswa.konseling.index', compact('sesiList', 'siswa'));
    }
}
