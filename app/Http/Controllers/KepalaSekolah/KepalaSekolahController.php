<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\TahunAjaran;
use App\Models\Kepsek;
use App\Models\GuruBk;
use App\Models\Siswa;
use App\Models\PengajuanKonseling;
use App\Models\SesiKonseling;
use App\Models\SuratPanggilan;
use App\Exports\KepsekLaporanExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KepalaSekolahController extends Controller
{
    /**
     * Helper to get Kepsek model instance.
     */
    private function getCurrentKepsek(): Kepsek
    {
        $user = auth()->user();
        return Kepsek::firstOrCreate(
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
     * 1. DASHBOARD EKSEKUTIF KEPALA SEKOLAH
     */
    public function dashboard(?Request $request = null)
    {
        $kepsek = $this->getCurrentKepsek();

        // 1. STATISTIK UTAMA
        $totalSiswa = Siswa::where('status_siswa', 'aktif')->count();
        $totalSesi = SesiKonseling::count();
        $sesiSelesai = SesiKonseling::where('status_sesi', 'selesai')->count();
        $totalSurat = SuratPanggilan::count();
        $persenTuntas = $totalSesi > 0 ? round(($sesiSelesai / $totalSesi) * 100, 1) : 0;

        $stats = [
            'total_siswa' => $totalSiswa,
            'total_sesi' => $totalSesi,
            'sesi_selesai' => $sesiSelesai,
            'surat_ortu' => $totalSurat,
            'persen_tuntas' => $persenTuntas,
        ];

        // 2. KINERJA GURU BK
        $gurus = GuruBk::with(['jadwalKetersediaans'])->get();
        $kinerjaGuru = $gurus->map(function ($g) {
            $total = SesiKonseling::whereHas('pengajuan.jadwal', function ($q) use ($g) {
                $q->where('id_guru_bk', $g->id_guru_bk);
            })->count();

            $selesai = SesiKonseling::whereHas('pengajuan.jadwal', function ($q) use ($g) {
                $q->where('id_guru_bk', $g->id_guru_bk);
            })->where('status_sesi', 'selesai')->count();

            $surat = SuratPanggilan::where('id_guru_bk', $g->id_guru_bk)->count();
            $slot = $g->jadwalKetersediaans->count();

            return [
                'nama_guru' => $g->nama_lengkap,
                'nip' => $g->nip ?? '-',
                'total_slot' => $slot,
                'total_layanan' => $total,
                'selesai' => $selesai,
                'surat_ortu' => $surat,
                'persen' => $total > 0 ? round(($selesai / $total) * 100, 1) : 0,
            ];
        });

        // 3. PEMETAAN BIDANG
        $pemetaanBidang = [
            ['nama' => 'Pribadi', 'count' => PengajuanKonseling::where('alasan_pengajuan', 'like', '%pribadi%')->count() ?: 1],
            ['nama' => 'Sosial', 'count' => PengajuanKonseling::where('alasan_pengajuan', 'like', '%sosial%')->count() ?: 1],
            ['nama' => 'Belajar', 'count' => PengajuanKonseling::where('alasan_pengajuan', 'like', '%belajar%')->count() ?: 1],
            ['nama' => 'Karir', 'count' => PengajuanKonseling::where('alasan_pengajuan', 'like', '%karir%')->count() ?: 1],
        ];

        return view('kepsek.dashboard', compact('kepsek', 'stats', 'kinerjaGuru', 'pemetaanBidang'));
    }

    /**
     * 2. KINERJA GURU BK
     */
    public function kinerjaGuruBk(?Request $request = null)
    {
        $kepsek = $this->getCurrentKepsek();
        $gurus = GuruBk::with(['jadwalKetersediaans'])->get();

        $kinerjaGuru = $gurus->map(function ($g) {
            $total = SesiKonseling::whereHas('pengajuan.jadwal', function ($q) use ($g) {
                $q->where('id_guru_bk', $g->id_guru_bk);
            })->count();

            $selesai = SesiKonseling::whereHas('pengajuan.jadwal', function ($q) use ($g) {
                $q->where('id_guru_bk', $g->id_guru_bk);
            })->where('status_sesi', 'selesai')->count();

            $surat = SuratPanggilan::where('id_guru_bk', $g->id_guru_bk)->count();
            $slot = $g->jadwalKetersediaans->count();

            return [
                'nama_guru' => $g->nama_lengkap,
                'nip' => $g->nip ?? '-',
                'total_slot' => $slot,
                'total_layanan' => $total,
                'selesai' => $selesai,
                'surat_ortu' => $surat,
                'persen' => $total > 0 ? round(($selesai / $total) * 100, 1) : 0,
            ];
        });

        return view('kepsek.kinerja.index', compact('kepsek', 'kinerjaGuru'));
    }

    /**
     * 3. PEMETAAN BIDANG KONSELING 
     */
    public function pemetaanBidang(?Request $request = null)
    {
        $kepsek = $this->getCurrentKepsek();

        $pemetaanBidang = [
            ['nama' => 'Pribadi', 'count' => PengajuanKonseling::where('alasan_pengajuan', 'like', '%pribadi%')->count() ?: 1],
            ['nama' => 'Sosial', 'count' => PengajuanKonseling::where('alasan_pengajuan', 'like', '%sosial%')->count() ?: 1],
            ['nama' => 'Belajar', 'count' => PengajuanKonseling::where('alasan_pengajuan', 'like', '%belajar%')->count() ?: 1],
            ['nama' => 'Karir', 'count' => PengajuanKonseling::where('alasan_pengajuan', 'like', '%karir%')->count() ?: 1],
        ];

        $pengajuanList = PengajuanKonseling::with(['siswa.kelas'])->orderBy('created_at', 'desc')->paginate(15);

        return view('kepsek.pemetaan.index', compact('kepsek', 'pemetaanBidang', 'pengajuanList'));
    }

    /**
     * 4. LAPORAN KINERJA EKSEKUTIF & DOKUMEN 
     */
    public function indexLaporan(Request $request)
    {
        $tipeRekap = $request->get('tipe_rekap', 'rekap_eksekutif');
        $idTahunAjaran = $request->get('id_tahun_ajaran');
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');

        $data = $this->getKepsekReportData($tipeRekap, $idTahunAjaran, $bulan, $tahun);
        $tahunAjarans = TahunAjaran::orderBy('nama_tahun_ajaran', 'desc')->get();

        return view('kepsek.laporan.index', array_merge($data, compact('tahunAjarans', 'tipeRekap', 'idTahunAjaran', 'bulan', 'tahun')));
    }

    public function downloadLaporanPdf(Request $request)
    {
        $tipeRekap = $request->get('tipe_rekap', 'rekap_eksekutif');
        $idTahunAjaran = $request->get('id_tahun_ajaran');
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');

        $data = $this->getKepsekReportData($tipeRekap, $idTahunAjaran, $bulan, $tahun);
        $data['tipeRekap'] = $tipeRekap;

        $pdf = Pdf::loadView('pdf.kepsek_rekap', $data);
        return $pdf->download("Laporan-Eksekutif-Kepsek-{$tipeRekap}-" . date('Ymd') . ".pdf");
    }

    public function downloadLaporanExcel(Request $request)
    {
        $tipeRekap = $request->get('tipe_rekap', 'rekap_eksekutif');
        $idTahunAjaran = $request->get('id_tahun_ajaran');
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');

        $data = $this->getKepsekReportData($tipeRekap, $idTahunAjaran, $bulan, $tahun);
        $data['tipeRekap'] = $tipeRekap;

        return Excel::download(
            new KepsekLaporanExport($data),
            "Laporan-Eksekutif-Kepsek-{$tipeRekap}-" . date('Ymd') . ".xlsx"
        );
    }

    /**
     * Helper to prepare report data for Kepala Sekolah
     */
    private function getKepsekReportData($tipeRekap, $idTahunAjaran = null, $bulan = null, $tahun = null): array
    {
        $kepsek = $this->getCurrentKepsek();
        $tahunAjaranAktif = TahunAjaran::where('status_aktif', true)->first();

        // 1. Data Sesi Konseling Eksekutif
        $query = SesiKonseling::with(['pengajuan.siswa.kelas', 'pengajuan.jadwal.guruBk']);
        if ($bulan) {
            $query->whereMonth('tanggal_pelaksanaan', $bulan);
        }
        if ($tahun) {
            $query->whereYear('tanggal_pelaksanaan', $tahun);
        }
        if ($idTahunAjaran) {
            $query->whereHas('pengajuan.siswa.kelas', function ($q) use ($idTahunAjaran) {
                $q->where('id_tahun_ajaran', $idTahunAjaran);
            });
        }
        $sesiList = $query->orderBy('tanggal_pelaksanaan', 'desc')->get();

        // 2. Data Kinerja Guru BK
        $gurus = GuruBk::with(['jadwalKetersediaans'])->get();
        $kinerjaGuru = $gurus->map(function ($g) use ($bulan, $tahun) {
            $querySesi = SesiKonseling::whereHas('pengajuan.jadwal', function ($q) use ($g) {
                $q->where('id_guru_bk', $g->id_guru_bk);
            });
            if ($bulan) {
                $querySesi->whereMonth('tanggal_pelaksanaan', $bulan);
            }
            if ($tahun) {
                $querySesi->whereYear('tanggal_pelaksanaan', $tahun);
            }

            $total = (clone $querySesi)->count();
            $selesai = (clone $querySesi)->where('status_sesi', 'selesai')->count();
            $surat = SuratPanggilan::where('id_guru_bk', $g->id_guru_bk)->count();
            $slot = $g->jadwalKetersediaans->count();

            return [
                'nama' => $g->nama_lengkap,
                'nip' => $g->nip ?? '-',
                'slot_dibuka' => $slot,
                'sesi_selesai' => $selesai,
                'tindak_lanjut' => $surat,
                'efektivitas' => $total > 0 ? round(($selesai / $total) * 100, 1) : 100,
            ];
        });

        // 3. Data Pemetaan Bidang Bimbingan
        $totalPengajuan = PengajuanKonseling::count() ?: 1;
        $countPribadi = PengajuanKonseling::where('alasan_pengajuan', 'like', '%pribadi%')->count();
        $countSosial = PengajuanKonseling::where('alasan_pengajuan', 'like', '%sosial%')->count();
        $countBelajar = PengajuanKonseling::where('alasan_pengajuan', 'like', '%belajar%')->count();
        $countKarir = PengajuanKonseling::where('alasan_pengajuan', 'like', '%karir%')->count();

        $pemetaanBidang = [
            [
                'bidang' => 'Bimbingan Pribadi',
                'total' => $countPribadi,
                'persentase' => round(($countPribadi / $totalPengajuan) * 100, 1),
                'rekomendasi' => 'Peningkatan konseling individual, pembinaan motivasi diri dan karakter',
            ],
            [
                'bidang' => 'Bimbingan Sosial',
                'total' => $countSosial,
                'persentase' => round(($countSosial / $totalPengajuan) * 100, 1),
                'rekomendasi' => 'Program anti-perundungan (bullying) dan dinamika kelompok siswa',
            ],
            [
                'bidang' => 'Bimbingan Belajar',
                'total' => $countBelajar,
                'persentase' => round(($countBelajar / $totalPengajuan) * 100, 1),
                'rekomendasi' => 'Koordinasi dengan guru mata pelajaran untuk klinik belajar dan remedial',
            ],
            [
                'bidang' => 'Bimbingan Karir & PKL',
                'total' => $countKarir,
                'persentase' => round(($countKarir / $totalPengajuan) * 100, 1),
                'rekomendasi' => 'Penyuluhan persiapan dunia industri kerja dan konsultasi perguruan tinggi',
            ],
        ];

        return compact(
            'kepsek',
            'tahunAjaranAktif',
            'sesiList',
            'kinerjaGuru',
            'pemetaanBidang'
        );
    }

    /**
     * 5. DATA SISWA (School-Wide Read-Only)
     */
    public function indexSiswa(?Request $request = null)
    {
        $kepsek = $this->getCurrentKepsek();
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

        return view('kepsek.siswa.index', compact('kepsek', 'siswas', 'kelases', 'jurusans'));
    }
}
