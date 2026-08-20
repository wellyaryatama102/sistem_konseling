@extends('layouts.app')
@section('title', 'Riwayat Konseling Siswa')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Riwayat Konseling & Perkembangan Siswa</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Histori lengkap pelaksanaan sesi konseling, hasil pembinaan, dan tindak lanjut perkembangan siswa.</p>
</div>

<div class="card">
    {{-- Form Filter --}}
    <form action="{{ route('guru.riwayat.index') }}" method="GET" style="display:flex; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap; align-items:center;">
        <input type="text" name="search" class="form-control" style="flex:1; min-width:200px;"
               placeholder="Cari NIS, NISN, atau Nama Siswa..."
               value="{{ request('search') }}">

        <select name="id_kelas" class="form-control" style="width:170px;">
            <option value="">-- Semua Kelas --</option>
            @foreach($kelases as $k)
                <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                </option>
            @endforeach
        </select>

        <input type="date" name="tanggal" class="form-control" style="width:160px;" value="{{ request('tanggal') }}">

        <select name="status" class="form-control" style="width:160px;">
            <option value="">-- Semua Status --</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="terjadwal" {{ request('status') == 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->hasAny(['search', 'id_kelas', 'tanggal', 'status']))
            <a href="{{ route('guru.riwayat.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>

    {{-- Tabel Riwayat Konseling --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Guru BK</th>
                    <th>Jenis Layanan</th>
                    <th>Tgl Pelaksanaan</th>
                    <th>Hasil & RTL</th>
                    <th>Status Sesi</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayats as $index => $r)
                <tr>
                    <td>{{ $riwayats->firstItem() + $index }}</td>
                    <td>
                        <strong>{{ $r->pengajuan->siswa->nama_siswa ?? '-' }}</strong><br>
                        <small style="color:var(--text-muted);">NIS: {{ $r->pengajuan->siswa->nis ?? '-' }}</small>
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $r->pengajuan->siswa->kelas->nama_kelas ?? '-' }}</span>
                    </td>
                    <td>
                        <small><strong>{{ $r->pengajuan && $r->pengajuan->jadwal && $r->pengajuan->jadwal->guruBk ? $r->pengajuan->jadwal->guruBk->nama_lengkap : 'Guru BK' }}</strong></small>
                    </td>
                    <td>
                        <span class="badge badge-info">{{ ucfirst($r->pengajuan->jenis_konseling ?? 'Individu') }}</span>
                    </td>
                    <td>
                        <small style="font-weight:700; color:var(--primary-dark);">
                            {{ \Carbon\Carbon::parse($r->tanggal_pelaksanaan)->format('d/m/Y') }}
                        </small>
                    </td>
                    <td style="max-width:240px; font-size:0.85rem;">
                        <strong>Hasil:</strong> {{ \Illuminate\Support\Str::limit($r->hasil_konseling ?? '-', 50) }}<br>
                        @if($r->rencana_tindak_lanjut)
                            <small style="color:var(--text-muted);"><strong>RTL:</strong> {{ \Illuminate\Support\Str::limit($r->rencana_tindak_lanjut, 40) }}</small>
                        @endif
                    </td>
                    <td>
                        @if($r->status_sesi == 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @elseif($r->status_sesi == 'terjadwal')
                            <span class="badge badge-warning">Terjadwal</span>
                        @elseif($r->status_sesi == 'dibatalkan')
                            <span class="badge badge-danger">Dibatalkan</span>
                        @else
                            <span class="badge badge-secondary">{{ strtoupper($r->status_sesi) }}</span>
                        @endif
                    </td>
                    <td style="text-align:center; white-space:nowrap;">
                        @if($r->pengajuan && $r->pengajuan->id_siswa)
                            <a href="{{ route('guru.siswa.show', $r->pengajuan->id_siswa) }}" class="btn btn-sm btn-primary" style="display:inline-flex; align-items:center; gap:0.35rem;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Detail Siswa
                            </a>
                        @else
                            <span style="color:var(--text-muted); font-size:0.8rem;">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding:2.5rem; color:var(--text-muted);">
                        Belum ada data riwayat sesi konseling ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.25rem;">
        {{ $riwayats->links() }}
    </div>
</div>

@endsection
