<table>
    {{-- KOP SURAT RESMI SEKOLAH --}}
    <tr>
        <td colspan="10" style="text-align: center; font-weight: bold; font-size: 13pt;">PEMERINTAH PROVINSI SUMATERA BARAT</td>
    </tr>
    <tr>
        <td colspan="10" style="text-align: center; font-weight: bold; font-size: 14pt;">DINAS PENDIDIKAN - CABANG DINAS WILAYAH IV</td>
    </tr>
    <tr>
        <td colspan="10" style="text-align: center; font-weight: bold; font-size: 16pt;">SMK NEGERI 2 GUGUAK</td>
    </tr>
    <tr>
        <td colspan="10" style="text-align: center; font-size: 9pt; font-style: italic;">Jl. Raya Ampang Gadang, Kec. Guguak, Kab. Lima Puluh Kota, Sumatera Barat 26253</td>
    </tr>
    <tr>
        <td colspan="10" style="border-bottom: 2px solid #000000;"></td>
    </tr>
    <tr><td colspan="10"></td></tr>

    {{-- JUDUL LAPORAN --}}
    <tr>
        <td colspan="10" style="text-align: center; font-weight: bold; font-size: 13pt;">
            @if(($tipeRekap ?? 'layanan_konseling') == 'surat_panggilan')
                LAPORAN REKAPITULASI SURAT PANGGILAN ORANG TUA SISWA
            @else
                LAPORAN REKAPITULASI PELAYANAN BIMBINGAN &amp; KONSELING (LENGKAP)
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="10" style="text-align: center; font-size: 10pt;">
            Guru BK: {{ $guru->nama_lengkap ?? auth()->user()->name }} | Tanggal Cetak: {{ date('d/m/Y H:i') }} WIB
        </td>
    </tr>
    <tr><td colspan="10"></td></tr>

    {{-- KONTEN TABEL BERDASARKAN TIPE REKAP --}}
    @if(($tipeRekap ?? 'layanan_konseling') == 'surat_panggilan')
        <thead>
            <tr>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">No</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Nomor Surat</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Tgl Terbit</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Nama Siswa</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">NIS</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Kelas</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Nama Orang Tua / Wali</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">No. WA Ortu</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Perihal Pemanggilan</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Jadwal Pertemuan</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Status WA</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Status Surat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suratList as $index => $s)
            @php
                $siswaObj = $s->tindakLanjut->sesiKonseling->pengajuan->siswa ?? null;
            @endphp
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000;">{{ $s->nomor_surat }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $s->tanggal_terbit ? date('d/m/Y', strtotime($s->tanggal_terbit)) : '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $siswaObj->nama_siswa ?? '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $siswaObj->nis ?? '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $siswaObj->kelas->nama_kelas ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $siswaObj->nama_orang_tua_wali ?: '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $siswaObj->no_wa_orang_tua_wali ?: '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $s->perihal }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $s->tanggal_pertemuan ? date('d/m/Y', strtotime($s->tanggal_pertemuan)) : '-' }} ({{ $s->waktu_pertemuan ?: '-' }})</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ strtoupper($s->status_kirim_wa) }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ strtoupper($s->status_surat) }}</td>
            </tr>
            @endforeach
        </tbody>
    @else
        <thead>
            <tr>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">No</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Tanggal</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Nama Siswa</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">NIS</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Kelas &amp; Jurusan</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Guru BK Pembimbing</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Layanan &amp; Sumber</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Permasalahan</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Validasi</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Sesi Konseling</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Tindak Lanjut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($konselings as $index => $c)
            @php
                $tindakLanjutObj = $c->sesiKonseling ? $c->sesiKonseling->tindakLanjuts->first() : null;
                $jenisAksi = $tindakLanjutObj ? ucfirst(str_replace('_', ' ', $tindakLanjutObj->jenis_aksi)) : '-';
            @endphp
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{ $index + 1 }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $c->tanggal_pengajuan ? $c->tanggal_pengajuan->format('d/m/Y') : '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $c->siswa->nama_siswa ?? '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $c->siswa->nis ?? '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $c->siswa->kelas->nama_kelas ?? '-' }} ({{ $c->siswa->kelas->jurusan->nama_jurusan ?? '-' }})</td>
                <td style="border: 1px solid #000000;">{{ $c->jadwal->guruBk->nama_lengkap ?? '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ ucfirst($c->jenis_konseling) }} / {{ ucfirst($c->sumber_pengajuan) }}</td>
                <td style="border: 1px solid #000000;">{{ $c->alasan_pengajuan ?? '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ strtoupper(str_replace('_', ' ', $c->status_pengajuan)) }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $c->sesiKonseling ? strtoupper($c->sesiKonseling->status_sesi) : 'BELUM TERJADWAL' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $jenisAksi }}</td>
            </tr>
            @endforeach
        </tbody>
    @endif

    <tr><td colspan="10"></td></tr>
    <tr><td colspan="10"></td></tr>

    {{-- TANDA TANGAN PEJABAT --}}
    <tr>
        <td colspan="4" style="text-align: center;">
            Mengetahui,<br>
            Kepala SMK Negeri 2 Guguak<br><br><br><br>
            <strong><u>{{ $kepsek->nama_lengkap ?? 'Dr. Hj. Indrawati, M.Pd.' }}</u></strong><br>
            NIP. {{ $kepsek->nip ?? '19681125 199403 2 001' }}
        </td>
        <td colspan="2"></td>
        <td colspan="4" style="text-align: center;">
            Guguak, {{ date('d F Y') }}<br>
            Guru Bimbingan dan Konseling<br><br><br><br>
            <strong><u>{{ $guru->nama_lengkap ?? auth()->user()->name }}</u></strong><br>
            NIP. {{ $guru->nip ?? '-' }}
        </td>
    </tr>
</table>
