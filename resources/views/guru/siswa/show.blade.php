@extends('layouts.app')
@section('title', 'Detail Rekap Siswa')

@section('content')

<div style="max-width:920px; margin:0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
        <div>
            <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Detail Rekap &amp; Perkembangan Siswa</h2>
            <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Informasi identitas siswa dan riwayat bimbingan konseling.</p>
        </div>
        <a href="{{ route('guru.siswa.index') }}" class="btn btn-secondary">&larr; Kembali</a>
    </div>

    {{-- Profil Siswa --}}
    <div class="card" style="margin-bottom:1.5rem;">
        <h3 class="card-title" style="margin-bottom:1rem;">Profil Siswa</h3>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1rem; font-size:0.875rem;">
            <div>
                <table style="width:100%;">
                    <tr><td style="font-weight:600; width:130px; padding:0.3rem 0; color:var(--text-muted);">Nama Siswa</td><td style="padding:0.3rem 0; font-weight:700;">: {{ $siswa->nama_siswa }}</td></tr>
                    <tr><td style="font-weight:600; padding:0.3rem 0; color:var(--text-muted);">NIS / NISN</td><td style="padding:0.3rem 0;">: {{ $siswa->nis ?? '-' }} / {{ $siswa->nisn ?? '-' }}</td></tr>
                    <tr><td style="font-weight:600; padding:0.3rem 0; color:var(--text-muted);">Kelas Binaan</td><td style="padding:0.3rem 0;">: {{ $siswa->kelas->nama_kelas ?? '-' }} ({{ $siswa->kelas->jurusan->nama_jurusan ?? '-' }})</td></tr>
                    <tr><td style="font-weight:600; padding:0.3rem 0; color:var(--text-muted);">Wali Kelas</td><td style="padding:0.3rem 0;">: {{ $siswa->kelas->waliKelas->nama_lengkap ?? '-' }}</td></tr>
                </table>
            </div>
            <div>
                <table style="width:100%;">
                    <tr><td style="font-weight:600; width:140px; padding:0.3rem 0; color:var(--text-muted);">No. WA Siswa</td><td style="padding:0.3rem 0;">: <code>{{ $siswa->no_wa_siswa ?? '-' }}</code></td></tr>
                    <tr><td style="font-weight:600; padding:0.3rem 0; color:var(--text-muted);">Nama Orang Tua</td><td style="padding:0.3rem 0;">: {{ $siswa->nama_orang_tua_wali ?: '-' }}</td></tr>
                    <tr><td style="font-weight:600; padding:0.3rem 0; color:var(--text-muted);">No. WA Orang Tua</td><td style="padding:0.3rem 0;">: <code>{{ $siswa->no_wa_orang_tua_wali ?: '-' }}</code></td></tr>
                    <tr><td style="font-weight:600; padding:0.3rem 0; color:var(--text-muted);">Status Siswa</td><td style="padding:0.3rem 0;"><span class="badge badge-success">{{ strtoupper($siswa->status_siswa) }}</span></td></tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Riwayat Bimbingan Konseling --}}
    <div class="card">
        <h3 class="card-title" style="margin-bottom:1rem;">Riwayat Bimbingan &amp; Layanan Konseling</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width:35px; text-align:center;">No</th>
                        <th>Tanggal Sesi</th>
                        <th>Jenis &amp; Sumber</th>
                        <th>Alasan Konseling</th>
                        <th>Hasil &amp; Arahan BK</th>
                        <th>Status Sesi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatPengajuan as $idx => $p)
                    <tr>
                        <td style="text-align:center; color:var(--text-muted);">{{ $idx + 1 }}</td>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d/m/Y') }}</strong>
                            @if($p->jadwal)
                                <br><small style="color:var(--text-muted);">{{ substr($p->jadwal->jam_mulai, 0, 5) }} WIB</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-info">{{ ucfirst($p->jenis_konseling) }}</span><br>
                            <small style="color:var(--text-muted);">{{ ucfirst($p->sumber_pengajuan) }}</small>
                        </td>
                        <td style="max-width:200px; font-size:0.85rem;">{{ $p->alasan_pengajuan }}</td>
                        <td style="max-width:220px; font-size:0.85rem;">
                            @if($p->sesiKonseling && $p->sesiKonseling->hasil_konseling)
                                <div style="font-weight:600; color:var(--primary-dark);">{{ $p->sesiKonseling->hasil_konseling }}</div>
                                @if($p->sesiKonseling->catatan_untuk_siswa)
                                    <small style="color:var(--text-muted);">Arahan: {{ $p->sesiKonseling->catatan_untuk_siswa }}</small>
                                @endif
                            @else
                                <span style="color:var(--text-muted); font-style:italic;">Belum ada catatan hasil</span>
                            @endif
                        </td>
                        <td>
                            @if($p->status_pengajuan === 'disetujui')
                                <span class="badge badge-success">Disetujui</span>
                            @elseif($p->status_pengajuan === 'menunggu_validasi')
                                <span class="badge badge-warning">Menunggu</span>
                            @else
                                <span class="badge badge-danger">{{ ucfirst($p->status_pengajuan) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:2rem; color:var(--text-muted);">
                            Belum ada riwayat konseling untuk siswa ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection