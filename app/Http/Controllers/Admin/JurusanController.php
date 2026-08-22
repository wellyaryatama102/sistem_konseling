<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JurusanController extends Controller
{
    /**
     * Menampilkan daftar data jurusan master
     */
    public function index(Request $request)
    {
        $query = Jurusan::withCount('kelas');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_jurusan', 'like', "%{$search}%");
        }

        $jurusans = $query->orderBy('nama_jurusan')->paginate(15)->withQueryString();

        return view('admin.jurusan.index', compact('jurusans'));
    }

    /**
     * Menyimpan data jurusan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jurusan' => 'required|string|max:100|unique:jurusan,nama_jurusan',
        ]);

        Jurusan::create($validated);

        return redirect()->route('admin.jurusan.index')
            ->with('success', "Data jurusan '{$validated['nama_jurusan']}' berhasil ditambahkan.");
    }

    /**
     * Form edit data jurusan
     */
    public function edit(Jurusan $jurusan)
    {
        return view('admin.jurusan.edit', compact('jurusan'));
    }

    /**
     * Memperbarui data jurusan
     */
    public function update(Request $request, Jurusan $jurusan)
    {
        $validated = $request->validate([
            'nama_jurusan' => [
                'required',
                'string',
                'max:100',
                Rule::unique('jurusan', 'nama_jurusan')->ignore($jurusan->id_jurusan, 'id_jurusan')
            ],
        ]);

        $jurusan->update($validated);

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Data jurusan berhasil diperbarui.');
    }

    /**
     * Menghapus data jurusan
     */
    public function destroy(Jurusan $jurusan)
    {
        if ($jurusan->kelas()->count() > 0) {
            return back()->with('warning', "Jurusan '{$jurusan->nama_jurusan}' tidak dapat dihapus karena masih digunakan oleh data kelas terhubung.");
        }

        $nama = $jurusan->nama_jurusan;
        $jurusan->delete();

        return redirect()->route('admin.jurusan.index')
            ->with('success', "Data jurusan '{$nama}' berhasil dihapus.");
    }
}
