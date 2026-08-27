<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * FUNGSI FILE INI:
 * Merender rekapitulasi data laporan kesiswaan Wakasis ke format file Excel (.xlsx).
 */
class WakasisLaporanExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('excel.wakasis_rekap', $this->data);
    }

    public function title(): string
    {
        return 'Laporan Kesiswaan SMKN 2 Guguak';
    }
}
