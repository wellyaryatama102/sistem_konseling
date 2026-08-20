<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Panggilan Orang Tua - SMKN 2 Guguak</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11pt; line-height: 1.5; color: #000; margin: 25px; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 15px; }
        .header h4, .header h3, .header h2, .header p { margin: 0; }
        .title { text-align: center; font-weight: bold; font-size: 13pt; margin-bottom: 15px; text-decoration: underline; }
        .meta-table { width: 100%; margin-bottom: 15px; }
        .meta-table td { vertical-align: top; }
        .content { margin-bottom: 25px; text-align: justify; }
        .footer { width: 100%; margin-top: 30px; }
        .footer td { text-align: center; vertical-align: top; }
    </style>
</head>
<body>

    @php
        $siswa = $surat->tindakLanjut->sesiKonseling->pengajuan->siswa ?? null;
        $logoPath = public_path('images/logo_smk.png');
        $logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
    @endphp

    <table style="width: 100%; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 15px;">
        <tr>
            <td style="width: 75px; text-align: center; vertical-align: middle;">
                @if($logoBase64)
                    <img src="data:image/png;base64,{{ $logoBase64 }}" style="width: 65px; height: 65px;">
                @endif
            </td>
            <td style="text-align: center; vertical-align: middle;">
                <h4 style="margin: 0; font-size: 10pt; font-weight: bold; text-transform: uppercase;">PEMERINTAH PROVINSI SUMATERA BARAT</h4>
                <h3 style="margin: 0; font-size: 11.5pt; font-weight: bold; text-transform: uppercase;">DINAS PENDIDIKAN - CABANG DINAS WILAYAH IV</h3>
                <h2 style="margin: 0; font-size: 14pt; font-weight: 900; text-transform: uppercase;">SMK NEGERI 2 GUGUAK</h2>
                <p style="margin: 2px 0 0 0; font-size: 8.5pt;">Jl. Raya Ampang Gadang, Kec. Guguak, Kab. Lima Puluh Kota, Sumatera Barat 26253</p>
            </td>
            <td style="width: 75px;"></td>
        </tr>
    </table>

    <div class="title">SURAT PANGGILAN ORANG TUA / WALI SISWA</div>

    <table class="meta-table">
        <tr>
            <td width="120">Nomor Surat</td>
            <td width="10">:</td>
            <td><strong>{{ $surat->nomor_surat }}</strong></td>
        </tr>
        <tr>
            <td>Tanggal Surat</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($surat->tanggal_terbit)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Hal</td>
            <td>:</td>
            <td><strong>{{ $surat->perihal }}</strong></td>
        </tr>
    </table>

    <div class="content">
        <p>Kepada Yth.<br>
        <strong>{{ $siswa->nama_orang_tua_wali ?: 'Bapak/Ibu Orang Tua / Wali' }}</strong><br>
        dari siswa: <strong>{{ $siswa->nama_siswa ?? '-' }}</strong> (Kelas {{ $siswa->kelas->nama_kelas ?? '-' }})<br>
        di Tempat</p>

        <p>Dengan hormat,</p>
        <p>{{ $surat->isi_surat }}</p>

        <p style="margin-bottom: 5px;">Berkenaan dengan hal tersebut, kami mengharapkan kehadiran Bapak/Ibu pada:</p>

        <table style="margin-left: 20px; margin-bottom: 15px;">
            <tr>
                <td width="140">Hari / Tanggal</td>
                <td width="10">:</td>
                <td><strong>{{ \Carbon\Carbon::parse($surat->tanggal_pertemuan)->format('d F Y') }}</strong></td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>:</td>
                <td><strong>{{ substr($surat->waktu_pertemuan, 0, 5) }} WIB</strong></td>
            </tr>
            <tr>
                <td>Tempat</td>
                <td>:</td>
                <td>{{ $surat->tempat }}</td>
            </tr>
        </table>

        <p>Mengingat pentingnya pertemuan ini demi kebaikan dan perkembangan belajar siswa, kami sangat mengharapkan kehadiran Bapak/Ibu tepat pada waktu yang telah ditentukan. Atas perhatian dan kerja samanya, kami ucapkan terima kasih.</p>
    </div>

    <table class="footer">
        <tr>
            <td width="50%"></td>
            <td width="50%">
                Guguak, {{ \Carbon\Carbon::parse($surat->tanggal_terbit)->format('d F Y') }}<br>
                Guru Bimbingan dan Konseling,<br><br><br><br>
                <strong><u>{{ $surat->guruBk->nama_lengkap ?? 'Guru BK' }}</u></strong><br>
                <small>NIP. {{ $surat->guruBk->nip ?? '-' }}</small>
            </td>
        </tr>
    </table>

</body>
</html>
