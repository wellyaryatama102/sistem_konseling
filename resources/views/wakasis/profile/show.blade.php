@extends('layouts.app')
@section('title', 'Profil Wakasis')

@section('content')

<div style="max-width:650px;">
    <div style="margin-bottom:1.5rem;">
        <h2 style="margin:0; font-size:1.5rem;">Profil Wakasis</h2>
        <p style="color:#64748b; margin:0.25rem 0 0 0; font-size:0.875rem;">Kelola data diri dan keamanan akun Wakil Kepala Sekolah Bidang Kesiswaan.</p>
    </div>

    <div class="card" style="margin-bottom:1.5rem; border-left:4px solid #1e3a8a;">
        <h3 class="card-title" style="margin-bottom:0.75rem;">Peran & Hak Akses Akun</h3>
        <table style="width:100%; font-size:0.875rem;">
            <tr><td style="font-weight:600; width:170px; padding:0.25rem 0;">Role Pengguna</td><td>: <span class="badge badge-info">WAKIL KEPALA SEKOLAH BIDANG KESISWAAN</span></td></tr>
            <tr><td style="font-weight:600; padding:0.25rem 0;">Wewenang Utama</td><td>: Monitoring Kesiswaan & Laporan Rekapitulasi Tingkat Sekolah</td></tr>
            <tr><td style="font-weight:600; padding:0.25rem 0;">NIP / Username</td><td>: {{ $user->username }}</td></tr>
        </table>
    </div>

    <div class="card">
        <h3 class="card-title" style="margin-bottom:1rem;">Ubah Informasi Profil</h3>

        <form action="{{ route('wakasis.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <span style="color:var(--danger); font-size:0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid-2" style="gap:1rem; grid-template-columns: 1fr 1fr; margin-bottom:1rem;">
                <div>
                    <label class="form-label">Username / NIP</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                    @error('username')
                        <span style="color:var(--danger); font-size:0.8rem;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span style="color:var(--danger); font-size:0.8rem;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Kata Sandi Baru (Opsional)</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="wakasis_password" class="form-control" style="padding-right: 2.75rem;" placeholder="Biarkan kosong jika tidak ingin mengubah kata sandi">
                    <button type="button" onclick="togglePasswordVisibility('wakasis_password', this)" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer; color: var(--text-muted); display: flex; align-items: center; justify-content: center; width: 1.5rem; height: 1.5rem;" title="Tampilkan / Sembunyikan Password">
                        <svg class="eye-open" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg class="eye-closed" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span style="color:var(--danger); font-size:0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display:flex; justify-content:flex-end; margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary">Simpan Perubahan Profil</button>
            </div>
        </form>
    </div>
</div>

@endsection
