{{-- VIEW DASHBOARD ADMIN: Beranda statistik master data akun, kelas, siswa, & log aktivitas sistem --}}
@extends('layouts.app')
@section('title', 'Dashboard Administrator')

@section('content')

{{-- Header --}}
<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Dashboard Administrator</h2>
</div>

{{-- Kartu Statistik Utama (4 Card Ringkas) --}}
<div class="grid-4" style="margin-bottom:1.5rem;">
    <div class="stat-card">
        <span class="stat-lbl">Total Akun Terdaftar</span>
        <span class="stat-val">{{ $stats['total_users'] }}</span>
        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.35rem; font-weight:600;">
            <span style="color:var(--success);">{{ $stats['active_users'] }} Aktif</span> &bull; 
            <span style="color:var(--danger);">{{ $stats['inactive_users'] }} Nonaktif</span>
        </div>
    </div>

    <div class="stat-card">
        <span class="stat-lbl">Total Siswa Aktif</span>
        <span class="stat-val" style="color:var(--primary);">{{ $stats['total_siswa'] }}</span>
        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.35rem; font-weight:600;">
            Siswa Terdaftar di Sekolah
        </div>
    </div>

    <div class="stat-card gold">
        <span class="stat-lbl">Data Kelas &amp; Jurusan</span>
        <span class="stat-val" style="color:var(--primary-dark);">{{ $stats['total_kelas'] }} / {{ $stats['total_jurusan'] }}</span>
        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.35rem; font-weight:600;">
            {{ $stats['total_kelas'] }} Rombel Kelas &bull; {{ $stats['total_jurusan'] }} Jurusan
        </div>
    </div>

    <div class="stat-card blue">
        <span class="stat-lbl">Guru Konseling (BK)</span>
        <span class="stat-val" style="color:var(--info);">{{ $stats['guru_bk'] }}</span>
        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.35rem; font-weight:600;">
            Konselor BK Terdaftar
        </div>
    </div>
</div>

{{-- 2 Kolom Utama: Pengguna Terdaftar & Log Notifikasi --}}
<div class="grid-2" style="gap:1.5rem; align-items:start;">
    {{-- Tabel Pengguna Terdaftar Terbaru --}}
    <div class="card" style="margin-bottom:0;">
        <div class="card-header" style="margin-bottom:0.875rem; padding-bottom:0.625rem;">
            <h3 class="card-title" style="font-size:1rem;">Daftar Pengguna Terbaru</h3>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nama / Username</th>
                        <th>Peran</th>
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
        <div class="card-header" style="margin-bottom:0.875rem; padding-bottom:0.625rem;">
            <h3 class="card-title" style="font-size:1rem;">Log Notifikasi WhatsApp Terkini</h3>
            <a href="{{ route('admin.log-aktivitas.index') }}" class="btn btn-secondary btn-sm">Lihat Semua Log</a>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Penerima / Nomor</th>
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
