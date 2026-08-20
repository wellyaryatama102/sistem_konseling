<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    /**
     * Menampilkan daftar & manajemen Tahun Ajaran
     */
    public function index()
    {
        $tahunAjarans = TahunAjaran::withCount('kelas')->orderBy('nama_tahun_ajaran', 'desc')->get();
        return view('admin.tahun_ajaran.index', compact('tahunAjarans'));
    }

    /**
     * Menambahkan tahun ajaran baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tahun_ajaran' => 'required|string|max:20|unique:tahun_ajaran,nama_tahun_ajaran',
        ]);

        TahunAjaran::create([
            'nama_tahun_ajaran' => $validated['nama_tahun_ajaran'],
            'status_aktif' => $request->boolean('status_aktif', true),
        ]);

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', "Tahun ajaran {$validated['nama_tahun_ajaran']} berhasil ditambahkan.");
    }
}
