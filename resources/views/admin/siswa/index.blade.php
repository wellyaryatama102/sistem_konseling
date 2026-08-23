@extends('layouts.app')
@section('title', 'Data Siswa')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Data Master Siswa</h2>
        <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Pengelolaan administrasi data pokok siswa SMKN 2 Guguak.</p>
    </div>
</div>

<div class="card">
    {{-- Form Pencarian & Filter --}}
    <form action="{{ route('admin.siswa.index') }}" method="GET" style="display:flex; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap; align-items:center;">
        
        <input type="text" name="search" class="form-control" style="flex:1; min-width:200px;" placeholder="Cari NIS, NISN, atau Nama Siswa..." value="{{ request('search') }}">

        <select name="id_kelas" class="form-control" style="width:160px;">
            <option value="">-- Semua Kelas --</option>
            @foreach($kelases as $k)
                <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
            @endforeach
        </select>

        <select name="status_siswa" class="form-control" style="width:160px;">
            <option value="">-- Status Siswa --</option>
            <option value="aktif" {{ request('status_siswa') == 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="lulus" {{ request('status_siswa') == 'lulus' ? 'selected' : '' }}>Lulus</option>
            <option value="pindah" {{ request('status_siswa') == 'pindah' ? 'selected' : '' }}>Pindah</option>
            <option value="do" {{ request('status_siswa') == 'do' ? 'selected' : '' }}>DO</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        
        @if(request()->hasAny(['search', 'id_kelas', 'status_siswa']))
            <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">Reset</a>
        @endif

        {{-- TOMBOL UNDUH EXCEL DENGAN IKON & POSISI DI KANAN --}}
        <a href="{{ route('admin.siswa.export') }}" class="btn btn-success" style="display:inline-flex; align-items:center; margin-left:auto;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 6px;">
              <path d="M5.884 6.68a.5.5 0 1 0-.768.64L7.349 10l-2.233 2.68a.5.5 0 0 0 .768.64L8 10.781l2.116 2.54a.5.5 0 0 0 .768-.641L8.651 10l2.233-2.68a.5.5 0 0 0-.768-.64L8 9.219l-2.116-2.54z"/>
              <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
            </svg>
            Unduh Excel
        </a>
    </form>

    {{-- Tabel Data Siswa --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>NIS / NISN</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>L/P</th>
                    <th>No. WA Ortu</th>
                    <th>Status</th>
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
                    <td>{{ $s->kelas->nama_kelas ?? 'Belum ditentukan' }}</td>
                    <td>{{ $s->kelas->jurusan->nama_jurusan ?? '-' }}</td>
                    <td>{{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : ($s->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td>
                    <td><code>{{ $s->no_wa_orang_tua_wali ?? '-' }}</code></td>
                    <td>
                        @if($s->status_siswa == 'aktif')
                            <span class="badge badge-success">Aktif</span>
                        @elseif($s->status_siswa == 'lulus')
                            <span class="badge badge-info">Lulus</span>
                        @else
                            <span class="badge badge-danger">{{ strtoupper($s->status_siswa) }}</span>
                        @endif
                    </td>
                    <td style="display:flex;gap:.5rem;align-items:center;">
                        <a href="{{ route('admin.siswa.show', $s->id_siswa) }}" class="btn btn-secondary btn-sm">
                            Detail
                        </a>
                        <a href="{{ route('admin.siswa.edit', $s->id_siswa) }}" class="btn btn-primary btn-sm">
                            Edit
                        </a>
                        <form action="{{ route('admin.siswa.destroy', $s->id_siswa) }}" method="POST"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa {{ $s->nama_siswa }}? Seluruh data riwayat konseling dan akun terkait siswa ini akan ikut terhapus.')"
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
                    <td colspan="8" style="text-align:center;padding:2rem;color:var(--text-muted);">
                        Tidak ada data siswa ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div style="margin-top:1.25rem;">
        {{ $siswas->links() }}
    </div>
</div>

@endsection