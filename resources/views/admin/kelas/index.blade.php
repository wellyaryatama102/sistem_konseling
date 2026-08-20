@extends('layouts.app')
@section('title', 'Manajemen Kelas')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Manajemen Data Kelas</h2>
        <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Pengelolaan data kelas, konsentrasi keahlian (jurusan), dan penugasan wali kelas.</p>
    </div>

    <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary">
        + Tambah Kelas Baru
    </a>
</div>

<div class="card">
    {{-- Form Filter --}}
    <form action="{{ route('admin.kelas.index') }}" method="GET" style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;">
        <input type="text" name="search" class="form-control" style="flex:1; min-width:200px;"
               placeholder="Cari nama kelas..."
               value="{{ request('search') }}">

        <select name="tingkat" class="form-control" style="width:160px;">
            <option value="">-- Semua Tingkat --</option>
            <option value="X" {{ request('tingkat') == 'X' ? 'selected' : '' }}>Tingkat X</option>
            <option value="XI" {{ request('tingkat') == 'XI' ? 'selected' : '' }}>Tingkat XI</option>
            <option value="XII" {{ request('tingkat') == 'XII' ? 'selected' : '' }}>Tingkat XII</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->hasAny(['search', 'tingkat']))
            <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>

    {{-- Tabel Kelas --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama Kelas</th>
                    <th>Tingkat</th>
                    <th>Jurusan</th>
                    <th>Tahun Ajaran</th>
                    <th>Wali Kelas</th>
                    <th>Jumlah Siswa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelases as $k)
                <tr>
                    <td><strong>{{ $k->nama_kelas }}</strong></td>
                    <td><span class="badge badge-info">{{ $k->tingkat_kelas }}</span></td>
                    <td>{{ $k->jurusan->nama_jurusan ?? '-' }}</td>
                    <td>{{ $k->tahunAjaran->nama_tahun_ajaran ?? '-' }}</td>
                    <td>
                        @if($k->waliKelas)
                            <strong>{{ $k->waliKelas->nama_lengkap }}</strong>
                        @else
                            <span style="color:var(--text-muted); font-style:italic;">Belum ditentukan</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-success">{{ $k->siswas->count() }} Siswa</span>
                    </td>
                    <td style="display:flex;gap:.5rem;">
                        <a href="{{ route('admin.kelas.edit', $k->id_kelas) }}" class="btn btn-primary btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('admin.kelas.destroy', $k->id_kelas) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus kelas {{ $k->nama_kelas }}?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:2rem;color:var(--text-muted);">
                        Tidak ada data kelas ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div style="margin-top:1.25rem;">
        {{ $kelases->links() }}
    </div>
</div>

@endsection
