<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Jurusan;
use App\Models\WaliKelas;
use Illuminate\Http\Request;

/**
 * FUNGSI FILE INI:
 * Menangani kelola data kelas/rombel, penunjukan wali kelas, jurusan, dan tahun ajaran.
 */
class KelasController extends Controller
{
    /**
     * Menampilkan daftar kelas 
     */
    public function index(Request $request)
    {
        $query = Kelas::with(['waliKelas', 'jurusan', 'tahunAjaran', 'siswas']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_kelas', 'like', "%{$search}%")
                  ->orWhereHas('jurusan', function ($j) use ($search) {
                      $j->where('nama_jurusan', 'like', "%{$search}%");
                  });
        }

        if ($request->filled('tingkat')) {
            $query->where('tingkat_kelas', $request->tingkat);
        }

        $kelases = $query->orderBy('nama_kelas')->paginate(15)->withQueryString();
        $waliKelases = WaliKelas::orderBy('nama_lengkap')->get();
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();
        $tahunAjarans = TahunAjaran::orderBy('nama_tahun_ajaran', 'desc')->get();

        return view('admin.kelas.index', compact('kelases', 'waliKelases', 'jurusans', 'tahunAjarans'));
    }

    /**
     * Form tambah kelas 
     */
    public function create()
    {
        $waliKelases = WaliKelas::orderBy('nama_lengkap')->get();
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();
        $tahunAjarans = TahunAjaran::orderBy('nama_tahun_ajaran', 'desc')->get();

        return view('admin.kelas.create', compact('waliKelases', 'jurusans', 'tahunAjarans'));
    }

    /**
     * Menyimpan kelas baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:100',
            'tingkat_kelas' => 'required|string|max:10',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
            'id_wali_kelas' => 'nullable|exists:wali_kelas,id_wali_kelas',
        ]);

        Kelas::create($validated);

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas baru berhasil ditambahkan.');
    }

    /**
     * Form edit kelas 
     */
    public function edit(Kelas $kelas)
    {
        $waliKelases = WaliKelas::orderBy('nama_lengkap')->get();
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();
        $tahunAjarans = TahunAjaran::orderBy('nama_tahun_ajaran', 'desc')->get();

        return view('admin.kelas.edit', compact('kelas', 'waliKelases', 'jurusans', 'tahunAjarans'));
    }

    /**
     * Memperbarui data kelas
     */
    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:100',
            'tingkat_kelas' => 'required|string|max:10',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
            'id_wali_kelas' => 'nullable|exists:wali_kelas,id_wali_kelas',
        ]);

        $kelas->update($validated);

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Menghapus kelas
     */
    public function destroy(Kelas $kelas)
    {
        if ($kelas->siswas()->count() > 0) {
            return back()->with('warning', 'Kelas tidak dapat dihapus karena masih memiliki data siswa terhubung.');
        }

        $kelas->delete();

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil dihapus.');
    }
}
