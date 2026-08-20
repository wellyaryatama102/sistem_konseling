@extends('layouts.app')
@section('title', 'Dashboard Eksekutif Kepala Sekolah')

@section('content')

{{-- HEADER KEPALA SEKOLAH --}}
<div style="margin-bottom:1.5rem; background:white; padding:1.25rem 1.5rem; border-radius:0.5rem; border:1px solid var(--border-color);">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Executive Dashboard Kepala Sekolah</h2>
    <div style="font-size:1rem; font-weight:700; color:var(--primary); margin-top:0.25rem;">Selamat datang, {{ $kepsek->nama_lengkap }}</div>
    <div style="font-size:0.875rem; color:var(--text-muted); margin-top:0.25rem;">
        Sistem Informasi Konseling Siswa (SIKS) — SMK Negeri 2 Guguak
    </div>
</div>

{{-- STATISTIK UTAMA (Executive KPI Cards) --}}
<div class="grid-4" style="margin-bottom:1.5rem;">
    <div class="stat-card">
        <span class="stat-lbl">Total Siswa Aktif</span>
        <span class="stat-val">{{ $stats['total_siswa'] }}</span>
    </div>
    <div class="stat-card blue">
        <span class="stat-lbl">Total Sesi Dilaksanakan</span>
        <span class="stat-val" style="color:var(--info);">{{ $stats['total_sesi'] }}</span>
    </div>
    <div class="stat-card">
        <span class="stat-lbl">Layanan Selesai Tuntas</span>
        <span class="stat-val" style="color:var(--success);">{{ $stats['sesi_selesai'] }}</span>
    </div>
    <div class="stat-card gold">
        <span class="stat-lbl">Tingkat Ketuntasan</span>
        <span class="stat-val" style="color:var(--accent-gold);">{{ $stats['persen_tuntas'] }}%</span>
    </div>
</div>

{{-- KINERJA GURU BK 
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-header">
        <h3 class="card-title">Monitoring Kinerja Tim Guru Bimbingan Konseling</h3>
        <a href="{{ route('kepsek.kinerja.index') }}" class="btn btn-primary btn-sm">Lihat Detail Kinerja &rarr;</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama Guru BK</th>
                    <th>NIP</th>
                    <th>Slot Waktu Dibuka</th>
                    <th>Sesi Konseling Ditangani</th>
                    <th>Layanan Selesai</th>
                    <th>Panggilan Orang Tua</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kinerjaGuru as $kg)
                <tr>
                    <td><strong>{{ $kg['nama_guru'] }}</strong></td>
                    <td><code>{{ $kg['nip'] }}</code></td>
                    <td>{{ $kg['total_slot'] }} Slot</td>
                    <td><strong>{{ $kg['total_layanan'] }} Sesi</strong></td>
                    <td><span class="badge badge-success">{{ $kg['selesai'] }}</span></td>
                    <td><span class="badge badge-gold">{{ $kg['surat_ortu'] }} Surat</span></td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; padding:1.5rem; color:var(--text-muted);">Belum ada data kinerja Guru BK.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- PEMETAAN BIDANG LAYANAN --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Pemetaan Bidang Layanan Konseling</h3>
        <a href="{{ route('kepsek.pemetaan.index') }}" style="font-size:0.875rem; color:var(--primary); font-weight:700; text-decoration:none;">Analisis Lengkap &rarr;</a>
    </div>

    <div class="grid-3" style="gap:1rem; margin-top:0.5rem;">
        @foreach($pemetaanBidang as $pb)
        <div style="background:#F8FAFC; border:1px solid var(--border-color); border-radius:0.5rem; padding:1rem;">
            <div style="font-size:0.875rem; font-weight:700; color:var(--primary-dark);">{{ $pb['nama'] }}</div>
            <div style="font-size:1.5rem; font-weight:800; color:var(--primary); margin:0.35rem 0;">{{ $pb['count'] }} <span style="font-size:0.875rem; font-weight:500; color:var(--text-muted);">Pengajuan</span></div>
            <small style="color:var(--text-muted);">Berdasarkan data kebutuhan layanan siswa</small>
        </div>
        @endforeach
    </div>
</div>

@endsection
