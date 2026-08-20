@extends('layouts.app')
@section('title', 'Monitoring Layanan Siswa')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Monitoring Layanan Konseling Siswa</h2>
        <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Pemantauan status pelayanan konseling siswa binaan kelas {{ $kelas->nama_kelas }}.</p>
    </div>

    <a href="{{ route('wali.rujukan.create') }}" class="btn btn-primary">
        + Ajukan Rujukan ke Guru BK
    </a>
</div>

<div class="card">
    {{-- Form Filter --}}
    <form action="{{ route('wali.monitoring.index') }}" method="GET" style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;">
        <input type="text" name="search" class="form-control" style="flex:1; min-width:200px;"
               placeholder="Cari nama siswa..."
               value="{{ request('search') }}">

        <select name="status" class="form-control" style="width:180px;">
            <option value="">-- Semua Status Sesi --</option>
            <option value="terjadwal" {{ request('status') == 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('wali.monitoring.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>

    {{-- Tabel Monitoring Layanan Siswa --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>NIS</th>
                    <th>Tanggal Sesi</th>
                    <th>Jenis Layanan</th>
                    <th>Guru BK Pendamping</th>
                    <th>Status Layanan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($layananList as $pm)
                <tr>
                    <td><strong>{{ $pm->pengajuan->siswa->nama_siswa ?? '-' }}</strong></td>
                    <td>{{ $pm->pengajuan->siswa->nis ?? '-' }}</td>
                    <td><small style="font-weight:700; color:var(--primary-dark);">{{ \Carbon\Carbon::parse($pm->tanggal_pelaksanaan)->format('d/m/Y') }}</small></td>
                    <td><span class="badge badge-info">{{ ucfirst($pm->pengajuan->jenis_konseling ?? 'Individu') }}</span></td>
                    <td>{{ $pm->pengajuan && $pm->pengajuan->jadwal && $pm->pengajuan->jadwal->guruBk ? $pm->pengajuan->jadwal->guruBk->nama_lengkap : 'Guru BK' }}</td>
                    <td>
                        @if($pm->status_sesi == 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @elseif($pm->status_sesi == 'terjadwal')
                            <span class="badge badge-warning">Terjadwal</span>
                        @else
                            <span class="badge badge-danger">{{ strtoupper($pm->status_sesi) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:2rem; color:var(--text-muted);">
                        Belum ada data monitoring layanan konseling untuk siswa di kelas ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.25rem;">
        {{ $layananList->links() }}
    </div>
</div>

@endsection
