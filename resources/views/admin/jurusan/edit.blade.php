@extends('layouts.app')
@section('title', 'Edit Jurusan')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Edit Data Jurusan</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Perbarui informasi master jurusan keahlian.</p>
</div>

<div class="card" style="max-width:600px;">
    <form action="{{ route('admin.jurusan.update', $jurusan->id_jurusan) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Nama Jurusan <span style="color:var(--danger);">*</span></label>
            <input type="text" name="nama_jurusan" class="form-control"
                   value="{{ old('nama_jurusan', $jurusan->nama_jurusan) }}" required>
        </div>

        <div style="display:flex; gap:0.75rem; margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary" style="font-weight:700;">Simpan Perubahan</button>
            <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection
