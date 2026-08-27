<?php

namespace App\Http\Controllers\Wakasis;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\TahunAjaran;
use App\Models\Wakasis;
use App\Models\Siswa;
use App\Models\SesiKonseling;
use App\Models\TindakLanjut;
use App\Models\SuratPanggilan;
use App\Models\Kepsek;
use App\Exports\WakasisLaporanExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * FUNGSI FILE INI:
 * Menangani portal Wakasis Kesiswaan untuk memantau rekapitulasi konseling per jurusan/kelas dan ekspor laporan.
 */
class WakasisController extends Controller
{
    /**
     * Helper to get Wakasis model instance.
     */
    private function getCurrentWakasis(): Wakasis
    {
        $user = auth()->user();
        return Wakasis::firstOrCreate(
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
     * 1. DASHBOARD WAKASIS 
     */
    public function dashboard(?Request $request = null)
    {
        $wakasis = $this->getCurrentWakasis();

        // 1. STATISTIK UTAMA (Query dinamis dari database)
        $totalSiswa = Siswa::where('status_siswa', 'aktif')->count();
        $totalLayanan = SesiKonseling::count();
        $layananSelesai = SesiKonseling::where('status_sesi', 'selesai')->count();
        $layananTerjadwal = SesiKonseling::where('status_sesi', 'terjadwal')->count();
        $totalSuratOrtu = SuratPanggilan::count();
        $persenTuntas = $totalLayanan > 0 ? round(($layananSelesai / $totalLayanan) * 100, 1) : 0;

        $stats = [
            'total_siswa' => $totalSiswa,
            'total_layanan' => $totalLayanan,
            'layanan_selesai' => $layananSelesai,
            'layanan_terjadwal' => $layananTerjadwal,
            'surat_ortu' => $totalSuratOrtu,
            'persen_tuntas' => $persenTuntas,
        ];

        // 2. REKAPITULASI BERDASARKAN JURUSAN
        $jurusans = Jurusan::with(['kelas.siswas'])->get();
        $rekapJurusan = $jurusans->map(function ($jurusan) {
            $kelasIds = $jurusan->kelas->pluck('id_kelas');
            $siswaIds = Siswa::whereIn('id_kelas', $kelasIds)->pluck('id_siswa');

            $total = SesiKonseling::whereHas('pengajuan', function ($q) use ($siswaIds) {
                $q->whereIn('id_siswa', $siswaIds);
            })->count();

            $selesai = SesiKonseling::whereHas('pengajuan', function ($q) use ($siswaIds) {
                $q->whereIn('id_siswa', $siswaIds);
            })->where('status_sesi', 'selesai')->count();

            $surat = SuratPanggilan::whereHas('tindakLanjut.sesiKonseling.pengajuan', function ($q) use ($siswaIds) {
                $q->whereIn('id_siswa', $siswaIds);
            })->count();

            $persen = $total > 0 ? round(($selesai / $total) * 100, 1) : 0;

            return [
                'nama_jurusan' => $jurusan->nama_jurusan,
                'total_siswa' => $siswaIds->count(),
                'total_layanan' => $total,
                'selesai' => $selesai,
                'surat_ortu' => $surat,
                'persen_tuntas' => $persen,
            ];
        });

        // 3. REKAPITULASI BERDASARKAN KELAS
        $kelases = Kelas::with(['jurusan', 'siswas'])->orderBy('nama_kelas')->get();
        $rekapKelas = $kelases->map(function ($k) {
            $siswaIds = $k->siswas->pluck('id_siswa');

            $total = SesiKonseling::whereHas('pengajuan', function ($q) use ($siswaIds) {
                $q->whereIn('id_siswa', $siswaIds);
            })->count();

            $selesai = SesiKonseling::whereHas('pengajuan', function ($q) use ($siswaIds) {
                $q->whereIn('id_siswa', $siswaIds);
            })->where('status_sesi', 'selesai')->count();

            $surat = SuratPanggilan::whereHas('tindakLanjut.sesiKonseling.pengajuan', function ($q) use ($siswaIds) {
                $q->whereIn('id_siswa', $siswaIds);
            })->count();

            return [
                'nama_kelas' => $k->nama_kelas,
                'jurusan' => $k->jurusan->nama_jurusan ?? '-',
                'total_siswa' => $k->siswas->count(),
                'total_layanan' => $total,
                'selesai' => $selesai,
                'surat_ortu' => $surat,
            ];
        });

        return view('wakasis.dashboard', compact('wakasis', 'stats', 'rekapJurusan', 'rekapKelas'));
    }

    /**
     * 2. REKAPITULASI TINGKAT SEKOLAH 
     */
    public function indexRekapitulasi(Request $request)
    {
        $tipeRekap = $request->get('tipe_rekap', 'rekap_sekolah');
        $idJurusan = $request->get('id_jurusan');
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');

        $data = $this->getWakasisReportData($tipeRekap, $idJurusan, $bulan, $tahun);
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();

        return view('wakasis.laporan.index', array_merge($data, compact('jurusans', 'tipeRekap', 'idJurusan', 'bulan', 'tahun')));
    }

    /**
     * 3. UNDUH LAPORAN STATISTIK SEKOLAH PDF 
     */
    public function downloadLaporanPdf(Request $request)
    {
        $tipeRekap = $request->get('tipe_rekap', 'rekap_sekolah');
        $idJurusan = $request->get('id_jurusan');
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');

        $data = $this->getWakasisReportData($tipeRekap, $idJurusan, $bulan, $tahun);
        $data['tipeRekap'] = $tipeRekap;

        $pdf = Pdf::loadView('pdf.wakasis_rekap', $data);
        return $pdf->download("Laporan-Wakasis-{$tipeRekap}-" . date('Ymd') . ".pdf");
    }

    /**
     * UNDUH LAPORAN STATISTIK SEKOLAH EXCEL (.xlsx)
     */
    public function downloadLaporanExcel(Request $request)
    {
        $tipeRekap = $request->get('tipe_rekap', 'rekap_sekolah');
        $idJurusan = $request->get('id_jurusan');
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');

        $data = $this->getWakasisReportData($tipeRekap, $idJurusan, $bulan, $tahun);
        $data['tipeRekap'] = $tipeRekap;

        return Excel::download(
            new WakasisLaporanExport($data),
            "Laporan-Wakasis-{$tipeRekap}-" . date('Ymd') . ".xlsx"
        );
    }

    /**
     * Helper to prepare report data for Wakasis
     */
    private function getWakasisReportData($tipeRekap, $idJurusan = null, $bulan = null, $tahun = null): array
    {
        $wakasis = $this->getCurrentWakasis();
        $kepsek = Kepsek::first();

        $query = SesiKonseling::with(['pengajuan.siswa.kelas.jurusan', 'pengajuan.jadwal.guruBk']);

        if ($bulan) {
            $query->whereMonth('tanggal_pelaksanaan', $bulan);
        }
        if ($tahun) {
            $query->whereYear('tanggal_pelaksanaan', $tahun);
        }
        if ($idJurusan) {
            $query->whereHas('pengajuan.siswa.kelas', function ($q) use ($idJurusan) {
                $q->where('id_jurusan', $idJurusan);
            });
        }

        $sesiList = $query->orderBy('tanggal_pelaksanaan', 'desc')->get();

        // Rekapitulasi per Jurusan & Kelas
        $kelasQuery = Kelas::with(['jurusan', 'waliKelas', 'siswas']);
        if ($idJurusan) {
            $kelasQuery->where('id_jurusan', $idJurusan);
        }
        $kelases = $kelasQuery->orderBy('nama_kelas')->get();

        $jurusanStats = $kelases->map(function ($k) use ($bulan, $tahun) {
            $siswaIds = $k->siswas->pluck('id_siswa');
            $sesiQuery = SesiKonseling::whereHas('pengajuan', function ($q) use ($siswaIds) {
                $q->whereIn('id_siswa', $siswaIds);
            });

            if ($bulan) {
                $sesiQuery->whereMonth('tanggal_pelaksanaan', $bulan);
            }
            if ($tahun) {
                $sesiQuery->whereYear('tanggal_pelaksanaan', $tahun);
            }

            $totalLayanan = (clone $sesiQuery)->count();
            $totalSiswa = $k->siswas->count();
            $persen = $totalSiswa > 0 ? round(($totalLayanan / $totalSiswa) * 100, 1) : 0;

            return [
                'jurusan' => $k->jurusan->nama_jurusan ?? '-',
                'nama_kelas' => $k->nama_kelas,
                'wali_kelas' => $k->waliKelas->nama_lengkap ?? '-',
                'total_siswa' => $totalSiswa,
                'siswa_konseling' => $totalLayanan,
                'persentase' => $persen,
            ];
        });

        $periodeText = ($bulan ? date('F', mktime(0, 0, 0, (int)$bulan, 1)) : '') . ($tahun ? " $tahun" : ($bulan ? '' : 'Semua Periode'));

        return compact(
            'wakasis',
            'kepsek',
            'sesiList',
            'jurusanStats',
            'periodeText'
        );
    }

    /**
     * 4. DATA SISWA (School-Wide Read-Only)
     */
    public function indexSiswa(?Request $request = null)
    {
        $wakasis = $this->getCurrentWakasis();
        $query = Siswa::with(['kelas.jurusan', 'kelas.waliKelas']);

        if ($request && $request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request && $request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        $siswas = $query->orderBy('nama_siswa')->paginate(15)->withQueryString();
        $kelases = Kelas::orderBy('nama_kelas')->get();
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();

        return view('wakasis.siswa.index', compact('wakasis', 'siswas', 'kelases', 'jurusans'));
    }
}
