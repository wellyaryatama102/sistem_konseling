{{-- VIEW DASHBOARD ADMIN: Beranda Statistik Master Data & Log Aktivitas Sistem --}}
@extends('layouts.app')
@section('title', 'Dashboard Administrator')

@section('content')

{{-- Header & Quick Actions --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
    <div>
        <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Dashboard Administrator</h2>
        <p style="margin:0.25rem 0 0 0; font-size:0.875rem; color:var(--text-muted);">
            Selamat datang! Ringkasan pengelolaan akun pengguna, master kelas, dan aktivitas notifikasi sistem.
        </p>
    </div>
    <div style="display:flex; gap:0.5rem;">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">+ Tambah Pengguna</a>
        <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary btn-sm">Kelola Kelas</a>
    </div>
</div>

{{-- Kartu Statistik Master Data (4 Cards Alignment) --}}
<div class="grid-4" style="margin-bottom:1.5rem;">
    {{-- Total Akun --}}
    <div class="stat-card">
        <span class="stat-lbl">Total Akun Terdaftar</span>
        <span class="stat-val">{{ $stats['total_users'] }}</span>
        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.35rem; font-weight:600;">
            <span style="color:var(--success);">{{ $stats['active_users'] }} Aktif</span> &bull; 
            <span style="color:var(--danger);">{{ $stats['inactive_users'] }} Nonaktif</span>
        </div>
    </div>

    {{-- Data Siswa --}}
    <div class="stat-card">
        <span class="stat-lbl">Total Siswa</span>
        <span class="stat-val" style="color:var(--primary);">{{ $stats['total_siswa'] }}</span>
        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.35rem; font-weight:600;">
            Terdaftar dalam Rombel Kelas
        </div>
    </div>

    {{-- Kelas & Jurusan --}}
    <div class="stat-card gold">
        <span class="stat-lbl">Rombel Kelas &amp; Jurusan</span>
        <span class="stat-val" style="color:var(--primary-dark);">{{ $stats['total_kelas'] }} / {{ $stats['total_jurusan'] }}</span>
        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.35rem; font-weight:600;">
            {{ $stats['total_kelas'] }} Kelas &bull; {{ $stats['total_jurusan'] }} Jurusan
        </div>
    </div>

    {{-- SDM Sekolah (Guru BK & Wali Kelas) --}}
    <div class="stat-card blue">
        <span class="stat-lbl">Guru BK &amp; Wali Kelas</span>
        <span class="stat-val" style="color:var(--info);">{{ $stats['guru_bk'] }} / {{ $stats['wali_kelas'] ?? 0 }}</span>
        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.35rem; font-weight:600;">
            {{ $stats['guru_bk'] }} Konselor &bull; {{ $stats['wali_kelas'] ?? 0 }} Wali Kelas
        </div>
    </div>
</div>

{{-- 2 Kolom Utama: Pengguna Terdaftar Terbaru & Log Notifikasi WA --}}
<div class="grid-2" style="gap:1.5rem; align-items:start;">
    
    {{-- Tabel Pengguna Terdaftar Terbaru --}}
    <div class="card" style="margin-bottom:0;">
        <div class="card-header" style="margin-bottom:0.875rem; padding-bottom:0.625rem; display:flex; justify-content:space-between; align-items:center;">
            <h3 class="card-title" style="font-size:1rem; margin:0;">Daftar Pengguna Terbaru</h3>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nama / Username</th>
                        <th>Jabatan / Hak Akses</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers as $user)
                    <tr>
                        <td>
                            <strong>{{ $user->name }}</strong><br>
                            <small style="color:var(--text-muted);">{{ $user->username }}</small>
                        </td>
                        <td>
                            <span style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--primary-dark);">
                                {{ str_replace('_', ' ', $user->role) }}
                            </span>
                        </td>
                        <td>
                            @if($user->status === 'active')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding:1.5rem; color:var(--text-muted);">
                            Belum ada data pengguna terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tabel Log Notifikasi WhatsApp Terkini --}}
    <div class="card" style="margin-bottom:0;">
        <div class="card-header" style="margin-bottom:0.875rem; padding-bottom:0.625rem; display:flex; justify-content:space-between; align-items:center;">
            <h3 class="card-title" style="font-size:1rem; margin:0;">Log Notifikasi WhatsApp Terkini</h3>
            <a href="{{ route('admin.log-aktivitas.index') }}" class="btn btn-secondary btn-sm">Lihat Semua Log</a>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Penerima / No. WA</th>
                        <th>Jenis Notifikasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLogs as $log)
                    <tr>
                        <td>
                            <strong>{{ $log->penerima_nama ?? 'Penerima' }}</strong><br>
                            <small style="color:var(--text-muted);">{{ $log->no_wa }}</small>
                        </td>
                        <td>
                            <span style="font-size:0.75rem; font-weight:600; color:var(--text-dark);">
                                {{ str_replace('_', ' ', strtoupper($log->jenis_notifikasi)) }}
                            </span>
                        </td>
                        <td>
                            @if($log->status === 'sent')
                                <span class="badge badge-success">Terkirim</span>
                            @elseif($log->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">Gagal</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding:1.5rem; color:var(--text-muted);">
                            Belum ada riwayat notifikasi terkirim.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection