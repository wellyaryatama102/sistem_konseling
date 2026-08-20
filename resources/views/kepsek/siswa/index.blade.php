@extends('layouts.app')
@section('title', 'Data Siswa Sekolah')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Data Siswa SMK Negeri 2 Guguak</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Direktori seluruh siswa terdaftar di sekolah untuk pimpinan (Read-Only).</p>
</div>

<div class="card">
    {{-- Form Filter --}}
    <form action="{{ route('kepsek.siswa.index') }}" method="GET" style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;">
        <input type="text" name="search" class="form-control" style="flex:1; min-width:200px;"
               placeholder="Cari NIS, NISN, atau Nama Siswa..."
               value="{{ request('search') }}">

        <select name="id_kelas" class="form-control" style="width:160px;">
            <option value="">-- Semua Kelas --</option>
            @foreach($kelases as $k)
                <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
            @endforeach
        </select>

        <select name="id_jurusan" class="form-control" style="width:180px;">
            <option value="">-- Semua Jurusan --</option>
            @foreach($jurusans as $j)
                <option value="{{ $j->id_jurusan }}" {{ request('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->hasAny(['search', 'id_kelas', 'id_jurusan']))
            <a href="{{ route('kepsek.siswa.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>

    {{-- Tabel Data Siswa --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>NIS / NISN</th>
                    <th>Nama Siswa</th>
                    <th>Jenis Kelamin</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>Status Siswa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $s)
                <tr>
                    <td>
                        <span style="font-weight:700; color:var(--primary-dark);">NIS: {{ $s->nis ?? '-' }}</span><br>
                        <small style="color:var(--text-muted);">NISN: {{ $s->nisn ?? '-' }}</small>
                    </td>
                    <td><strong>{{ $s->nama_siswa }}</strong></td>
                    <td>{{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : ($s->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td>
                    <td>{{ $s->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $s->kelas->jurusan->nama_jurusan ?? '-' }}</td>
                    <td>
                        <span class="badge badge-{{ $s->status_siswa == 'aktif' ? 'success' : 'warning' }}">
                            {{ strtoupper($s->status_siswa) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:2rem; color:var(--text-muted);">
                        Tidak ada data siswa ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.25rem;">
        {{ $siswas->links() }}
    </div>
</div>

@endsection
