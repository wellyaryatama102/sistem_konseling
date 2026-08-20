@extends('layouts.app')
@section('title', 'Detail Siswa - ' . $siswa->nama_siswa)

@section('content')

<div style="max-width:1050px; margin:0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
        <div>
            <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Profil & Histori Konseling Siswa</h2>
            <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">
                Rekapitulasi data siswa, histori pengajuan, dan sesi tindak lanjut konseling.
            </p>
        </div>
        <div style="display:flex; gap:0.5rem;">
            <a href="{{ route('guru.siswa.index') }}" class="btn btn-secondary">
                &larr; Riwayat Konseling
            </a>
        </div>
    </div>

    {{-- 1. Profil Siswa --}}
    <div class="card" style="margin-bottom:1.5rem;">
        <h3 class="card-title" style="display:flex; align-items:center; gap:0.5rem; color:var(--primary-dark); border-bottom:1px solid var(--border-color); padding-bottom:0.75rem; margin-bottom:1rem;">
            Profil Siswa SMKN 2 Guguak
        </h3>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:1.5rem; font-size:0.875rem;">
            <div>
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="font-weight:600; width:170px; padding:0.4rem 0; color:var(--text-muted);">Nama Lengkap</td>
                        <td style="padding:0.4rem 0; font-weight:700; color:var(--text-dark);">: {{ $siswa->nama_siswa }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600; padding:0.4rem 0; color:var(--text-muted);">NIS / NISN</td>
                        <td style="padding:0.4rem 0; font-weight:600;">: {{ $siswa->nis ?? '-' }} / {{ $siswa->nisn ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600; padding:0.4rem 0; color:var(--text-muted);">Jenis Kelamin</td>
                        <td style="padding:0.4rem 0;">: {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : ($siswa->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600; padding:0.4rem 0; color:var(--text-muted);">Tempat, Tgl Lahir</td>
                        <td style="padding:0.4rem 0;">: {{ $siswa->tempat_lahir ? $siswa->tempat_lahir . ', ' . $siswa->tanggal_lahir : '-' }}</td>
                    </tr>
                </table>
            </div>
            <div>
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="font-weight:600; width:170px; padding:0.4rem 0; color:var(--text-muted);">Kelas & Jurusan</td>
                        <td style="padding:0.4rem 0; font-weight:600;">: {{ $siswa->kelas->nama_kelas ?? '-' }} ({{ $siswa->kelas->jurusan->nama_jurusan ?? '-' }})</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600; padding:0.4rem 0; color:var(--text-muted);">Wali Kelas</td>
                        <td style="padding:0.4rem 0;">: {{ $siswa->kelas->waliKelas->nama_lengkap ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600; padding:0.4rem 0; color:var(--text-muted);">No. WhatsApp Siswa</td>
                        <td style="padding:0.4rem 0;">: <code>{{ $siswa->no_wa_siswa ?? '-' }}</code></td>
                    </tr>
                    <tr>
                        <td style="font-weight:600; padding:0.4rem 0; color:var(--text-muted);">Orang Tua & No. WA</td>
                        <td style="padding:0.4rem 0;">: {{ $siswa->nama_orang_tua_wali ?: '-' }} (<code>{{ $siswa->no_wa_orang_tua_wali ?: '-' }}</code>)</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- 2. Histori Sesi Konseling Siswa --}}
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; border-bottom:1px solid var(--border-color); padding-bottom:0.75rem;">
            <h3 class="card-title" style="margin:0; color:var(--primary-dark);">
                Histori Sesi Konseling & Tindak Lanjut
            </h3>
            <span style="font-size:0.8rem; color:var(--text-muted); font-weight:600;">Total: {{ $riwayatPengajuan->count() }} Pengajuan</span>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tgl Pengajuan</th>
                        <th>Sumber & Jenis</th>
                        <th>Alasan Permasalahan</th>
                        <th>Status Pengajuan</th>
                        <th>Hasil Konseling</th>
                        <th>Tindak Lanjut</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatPengajuan as $p)
                    @php
                        $sesi = $p->sesiKonseling;
                    @endphp
                    <tr>
                        <td><small style="font-weight:700;">{{ $p->created_at ? $p->created_at->format('d/m/Y') : '-' }}</small></td>
                        <td>
                            <span class="badge badge-info">{{ ucfirst($p->sumber_pengajuan) }}</span><br>
                            <small>{{ ucfirst($p->jenis_konseling) }}</small>
                        </td>
                        <td style="max-width:180px; font-size:0.8rem;">
                            {{ \Illuminate\Support\Str::limit($p->alasan_pengajuan, 45) }}
                        </td>
                        <td>
                            @if($p->status_pengajuan == 'disetujui')
                                <span class="badge badge-success">Disetujui</span>
                            @elseif($p->status_pengajuan == 'menunggu_validasi')
                                <span class="badge badge-warning">Menunggu</span>
                            @else
                                <span class="badge badge-danger">{{ strtoupper($p->status_pengajuan) }}</span>
                            @endif
                        </td>
                        <td style="max-width:200px; font-size:0.8rem;">
                            {{ $sesi && $sesi->hasil_konseling ? \Illuminate\Support\Str::limit($sesi->hasil_konseling, 45) : '-' }}
                        </td>
                        <td>
                            @if($sesi && $sesi->tindakLanjuts->count() > 0)
                                <span class="badge badge-gold">{{ ucfirst(str_replace('_', ' ', $sesi->tindakLanjuts->first()->jenis_aksi)) }}</span>
                            @else
                                <small style="color:var(--text-muted);">-</small>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($sesi)
                                <a href="{{ route('guru.siswa.input-hasil', $sesi->id_sesi) }}" class="btn btn-primary btn-sm">
                                    {{ $sesi->status_sesi == 'selesai' ? 'Detail' : 'Input Hasil' }}
                                </a>
                            @else
                                <small style="color:var(--text-muted);">-</small>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:2rem; color:var(--text-muted);">
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
