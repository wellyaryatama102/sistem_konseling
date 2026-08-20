@extends('layouts.app')
@section('title', 'Edit Data Siswa')

@section('content')

<div style="max-width:780px; margin:0 auto;">
    <div style="margin-bottom:1.5rem;">
        <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Edit Data Siswa</h2>
        <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Perbarui data administrasi untuk siswa {{ $siswa->nama_siswa }}.</p>
    </div>

    <div class="card">
        <form action="{{ route('admin.siswa.update', $siswa->id_siswa) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Nama Lengkap Siswa <span style="color:var(--danger);">*</span></label>
                <input type="text" name="nama_siswa" class="form-control"
                       value="{{ old('nama_siswa', $siswa->nama_siswa) }}" required>
            </div>

            <div class="grid-2" style="gap:1rem; grid-template-columns: 1fr 1fr; margin-bottom:1rem;">
                <div>
                    <label class="form-label">NIS</label>
                    <input type="text" name="nis" class="form-control"
                           value="{{ old('nis', $siswa->nis) }}" placeholder="Nomor Induk Siswa">
                </div>
                <div>
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" class="form-control"
                           value="{{ old('nisn', $siswa->nisn) }}" placeholder="NISN Nasional">
                </div>
            </div>

            <div class="grid-2" style="gap:1rem; grid-template-columns: 1fr 1fr; margin-bottom:1rem;">
                <div>
                    <label class="form-label">Kelas</label>
                    <select name="id_kelas" class="form-control">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelases as $k)
                            <option value="{{ $k->id_kelas }}" {{ old('id_kelas', $siswa->id_kelas) == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }} ({{ $k->jurusan->nama_jurusan ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="grid-2" style="gap:1rem; grid-template-columns: 1fr 1fr; margin-bottom:1rem;">
                <div>
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control"
                           value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}">
                </div>

                <div>
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control"
                           value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $siswa->alamat) }}</textarea>
            </div>

            <div class="grid-3" style="gap:1rem; margin-bottom:1rem;">
                <div>
                    <label class="form-label">No. WA Siswa</label>
                    <input type="text" name="no_wa_siswa" class="form-control"
                           value="{{ old('no_wa_siswa', $siswa->no_wa_siswa) }}" placeholder="08xxxxxxxxxx">
                </div>

                <div>
                    <label class="form-label">Nama Orang Tua/Wali</label>
                    <input type="text" name="nama_orang_tua_wali" class="form-control"
                           value="{{ old('nama_orang_tua_wali', $siswa->nama_orang_tua_wali) }}" placeholder="Nama Ibu / Bapak / Wali">
                </div>

                <div>
                    <label class="form-label">No. WA Orang Tua</label>
                    <input type="text" name="no_wa_orang_tua_wali" class="form-control"
                           value="{{ old('no_wa_orang_tua_wali', $siswa->no_wa_orang_tua_wali) }}" placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Status Siswa</label>
                <select name="status_siswa" class="form-control" required>
                    <option value="aktif" {{ old('status_siswa', $siswa->status_siswa) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="lulus" {{ old('status_siswa', $siswa->status_siswa) == 'lulus' ? 'selected' : '' }}>Lulus</option>
                    <option value="pindah" {{ old('status_siswa', $siswa->status_siswa) == 'pindah' ? 'selected' : '' }}>Pindah</option>
                    <option value="do" {{ old('status_siswa', $siswa->status_siswa) == 'do' ? 'selected' : '' }}>DO</option>
                </select>
            </div>

            @php
                $cancelUrl = request('from') === 'laporan' 
                    ? route('admin.laporan.index', ['tipe_rekap' => 'status_siswa']) 
                    : route('admin.siswa.index');
            @endphp
            <div style="display:flex; gap:0.5rem; margin-top:1.5rem; justify-content:flex-end;">
                <a href="{{ $cancelUrl }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" style="font-weight:700;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@endsection
