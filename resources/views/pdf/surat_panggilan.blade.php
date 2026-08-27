<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Panggilan Orang Tua - SMKN 2 Guguak</title>

    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11pt; line-height: 1.5; color: #000; margin: 25px; }
        .title { text-align: center; font-weight: bold; font-size: 13pt; margin-bottom: 15px; text-decoration: underline; text-transform: uppercase; }
        .meta-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .meta-table td { vertical-align: top; padding: 2px 0; }
        .content { margin-bottom: 25px; text-align: justify; }
        .footer { width: 100%; margin-top: 30px; }
        .footer td { text-align: center; vertical-align: top; }
    </style>
</head>
<body>

    @php
        $siswa = $surat->tindakLanjut->sesiKonseling->pengajuan->siswa ?? null;
    @endphp

    <!-- PANGGIL KOP SURAT OTOMATIS -->
    @include('components.kop-surat')

    <div class="title">
        SURAT PANGGILAN ORANG TUA / WALI SISWA
    </div>

    <table class="meta-table">
        <tr>
            <td width="120">Nomor</td>
            <td width="10">:</td>
            <td><strong>{{ $surat->nomor_surat }}</strong></td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Hal</td>
            <td>:</td>
            <td><strong>{{ $surat->perihal ?: 'Undangan Orang Tua / Wali Siswa' }}</strong></td>
        </tr>
    </table>

    <div class="content">
        <table class="meta-table" style="margin-bottom: 10px;">
            <tr><td width="120">Kepada Yth,</td><td width="10">:</td><td></td></tr>
            <tr>
                <td>Bapak/Ibu/Sdr</td>
                <td>:</td>
                <td><strong>{{ $siswa->nama_orang_tua_wali ?: 'Orang Tua / Wali' }}</strong></td>
            </tr>
            <tr>
                <td>Orang Tua/Wali dari</td>
                <td>:</td>
                <td><strong>{{ $siswa->nama_siswa ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td>Siswa Kelas</td>
                <td>:</td>
                <td><strong>{{ $siswa->kelas->nama_kelas ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td>di</td>
                <td>:</td>
                <td>SMK Negeri 2 Kec. Guguak<br><u>Tempat</u></td>
            </tr>
        </table>

        <p>
            Dengan hormat, untuk kelancaran Kegiatan Belajar Mengajar anak kita di sekolah serta menindaklanjuti proses Bimbingan Konseling (BK), maka kami mengharapkan kedatangan Bapak/Ibu/Sdr untuk dapat hadir pada:
        </p>

        <table style="margin-left: 20px; margin-bottom: 15px;">
            <tr>
                <td width="140">Hari / Tanggal</td>
                <td width="10">:</td>
                <td><strong>{{ \Carbon\Carbon::parse($surat->tanggal_pertemuan)->translatedFormat('l, d F Y') }}</strong></td>
            </tr>
            <tr>
                <td>Pukul / Jam</td>
                <td>:</td>
                <td><strong>{{ substr($surat->waktu_pertemuan, 0, 5) }} WIB</strong></td>
            </tr>
            <tr>
                <td>Tempat</td>
                <td>:</td>
                <td>{{ $surat->tempat ?: 'Ruang Bimbingan Konseling (BK) SMKN 2 Guguak' }}</td>
            </tr>
        </table>

        <p>Sehubungan dengan masalah / agenda bimbingan yang dihadapi anak kita, yaitu:</p>

        <div style="margin-left: 20px; margin-bottom: 15px;">
            {!! $surat->isi_surat !!}
        </div>

        <p>
            Mengingat pentingnya pertemuan ini demi kebaikan, perkembangan belajar, dan masa depan siswa, kami sangat mengharapkan kehadiran Bapak/Ibu tepat pada waktunya. Atas perhatian, kehadiran, dan kerja samanya, kami ucapkan terima kasih.
        </p>
    </div>

    <table class="footer">
        <tr>
            <td width="50%"></td>
            <td width="50%">
                Ampang Gadang, {{ \Carbon\Carbon::parse($surat->tanggal_terbit)->translatedFormat('d F Y') }}
                <br>Guru Bimbingan dan Konseling (BK),
                <br><br><br><br>
                <strong><u>{{ $surat->guruBk->nama_lengkap ?? 'Fadillah Syukria Putri, S.Psi' }}</u></strong>
                <br><small>NIP. {{ $surat->guruBk->nip ?? '-' }}</small>
            </td>
        </tr>
    </table>
</body>
</html>