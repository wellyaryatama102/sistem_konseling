<table style="width: 100%; border-collapse: collapse;">
    {{-- Panggil Kop versi Excel dengan 7 Kolom --}}
    @include('components.kop-surat', ['isExcel' => true, 'colspan' => 7])

    <tr>
        <td colspan="7" style="text-align: center; font-weight: bold; font-size: 13pt; border: none;">
            @if($tipeRekap == 'rekap_jurusan')
                LAPORAN REKAPITULASI LAYANAN KONSELING PER JURUSAN &amp; KELAS
            @else
                LAPORAN REKAPITULASI STATISTIK LAYANAN KESISWAAN SEKOLAH
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="7" style="text-align: center; font-size: 10pt; border: none;">
            Periode: {{ $periodeText ?? 'Semua Data' }} | Tanggal Cetak: {{ date('d/m/Y H:i') }} WIB
        </td>
    </tr>
    <tr><td colspan="7" style="border: none; height: 10px;"></td></tr>

    @if($tipeRekap == 'rekap_jurusan')
        <thead>
            <tr>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">No</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Jurusan</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Nama Kelas</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; border: 1px solid #000000;">Wali Kelas</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Total Siswa</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Siswa Konseling</th>
                <th style="background-color: #1B4D3E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jurusanStats as $index => $js)
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000;">{{ $js['jurusan'] }}</td>
                <td style="border: 1px solid #000000;">{{ $js['nama_kelas'] }}</td>
                <td style="border: 1px solid #000000;">{{ $js['wali_kelas'] }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $js['total_siswa'] }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $js['siswa_konseling'] }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $js['persentase'] }}%</td>
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
            @forelse($sesiList as $index => $k)
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{ $index + 1 }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ \Carbon\Carbon::parse($k->tanggal_pelaksanaan)->format('d/m/Y') }}</td>
                <td style="border: 1px solid #000000;">{{ $k->pengajuan->siswa->nama_siswa ?? '-' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $k->pengajuan->siswa->kelas->nama_kelas ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $k->pengajuan && $k->pengajuan->jadwal && $k->pengajuan->jadwal->guruBk ? $k->pengajuan->jadwal->guruBk->nama_lengkap : 'Guru BK' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ ucfirst($k->pengajuan->jenis_konseling ?? 'Individu') }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ strtoupper($k->status_sesi) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; border: 1px solid #000000;">Tidak ada data rekapitulasi.</td>
            </tr>
            @endforelse
        </tbody>
    @endif
</table>