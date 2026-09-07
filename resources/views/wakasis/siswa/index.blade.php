@extends('layouts.app')
@section('title', 'Data Siswa Sekolah')

@section('content')

{{-- Header Halaman --}}
<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Data Siswa SMK Negeri 2 Guguak</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Direktori seluruh siswa terdaftar di sekolah (Read-Only).</p>
</div>

<div class="card">
    {{-- Form Filter --}}
    <form action="{{ route('wakasis.siswa.index') }}" method="GET" style="display:flex; gap:0.75rem; margin-bottom:1.5rem; flex-wrap:wrap; align-items:center;">
        <div style="flex:1; min-width:240px;">
            <input type="text" name="search" class="form-control"
                   placeholder="Cari NIS, NISN, atau Nama Siswa..."
                   value="{{ request('search') }}">
        </div>

        <div style="width:160px;">
            <select name="id_kelas" class="form-control">
                <option value="">-- Semua Kelas --</option>
                @foreach($kelases as $k)
                    <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
        </div>

        <div style="width:180px;">
            <select name="id_jurusan" class="form-control">
                <option value="">-- Semua Jurusan --</option>
                @foreach($jurusans as $j)
                    <option value="{{ $j->id_jurusan }}" {{ request('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                @endforeach
            </select>
        </div>

        <div style="display:flex; gap:0.5rem;">
            <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                Filter
            </button>
            
            @if(request()->hasAny(['search', 'id_kelas', 'id_jurusan']))
                <a href="{{ route('wakasis.siswa.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </div>
    </form>

    {{-- Tabel Data Siswa --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width:180px;">NIS / NISN</th>
                    <th>Nama Siswa</th>
                    <th style="width:140px;">Jenis Kelamin</th>
                    <th style="width:120px;">Kelas</th>
                    <th style="width:180px;">Jurusan</th>
                    <th style="width:130px; text-align:center;">Status Siswa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $s)
                <tr>
                    <td>
                        <div style="font-weight:700; color:var(--primary-dark);">{{ $s->nis ?? '-' }}</div>
                        <small style="color:var(--text-muted); font-size:0.75rem;">NISN: {{ $s->nisn ?? '-' }}</small>
                    </td>
                    <td>
                        <strong style="color:var(--text-dark);">{{ $s->nama_siswa }}</strong>
                    </td>
                    <td>{{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : ($s->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td>
                    <td><span class="badge badge-info">{{ $s->kelas->nama_kelas ?? '-' }}</span></td>
                    <td><span style="font-size:0.85rem; font-weight:500;">{{ $s->kelas->jurusan->nama_jurusan ?? '-' }}</span></td>
                    <td style="text-align:center;">
                        <span class="badge badge-{{ $s->status_siswa == 'aktif' ? 'success' : 'danger' }}">
                            {{ strtoupper($s->status_siswa ?? 'AKTIF') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:2.5rem 1rem; color:var(--text-muted);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:0.5rem; opacity:0.5;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <div>Tidak ada data siswa yang ditemukan.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Custom Pagination Bar (Memperbaiki tampilan acak-acakan) --}}
    @if($siswas->hasPages())
    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:1.5rem; padding-top:1rem; border-top:1px solid var(--border-color); flex-wrap:wrap; gap:1rem;">
        <div style="font-size:0.85rem; color:var(--text-muted);">
            Menampilkan <strong>{{ $siswas->firstItem() ?? 0 }}</strong> - <strong>{{ $siswas->lastItem() ?? 0 }}</strong> dari <strong>{{ $siswas->total() }}</strong> siswa
        </div>
        
        <div style="display:flex; gap:0.35rem; align-items:center;">
            {{-- Tombol Previous --}}
            @if ($siswas->onFirstPage())
                <span class="btn btn-sm btn-secondary" style="opacity:0.5; cursor:not-allowed;">&laquo; Prev</span>
            @else
                <a href="{{ $siswas->previousPageUrl() }}" class="btn btn-sm btn-secondary">&laquo; Prev</a>
            @endif

            {{-- Nomor Halaman --}}
            @foreach ($siswas->getUrlRange(1, $siswas->lastPage()) as $page => $url)
                @if ($page == $siswas->currentPage())
                    <span class="btn btn-sm btn-primary" style="font-weight:700;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="btn btn-sm btn-secondary">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Tombol Next --}}
            @if ($siswas->hasMorePages())
                <a href="{{ $siswas->nextPageUrl() }}" class="btn btn-sm btn-secondary">Next &raquo;</a>
            @else
                <span class="btn btn-sm btn-secondary" style="opacity:0.5; cursor:not-allowed;">Next &raquo;</span>
            @endif
        </div>
    </div>
    @endif
</div>

@endsection