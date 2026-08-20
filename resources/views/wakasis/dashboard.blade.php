@extends('layouts.app')
@section('title', 'Dashboard Wakasis')

@section('content')

{{-- HEADER WAKASIS --}}
<div style="margin-bottom:1.5rem; background:white; padding:1.25rem 1.5rem; border-radius:0.5rem; border:1px solid var(--border-color);">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Dashboard Wakil Kesiswaan</h2>
    <div style="font-size:1rem; font-weight:700; color:var(--primary); margin-top:0.25rem;">Selamat datang, {{ $wakasis->nama_lengkap }}</div>
    <div style="font-size:0.875rem; color:var(--text-muted); margin-top:0.25rem;">
        Monitoring & Rekapitulasi Pelayanan Konseling Siswa Tingkat Sekolah — SMKN 2 Guguak
    </div>
</div>

{{-- STATISTIK UTAMA (6 Cards Dinamis Database) --}}
<div class="grid-4" style="margin-bottom:1.5rem;">
    <div class="stat-card">
        <span class="stat-lbl">Total Siswa Aktif</span>
        <span class="stat-val">{{ $stats['total_siswa'] }}</span>
    </div>
    <div class="stat-card blue">
        <span class="stat-lbl">Total Sesi Layanan</span>
        <span class="stat-val" style="color:var(--info);">{{ $stats['total_layanan'] }}</span>
    </div>
    <div class="stat-card">
        <span class="stat-lbl">Layanan Selesai</span>
        <span class="stat-val" style="color:var(--success);">{{ $stats['layanan_selesai'] }}</span>
    </div>
    <div class="stat-card gold">
        <span class="stat-lbl">Persentase Tuntas</span>
        <span class="stat-val" style="color:var(--accent-gold);">{{ $stats['persen_tuntas'] }}%</span>
    </div>
</div>

{{-- REKAPITULASI BERDASARKAN JURUSAN  --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-header">
        <h3 class="card-title">Rekapitulasi Layanan Konseling per Jurusan</h3>
        <a href="{{ route('wakasis.rekapitulasi.index') }}" class="btn btn-primary btn-sm">Laporan Lengkap &rarr;</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama Jurusan</th>
                    <th>Total Siswa</th>
                    <th>Total Sesi Konseling</th>
                    <th>Selesai Tuntas</th>
                    <th>Surat Panggilan Ortu</th>
                    <th>Tingkat Ketuntasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapJurusan as $rj)
                <tr>
                    <td><strong>{{ $rj['nama_jurusan'] }}</strong></td>
                    <td>{{ $rj['total_siswa'] }} Siswa</td>
                    <td><strong>{{ $rj['total_layanan'] }}</strong></td>
                    <td><span class="badge badge-success">{{ $rj['selesai'] }}</span></td>
                    <td><span class="badge badge-gold">{{ $rj['surat_ortu'] }} Surat</span></td>
                    <td>
                        <div style="display:flex; align-items:center; gap:0.5rem;">
                            <div style="flex:1; background:#E2E8F0; height:8px; border-radius:4px; overflow:hidden;">
                                <div style="width:{{ $rj['persen_tuntas'] }}%; background:var(--primary); height:100%;"></div>
                            </div>
                            <span style="font-weight:700; font-size:0.8rem;">{{ $rj['persen_tuntas'] }}%</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; padding:1.5rem; color:var(--text-muted);">Tidak ada data jurusan ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- REKAPITULASI BERDASARKAN KELAS --}}
<div class="card">
    <h3 class="card-title" style="margin-bottom:1rem;">Rekapitulasi Layanan per Rombongan Belajar (Kelas)</h3>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>Total Siswa</th>
                    <th>Total Sesi Konseling</th>
                    <th>Konseling Selesai</th>
                    <th>Panggilan Orang Tua</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapKelas as $rk)
                <tr>
                    <td><strong>{{ $rk['nama_kelas'] }}</strong></td>
                    <td>{{ $rk['jurusan'] }}</td>
                    <td>{{ $rk['total_siswa'] }} Siswa</td>
                    <td><strong>{{ $rk['total_layanan'] }}</strong></td>
                    <td><span class="badge badge-success">{{ $rk['selesai'] }}</span></td>
                    <td><span class="badge badge-gold">{{ $rk['surat_ortu'] }} Surat</span></td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; padding:1.5rem; color:var(--text-muted);">Tidak ada data kelas ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
