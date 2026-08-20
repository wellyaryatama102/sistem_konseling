@extends('layouts.app')
@section('title', 'Data Siswa Binaan')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Data Siswa Binaan Kelas {{ $kelas->nama_kelas }}</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Daftar siswa yang berada di bawah bimbingan Anda (Jurusan: {{ $kelas->jurusan->nama_jurusan ?? '-' }}).</p>
</div>

<div class="card">
    {{-- Form Filter --}}
    <form action="{{ route('wali.siswa.index') }}" method="GET" style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;">
        <input type="text" name="search" class="form-control" style="flex:1; min-width:200px;"
               placeholder="Cari NIS, NISN, atau Nama Siswa..."
               value="{{ request('search') }}">

        <button type="submit" class="btn btn-primary">Cari</button>
        @if(request()->has('search'))
            <a href="{{ route('wali.siswa.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>

    {{-- Tabel Siswa --}}
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
                    <th>Aksi</th>
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
                    <td>{{ $kelas->nama_kelas }}</td>
                    <td>{{ $kelas->jurusan->nama_jurusan ?? '-' }}</td>
                    <td>
                        <span class="badge badge-{{ $s->status_siswa == 'aktif' ? 'success' : 'warning' }}">
                            {{ strtoupper($s->status_siswa) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('wali.siswa.show', $s->id_siswa) }}" class="btn btn-secondary btn-sm">Lihat Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:2rem; color:var(--text-muted);">
                        Tidak ada data siswa ditemukan untuk kelas ini.
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
