@if(isset($isExcel) && $isExcel == true)
    {{-- MODE EXCEL: TANPA GAMBAR, HANYA TEKS DALAM TABEL --}}
    @php 
        $kolom = $colspan ?? 10; 
    @endphp
    <tr>
        <td colspan="{{ $kolom }}" style="text-align: center; font-weight: bold; font-size: 13pt;">PEMERINTAH PROVINSI SUMATERA BARAT</td>
    </tr>
    <tr>
        <td colspan="{{ $kolom }}" style="text-align: center; font-weight: bold; font-size: 14pt;">DINAS PENDIDIKAN</td>
    </tr>
    <tr>
        <td colspan="{{ $kolom }}" style="text-align: center; font-weight: bold; font-size: 16pt;">SMK NEGERI 2 GUGUAK</td>
    </tr>
    <tr>
        <td colspan="{{ $kolom }}" style="text-align: center; font-size: 9pt; font-style: italic;">Jl. Raya Ampang Gadang, Kec. Guguak, Kab. Lima Puluh Kota, Sumatera Barat 26253</td>
    </tr>
    {{-- GARIS BAWAH KOP EXCEL --}}
    <tr>
        <td colspan="{{ $kolom }}" style="border-bottom: 3px double #000000; height: 5px;"></td>
    </tr>
    <tr><td colspan="{{ $kolom }}" style="height: 10px;"></td></tr>

@else
    {{-- MODE PDF / WEB: LENGKAP DENGAN GAMBAR LOGO --}}    
    @php
        // LOGIKA GAMBAR ANTI ERROR
        $logoSumbarPath = public_path('images/sumbar.png');
        $logoSumbar = file_exists($logoSumbarPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoSumbarPath)) : asset('images/sumbar.png');
        
        $logoSmkPath = public_path('images/logo_smk.png');
        $logoSmk = file_exists($logoSmkPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoSmkPath)) : asset('images/logo_smk.png');
    @endphp

    <table style="width: 100%; padding-bottom: 5px; margin-bottom: 5px; border-collapse: collapse; border: none; background-color: transparent;">
        <tr>
            <td style="width: 85px; text-align: left; vertical-align: middle; border: none; padding: 0;">
                <img src="{{ $logoSumbar }}" style="width: 65px; height: 75px; object-fit: contain;" alt="Logo Sumbar">
            </td>
            <td style="text-align: center; vertical-align: middle; padding: 0 5px; border: none;">
                <h4 style="margin: 0; font-size: 10pt; font-weight: bold; text-transform: uppercase; font-family: Arial, sans-serif; color: #000;">PEMERINTAH PROVINSI SUMATERA BARAT</h4>
                <h4 style="margin: 0; font-size: 11pt; font-weight: bold; text-transform: uppercase; font-family: Arial, sans-serif; color: #000;">DINAS PENDIDIKAN</h4>
                <h2 style="margin: 2px 0; font-size: 14pt; font-weight: 900; text-transform: uppercase; font-family: Arial, sans-serif; color: #000;">SMK NEGERI 2 GUGUAK</h2>
                <p style="margin: 2px 0 0 0; font-size: 8pt; font-family: Arial, sans-serif; color: #000;">
                    Jl. Raya Ampang Gadang, Kec. Guguak, Kab. Lima Puluh Kota, Sumatera Barat 26253<br>
                    <span style="font-size: 7.5pt;">Website: www.smkn2guguak.sch.id | Email: info@smkn2guguak.sch.id</span>
                </p>
            </td>
            <td style="width: 85px; text-align: right; vertical-align: middle; border: none; padding: 0;">
                <img src="{{ $logoSmk }}" style="width: 70px; height: 70px; object-fit: contain;" alt="Logo SMK">
            </td>
        </tr>
    </table>
    
    {{-- GARIS BAWAH KOP--}}
    <hr style="border: none; border-top: 3px double #000; margin-top: 0; margin-bottom: 15px;">
    
@endif