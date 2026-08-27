<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * FUNGSI FILE INI:
 * Merender rekapitulasi data laporan Admin ke format file Excel (.xlsx).
 */
class AdminLaporanExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('excel.admin_rekap', $this->data);
    }

    public function title(): string
    {
        return 'Laporan Admin SMKN 2 Guguak';
    }
}
