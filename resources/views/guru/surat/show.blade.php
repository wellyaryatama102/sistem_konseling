{{-- VIEW PRATINJAU SURAT PANGGILAN: Tampilan cetak kop surat panggilan resmi & tombol pengiriman WA Gateway --}}
@extends('layouts.app')
@section('title', 'Pratinjau Surat Panggilan')

@section('content')

<div style="max-width:850px; margin:0 auto;">
    @php
        $siswa = $surat->tindakLanjut->sesiKonseling->pengajuan->siswa ?? null;
    @endphp

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
        <div>
            <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Pratinjau & Pengiriman Surat Panggilan</h2>
            <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Status Kirim WhatsApp: 
                <span class="badge badge-{{ $surat->status_kirim_wa == 'terkirim' ? 'success' : ($surat->status_kirim_wa == 'gagal' ? 'danger' : 'warning') }}">
                    {{ strtoupper($surat->status_kirim_wa) }}
                </span>
            </p>
        </div>

        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <button onclick="window.print()" class="btn btn-secondary">Cetak Surat</button>
            <a href="{{ route('guru.surat.pdf', $surat->id_surat) }}" class="btn btn-primary">Download PDF</a>
            <form action="{{ route('guru.surat.kirim-wa', $surat->id_surat) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-{{ $surat->status_kirim_wa == 'gagal' ? 'danger' : 'success' }}"
                        onclick="return confirm('Kirim notifikasi surat panggilan ini via WhatsApp Gateway?')">
                    {{ $surat->status_kirim_wa == 'gagal' ? 'Kirim Ulang WA' : 'Kirim WA' }}
                </button>
            </form>
            <a href="{{ route('guru.surat.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    {{-- Detail Status Pengiriman --}}
    <div class="card" style="margin-bottom:1.5rem; border-left:4px solid var(--primary);">
        <h3 class="card-title" style="margin-bottom:0.75rem;">Informasi Pengiriman WhatsApp</h3>
        <table style="width:100%; font-size:0.875rem;">
            <tr><td style="font-weight:600; width:180px; padding:0.25rem 0;">Penerima Ortu/Wali</td><td>: {{ $siswa->nama_orang_tua_wali ?: 'Bapak/Ibu Orang Tua/Wali' }} (Orang Tua dari {{ $siswa->nama_siswa ?? '-' }})</td></tr>
            <tr><td style="font-weight:600; padding:0.25rem 0;">Nomor WhatsApp Tujuan</td><td>: <code>{{ $siswa->no_wa_orang_tua_wali ?: 'Belum diisi' }}</code></td></tr>
            <tr><td style="font-weight:600; padding:0.25rem 0;">Jadwal Pertemuan</td><td>: {{ \Carbon\Carbon::parse($surat->tanggal_pertemuan)->format('d F Y') }} jam {{ substr($surat->waktu_pertemuan, 0, 5) }} WIB</td></tr>
            <tr><td style="font-weight:600; padding:0.25rem 0;">Status Gateway WA</td><td>: 
                <span class="badge badge-{{ $surat->status_kirim_wa == 'terkirim' ? 'success' : ($surat->status_kirim_wa == 'gagal' ? 'danger' : 'warning') }}">
                    {{ strtoupper($surat->status_kirim_wa) }}
                </span>
            </td></tr>
        </table>
    </div>

    {{-- KOP SEKOLAH & FORMAT SURAT PANGGILAN RESMI --}}
    <div class="card" id="suratPrintArea" style="background:white; padding:2.5rem; border:1px solid #cbd5e1; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);">
        
        <!-- PANGGIL KOP SURAT OTOMATIS -->
        @include('components.kop-surat')

        {{-- Nomor & Tanggal Surat --}}
        <div style="display:flex; justify-content:space-between; margin-bottom:1.5rem; font-size:0.9rem;">
            <div>
                <div><strong>Nomor:</strong> {{ $surat->nomor_surat }}</div>
                <div><strong>Lampiran:</strong> -</div>
                <div><strong>Perihal:</strong> {{ $surat->perihal }}</div>
            </div>
            <div style="text-align:right;">
                Guguak, {{ \Carbon\Carbon::parse($surat->tanggal_terbit)->format('d F Y') }}
            </div>
        </div>

        {{-- Tujuan Surat --}}
        <div style="margin-bottom:1.5rem; font-size:0.9rem; line-height:1.6;">
            <div>Kepada Yth.</div>
            <div><strong>{{ $siswa->nama_orang_tua_wali ?: 'Bapak/Ibu Orang Tua / Wali' }}</strong></div>
            <div>dari siswa: <strong>{{ $siswa->nama_siswa ?? '-' }}</strong> (Kelas {{ $siswa->kelas->nama_kelas ?? '-' }})</div>
            <div>di Tempat</div>
        </div>

        {{-- Isi Surat --}}
        <div style="font-size:0.9rem; line-height:1.7; text-align:justify; margin-bottom:1.5rem;">
            <p style="margin-bottom:1rem;">Dengan hormat,</p>
            <p style="margin-bottom:1rem;">{{ $surat->isi_surat }}</p>
            <p style="margin-bottom:0.5rem;">Berkenaan dengan hal tersebut, kami mengharapkan kehadiran Bapak/Ibu pada:</p>

            <table style="margin-left:1.5rem; margin-bottom:1rem; width:calc(100% - 1.5rem); font-size:0.9rem;">
                <tr><td style="width:140px; font-weight:600; padding:0.25rem 0;">Hari / Tanggal</td><td>: {{ \Carbon\Carbon::parse($surat->tanggal_pertemuan)->format('d F Y') }}</td></tr>
                <tr><td style="font-weight:600; padding:0.25rem 0;">Waktu</td><td>: {{ substr($surat->waktu_pertemuan, 0, 5) }} WIB</td></tr>
                <tr><td style="font-weight:600; padding:0.25rem 0;">Tempat</td><td>: {{ $surat->tempat }}</td></tr>
            </table>

            <p>Mengingat pentingnya pertemuan ini demi kebaikan dan perkembangan belajar siswa, kehadiran Bapak/Ibu sangat kami harapkan tepat pada waktunya.</p>
            <p style="margin-top:1rem;">Demikian surat panggilan ini kami sampaikan. Atas perhatian dan kerja sama Bapak/Ibu, kami ucapkan terima kasih.</p>
        </div>

        {{-- Tanda Tangan Guru BK --}}
        <div style="display:flex; justify-content:flex-end; margin-top:3rem;">
            <div style="text-align:center; min-width:220px; font-size:0.9rem;">
                <div>Guru Bimbingan dan Konseling,</div>
                <div style="height:60px;"></div>
                <div style="font-weight:700; text-decoration:underline;">{{ $surat->guruBk->nama_lengkap ?? 'Guru BK' }}</div>
                <div style="color:#64748b; font-size:0.8rem;">NIP. {{ $surat->guruBk->nip ?? '-' }}</div>
            </div>
        </div>

    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #suratPrintArea, #suratPrintArea * { visibility: visible; }
    #suratPrintArea { position: absolute; left: 0; top: 0; width: 100%; border: none !important; box-shadow: none !important; }
}
</style>

@endsection