<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * FUNGSI FILE INI: Mengekspor daftar master data siswa ke format file Excel
 */
class SiswaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * Mengambil data siswa (beserta data kelas & jurusan)
     */
    public function collection()
    { // <-- KURUNG KURAWAL BUKA INI SEBELUMNYA HILANG (Penyebab Fatal Error)
        return Siswa::with('kelas.jurusan')->get();
    }

    public function map($siswa): array
    {
        return [
            $siswa->nis ?? '-',
            $siswa->nisn ?? '-',
            $siswa->nama_siswa,
            $siswa->kelas->nama_kelas ?? 'Belum ditentukan',
            // Diperbaiki dengan optional chaining (?->) agar tidak error jika kelas null
            $siswa->kelas?->jurusan?->nama_jurusan ?? '-', 
            $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : ($siswa->jenis_kelamin == 'P' ? 'Perempuan' : '-'),
            $siswa->no_wa_orang_tua_wali ?? '-',
            ucfirst($siswa->status_siswa ?? '-'), 
        ];
    }

    /**
     * Header baris pertama
     */
    public function headings(): array
    {
        return [
            'NIS',
            'NISN',
            'Nama Siswa',
            'Kelas',
            'Jurusan',
            'Jenis Kelamin',
            'No. WA Ortu',
            'Status'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}