{{-- VIEW INPUT HASIL KONSELING: Formulir pencatatan hasil konseling, catatan rahasia BK, & 3 opsi tindak lanjut --}}
@extends('layouts.app')
@section('title', 'Input Hasil Konseling')

@section('content')

<div style="max-width:920px; margin:0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <div>
            <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Input Hasil & Tindak Lanjut Konseling</h2>
            <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">
                Pencatatan evaluasi hasil konseling, arahan untuk siswa, serta penetapan opsi tindak lanjut.
            </p>
        </div>
        <a href="{{ route('guru.layanan.index') }}" class="btn btn-secondary">
            &larr; Kembali
        </a>
    </div>

    {{-- Informasi Sesi Konseling --}}
    <div class="card" style="margin-bottom:1.5rem; background:#F8FAFC; border:1px solid var(--border-color);">
        <h3 class="card-title" style="color:var(--primary-dark); font-size:1rem; border-bottom:1px solid var(--border-color); padding-bottom:0.5rem; margin-bottom:0.75rem;">
            Informasi Sesi Konseling
        </h3>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1rem; font-size:0.875rem;">
            <div>
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="font-weight:600; width:140px; padding:0.3rem 0; color:var(--text-muted);">Nama Siswa</td>
                        <td style="padding:0.3rem 0; font-weight:700; color:var(--text-dark);">: {{ $konseling->pengajuan->siswa->nama_siswa ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600; padding:0.3rem 0; color:var(--text-muted);">NIS / NISN</td>
                        <td style="padding:0.3rem 0;">: {{ $konseling->pengajuan->siswa->nis ?? '-' }} / {{ $konseling->pengajuan->siswa->nisn ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600; padding:0.3rem 0; color:var(--text-muted);">Kelas</td>
                        <td style="padding:0.3rem 0;">: {{ $konseling->pengajuan->siswa->kelas->nama_kelas ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div>
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="font-weight:600; width:140px; padding:0.3rem 0; color:var(--text-muted);">Sumber & Jenis</td>
                        <td style="padding:0.3rem 0;">: <span class="badge badge-info">{{ ucfirst($konseling->pengajuan->sumber_pengajuan ?? 'Siswa') }}</span> - {{ ucfirst($konseling->pengajuan->jenis_konseling ?? 'Individu') }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600; padding:0.3rem 0; color:var(--text-muted);">Tanggal Pelaksanaan</td>
                        <td style="padding:0.3rem 0; font-weight:600;">: {{ \Carbon\Carbon::parse($konseling->tanggal_pelaksanaan)->format('d F Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div style="margin-top:0.75rem; padding-top:0.75rem; border-top:1px dashed var(--border-color); font-size:0.875rem;">
            <div style="font-weight:600; color:var(--text-muted); margin-bottom:0.25rem;">Permasalahan yang Diajukan:</div>
            <div style="background:#FFFFFF; padding:0.625rem 0.875rem; border-radius:0.375rem; border:1px solid var(--border-color); color:var(--text-dark);">
                {{ $konseling->pengajuan->alasan_pengajuan ?? 'Tidak ada catatan permasalahan awal.' }}
            </div>
        </div>
    </div>

    {{-- Form Input Hasil Layanan --}}
    <form action="{{ route('guru.siswa.simpan-hasil', $konseling->id_sesi) }}" method="POST">
        @csrf

        <div class="card" style="margin-bottom:1.5rem;">
            <h3 class="card-title" style="margin-bottom:1.25rem; font-size:1.1rem; color:var(--primary-dark);">
                Form Evaluasi & Hasil Konseling
            </h3>

            {{-- Status Kehadiran Siswa --}}
            <div class="form-group">
                <label class="form-label">Status Kehadiran Siswa <span style="color:var(--danger);">*</span></label>
                <div style="display:flex; gap:1.5rem; margin-top:0.35rem;">
                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; font-weight:600;">
                        <input type="radio" name="status_kehadiran" value="hadir" {{ old('status_kehadiran', $konseling->status_kehadiran ?? 'hadir') == 'hadir' ? 'checked' : '' }} required>
                        <span>Hadir Mengikuti Sesi</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; font-weight:600; color:var(--danger);">
                        <input type="radio" name="status_kehadiran" value="tidak_hadir" {{ old('status_kehadiran', $konseling->status_kehadiran) == 'tidak_hadir' ? 'checked' : '' }}>
                        <span>Tidak Hadir (Alpa/Izin)</span>
                    </label>
                </div>
            </div>

            {{-- 1. Hasil Konseling --}}
            <div class="form-group">
                <label for="hasil_konseling" class="form-label">
                    Hasil Konseling <span style="color:var(--danger);">*</span>
                </label>
                <textarea name="hasil_konseling" id="hasil_konseling" rows="4" class="form-control"
                          placeholder="Mencatat pokok pembahasan, respons keterbukaan siswa, dan dinamika yang tercapai saat konseling..."
                          required>{{ old('hasil_konseling', $konseling->hasil_konseling) }}</textarea>
                <small style="color:var(--text-muted); font-size:0.8rem;">Deskripsikan hasil konseling secara komprehensif.</small>
            </div>

            {{-- 2. Rencana Tindak Lanjut --}}
            <div class="form-group">
                <label for="rencana_tindak_lanjut" class="form-label">
                    Rencana Tindak Lanjut
                </label>
                <textarea name="rencana_tindak_lanjut" id="rencana_tindak_lanjut" rows="2" class="form-control"
                          placeholder="Rencana langkah konkret yang disepakati bersama siswa...">{{ old('rencana_tindak_lanjut', $konseling->rencana_tindak_lanjut) }}</textarea>
            </div>

            {{-- 3. Catatan Untuk Siswa (Ditampilkan di Dashboard Siswa) --}}
            <div class="form-group">
                <label for="catatan_untuk_siswa" class="form-label">
                    Catatan / Arahan untuk Siswa (Dapat Dilihat Siswa)
                </label>
                <textarea name="catatan_untuk_siswa" id="catatan_untuk_siswa" rows="2" class="form-control"
                          placeholder="Pesan motivasi, tips perbaikan, atau komitmen belajar yang dapat dibaca oleh siswa di akun mereka...">{{ old('catatan_untuk_siswa', $konseling->catatan_untuk_siswa) }}</textarea>
            </div>

            {{-- 4. Catatan Rahasia (Khusus Guru BK) --}}
            <div class="form-group" style="padding:1rem; background:#FAF5FF; border:1px solid #E9D5FF; border-radius:0.5rem; margin-bottom:1.5rem;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem;">
                    <label for="catatan_rahasia" class="form-label" style="font-weight:700; color:#7E22CE; margin:0;">
                        Catatan Rahasia Guru BK (Opsional)
                    </label>
                    <span class="badge" style="background:#F3E8FF; color:#7E22CE; font-size:0.7rem; border:1px solid #D8B4FE;">HANYA GURU BK</span>
                </div>
                <p style="font-size:0.78rem; color:#6B21A8; margin:0 0 0.5rem 0;">
                    Catatan ini bersifat rahasia profesional dan <strong>TIDAK AKAN DITAMPILKAN</strong> kepada siswa, wali kelas, wakasis, maupun kepala sekolah.
                </p>
                <textarea name="catatan_rahasia" id="catatan_rahasia" rows="3" class="form-control"
                          style="border-color:#D8B4FE; background:#FFFFFF;"
                          placeholder="Tuliskan catatan observasi mendalam latar belakang keluarga atau catatan profesional BK...">{{ old('catatan_rahasia', $konseling->catatan_rahasia) }}</textarea>
            </div>

            {{-- 5. Penetapan Opsi Tindak Lanjut (3 Opsi Bab III) --}}
            <div style="border-top:1px solid var(--border-color); padding-top:1.25rem; margin-top:1.25rem;">
                <h4 style="margin:0 0 0.75rem 0; font-size:1rem; font-weight:700; color:var(--primary-dark);">
                    Penetapan Opsi Tindak Lanjut (Bab III)
                </h4>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1rem; margin-bottom:1rem;">
                    <label style="border:1px solid var(--border-color); padding:1rem; border-radius:0.5rem; cursor:pointer; background:#FFFFFF; display:flex; gap:0.75rem;">
                        <input type="radio" name="opsi_tindak_lanjut" value="selesai" checked onchange="toggleLanjutanSlot(this.value)">
                        <div>
                            <strong style="color:var(--success); font-size:0.9rem; display:block;">1. Selesai (Tuntas)</strong>
                            <small style="color:var(--text-muted);">Sesi konseling selesai dan permasalahan siswa telah tuntas.</small>
                        </div>
                    </label>

                    <label style="border:1px solid var(--border-color); padding:1rem; border-radius:0.5rem; cursor:pointer; background:#FFFFFF; display:flex; gap:0.75rem;">
                        <input type="radio" name="opsi_tindak_lanjut" value="sesi_lanjutan" onchange="toggleLanjutanSlot(this.value)">
                        <div>
                            <strong style="color:var(--info); font-size:0.9rem; display:block;">2. Jadwal Sesi Lanjutan</strong>
                            <small style="color:var(--text-muted);">Memerlukan pertemuan lanjutan pada slot ketersediaan berikutnya.</small>
                        </div>
                    </label>

                    <label style="border:1px solid var(--border-color); padding:1rem; border-radius:0.5rem; cursor:pointer; background:#FFFFFF; display:flex; gap:0.75rem;">
                        <input type="radio" name="opsi_tindak_lanjut" value="surat_ortu" onchange="toggleLanjutanSlot(this.value)">
                        <div>
                            <strong style="color:var(--accent-gold); font-size:0.9rem; display:block;">3. Pemanggilan Orang Tua &amp; Konseling Lanjutan</strong>
                            <small style="color:var(--text-muted);">Menerbitkan surat panggilan resmi Orang Tua &amp; siswa memilih jadwal konseling lanjutan pendampingan Orang Tua.</small>
                        </div>
                    </label>
                </div>

                {{-- Slot Jadwal Lanjutan (Conditional) --}}
                <div id="slotLanjutanContainer" style="display:none; padding:1rem; background:#F0FDF4; border:1px solid #BBF7D0; border-radius:0.5rem; margin-bottom:1rem;">
                    <label class="form-label" style="font-weight:700; color:var(--primary-dark);">Pilih Slot Ketersediaan untuk Sesi Lanjutan</label>
                    <select name="id_jadwal_lanjutan" class="form-control">
                        <option value="">-- Pilih Slot Jadwal Tersedia --</option>
                        @foreach($slotsTersedia as $slot)
                            <option value="{{ $slot->id_jadwal }}">
                                {{ \Carbon\Carbon::parse($slot->tanggal_tersedia)->format('d/m/Y') }} ({{ substr($slot->jam_mulai, 0, 5) }} - {{ substr($slot->jam_selesai, 0, 5) }} WIB)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="catatan_tindak_lanjut" class="form-label">Catatan Tindak Lanjut</label>
                    <input type="text" name="catatan_tindak_lanjut" id="catatan_tindak_lanjut" class="form-control"
                           placeholder="Catatan tambahan mengenai tindak lanjut yang dipilih...">
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1.5rem; border-top:1px solid var(--border-color); padding-top:1rem;">
                <a href="{{ route('guru.layanan.index') }}" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary" style="padding:0.6rem 1.5rem; font-weight:700;">
                    Simpan Hasil & Eksekusi Tindak Lanjut
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function toggleLanjutanSlot(val) {
    const container = document.getElementById('slotLanjutanContainer');
    if (val === 'sesi_lanjutan') {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
    }
}
</script>

@endsection
