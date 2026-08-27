<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * FUNGSI FILE INI:
 * Merekam data profil Kepala Sekolah untuk pengesahan & pengunduhan laporan eksekutif.
 */
class Kepsek extends Model
{
    use HasFactory;

    protected $table = 'kepsek';
    protected $primaryKey = 'id_kepsek';

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
}
