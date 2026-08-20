<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Rekapitulasi Monitoring Kesiswaan - SMKN 2 Guguak</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 9pt; line-height: 1.4; color: #111; margin: 15px 20px; }
        .header { text-align: center; border-bottom: 2.5px double #000; padding-bottom: 6px; margin-bottom: 12px; }
        .header h4, .header h3, .header h2, .header p { margin: 0; }
        .header h4 { font-size: 9pt; font-weight: normal; }
        .header h3 { font-size: 11pt; font-weight: bold; }
        .header h2 { font-size: 13pt; font-weight: 800; }
        .header p { font-size: 8pt; color: #333; margin-top: 2px; }
        .title { text-align: center; font-weight: bold; font-size: 11pt; margin: 10px 0 4px 0; text-transform: uppercase; }
        .subtitle { text-align: center; font-size: 8.5pt; color: #444; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 8.5pt; }
        th, td { border: 1px solid #333; padding: 5px 7px; text-align: left; }
        th { background-color: #f1f5f9; font-weight: bold; text-align: center; }
        .footer { width: 100%; margin-top: 25px; border: none; }
        .footer td { border: none; text-align: center; vertical-align: top; font-size: 9pt; }
    </style>
</head>
<body>

    @php
        $logoPath = public_path('images/logo_smk.png');
        $logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
    @endphp

    <table style="width: 100%; border-collapse: collapse; border: none; border-bottom: 2.5px double #000; padding-bottom: 6px; margin-bottom: 12px;">
        <tr>
            <td style="width: 70px; border: none; text-align: center; vertical-align: middle;">
                @if($logoBase64)
                    <img src="data:image/png;base64,{{ $logoBase64 }}" style="width: 60px; height: 60px;">
                @endif
            </td>
            <td style="border: none; text-align: center; vertical-align: middle;">
                <h4 style="margin: 0; font-size: 9.5pt; font-weight: bold; text-transform: uppercase;">PEMERINTAH PROVINSI SUMATERA BARAT</h4>
                <h3 style="margin: 0; font-size: 11pt; font-weight: bold; text-transform: uppercase;">DINAS PENDIDIKAN - CABANG DINAS WILAYAH IV</h3>
                <h2 style="margin: 0; font-size: 13.5pt; font-weight: 800; text-transform: uppercase;">SMK NEGERI 2 GUGUAK</h2>
                <p style="margin: 2px 0 0 0; font-size: 8pt; color: #333;">Jl. Raya Ampang Gadang, Kec. Guguak, Kab. Lima Puluh Kota, Sumatera Barat 26253</p>
            </td>
            <td style="width: 70px; border: none;"></td>
        </tr>
    </table>

    <div class="title">
        @if(($tipeRekap ?? 'rekap_sekolah') == 'rekap_jurusan')
            LAPORAN REKAPITULASI LAYANAN KONSELING PER JURUSAN & KELAS
        @else
            LAPORAN REKAPITULASI STATISTIK LAYANAN KESISWAAN SEKOLAH
        @endif
    </div>
    <div class="subtitle">
        Periode: {{ $periodeText ?? 'Semua Data' }} | Dicetak: {{ date('d F Y H:i') }} WIB
    </div>

    @if(($tipeRekap ?? 'rekap_sekolah') == 'rekap_jurusan')
        <table>
            <thead>
                <tr>
                    <th width="25">No</th>
                    <th>Jurusan</th>
                    <th>Nama Kelas</th>
                    <th>Wali Kelas</th>
                    <th width="75">Total Siswa</th>
                    <th width="85">Siswa Konseling</th>
                    <th width="65">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jurusanStats as $index => $js)
                <tr>
                    <td style="text-align:center;">{{ $index + 1 }}</td>
                    <td>{{ $js['jurusan'] }}</td>
                    <td>{{ $js['nama_kelas'] }}</td>
                    <td>{{ $js['wali_kelas'] }}</td>
                    <td style="text-align:center;">{{ $js['total_siswa'] }}</td>
                    <td style="text-align:center;">{{ $js['siswa_konseling'] }}</td>
                    <td style="text-align:center;">{{ $js['persentase'] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <table>
            <thead>
                <tr>
                    <th width="25">No</th>
                    <th width="70">Tanggal</th>
                    <th>Nama Siswa</th>
                    <th width="65">Kelas</th>
                    <th>Guru BK Pembimbing</th>
                    <th width="65">Jenis</th>
                    <th width="65">Status Sesi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sesiList as $index => $k)
                <tr>
                    <td style="text-align:center;">{{ $index + 1 }}</td>
                    <td style="text-align:center;">{{ \Carbon\Carbon::parse($k->tanggal_pelaksanaan)->format('d/m/Y') }}</td>
                    <td><strong>{{ $k->pengajuan->siswa->nama_siswa ?? '-' }}</strong></td>
                    <td style="text-align:center;">{{ $k->pengajuan->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $k->pengajuan && $k->pengajuan->jadwal && $k->pengajuan->jadwal->guruBk ? $k->pengajuan->jadwal->guruBk->nama_lengkap : 'Guru BK' }}</td>
                    <td style="text-align:center;">{{ ucfirst($k->pengajuan->jenis_konseling ?? 'Individu') }}</td>
                    <td style="text-align:center;">{{ strtoupper($k->status_sesi) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;">Tidak ada data rekapitulasi konseling.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <table class="footer">
        <tr>
            <td width="50%">
                Mengetahui,<br>
                Kepala SMK Negeri 2 Guguak<br><br><br><br>
                <strong><u>{{ $kepsek->nama_lengkap ?? 'Asvetinius, M.Pd' }}</u></strong><br>
                NIP. {{ $kepsek->nip ?? '19700101 199501 1 001' }}
            </td>
            <td width="50%">
                Guguak, {{ date('d F Y') }}<br>
                Wakil Kepala Sekolah Bidang Kesiswaan<br><br><br><br>
                <strong><u>{{ $wakasis->nama_lengkap ?? auth()->user()->name }}</u></strong><br>
                NIP. {{ $wakasis->nip ?? '-' }}
            </td>
        </tr>
    </table>

</body>
</html>
