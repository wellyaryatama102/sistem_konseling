{{-- VIEW DASHBOARD SISWA: Beranda statistik pengajuan, jadwal terdekat, arahan konseling, & profil --}}
@extends('layouts.app')
@section('title', 'Beranda Siswa')

@section('content')

{{-- Header Welcome --}}
<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Selamat Datang, {{ $siswa->nama_siswa }}</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">
        Sistem Informasi Layanan Konseling Siswa (SIKS) SMK Negeri 2 Guguak.
    </p>
</div>

{{-- Kartu Statistik Ringkasan Pengajuan Siswa --}}
<div class="grid-3" style="margin-bottom:1.5rem;">
    <div class="stat-card">
        <span class="stat-lbl">TOTAL PENGAJUAN SAYA</span>
        <span class="stat-val">{{ $stats['total_pengajuan'] }}</span>
        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.35rem; font-weight:600;">
            Mandiri &amp; Rujukan
        </div>
    </div>
    <div class="stat-card">
        <span class="stat-lbl">DISETUJUI GURU BK</span>
        <span class="stat-val" style="color:var(--success);">{{ $stats['disetujui'] }}</span>
        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.35rem; font-weight:600;">
            Sesi Siap/Selesai
        </div>
    </div>
    <div class="stat-card gold">
        <span class="stat-lbl">MENUNGGU VALIDASI</span>
        <span class="stat-val" style="color:var(--accent-gold);">{{ $stats['menunggu'] }}</span>
        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.35rem; font-weight:600;">
            Proses Pemeriksaan BK
        </div>
    </div>
</div>

{{-- Alert Khusus Tindak Lanjut Pemanggilan Orang Tua / Slot Lanjutan --}}
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

{{-- Alert Peringatan Jika Kontak WA Orang Tua Belum Diisi --}}
@if(empty($siswa->no_wa_orang_tua_wali))
    <div style="background:#EFF6FF; border:1px solid #BFDBFE; border-radius:0.5rem; padding:1rem; margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">
        <div style="font-size:0.875rem; color:#1E40AF;">
            <strong>Penting:</strong> Nomor WhatsApp Orang Tua/Wali Anda belum diisi. Harap perbarui data profil agar notifikasi layanan konseling dan panggilan resmi dapat terkirim.
        </div>
        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary" style="white-space:nowrap;">Lengkapi Profil</a>
    </div>
@endif

{{-- Grid Utama: Jadwal Terdekat & Profil Kontak Orang Tua --}}
<div class="grid-2" style="margin-bottom:1.5rem; gap:1.5rem; align-items:start;">
    
    {{-- Jadwal Terdekat --}}
    <div class="card" style="margin-bottom:0;">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <h3 class="card-title" style="font-size:1rem; margin:0;">Jadwal Sesi Konseling Terdekat</h3>
            <a href="{{ route('siswa.jadwal.available') }}" class="btn btn-primary btn-sm">+ Cari Slot Jadwal</a>
        </div>

        @if($jadwalTerdekat && $jadwalTerdekat->jadwal)
            <div style="background-color:#F0FDF4; border:1px solid #BBF7D0; padding:1.25rem; border-radius:0.5rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                    <span style="font-weight:800; color:var(--primary-dark); font-size:1.05rem;">
                        {{ \Carbon\Carbon::parse($jadwalTerdekat->jadwal->tanggal_tersedia)->translatedFormat('l, d F Y') }}
                    </span>
                    <span class="badge badge-success" style="text-transform:uppercase;">Disetujui</span>
                </div>
                <div style="font-size:0.875rem; line-height:1.6; color:var(--text-dark);">
                    <strong>Waktu:</strong> {{ substr($jadwalTerdekat->jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwalTerdekat->jadwal->jam_selesai, 0, 5) }} WIB<br>
                    <strong>Guru BK:</strong> {{ $jadwalTerdekat->jadwal->guruBk->nama_lengkap ?? 'Guru BK SMKN 2 Guguak' }}<br>
                    <strong>Jenis Layanan:</strong> {{ ucfirst($jadwalTerdekat->jenis_konseling) }} 
                    <small style="color:var(--text-muted);">({{ $jadwalTerdekat->sumber_pengajuan == 'rujukan' ? 'Rujukan Wali Kelas' : 'Pengajuan Mandiri' }})</small><br>
                    <strong>Catatan BK:</strong> {{ $jadwalTerdekat->catatan_validasi ?? 'Harap hadir tepat waktu di ruang BK.' }}
                </div>
            </div>
        @else
            <div style="text-align:center; padding:1.5rem 0;">
                <p style="color:var(--text-muted); margin-bottom:1rem; font-size:0.875rem;">Anda belum memiliki jadwal konseling aktif terdekat.</p>
                <a href="{{ route('siswa.jadwal.available') }}" class="btn btn-primary btn-sm">
                    + Ajukan Jadwal Konseling Sekarang
                </a>
            </div>
        @endif
    </div>

    {{-- Profil Kontak Orang Tua --}}
    <div class="card" style="margin-bottom:0;">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <h3 class="card-title" style="font-size:1rem; margin:0;">Profil &amp; Kontak Orang Tua / Wali</h3>
            <a href="{{ route('profile.edit') }}" style="font-size:0.875rem; color:var(--primary); font-weight:700; text-decoration:none;">Perbarui &rarr;</a>
        </div>
        <p style="font-size:0.8rem; color:var(--text-muted); margin-top:0.25rem; margin-bottom:0.75rem;">
            Pastikan kontak WhatsApp Orang Tua terdaftar untuk validasi dan konfirmasi surat panggilan resmi.
        </p>
        <ul style="list-style:none; padding:0; margin:0; font-size:0.875rem;">
            <li style="padding:0.45rem 0; border-bottom:1px solid var(--border-color); display:flex; justify-content:space-between;">
                <span style="color:var(--text-muted);">NIS / NISN</span>
                <strong>{{ $siswa->nis ?? '-' }} / {{ $siswa->nisn ?? '-' }}</strong>
            </li>
            <li style="padding:0.45rem 0; border-bottom:1px solid var(--border-color); display:flex; justify-content:space-between;">
                <span style="color:var(--text-muted);">Kelas Saat Ini</span>
                <strong>{{ $siswa->kelas->nama_kelas ?? '-' }}</strong>
            </li>
            <li style="padding:0.45rem 0; border-bottom:1px solid var(--border-color); display:flex; justify-content:space-between;">
                <span style="color:var(--text-muted);">No. WA Saya</span>
                <code>{{ $siswa->no_wa_siswa ?? '-' }}</code>
            </li>
            <li style="padding:0.45rem 0; border-bottom:1px solid var(--border-color); display:flex; justify-content:space-between;">
                <span style="color:var(--text-muted);">Nama Orang Tua / Wali</span>
                <strong>{{ $siswa->nama_orang_tua_wali ?: '-' }}</strong>
            </li>
            <li style="padding:0.45rem 0; display:flex; justify-content:space-between;">
                <span style="color:var(--text-muted);">No. WA Orang Tua</span>
                <code>{{ $siswa->no_wa_orang_tua_wali ?: 'Belum diisi' }}</code>
            </li>
        </ul>
    </div>

</div>

{{-- Tabel Riwayat Pengajuan & Arahan Konseling Terbaru --}}
<div class="card">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h3 class="card-title" style="font-size:1rem; margin:0;">Riwayat Pengajuan &amp; Arahan Konseling Terbaru</h3>
        <a href="{{ route('siswa.pengajuan.index') }}" class="btn btn-secondary btn-sm">Lihat Semua Riwayat</a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Tanggal / Sumber</th>
                    <th>Jenis &amp; Alasan</th>
                    <th>Status Validasi</th>
                    <th>Arahan Umum Guru BK</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatPengajuan ?? [] as $pengajuan)
                <tr>
                    <td>
                        <strong>{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d/m/Y') }}</strong><br>
                        <small style="color:var(--text-muted);">
                            {{ $pengajuan->sumber_pengajuan == 'rujukan' ? 'Rujukan Wali Kelas' : ($pengajuan->sumber_pengajuan == 'guru_bk' ? 'Jadwal BK' : 'Pengajuan Mandiri') }}
                        </small>
                    </td>
                    <td>
                        <strong>{{ ucfirst($pengajuan->jenis_konseling) }}</strong><br>
                        <small style="color:var(--text-muted);">{{ \Illuminate\Support\Str::limit($pengajuan->alasan_pengajuan, 40) }}</small>
                    </td>
                    <td>
                        @if($pengajuan->status_pengajuan === 'disetujui')
                            <span class="badge badge-success">Disetujui</span>
                        @elseif($pengajuan->status_pengajuan === 'menunggu_validasi')
                            <span class="badge badge-warning">Menunggu</span>
                        @elseif($pengajuan->status_pengajuan === 'dibatalkan')
                            <span class="badge badge-secondary">Dibatalkan</span>
                        @else
                            <span class="badge badge-danger">Ditolak</span>
                        @endif
                    </td>
                    <td style="font-size:0.85rem; color:var(--text-dark);">
                        {{ $pengajuan->sesiKonseling->catatan_untuk_siswa ?? ($pengajuan->catatan_validasi ?? '-') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding:1.5rem; color:var(--text-muted);">
                        Belum ada riwayat pengajuan konseling.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection