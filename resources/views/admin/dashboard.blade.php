@extends('layouts.app')
@section('title', 'Dashboard Administrator')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Dashboard Administrator</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Pengelolaan akun 6 peran, data master akademik, dan pemantauan sistem SIKS SMKN 2 Guguak.</p>
</div>

<div class="grid-4" style="margin-bottom:1.5rem;">
    <div class="stat-card">
        <span class="stat-lbl">Total Akun Terdaftar</span>
        <span class="stat-val">{{ $stats['total_users'] }}</span>
    </div>
    <div class="stat-card">
        <span class="stat-lbl">Total Siswa Aktif</span>
        <span class="stat-val" style="color:var(--primary);">{{ $stats['total_siswa'] }}</span>
    </div>
    <div class="stat-card blue">
        <span class="stat-lbl">Guru BK</span>
        <span class="stat-val" style="color:var(--info);">{{ $stats['guru_bk'] }}</span>
    </div>
    <div class="stat-card gold">
        <span class="stat-lbl">Wali Kelas</span>
        <span class="stat-val" style="color:var(--accent-gold);">{{ $stats['wali_kelas'] }}</span>
    </div>
</div>

<div class="card" style="margin-bottom:1.5rem;">
    <h3 class="card-title" style="margin-bottom:1rem;">Distribusi Pengguna Berdasarkan Role</h3>
    <div class="grid-4">
        <div class="stat-card">
            <span class="stat-lbl">Wakil Kesiswaan</span>
            <span class="stat-val">{{ $stats['wakasis'] }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-lbl">Kepala Sekolah</span>
            <span class="stat-val">{{ $stats['kepala_sekolah'] }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-lbl">Akun Aktif</span>
            <span class="stat-val" style="color:var(--success);">{{ $stats['active_users'] }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-lbl">Akun Nonaktif</span>
            <span class="stat-val" style="color:var(--danger);">{{ $stats['inactive_users'] }}</span>
        </div>
    </div>
</div>

<div class="card">
    <h3 class="card-title" style="margin-bottom:1rem;">Navigasi Cepat Pengelolaan Sistem</h3>
    <div class="grid-4" style="gap:1rem;">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="text-align:center; justify-content:center; padding:1rem; font-weight:700;">
            👥 Kelola User & Hak Akses
        </a>
        <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary" style="text-align:center; justify-content:center; padding:1rem; font-weight:700;">
            🎓 Data Siswa Sekolah
        </a>
        <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary" style="text-align:center; justify-content:center; padding:1rem; font-weight:700;">
            🏫 Data Kelas & Jurusan
        </a>
        <a href="{{ route('admin.tahun-ajaran.index') }}" class="btn btn-secondary" style="text-align:center; justify-content:center; padding:1rem; font-weight:700;">
            📅 Tahun Ajaran
        </a>
    </div>
</div>

@endsection
