<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use Illuminate\Http\Request;

/**
 * FUNGSI FILE INI:
 * Menangani kelola data master periode tahun ajaran akademik dan pengaktifan semester berjalan.
 */
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

        $statusAktif = $request->boolean('status_aktif', false);

        if ($statusAktif) {
            // Nonaktifkan tahun ajaran lainnya jika ini diset aktif
            TahunAjaran::query()->update(['status_aktif' => false]);
        }

        TahunAjaran::create([
            'nama_tahun_ajaran' => $validated['nama_tahun_ajaran'],
            'status_aktif' => $statusAktif,
        ]);

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', "Tahun ajaran {$validated['nama_tahun_ajaran']} berhasil ditambahkan.");
    }

    /**
     * Mengubah status aktif tahun ajaran
     */
    public function toggleStatus(TahunAjaran $tahunAjaran)
    {
        $newStatus = !$tahunAjaran->status_aktif;

        if ($newStatus) {
            // Nonaktifkan tahun ajaran lain agar hanya 1 yang aktif secara publik
            TahunAjaran::query()->where('id_tahun_ajaran', '!=', $tahunAjaran->id_tahun_ajaran)->update(['status_aktif' => false]);
        }

        $tahunAjaran->update(['status_aktif' => $newStatus]);

        $msg = $newStatus ? "Tahun ajaran {$tahunAjaran->nama_tahun_ajaran} berhasil diaktifkan." : "Tahun ajaran {$tahunAjaran->nama_tahun_ajaran} dinonaktifkan.";
        return back()->with('success', $msg);
    }

    /**
     * Menghapus tahun ajaran jika tidak digunakan
     */
    public function destroy(TahunAjaran $tahunAjaran)
    {
        if ($tahunAjaran->kelas()->count() > 0) {
            return back()->with('warning', "Tahun ajaran {$tahunAjaran->nama_tahun_ajaran} tidak dapat dihapus karena masih digunakan oleh data kelas terhubung.");
        }

        $nama = $tahunAjaran->nama_tahun_ajaran;
        $tahunAjaran->delete();

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', "Tahun ajaran {$nama} berhasil dihapus.");
    }
}

