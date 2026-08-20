<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPanggilan extends Model
{
    use HasFactory;

    protected $table = 'surat_panggilan';
    protected $primaryKey = 'id_surat';

    protected $fillable = [
        'id_tindak_lanjut',
        'id_guru_bk',
        'nomor_surat',
        'perihal',
        'isi_surat',
        'tanggal_terbit',
        'tanggal_pertemuan',
        'waktu_pertemuan',
        'tempat',
        'status_surat',
        'status_kirim_wa',
        'file_path',
    ];

    // Relasi ke Tindak Lanjut
    public function tindakLanjut()
    {
        return $this->belongsTo(TindakLanjut::class, 'id_tindak_lanjut', 'id_tindak_lanjut');
    }

    // Relasi ke Guru BK
    public function guruBk()
    {
        return $this->belongsTo(GuruBk::class, 'id_guru_bk', 'id_guru_bk');
    }

    // Relasi 1:N ke Notifikasi
    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'id_surat', 'id_surat');
    }
}
