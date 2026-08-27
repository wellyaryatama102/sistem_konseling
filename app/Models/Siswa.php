<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * FUNGSI FILE INI:
 * Merekam biodata siswa, NIS/NISN, nomor WhatsApp siswa & orang tua/wali, serta relasi kelas.
 */
class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'user_id',
        'username',
        'password',
        'nis',
        'nisn',
        'nama_siswa',
        'id_kelas',
        'tahun_masuk',
        'status_siswa',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'foto_siswa',
        'no_wa_siswa',
        'nama_orang_tua_wali',
        'no_wa_orang_tua_wali',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    // Relasi 1:N ke Pengajuan Konseling
    public function pengajuanKonselings()
    {
        return $this->hasMany(PengajuanKonseling::class, 'id_siswa', 'id_siswa');
    }
}
