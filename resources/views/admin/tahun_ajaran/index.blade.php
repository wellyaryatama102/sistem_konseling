@extends('layouts.app')
@section('title', 'Tahun Ajaran')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Manajemen Tahun Ajaran</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Pengelolaan master tahun ajaran dan status aktif akademik SMKN 2 Guguak.</p>
</div>

<div class="grid-2" style="gap:1.5rem; align-items:start;">
    {{-- Form Tambah Tahun Ajaran --}}
    <div class="card">
        <h3 class="card-title" style="margin-bottom:1rem;">Tambah Tahun Ajaran Baru</h3>
        <form action="{{ route('admin.tahun-ajaran.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Tahun Ajaran <span style="color:var(--danger);">*</span></label>
                <input type="text" name="nama_tahun_ajaran" class="form-control"
                       placeholder="Contoh: 2026/2027" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status Aktif</label>
                <select name="status_aktif" class="form-control" required>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="font-weight:700;">+ Simpan Tahun Ajaran</button>
        </form>
    </div>

    {{-- Tabel Ringkasan Tahun Ajaran --}}
    <div class="card">
        <h3 class="card-title" style="margin-bottom:1rem;">Daftar Tahun Ajaran Terdaftar</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tahun Ajaran</th>
                        <th>Jumlah Kelas</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tahunAjarans as $ta)
                    <tr>
                        <td><strong>{{ $ta->nama_tahun_ajaran }}</strong></td>
                        <td>{{ $ta->kelas_count ?? 0 }} Kelas</td>
                        <td>
                            @if($ta->status_aktif)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-info">Arsip</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding:1.5rem; color:var(--text-muted);">Belum ada data tahun ajaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
