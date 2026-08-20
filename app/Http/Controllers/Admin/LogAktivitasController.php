<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WaLog;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    /**
     * Menampilkan Log Aktivitas & Dispatch Log WhatsApp System
     */
    public function index(Request $request)
    {
        $query = WaLog::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('penerima_nama', 'like', "%{$search}%")
                  ->orWhere('no_wa', 'like', "%{$search}%")
                  ->orWhere('jenis_notifikasi', 'like', "%{$search}%")
                  ->orWhere('isi_pesan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('penerima_tipe')) {
            $query->where('penerima_tipe', $request->penerima_tipe);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.log_aktivitas.index', compact('logs'));
    }
}
