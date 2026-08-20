@extends('layouts.app')
@section('title', 'Buat Surat Panggilan')

@section('content')

<div style="max-width:780px; margin:0 auto;">
    <div style="margin-bottom:1.5rem;">
        <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Penerbitan Surat Panggilan Orang Tua / Wali</h2>
        <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Isi formulir berikut untuk menerbitkan surat panggilan resmi dan mengirimkan notifikasi WhatsApp.</p>
    </div>

    <div class="card">
        <form action="{{ route('guru.surat.store') }}" method="POST">
            @csrf

            {{-- Pilih Tindak Lanjut Terkait --}}
            <div class="form-group">
                <label class="form-label">Pilih Rujukan Tindak Lanjut Konseling <span style="color:var(--danger);">*</span></label>
                <select name="id_tindak_lanjut" id="tindakSelect" class="form-control" onchange="onTindakSelected(this)" required>
                    <option value="">-- Pilih Tindak Lanjut --</option>
                    @if($tindakLanjut)
                        <option value="{{ $tindakLanjut->id_tindak_lanjut }}"
                                data-siswa="{{ $tindakLanjut->sesiKonseling->pengajuan->siswa->nama_siswa ?? '-' }}"
                                data-kelas="{{ $tindakLanjut->sesiKonseling->pengajuan->siswa->kelas->nama_kelas ?? '-' }}"
                                data-ortu="{{ $tindakLanjut->sesiKonseling->pengajuan->siswa->nama_orang_tua_wali ?: 'Orang Tua / Wali' }}"
                                data-wa="{{ $tindakLanjut->sesiKonseling->pengajuan->siswa->no_wa_orang_tua_wali ?? '' }}"
                                selected>
                            {{ $tindakLanjut->sesiKonseling->pengajuan->siswa->nama_siswa ?? '-' }} ({{ $tindakLanjut->sesiKonseling->pengajuan->siswa->kelas->nama_kelas ?? '-' }}) - {{ $tindakLanjut->catatan }}
                        </option>
                    @endif
                    @foreach($availableTindakLanjuts as $t)
                        @if(!$tindakLanjut || $t->id_tindak_lanjut !== $tindakLanjut->id_tindak_lanjut)
                            <option value="{{ $t->id_tindak_lanjut }}"
                                    data-siswa="{{ $t->sesiKonseling->pengajuan->siswa->nama_siswa ?? '-' }}"
                                    data-kelas="{{ $t->sesiKonseling->pengajuan->siswa->kelas->nama_kelas ?? '-' }}"
                                    data-ortu="{{ $t->sesiKonseling->pengajuan->siswa->nama_orang_tua_wali ?: 'Orang Tua / Wali' }}"
                                    data-wa="{{ $t->sesiKonseling->pengajuan->siswa->no_wa_orang_tua_wali ?? '' }}">
                                {{ $t->sesiKonseling->pengajuan->siswa->nama_siswa ?? '-' }} ({{ $t->sesiKonseling->pengajuan->siswa->kelas->nama_kelas ?? '-' }}) - {{ $t->catatan }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            {{-- Info Siswa & Orang Tua Terpilih --}}
            <div style="background:#F8FAFC; border:1px solid var(--border-color); border-radius:0.5rem; padding:1rem; margin-bottom:1.25rem;">
                <div style="font-weight:700; font-size:0.875rem; margin-bottom:0.5rem; color:var(--primary-dark);">Informasi Orang Tua / Wali (Dari Profil Siswa):</div>
                <div style="font-size:0.85rem; color:var(--text-dark);">
                    <div>• Nama Siswa: <strong id="lblSiswa">-</strong> (<span id="lblKelas">-</span>)</div>
                    <div>• Orang Tua / Wali: <strong id="lblNamaOrtu">-</strong></div>
                    <div>• No. WhatsApp Orang Tua: <strong id="lblWaOrtu" style="color:var(--primary);">-</strong></div>
                </div>
            </div>

            {{-- Nomor Surat & Tanggal Terbit --}}
            <div class="grid-2" style="gap:1rem; grid-template-columns: 1fr 1fr; margin-bottom:1rem;">
                <div>
                    <label class="form-label">Nomor Surat Resmi</label>
                    <input type="text" name="nomor_surat" class="form-control"
                           value="{{ old('nomor_surat', $nomorOtomatis) }}" required>
                </div>
                <div>
                    <label class="form-label">Tanggal Terbit Surat</label>
                    <input type="date" name="tanggal_terbit" class="form-control"
                           value="{{ old('tanggal_terbit', date('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Perihal Surat</label>
                <input type="text" name="perihal" class="form-control"
                       value="{{ old('perihal', 'Panggilan Orang Tua / Wali Siswa') }}" required>
            </div>

            {{-- Jadwal Pertemuan --}}
            <div class="grid-2" style="gap:1rem; grid-template-columns: 1fr 1fr; margin-bottom:1rem;">
                <div>
                    <label class="form-label">Tanggal Pertemuan di Sekolah</label>
                    <input type="date" name="tanggal_pertemuan" class="form-control"
                           value="{{ old('tanggal_pertemuan', date('Y-m-d', strtotime('+2 days'))) }}" required>
                </div>
                <div>
                    <label class="form-label">Waktu Pertemuan</label>
                    <input type="time" name="waktu_pertemuan" class="form-control"
                           value="{{ old('waktu_pertemuan', '09:00') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Tempat Pertemuan</label>
                <input type="text" name="tempat" class="form-control"
                       value="{{ old('tempat', 'Ruang Konseling SMKN 2 Guguak') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Isi Surat / Penjelasan Keperluan</label>
                <textarea name="isi_surat" class="form-control" rows="4" required>{{ old('isi_surat', "Sehubungan dengan perlunya koordinasi dan pembinaan kedisiplinan serta perkembangan belajar siswa di sekolah, kami mengharapkan kehadiran Bapak/Ibu Orang Tua/Wali pada waktu dan tempat yang telah ditentukan.") }}</textarea>
            </div>

            <div style="display:flex; gap:0.5rem; margin-top:1.5rem; justify-content:flex-end;">
                <a href="{{ route('guru.surat.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" style="font-weight:700;">Simpan & Terbitkan Surat</button>
            </div>
        </form>
    </div>
</div>

<script>
function onTindakSelected(select) {
    const option = select.options[select.selectedIndex];
    const siswa = option.getAttribute('data-siswa') || '-';
    const kelas = option.getAttribute('data-kelas') || '-';
    const ortu = option.getAttribute('data-ortu') || '-';
    const wa = option.getAttribute('data-wa') || 'Belum terdaftar';
    
    document.getElementById('lblSiswa').textContent = siswa;
    document.getElementById('lblKelas').textContent = kelas;
    document.getElementById('lblNamaOrtu').textContent = ortu;
    document.getElementById('lblWaOrtu').textContent = wa;
}

document.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('tindakSelect');
    if (sel.value) {
        onTindakSelected(sel);
    }
});
</script>

@endsection
