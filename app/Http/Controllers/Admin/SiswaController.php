<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

// LIBRARY UNTUK EXCEL 
use App\Exports\SiswaExport;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    // Menampilkan daftar data siswa
    public function index(Request $request)
    {
        $query = Siswa::with(['user', 'kelas.jurusan']);

        // Filter pencarian (Nama, NIS, NISN)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nama_siswa', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan Kelas
        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        // Filter berdasarkan Status Siswa (aktif, lulus, pindah, do)
        if ($request->filled('status_siswa')) {
            $query->where('status_siswa', $request->status_siswa);
        }

        $siswas = $query->orderBy('nama_siswa')->paginate(15)->withQueryString();
        $kelases = Kelas::orderBy('nama_kelas')->get();

        return view('admin.siswa.index', compact('siswas', 'kelases'));
    }

    // Mengunduh data siswa ke Excel
    public function export()
    {
        return Excel::download(new SiswaExport, 'Data_Siswa.xlsx');
    }

    // Detail data dasar siswa
    public function show(Siswa $siswa)
    {
        $siswa->load(['user', 'kelas.jurusan', 'kelas.waliKelas']);
        return view('admin.siswa.show', compact('siswa'));
    }

    //Form edit data dasar siswa
    public function edit(Siswa $siswa)
    {
        $siswa->load(['user', 'kelas']);
        $kelases = Kelas::orderBy('nama_kelas')->get();
        return view('admin.siswa.edit', compact('siswa', 'kelases'));
    }

    // Update data dasar siswa
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'nis' => ['nullable', 'string', 'max:50', Rule::unique('siswa', 'nis')->ignore($siswa->id_siswa, 'id_siswa')],
            'nisn' => ['nullable', 'string', 'max:50', Rule::unique('siswa', 'nisn')->ignore($siswa->id_siswa, 'id_siswa')],
            'id_kelas' => 'nullable|exists:kelas,id_kelas',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'no_wa_siswa' => 'nullable|string|max:20',
            'nama_orang_tua_wali' => 'nullable|string|max:255',
            'no_wa_orang_tua_wali' => 'nullable|string|max:20',
            'status_siswa' => 'required|in:aktif,lulus,pindah,do',
        ]);

        // Update User name if linked
        if ($siswa->user) {
            $siswa->user->update([
                'name' => $validated['nama_siswa'],
            ]);
        }

        // Update Siswa details
        $siswa->update($validated);

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    // Menghapus data siswa dan akun pengguna yang terkait
    public function destroy(Siswa $siswa)
    {
        $nama = $siswa->nama_siswa;

        DB::transaction(function () use ($siswa) {
            // Hapus file foto jika ada
            if ($siswa->foto_siswa && Storage::disk('public')->exists($siswa->foto_siswa)) {
                Storage::disk('public')->delete($siswa->foto_siswa);
            }

            $user = $siswa->user;
            $siswa->delete();

            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.siswa.index')->with('success', "Data siswa {$nama} berhasil dihapus.");
    }
}