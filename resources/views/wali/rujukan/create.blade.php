@extends('layouts.app')
@section('title', 'Buat Rujukan ke Guru BK')

@section('content')

<div style="max-width:720px; margin:0 auto;">
    <div style="margin-bottom:1.5rem;">
        <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Pengajuan Rujukan Konseling ke Guru BK</h2>
        <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Ajukan rujukan penanganan siswa binaan kepada Guru Bimbingan Konseling SMKN 2 Guguak.</p>
    </div>

    <div class="card">
        <form action="{{ route('wali.rujukan.store') }}" method="POST">
            @csrf

            {{-- Siswa Dropdown (Class Scoped) --}}
            <div class="form-group">
                <label class="form-label">Pilih Siswa Target (Kelas {{ $kelas->nama_kelas }}) <span style="color:var(--danger);">*</span></label>
                <select name="id_siswa" class="form-control" required>
                    <option value="">-- Pilih Siswa Binaan --</option>
                    @foreach($siswas as $s)
                        <option value="{{ $s->id_siswa }}" {{ (old('id_siswa', $selectedSiswaId ?? '') == $s->id_siswa) ? 'selected' : '' }}>
                            {{ $s->nama_siswa }} (NIS: {{ $s->nis ?? '-' }})
                        </option>
                    @endforeach
                </select>
                @error('id_siswa')
                    <span style="color:var(--danger); font-size:0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid-2" style="gap:1rem; grid-template-columns: 1fr 1fr; margin-bottom:1rem;">
                <div class="form-group">
                    <label class="form-label">Kelas Binaan</label>
                    <input type="text" class="form-control" value="{{ $kelas->nama_kelas }} ({{ $kelas->jurusan->nama_jurusan ?? '-' }})" readonly style="background:#F8FAFC;">
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Layanan yang Diharapkan</label>
                    <select name="jenis_konseling" class="form-control" required>
                        <option value="individu" {{ old('jenis_konseling') == 'individu' ? 'selected' : '' }}>Konseling Individu</option>
                        <option value="kelompok" {{ old('jenis_konseling') == 'kelompok' ? 'selected' : '' }}>Konseling Kelompok</option>
                        <option value="insidental" {{ old('jenis_konseling', 'insidental') == 'insidental' ? 'selected' : '' }}>Konseling Insidental (Mendesak)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alasan & Gambaran Permasalahan Siswa <span style="color:var(--danger);">*</span></label>
                <textarea name="alasan_rujukan" class="form-control" rows="4" placeholder="Jelaskan secara jelas kendala belajar, kedisiplinan, keterlambatan, atau perubahan perilaku siswa yang melatarbelakangi pengajuan rujukan ini..." required>{{ old('alasan_rujukan') }}</textarea>
                @error('alasan_rujukan')
                    <span style="color:var(--danger); font-size:0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display:flex; gap:0.5rem; margin-top:1.5rem; justify-content:flex-end;">
                <a href="{{ route('wali.dashboard') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" style="font-weight:700;">Kirim Rujukan ke Guru BK</button>
            </div>
        </form>
    </div>
</div>

@endsection
