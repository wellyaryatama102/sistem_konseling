@extends('layouts.app')
@section('title', 'Edit Pengguna')

@section('content')
<div style="max-width:650px;">
    <div style="margin-bottom:1.5rem;">
        <h2 style="margin:0; font-size:1.5rem;">Edit Akun Pengguna</h2>
        <p style="color:#64748b; margin:0.25rem 0 0 0; font-size:0.875rem;">Perbarui informasi akun untuk {{ $user->name }}.</p>
    </div>

    {{-- Form Edit Akun --}}
    <div class="card">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control"
                       value="{{ old('username', $user->username) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Role Akses</label>
                <select name="role" class="form-control" required>
                    <option value="admin" {{ old('role', $user->role)=='admin'?'selected':'' }}>Admin</option>
                    <option value="guru_bk" {{ old('role', $user->role)=='guru_bk'?'selected':'' }}>Guru BK</option>
                    <option value="wali_kelas" {{ old('role', $user->role)=='wali_kelas'?'selected':'' }}>Wali Kelas</option>
                    <option value="siswa" {{ old('role', $user->role)=='siswa'?'selected':'' }}>Siswa</option>
                    <option value="wakasis" {{ old('role', $user->role)=='wakasis'?'selected':'' }}>Wakasis</option>
                    <option value="kepala_sekolah" {{ old('role', $user->role)=='kepala_sekolah'?'selected':'' }}>Kepala Sekolah</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Status Akun</label>
                <select name="status" class="form-control" required>
                    <option value="active" {{ old('status', $user->status)=='active'?'selected':'' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $user->status)=='inactive'?'selected':'' }}>Nonaktif</option>
                </select>
            </div>

            <div style="display:flex; gap:0.5rem; margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary">Perbarui Akun</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-danger">Kembali</a>
            </div>
        </form>
    </div>

    {{-- Form Reset Password --}}
    <div class="card" style="margin-top:1.5rem;">
        <h3 class="card-title" style="margin-bottom:0.5rem;">Reset Password</h3>
        <p style="color:#64748b; font-size:0.875rem; margin-bottom:1rem;">Ubah password akun pengguna ini secara langsung.</p>

        <form action="{{ route('admin.users.reset-password', $user) }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Password Baru</label>
                <div style="position:relative;">
                    <input type="password" name="new_password" id="new_password"
                           class="form-control" required
                           placeholder="Masukkan password baru"
                           style="padding-right:2.75rem;">

                    <button type="button"
                            onclick="togglePasswordVisibility('new_password', this)"
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

            <button type="submit" class="btn btn-primary" style="color:#ffffff;">Reset Password</button>
        </form>
    </div>
</div>
@endsection