<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * FUNGSI FILE INI:
 * Merekam biodata Wali Kelas (NIP/NUPTK, nama, kontak) dan kelas binaan yang diampunya.
 */
class WaliKelas extends Model
{
    use HasFactory;

    protected $table = 'wali_kelas';
    protected $primaryKey = 'id_wali_kelas';

    protected $fillable = [
        'user_id',
        'username',
        'password',
        'nip_nuptk',
        'nama_lengkap',
        'email',
        'no_hp',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'jabatan',
        'foto_profil',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi 1:1 ke Kelas yang diampu
    public function kelas()
    {
        return $this->hasOne(Kelas::class, 'id_wali_kelas', 'id_wali_kelas');
    }

    // Relasi rujukan konseling yang diajukan oleh wali kelas
    public function rujukanKonselings()
    {
        return $this->hasMany(PengajuanKonseling::class, 'id_wali_kelas', 'id_wali_kelas')
            ->where('sumber_pengajuan', 'wali_kelas');
    }
}
