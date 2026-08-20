@extends('layouts.app')
@section('title', 'Dashboard Wali Kelas')

@section('content')

{{-- HEADER WALI KELAS --}}
<div style="margin-bottom:1.5rem; background:white; padding:1.25rem 1.5rem; border-radius:0.5rem; border:1px solid var(--border-color);">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Dashboard Wali Kelas</h2>
    <div style="font-size:1rem; font-weight:700; color:var(--primary); margin-top:0.25rem;">Selamat datang, {{ $wali->nama_lengkap }}</div>
    <div style="font-size:0.875rem; color:var(--text-muted); margin-top:0.25rem;">
        Kelas Binaan: <strong style="color:var(--text-dark);">{{ $kelas->nama_kelas }} ({{ $kelas->jurusan->nama_jurusan ?? '-' }})</strong> — Tahun Ajaran {{ $kelas->tahunAjaran->nama_tahun_ajaran ?? '2026/2027' }}
    </div>
</div>

{{-- STATISTIK UTAMA KELAS --}}
<div class="grid-4" style="margin-bottom:1.5rem;">
    <div class="stat-card">
        <span class="stat-lbl">Total Siswa di Kelas</span>
        <span class="stat-val">{{ $totalSiswa }}</span>
    </div>
    <div class="stat-card gold">
        <span class="stat-lbl">Rujukan Saya ke BK</span>
        <span class="stat-val" style="color:var(--accent-gold);">{{ $totalRujukan }}</span>
    </div>
    <div class="stat-card blue">
        <span class="stat-lbl">Sedang Konseling</span>
        <span class="stat-val" style="color:var(--info);">{{ $sedangKonseling }}</span>
    </div>
    <div class="stat-card">
        <span class="stat-lbl">Konseling Tuntas</span>
        <span class="stat-val" style="color:var(--success);">{{ $selesaiKonseling }}</span>
    </div>
</div>

{{-- DATA SISWA KELAS BINAAN --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-header">
        <h3 class="card-title">Data Siswa Binaan Kelas {{ $kelas->nama_kelas }}</h3>
        <a href="{{ route('wali.siswa.index') }}" style="font-size:0.875rem; color:var(--primary); text-decoration:none; font-weight:700;">Lihat Seluruh Siswa &rarr;</a>
    </div>

    {{-- Pencarian Siswa --}}
    <form action="{{ route('wali.dashboard') }}" method="GET" style="display:flex; gap:0.5rem; margin-bottom:1rem;">
        <input type="text" name="search_siswa" class="form-control" placeholder="Cari nama siswa di kelas ini..." value="{{ request('search_siswa') }}">
        <button type="submit" class="btn btn-primary btn-sm">Cari</button>
    </form>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>NIS / NISN</th>
                    <th>Jenis Kelamin</th>
                    <th>Status Siswa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswasList as $s)
                <tr>
                    <td><strong>{{ $s->nama_siswa }}</strong></td>
                    <td>
                        <span style="font-weight:700;">{{ $s->nis ?? '-' }}</span> / <small style="color:var(--text-muted);">{{ $s->nisn ?? '-' }}</small>
                    </td>
                    <td>{{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : ($s->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td>
                    <td>
                        <span class="badge badge-{{ $s->status_siswa == 'aktif' ? 'success' : 'warning' }}">
                            {{ strtoupper($s->status_siswa) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('wali.siswa.show', $s->id_siswa) }}" class="btn btn-secondary btn-sm">Lihat Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:1.5rem; color:var(--text-muted);">
                        Tidak ada data siswa ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">
        {{ $siswasList->appends(request()->except('siswa_page'))->links() }}
    </div>
</div>

<div class="grid-2" style="gap:1.5rem; align-items:start;">
    {{-- RUJUKAN KE GURU BK --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Rujukan Siswa ke Guru BK</h3>
            <a href="{{ route('wali.rujukan.create') }}" class="btn btn-primary btn-sm">+ Buat Rujukan</a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Alasan Rujukan</th>
                        <th>Status Pengajuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rujukanList as $r)
                    <tr>
                        <td><strong>{{ $r->siswa->nama_siswa ?? '-' }}</strong></td>
                        <td style="max-width:180px; font-size:0.825rem;">{{ \Illuminate\Support\Str::limit($r->alasan_rujukan, 45) }}</td>
                        <td>
                            @if($r->status_pengajuan == 'menunggu_validasi')
                                <span class="badge badge-warning">Menunggu BK</span>
                            @elseif($r->status_pengajuan == 'disetujui')
                                <span class="badge badge-success">Diterima BK</span>
                            @else
                                <span class="badge badge-danger">{{ strtoupper($r->status_pengajuan) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding:1.5rem; color:var(--text-muted);">
                            Belum ada rujukan siswa dibuat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SESI KONSELING SISWA BINAAN --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Jadwal Sesi Konseling Siswa</h3>
            <a href="{{ route('wali.jadwal.index') }}" style="font-size:0.875rem; color:var(--primary); font-weight:700; text-decoration:none;">Lihat Semua &rarr;</a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Tanggal</th>
                        <th>Status Sesi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwalList as $j)
                    <tr>
                        <td><strong>{{ $j->pengajuan->siswa->nama_siswa ?? '-' }}</strong></td>
                        <td><small style="font-weight:700;">{{ \Carbon\Carbon::parse($j->tanggal_pelaksanaan)->format('d/m/Y') }}</small></td>
                        <td>
                            @if($j->status_sesi == 'terjadwal')
                                <span class="badge badge-warning">Terjadwal</span>
                            @elseif($j->status_sesi == 'selesai')
                                <span class="badge badge-success">Selesai</span>
                            @else
                                <span class="badge badge-info">{{ strtoupper($j->status_sesi) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding:1.5rem; color:var(--text-muted);">
                            Belum ada jadwal sesi konseling siswa di kelas ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
