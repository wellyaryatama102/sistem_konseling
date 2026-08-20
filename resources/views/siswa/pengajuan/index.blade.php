@extends('layouts.app')
@section('title', 'Pengajuan Saya')

@section('content')
<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Riwayat Pengajuan Jadwal Konseling Saya</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Pantau status validasi atau batalkan jadwal konseling Anda.</p>
</div>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Tanggal & Waktu Sesi</th>
                    <th>Guru BK</th>
                    <th>Jenis Layanan</th>
                    <th>Alasan Pengajuan</th>
                    <th>Status Pengajuan</th>
                    <th>Catatan Guru BK</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuans as $p)
                <tr>
                    <td>
                        @if($p->jadwal)
                            <strong>{{ \Carbon\Carbon::parse($p->jadwal->tanggal_tersedia)->format('d-m-Y') }}</strong><br>
                            <small>{{ substr($p->jadwal->jam_mulai, 0, 5) }} - {{ substr($p->jadwal->jam_selesai, 0, 5) }} WIB</small>
                        @else
                            <span class="badge badge-info">Insidental</span>
                        @endif
                    </td>
                    <td>{{ $p->jadwal->guruBk->nama_lengkap ?? 'Guru BK' }}</td>
                    <td><span class="badge badge-info">{{ ucfirst($p->jenis_konseling) }}</span></td>
                    <td style="max-width:200px; font-size:0.85rem;">{{ $p->alasan_pengajuan }}</td>
                    <td>
                        @if($p->status_pengajuan === 'menunggu_validasi')
                            <span class="badge badge-warning">Menunggu Validasi</span>
                        @elseif($p->status_pengajuan === 'disetujui')
                            <span class="badge badge-success">Disetujui</span>
                        @elseif($p->status_pengajuan === 'ditolak')
                            <span class="badge badge-danger">Ditolak</span>
                        @else
                            <span class="badge badge-danger">Dibatalkan</span>
                        @endif
                    </td>
                    <td style="max-width:180px; font-size:0.8rem; color:var(--text-muted);">
                        {{ $p->catatan_validasi ?: '-' }}
                    </td>
                    <td>
                        @if(in_array($p->status_pengajuan, ['menunggu_validasi', 'disetujui']))
                            <form action="{{ route('siswa.pengajuan.batal', $p->id_pengajuan) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin membatalkan pengajuan konseling ini?')">
                                    Batalkan
                                </button>
                            </form>
                        @else
                            <span style="font-size:0.75rem; color:var(--text-muted);">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:2rem; color:var(--text-muted);">
                        Belum ada pengajuan konseling yang diajukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $pengajuans->links() }}
    </div>
</div>
@endsection
