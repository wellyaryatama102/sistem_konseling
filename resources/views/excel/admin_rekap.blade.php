<table>
    {{-- KOP SURAT RESMI SEKOLAH --}}
    <tr>
        <td colspan="{{ ($tipeRekap ?? 'layanan_konseling') == 'siswa_kelas' ? 9 : 15 }}" style="text-align: center; font-weight: bold; font-size: 13pt;">PEMERINTAH PROVINSI SUMATERA BARAT</td>
    </tr>
    <tr>
        <td colspan="{{ ($tipeRekap ?? 'layanan_konseling') == 'siswa_kelas' ? 9 : 15 }}" style="text-align: center; font-weight: bold; font-size: 14pt;">DINAS PENDIDIKAN</td>
    </tr>
    <tr>
        <td colspan="{{ ($tipeRekap ?? 'layanan_konseling') == 'siswa_kelas' ? 9 : 15 }}" style="text-align: center; font-weight: bold; font-size: 16pt;">SMK NEGERI 2 GUGUAK</td>
    </tr>
    <tr>
        <td colspan="{{ ($tipeRekap ?? 'layanan_konseling') == 'siswa_kelas' ? 9 : 15 }}" style="text-align: center; font-size: 9pt; font-style: italic;">Jl. Raya Ampang Gadang, Kec. Guguak, Kab. Lima Puluh Kota, Sumatera Barat 26253</td>
    </tr>
    <tr>
        <td colspan="{{ ($tipeRekap ?? 'layanan_konseling') == 'siswa_kelas' ? 9 : 15 }}" style="border-bottom: 2px solid #000000;"></td>
    </tr>
    <tr><td colspan="{{ ($tipeRekap ?? 'layanan_konseling') == 'siswa_kelas' ? 9 : 15 }}"></td></tr>

    {{-- JUDUL LAPORAN --}}
    <tr>
        <td colspan="{{ ($tipeRekap ?? 'layanan_konseling') == 'siswa_kelas' ? 9 : 15 }}" style="text-align: center; font-weight: bold; font-size: 13pt;">
            @if(($tipeRekap ?? 'layanan_konseling') == 'siswa_kelas')
                LAPORAN DATA ROMBONGAN BELAJAR &amp; DISTRIBUSI SISWA
            @else
                LAPORAN REKAPITULASI PELAYANAN BIMBINGAN &amp; KONSELING LENGKAP
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="{{ ($tipeRekap ?? 'layanan_konseling') == 'siswa_kelas' ? 9 : 15 }}" style="text-align: center; font-size: 10pt;">
            @php
                $selectedTaName = !empty($request['id_tahun_ajaran']) ? ($tahunAjarans->firstWhere('id_tahun_ajaran', $request['id_tahun_ajaran'])->nama_tahun_ajaran ?? 'Semua') : 'Semua Tahun Ajaran';
                $semesterText = !empty($request['semester']) ? ' | Semester ' . ucfirst($request['semester']) : '';
                $bulanText = !empty($request['bulan']) ? ' | Bulan ' . $request['bulan'] : '';
            @endphp
            Tahun Ajaran: {{ $selectedTaName }}{{ $semesterText }}{{ $bulanText }} | Tanggal Cetak: {{ date('d/m/Y H:i') }} WIB
        </td>
    </tr>
    <tr><td colspan="{{ ($tipeRekap ?? 'layanan_konseling') == 'siswa_kelas' ? 9 : 15 }}"></td></tr>

    {{-- KONTEN TABEL BERDASARKAN TIPE REKAP --}}
    @if(($tipeRekap ?? 'layanan_konseling') == 'layanan_konseling')
        <thead>
            <tr>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">No</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Tgl Pengajuan</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">NIS</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Nama Siswa</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Kelas &amp; Jurusan</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Wali Kelas</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Guru BK Pembimbing</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Jenis Layanan</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Sumber</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Alasan Permasalahan</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Catatan Rujukan</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Status Validasi</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Status Sesi</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Hasil Konseling</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Tindak Lanjut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($konselings as $index => $c)
            @php
                $waliKelasNama = $c->siswa->kelas->waliKelas->nama_lengkap ?? ($c->waliKelas->nama_lengkap ?? '-');
                $tindakLanjutObj = $c->sesiKonseling ? $c->sesiKonseling->tindakLanjuts->first() : null;
                $jenisAksi = $tindakLanjutObj ? ucfirst(str_replace('_', ' ', $tindakLanjutObj->jenis_aksi)) : '-';
            @endphp
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000;">{{ $c->tanggal_pengajuan ? $c->tanggal_pengajuan->format('d/m/Y H:i') : '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $c->siswa->nis ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $c->siswa->nama_siswa ?? 'Siswa Dihapus' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $c->siswa->kelas->nama_kelas ?? '-' }} ({{ $c->siswa->kelas->jurusan->nama_jurusan ?? '-' }})</td>
                <td style="border: 1px solid #000000;">{{ $waliKelasNama }}</td>
                <td style="border: 1px solid #000000;">{{ $c->jadwal->guruBk->nama_lengkap ?? '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ ucfirst($c->jenis_konseling) }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ ucfirst(str_replace('_', ' ', $c->sumber_pengajuan)) }}</td>
                <td style="border: 1px solid #000000;">{{ $c->alasan_pengajuan }}</td>
                <td style="border: 1px solid #000000;">{{ $c->alasan_rujukan ?: '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ strtoupper(str_replace('_', ' ', $c->status_pengajuan)) }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $c->sesiKonseling ? strtoupper($c->sesiKonseling->status_sesi) : 'BELUM ADA' }}</td>
                <td style="border: 1px solid #000000;">{{ $c->sesiKonseling->hasil_konseling ?? '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $jenisAksi }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="15" style="text-align: center; border: 1px solid #000000;">Tidak ada data pelayanan konseling yang sesuai dengan filter.</td>
            </tr>
            @endforelse
        </tbody>
    @else
        <thead>
            <tr>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">No</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Nama Kelas</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Tingkat</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Program Keahlian</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Tahun Ajaran</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Wali Kelas</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">NIS</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">NISN</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Nama Lengkap Siswa</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">L/P</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Tempat, Tanggal Lahir</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Nama Orang Tua / Wali</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">No WA Orang Tua</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Alamat</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Status Siswa</th>
            </tr>
        </thead>
        <tbody>
            @php $globalIdx = 1; @endphp
            @forelse($kelasSummary as $k)
                @foreach($k->siswas as $s)
                @php
                    $tglLahir = $s->tanggal_lahir ? date('d/m/Y', strtotime($s->tanggal_lahir)) : '-';
                    $ttl = ($s->tempat_lahir ? $s->tempat_lahir . ', ' : '') . $tglLahir;
                @endphp
                <tr>
                    <td style="text-align: center; border: 1px solid #000000;">{{ $globalIdx++ }}</td>
                    <td style="border: 1px solid #000000;"><strong>{{ $k->nama_kelas }}</strong></td>
                    <td style="text-align: center; border: 1px solid #000000;">{{ $k->tingkat_kelas }}</td>
                    <td style="border: 1px solid #000000;">{{ $k->jurusan->nama_jurusan ?? '-' }}</td>
                    <td style="text-align: center; border: 1px solid #000000;">{{ $k->tahunAjaran->nama_tahun_ajaran ?? '-' }}</td>
                    <td style="border: 1px solid #000000;">{{ $k->waliKelas->nama_lengkap ?? '-' }}</td>
                    <td style="border: 1px solid #000000;">{{ $s->nis ?? '-' }}</td>
                    <td style="border: 1px solid #000000;">{{ $s->nisn ?? '-' }}</td>
                    <td style="border: 1px solid #000000;"><strong>{{ $s->nama_siswa }}</strong></td>
                    <td style="text-align: center; border: 1px solid #000000;">{{ $s->jenis_kelamin ?? '-' }}</td>
                    <td style="border: 1px solid #000000;">{{ $ttl }}</td>
                    <td style="border: 1px solid #000000;">{{ $s->nama_orang_tua_wali ?: '-' }}</td>
                    <td style="border: 1px solid #000000;">{{ $s->no_wa_orang_tua_wali ?: '-' }}</td>
                    <td style="border: 1px solid #000000;">{{ $s->alamat ?: '-' }}</td>
                    <td style="text-align: center; border: 1px solid #000000;">{{ strtoupper($s->status_siswa) }}</td>
                </tr>
                @endforeach
            @empty
            <tr>
                <td colspan="15" style="text-align: center; border: 1px solid #000000;">Tidak ada data rombongan belajar yang sesuai dengan filter.</td>
            </tr>
            @endforelse
        </tbody>
    @endif
</table>
