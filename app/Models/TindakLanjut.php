<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakLanjut extends Model
{
    use HasFactory;

    protected $table = 'tindak_lanjut';
    protected $primaryKey = 'id_tindak_lanjut';

    protected $fillable = [
        'id_sesi',
        'id_jadwal',
        'jenis_aksi',
        'status_tindak_lanjut',
        'catatan',
    ];

    // Relasi ke Sesi Konseling
    public function sesiKonseling()
    {
        return $this->belongsTo(SesiKonseling::class, 'id_sesi', 'id_sesi');
    }

    public function sesi()
    {
        return $this->sesiKonseling();
    }

    // Relasi ke Jadwal Ketersediaan untuk sesi lanjutan
    public function jadwal()
    {
        return $this->belongsTo(JadwalKetersediaan::class, 'id_jadwal', 'id_jadwal');
    }

    // Relasi 1:N ke Surat Panggilan
    public function suratPanggilans()
    {
        return $this->hasMany(SuratPanggilan::class, 'id_tindak_lanjut', 'id_tindak_lanjut');
    }

    public function suratPanggilan()
    {
        return $this->hasOne(SuratPanggilan::class, 'id_tindak_lanjut', 'id_tindak_lanjut');
    }
}
