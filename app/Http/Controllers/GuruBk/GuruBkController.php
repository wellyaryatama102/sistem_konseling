<?php

namespace App\Http\Controllers\GuruBk;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Jurusan;
use App\Models\GuruBk;
use App\Models\Siswa;
use App\Models\JadwalKetersediaan;
use App\Models\PengajuanKonseling;
use App\Models\SesiKonseling;
use App\Models\TindakLanjut;
use App\Models\SuratPanggilan;
use App\Models\Notifikasi;
use App\Models\WaLog;
use App\Models\Kepsek;
use App\Services\WhatsApp\WhatsAppNotificationService;
use App\Exports\GuruBkLaporanExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * FUNGSI FILE INI:
 * Menangani operasional layanan Bimbingan Konseling Guru BK (jadwal slot, validasi pengajuan, hasil konseling, tindak lanjut, & surat panggilan).
 */
class GuruBkController extends Controller
{
    protected WhatsAppNotificationService $waService;

    public function __construct(WhatsAppNotificationService $waService)
    {
        $this->waService = $waService;
    }

    /**
     * Helper to get current GuruBK model instance.
     */
    private function getCurrentGuruBk(): GuruBk
    {
        $user = auth()->user();
        return GuruBk::firstOrCreate(
            ['user_id' => $user->id],
            [
                'username' => $user->username,
                'password' => $user->password,
                'nama_lengkap' => $user->name,
                'email' => $user->email,
            ]
        );
    }

    /**
     * DASHBOARD GURU BK
     */
    public function dashboard()
    {
        $guru = $this->getCurrentGuruBk();
        $today = Carbon::today()->toDateString();

        // Mengambil statistik konseling riil dari database
        $stats = [
            'total_siswa' => Siswa::where('status_siswa', 'aktif')->count(),
            'pengajuan_menunggu' => PengajuanKonseling::where('status_pengajuan', 'menunggu_validasi')->count(),
            'jadwal_hari_ini' => JadwalKetersediaan::where('id_guru_bk', $guru->id_guru_bk)
                ->where('tanggal_tersedia', $today)
                ->count(),
            'konseling_berlangsung' => SesiKonseling::whereHas('pengajuan', function ($q) use ($guru) {
                $q->whereHas('jadwal', function ($j) use ($guru) {
                    $j->where('id_guru_bk', $guru->id_guru_bk);
                })->orWhere('id_siswa', '>', 0);
            })->where('status_sesi', 'terjadwal')->count(),
            'konseling_selesai' => SesiKonseling::where('status_sesi', 'selesai')->count(),
            'tindak_lanjut' => TindakLanjut::where('status_tindak_lanjut', 'belum_ditindaklanjuti')->count(),
        ];

        // Mengambil sesi konseling terdekat
        $jadwalTerdekat = SesiKonseling::with(['pengajuan.siswa.kelas', 'pengajuan.jadwal'])
            ->where('tanggal_pelaksanaan', '>=', $today)
            ->orderBy('tanggal_pelaksanaan', 'asc')
            ->take(5)
            ->get();

        // Mengambil pengajuan konseling terbaru
        $recentPengajuan = PengajuanKonseling::with(['siswa.kelas', 'jadwal'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('guru.dashboard', compact('stats', 'jadwalTerdekat', 'recentPengajuan', 'guru'));
    }

    /**
     * PENGAJUAN KONSELING
     */
    public function indexPengajuan(Request $request)
    {
        $query = PengajuanKonseling::with(['siswa.kelas', 'jadwal.guruBk', 'waliKelas', 'sesiKonseling']);

        if ($request->filled('status')) {
            $query->where('status_pengajuan', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $pengajuans = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('guru.pengajuan.index', compact('pengajuans'));
    }

    /**
     * Validasi Pengajuan Konseling Siswa / Rujukan Walas (Activity Diagram Guru BK)
     */
    public function validasiPengajuan(Request $request, PengajuanKonseling $pengajuan)
    {
        $request->validate([
            'action' => 'required|in:disetujui,ditolak',
            'catatan_validasi' => 'nullable|string',
        ]);

        $guru = $this->getCurrentGuruBk();
        $isDisetujui = ($request->action === 'disetujui');
        $status = $isDisetujui ? 'disetujui' : 'ditolak';

        $pengajuan->update([
            'status_pengajuan' => $status,
            'catatan_validasi' => $request->catatan_validasi,
        ]);

        $slot = $pengajuan->jadwal;
        $siswa = $pengajuan->siswa;

        if ($isDisetujui) {
            // Update slot menjadi terisi
            if ($slot) {
                $slot->update(['status_slot' => 'terisi']);
            }

            // Membentuk sesi konseling baru (1:1 dengan pengajuan)
            SesiKonseling::firstOrCreate(
                ['id_pengajuan' => $pengajuan->id_pengajuan],
                [
                    'status_sesi' => 'terjadwal',
                    'tanggal_pelaksanaan' => $slot ? $slot->tanggal_tersedia : Carbon::today()->toDateString(),
                    'status_kehadiran' => 'menunggu',
                    'catatan_untuk_siswa' => $request->catatan_validasi ?? 'Silakan hadir tepat waktu di ruang BK.',
                ]
            );
            // PENGIRIMAN NOTIFIKASI WHATSAPP KE SISWA (PENGAJUAN DISETUJUI)
            if ($siswa && $siswa->no_wa_siswa) {
                $tglStr = $slot ? Carbon::parse($slot->tanggal_tersedia)->format('d-m-Y') : date('d-m-Y');
                $jamStr = $slot ? substr($slot->jam_mulai, 0, 5) : '-';
                $msg = "Pengajuan Konseling Disetujui!\n\n"
                    . "Halo " . $siswa->nama_siswa . ",\n"
                    . "Pengajuan jadwal konseling Anda pada tanggal " . $tglStr . " jam " . $jamStr . " WIB telah DISETUJUI oleh Guru BK.\n\n"
                    . "Catatan BK: " . ($request->catatan_validasi ?? 'Mohon hadir tepat waktu di ruang BK.');
                
                $this->waService->send('siswa', $siswa->nama_siswa, $siswa->no_wa_siswa, 'persetujuan', $msg);
            }

            // PENGIRIMAN NOTIFIKASI SISTEM (DATABASE) KE AKUN SISWA
            if ($siswa && $siswa->user_id) {
                Notifikasi::create([
                    'user_id' => $siswa->user_id,
                    'judul_notifikasi' => 'Pengajuan Konseling Disetujui',
                    'jenis_notifikasi' => 'persetujuan',
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'id_jadwal' => $slot ? $slot->id_jadwal : null,
                    'tipe_penerima' => 'siswa',
                    'isi_pesan' => 'Pengajuan konseling Anda telah disetujui Guru BK.',
                    'no_wa_tujuan' => $siswa->no_wa_siswa,
                    'status_kirim' => 'sent',
                    'tanggal_kirim' => Carbon::now(),
                ]);
            }

            $flashMsg = 'Pengajuan konseling berhasil disetujui.';
        } else {
            // Jika ditolak, slot kembali tersedia
            if ($slot) {
                $slot->update(['status_slot' => 'tersedia']);
            }

            if ($siswa && $siswa->no_wa_siswa) {
                $msg = "Pengajuan Konseling Belum Dapat Disetujui\n\n"
                    . "Halo " . $siswa->nama_siswa . ",\n"
                    . "Pengajuan konseling Anda belum disetujui.\n"
                    . "Catatan BK: " . ($request->catatan_validasi ?? 'Silakan ajukan jadwal lainnya.');
                
                $this->waService->send('siswa', $siswa->nama_siswa, $siswa->no_wa_siswa, 'penolakan', $msg);
            }

            if ($siswa && $siswa->user_id) {
                Notifikasi::create([
                    'user_id' => $siswa->user_id,
                    'judul_notifikasi' => 'Pengajuan Konseling Belum Disetujui',
                    'jenis_notifikasi' => 'penolakan',
                    'id_pengajuan' => $pengajuan->id_pengajuan,
                    'id_jadwal' => $slot ? $slot->id_jadwal : null,
                    'tipe_penerima' => 'siswa',
                    'isi_pesan' => 'Pengajuan konseling Anda belum disetujui Guru BK. Catatan: ' . ($request->catatan_validasi ?? '-'),
                    'no_wa_tujuan' => $siswa->no_wa_siswa,
                    'status_kirim' => 'sent',
                    'tanggal_kirim' => Carbon::now(),
                ]);
            }

            $flashMsg = 'Pengajuan konseling berhasil ditolak.';
        }

        return back()->with('success', $flashMsg);
    }

    /**
     * JADWAL & AGENDA KONSELING TERPADU (Tab Agenda Sesi & Tab Slot Ketersediaan)
     */
    public function indexJadwal(Request $request)
    {
        $guru = $this->getCurrentGuruBk();
        
        // 1. Data Agenda Konseling Terjadwal
        $query = SesiKonseling::with(['pengajuan.siswa.kelas', 'pengajuan.jadwal.guruBk']);

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_pelaksanaan', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status_sesi', $request->status);
        }

        if ($request->filled('kelas_id')) {
            $query->whereHas('pengajuan.siswa', function ($q) use ($request) {
                $q->where('id_kelas', $request->kelas_id);
            });
        }

        $jadwals = $query->orderBy('tanggal_pelaksanaan', 'desc')->paginate(15, ['*'], 'page_agenda')->withQueryString();
        $kelases = Kelas::orderBy('nama_kelas')->get();

        // 2. Data Slot Ketersediaan Guru BK
        $queryKetersediaan = JadwalKetersediaan::where('id_guru_bk', $guru->id_guru_bk);

        if ($request->filled('tanggal_slot')) {
            $queryKetersediaan->where('tanggal_tersedia', $request->tanggal_slot);
        }

        if ($request->filled('status_slot')) {
            $queryKetersediaan->where('status_slot', $request->status_slot);
        }

        $ketersediaans = $queryKetersediaan->orderBy('tanggal_tersedia', 'desc')
            ->orderBy('jam_mulai', 'asc')
            ->paginate(15, ['*'], 'page_slot')
            ->withQueryString();

        return view('guru.jadwal.index', compact('jadwals', 'ketersediaans', 'kelases', 'guru'));
    }

    /**
     * KETERSEDIAAN JADWAL (Redirect to unified Jadwal menu)
     */
    public function indexKetersediaan(Request $request)
    {
        return redirect()->route('guru.jadwal.index', array_merge(['tab' => 'ketersediaan'], $request->all()));
    }

    public function storeKetersediaan(Request $request)
    {
        $guru = $this->getCurrentGuruBk();

        $validated = $request->validate([
            'tanggal_tersedia' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        JadwalKetersediaan::create([
            'id_guru_bk' => $guru->id_guru_bk,
            'tanggal_tersedia' => $validated['tanggal_tersedia'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'status_slot' => 'tersedia',
        ]);

        return back()->with('success', 'Slot jadwal ketersediaan berhasil ditambahkan.');
    }

    public function destroyKetersediaan(JadwalKetersediaan $jadwal)
    {
        $guru = $this->getCurrentGuruBk();
        if ($jadwal->id_guru_bk !== $guru->id_guru_bk) {
            abort(403);
        }

        if ($jadwal->status_slot === 'terisi') {
            return back()->withErrors(['jadwal' => 'Slot jadwal yang sudah terisi pengajuan tidak dapat dihapus langsung.']);
        }

        $jadwal->delete();

        return back()->with('success', 'Slot ketersediaan berhasil dihapus.');
    }

    /**
     * LAYANAN KONSELING & SESI
     */
    public function indexLayanan(Request $request)
    {
        $query = SesiKonseling::with(['pengajuan.siswa.kelas', 'pengajuan.jadwal']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pengajuan.siswa', function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status_sesi', $request->status);
        }

        $layananList = $query->orderBy('tanggal_pelaksanaan', 'desc')->paginate(15)->withQueryString();
        $siswas = Siswa::with('kelas')->where('status_siswa', 'aktif')->orderBy('nama_siswa')->get();

        return view('guru.layanan.index', compact('layananList', 'siswas'));
    }

    /**
     * Input Hasil Konseling & Kehadira
     */
    public function inputHasil(SesiKonseling $konseling)
    {
        $konseling->load(['pengajuan.siswa.kelas', 'pengajuan.jadwal', 'tindakLanjuts']);
        $slotsTersedia = JadwalKetersediaan::where('status_slot', 'tersedia')
            ->where('tanggal_tersedia', '>=', Carbon::today()->toDateString())
            ->orderBy('tanggal_tersedia')
            ->orderBy('jam_mulai')
            ->get();

        return view('guru.siswa.input_hasil', compact('konseling', 'slotsTersedia'));
    }

    /**
     * Simpan Hasil Konseling & Tindak Lanjut
     */
    public function simpanHasil(Request $request, SesiKonseling $konseling)
    {
        $validated = $request->validate([
            'status_kehadiran' => 'required|in:hadir,tidak_hadir',
            'hasil_konseling' => 'required|string',
            'rencana_tindak_lanjut' => 'nullable|string',
            'catatan_untuk_siswa' => 'nullable|string',
            'catatan_rahasia' => 'nullable|string',
            'opsi_tindak_lanjut' => 'required|in:selesai,sesi_lanjutan,surat_ortu',
            'id_jadwal_lanjutan' => 'nullable|exists:jadwal_ketersediaan,id_jadwal',
            'catatan_tindak_lanjut' => 'nullable|string',
        ]);

        $konseling->update([
            'status_kehadiran' => $validated['status_kehadiran'],
            'hasil_konseling' => $validated['hasil_konseling'],
            'rencana_tindak_lanjut' => $validated['rencana_tindak_lanjut'] ?? null,
            'catatan_untuk_siswa' => $validated['catatan_untuk_siswa'] ?? null,
            'catatan_rahasia' => $validated['catatan_rahasia'] ?? null,
            'status_sesi' => 'selesai',
        ]);

        $opsi = $validated['opsi_tindak_lanjut'];

        if ($opsi === 'selesai') {
            // Selesai tanpa aksi tambahan
            TindakLanjut::create([
                'id_sesi' => $konseling->id_sesi,
                'id_jadwal' => null,
                'jenis_aksi' => 'selesai',
                'status_tindak_lanjut' => 'selesai',
                'catatan' => $validated['catatan_tindak_lanjut'] ?? 'Konseling selesai tuntas.',
            ]);
        } elseif ($opsi === 'sesi_lanjutan') {
            // Mengatur jadwal sesi lanjutan
            $idSlot = $validated['id_jadwal_lanjutan'] ?? null;
            if ($idSlot) {
                $slotBaru = JadwalKetersediaan::find($idSlot);
                if ($slotBaru) {
                    $slotBaru->update(['status_slot' => 'terisi']);

                    // Buat pengajuan baru untuk sesi lanjutan
                    $pengajuanBaru = PengajuanKonseling::create([
                        'id_siswa' => $konseling->pengajuan->id_siswa,
                        'id_jadwal' => $slotBaru->id_jadwal,
                        'jenis_konseling' => $konseling->pengajuan->jenis_konseling,
                        'alasan_pengajuan' => 'Sesi konseling lanjutan: ' . ($validated['catatan_tindak_lanjut'] ?? '-'),
                        'sumber_pengajuan' => 'guru_bk',
                        'status_pengajuan' => 'disetujui',
                        'tanggal_pengajuan' => Carbon::now(),
                        'catatan_validasi' => 'Sesi lanjutan dijadwalkan oleh Guru BK.',
                    ]);

                    SesiKonseling::create([
                        'id_pengajuan' => $pengajuanBaru->id_pengajuan,
                        'status_sesi' => 'terjadwal',
                        'tanggal_pelaksanaan' => $slotBaru->tanggal_tersedia,
                        'status_kehadiran' => 'menunggu',
                        'catatan_untuk_siswa' => 'Harap hadir di sesi konseling lanjutan.',
                    ]);

                    TindakLanjut::create([
                        'id_sesi' => $konseling->id_sesi,
                        'id_jadwal' => $slotBaru->id_jadwal,
                        'jenis_aksi' => 'sesi_lanjutan',
                        'status_tindak_lanjut' => 'terjadwal',
                        'catatan' => $validated['catatan_tindak_lanjut'] ?? 'Jadwal sesi lanjutan telah diagendakan.',
                    ]);

                    // Notifikasi WA ke siswa
                    $siswa = $konseling->pengajuan->siswa;
                    if ($siswa && $siswa->no_wa_siswa) {
                        $msg = "Jadwal Konseling Lanjutan Ditetapkan!\n\n"
                            . "Halo " . $siswa->nama_siswa . ",\n"
                            . "Guru BK telah menjadwalkan sesi konseling lanjutan pada tanggal " . Carbon::parse($slotBaru->tanggal_tersedia)->format('d-m-Y') . " jam " . substr($slotBaru->jam_mulai, 0, 5) . " WIB.\n\n"
                            . "Mohon hadir tepat waktu.";
                        $this->waService->send('siswa', $siswa->nama_siswa, $siswa->no_wa_siswa, 'persetujuan', $msg);
                    }
                }
            }
        } elseif ($opsi === 'surat_ortu') {
            // Menerbitkan tindak lanjut surat orang tua & konseling lanjutan pendampingan ortua
            $tindakLanjut = TindakLanjut::create([
                'id_sesi' => $konseling->id_sesi,
                'id_jadwal' => null,
                'jenis_aksi' => 'surat_ortu',
                'status_tindak_lanjut' => 'belum_ditindaklanjuti',
                'catatan' => $validated['catatan_tindak_lanjut'] ?? 'Pemanggilan Orang Tua & Konseling Lanjutan Pendampingan Orang Tua.',
            ]);

            // Mengirim Notifikasi Sistem & WA ke Siswa untuk memilih jadwal konseling lanjutan bersama Orang Tua
            $siswa = $konseling->pengajuan->siswa ?? null;
            if ($siswa) {
                if ($siswa->no_wa_siswa) {
                    $msg = "INSTUKSI KONSELING LANJUTAN PENDAMPINGAN ORANG TUA!\n\n"
                        . "Halo " . $siswa->nama_siswa . ",\n"
                        . "Guru BK telah menetapkan tindak lanjut Pemanggilan Orang Tua & Konseling Lanjutan.\n"
                        . "Silakan login ke aplikasi SIKS untuk memilih slot jadwal konseling lanjutan yang didampingi oleh Orang Tua/Wali Anda.\n\n"
                        . "Catatan BK: " . ($validated['catatan_tindak_lanjut'] ?? 'Perlu konseling lanjutan pendampingan orang tua.');
                    $this->waService->send('siswa', $siswa->nama_siswa, $siswa->no_wa_siswa, 'persetujuan', $msg);
                }

                if ($siswa->user_id) {
                    Notifikasi::create([
                        'user_id' => $siswa->user_id,
                        'judul_notifikasi' => 'Pilih Jadwal Konseling Lanjutan Pendampingan Orang Tua',
                        'jenis_notifikasi' => 'surat_panggilan',
                        'id_pengajuan' => $konseling->id_pengajuan,
                        'tipe_penerima' => 'siswa',
                        'isi_pesan' => 'Guru BK menetapkan Pemanggilan Orang Tua. Silakan pilih slot jadwal konseling lanjutan pendampingan orang tua Anda.',
                        'status_kirim' => 'sent',
                        'tanggal_kirim' => Carbon::now(),
                    ]);
                }
            }

            return redirect()->route('guru.surat.create', ['tindak_lanjut_id' => $tindakLanjut->id_tindak_lanjut])
                ->with('success', 'Catatan hasil konseling berhasil disimpan. Siswa telah dinotifikasi untuk memilih jadwal konseling lanjutan pendampingan orang tua.');
        }

        return redirect()->route('guru.layanan.index')->with('success', 'Hasil konseling dan tindak lanjut berhasil disimpan.');
    }

    /**
     *RIWAYAT SISWA TERPADU (Tab Direktori Siswa & Log Riwayat Konseling)
     */
    public function indexSiswa(Request $request)
    {
        $activeTab = $request->get('tab', 'siswa'); // 'siswa' or 'riwayat'
        $kelases = Kelas::orderBy('nama_kelas')->get();

        // 1. Data Direktori Siswa
        $querySiswa = Siswa::with(['kelas.jurusan', 'kelas.waliKelas'])
            ->withCount([
                'pengajuanKonselings as total_pengajuan',
                'pengajuanKonselings as total_sesi_selesai' => function ($q) {
                    $q->whereHas('sesiKonseling', function ($sq) {
                        $sq->where('status_sesi', 'selesai');
                    });
                }
            ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $querySiswa->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kelas_id') || $request->filled('id_kelas')) {
            $idKelas = $request->kelas_id ?? $request->id_kelas;
            $querySiswa->where('id_kelas', $idKelas);
        }

        $siswas = $querySiswa->orderBy('nama_siswa')->paginate(15, ['*'], 'page_siswa')->withQueryString();

        // Riwayat Konseling Global
        $queryRiwayat = SesiKonseling::with([
            'pengajuan.siswa.kelas.jurusan',
            'pengajuan.jadwal.guruBk',
            'tindakLanjuts.suratPanggilans'
        ]);

        if ($request->filled('search_riwayat')) {
            $searchR = $request->search_riwayat;
            $queryRiwayat->whereHas('pengajuan.siswa', function ($q) use ($searchR) {
                $q->where('nama_siswa', 'like', "%{$searchR}%")
                  ->orWhere('nis', 'like', "%{$searchR}%")
                  ->orWhere('nisn', 'like', "%{$searchR}%");
            });
        }

        if ($request->filled('kelas_riwayat')) {
            $queryRiwayat->whereHas('pengajuan.siswa', function ($q) use ($request) {
                $q->where('id_kelas', $request->kelas_riwayat);
            });
        }

        if ($request->filled('tanggal_riwayat')) {
            $queryRiwayat->whereDate('tanggal_pelaksanaan', $request->tanggal_riwayat);
        }

        if ($request->filled('status_riwayat')) {
            $queryRiwayat->where('status_sesi', $request->status_riwayat);
        }

        $riwayats = $queryRiwayat->orderBy('tanggal_pelaksanaan', 'desc')->paginate(15, ['*'], 'page_riwayat')->withQueryString();

        return view('guru.siswa.index', compact('siswas', 'riwayats', 'kelases', 'activeTab'));
    }

    public function showSiswa(Siswa $siswa)
    {
        $siswa->load(['kelas.waliKelas', 'kelas.jurusan']);
        $riwayatPengajuan = PengajuanKonseling::with(['jadwal', 'sesiKonseling.tindakLanjuts.suratPanggilans'])
            ->where('id_siswa', $siswa->id_siswa)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guru.siswa.show', compact('siswa', 'riwayatPengajuan'));
    }

    /**
     * TINDAK LANJUT & SURAT PANGGILAN
     */
    public function indexTindakLanjut(Request $request)
    {
        // 1. Data Surat Panggilan Orang Tua
        $querySurat = SuratPanggilan::with(['tindakLanjut.sesiKonseling.pengajuan.siswa.kelas', 'guruBk']);

        if ($request->filled('search_surat')) {
            $search = $request->search_surat;
            $querySurat->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhereHas('tindakLanjut.sesiKonseling.pengajuan.siswa', function ($s) use ($search) {
                      $s->where('nama_siswa', 'like', "%{$search}%");
                  });
            });
        }

        $surats = $querySurat->orderBy('tanggal_terbit', 'desc')->paginate(15, ['*'], 'page_surat')->withQueryString();

        // 2. Data Rencana Tindak Lanjut (RTL)
        $queryTL = TindakLanjut::with(['sesiKonseling.pengajuan.siswa.kelas', 'jadwal', 'suratPanggilans']);

        if ($request->filled('status')) {
            $queryTL->where('status_tindak_lanjut', $request->status);
        }

        if ($request->filled('jenis_aksi')) {
            $queryTL->where('jenis_aksi', $request->jenis_aksi);
        }

        $tindakLanjuts = $queryTL->orderBy('created_at', 'desc')->paginate(15, ['*'], 'page_tl')->withQueryString();

        return view('guru.tindak_lanjut.index', compact('surats', 'tindakLanjuts'));
    }

    public function storeTindakLanjut(Request $request)
    {
        $validated = $request->validate([
            'id_sesi' => 'required|exists:sesi_konseling,id_sesi',
            'jenis_aksi' => 'required|in:selesai,sesi_lanjutan,surat_ortu',
            'id_jadwal' => 'nullable|exists:jadwal_ketersediaan,id_jadwal',
            'catatan' => 'nullable|string',
        ]);

        TindakLanjut::create($validated);

        return back()->with('success', 'Data tindak lanjut berhasil ditambahkan.');
    }

    public function updateTindakLanjut(Request $request, TindakLanjut $tindakLanjut)
    {
        $validated = $request->validate([
            'status_tindak_lanjut' => 'required|in:belum_ditindaklanjuti,terjadwal,selesai',
            'catatan' => 'nullable|string',
        ]);

        $tindakLanjut->update($validated);

        return back()->with('success', 'Status tindak lanjut berhasil diperbarui.');
    }

    /**
     * SURAT PANGGILAN ORANG TUA / WALI (Redirect to unified Tindak Lanjut & Surat)
     */
    public function indexSurat(Request $request)
    {
        return redirect()->route('guru.tindak-lanjut.index', array_merge(['tab' => 'surat'], $request->all()));
    }

    public function createSurat(Request $request)
    {
        $guru = $this->getCurrentGuruBk();
        $tindakLanjutId = $request->tindak_lanjut_id;
        $tindakLanjut = $tindakLanjutId ? TindakLanjut::with('sesiKonseling.pengajuan.siswa.kelas')->find($tindakLanjutId) : null;
        
        $availableTindakLanjuts = TindakLanjut::with('sesiKonseling.pengajuan.siswa.kelas')
            ->where('jenis_aksi', 'surat_ortu')
            ->whereDoesntHave('suratPanggilans')
            ->get();

        // Generate Nomor Surat Otomatis dengan format: 422/[nomor_urut]/SMK.N 2-GG/[romawi]/[tahun]
        $romawiBulan = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $bulanIdx = Carbon::now()->month - 1;
        $tahun = Carbon::now()->year;
        
        // Menghitung jumlah surat di tahun ini untuk menentukan nomor urut berikutnya (001, 002, dst)
        $count = SuratPanggilan::whereYear('tanggal_terbit', $tahun)->count() + 1;
        
        // Format string otomatis
        $nomorOtomatis = sprintf("422/%03d/SMK.N 2-GG/%s/%d", $count, $romawiBulan[$bulanIdx], $tahun);

        return view('guru.surat.create', compact('guru', 'tindakLanjut', 'availableTindakLanjuts', 'nomorOtomatis'));
    }

    public function storeSurat(Request $request)
    {
        $guru = $this->getCurrentGuruBk();

        $validated = $request->validate([
            'id_tindak_lanjut' => 'required|exists:tindak_lanjut,id_tindak_lanjut',
            'nomor_surat' => 'required|string|unique:surat_panggilan,nomor_surat',
            'perihal' => 'required|string',
            'tanggal_terbit' => 'required|date',
            'tanggal_pertemuan' => 'required|date|after_or_equal:tanggal_terbit',
            'waktu_pertemuan' => 'required',
            'tempat' => 'required|string',
            'isi_surat' => 'required|string',
        ]);

        $surat = SuratPanggilan::create([
            'id_tindak_lanjut' => $validated['id_tindak_lanjut'],
            'id_guru_bk' => $guru->id_guru_bk,
            'nomor_surat' => $validated['nomor_surat'],
            'perihal' => $validated['perihal'],
            'isi_surat' => $validated['isi_surat'],
            'tanggal_terbit' => $validated['tanggal_terbit'],
            'tanggal_pertemuan' => $validated['tanggal_pertemuan'],
            'waktu_pertemuan' => $validated['waktu_pertemuan'],
            'tempat' => $validated['tempat'],
            'status_surat' => 'terbit',
            'status_kirim_wa' => 'pending',
        ]);

        // Otomatis kirim notifikasi WA ke Orang Tua / Wali
        $this->kirimSuratWa($surat);

        return redirect()->route('guru.surat.index')->with('success', 'Surat Panggilan Orang Tua berhasil diterbitkan dan dikirimkan melalui WhatsApp Gateway.');
    }

    public function showSurat(SuratPanggilan $surat)
    {
        $surat->load(['tindakLanjut.sesiKonseling.pengajuan.siswa.kelas', 'guruBk']);
        return view('guru.surat.show', compact('surat'));
    }

    public function downloadSuratPdf(SuratPanggilan $surat)
    {
        $surat->load(['tindakLanjut.sesiKonseling.pengajuan.siswa.kelas', 'guruBk']);
        $pdf = Pdf::loadView('pdf.surat_panggilan', compact('surat'));
        return $pdf->download('Surat-Panggilan-' . Str::slug($surat->nomor_surat) . '.pdf');
    }

    public function kirimSuratWa(SuratPanggilan $surat)
    {
        $surat->load(['tindakLanjut.sesiKonseling.pengajuan.siswa', 'guruBk']);
        $siswa = $surat->tindakLanjut->sesiKonseling->pengajuan->siswa ?? null;

        if ($siswa && $siswa->no_wa_orang_tua_wali) {
            $msg = "SURAT PANGGILAN RESMI ORANG TUA / WALI\n"
                . "SMK NEGERI 2 GUGUAK\n\n"
                . "Kepada Yth. Bapak/Ibu Orang Tua/Wali dari " . $siswa->nama_siswa . ",\n\n"
                . "No. Surat: " . $surat->nomor_surat . "\n"
                . "Perihal: " . $surat->perihal . "\n"
                . "Hari/Tanggal: " . Carbon::parse($surat->tanggal_pertemuan)->translatedFormat('l, d F Y') . "\n"
                . "Waktu: " . substr($surat->waktu_pertemuan, 0, 5) . " WIB\n"
                . "Tempat: " . $surat->tempat . "\n\n"
                . "Isi Pesan:\n" . $surat->isi_surat . "\n\n"
                . "Kehadiran Bapak/Ibu sangat diharapkan demi perkembangan belajar putra/putri kita di sekolah.\n\n"
                . "Hormat kami,\nGuru BK SMKN 2 Guguak\n" . ($surat->guruBk->nama_lengkap ?? '-');

            $log = $this->waService->send('orang_tua', $siswa->nama_orang_tua_wali ?? 'Orang Tua Siswa', $siswa->no_wa_orang_tua_wali, 'surat_panggilan', $msg);
            
            $surat->update(['status_kirim_wa' => $log->status === 'sent' ? 'terkirim' : 'gagal']);

            return back()->with('success', 'Notifikasi Surat Panggilan telah dikirim ke nomor WhatsApp Orang Tua.');
        }

        return back()->withErrors(['wa' => 'Nomor WhatsApp Orang Tua belum terdaftar pada data profil siswa.']);
    }

    /**
     * LOG NOTIFIKASI WHATSAPP 
     */
    public function indexNotifikasi(Request $request)
    {
        $query = WaLog::query();

        if ($request->filled('penerima_tipe')) {
            $query->where('penerima_tipe', $request->penerima_tipe);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('guru.notifikasi.index', compact('logs'));
    }

    /**
     * LAPORAN & REKAPITULASI LAYANAN BK
     * 2 Menu Utama: Laporan Pelayanan Konseling & Laporan Surat Panggilan Orang Tua 
     */
    public function laporan(Request $request)
    {
        $tipeRekap = $request->get('tipe_rekap', 'layanan_konseling');
        if (!in_array($tipeRekap, ['layanan_konseling', 'surat_panggilan', 'siswa_kelas', 'pelaksanaan', 'pengajuan', 'tindak_lanjut'])) {
            $tipeRekap = 'layanan_konseling';
        }
        $idTahunAjaran = $request->get('id_tahun_ajaran');
        $idKelas = $request->get('id_kelas');
        $bulan = $request->get('bulan');

        $data = $this->getLaporanData($request);
        $kelases = Kelas::orderBy('nama_kelas')->get();
        $tahunAjarans = TahunAjaran::orderBy('nama_tahun_ajaran', 'desc')->get();

        return view('guru.laporan.index', array_merge($data, compact('kelases', 'tahunAjarans', 'tipeRekap', 'idTahunAjaran', 'idKelas', 'bulan')));
    }

    public function downloadLaporanPdf(Request $request)
    {
        $tipeRekap = $request->get('tipe_rekap', 'layanan_konseling');
        if (!in_array($tipeRekap, ['layanan_konseling', 'surat_panggilan', 'siswa_kelas', 'pelaksanaan', 'pengajuan', 'tindak_lanjut'])) {
            $tipeRekap = 'layanan_konseling';
        }
        $data = $this->getLaporanData($request);
        $data['tipeRekap'] = $tipeRekap;
        $data['request'] = $request->all();

        $pdf = Pdf::loadView('pdf.rekap_laporan', $data)->setPaper('a4', 'landscape');
        return $pdf->download("Laporan-BK-{$tipeRekap}-" . date('Ymd') . ".pdf");
    }

    public function downloadLaporanExcel(Request $request)
    {
        $tipeRekap = $request->get('tipe_rekap', 'layanan_konseling');
        if (!in_array($tipeRekap, ['layanan_konseling', 'surat_panggilan', 'siswa_kelas', 'pelaksanaan', 'pengajuan', 'tindak_lanjut'])) {
            $tipeRekap = 'layanan_konseling';
        }
        $data = $this->getLaporanData($request);
        $data['tipeRekap'] = $tipeRekap;
        $data['request'] = $request->all();

        return Excel::download(
            new GuruBkLaporanExport($data),
            "Laporan-BK-{$tipeRekap}-" . date('Ymd') . ".xlsx"
        );
    }

    /**
     * Helper to prepare Guru BK report data
     */
    private function getLaporanData(Request $request): array
    {
        $guru = $this->getCurrentGuruBk();
        $kepsek = Kepsek::first();
        $tipeRekap = $request->get('tipe_rekap', 'layanan_konseling');
        $idTahunAjaran = $request->get('id_tahun_ajaran');
        $idKelas = $request->get('id_kelas');
        $bulan = $request->get('bulan');

        // 1. Data Pelayanan Konseling Lengkap (Berurutan Berdasarkan Tanggal)
        $konselingQuery = PengajuanKonseling::with([
            'siswa.kelas.jurusan',
            'siswa.kelas.waliKelas',
            'siswa.kelas.tahunAjaran',
            'waliKelas',
            'jadwal.guruBk',
            'sesiKonseling.tindakLanjuts.suratPanggilan'
        ])->orderBy('tanggal_pengajuan', 'asc');

        if ($idTahunAjaran) {
            $konselingQuery->whereHas('siswa.kelas', function ($q) use ($idTahunAjaran) {
                $q->where('id_tahun_ajaran', $idTahunAjaran);
            });
        }
        if ($idKelas) {
            $konselingQuery->whereHas('siswa', function ($q) use ($idKelas) {
                $q->where('id_kelas', $idKelas);
            });
        }
        if ($bulan) {
            $konselingQuery->whereMonth('tanggal_pengajuan', $bulan);
        }

        $konselings = $konselingQuery->get();

        // 2. Data Surat Panggilan Orang Tua Lengkap (Berurutan Berdasarkan Tanggal Terbit)
        $suratQuery = SuratPanggilan::with([
            'tindakLanjut.sesiKonseling.pengajuan.siswa.kelas.jurusan',
            'tindakLanjut.sesiKonseling.pengajuan.siswa.kelas.waliKelas',
            'tindakLanjut.sesiKonseling.pengajuan.jadwal.guruBk',
            'guruBk'
        ])->orderBy('tanggal_terbit', 'asc');

        if ($idTahunAjaran) {
            $suratQuery->whereHas('tindakLanjut.sesiKonseling.pengajuan.siswa.kelas', function ($q) use ($idTahunAjaran) {
                $q->where('id_tahun_ajaran', $idTahunAjaran);
            });
        }
        if ($idKelas) {
            $suratQuery->whereHas('tindakLanjut.sesiKonseling.pengajuan.siswa', function ($q) use ($idKelas) {
                $q->where('id_kelas', $idKelas);
            });
        }
        if ($bulan) {
            $suratQuery->whereMonth('tanggal_terbit', $bulan);
        }

        $suratList = $suratQuery->get();

        // Data summary kelas (for backward compatibility if needed)
        $kelasSummary = Kelas::with(['waliKelas', 'jurusan', 'tahunAjaran', 'siswas'])->orderBy('nama_kelas', 'asc')->get();
        $tahunAjarans = TahunAjaran::orderBy('nama_tahun_ajaran', 'desc')->get();

        $sesiList = SesiKonseling::with(['pengajuan.siswa.kelas.jurusan', 'pengajuan.jadwal.guruBk', 'tindakLanjuts'])->orderBy('tanggal_pelaksanaan', 'asc')->get();
        $pengajuanList = $konselings;
        $tindakLanjutList = TindakLanjut::with(['sesi.pengajuan.siswa.kelas', 'suratPanggilan'])->orderBy('created_at', 'asc')->get();

        return compact(
            'guru',
            'kepsek',
            'konselings',
            'suratList',
            'kelasSummary',
            'tahunAjarans',
            'sesiList',
            'pengajuanList',
            'tindakLanjutList'
        );
    }

    /**
     * Riwayat Konseling (Redirect to integrated Data & Riwayat Siswa)
     */
    public function indexRiwayat(Request $request)
    {
        return redirect()->route('guru.siswa.index', array_merge(['tab' => 'riwayat'], $request->all()));
    }

    /**
     * Data Kelas
     */
    public function indexKelas(Request $request)
    {
        $kelases = Kelas::with(['waliKelas', 'jurusan', 'tahunAjaran', 'siswas'])->orderBy('nama_kelas')->get();
        return view('guru.kelas.index', compact('kelases'));
    }
}
