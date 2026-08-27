<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * FUNGSI FILE INI:
 * Merekam data rombongan belajar / kelas siswa (nama kelas, tingkat kelas X/XI/XII).
 */
class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';

    protected $fillable = [
        'id_tahun_ajaran',
        'nama_kelas',
        'tingkat_kelas',
        'id_jurusan',
        'id_wali_kelas',
    ];

    // Relasi ke Tahun Ajaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    // Relasi ke Jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }

    // Relasi ke Wali Kelas
    public function waliKelas()
    {
        return $this->belongsTo(WaliKelas::class, 'id_wali_kelas', 'id_wali_kelas');
    }

    // Relasi 1:N ke Siswa
    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'id_kelas');
    }
}
