<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaLog extends Model
{
    use HasFactory;

    protected $table = 'wa_logs';

    protected $fillable = [
        'penerima_tipe',
        'penerima_nama',
        'no_wa',
        'jenis_notifikasi',
        'isi_pesan',
        'status',
        'gateway_response',
        'error_message',
    ];
}
