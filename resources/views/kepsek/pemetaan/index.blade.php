@extends('layouts.app')
@section('title', 'Pemetaan Masalah Konseling')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Pemetaan Bidang & Permasalahan Konseling Siswa</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Distribusi kategori kebutuhan layanan siswa (Pribadi, Sosial, Belajar, Karir).</p>
</div>

<div class="grid-4" style="margin-bottom:1.5rem;">
    @foreach($pemetaanBidang as $pb)
    <div class="card" style="text-align:center; padding:1.5rem 1rem;">
        <div style="font-size:0.9rem; font-weight:700; color:var(--primary-dark);">{{ $pb['nama'] }}</div>
        <div style="font-size:2rem; font-weight:800; color:var(--primary); margin:0.5rem 0;">{{ $pb['count'] }}</div>
        <small style="color:var(--text-muted);">Total Pengajuan</small>
    </div>
    @endforeach
</div>

<div class="card">
    <h3 class="card-title" style="margin-bottom:1rem;">Daftar Pengajuan Masalah Siswa</h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Kategori Bidang</th>
                    <th>Uraian Permasalahan</th>
                    <th>Status Pengajuan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuanList as $p)
                <tr>
                    <td><small style="font-weight:700;">{{ $p->created_at ? $p->created_at->format('d/m/Y') : '-' }}</small></td>
                    <td><strong>{{ $p->siswa->nama_siswa ?? '-' }}</strong></td>
                    <td>{{ $p->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td><span class="badge badge-info">{{ ucfirst($p->kategori_masalah ?? 'Pribadi') }}</span></td>
                    <td style="max-width:300px; font-size:0.85rem;">{{ $p->alasan_pengajuan }}</td>
                    <td>
                        @if($p->status_pengajuan == 'disetujui')
                            <span class="badge badge-success">Disetujui</span>
                        @elseif($p->status_pengajuan == 'menunggu_validasi')
                            <span class="badge badge-warning">Menunggu</span>
                        @else
                            <span class="badge badge-danger">{{ strtoupper($p->status_pengajuan) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; padding:2rem; color:var(--text-muted);">Belum ada data pengajuan masalah siswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
