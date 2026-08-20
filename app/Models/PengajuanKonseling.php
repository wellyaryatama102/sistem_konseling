<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanKonseling extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_konseling';
    protected $primaryKey = 'id_pengajuan';

    protected $fillable = [
        'id_siswa',
        'id_jadwal',
        'jenis_konseling',
        'alasan_pengajuan',
        'alasan_rujukan',
        'sumber_pengajuan',
        'id_wali_kelas',
        'status_pengajuan',
        'tanggal_pengajuan',
        'tanggal_pembatalan',
        'catatan_validasi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'datetime',
            'tanggal_pembatalan' => 'datetime',
        ];
    }

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    // Relasi ke Jadwal Ketersediaan
    public function jadwal()
    {
        return $this->belongsTo(JadwalKetersediaan::class, 'id_jadwal', 'id_jadwal');
    }

    // Relasi ke Wali Kelas (jika sumber rujukan wali kelas)
    public function waliKelas()
    {
        return $this->belongsTo(WaliKelas::class, 'id_wali_kelas', 'id_wali_kelas');
    }

    // Relasi 1:1 ke Sesi Konseling
    public function sesiKonseling()
    {
        return $this->hasOne(SesiKonseling::class, 'id_pengajuan', 'id_pengajuan');
    }

    // Relasi 1:N ke Notifikasi
    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'id_pengajuan', 'id_pengajuan');
    }
}
