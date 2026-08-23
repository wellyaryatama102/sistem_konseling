@extends('layouts.app')
@section('title', 'Manajemen Pengguna')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h2 style="margin:0; font-size:1.5rem;">Manajemen Pengguna</h2>
        <p style="color:#64748b; margin:0.25rem 0 0 0; font-size:0.875rem;">Kelola akun pengguna, hak akses role, dan status aktivasi akun.</p>
    </div>

    {{-- BUNGKUSAN TOMBOL EXCEL & TAMBAH PENGGUNA --}}
    <div style="display: flex; gap: 0.5rem; align-items: center;">
        {{-- Tombol Unduh Excel --}}
        <a href="{{ route('admin.users.export') }}" class="btn btn-success" style="display: inline-flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 6px;">
              <path d="M5.884 6.68a.5.5 0 1 0-.768.64L7.349 10l-2.233 2.68a.5.5 0 0 0 .768.64L8 10.781l2.116 2.54a.5.5 0 0 0 .768-.641L8.651 10l2.233-2.68a.5.5 0 0 0-.768-.64L8 9.219l-2.116-2.54z"/>
              <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
            </svg>
            Unduh Excel
        </a>

        {{-- Tombol Tambah Pengguna Baru --}}
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center;">
            + Tambah Pengguna Baru
        </a>
    </div>
</div>

<div class="card">
    {{-- Pencarian Pengguna Berdasarkan Nama --}}
    <form action="{{ route('admin.users.index') }}" method="GET" style="display:flex; gap:0.75rem; margin-bottom:1.5rem; flex-wrap:wrap; align-items:center;">
        <div style="flex:1; min-width:260px;">
            <input type="text" name="search" class="form-control"
                   placeholder="Cari berdasarkan nama pengguna..."
                   value="{{ request('search') }}" style="height:38px; width:100%;">
        </div>

        <button type="submit" class="btn btn-primary" style="height:38px; display:inline-flex; align-items:center; justify-content:center; padding:0 1.25rem;">
            Cari
        </button>

        @if(request()->filled('search'))
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="height:38px; display:inline-flex; align-items:center; justify-content:center; padding:0 1rem;">
                Reset
            </a>
        @endif
    </form>

    {{-- Tabel Pengguna --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama Pengguna</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td><strong>{{ $u->name }}</strong></td>
                    <td><code>{{ $u->username }}</code></td>
                    <td>{{ $u->email }}</td>
                    <td>
                        <span class="badge badge-info">
                            {{ str_replace('_',' ', strtoupper($u->role)) }}
                        </span>
                    </td>
                    <td>
                        @if($u->status=='active')
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td style="display:flex;gap:.5rem;">
                        {{-- Edit --}}
                        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-primary btn-sm">
                            Edit
                        </a>

                        {{-- Toggle Status Aktif / Nonaktif --}}
                        <form action="{{ route('admin.users.toggle-status', $u) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $u->status=='active'?'danger':'success' }} btn-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin mengaktifkan/menonaktifkan akun {{ $u->name }}?')">
                                {{ $u->status=='active'?'Nonaktifkan':'Aktifkan' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:2rem;color:#64748b;">
                        Tidak ada data pengguna yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div style="margin-top:1.25rem;">
        {{ $users->links() }}
    </div>
</div>
@endsection