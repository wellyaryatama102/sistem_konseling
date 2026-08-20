<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\TahunAjaran;
use App\Models\Kepsek;
use App\Models\PengajuanKonseling;
use App\Models\SesiKonseling;
use App\Models\SuratPanggilan;
use App\Models\WaLog;
use App\Exports\AdminLaporanExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Menampilkan Rekapitulasi & Laporan Sistem untuk Admin
     */
    public function index(Request $request)
    {
        $tipeRekap = $request->get('tipe_rekap', 'layanan_konseling');
        if (!in_array($tipeRekap, ['layanan_konseling', 'siswa_kelas'])) {
            $tipeRekap = 'layanan_konseling';
        }
        $idKelas = $request->get('id_kelas');
        $idJurusan = $request->get('id_jurusan');
        $status = $request->get('status');
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');
        $search = $request->get('search');

        $data = $this->getReportData($request);
        $kelases = Kelas::orderBy('nama_kelas')->get();
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();
        $tahunAjarans = TahunAjaran::orderBy('nama_tahun_ajaran', 'desc')->get();

        return view('admin.laporan.index', array_merge($data, compact(
            'kelases', 'jurusans', 'tahunAjarans', 'tipeRekap', 'idKelas', 'idJurusan', 'status', 'bulan', 'tahun', 'search'
        )));
    }

    /**
     * Download Laporan Format PDF
     */
    public function downloadPdf(Request $request)
    {
        $tipeRekap = $request->get('tipe_rekap', 'layanan_konseling');
        if (!in_array($tipeRekap, ['layanan_konseling', 'siswa_kelas'])) {
            $tipeRekap = 'layanan_konseling';
        }
        $data = $this->getReportData($request);
        $data['tipeRekap'] = $tipeRekap;
        $data['request'] = $request->all();

        $pdf = Pdf::loadView('pdf.admin_rekap', $data)->setPaper('a4', 'landscape');
        return $pdf->download("Laporan-Admin-{$tipeRekap}-" . date('Ymd') . ".pdf");
    }

    /**
     * Download Laporan Format Excel (.xlsx)
     */
    public function downloadExcel(Request $request)
    {
        $tipeRekap = $request->get('tipe_rekap', 'layanan_konseling');
        if (!in_array($tipeRekap, ['layanan_konseling', 'siswa_kelas'])) {
            $tipeRekap = 'layanan_konseling';
        }
        $data = $this->getReportData($request);
        $data['tipeRekap'] = $tipeRekap;
        $data['request'] = $request->all();

        return Excel::download(
            new AdminLaporanExport($data),
            "Laporan-Admin-{$tipeRekap}-" . date('Ymd') . ".xlsx"
        );
    }

    /**
     * Helper to prepare comprehensive report data
     */
    private function getReportData(Request $request): array
    {
        $tipeRekap = $request->get('tipe_rekap', 'layanan_konseling');
        if (!in_array($tipeRekap, ['layanan_konseling', 'siswa_kelas'])) {
            $tipeRekap = 'layanan_konseling';
        }
        $idTahunAjaran = $request->get('id_tahun_ajaran');
        $semester = $request->get('semester');
        $bulan = $request->get('bulan');
        $idKelas = $request->get('id_kelas');
        $idJurusan = $request->get('id_jurusan');
        $status = $request->get('status');
        $tahun = $request->get('tahun');
        $search = $request->get('search');

        // Master stats ringkasan eksekutif
        $stats = [
            'total_siswa' => Siswa::count(),
            'siswa_aktif' => Siswa::where('status_siswa', 'aktif')->count(),
            'siswa_lulus' => Siswa::where('status_siswa', 'lulus')->count(),
            'siswa_nonaktif' => Siswa::whereIn('status_siswa', ['pindah', 'do'])->count(),
            'siswa_laki' => Siswa::where('jenis_kelamin', 'L')->count(),
            'siswa_perempuan' => Siswa::where('jenis_kelamin', 'P')->count(),
            'total_kelas' => Kelas::count(),
            'total_jurusan' => Jurusan::count(),
            'total_pengguna' => User::count(),
            'user_aktif' => User::where('status', 'active')->count(),
            'user_nonaktif' => User::where('status', 'inactive')->count(),
            'total_pengajuan' => PengajuanKonseling::count(),
            'pengajuan_disetujui' => PengajuanKonseling::where('status_pengajuan', 'disetujui')->count(),
            'sesi_selesai' => SesiKonseling::where('status_sesi', 'selesai')->count(),
            'total_surat' => SuratPanggilan::count(),
            'surat_wa_terkirim' => SuratPanggilan::where('status_kirim_wa', 'terkirim')->count(),
        ];

        // 1. Rekap Role Pengguna
        $roleSummary = User::select('role', DB::raw('count(*) as total'),
            DB::raw("SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_count"),
            DB::raw("SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive_count"))
            ->groupBy('role')
            ->get();

        // 2. Rekap Siswa per Kelas (Berurutan Abjad A-Z)
        $kelasQuery = Kelas::withCount([
            'siswas',
            'siswas as siswas_laki_count' => function ($q) {
                $q->where('jenis_kelamin', 'L');
            },
            'siswas as siswas_perempuan_count' => function ($q) {
                $q->where('jenis_kelamin', 'P');
            },
            'siswas as siswas_aktif_count' => function ($q) {
                $q->where('status_siswa', 'aktif');
            }
        ])
        ->with(['waliKelas', 'jurusan', 'tahunAjaran', 'siswas' => function ($q) {
            $q->orderBy('nama_siswa', 'asc');
        }])
        ->orderBy('nama_kelas', 'asc');

        if ($idTahunAjaran) {
            $kelasQuery->where('id_tahun_ajaran', $idTahunAjaran);
        }
        if ($idKelas) {
            $kelasQuery->where('id_kelas', $idKelas);
        }
        if ($idJurusan) {
            $kelasQuery->where('id_jurusan', $idJurusan);
        }
        $kelasSummary = $kelasQuery->get();

        // 3. Rekap Status Siswa
        $siswaStatusSummary = Siswa::select('status_siswa', DB::raw('count(*) as total'))
            ->groupBy('status_siswa')
            ->get();

        // 4. Data Siswa Detail (Arsip Kesiswaan - Abjad A-Z)
        $siswaQuery = Siswa::with(['kelas.jurusan', 'kelas.tahunAjaran', 'user'])
            ->orderBy('nama_siswa', 'asc');
        if ($idTahunAjaran) {
            $siswaQuery->whereHas('kelas', function ($q) use ($idTahunAjaran) {
                $q->where('id_tahun_ajaran', $idTahunAjaran);
            });
        }
        if ($idKelas) {
            $siswaQuery->where('id_kelas', $idKelas);
        }
        if ($idJurusan) {
            $siswaQuery->whereHas('kelas', function ($q) use ($idJurusan) {
                $q->where('id_jurusan', $idJurusan);
            });
        }
        if ($status) {
            $siswaQuery->where('status_siswa', $status);
        }
        if ($search) {
            $siswaQuery->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }
        $siswas = $siswaQuery->get();

        // 5. Data Users
        $userQuery = User::orderBy('name', 'asc');
        if ($status) {
            $userQuery->where('status', $status);
        }
        if ($bulan) {
            $userQuery->whereMonth('created_at', $bulan);
        }
        if ($search) {
            $userQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $users = $userQuery->get();

        // 6. Data Pelayanan Konseling (Berurutan Berdasarkan Tanggal)
        $konselingQuery = PengajuanKonseling::with([
            'siswa.kelas.jurusan',
            'waliKelas',
            'jadwal.guruBk',
            'sesiKonseling.tindakLanjuts.suratPanggilan'
        ])->orderBy('tanggal_pengajuan', 'asc');

        if ($idTahunAjaran) {
            $konselingQuery->whereHas('siswa.kelas', function ($q) use ($idTahunAjaran) {
                $q->where('id_tahun_ajaran', $idTahunAjaran);
            });
        }
        if ($semester) {
            if ($semester === 'ganjil') {
                $konselingQuery->whereMonth('tanggal_pengajuan', '>=', 7)->whereMonth('tanggal_pengajuan', '<=', 12);
            } elseif ($semester === 'genap') {
                $konselingQuery->whereMonth('tanggal_pengajuan', '>=', 1)->whereMonth('tanggal_pengajuan', '<=', 6);
            }
        }
        if ($idKelas) {
            $konselingQuery->whereHas('siswa', function ($q) use ($idKelas) {
                $q->where('id_kelas', $idKelas);
            });
        }
        if ($status) {
            $konselingQuery->where('status_pengajuan', $status);
        }
        if ($bulan) {
            $konselingQuery->whereMonth('tanggal_pengajuan', $bulan);
        }
        if ($tahun) {
            $konselingQuery->whereYear('tanggal_pengajuan', $tahun);
        }
        if ($search) {
            $konselingQuery->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }
        $konselings = $konselingQuery->get();

        // 7. Data Surat Panggilan
        $suratQuery = SuratPanggilan::with([
            'guruBk',
            'tindakLanjut.sesiKonseling.pengajuan.siswa.kelas.jurusan'
        ])->orderBy('tanggal_terbit', 'desc');

        if ($idTahunAjaran) {
            $suratQuery->whereHas('tindakLanjut.sesiKonseling.pengajuan.siswa.kelas', function ($q) use ($idTahunAjaran) {
                $q->where('id_tahun_ajaran', $idTahunAjaran);
            });
        }
        if ($semester) {
            if ($semester === 'ganjil') {
                $suratQuery->whereMonth('tanggal_terbit', '>=', 7)->whereMonth('tanggal_terbit', '<=', 12);
            } elseif ($semester === 'genap') {
                $suratQuery->whereMonth('tanggal_terbit', '>=', 1)->whereMonth('tanggal_terbit', '<=', 6);
            }
        }
        if ($status) {
            $suratQuery->where('status_kirim_wa', $status);
        }
        if ($bulan) {
            $suratQuery->whereMonth('tanggal_terbit', $bulan);
        }
        if ($tahun) {
            $suratQuery->whereYear('tanggal_terbit', $tahun);
        }
        if ($search) {
            $suratQuery->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%");
            });
        }
        $surats = $suratQuery->get();

        $totalPengguna = User::count();
        $totalSiswa = Siswa::count();
        $totalKelas = Kelas::count();
        $tahunAjaranAktif = TahunAjaran::where('status_aktif', true)->first();
        $tahunAjarans = TahunAjaran::orderBy('nama_tahun_ajaran', 'desc')->get();
        $kelases = Kelas::with('tahunAjaran')->orderBy('nama_kelas')->get();
        $kepsek = Kepsek::first();

        return compact(
            'stats',
            'roleSummary',
            'kelasSummary',
            'siswaStatusSummary',
            'siswas',
            'users',
            'konselings',
            'surats',
            'totalPengguna',
            'totalSiswa',
            'totalKelas',
            'tahunAjaranAktif',
            'tahunAjarans',
            'kelases',
            'kepsek'
        );
    }
}
