@extends('layouts.app')
@section('title', 'Tambah Pengguna')

@section('content')
<div style="max-width:650px;">
    <div style="margin-bottom:1.5rem;">
        <h2 style="margin:0; font-size:1.5rem;">Tambah Akun Pengguna Baru</h2>
        <p style="color:#64748b; margin:0.25rem 0 0 0; font-size:0.875rem;">Isi formulir berikut untuk membuat akun pengguna baru.</p>
    </div>

    <div class="card">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            {{-- Nama --}}
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
            </div>

            {{-- Username --}}
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control"
                       value="{{ old('username') }}" placeholder="Masukkan username" required>
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email') }}" placeholder="contoh@sekolah.sch.id" required>
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label">Password</label>
                <div style="position:relative;">
                    <input type="password" name="password" id="password"
                           class="form-control" required placeholder="Minimal 6 karakter" style="padding-right:2.75rem;">
                    <button type="button"
                            onclick="togglePasswordVisibility('password', this)"
                            style="position:absolute; right:0.75rem; top:50%; transform:translateY(-50%); border:0; background:none; cursor:pointer; color:var(--text-muted); display:flex; align-items:center; justify-content:center; width:1.5rem; height:1.5rem;"
                            title="Tampilkan / Sembunyikan Password">
                        <svg class="eye-open" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg class="eye-closed" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Role --}}
            <div class="form-group">
                <label class="form-label">Role Akses</label>
                <select name="role" class="form-control" required>
                    <option value="admin" {{ old('role')=='admin'?'selected':'' }}>Admin</option>
                    <option value="guru_bk" {{ old('role')=='guru_bk'?'selected':'' }}>Guru BK</option>
                    <option value="wali_kelas" {{ old('role')=='wali_kelas'?'selected':'' }}>Wali Kelas</option>
                    <option value="siswa" {{ old('role')=='siswa'?'selected':'' }}>Siswa</option>
                    <option value="wakasis" {{ old('role')=='wakasis'?'selected':'' }}>Wakasis</option>
                    <option value="kepala_sekolah" {{ old('role')=='kepala_sekolah'?'selected':'' }}>Kepala Sekolah</option>
                </select>
            </div>

            {{-- Status --}}
            <div class="form-group">
                <label class="form-label">Status Akun</label>
                <select name="status" class="form-control" required>
                    <option value="active" {{ old('status')=='active'?'selected':'' }}>Aktif</option>
                    <option value="inactive" {{ old('status')=='inactive'?'selected':'' }}>Nonaktif</option>
                </select>
            </div>

            <div style="display:flex; gap:0.5rem; margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary">Simpan Akun</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-danger">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
