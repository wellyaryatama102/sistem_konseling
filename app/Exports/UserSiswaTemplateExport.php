<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Menghasilkan file template Excel (.xlsx) untuk akun pengguna siswa.
 */
class UserSiswaTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        return [
            [
                'Welly Aryatama',
                'wellya',
                'wellya@siswa.smkn2guguak.sch.id',
                'wellya',
                'X PPLG 1',
            ],
            [
                'Ahmad Fauzi',
                'ahmadf',
                'ahmadf@siswa.smkn2guguak.sch.id',
                'ahmadf',
                'X PPLG 1',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap Siswa *',
            'Username / NIS *',
            'Email (Opsional - Kosongkan jika otomatis)',
            'Password (Opsional - Kosongkan jika samakan Username/NIS)',
            'Nama Kelas (Opsional - Contoh: X PPLG 1)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1B4D3E']
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 20,
            'C' => 38,
            'D' => 30,
            'E' => 20,
        ];
    }
}
