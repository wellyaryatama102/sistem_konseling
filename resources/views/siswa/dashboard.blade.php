{{-- VIEW DASHBOARD SISWA: Beranda utama siswa untuk melihat statistik konseling & notifikasi tindak lanjut --}}
@extends('layouts.app')
@section('title', 'Beranda Siswa')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Selamat Datang, {{ $siswa->nama_siswa }}</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Sistem Informasi Layanan Konseling Siswa (SIKS) SMK Negeri 2 Guguak.</p>
</div>

<div class="grid-4" style="margin-bottom:1.5rem;">
    <div class="stat-card">
        <span class="stat-lbl">Total Pengajuan Saya</span>
        <span class="stat-val">{{ $stats['total_pengajuan'] }}</span>
    </div>
    <div class="stat-card">
        <span class="stat-lbl">Disetujui Guru BK</span>
        <span class="stat-val" style="color:var(--success);">{{ $stats['disetujui'] }}</span>
    </div>
    <div class="stat-card gold">
        <span class="stat-lbl">Menunggu Validasi</span>
        <span class="stat-val" style="color:var(--accent-gold);">{{ $stats['menunggu'] }}</span>
    </div>
    <div class="stat-card blue">
        <span class="stat-lbl">Kelas Binaan</span>
        <span class="stat-val" style="font-size:1.25rem; color:var(--info);">{{ $siswa->kelas->nama_kelas ?? '-' }}</span>
    </div>
</div>

@if(isset($pendingTindakLanjutOrtu) && $pendingTindakLanjutOrtu)
    <div style="background:#FFFBEB; border:1px solid #FCD34D; border-radius:0.5rem; padding:1.25rem; margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <div>
            <div style="font-weight:800; color:#B45309; font-size:1.05rem;">
                Instruksi Guru BK: Pemanggilan Orang Tua &amp; Konseling Lanjutan
            </div>
            <div style="font-size:0.875rem; color:#92400E; margin-top:0.25rem;">
                Guru BK telah menetapkan tindak lanjut Pemanggilan Orang Tua. Anda diminta memilih kembali slot jadwal <strong>Konseling Lanjutan Pendampingan Orang Tua</strong>.
            </div>
        </div>
        <a href="{{ route('siswa.jadwal.available', ['tindak_lanjut_id' => $pendingTindakLanjutOrtu->id_tindak_lanjut]) }}" class="btn btn-warning" style="font-weight:700; color:#000;">
            Pilih Jadwal Konseling Lanjutan &rarr;
        </a>
    </div>
@endif

<div class="grid-2" style="margin-top:1.5rem; gap:1.5rem; align-items:start;">
    {{-- Jadwal Terdekat --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Jadwal Sesi Konseling Terdekat</h3>
            <a href="{{ route('siswa.jadwal.available') }}" class="btn btn-primary btn-sm">+ Cari Slot Jadwal</a>
        </div>

        @if($jadwalTerdekat && $jadwalTerdekat->jadwal)
            <div style="background-color:#F0FDF4; border:1px solid #BBF7D0; padding:1.25rem; border-radius:0.5rem;">
                <div style="font-weight:700; color:var(--primary-dark); font-size:1.05rem;">
                    {{ \Carbon\Carbon::parse($jadwalTerdekat->jadwal->tanggal_tersedia)->format('d F Y') }}
                </div>
                <div style="font-size:0.875rem; margin-top:0.35rem; color:var(--text-dark);">
                    <strong>Waktu:</strong> {{ substr($jadwalTerdekat->jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwalTerdekat->jadwal->jam_selesai, 0, 5) }} WIB<br>
                    <strong>Guru BK:</strong> {{ $jadwalTerdekat->jadwal->guruBk->nama_lengkap ?? 'Guru BK SMKN 2 Guguak' }}<br>
                    <strong>Jenis Layanan:</strong> {{ ucfirst($jadwalTerdekat->jenis_konseling) }}<br>
                    <strong>Catatan BK:</strong> {{ $jadwalTerdekat->catatan_validasi ?? 'Harap hadir tepat waktu di ruang BK.' }}
                </div>
            </div>
        @else
            <p style="color:var(--text-muted); margin:0;">Anda belum memiliki jadwal konseling aktif terdekat.</p>
            <div style="margin-top:1rem;">
                <a href="{{ route('siswa.jadwal.available') }}" class="btn btn-primary">
                    + Ajukan Jadwal Konseling Sekarang
                </a>
            </div>
        @endif
    </div>

    {{-- Profil Kontak Orang Tua --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Profil & Kontak Orang Tua / Wali</h3>
            <a href="{{ route('profile.edit') }}" style="font-size:0.875rem; color:var(--primary); font-weight:700; text-decoration:none;">Perbarui &rarr;</a>
        </div>
        <p style="font-size:0.825rem; color:var(--text-muted); margin-top:0;">Pastikan kontak WhatsApp Orang Tua terdaftar untuk validasi dan konfirmasi surat panggilan resmi.</p>
        <ul style="list-style:none; padding:0; margin:0; font-size:0.875rem;">
            <li style="padding:0.4rem 0; border-bottom:1px solid var(--border-color);"><strong>NIS / NISN:</strong> {{ $siswa->nis ?? '-' }} / {{ $siswa->nisn ?? '-' }}</li>
            <li style="padding:0.4rem 0; border-bottom:1px solid var(--border-color);"><strong>Kelas:</strong> {{ $siswa->kelas->nama_kelas ?? '-' }}</li>
            <li style="padding:0.4rem 0; border-bottom:1px solid var(--border-color);"><strong>No. WA Siswa:</strong> <code>{{ $siswa->no_wa_siswa ?? '-' }}</code></li>
            <li style="padding:0.4rem 0; border-bottom:1px solid var(--border-color);"><strong>Nama Orang Tua / Wali:</strong> {{ $siswa->nama_orang_tua_wali ?: '-' }}</li>
            <li style="padding:0.4rem 0;"><strong>No. WA Orang Tua:</strong> <code>{{ $siswa->no_wa_orang_tua_wali ?: 'Belum diisi' }}</code></li>
        </ul>
    </div>
</div>

@endsection