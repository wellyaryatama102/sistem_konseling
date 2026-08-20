<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations according to the 15 Entities of ERD SIKS SMKN 2 Guguak.
     */
    public function up(): void
    {
        // Users table 
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('id');
            $table->enum('role', ['admin', 'guru_bk', 'wali_kelas', 'siswa', 'wakasis', 'kepala_sekolah'])->default('siswa')->after('email');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('role');
        });

        // 1. ENTITAS: tahun_ajaran (PK: id_tahun_ajaran)
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id('id_tahun_ajaran');
            $table->string('nama_tahun_ajaran'); // e.g. 2026/2027
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        // 2. ENTITAS: jurusan (PK: id_jurusan)
        Schema::create('jurusan', function (Blueprint $table) {
            $table->id('id_jurusan');
            $table->string('nama_jurusan'); // e.g. PPLG, AKL, TJKT
            $table->timestamps();
        });

        // 3. ENTITAS: admin (PK: id_admin)
        Schema::create('admin', function (Blueprint $table) {
            $table->id('id_admin');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('nip')->nullable();
            $table->string('nama_lengkap');
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('foto_profil')->nullable();
            $table->timestamps();
        });

        // 4. ENTITAS: wakasis (PK: id_wakasis)
        Schema::create('wakasis', function (Blueprint $table) {
            $table->id('id_wakasis');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('nip')->nullable();
            $table->string('nama_lengkap');
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('jabatan')->default('Wakil Kepala Bidang Kesiswaan');
            $table->string('foto_profil')->nullable();
            $table->timestamps();
        });

        // 5. ENTITAS: wali_kelas (PK: id_wali_kelas)
        Schema::create('wali_kelas', function (Blueprint $table) {
            $table->id('id_wali_kelas');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('nip_nuptk')->nullable();
            $table->string('nama_lengkap');
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('jabatan')->default('Guru & Wali Kelas');
            $table->string('foto_profil')->nullable();
            $table->timestamps();
        });

        // 6. ENTITAS: kelas (PK: id_kelas, FK: id_tahun_ajaran, id_jurusan, id_wali_kelas)
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->foreignId('id_tahun_ajaran')->nullable()->constrained('tahun_ajaran', 'id_tahun_ajaran')->onDelete('set null');
            $table->string('nama_kelas'); // e.g. X PPLG 1
            $table->string('tingkat_kelas'); // X, XI, XII
            $table->foreignId('id_jurusan')->nullable()->constrained('jurusan', 'id_jurusan')->onDelete('set null');
            $table->foreignId('id_wali_kelas')->nullable()->constrained('wali_kelas', 'id_wali_kelas')->onDelete('set null');
            $table->timestamps();
        });

        // 7. ENTITAS: siswa (PK: id_siswa, FK: id_kelas)
        Schema::create('siswa', function (Blueprint $table) {
            $table->id('id_siswa');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('username')->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('nis')->nullable()->unique();
            $table->string('nisn')->nullable()->unique();
            $table->string('nama_siswa');
            $table->foreignId('id_kelas')->nullable()->constrained('kelas', 'id_kelas')->onDelete('set null');
            $table->string('tahun_masuk')->nullable();
            $table->enum('status_siswa', ['aktif', 'lulus', 'pindah', 'do'])->default('aktif');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('agama')->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto_siswa')->nullable();
            $table->string('no_wa_siswa')->nullable();
            $table->string('nama_orang_tua_wali')->nullable();
            $table->string('no_wa_orang_tua_wali')->nullable();
            $table->timestamps();
        });

        // 8. ENTITAS: guru_bk (PK: id_guru_bk)
        Schema::create('guru_bk', function (Blueprint $table) {
            $table->id('id_guru_bk');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('nip')->nullable();
            $table->string('nama_lengkap');
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('jabatan')->default('Guru Bimbingan dan Konseling');
            $table->string('foto_profil')->nullable();
            $table->string('tanda_tangan_digital')->nullable();
            $table->timestamps();
        });

        // 9. ENTITAS: kepsek (PK: id_kepsek)
        Schema::create('kepsek', function (Blueprint $table) {
            $table->id('id_kepsek');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('nip')->nullable();
            $table->string('nama_lengkap');
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('jabatan')->default('Kepala Sekolah');
            $table->string('foto_profil')->nullable();
            $table->string('tanda_tangan_digital')->nullable();
            $table->timestamps();
        });

        // 10. ENTITAS: jadwal_ketersediaan (PK: id_jadwal, FK: id_guru_bk)
        Schema::create('jadwal_ketersediaan', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->foreignId('id_guru_bk')->constrained('guru_bk', 'id_guru_bk')->onDelete('cascade');
            $table->date('tanggal_tersedia');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('status_slot', ['tersedia', 'terisi', 'selesai', 'dibatalkan'])->default('tersedia');
            $table->timestamps();
        });

        // 11. ENTITAS: pengajuan_konseling (PK: id_pengajuan, FK: id_siswa, id_jadwal, id_wali_kelas)
        Schema::create('pengajuan_konseling', function (Blueprint $table) {
            $table->id('id_pengajuan');
            $table->foreignId('id_siswa')->constrained('siswa', 'id_siswa')->onDelete('cascade');
            $table->foreignId('id_jadwal')->nullable()->constrained('jadwal_ketersediaan', 'id_jadwal')->onDelete('set null');
            $table->enum('jenis_konseling', ['individu', 'kelompok', 'insidental'])->default('individu');
            $table->text('alasan_pengajuan');
            $table->text('alasan_rujukan')->nullable();
            $table->enum('sumber_pengajuan', ['siswa', 'wali_kelas', 'guru_bk'])->default('siswa');
            $table->foreignId('id_wali_kelas')->nullable()->constrained('wali_kelas', 'id_wali_kelas')->onDelete('set null');
            $table->enum('status_pengajuan', ['menunggu_validasi', 'disetujui', 'ditolak', 'dibatalkan'])->default('menunggu_validasi');
            $table->dateTime('tanggal_pengajuan')->useCurrent();
            $table->dateTime('tanggal_pembatalan')->nullable();
            $table->text('catatan_validasi')->nullable();
            $table->timestamps();
        });

        // 12. ENTITAS: sesi_konseling (PK: id_sesi, FK: id_pengajuan)
        Schema::create('sesi_konseling', function (Blueprint $table) {
            $table->id('id_sesi');
            $table->foreignId('id_pengajuan')->constrained('pengajuan_konseling', 'id_pengajuan')->onDelete('cascade');
            $table->enum('status_sesi', ['terjadwal', 'berlangsung', 'selesai', 'dibatalkan'])->default('terjadwal');
            $table->date('tanggal_pelaksanaan');
            $table->enum('status_kehadiran', ['menunggu', 'hadir', 'tidak_hadir'])->default('menunggu');
            $table->text('hasil_konseling')->nullable();
            $table->text('rencana_tindak_lanjut')->nullable();
            $table->text('catatan_untuk_siswa')->nullable(); // Catatan arahan yang dapat dibaca siswa
            $table->text('catatan_rahasia')->nullable(); // Catatan rahasia khusus Guru BK
            $table->timestamps();
        });

        // 13. ENTITAS: tindak_lanjut (PK: id_tindak_lanjut, FK: id_sesi, id_jadwal)
        Schema::create('tindak_lanjut', function (Blueprint $table) {
            $table->id('id_tindak_lanjut');
            $table->foreignId('id_sesi')->constrained('sesi_konseling', 'id_sesi')->onDelete('cascade');
            $table->foreignId('id_jadwal')->nullable()->constrained('jadwal_ketersediaan', 'id_jadwal')->onDelete('set null');
            $table->enum('jenis_aksi', ['selesai', 'sesi_lanjutan', 'surat_ortu'])->default('selesai');
            $table->enum('status_tindak_lanjut', ['belum_ditindaklanjuti', 'terjadwal', 'selesai'])->default('belum_ditindaklanjuti');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 14. ENTITAS: surat_panggilan (PK: id_surat, FK: id_tindak_lanjut, id_guru_bk)
        Schema::create('surat_panggilan', function (Blueprint $table) {
            $table->id('id_surat');
            $table->foreignId('id_tindak_lanjut')->constrained('tindak_lanjut', 'id_tindak_lanjut')->onDelete('cascade');
            $table->foreignId('id_guru_bk')->constrained('guru_bk', 'id_guru_bk')->onDelete('cascade');
            $table->string('nomor_surat')->unique();
            $table->string('perihal')->default('Panggilan Orang Tua / Wali Siswa');
            $table->text('isi_surat');
            $table->date('tanggal_terbit');
            $table->date('tanggal_pertemuan');
            $table->time('waktu_pertemuan');
            $table->string('tempat')->default('Ruang Konseling SMKN 2 Guguak');
            $table->enum('status_surat', ['draft', 'terbit', 'selesai'])->default('terbit');
            $table->enum('status_kirim_wa', ['pending', 'terkirim', 'gagal'])->default('pending');
            $table->string('file_path')->nullable();
            $table->timestamps();
        });

        // 15. ENTITAS: notifikasi (PK: id_notifikasi, FK: id_pengajuan, id_jadwal, id_surat)
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('judul_notifikasi');
            $table->string('jenis_notifikasi'); // pengajuan_baru, persetujuan, penolakan, pembatalan_jadwal, pengingat_jadwal, surat_panggilan, rujukan_baru
            $table->foreignId('id_pengajuan')->nullable()->constrained('pengajuan_konseling', 'id_pengajuan')->onDelete('cascade');
            $table->foreignId('id_jadwal')->nullable()->constrained('jadwal_ketersediaan', 'id_jadwal')->onDelete('set null');
            $table->foreignId('id_surat')->nullable()->constrained('surat_panggilan', 'id_surat')->onDelete('cascade');
            $table->string('tipe_penerima'); // guru_bk, siswa, wali_kelas, orang_tua
            $table->text('isi_pesan');
            $table->string('no_wa_tujuan')->nullable();
            $table->enum('status_kirim', ['pending', 'sent', 'failed'])->default('pending');
            $table->dateTime('tanggal_kirim')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // Supporting Table: wa_logs (Audit log detail for WhatsApp gateway)
        Schema::create('wa_logs', function (Blueprint $table) {
            $table->id();
            $table->string('penerima_tipe');
            $table->string('penerima_nama');
            $table->string('no_wa');
            $table->string('jenis_notifikasi');
            $table->text('isi_pesan');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->text('gateway_response')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wa_logs');
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('surat_panggilan');
        Schema::dropIfExists('tindak_lanjut');
        Schema::dropIfExists('sesi_konseling');
        Schema::dropIfExists('pengajuan_konseling');
        Schema::dropIfExists('jadwal_ketersediaan');
        Schema::dropIfExists('kepsek');
        Schema::dropIfExists('guru_bk');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('wali_kelas');
        Schema::dropIfExists('wakasis');
        Schema::dropIfExists('admin');
        Schema::dropIfExists('jurusan');
        Schema::dropIfExists('tahun_ajaran');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role', 'status']);
        });
    }
};
