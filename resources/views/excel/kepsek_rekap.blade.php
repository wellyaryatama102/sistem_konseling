<table style="width: 100%; border-collapse: collapse;">
    {{-- Panggil Kop versi Excel dengan 7 Kolom --}}
    @include('components.kop-surat', ['isExcel' => true, 'colspan' => 7])

    <tr>
        <td colspan="7" style="text-align: center; font-weight: bold; font-size: 13pt; border: none;">
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
        <td colspan="7" style="text-align: center; font-size: 10pt; border: none;">
            Tahun Ajaran: {{ $tahunAjaranAktif->nama_tahun_ajaran ?? '2026/2027' }} | Tanggal Cetak: {{ date('d/m/Y H:i') }} WIB
        </td>
    </tr>
    <tr><td colspan="7" style="border: none; height: 10px;"></td></tr>

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
</table>