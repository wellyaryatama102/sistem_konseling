@extends('layouts.app')
@section('title', 'Import Akun Siswa Masal Per Kelas')

@section('content')

<div style="max-width:800px; margin:0 auto;">
    <div style="margin-bottom:1.5rem;">
        <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Import Akun Siswa Masal (Excel/CSV)</h2>
        <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Buat akun login pengguna dan data pokok siswa sekaligus untuk satu kelas menggunakan file Excel.</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
            {{ session('error') }}
        </div>
    @endif

    <div class="card" style="margin-bottom: 1.5rem;">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
            <div>
                <h4 style="margin:0; color:var(--primary-dark); font-size:1.1rem;">Langkah 1: Unduh Format Template Excel</h4>
                <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.85rem;">Gunakan format template resmi agar data terisi secara akurat dan tanpa error.</p>
            </div>
            <a href="{{ route('admin.siswa.template') }}" class="btn btn-success" style="display:inline-flex; align-items:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 6px;">
                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                </svg>
                Unduh Template Excel (.xlsx)
            </a>
        </div>
    </div>

    <div class="card">
        <form action="{{ route('admin.siswa.import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <h4 style="margin-top:0; margin-bottom:1rem; color:var(--primary-dark); font-size:1.1rem; border-bottom:1px solid var(--border-color); padding-bottom:0.5rem;">Langkah 2: Pilih Kelas & Unggah File</h4>

            <div class="form-group" style="margin-bottom:1.25rem;">
                <label class="form-label">Kelas Tujuan <span style="color:var(--danger);">*</span></label>
                <select name="id_kelas" class="form-control" required style="font-weight:600;">
                    <option value="">-- Pilih Kelas Tujuan --</option>
                    @foreach($kelases as $k)
                        <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} ({{ $k->jurusan->nama_jurusan ?? '-' }})
                        </option>
                    @endforeach
                </select>
                <small style="color:var(--text-muted); display:block; margin-top:0.25rem;">Seluruh siswa dalam file Excel ini akan dimasukkan ke dalam kelas yang dipilih.</small>
            </div>

            <div class="form-group" style="margin-bottom:1.25rem;">
                <label class="form-label">Kebijakan Password Login Default <span style="color:var(--danger);">*</span></label>
                <div style="display:flex; gap:1.5rem; margin-top:0.5rem;">
                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; font-size:0.9rem;">
                        <input type="radio" name="password_option" value="nis" checked onclick="toggleCustomPasswordInput(false)">
                        <span>Gunakan <strong>NIS Siswa</strong> sebagai password default</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; font-size:0.9rem;">
                        <input type="radio" name="password_option" value="custom" onclick="toggleCustomPasswordInput(true)">
                        <span>Set Password Seragam Sama Semua</span>
                    </label>
                </div>
            </div>

            <div id="custom_password_wrapper" style="display:none; margin-bottom:1.25rem; background:var(--bg-light, #f8fafc); padding:1rem; border-radius:8px; border:1px solid var(--border-color);">
                <label class="form-label">Masukkan Password Seragam <span style="color:var(--danger);">*</span></label>
                <input type="text" name="custom_password" class="form-control" placeholder="Minimal 6 karakter, contoh: smkn2guguak" value="{{ old('custom_password') }}">
            </div>

            <div class="form-group" style="margin-bottom:1.5rem;">
                <label class="form-label">Pilih File Excel / CSV (.xlsx, .xls, .csv) <span style="color:var(--danger);">*</span></label>
                <input type="file" name="file_excel" class="form-control" accept=".xlsx, .xls, .csv" required style="padding:0.6rem;">
            </div>

            <div style="display:flex; justify-content:flex-end; gap:1rem; margin-top:1.5rem; padding-top:1rem; border-top:1px solid var(--border-color);">
                <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" style="display:inline-flex; align-items:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right:6px;">
                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                        <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                    </svg>
                    Proses Import Akun Masal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleCustomPasswordInput(show) {
        document.getElementById('custom_password_wrapper').style.display = show ? 'block' : 'none';
    }
</script>

@endsection
