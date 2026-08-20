<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';

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
        'pendidikan_terakhir',
        'foto_profil',
    ];

    // Relasi ke User akun
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
