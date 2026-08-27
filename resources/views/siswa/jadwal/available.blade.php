{{-- VIEW PILIH JADWAL SISWA: Halaman pemilihan slot ketersediaan waktu Guru BK untuk konseling mandiri / pendampingan ortu --}}
@extends('layouts.app')
@section('title', 'Cari Slot Konseling')

@section('content')
<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Pilih Slot Jadwal Layanan Konseling</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Pilih slot ketersediaan waktu yang disediakan Guru BK untuk berkonsultasi.</p>
</div>

@if(isset($pendingTindakLanjutOrtu) && $pendingTindakLanjutOrtu)
    <div style="background:#FFFBEB; border:1px solid #FCD34D; border-radius:0.5rem; padding:1rem 1.25rem; margin-bottom:1.25rem; color:#92400E; font-size:0.875rem;">
        <strong style="color:#B45309; font-size:0.95rem; display:block;">📌 Pemilihan Jadwal Konseling Lanjutan Pendampingan Orang Tua</strong>
        Anda diminta oleh Guru BK untuk memilih salah satu slot ketersediaan di bawah ini untuk pelaksanaan <strong>Sesi Konseling Lanjutan yang didampingi oleh Orang Tua/Wali</strong>.
    </div>
@endif

<div class="card">
    <div class="grid-2">
        @forelse($slots as $slot)
        <div style="border:1px solid {{ isset($pendingTindakLanjutOrtu) && $pendingTindakLanjutOrtu ? '#FCD34D' : 'var(--border-color)' }}; border-radius:0.5rem; padding:1.25rem; background-color:#FFFFFF; box-shadow:0 1px 3px rgba(0,0,0,0.03);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                <span class="badge badge-success">Tersedia</span>
                <span style="font-size:0.75rem; color:var(--text-muted); font-weight:600;">Guru BK: {{ $slot->guruBk->nama_lengkap ?? 'Guru BK' }}</span>
            </div>
            <h4 style="margin:0.5rem 0 0.25rem 0; font-size:1.125rem; font-weight:700; color:var(--primary-dark);">
                {{ \Carbon\Carbon::parse($slot->tanggal_tersedia)->format('d F Y') }}
            </h4>
            <div style="font-size:0.875rem; color:var(--text-muted); margin-bottom:1rem;">
                Waktu Pelayanan: <strong>{{ substr($slot->jam_mulai, 0, 5) }} - {{ substr($slot->jam_selesai, 0, 5) }} WIB</strong>
            </div>

            <form action="{{ route('siswa.jadwal.ajukan', $slot->id_jadwal) }}" method="POST">
                @csrf
                @if(isset($pendingTindakLanjutOrtu) && $pendingTindakLanjutOrtu)
                    <input type="hidden" name="tindak_lanjut_id" value="{{ $pendingTindakLanjutOrtu->id_tindak_lanjut }}">
                @endif
                <div class="form-group" style="margin-bottom:0.75rem;">
                    <label class="form-label" style="font-size:0.75rem;">Jenis Layanan</label>
                    <select name="jenis_konseling" class="form-control" style="font-size:0.85rem;" required>
                        <option value="individu" selected>Konseling Individu (Pendampingan Ortu)</option>
                        <option value="kelompok">Konseling Kelompok</option>
                        <option value="insidental">Konseling Insidental</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0.75rem;">
                    <label class="form-label" style="font-size:0.75rem;">Alasan / Pokok Pembahasan</label>
                    <input type="text" name="alasan_pengajuan" class="form-control"
                           value="{{ isset($pendingTindakLanjutOrtu) && $pendingTindakLanjutOrtu ? 'Sesi Konseling Lanjutan Pendampingan Orang Tua' : old('alasan_pengajuan') }}"
                           placeholder="Tuliskan pokok permasalahan atau topik konsultasi..." required style="font-size:0.85rem;">
                </div>
                <button type="submit" class="btn btn-{{ isset($pendingTindakLanjutOrtu) && $pendingTindakLanjutOrtu ? 'warning' : 'primary' }} btn-sm" style="width:100%; justify-content:center; font-weight:700; color:{{ isset($pendingTindakLanjutOrtu) && $pendingTindakLanjutOrtu ? '#000' : '#FFF' }};">
                    {{ isset($pendingTindakLanjutOrtu) && $pendingTindakLanjutOrtu ? 'Pilih Slot Konseling Lanjutan Ortua →' : 'Ajukan Jadwal Ini →' }}
                </button>
            </form>
        </div>
        @empty
        <div style="grid-column: 1 / -1; text-align:center; padding:3rem; color:var(--text-muted);">
            Belum ada slot jadwal ketersediaan yang dibuka saat ini. Silakan kembali lagi nanti atau hubungi Guru BK di sekolah.
        </div>
        @endforelse
    </div>

    <div style="margin-top:1.5rem;">
        {{ $slots->links() }}
    </div>
</div>
@endsection
