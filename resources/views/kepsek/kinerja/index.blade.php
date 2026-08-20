@extends('layouts.app')
@section('title', 'Kinerja Guru BK')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Monitoring & Evaluasi Kinerja Guru BK</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Evaluasi efektivitas dan produktivitas layanan tim Guru Bimbingan Konseling SMKN 2 Guguak.</p>
</div>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama Guru BK</th>
                    <th>NIP</th>
                    <th>Slot Ketersediaan</th>
                    <th>Sesi Konseling</th>
                    <th>Tuntas Selesai</th>
                    <th>Surat Panggilan Ortu</th>
                    <th>Persentase Ketuntasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kinerjaGuru as $kg)
                <tr>
                    <td><strong>{{ $kg['nama_guru'] }}</strong></td>
                    <td><code>{{ $kg['nip'] }}</code></td>
                    <td>{{ $kg['total_slot'] }} Slot</td>
                    <td><strong>{{ $kg['total_layanan'] }} Sesi</strong></td>
                    <td><span class="badge badge-success">{{ $kg['selesai'] }}</span></td>
                    <td><span class="badge badge-gold">{{ $kg['surat_ortu'] }} Surat</span></td>
                    <td>
                        <div style="display:flex; align-items:center; gap:0.5rem;">
                            <div style="flex:1; background:#E2E8F0; height:8px; border-radius:4px; overflow:hidden;">
                                <div style="width:{{ $kg['persen'] }}%; background:var(--primary); height:100%;"></div>
                            </div>
                            <span style="font-weight:700; font-size:0.8rem;">{{ $kg['persen'] }}%</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; padding:2rem; color:var(--text-muted);">Belum ada data kinerja Guru BK.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
