@php
    // Logika ini memastikan logo terbaca baik saat dirender di Web biasa maupun dicetak ke PDF (DOMPDF)
    $logoSumbarPath = public_path('images/sumbar.png');
    $logoSumbar = file_exists($logoSumbarPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoSumbarPath)) : asset('images/sumbar.png');
    
    $logoSmkPath = public_path('images/logo_smk.png');
    $logoSmk = file_exists($logoSmkPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoSmkPath)) : asset('images/logo_smk.png');
@endphp

<table style="width: 100%; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 15px; border-collapse: collapse; border: none;">
    <tr>
        <!-- LOGO KIRI (SUMBAR) -->
        <td style="width: 90px; text-align: left; vertical-align: middle; border: none;">
            <img src="{{ $sumbar.png }}" style="width: 65px; height: 75px; object-fit: contain;" alt="Logo Sumbar">
        </td>

        <!-- IDENTITAS SEKOLAH -->
        <td style="text-align: center; vertical-align: middle; padding: 0 5px; border: none;">
            <h4 style="margin: 0; font-size: 10pt; font-weight: bold; text-transform: uppercase; font-family: Arial, sans-serif;">PEMERINTAH PROVINSI SUMATERA BARAT</h4>
            <h4 style="margin: 0; font-size: 11pt; font-weight: bold; text-transform: uppercase; font-family: Arial, sans-serif;">DINAS PENDIDIKAN</h4>
            <h2 style="margin: 2px 0; font-size: 14pt; font-weight: 900; text-transform: uppercase; font-family: Arial, sans-serif;">SMK NEGERI 2 GUGUAK</h2>
            <p style="margin: 2px 0 0 0; font-size: 8pt; font-family: Arial, sans-serif;">
                Jl. Raya Ampang Gadang, Kec. Guguak, Kab. Lima Puluh Kota, Sumatera Barat 26253<br>
                <span style="font-size: 7.5pt;">Website: www.smkn2guguak.sch.id | Email: info@smkn2guguak.sch.id</span>
            </p>
        </td>

        <!-- LOGO KANAN (SMK) -->
        <td style="width: 90px; text-align: right; vertical-align: middle; border: none;">
            <img src="{{ $logo_Smk }}" style="width: 70px; height: 70px; object-fit: contain;" alt="Logo SMK">
        </td>
    </tr>
</table>