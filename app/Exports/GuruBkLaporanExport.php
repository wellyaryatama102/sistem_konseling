<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * FUNGSI FILE INI:
 * Merender rekapitulasi data laporan pelayanan Guru BK ke format file Excel (.xlsx).
 */
class GuruBkLaporanExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('excel.guru_rekap', $this->data);
    }

    public function title(): string
    {
        return 'Laporan Layanan BK SMKN 2 Guguak';
    }
}
