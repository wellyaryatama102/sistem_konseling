<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * FUNGSI FILE INI:
 * Mengekspor daftar akun pengguna sistem ke format file Excel (.xlsx).
 */
class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Mengambil data dari database
    */
    public function collection()
    {
        // Mengambil semua user (bisa disesuaikan jika ingin di-filter)
        return User::all();
    }

    /**
    * Memetakan data (memilih kolom mana saja yang akan dimasukkan ke Excel)
    */
    public function map($user): array
    {
        return [
            $user->name,
            $user->username,
            $user->email,
            str_replace('_', ' ', strtoupper($user->role)), // Merapikan teks role
            $user->status === 'active' ? 'Aktif' : 'Nonaktif', // Merapikan teks status
        ];
    }

    /**
    * Membuat Judul/Header pada baris paling atas di Excel
    */
    public function headings(): array
    {
        return [
            'Nama Pengguna',
            'Username',
            'Email',
            'Role',
            'Status'
        ];
    }
}