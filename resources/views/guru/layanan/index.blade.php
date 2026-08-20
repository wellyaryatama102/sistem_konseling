@extends('layouts.app')
@section('title', 'Layanan Konseling')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Pelayanan Sesi Konseling Siswa</h2>
        <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Daftar sesi konseling, pencatatan hasil, dan evaluasi tindak lanjut siswa.</p>
    </div>
</div>

<div class="card">
    {{-- Form Filter --}}
    <form action="{{ route('guru.layanan.index') }}" method="GET" style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;">
        <input type="text" name="search" class="form-control" style="flex:1; min-width:200px;"
               placeholder="Cari nama siswa atau NIS..."
               value="{{ request('search') }}">

        <select name="status" class="form-control" style="width:180px;">
            <option value="">-- Semua Status Sesi --</option>
            <option value="terjadwal" {{ request('status') == 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('guru.layanan.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>

    {{-- Tabel Layanan Konseling --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Tanggal Pelaksanaan</th>
                    <th>Jenis Layanan</th>
                    <th>Permasalahan</th>
                    <th>Hasil Konseling</th>
                    <th>Status Sesi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($layananList as $l)
                <tr>
                    <td>
                        <strong>{{ $l->pengajuan->siswa->nama_siswa ?? '-' }}</strong><br>
                        <small style="color:var(--text-muted);">NIS: {{ $l->pengajuan->siswa->nis ?? '-' }}</small>
                    </td>
                    <td>{{ $l->pengajuan->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td>
                        <small style="font-weight:700; color:var(--primary-dark);">{{ \Carbon\Carbon::parse($l->tanggal_pelaksanaan)->format('d/m/Y') }}</small>
                    </td>
                    <td>
                        <span class="badge badge-info">{{ ucfirst($l->pengajuan->jenis_konseling ?? 'Individu') }}</span>
                    </td>
                    <td style="max-width:180px; font-size:0.85rem;">
                        {{ \Illuminate\Support\Str::limit($l->pengajuan->alasan_pengajuan ?? '-', 45) }}
                    </td>
                    <td style="max-width:200px; font-size:0.85rem;">
                        {{ \Illuminate\Support\Str::limit($l->hasil_konseling ?? 'Belum diisi', 45) }}
                    </td>
                    <td>
                        @if($l->status_sesi == 'terjadwal')
                            <span class="badge badge-warning">Terjadwal</span>
                        @elseif($l->status_sesi == 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @else
                            <span class="badge badge-danger">{{ strtoupper($l->status_sesi) }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('guru.siswa.input-hasil', $l->id_sesi) }}" class="btn btn-primary btn-sm">
                            {{ $l->status_sesi == 'selesai' ? 'Detail / Edit Hasil' : 'Input Hasil & Tindak Lanjut' }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:2rem; color:var(--text-muted);">
                        Belum ada data sesi konseling terdaftar.
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
