@extends('layouts.app')
@section('title', 'Hasil Konseling Saya')

@section('content')
<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Hasil & Arahan Konseling Saya</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Daftar sesi konseling yang telah dilaksanakan beserta arahan pembinaan dari Guru BK.</p>
</div>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Tanggal & Waktu</th>
                    <th>Guru BK</th>
                    <th>Jenis Layanan</th>
                    <th>Arahan / Catatan untuk Siswa</th>
                    <th>Status Sesi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sesiList as $k)
                <tr>
                    <td>
                        <strong>{{ \Carbon\Carbon::parse($k->tanggal_pelaksanaan)->format('d-m-Y') }}</strong>
                        @if($k->pengajuan && $k->pengajuan->jadwal)
                            <br><small style="color:var(--text-muted);">{{ substr($k->pengajuan->jadwal->jam_mulai, 0, 5) }} - {{ substr($k->pengajuan->jadwal->jam_selesai, 0, 5) }} WIB</small>
                        @endif
                    </td>
                    <td>{{ $k->pengajuan && $k->pengajuan->jadwal && $k->pengajuan->jadwal->guruBk ? $k->pengajuan->jadwal->guruBk->nama_lengkap : 'Guru BK' }}</td>
                    <td><span class="badge badge-info">{{ ucfirst($k->pengajuan->jenis_konseling ?? 'Individu') }}</span></td>
                    <td>
                        @if($k->catatan_untuk_siswa)
                            <div style="font-size:0.875rem; color:var(--text-dark); background:#F8FAFC; padding:0.5rem 0.75rem; border-radius:0.375rem; border:1px solid var(--border-color);">
                                {{ $k->catatan_untuk_siswa }}
                            </div>
                        @else
                            <span style="font-size:0.85rem; color:var(--text-muted); font-style:italic;">Belum ada catatan tambahan dari Guru BK</span>
                        @endif
                    </td>
                    <td>
                        @if($k->status_sesi === 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @elseif($k->status_sesi === 'terjadwal')
                            <span class="badge badge-warning">Terjadwal</span>
                        @else
                            <span class="badge badge-danger">{{ ucfirst($k->status_sesi) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; color:var(--text-muted); padding:2rem;">
                        Belum ada riwayat hasil konseling.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $sesiList->links() }}
    </div>
</div>
@endsection
