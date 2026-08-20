<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';

    protected $fillable = [
        'user_id',
        'judul_notifikasi',
        'jenis_notifikasi',
        'id_pengajuan',
        'id_jadwal',
        'id_surat',
        'tipe_penerima',
        'isi_pesan',
        'no_wa_tujuan',
        'status_kirim',
        'tanggal_kirim',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanKonseling::class, 'id_pengajuan', 'id_pengajuan');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalKetersediaan::class, 'id_jadwal', 'id_jadwal');
    }

    public function surat()
    {
        return $this->belongsTo(SuratPanggilan::class, 'id_surat', 'id_surat');
    }
}
