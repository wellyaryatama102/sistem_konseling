<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\TahunAjaran;
use App\Models\Jurusan;
use App\Models\Admin;
use App\Models\GuruBk;
use App\Models\WaliKelas;
use App\Models\Wakasis;
use App\Models\Kepsek;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\JadwalKetersediaan;
use App\Models\PengajuanKonseling;
use App\Models\SesiKonseling;
use App\Models\TindakLanjut;
use App\Models\SuratPanggilan;
use App\Models\Notifikasi;
use App\Models\WaLog;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Master Data Tahun Ajaran
        $taAktif = TahunAjaran::create([
            'nama_tahun_ajaran' => '2026/2027',
            'status_aktif' => true,
        ]);

        TahunAjaran::create([
            'nama_tahun_ajaran' => '2025/2026',
            'status_aktif' => false,
        ]);

        // 2. Master Data Jurusan
        $jurPPLG = Jurusan::create(['nama_jurusan' => 'PPLG']);
        $jurAKL  = Jurusan::create(['nama_jurusan' => 'AKL']);
        $jurTJKT = Jurusan::create(['nama_jurusan' => 'TJKT']);
        $jurDKV  = Jurusan::create(['nama_jurusan' => 'DKV']);
        $jurTO   = Jurusan::create(['nama_jurusan' => 'TO']);

        // 3. User & Entitas Admin
        $userAdmin = User::create([
            'name' => 'Administrator BK',
            'username' => 'admin',
            'email' => 'admin@smkn2guguak.sch.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        Admin::create([
            'user_id' => $userAdmin->id,
            'username' => 'admin',
            'password' => Hash::make('password'),
            'nip' => '19800101 200501 1 001',
            'nama_lengkap' => 'Administrator BK',
            'email' => 'admin@smkn2guguak.sch.id',
            'no_hp' => '081234567890',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Lima Puluh Kota',
            'tanggal_lahir' => '1980-01-01',
            'alamat' => 'Guguak, Kab. Lima Puluh Kota',
            'pendidikan_terakhir' => 'S1 Teknik Informatika',
        ]);

        // 4. User & Entitas Guru BK
        $userGuru = User::create([
            'name' => 'Ahmad Fauzi, S.Pd., Gr.',
            'username' => 'gurubk',
            'email' => 'gurubk@smkn2guguak.sch.id',
            'password' => Hash::make('password'),
            'role' => 'guru_bk',
            'status' => 'active',
        ]);

        $guruBk = GuruBk::create([
            'user_id' => $userGuru->id,
            'username' => 'gurubk',
            'password' => Hash::make('password'),
            'nip' => '19850512 201001 1 012',
            'nama_lengkap' => 'Ahmad Fauzi, S.Pd., Gr.',
            'email' => 'gurubk@smkn2guguak.sch.id',
            'no_hp' => '081267890011',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Payakumbuh',
            'tanggal_lahir' => '1985-05-12',
            'alamat' => 'Jl. Tan Malaka No. 45, Kec. Guguak',
            'jabatan' => 'Guru Bimbingan dan Konseling',
        ]);

        // 5. User & Entitas Wali Kelas
        $userWali1 = User::create([
            'name' => 'Siti Rahma, S.Pd.',
            'username' => 'walikelas1',
            'email' => 'walikelas1@smkn2guguak.sch.id',
            'password' => Hash::make('password'),
            'role' => 'wali_kelas',
            'status' => 'active',
        ]);

        $wali1 = WaliKelas::create([
            'user_id' => $userWali1->id,
            'username' => 'walikelas1',
            'password' => Hash::make('password'),
            'nip_nuptk' => '19880315 201201 2 005',
            'nama_lengkap' => 'Siti Rahma, S.Pd.',
            'email' => 'walikelas1@smkn2guguak.sch.id',
            'no_hp' => '081345678901',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Guguak',
            'tanggal_lahir' => '1988-03-15',
            'alamat' => 'Kec. Guguak, Kab. Lima Puluh Kota',
            'jabatan' => 'Guru & Wali Kelas X PPLG 1',
        ]);

        $userWali2 = User::create([
            'name' => 'Rina Wulandari, M.Pd.',
            'username' => 'walikelas2',
            'email' => 'walikelas2@smkn2guguak.sch.id',
            'password' => Hash::make('password'),
            'role' => 'wali_kelas',
            'status' => 'active',
        ]);

        $wali2 = WaliKelas::create([
            'user_id' => $userWali2->id,
            'username' => 'walikelas2',
            'password' => Hash::make('password'),
            'nip_nuptk' => '19860920 201101 2 008',
            'nama_lengkap' => 'Rina Wulandari, M.Pd.',
            'email' => 'walikelas2@smkn2guguak.sch.id',
            'no_hp' => '081345678902',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Payakumbuh',
            'tanggal_lahir' => '1986-09-20',
            'alamat' => 'Payakumbuh',
            'jabatan' => 'Guru & Wali Kelas XI AKL 2',
        ]);

        // 6. User & Entitas Wakasis
        $userWakasis = User::create([
            'name' => 'Drs. H. Mulyadi, M.M.',
            'username' => 'wakasis',
            'email' => 'wakasis@smkn2guguak.sch.id',
            'password' => Hash::make('password'),
            'role' => 'wakasis',
            'status' => 'active',
        ]);

        Wakasis::create([
            'user_id' => $userWakasis->id,
            'username' => 'wakasis',
            'password' => Hash::make('password'),
            'nip' => '19720410 199802 1 003',
            'nama_lengkap' => 'Drs. H. Mulyadi, M.M.',
            'email' => 'wakasis@smkn2guguak.sch.id',
            'no_hp' => '081278901234',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Bukittinggi',
            'tanggal_lahir' => '1972-04-10',
            'alamat' => 'Jl. Khatib Sulaiman No. 10, Guguak',
            'jabatan' => 'Wakil Kepala Bidang Kesiswaan',
        ]);

        // 7. User & Entitas Kepala Sekolah
        $userKepsek = User::create([
            'name' => 'Dr. Hj. Indrawati, M.Pd.',
            'username' => 'kepsek',
            'email' => 'kepsek@smkn2guguak.sch.id',
            'password' => Hash::make('password'),
            'role' => 'kepala_sekolah',
            'status' => 'active',
        ]);

        Kepsek::create([
            'user_id' => $userKepsek->id,
            'username' => 'kepsek',
            'password' => Hash::make('password'),
            'nip' => '19681125 199403 2 001',
            'nama_lengkap' => 'Dr. Hj. Indrawati, M.Pd.',
            'email' => 'kepsek@smkn2guguak.sch.id',
            'no_hp' => '081198765432',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1968-11-25',
            'alamat' => 'Kec. Guguak, Kab. Lima Puluh Kota',
            'jabatan' => 'Kepala Sekolah',
        ]);

        // 8. Entitas Kelas
        $kelas1 = Kelas::create([
            'id_tahun_ajaran' => $taAktif->id_tahun_ajaran,
            'nama_kelas' => 'X PPLG 1',
            'tingkat_kelas' => 'X',
            'id_jurusan' => $jurPPLG->id_jurusan,
            'id_wali_kelas' => $wali1->id_wali_kelas,
        ]);

        $kelas2 = Kelas::create([
            'id_tahun_ajaran' => $taAktif->id_tahun_ajaran,
            'nama_kelas' => 'XI AKL 2',
            'tingkat_kelas' => 'XI',
            'id_jurusan' => $jurAKL->id_jurusan,
            'id_wali_kelas' => $wali2->id_wali_kelas,
        ]);

        // 9. User & Entitas Siswa
        $userSiswa1 = User::create([
            'name' => 'Andi Saputra',
            'username' => 'siswa1',
            'email' => 'andi@smkn2guguak.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'status' => 'active',
        ]);

        $siswa1 = Siswa::create([
            'user_id' => $userSiswa1->id,
            'username' => 'siswa1',
            'password' => Hash::make('password'),
            'nis' => '20261001',
            'nisn' => '0089123456',
            'nama_siswa' => 'Andi Saputra',
            'id_kelas' => $kelas1->id_kelas,
            'tahun_masuk' => '2026',
            'status_siswa' => 'aktif',
            'tempat_lahir' => 'Guguak',
            'tanggal_lahir' => '2008-05-12',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'alamat' => 'Jl. Tan Bawa No. 12, Guguak',
            'no_wa_siswa' => '081234567891',
            'nama_orang_tua_wali' => 'Bapak Hendra Saputra',
            'no_wa_orang_tua_wali' => '081298765431',
        ]);

        $userSiswa2 = User::create([
            'name' => 'Budi Santoso',
            'username' => 'siswa2',
            'email' => 'budi@smkn2guguak.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'status' => 'active',
        ]);

        $siswa2 = Siswa::create([
            'user_id' => $userSiswa2->id,
            'username' => 'siswa2',
            'password' => Hash::make('password'),
            'nis' => '20261002',
            'nisn' => '0089123457',
            'nama_siswa' => 'Budi Santoso',
            'id_kelas' => $kelas2->id_kelas,
            'tahun_masuk' => '2025',
            'status_siswa' => 'aktif',
            'tempat_lahir' => 'Payakumbuh',
            'tanggal_lahir' => '2007-09-20',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'alamat' => 'Jl. Sudirman No. 45, Payakumbuh',
            'no_wa_siswa' => '081234567892',
            'nama_orang_tua_wali' => 'Bapak Agus Santoso',
            'no_wa_orang_tua_wali' => '081298765432',
        ]);

        // 10. Entitas Jadwal Ketersediaan
        $slot1 = JadwalKetersediaan::create([
            'id_guru_bk' => $guruBk->id_guru_bk,
            'tanggal_tersedia' => Carbon::tomorrow()->toDateString(),
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '09:45:00',
            'status_slot' => 'terisi',
        ]);

        $slot2 = JadwalKetersediaan::create([
            'id_guru_bk' => $guruBk->id_guru_bk,
            'tanggal_tersedia' => Carbon::tomorrow()->toDateString(),
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '10:45:00',
            'status_slot' => 'tersedia',
        ]);

        $slot3 = JadwalKetersediaan::create([
            'id_guru_bk' => $guruBk->id_guru_bk,
            'tanggal_tersedia' => Carbon::tomorrow()->addDay()->toDateString(),
            'jam_mulai' => '08:30:00',
            'jam_selesai' => '09:15:00',
            'status_slot' => 'tersedia',
        ]);

        // 11. Entitas Pengajuan Konseling
        $pengajuan1 = PengajuanKonseling::create([
            'id_siswa' => $siswa1->id_siswa,
            'id_jadwal' => $slot1->id_jadwal,
            'jenis_konseling' => 'individu',
            'alasan_pengajuan' => 'Konsultasi minat bakat dan perancangan Karir setelah lulus SMK.',
            'sumber_pengajuan' => 'siswa',
            'status_pengajuan' => 'disetujui',
            'tanggal_pengajuan' => Carbon::now()->subHours(2),
            'catatan_validasi' => 'Disetujui. Silakan hadir tepat waktu di ruang BK.',
        ]);

        $pengajuan2 = PengajuanKonseling::create([
            'id_siswa' => $siswa2->id_siswa,
            'id_jadwal' => null,
            'jenis_konseling' => 'insidental',
            'alasan_pengajuan' => 'Penanganan keterlambatan berulang dan kedisiplinan belajar.',
            'alasan_rujukan' => 'Siswa sering terlambat masuk sekolah lebih dari 3 kali dalam seminggu.',
            'sumber_pengajuan' => 'wali_kelas',
            'id_wali_kelas' => $wali2->id_wali_kelas,
            'status_pengajuan' => 'disetujui',
            'tanggal_pengajuan' => Carbon::yesterday(),
            'catatan_validasi' => 'Rujukan diterima. Sesi konseling insidental segera dilaksanakan.',
        ]);

        // 12. Entitas Sesi Konseling
        $sesi1 = SesiKonseling::create([
            'id_pengajuan' => $pengajuan1->id_pengajuan,
            'status_sesi' => 'terjadwal',
            'tanggal_pelaksanaan' => Carbon::tomorrow()->toDateString(),
            'status_kehadiran' => 'menunggu',
            'hasil_konseling' => null,
            'rencana_tindak_lanjut' => null,
            'catatan_untuk_siswa' => 'Harap membawa portofolio minat bakat ke ruang BK.',
        ]);

        $sesi2 = SesiKonseling::create([
            'id_pengajuan' => $pengajuan2->id_pengajuan,
            'status_sesi' => 'selesai',
            'tanggal_pelaksanaan' => Carbon::yesterday()->toDateString(),
            'status_kehadiran' => 'hadir',
            'hasil_konseling' => 'Siswa mengakui kesulitan bangun pagi karena membantu usaha keluarga hingga larut malam. Siswa bersedia memperbaiki pola istirahat.',
            'rencana_tindak_lanjut' => 'Pemanggilan orang tua untuk mendiskusikan penyesuaian jam belajar dan istirahat di rumah.',
            'catatan_untuk_siswa' => 'Tetap semangat dan jaga komitmen untuk hadir tepat waktu setiap pagi.',
            'catatan_rahasia' => 'Perlu kerja sama dengan orang tua agar siswa tidak dibebani jam kerja malam berlebihan.',
        ]);

        // 13. Entitas Tindak Lanjut
        $tindakLanjut2 = TindakLanjut::create([
            'id_sesi' => $sesi2->id_sesi,
            'id_jadwal' => null,
            'jenis_aksi' => 'surat_ortu',
            'status_tindak_lanjut' => 'selesai',
            'catatan' => 'Menerbitkan surat panggilan orang tua untuk koordinasi pembinaan kedisiplinan.',
        ]);

        // 14. Entitas Surat Panggilan
        SuratPanggilan::create([
            'id_tindak_lanjut' => $tindakLanjut2->id_tindak_lanjut,
            'id_guru_bk' => $guruBk->id_guru_bk,
            'nomor_surat' => '421.5/BK-SMKN2/2026/08/001',
            'perihal' => 'Panggilan Orang Tua / Wali Siswa',
            'isi_surat' => 'Sehubungan dengan pembinaan kedisiplinan dan kehadiran ananda Budi Santoso di sekolah, kami mengharapkan kehadiran Bapak/Ibu Orang Tua/Wali di ruang Bimbingan Konseling.',
            'tanggal_terbit' => Carbon::today()->toDateString(),
            'tanggal_pertemuan' => Carbon::tomorrow()->addDays(2)->toDateString(),
            'waktu_pertemuan' => '09:00:00',
            'tempat' => 'Ruang Konseling SMKN 2 Guguak',
            'status_surat' => 'terbit',
            'status_kirim_wa' => 'terkirim',
        ]);

        // 15. Entitas Notifikasi & Log WA
        Notifikasi::create([
            'user_id' => $userSiswa1->id,
            'judul_notifikasi' => 'Pengajuan Konseling Disetujui',
            'jenis_notifikasi' => 'persetujuan',
            'id_pengajuan' => $pengajuan1->id_pengajuan,
            'id_jadwal' => $slot1->id_jadwal,
            'tipe_penerima' => 'siswa',
            'isi_pesan' => 'Pengajuan jadwal konseling Anda pada tanggal ' . Carbon::parse($slot1->tanggal_tersedia)->format('d-m-Y') . ' telah disetujui Guru BK.',
            'no_wa_tujuan' => $siswa1->no_wa_siswa,
            'status_kirim' => 'sent',
            'tanggal_kirim' => Carbon::now(),
            'is_read' => false,
        ]);

        WaLog::create([
            'penerima_tipe' => 'siswa',
            'penerima_nama' => $siswa1->nama_siswa,
            'no_wa' => $siswa1->no_wa_siswa,
            'jenis_notifikasi' => 'persetujuan',
            'isi_pesan' => 'Pengajuan Konseling Disetujui! Halo Andi Saputra, jadwal konseling Anda telah DISETUJUI oleh Guru BK.',
            'status' => 'sent',
            'gateway_response' => '{"status":true,"message":"Message sent successfully"}',
        ]);
    }
}
