@extends('layouts.app')
@section('title', 'Tambah Data Siswa Baru')

@section('content')

<div style="max-width:780px; margin:0 auto;">
    <div style="margin-bottom:1.5rem;">
        <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Tambah Data Siswa Baru</h2>
        <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Tambahkan data pokok siswa baru beserta akun login penggunanya.</p>
    </div>

    <div class="card">
        <form action="{{ route('admin.siswa.store') }}" method="POST">
            @csrf

            <h4 style="margin-top:0; margin-bottom:1rem; color:var(--primary-dark); font-size:1.1rem; border-bottom:1px solid var(--border-color); pb:0.5rem;">A. Akun Login Siswa</h4>

            <div class="grid-2" style="gap:1rem; grid-template-columns: 1fr 1fr; margin-bottom:1rem;">
                <div>
                    <label class="form-label">Username Login <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="username" class="form-control"
                           value="{{ old('username') }}" placeholder="Contoh: siswa123 / nis" required>
                </div>
                <div>
                    <label class="form-label">Password <span style="color:var(--danger);">*</span></label>
                    <input type="password" name="password" class="form-control"
                           placeholder="Minimal 6 karakter" required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom:1.5rem;">
                <label class="form-label">Email (Opsional)</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email') }}" placeholder="Contoh: siswa@sekolah.sch.id">
            </div>

            <h4 style="margin-top:1.5rem; margin-bottom:1rem; color:var(--primary-dark); font-size:1.1rem; border-bottom:1px solid var(--border-color); pb:0.5rem;">B. Data Pokok Siswa</h4>

            <div class="form-group">
                <label class="form-label">Nama Lengkap Siswa <span style="color:var(--danger);">*</span></label>
                <input type="text" name="nama_siswa" class="form-control"
                       value="{{ old('nama_siswa') }}" placeholder="Nama sesuai ijazah/dokumen" required>
            </div>

            <div class="grid-2" style="gap:1rem; grid-template-columns: 1fr 1fr; margin-bottom:1rem;">
                <div>
                    <label class="form-label">NIS</label>
                    <input type="text" name="nis" class="form-control"
                           value="{{ old('nis') }}" placeholder="Nomor Induk Siswa">
                </div>
                <div>
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" class="form-control"
                           value="{{ old('nisn') }}" placeholder="NISN Nasional">
                </div>
            </div>

            <div class="grid-2" style="gap:1rem; grid-template-columns: 1fr 1fr; margin-bottom:1rem;">
                <div>
                    <label class="form-label">Kelas</label>
                    <select name="id_kelas" class="form-control">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelases as $k)
                            <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }} ({{ $k->jurusan->nama_jurusan ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="grid-2" style="gap:1rem; grid-template-columns: 1fr 1fr; margin-bottom:1rem;">
                <div>
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control"
                           value="{{ old('tempat_lahir') }}" placeholder="Kota/Kabupaten">
                </div>

                <div>
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control"
                           value="{{ old('tanggal_lahir') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat tempat tinggal siswa">{{ old('alamat') }}</textarea>
            </div>

            <div class="grid-3" style="gap:1rem; margin-bottom:1rem;">
                <div>
                    <label class="form-label">No. WA Siswa</label>
                    <input type="text" name="no_wa_siswa" class="form-control"
                           value="{{ old('no_wa_siswa') }}" placeholder="08xxxxxxxxxx">
                </div>

                <div>
                    <label class="form-label">Nama Orang Tua/Wali</label>
                    <input type="text" name="nama_orang_tua_wali" class="form-control"
                           value="{{ old('nama_orang_tua_wali') }}" placeholder="Nama Ibu / Bapak / Wali">
                </div>

                <div>
                    <label class="form-label">No. WA Orang Tua</label>
                    <input type="text" name="no_wa_orang_tua_wali" class="form-control"
                           value="{{ old('no_wa_orang_tua_wali') }}" placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Status Siswa <span style="color:var(--danger);">*</span></label>
                <select name="status_siswa" class="form-control" required>
                    <option value="aktif" {{ old('status_siswa', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="lulus" {{ old('status_siswa') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                    <option value="pindah" {{ old('status_siswa') == 'pindah' ? 'selected' : '' }}>Pindah</option>
                    <option value="do" {{ old('status_siswa') == 'do' ? 'selected' : '' }}>DO</option>
                </select>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:1rem; margin-top:1.5rem;">
                <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Siswa Baru</button>
            </div>
        </form>
    </div>
</div>

@endsection
