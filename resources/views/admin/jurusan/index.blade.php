@extends('layouts.app')
@section('title', 'Manajemen Jurusan')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Manajemen Data Jurusan</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Pengelolaan data master jurusan keahlian SMKN 2 Guguak.</p>
</div>

<div class="grid-2" style="gap:1.5rem; align-items:start;">
    {{-- Form Tambah Jurusan --}}
    <div class="card">
        <h3 class="card-title" style="margin-bottom:1rem;">Tambah Jurusan Baru</h3>
        <form action="{{ route('admin.jurusan.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Jurusan <span style="color:var(--danger);">*</span></label>
                <input type="text" name="nama_jurusan" class="form-control"
                       placeholder="Contoh: Rekayasa Perangkat Lunak" required value="{{ old('nama_jurusan') }}">
            </div>
            <button type="submit" class="btn btn-primary" style="font-weight:700;">+ Simpan Jurusan</button>
        </form>
    </div>

    {{-- Tabel Daftar Jurusan --}}
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:0.5rem;">
            <h3 class="card-title" style="margin:0;">Daftar Jurusan Terdaftar</h3>
            <form action="{{ route('admin.jurusan.index') }}" method="GET" style="display:flex; gap:0.5rem;">
                <input type="text" name="search" class="form-control" placeholder="Cari jurusan..." value="{{ request('search') }}" style="width:180px;">
                <button type="submit" class="btn btn-secondary btn-sm">Cari</button>
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nama Jurusan</th>
                        <th>Jumlah Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jurusans as $j)
                    <tr>
                        <td><strong>{{ $j->nama_jurusan }}</strong></td>
                        <td>
                            <span class="badge badge-info">{{ $j->kelas_count ?? 0 }} Kelas</span>
                        </td>
                        <td style="display:flex; gap:0.5rem; align-items:center;">
                            <a href="{{ route('admin.jurusan.edit', $j->id_jurusan) }}" class="btn btn-primary btn-sm">
                                Edit
                            </a>
                            <form action="{{ route('admin.jurusan.destroy', $j->id_jurusan) }}" method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus jurusan {{ $j->nama_jurusan }}?')"
                                  style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding:1.5rem; color:var(--text-muted);">
                            Belum ada data jurusan terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:1rem;">
            {{ $jurusans->links() }}
        </div>
    </div>
</div>

@endsection
