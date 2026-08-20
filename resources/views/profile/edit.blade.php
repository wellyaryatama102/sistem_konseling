@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div style="max-width: 850px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem;">
        <h2 style="margin: 0; font-size: 1.75rem; font-weight: 800; color: var(--primary-dark);">Profil Pengguna</h2>
        <p style="color: var(--text-muted); margin: 0.25rem 0 0 0; font-size: 0.875rem;">Kelola informasi profil pribadi dan pengaturan kata sandi akun Anda.</p>
    </div>

    <div class="grid-2" style="grid-template-columns: 1fr; gap: 1.5rem;">
        <!-- Card: Edit Profile -->
        <div class="card">
            <h3 class="card-title" style="border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem; margin-bottom: 1.5rem;">
                Informasi Akun & Diri
            </h3>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf

                <!-- Account Information (Common) -->
                <h4 style="margin: 0 0 1rem 0; font-size: 1rem; color: var(--primary-dark); font-weight: 700;">Data Kredensial Akun</h4>
                
                <div class="grid-2" style="gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Username <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                        @error('username')
                            <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid-2" style="gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px dashed var(--border-color);">
                    <div class="form-group">
                        <label class="form-label">Email <span style="color: var(--danger);">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hak Akses / Status</label>
                        <div style="display: flex; gap: 0.5rem; align-items: center; margin-top: 0.5rem;">
                            <span class="badge badge-info">{{ strtoupper(str_replace('_', ' ', $user->role)) }}</span>
                            <span class="badge badge-{{ $user->status === 'active' ? 'success' : 'danger' }}">{{ strtoupper($user->status) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Profile Information (Role Specific) -->
                <h4 style="margin: 0 0 1rem 0; font-size: 1rem; color: var(--primary-dark); font-weight: 700;">Data Detail Profil</h4>

                <!-- ROLE: ADMIN -->
                @if($user->role === 'admin')
                    <div class="form-group">
                        <label class="form-label">NIP (Nomor Induk Pegawai)</label>
                        <input type="text" name="nip" class="form-control" value="{{ old('nip', $profile->nip ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor Telepon/HP</label>
                        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $profile->no_hp ?? '') }}" placeholder="Contoh: 081234567890">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                    </div>

                <!-- ROLE: GURU BK, WALI KELAS, WAKASIS, KEPALA SEKOLAH -->
                @elseif(in_array($user->role, ['guru_bk', 'wali_kelas', 'wakasis', 'kepala_sekolah']))
                    <div class="grid-2" style="gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label class="form-label">NIP (Nomor Induk Pegawai)</label>
                            <input type="text" name="nip" class="form-control" value="{{ old('nip', $profile->nip ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control">
                                <option value="L" {{ old('jenis_kelamin', $profile->jenis_kelamin ?? '') === 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="P" {{ old('jenis_kelamin', $profile->jenis_kelamin ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid-2" style="gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $profile->tempat_lahir ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $profile->tanggal_lahir ?? '') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nomor Telepon/HP (WhatsApp)</label>
                        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $profile->no_hp ?? '') }}" placeholder="Contoh: 081234567890">
                    </div>

                    @if($user->role === 'guru_bk')
                        <div class="form-group">
                            <label class="form-label">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_terakhir" class="form-control" value="{{ old('pendidikan_terakhir', $profile->pendidikan_terakhir ?? '') }}" placeholder="Contoh: S1 Bimbingan dan Konseling">
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                    </div>

                <!-- ROLE: SISWA -->
                @elseif($user->role === 'siswa')
                    <div class="grid-3" style="gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label class="form-label">NIS</label>
                            <input type="text" name="nis" class="form-control" value="{{ old('nis', $profile->nis ?? '') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">NISN</label>
                            <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $profile->nisn ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kelas Saat Ini</label>
                            <input type="text" class="form-control" value="{{ $profile->kelas->nama_kelas ?? 'Belum Ditentukan' }} ({{ $profile->kelas->jurusan->nama_jurusan ?? '-' }})" readonly style="background-color: var(--bg-main); cursor: not-allowed;">
                        </div>
                    </div>

                    <div class="grid-3" style="gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control">
                                <option value="L" {{ old('jenis_kelamin', $profile->jenis_kelamin ?? '') === 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="P" {{ old('jenis_kelamin', $profile->jenis_kelamin ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $profile->tempat_lahir ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $profile->tanggal_lahir ?? '') }}">
                        </div>
                    </div>

                    <div class="grid-2" style="gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Nomor WhatsApp Siswa</label>
                            <input type="text" name="no_wa_siswa" class="form-control" value="{{ old('no_wa_siswa', $profile->no_wa_siswa ?? '') }}" placeholder="Contoh: 081234567890">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Orang Tua / Wali</label>
                            <input type="text" name="nama_orang_tua_wali" class="form-control" value="{{ old('nama_orang_tua_wali', $profile->nama_orang_tua_wali ?? '') }}" placeholder="Nama Ibu/Bapak/Wali">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nomor WhatsApp Orang Tua / Wali <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="no_wa_orang_tua_wali" class="form-control" value="{{ old('no_wa_orang_tua_wali', $profile->no_wa_orang_tua_wali ?? '') }}" placeholder="Contoh: 081298765432" required>
                        <small style="color: var(--text-muted);">(Wajib aktif untuk penerimaan notifikasi surat panggilan konseling)</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat Rumah Tinggal</label>
                        <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                    </div>
                @endif

                <div style="display: flex; justify-content: flex-end; margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                    <button type="submit" class="btn btn-primary" style="font-weight: 700;">Simpan Perubahan Profil</button>
                </div>
            </form>
        </div>

        <!-- Card: Change Password -->
        <div class="card" style="margin-top: 1.5rem;">
            <h3 class="card-title" style="border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem; margin-bottom: 1.5rem;">
                Ubah Password
            </h3>

            <form action="{{ route('profile.password') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Password Saat Ini <span style="color: var(--danger);">*</span></label>
                    <div style="position: relative;">
                        <input type="password" name="current_password" id="current_password" class="form-control" style="padding-right: 2.75rem;" required placeholder="Masukkan password lama">
                        <button type="button" onclick="togglePasswordVisibility('current_password', this)" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer; color: var(--text-muted); display: flex; align-items: center; justify-content: center; width: 1.5rem; height: 1.5rem;" title="Tampilkan / Sembunyikan Password">
                            <svg class="eye-open" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg class="eye-closed" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid-2" style="gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Password Baru <span style="color: var(--danger);">*</span></label>
                        <div style="position: relative;">
                            <input type="password" name="password" id="new_password" class="form-control" style="padding-right: 2.75rem;" required placeholder="Minimal 6 karakter">
                            <button type="button" onclick="togglePasswordVisibility('new_password', this)" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer; color: var(--text-muted); display: flex; align-items: center; justify-content: center; width: 1.5rem; height: 1.5rem;" title="Tampilkan / Sembunyikan Password">
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
                            <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru <span style="color: var(--danger);">*</span></label>
                        <div style="position: relative;">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" style="padding-right: 2.75rem;" required placeholder="Ulangi password baru">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer; color: var(--text-muted); display: flex; align-items: center; justify-content: center; width: 1.5rem; height: 1.5rem;" title="Tampilkan / Sembunyikan Password">
                                <svg class="eye-open" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg class="eye-closed" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                    <button type="submit" class="btn btn-primary" style="font-weight: 700;">Update Password Baru</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
