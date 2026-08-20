<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKonselingExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportExcelController extends Controller
{
    public function export(Request $request)
    {
        return Excel::download(new LaporanKonselingExport($request->kelas_id), 'Laporan_Rekap_Konseling_SMKN2.xlsx');
    }
}
