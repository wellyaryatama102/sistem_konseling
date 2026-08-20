<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKetersediaan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_ketersediaan';
    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'id_guru_bk',
        'tanggal_tersedia',
        'jam_mulai',
        'jam_selesai',
        'status_slot',
    ];

    // Relasi N:1 ke Guru BK
    public function guruBk()
    {
        return $this->belongsTo(GuruBk::class, 'id_guru_bk', 'id_guru_bk');
    }

    // Relasi 1:N ke Pengajuan Konseling
    public function pengajuanKonselings()
    {
        return $this->hasMany(PengajuanKonseling::class, 'id_jadwal', 'id_jadwal');
    }

    // Relasi 1:N ke Tindak Lanjut
    public function tindakLanjuts()
    {
        return $this->hasMany(TindakLanjut::class, 'id_jadwal', 'id_jadwal');
    }

    // Relasi 1:N ke Notifikasi
    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'id_jadwal', 'id_jadwal');
    }
}
