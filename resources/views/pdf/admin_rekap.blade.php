<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Rekapitulasi Sistem - Administrator SMKN 2 Guguak</title>
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
        @if(($tipeRekap ?? 'layanan_konseling') == 'siswa_kelas')
            LAPORAN DATA ROMBONGAN BELAJAR &amp; DISTRIBUSI SISWA
        @else
            LAPORAN REKAPITULASI PELAYANAN BIMBINGAN &amp; KONSELING
        @endif
    </div>
    <div class="subtitle">
        @php
            $selectedTaName = !empty($request['id_tahun_ajaran']) ? ($tahunAjarans->firstWhere('id_tahun_ajaran', $request['id_tahun_ajaran'])->nama_tahun_ajaran ?? 'Semua') : 'Semua Tahun Ajaran';
            $semesterText = !empty($request['semester']) ? ' | Semester ' . ucfirst($request['semester']) : '';
            $bulanText = !empty($request['bulan']) ? ' | Bulan ' . $request['bulan'] : '';
        @endphp
        Tahun Ajaran: {{ $selectedTaName }}{{ $semesterText }}{{ $bulanText }} | Tanggal Cetak: {{ date('d F Y H:i') }} WIB
    </div>

    @if(($tipeRekap ?? 'layanan_konseling') == 'layanan_konseling')
        <table>
            <thead>
                <tr>
                    <th width="20">No</th>
                    <th width="65">Tanggal</th>
                    <th>Nama Siswa &amp; Kelas</th>
                    <th>Guru BK Pembimbing</th>
                    <th width="80">Layanan &amp; Sumber</th>
                    <th>Alasan Permasalahan</th>
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
    @else
        @forelse($kelasSummary as $k)
            <div style="background-color:#f1f5f9; border:1px solid #cbd5e1; padding:5px 8px; margin-bottom:5px; font-size:8pt; border-radius:3px;">
                <strong>Kelas:</strong> {{ $k->nama_kelas }} ({{ $k->tingkat_kelas }}) &nbsp;|&nbsp;
                <strong>Program Keahlian:</strong> {{ $k->jurusan->nama_jurusan ?? '-' }} &nbsp;|&nbsp;
                <strong>Tahun Ajaran:</strong> {{ $k->tahunAjaran->nama_tahun_ajaran ?? '-' }} &nbsp;|&nbsp;
                <strong>Wali Kelas:</strong> {{ $k->waliKelas->nama_lengkap ?? 'Belum Ditentukan' }} &nbsp;|&nbsp;
                <strong>Total Siswa:</strong> {{ $k->siswas_count }} (L: {{ $k->siswas_laki_count }}, P: {{ $k->siswas_perempuan_count }})
            </div>

            <table>
                <thead>
                    <tr>
                        <th width="20">No</th>
                        <th width="80">NIS / NISN</th>
                        <th>Nama Lengkap Siswa</th>
                        <th width="25">L/P</th>
                        <th width="90">Tempat, Tgl Lahir</th>
                        <th>Orang Tua / Kontak</th>
                        <th>Alamat</th>
                        <th width="50">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($k->siswas as $sIdx => $s)
                    @php
                        $tglLahir = $s->tanggal_lahir ? date('d/m/Y', strtotime($s->tanggal_lahir)) : '-';
                        $ttl = ($s->tempat_lahir ? $s->tempat_lahir . ', ' : '') . $tglLahir;
                    @endphp
                    <tr>
                        <td style="text-align:center;">{{ $sIdx + 1 }}</td>
                        <td>{{ $s->nis ?? '-' }}<br><small>{{ $s->nisn ?? '-' }}</small></td>
                        <td><strong>{{ $s->nama_siswa }}</strong></td>
                        <td style="text-align:center;">{{ $s->jenis_kelamin ?? '-' }}</td>
                        <td><small>{{ $ttl }}</small></td>
                        <td>{{ $s->nama_orang_tua_wali ?: '-' }}<br><small>WA: {{ $s->no_wa_orang_tua_wali ?: '-' }}</small></td>
                        <td><small>{{ $s->alamat ?: '-' }}</small></td>
                        <td style="text-align:center;">{{ strtoupper($s->status_siswa) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:8px;">Belum ada data siswa terdaftar pada kelas ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-bottom:12px;"></div>
        @empty
            <table>
                <tbody>
                    <tr>
                        <td style="text-align:center; padding:15px;">Tidak ada data rombongan belajar yang sesuai dengan filter.</td>
                    </tr>
                </tbody>
            </table>
        @endforelse
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
                Administrator Sistem SIKS<br><br><br><br>
                <strong><u>{{ auth()->user()->name ?? 'Administrator BK' }}</u></strong><br>
                NIP. {{ auth()->user()->admin->nip ?? '-' }}
            </td>
        </tr>
    </table>

</body>
</html>