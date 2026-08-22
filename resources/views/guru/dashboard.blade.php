@extends('layouts.app')
@section('title', 'Dashboard Guru BK')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Dashboard Guru BK</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Ringkasan statistik dan aktivitas pelayanan konseling siswa SMKN 2 Guguak.</p>
</div>

{{-- Statistik Utama --}}
<div class="grid-4" style="margin-bottom:1.5rem;">
    <div class="stat-card">
        <span class="stat-lbl">Total Siswa Aktif</span>
        <span class="stat-val">{{ $stats['total_siswa'] }}</span>
    </div>
    <div class="stat-card gold">
        <span class="stat-lbl">Menunggu Verifikasi</span>
        <span class="stat-val" style="color:var(--accent-gold);">{{ $stats['pengajuan_menunggu'] }}</span>
    </div>
    <div class="stat-card blue">
        <span class="stat-lbl">Jadwal Hari Ini</span>
        <span class="stat-val" style="color:var(--info);">{{ $stats['jadwal_hari_ini'] }}</span>
    </div>
    <div class="stat-card">
        <span class="stat-lbl">Konseling Selesai</span>
        <span class="stat-val" style="color:var(--success);">{{ $stats['konseling_selesai'] }}</span>
    </div>
</div>

<div class="grid-2" style="gap:1.5rem; align-items:start;">
    {{-- Jadwal Konseling Terdekat --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Sesi Konseling Terdekat</h3>
            <a href="{{ route('guru.jadwal.index') }}" style="font-size:0.875rem; color:var(--primary); text-decoration:none; font-weight:700;">Lihat Semua &rarr;</a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Tanggal & Waktu</th>
                        <th>Status Sesi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwalTerdekat as $j)
                    <tr>
                        <td><strong>{{ $j->pengajuan->siswa->nama_siswa ?? '-' }}</strong></td>
                        <td>{{ $j->pengajuan->siswa->kelas->nama_kelas ?? '-' }}</td>
                        <td>
                            <small style="color:var(--primary-dark); font-weight:600;">{{ \Carbon\Carbon::parse($j->tanggal_pelaksanaan)->format('d/m/Y') }}</small><br>
                            <small style="color:var(--text-muted);">{{ $j->pengajuan && $j->pengajuan->jadwal ? substr($j->pengajuan->jadwal->jam_mulai, 0, 5) . ' WIB' : 'Insidental' }}</small>
                        </td>
                        <td>
                            @if($j->status_sesi == 'terjadwal')
                                <span class="badge badge-info">Terjadwal</span>
                            @elseif($j->status_sesi == 'selesai')
                                <span class="badge badge-success">Selesai</span>
                            @else
                                <span class="badge badge-warning">{{ strtoupper($j->status_sesi) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:1.5rem; color:var(--text-muted);">
                            Tidak ada jadwal konseling terdekat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pengajuan Terbaru --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pengajuan Konseling Terbaru</h3>
            <a href="{{ route('guru.pengajuan.index') }}" style="font-size:0.875rem; color:var(--primary); text-decoration:none; font-weight:700;">Lihat Semua &rarr;</a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Alasan Konseling</th>
                        <th>Tgl Pengajuan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPengajuan as $p)
                    <tr>
                        <td><strong>{{ $p->siswa->nama_siswa ?? '-' }}</strong></td>
                        <td style="max-width:180px; font-size:0.8rem;">
                            {{ \Illuminate\Support\Str::limit($p->alasan_pengajuan, 45) }}
                        </td>
                        <td><small style="color:var(--text-muted);">{{ $p->created_at ? $p->created_at->format('d/m/Y') : '-' }}</small></td>
                        <td>
                            @if($p->status_pengajuan == 'menunggu_validasi')
                                <span class="badge badge-warning">Menunggu</span>
                            @elseif($p->status_pengajuan == 'disetujui')
                                <span class="badge badge-success">Disetujui</span>
                            @else
                                <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:1.5rem; color:var(--text-muted);">
                            Belum ada pengajuan konseling terbaru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
