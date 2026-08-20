@extends('layouts.app')
@section('title', 'Tambah Kelas')

@section('content')

<div style="max-width:650px; margin:0 auto;">
    <div style="margin-bottom:1.5rem;">
        <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Tambah Data Kelas Baru</h2>
        <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Isi formulir berikut untuk menambahkan data rombongan belajar.</p>
    </div>

    <div class="card">
        <form action="{{ route('admin.kelas.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Kelas <span style="color:var(--danger);">*</span></label>
                <input type="text" name="nama_kelas" class="form-control"
                       value="{{ old('nama_kelas') }}" placeholder="Contoh: X PPLG 1" required>
            </div>

            <div class="grid-2" style="gap:1rem; grid-template-columns: 1fr 1fr; margin-bottom:1rem;">
                <div>
                    <label class="form-label">Tingkat <span style="color:var(--danger);">*</span></label>
                    <select name="tingkat_kelas" class="form-control" required>
                        <option value="X" {{ old('tingkat_kelas') == 'X' ? 'selected' : '' }}>Tingkat X</option>
                        <option value="XI" {{ old('tingkat_kelas') == 'XI' ? 'selected' : '' }}>Tingkat XI</option>
                        <option value="XII" {{ old('tingkat_kelas') == 'XII' ? 'selected' : '' }}>Tingkat XII</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Jurusan <span style="color:var(--danger);">*</span></label>
                    <select name="id_jurusan" class="form-control" required>
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach($jurusans as $j)
                            <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                                {{ $j->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Tahun Ajaran <span style="color:var(--danger);">*</span></label>
                <select name="id_tahun_ajaran" class="form-control" required>
                    <option value="">-- Pilih Tahun Ajaran --</option>
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id_tahun_ajaran }}" {{ old('id_tahun_ajaran') == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                            {{ $ta->nama_tahun_ajaran }} {{ $ta->status_aktif ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Wali Kelas (Opsional)</label>
                <select name="id_wali_kelas" class="form-control">
                    <option value="">-- Pilih Wali Kelas --</option>
                    @foreach($waliKelases as $w)
                        <option value="{{ $w->id_wali_kelas }}" {{ old('id_wali_kelas') == $w->id_wali_kelas ? 'selected' : '' }}>
                            {{ $w->nama_lengkap }} (NIP: {{ $w->nip_nuptk ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex; gap:0.5rem; margin-top:1.5rem; justify-content:flex-end;">
                <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" style="font-weight:700;">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>

@endsection
