<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * FUNGSI FILE INI:
 * Mengekspor daftar master data siswa beserta relasi kelas dan jurusan ke format file Excel (.xlsx).
 */
class SiswaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * Mengambil data siswa (beserta data kelas & jurusan agar lebih cepat)
     */
    public function collection()
    {
        // Menggunakan with() agar relasi kelas dan jurusan ikut dipanggil
        return Siswa::with('kelas.jurusan')->get();
    }

    /**
     * Memetakan kolom Excel
     */
    public function map($siswa): array
    {
        return [
            $siswa->nis ?? '-',
            $siswa->nisn ?? '-',
            $siswa->nama_siswa,
            $siswa->kelas->nama_kelas ?? 'Belum ditentukan',
            $siswa->kelas->jurusan->nama_jurusan ?? '-',
            $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : ($siswa->jenis_kelamin == 'P' ? 'Perempuan' : '-'),
            $siswa->no_wa_orang_tua_wali ?? '-',
            ucfirst($siswa->status_siswa), // Mengubah awalan jadi huruf besar
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

    /**
     * Opsional: Menebalkan huruf di baris Header (Baris 1)
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}