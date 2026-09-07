<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**FUNGSI FILE INI: file template Excel (.xlsx) untuk impor data siswa per kelas.
 */
class SiswaTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        return [
            [
                'Ahmad Fauzi',
                '21001',
                '0051234567',
                'L',
                '081234567890',
                'Budi Santoso',
                '081298765432',
                'ahmad_21001',
                '21001',
            ],
            [
                'Siti Aminah',
                '21002',
                '0057654321',
                'P',
                '081345678901',
                'Rahmat Hidayat',
                '081387654321',
                'siti_21002',
                '21002',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Siswa *',
            'NIS *',
            'NISN',
            'Jenis Kelamin (L/P)',
            'No. WA Siswa',
            'Nama Orang Tua / Wali',
            'No. WA Orang Tua / Wali',
            'Username (Opsional - Kosongkan jika samakan NIS)',
            'Password (Opsional - Kosongkan jika samakan NIS)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E3A8A']
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 15,
            'C' => 18,
            'D' => 20,
            'E' => 18,
            'F' => 25,
            'G' => 22,
            'H' => 35,
            'I' => 35,
        ];
    }
}
