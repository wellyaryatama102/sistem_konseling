<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Rekapitulasi Pelayanan Bimbingan Konseling - SMKN 2 Guguak</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 8.5pt; line-height: 1.4; color: #111; margin: 10px 15px; }
        .title { text-align: center; font-weight: bold; font-size: 11pt; margin: 8px 0 3px 0; text-transform: uppercase; }
        .subtitle { text-align: center; font-size: 8pt; color: #444; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 8pt; }
        th, td { border: 1px solid #333; padding: 4.5px 6px; text-align: left; }
        th { background-color: #f1f5f9; font-weight: bold; text-align: center; }
        .footer { width: 100%; margin-top: 20px; border: none; }
        .footer td { border: none; text-align: center; vertical-align: top; font-size: 8.5pt; }
    </style>
</head>
<body>

    <!-- PANGGIL KOP SURAT OTOMATIS -->
    @include('components.kop-surat')

    <div class="title">
        @if(($tipeRekap ?? 'layanan_konseling') == 'surat_panggilan')
            LAPORAN REKAPITULASI SURAT PANGGILAN ORANG TUA SISWA
        @else
            LAPORAN REKAPITULASI PELAYANAN BIMBINGAN &amp; KONSELING (LENGKAP)
        @endif
    </div>
    <div class="subtitle">
        @php
            $selectedTaName = !empty($request['id_tahun_ajaran']) ? ($tahunAjarans->firstWhere('id_tahun_ajaran', $request['id_tahun_ajaran'])->nama_tahun_ajaran ?? 'Semua') : 'Semua Tahun Ajaran';
            $bulanText = !empty($request['bulan']) ? ' | Bulan ' . $request['bulan'] : '';
        @endphp
        Guru BK: {{ $guru->nama_lengkap ?? auth()->user()->name }} | Tahun Ajaran: {{ $selectedTaName }}{{ $bulanText }} | Tanggal Cetak: {{ date('d F Y H:i') }} WIB
    </div>

    @if(($tipeRekap ?? 'layanan_konseling') == 'surat_panggilan')
        <table>
            <thead>
                <tr>
                    <th width="20">No</th>
                    <th width="100">No. Surat</th>
                    <th width="65">Tgl Terbit</th>
                    <th>Nama Siswa &amp; Kelas</th>
                    <th>Orang Tua / Wali (Kontak)</th>
                    <th>Perihal Pemanggilan</th>
                    <th width="85">Jadwal Menghadap</th>
                    <th width="65">Status WA</th>
                    <th width="65">Status Surat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suratList as $index => $s)
                @php
                    $siswaObj = $s->tindakLanjut->sesiKonseling->pengajuan->siswa ?? null;
                @endphp
                <tr>
                    <td style="text-align:center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $s->nomor_surat }}</strong></td>
                    <td style="text-align:center;">{{ $s->tanggal_terbit ? date('d/m/Y', strtotime($s->tanggal_terbit)) : '-' }}</td>
                    <td><strong>{{ $siswaObj->nama_siswa ?? '-' }}</strong><br><small>{{ $siswaObj->kelas->nama_kelas ?? '-' }} (NIS: {{ $siswaObj->nis ?? '-' }})</small></td>
                    <td>{{ $siswaObj->nama_orang_tua_wali ?: '-' }}<br><small>WA: {{ $siswaObj->no_wa_orang_tua_wali ?: '-' }}</small></td>
                    <td>{{ $s->perihal }}</td>
                    <td style="text-align:center;">{{ $s->tanggal_pertemuan ? date('d/m/Y', strtotime($s->tanggal_pertemuan)) : '-' }}<br><small>Pukul {{ $s->waktu_pertemuan ?: '-' }}</small></td>
                    <td style="text-align:center;">{{ strtoupper($s->status_kirim_wa) }}</td>
                    <td style="text-align:center;">{{ strtoupper($s->status_surat) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding:10px;">Tidak ada data surat panggilan orang tua yang sesuai dengan filter.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    @else
        <table>
            <thead>
                <tr>
                    <th width="20">No</th>
                    <th width="65">Tanggal</th>
                    <th>Nama Siswa &amp; Kelas</th>
                    <th>Guru BK Pembimbing</th>
                    <th width="80">Layanan &amp; Sumber</th>
                    <th>Permasalahan / Kasus</th>
                    <th width="60">Validasi</th>
                    <th width="60">Sesi</th>
                    <th width="75">Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($konselings as $index => $c)
                @php
                    $tindakLanjutObj = $c->sesiKonseling ? $c->sesiKonseling->tindakLanjuts->first() : null;
                    $jenisAksi = $tindakLanjutObj ? ucfirst(str_replace('_', ' ', $tindakLanjutObj->jenis_aksi)) : '-';
                @endphp
                <tr>
                    <td style="text-align:center;">{{ $index + 1 }}</td>
                    <td>{{ $c->tanggal_pengajuan ? $c->tanggal_pengajuan->format('d/m/Y') : '-' }}</td>
                    <td><strong>{{ $c->siswa->nama_siswa ?? '-' }}</strong><br><small>{{ $c->siswa->kelas->nama_kelas ?? '-' }} ({{ $c->siswa->nis ?? '-' }})</small></td>
                    <td>{{ $c->jadwal->guruBk->nama_lengkap ?? '-' }}</td>
                    <td style="text-align:center;">{{ ucfirst($c->jenis_konseling) }}<br><small>({{ ucfirst($c->sumber_pengajuan) }})</small></td>
                    <td>{{ \Illuminate\Support\Str::limit($c->alasan_pengajuan, 65) }}</td>
                    <td style="text-align:center;">{{ strtoupper(str_replace('_', ' ', $c->status_pengajuan)) }}</td>
                    <td style="text-align:center;">{{ $c->sesiKonseling ? strtoupper($c->sesiKonseling->status_sesi) : 'BELUM' }}</td>
                    <td style="text-align:center;">{{ $jenisAksi }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding:10px;">Tidak ada data pelayanan konseling yang sesuai dengan filter.</td>
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
                <strong><u>{{ $kepsek->nama_lengkap ?? 'Dr. Hj. Indrawati, M.Pd.' }}</u></strong><br>
                NIP. {{ $kepsek->nip ?? '19681125 199403 2 001' }}
            </td>
            <td width="50%">
                Guguak, {{ date('d F Y') }}<br>
                Guru Bimbingan dan Konseling<br><br><br><br>
                <strong><u>{{ $guru->nama_lengkap ?? auth()->user()->name }}</u></strong><br>
                NIP. {{ $guru->nip ?? '-' }}
            </td>
        </tr>
    </table>

</body>
</html>