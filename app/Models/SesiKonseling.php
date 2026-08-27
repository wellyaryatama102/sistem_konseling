<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * FUNGSI FILE INI:
 * Merekam pelaksanaan bimbingan konseling, status kehadiran, hasil konseling, dan Catatan Rahasia Guru BK.
 */
class SesiKonseling extends Model
{
    use HasFactory;

    protected $table = 'sesi_konseling';
    protected $primaryKey = 'id_sesi';

    protected $fillable = [
        'id_pengajuan',
        'status_sesi',
        'tanggal_pelaksanaan',
        'status_kehadiran',
        'hasil_konseling',
        'rencana_tindak_lanjut',
        'catatan_untuk_siswa',
        'catatan_rahasia',
    ];

    // Relasi 1:1 ke Pengajuan Konseling
    public function pengajuan()
    {
        return $this->belongsTo(PengajuanKonseling::class, 'id_pengajuan', 'id_pengajuan');
    }

    // Relasi 1:N ke Tindak Lanjut
    public function tindakLanjuts()
    {
        return $this->hasMany(TindakLanjut::class, 'id_sesi', 'id_sesi');
    }
}
