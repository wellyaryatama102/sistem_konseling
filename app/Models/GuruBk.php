<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * FUNGSI FILE INI:
 * Merekam data profil Guru Bimbingan Konseling (NIP, nama, kontak, foto profil) dan jadwal ketersediaannya.
 */
class GuruBk extends Model
{
    use HasFactory;

    protected $table = 'guru_bk';
    protected $primaryKey = 'id_guru_bk';

    protected $fillable = [
        'user_id',
        'username',
        'password',
        'nip',
        'nama_lengkap',
        'email',
        'no_hp',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'jabatan',
        'foto_profil',
        'tanda_tangan_digital',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi 1:N ke Jadwal Ketersediaan
    public function jadwalKetersediaans()
    {
        return $this->hasMany(JadwalKetersediaan::class, 'id_guru_bk', 'id_guru_bk');
    }

    // Relasi 1:N ke Surat Panggilan
    public function suratPanggilans()
    {
        return $this->hasMany(SuratPanggilan::class, 'id_guru_bk', 'id_guru_bk');
    }
}
