@extends('layouts.app')
@section('title', 'Detail Data Siswa')

@section('content')

<div style="max-width:800px; margin:0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <div>
            <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Detail Data Dasar Siswa</h2>
            <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Informasi administrasi kesiswaan.</p>
        </div>
        @php
            $backUrl = request('from') === 'laporan' 
                ? route('admin.laporan.index', ['tipe_rekap' => 'status_siswa']) 
                : route('admin.siswa.index');
        @endphp
        <div style="display:flex; gap:0.5rem; align-items:center;">
            <a href="{{ route('admin.siswa.edit', ['siswa' => $siswa->id_siswa, 'from' => request('from')]) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('admin.siswa.destroy', $siswa->id_siswa) }}" method="POST"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa {{ $siswa->nama_siswa }}? Seluruh data riwayat konseling dan akun terkait siswa ini akan ikut terhapus.')"
                  style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
            <a href="{{ $backUrl }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card">
        <h3 class="card-title" style="border-bottom:1px solid var(--border-color); padding-bottom:0.75rem;">Data Akun & Identitas</h3>
        
        <table style="width:100%; border-collapse:collapse; margin-bottom:1.5rem; font-size:0.875rem;">
            <tr>
                <td style="width:200px; font-weight:600; padding:0.5rem 0; color:var(--text-muted);">Nama Lengkap</td>
                <td style="padding:0.5rem 0; font-weight:700; color:var(--text-dark);">: {{ $siswa->nama_siswa }}</td>
            </tr>
            <tr>
                <td style="font-weight:600; padding:0.5rem 0; color:var(--text-muted);">Username / Email</td>
                <td style="padding:0.5rem 0;">: {{ $siswa->user->username ?? '-' }} ({{ $siswa->user->email ?? '-' }})</td>
            </tr>
            <tr>
                <td style="font-weight:600; padding:0.5rem 0; color:var(--text-muted);">NIS / NISN</td>
                <td style="padding:0.5rem 0;">: {{ $siswa->nis ?? '-' }} / {{ $siswa->nisn ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight:600; padding:0.5rem 0; color:var(--text-muted);">Kelas & Jurusan</td>
                <td style="padding:0.5rem 0;">: {{ $siswa->kelas->nama_kelas ?? 'Belum ada kelas' }} ({{ $siswa->kelas->jurusan->nama_jurusan ?? '-' }})</td>
            </tr>
            <tr>
                <td style="font-weight:600; padding:0.5rem 0; color:var(--text-muted);">Jenis Kelamin</td>
                <td style="padding:0.5rem 0;">: {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : ($siswa->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td>
            </tr>
            <tr>
                <td style="font-weight:600; padding:0.5rem 0; color:var(--text-muted);">Tempat, Tanggal Lahir</td>
                <td style="padding:0.5rem 0;">: {{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir : '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight:600; padding:0.5rem 0; color:var(--text-muted);">Alamat</td>
                <td style="padding:0.5rem 0;">: {{ $siswa->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight:600; padding:0.5rem 0; color:var(--text-muted);">No. WA Siswa</td>
                <td style="padding:0.5rem 0;">: <code>{{ $siswa->no_wa_siswa ?? '-' }}</code></td>
            </tr>
            <tr>
                <td style="font-weight:600; padding:0.5rem 0; color:var(--text-muted);">Nama & No. WA Orang Tua</td>
                <td style="padding:0.5rem 0;">: {{ $siswa->nama_orang_tua_wali ?: '-' }} (<code>{{ $siswa->no_wa_orang_tua_wali ?? '-' }}</code>)</td>
            </tr>
            <tr>
                <td style="font-weight:600; padding:0.5rem 0; color:var(--text-muted);">Status Siswa</td>
                <td style="padding:0.5rem 0;">: 
                    <span class="badge badge-{{ $siswa->status_siswa == 'aktif' ? 'success' : 'warning' }}">
                        {{ strtoupper($siswa->status_siswa) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
</div>

@endsection
