@extends('layouts.app')
@section('title', 'Jadwal Konseling Siswa')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Jadwal Konseling Siswa Kelas {{ $kelas->nama_kelas }}</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Informasi dan jadwal layanan konseling siswa binaan kelas Anda.</p>
</div>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Tanggal Konseling</th>
                    <th>Waktu Pertemuan</th>
                    <th>Jenis Layanan</th>
                    <th>Status Sesi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $j)
                <tr>
                    <td><strong>{{ $j->pengajuan->siswa->nama_siswa ?? '-' }}</strong></td>
                    <td>{{ $kelas->nama_kelas }}</td>
                    <td><small style="font-weight:700; color:var(--primary-dark);">{{ \Carbon\Carbon::parse($j->tanggal_pelaksanaan)->format('d/m/Y') }}</small></td>
                    <td><small style="color:var(--primary); font-weight:600;">{{ $j->pengajuan && $j->pengajuan->jadwal ? substr($j->pengajuan->jadwal->jam_mulai, 0, 5) . ' WIB' : 'Insidental' }}</small></td>
                    <td><span class="badge badge-info">{{ ucfirst($j->pengajuan->jenis_konseling ?? 'Individu') }}</span></td>
                    <td>
                        @if($j->status_sesi == 'terjadwal')
                            <span class="badge badge-warning">Terjadwal</span>
                        @elseif($j->status_sesi == 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @elseif($j->status_sesi == 'dibatalkan')
                            <span class="badge badge-danger">Dibatalkan</span>
                        @else
                            <span class="badge badge-info">{{ strtoupper($j->status_sesi) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:2rem; color:var(--text-muted);">
                        Belum ada jadwal konseling terdaftar untuk siswa di kelas ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.25rem;">
        {{ $jadwals->links() }}
    </div>
</div>

@endsection
