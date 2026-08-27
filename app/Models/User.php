<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * FUNGSI FILE INI:
 * Merekam data akun autentikasi pengguna sistem (users) untuk 6 role hak akses.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Helper to check user role
    public function isRole(string $roleName): bool
    {
        return $this->role === $roleName;
    }

    // Relasi 1:1 ke entitas Admin
    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id');
    }

    // Relasi 1:1 ke entitas Guru BK
    public function guruBk()
    {
        return $this->hasOne(GuruBk::class, 'user_id');
    }

    // Relasi 1:1 ke entitas Wali Kelas
    public function waliKelas()
    {
        return $this->hasOne(WaliKelas::class, 'user_id');
    }

    // Relasi 1:1 ke entitas Siswa
    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'user_id');
    }

    // Relasi 1:1 ke entitas Wakasis
    public function wakasis()
    {
        return $this->hasOne(Wakasis::class, 'user_id');
    }

    // Relasi 1:1 ke entitas Kepsek
    public function kepsek()
    {
        return $this->hasOne(Kepsek::class, 'user_id');
    }

    // Relasi Notifikasi sistem
    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'user_id');
    }
}
