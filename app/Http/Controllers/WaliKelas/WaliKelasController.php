<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\WaliKelas;
use App\Models\Siswa;
use App\Models\PengajuanKonseling;
use App\Models\SesiKonseling;
use App\Models\GuruBk;
use App\Services\WhatsApp\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * FUNGSI FILE INI:
 * Menangani portal wali kelas untuk memantau siswa binaan, pengajuan rujukan alih tangan kasus ke Guru BK, serta monitoring.
 */
class WaliKelasController extends Controller
{
    protected WhatsAppNotificationService $waService;

    public function __construct(WhatsAppNotificationService $waService)
    {
        $this->waService = $waService;
    }

    /**
     * Helper to get the WaliKelas model instance and assigned class.
     */
    private function getWaliKelasData()
    {
        $user = auth()->user();
        $wali = WaliKelas::where('user_id', $user->id)
            ->orWhere('username', $user->username)
            ->first();

        if (!$wali) {
            $wali = WaliKelas::create([
                'user_id' => $user->id,
                'username' => $user->username,
                'password' => $user->password,
                'nama_lengkap' => $user->name,
                'email' => $user->email,
            ]);
        } elseif (!$wali->user_id) {
            $wali->update(['user_id' => $user->id]);
        }

        $kelas = Kelas::with(['jurusan', 'tahunAjaran'])->where('id_wali_kelas', $wali->id_wali_kelas)->first();

        return [$wali, $kelas];
    }

    /**
     * 1. DASHBOARD WALI KELAS 
     */
    public function dashboard(Request $request)
    {
        [$wali, $kelas] = $this->getWaliKelasData();

        if (!$kelas) {
            return view('wali.no_kelas', compact('wali'));
        }

        // Statistik Siswa Binaan
        $totalSiswa = Siswa::where('id_kelas', $kelas->id_kelas)->where('status_siswa', 'aktif')->count();

        $totalRujukan = PengajuanKonseling::where('id_wali_kelas', $wali->id_wali_kelas)
            ->where('sumber_pengajuan', 'wali_kelas')
            ->count();

        $sedangKonseling = SesiKonseling::whereHas('pengajuan.siswa', function ($q) use ($kelas) {
            $q->where('id_kelas', $kelas->id_kelas);
        })->where('status_sesi', 'terjadwal')->count();

        $selesaiKonseling = SesiKonseling::whereHas('pengajuan.siswa', function ($q) use ($kelas) {
            $q->where('id_kelas', $kelas->id_kelas);
        })->where('status_sesi', 'selesai')->count();

        // Data Siswa di Kelas yang Diampu
        $siswasQuery = Siswa::where('id_kelas', $kelas->id_kelas);
        if ($request->filled('search_siswa')) {
            $search = $request->search_siswa;
            $siswasQuery->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }
        $siswasList = $siswasQuery->orderBy('nama_siswa')->paginate(10, ['*'], 'siswa_page')->withQueryString();

        // Daftar Rujukan Terkini
        $rujukanList = PengajuanKonseling::with(['siswa', 'sesiKonseling'])
            ->where('id_wali_kelas', $wali->id_wali_kelas)
            ->where('sumber_pengajuan', 'wali_kelas')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Sesi Konseling Terkini Siswa Binaan
        $jadwalList = SesiKonseling::with(['pengajuan.siswa', 'pengajuan.jadwal.guruBk'])
            ->whereHas('pengajuan.siswa', function ($q) use ($kelas) {
                $q->where('id_kelas', $kelas->id_kelas);
            })
            ->orderBy('tanggal_pelaksanaan', 'desc')
            ->take(5)
            ->get();

        return view('wali.dashboard', compact(
            'wali',
            'kelas',
            'totalSiswa',
            'totalRujukan',
            'sedangKonseling',
            'selesaiKonseling',
            'siswasList',
            'rujukanList',
            'jadwalList'
        ));
    }

    /**
     * 2. DATA SISWA BINAAN 
     */
    public function indexSiswa(Request $request)
    {
        [$wali, $kelas] = $this->getWaliKelasData();
        if (!$kelas) return view('wali.no_kelas', compact('wali'));

        $query = Siswa::where('id_kelas', $kelas->id_kelas);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $siswas = $query->orderBy('nama_siswa')->paginate(15)->withQueryString();

        return view('wali.siswa.index', compact('wali', 'kelas', 'siswas'));
    }

    public function showSiswa(Siswa $siswa)
    {
        [$wali, $kelas] = $this->getWaliKelasData();
        if (!$kelas) {
            return view('wali.no_kelas', compact('wali'));
        }

        if ((int)$siswa->id_kelas !== (int)$kelas->id_kelas) {
            abort(403, 'Anda tidak diperbolehkan mengakses data siswa dari kelas lain.');
        }

        $siswa->load(['kelas.jurusan']);

        // Riwayat pengajuan & status layanan siswa binaan
        $riwayatLayanan = SesiKonseling::with(['pengajuan.jadwal.guruBk'])
            ->whereHas('pengajuan', function ($q) use ($siswa) {
                $q->where('id_siswa', $siswa->id_siswa);
            })
            ->orderBy('tanggal_pelaksanaan', 'desc')
            ->get();

        return view('wali.siswa.show', compact('wali', 'kelas', 'siswa', 'riwayatLayanan'));
    }

    /**
     * 3. AJUKAN RUJUKAN KE GURU BK 
     */
    public function createRujukan(Request $request)
    {
        [$wali, $kelas] = $this->getWaliKelasData();
        if (!$kelas) return view('wali.no_kelas', compact('wali'));

        $siswas = Siswa::where('id_kelas', $kelas->id_kelas)->where('status_siswa', 'aktif')->orderBy('nama_siswa')->get();
        $selectedSiswaId = $request->siswa_id;

        return view('wali.rujukan.create', compact('wali', 'kelas', 'siswas', 'selectedSiswaId'));
    }

    public function storeRujukan(Request $request)
    {
        [$wali, $kelas] = $this->getWaliKelasData();
        if (!$kelas) return view('wali.no_kelas', compact('wali'));

        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'alasan_rujukan' => 'required|string|min:5',
            'jenis_konseling' => 'required|in:individu,kelompok,insidental',
        ]);

        $siswa = Siswa::where('id_kelas', $kelas->id_kelas)->findOrFail($validated['id_siswa']);

        $pengajuan = PengajuanKonseling::create([
            'id_siswa' => $siswa->id_siswa,
            'id_jadwal' => null,
            'jenis_konseling' => $validated['jenis_konseling'],
            'alasan_pengajuan' => 'Rujukan dari Wali Kelas: ' . $validated['alasan_rujukan'],
            'alasan_rujukan' => $validated['alasan_rujukan'],
            'sumber_pengajuan' => 'wali_kelas',
            'id_wali_kelas' => $wali->id_wali_kelas,
            'status_pengajuan' => 'menunggu_validasi',
            'tanggal_pengajuan' => Carbon::now(),
        ]);

        // Mengirim notifikasi WhatsApp ke Guru BK
        $guru = GuruBk::first();
        if ($guru && $guru->no_hp) {
            $msg = "Rujukan Konseling Baru dari Wali Kelas!\n\n"
                . "Wali Kelas " . $wali->nama_lengkap . " (" . $kelas->nama_kelas . ") telah merujuk siswa:\n"
                . "Nama: " . $siswa->nama_siswa . "\n"
                . "Alasan Rujukan: " . $validated['alasan_rujukan'] . "\n\n"
                . "Silakan buka aplikasi SIKS untuk memvalidasi dan menindaklanjuti rujukan ini.";

            $this->waService->send('guru_bk', $guru->nama_lengkap, $guru->no_hp, 'rujukan_baru', $msg);
        }

        return redirect()->route('wali.monitoring.index')->with('success', 'Rujukan konseling berhasil diajukan ke Guru BK.');
    }

    /**
     * 4. MONITORING STATUS LAYANAN SISWA 
     */
    public function indexMonitoring(Request $request)
    {
        [$wali, $kelas] = $this->getWaliKelasData();
        if (!$kelas) return view('wali.no_kelas', compact('wali'));

        $query = SesiKonseling::with(['pengajuan.siswa', 'pengajuan.jadwal.guruBk'])
            ->whereHas('pengajuan.siswa', function ($q) use ($kelas) {
                $q->where('id_kelas', $kelas->id_kelas);
            });

        if ($request->filled('status')) {
            $query->where('status_sesi', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pengajuan.siswa', function ($s) use ($search) {
                $s->where('nama_siswa', 'like', "%{$search}%");
            });
        }

        $layananList = $query->orderBy('tanggal_pelaksanaan', 'desc')->paginate(15)->withQueryString();

        return view('wali.pemantauan.index', compact('wali', 'kelas', 'layananList'));
    }

    /**
     * 5. JADWAL KONSELING SISWA BINAAN
     */
    public function indexJadwal(Request $request)
    {
        [$wali, $kelas] = $this->getWaliKelasData();
        if (!$kelas) return view('wali.no_kelas', compact('wali'));

        $jadwals = SesiKonseling::with(['pengajuan.siswa', 'pengajuan.jadwal.guruBk'])
            ->whereHas('pengajuan.siswa', function ($q) use ($kelas) {
                $q->where('id_kelas', $kelas->id_kelas);
            })
            ->orderBy('tanggal_pelaksanaan', 'desc')
            ->paginate(15);

        return view('wali.jadwal.index', compact('wali', 'kelas', 'jadwals'));
    }
}
