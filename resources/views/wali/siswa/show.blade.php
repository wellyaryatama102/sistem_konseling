@extends('layouts.app')
@section('title', 'Detail Siswa Binaan')

@section('content')

<div style="max-width:850px; margin:0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <div>
            <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Detail Siswa Binaan</h2>
            <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Informasi profil dan histori layanan konseling siswa {{ $siswa->nama_siswa }}.</p>
        </div>
        <div style="display:flex; gap:0.5rem;">
            <a href="{{ route('wali.rujukan.create', ['siswa_id' => $siswa->id_siswa]) }}" class="btn btn-gold">
                + Ajukan Rujukan BK
            </a>
            <a href="{{ route('wali.siswa.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card" style="margin-bottom:1.5rem;">
        <h3 class="card-title">Informasi Dasar Siswa</h3>
        <table style="width:100%; font-size:0.875rem;">
            <tr><td style="font-weight:600; width:180px; padding:0.4rem 0; color:var(--text-muted);">Nama Lengkap</td><td style="font-weight:700; color:var(--text-dark);">: {{ $siswa->nama_siswa }}</td></tr>
            <tr><td style="font-weight:600; padding:0.4rem 0; color:var(--text-muted);">NIS / NISN</td><td>: {{ $siswa->nis ?? '-' }} / {{ $siswa->nisn ?? '-' }}</td></tr>
            <tr><td style="font-weight:600; padding:0.4rem 0; color:var(--text-muted);">Kelas & Jurusan</td><td>: {{ $siswa->kelas->nama_kelas ?? '-' }} ({{ $siswa->kelas->jurusan->nama_jurusan ?? '-' }})</td></tr>
            <tr><td style="font-weight:600; padding:0.4rem 0; color:var(--text-muted);">Jenis Kelamin</td><td>: {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : ($siswa->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td></tr>
            <tr><td style="font-weight:600; padding:0.4rem 0; color:var(--text-muted);">Status Siswa</td><td>: <span class="badge badge-success">{{ strtoupper($siswa->status_siswa) }}</span></td></tr>
        </table>
    </div>

    {{-- Histori Layanan Konseling Siswa Binaan --}}
    <div class="card">
        <h3 class="card-title">Histori Layanan Konseling Siswa</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal Pelaksanaan</th>
                        <th>Jenis Layanan</th>
                        <th>Guru BK</th>
                        <th>Status Sesi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatLayanan as $l)
                    <tr>
                        <td><small style="font-weight:700;">{{ \Carbon\Carbon::parse($l->tanggal_pelaksanaan)->format('d/m/Y') }}</small></td>
                        <td><span class="badge badge-info">{{ ucfirst($l->pengajuan->jenis_konseling ?? 'Individu') }}</span></td>
                        <td>{{ $l->pengajuan && $l->pengajuan->jadwal && $l->pengajuan->jadwal->guruBk ? $l->pengajuan->jadwal->guruBk->nama_lengkap : 'Guru BK' }}</td>
                        <td>
                            @if($l->status_sesi == 'selesai')
                                <span class="badge badge-success">Selesai</span>
                            @elseif($l->status_sesi == 'terjadwal')
                                <span class="badge badge-warning">Terjadwal</span>
                            @else
                                <span class="badge badge-info">{{ strtoupper($l->status_sesi) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center; padding:1.5rem; color:var(--text-muted);">Belum ada catatan layanan konseling untuk siswa ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
