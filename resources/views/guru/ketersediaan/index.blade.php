@extends('layouts.app')
@section('title', 'Jadwal Ketersediaan')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Jadwal Ketersediaan Guru BK</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Atur slot waktu ketersediaan pelayanan konseling yang dapat dipilih siswa di SMKN 2 Guguak.</p>
</div>

<div class="grid-2" style="gap:1.5rem; align-items:start;">
    {{-- Form Tambah Ketersediaan --}}
    <div class="card">
        <h3 class="card-title" style="margin-bottom:1rem;">Tambah Slot Ketersediaan</h3>
        <form action="{{ route('guru.ketersediaan.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Tanggal Tersedia</label>
                <input type="date" name="tanggal_tersedia" class="form-control"
                       value="{{ old('tanggal_tersedia', date('Y-m-d')) }}" required>
            </div>

            <div class="grid-2" style="gap:1rem; grid-template-columns: 1fr 1fr; margin-bottom:1rem;">
                <div>
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control"
                           value="{{ old('jam_mulai', '08:00') }}" required>
                </div>
                <div>
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control"
                           value="{{ old('jam_selesai', '09:00') }}" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Tambah Slot Waktu</button>
        </form>
    </div>

    {{-- Tabel Ketersediaan --}}
    <div class="card">
        <h3 class="card-title" style="margin-bottom:1rem;">Daftar Slot Ketersediaan Terdaftar</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Hari / Tanggal</th>
                        <th>Waktu (Mulai - Selesai)</th>
                        <th>Status Slot</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ketersediaans as $j)
                    <tr>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($j->tanggal_tersedia)->format('d/m/Y') }}</strong>
                        </td>
                        <td>
                            <code>{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }} WIB</code>
                        </td>
                        <td>
                            @if($j->status_slot == 'tersedia')
                                <span class="badge badge-success">Tersedia</span>
                            @elseif($j->status_slot == 'terisi')
                                <span class="badge badge-warning">Terisi Siswa</span>
                            @else
                                <span class="badge badge-info">{{ strtoupper($j->status_slot) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($j->status_slot == 'tersedia')
                                <form action="{{ route('guru.ketersediaan.destroy', $j->id_jadwal) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus slot ketersediaan ini?')">
                                        Hapus
                                    </button>
                                </form>
                            @else
                                <small style="color:var(--text-muted);">-</small>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:1.5rem; color:var(--text-muted);">
                            Belum ada slot ketersediaan yang ditambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:1.25rem;">
            {{ $ketersediaans->links() }}
        </div>
    </div>
</div>

@endsection
