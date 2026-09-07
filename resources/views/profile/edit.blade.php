@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<style>
    .profile-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; }
    .pwd-wrapper { position: relative; }
    .pwd-toggle { 
        position: absolute; 
        right: 0.75rem; 
        top: 50%; 
        transform: translateY(-50%); 
        background: none; 
        border: none; 
        cursor: pointer; 
        padding: 0; 
        color: #64748b; 
        display: flex;
        align-items: center;
    }
    
    @media (max-width: 768px) {
        .profile-grid-3 { grid-template-columns: 1fr !important; gap: 0.5rem !important; }
        .card-profile { padding: 1rem !important; }
    }
</style>

<div style="max-width: 950px; margin: 0 auto;">
    <div class="card card-profile" style="padding: 1.25rem; background: #fff; border-radius: 8px;">
        
        <div style="margin-bottom: 1rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem;">
            <h2 style="margin: 0; font-size: 1.35rem; font-weight: 800;">Kelola Profil & Keamanan</h2>
            <p style="color: #64748b; margin: 0.15rem 0 0; font-size: 0.8rem;">Perbarui informasi biodata pribadi dan kata sandi akun Anda.</p>
        </div>

        <!-- FORM 1: UPDATE PROFIL -->
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <h4 style="margin: 0 0 0.5rem; font-size: 0.9rem; font-weight: 700;">1. Kredensial Akun</h4>
            <div class="profile-grid-3" style="margin-bottom: 0.75rem;">
                <div>
                    <label class="form-label" style="font-size: 0.8rem;">Nama Lengkap <span style="color: red;">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div>
                    <label class="form-label" style="font-size: 0.8rem;">Username <span style="color: red;">*</span></label>
                    <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                </div>
                <div>
                    <label class="form-label" style="font-size: 0.8rem;">Email <span style="color: red;">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
            </div>

            <h4 style="margin: 0.75rem 0 0.5rem; font-size: 0.9rem; font-weight: 700;">2. Detail Biodata Profil </h4>

            {{-- ROLE PEGAWAI: ADMIN, GURU BK, WAKASIS, KEPSEK, WALI KELAS --}}
            @if(in_array($user->role, ['admin', 'guru_bk', 'wakasis', 'kepala_sekolah', 'wali_kelas']))
                @php $nipField = $user->role === 'wali_kelas' ? 'nip_nuptk' : 'nip'; @endphp
                
                <div class="profile-grid-3" style="margin-bottom: 0.5rem;">
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">{{ $user->role === 'wali_kelas' ? 'NIP / NUPTK' : 'NIP' }}</label>
                        <input type="text" name="{{ $nipField }}" class="form-control" value="{{ old($nipField, $profile->$nipField ?? '') }}">
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="L" {{ old('jenis_kelamin', $profile->jenis_kelamin ?? '') === 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ old('jenis_kelamin', $profile->jenis_kelamin ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">No. HP</label>
                        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $profile->no_hp ?? '') }}">
                    </div>
                </div>

                <div class="profile-grid-3" style="margin-bottom: 0.5rem;">
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $profile->tempat_lahir ?? '') }}">
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $profile->tanggal_lahir ?? '') }}">
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">{{ $user->role === 'admin' ? 'Pendidikan Terakhir' : 'Jabatan' }}</label>
                        <input type="text" name="{{ $user->role === 'admin' ? 'pendidikan_terakhir' : 'jabatan' }}" class="form-control" value="{{ old($user->role === 'admin' ? 'pendidikan_terakhir' : 'jabatan', $profile->{$user->role === 'admin' ? 'pendidikan_terakhir' : 'jabatan'} ?? '') }}">
                    </div>
                </div>

                <div style="margin-bottom: 0.5rem;">
                    <label class="form-label" style="font-size: 0.8rem;">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="1" style="min-height: 38px;">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                </div>

            {{-- ROLE: SISWA --}}
            @elseif($user->role === 'siswa')
                <div class="profile-grid-3" style="margin-bottom: 0.5rem;">
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">NIS <span style="color: red;">*</span></label>
                        <input type="text" name="nis" class="form-control" value="{{ old('nis', $profile->nis ?? '') }}" required>
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">NISN</label>
                        <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $profile->nisn ?? '') }}">
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Agama</label>
                        <input type="text" name="agama" class="form-control" value="{{ old('agama', $profile->agama ?? '') }}">
                    </div>
                </div>

                <div class="profile-grid-3" style="margin-bottom: 0.5rem;">
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="L" {{ old('jenis_kelamin', $profile->jenis_kelamin ?? '') === 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ old('jenis_kelamin', $profile->jenis_kelamin ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $profile->tempat_lahir ?? '') }}">
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $profile->tanggal_lahir ?? '') }}">
                    </div>
                </div>

                <div class="profile-grid-3" style="margin-bottom: 0.5rem;">
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">No. WA Siswa</label>
                        <input type="text" name="no_wa_siswa" class="form-control" value="{{ old('no_wa_siswa', $profile->no_wa_siswa ?? '') }}">
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">Nama Ortu / Wali</label>
                        <input type="text" name="nama_orang_tua_wali" class="form-control" value="{{ old('nama_orang_tua_wali', $profile->nama_orang_tua_wali ?? '') }}">
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 0.8rem;">No. WA Ortu / Wali <span style="color: red;">*</span></label>
                        <input type="text" name="no_wa_orang_tua_wali" class="form-control" value="{{ old('no_wa_orang_tua_wali', $profile->no_wa_orang_tua_wali ?? '') }}" required>
                    </div>
                </div>

                <div style="margin-bottom: 0.5rem;">
                    <label class="form-label" style="font-size: 0.8rem;">Alamat Rumah</label>
                    <textarea name="alamat" class="form-control" rows="1" style="min-height: 38px;">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                </div>
            @endif

            <div style="display: flex; justify-content: flex-end; margin: 0.75rem 0 1rem;">
                <button type="submit" class="btn btn-primary" style="font-weight: 700; padding: 0.4rem 1.25rem;">Simpan Perubahan Profil</button>
            </div>
        </form>

        <hr style="border: 0; border-top: 1px dashed #e5e7eb; margin: 0.75rem 0;">

        <!-- FORM 2: UBAH PASSWORD DENGAN IKON MATA SVG LINE -->
        <form action="{{ route('profile.password') }}" method="POST">
            @csrf
            <h4 style="margin: 0 0 0.5rem; font-size: 0.9rem; font-weight: 700;">3. Ubah Kata Sandi Akun</h4>

            <div class="profile-grid-3">
                <div>
                    <label class="form-label" style="font-size: 0.8rem;">Password Saat Ini <span style="color: red;">*</span></label>
                    <div class="pwd-wrapper">
                        <input type="password" name="current_password" id="curr_pwd" class="form-control" style="padding-right: 2.25rem;" required placeholder="Password lama">
                        <button type="button" class="pwd-toggle" onclick="togglePwd('curr_pwd', this)">
                            <svg class="eye-open" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-closed" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="form-label" style="font-size: 0.8rem;">Password Baru <span style="color: red;">*</span></label>
                    <div class="pwd-wrapper">
                        <input type="password" name="password" id="new_pwd" class="form-control" style="padding-right: 2.25rem;" required placeholder="Min 6 karakter">
                        <button type="button" class="pwd-toggle" onclick="togglePwd('new_pwd', this)">
                            <svg class="eye-open" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-closed" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="form-label" style="font-size: 0.8rem;">Konfirmasi Password <span style="color: red;">*</span></label>
                    <div class="pwd-wrapper">
                        <input type="password" name="password_confirmation" id="conf_pwd" class="form-control" style="padding-right: 2.25rem;" required placeholder="Ulangi password">
                        <button type="button" class="pwd-toggle" onclick="togglePwd('conf_pwd', this)">
                            <svg class="eye-open" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-closed" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 0.75rem;">
                <button type="submit" class="btn btn-primary" style="font-weight: 700; padding: 0.4rem 1.25rem;">Update Password</button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePwd(inputId, btn) {
        const input = document.getElementById(inputId);
        const isPwd = input.type === 'password';

        input.type = isPwd ? 'text' : 'password';
        btn.querySelector('.eye-open').style.display = isPwd ? 'none' : 'block';
        btn.querySelector('.eye-closed').style.display = isPwd ? 'block' : 'none';
    }
</script>

@endsection