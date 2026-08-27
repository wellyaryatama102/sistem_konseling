<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * FUNGSI FILE INI:
 * Merekam periode akademik tahun ajaran dan status aktifnya (aktif / nonaktif).
 */
class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';
    protected $primaryKey = 'id_tahun_ajaran';

    protected $fillable = [
        'nama_tahun_ajaran',
        'status_aktif',
    ];

    // Relasi 1:N ke Kelas
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }
}
