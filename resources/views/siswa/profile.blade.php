@extends('layouts.app')
@section('title', 'Lengkapi Profil Siswa')

@section('content')
<div style="max-width:700px;">
    <h2 style="margin-bottom:1.5rem;">Lengkapi Profil Siswa</h2>

    <div class="card">
        <p style="color:#64748b; font-size:0.875rem; margin-top:0;">Pastikan data diri dan nomor WhatsApp Orang Tua/Wali diisi secara akurat.</p>

        <form action="{{ route('siswa.profile.update') }}" method="POST">
            @csrf
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">NIS (Nomor Induk Siswa)</label>
                    <input type="text" name="nis" class="form-control" value="{{ old('nis', $siswa->nis) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $siswa->nisn) }}" required>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Kelas</label>
                    <select name="kelas_id" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelases as $k)
                            <option value="{{ $k->id }}" {{ old('kelas_id', $siswa->kelas_id) == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-Laki</option>
                        <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', optional($siswa->tanggal_lahir)->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat', $siswa->alamat) }}</textarea>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Nomor WhatsApp Siswa</label>
                    <input type="text" name="no_wa_siswa" class="form-control" value="{{ old('no_wa_siswa', $siswa->no_wa_siswa) }}" placeholder="Contoh: 081234567890" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor WhatsApp Orang Tua / Wali (WAJIB)</label>
                    <input type="text" name="no_wa_ortu" class="form-control" value="{{ old('no_wa_ortu', $siswa->no_wa_ortu) }}" placeholder="Contoh: 081298765432" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top:1rem;">Simpan Profil Saya</button>
        </form>
    </div>
</div>
@endsection
