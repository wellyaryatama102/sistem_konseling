<table>
    {{-- KOP SURAT RESMI SEKOLAH --}}
    <tr>
        <td colspan="7" style="text-align: center; font-weight: bold; font-size: 13pt;">PEMERINTAH PROVINSI SUMATERA BARAT</td>
    </tr>
    <tr>
        <td colspan="7" style="text-align: center; font-weight: bold; font-size: 14pt;">DINAS PENDIDIKAN - CABANG DINAS WILAYAH IV</td>
    </tr>
    <tr>
        <td colspan="7" style="text-align: center; font-weight: bold; font-size: 16pt;">SMK NEGERI 2 GUGUAK</td>
    </tr>
    <tr>
        <td colspan="7" style="text-align: center; font-size: 9pt; font-style: italic;">Jl. Raya Ampang Gadang, Kec. Guguak, Kab. Lima Puluh Kota, Sumatera Barat 26253</td>
    </tr>
    <tr>
        <td colspan="7" style="border-bottom: 2px solid #000000;"></td>
    </tr>
    <tr><td colspan="7"></td></tr>

    {{-- JUDUL LAPORAN --}}
    <tr>
        <td colspan="7" style="text-align: center; font-weight: bold; font-size: 13pt;">
            @if($tipeRekap == 'kinerja_guru_bk')
                LAPORAN EKSEKUTIF EVALUASI KINERJA GURU BIMBINGAN KONSELING
            @elseif($tipeRekap == 'pemetaan_bidang')
                LAPORAN PEMETAAN BIDANG &amp; MASALAH BIMBINGAN SISWA
            @else
                LAPORAN EKSEKUTIF PELAYANAN BIMBINGAN DAN KONSELING SEKOLAH
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="7" style="text-align: center; font-size: 10pt;">
            Tahun Ajaran: {{ $tahunAjaranAktif->nama_tahun_ajaran ?? '2026/2027' }} | Tanggal Cetak: {{ date('d/m/Y H:i') }} WIB
        </td>
    </tr>
    <tr><td colspan="7"></td></tr>

    {{-- KONTEN TABEL BERDASARKAN TIPE REKAP --}}
    @if($tipeRekap == 'kinerja_guru_bk')
        <thead>
            <tr>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">No</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Nama Guru BK</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">NIP</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Slot Dibuka</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Sesi Terlaksana</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Tindak Lanjut Selesai</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Efektivitas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kinerjaGuru as $index => $kg)
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000;">{{ $kg['nama'] }}</td>
                <td style="border: 1px solid #000000;">{{ $kg['nip'] ?? '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $kg['slot_dibuka'] }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $kg['sesi_selesai'] }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $kg['tindak_lanjut'] }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $kg['efektivitas'] }}%</td>
            </tr>
            @endforeach
        </tbody>
    @elseif($tipeRekap == 'pemetaan_bidang')
        <thead>
            <tr>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">No</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Bidang Bimbingan</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Jumlah Kasus / Konseling</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Persentase</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;" colspan="3">Keterangan / Rekomendasi Program</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemetaanBidang as $index => $pb)
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000;">{{ $pb['bidang'] }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $pb['total'] }} Kasus</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $pb['persentase'] }}%</td>
                <td style="border: 1px solid #000000;" colspan="3">{{ $pb['rekomendasi'] }}</td>
            </tr>
            @endforeach
        </tbody>
    @else
        <thead>
            <tr>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">No</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Tanggal</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Nama Siswa</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Kelas</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Guru BK Pembimbing</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Jenis Layanan</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Status Sesi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sesiList as $index => $k)
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{ $index + 1 }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ \Carbon\Carbon::parse($k->tanggal_pelaksanaan)->format('d/m/Y') }}</td>
                <td style="border: 1px solid #000000;">{{ $k->pengajuan->siswa->nama_siswa ?? '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $k->pengajuan->siswa->kelas->nama_kelas ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $k->pengajuan && $k->pengajuan->jadwal && $k->pengajuan->jadwal->guruBk ? $k->pengajuan->jadwal->guruBk->nama_lengkap : 'Guru BK' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ ucfirst($k->pengajuan->jenis_konseling ?? 'Individu') }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ strtoupper($k->status_sesi) }}</td>
            </tr>
            @endforeach
        </tbody>
    @endif

    {{-- LEMBAR TANDA TANGAN / PENGESAHAN --}}
    <tr><td colspan="7"></td></tr>
    <tr><td colspan="7"></td></tr>
    <tr>
        <td colspan="4"></td>
        <td colspan="3" style="text-align: center;">
            Guguak, {{ date('d F Y') }}<br>
            Kepala SMK Negeri 2 Guguak<br><br><br><br><br>
            <strong>{{ $kepsek->nama_lengkap ?? auth()->user()->name }}</strong><br>
            NIP. {{ $kepsek->nip ?? '19700101 199501 1 001' }}
        </td>
    </tr>
</table>
