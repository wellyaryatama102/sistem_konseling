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
use App\Exports\SiswaExport;
use App\Exports\SiswaTemplateExport;
use App\Imports\SiswaImport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

/**
 * FUNGSI FILE INI:
 * Menangani kelola data siswa (CRUD Siswa), penempatan kelas, kontak orang tua/wali, ekspor/impor Excel, dan input masal.
 */
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

    // Form tambah data siswa baru
    public function create()
    {
        $kelases = Kelas::orderBy('nama_kelas')->get();
        return view('admin.siswa.create', compact('kelases'));
    }

    // Menyimpan data siswa baru dan pembuatan akun penggunanya
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username|unique:siswa,username',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'nis' => 'nullable|string|max:50|unique:siswa,nis',
            'nisn' => 'nullable|string|max:50|unique:siswa,nisn',
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

        DB::transaction(function () use ($validated) {
            $email = $validated['email'] ?? ($validated['username'] . '@siswa.smkn2guguak.sch.id');
            
            // 1. Buat akun pengguna (User)
            $user = User::create([
                'name' => $validated['nama_siswa'],
                'username' => $validated['username'],
                'email' => $email,
                'password' => bcrypt($validated['password']),
                'role' => 'siswa',
                'status' => 'active',
            ]);

            // 2. Buat data pokok siswa
            Siswa::create([
                'user_id' => $user->id,
                'username' => $validated['username'],
                'password' => $user->password,
                'nama_siswa' => $validated['nama_siswa'],
                'nis' => $validated['nis'] ?? null,
                'nisn' => $validated['nisn'] ?? null,
                'id_kelas' => $validated['id_kelas'] ?? null,
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'agama' => $validated['agama'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'no_wa_siswa' => $validated['no_wa_siswa'] ?? null,
                'nama_orang_tua_wali' => $validated['nama_orang_tua_wali'] ?? null,
                'no_wa_orang_tua_wali' => $validated['no_wa_orang_tua_wali'] ?? null,
                'status_siswa' => $validated['status_siswa'],
            ]);
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa baru beserta akunnya berhasil ditambahkan.');
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

    // Unduh Template Excel Impor Siswa
    public function downloadTemplate()
    {
        return Excel::download(new SiswaTemplateExport, 'Template_Import_Siswa_Per_Kelas.xlsx');
    }

    // Form Unggah Import Excel Siswa
    public function importForm()
    {
        $kelases = Kelas::orderBy('nama_kelas')->get();
        return view('admin.siswa.import', compact('kelases'));
    }

    // Memproses File Import Excel Siswa
    public function importStore(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'file_excel' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'password_option' => 'required|in:nis,custom',
            'custom_password' => 'nullable|required_if:password_option,custom|string|min:6',
        ], [
            'id_kelas.required' => 'Pilih kelas tujuan terlebih dahulu.',
            'file_excel.required' => 'File Excel wajib diunggah.',
            'file_excel.mimes' => 'Format file harus berupa Excel (.xlsx, .xls) atau CSV (.csv).',
            'custom_password.required_if' => 'Password kustom wajib diisi jika Anda memilih opsi password kustom.',
        ]);

        $defaultPasswordSetting = $request->password_option === 'custom' ? $request->custom_password : 'nis';
        $import = new SiswaImport((int) $request->id_kelas, $defaultPasswordSetting);

        try {
            Excel::import($import, $request->file('file_excel'));
            $count = $import->getImportedCount();
            $errors = $import->getErrors();

            if ($count === 0 && !empty($errors)) {
                return redirect()->back()->withInput()->with('error', 'Gagal mengimpor data: ' . implode(' | ', $errors));
            }

            $message = "Berhasil membuat {$count} akun siswa beserta data pokoknya.";
            if (!empty($errors)) {
                $message .= " Namun beberapa baris dilewati: " . implode(' | ', $errors);
                return redirect()->route('admin.siswa.index')->with('warning', $message);
            }

            return redirect()->route('admin.siswa.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memproses file: ' . $e->getMessage());
        }
    }

    // Form Input Masal Siswa Per Kelas (Web Form)
    public function bulkForm()
    {
        $kelases = Kelas::orderBy('nama_kelas')->get();
        return view('admin.siswa.bulk', compact('kelases'));
    }

    // Memproses Simpan Masal dari Form Web
    public function bulkStore(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'siswas' => 'required|array|min:1',
            'siswas.*.nama_siswa' => 'required|string|max:255',
            'siswas.*.nis' => 'nullable|string|max:50',
            'siswas.*.nisn' => 'nullable|string|max:50',
            'siswas.*.jenis_kelamin' => 'nullable|in:L,P',
            'siswas.*.no_wa_siswa' => 'nullable|string|max:20',
            'siswas.*.nama_orang_tua_wali' => 'nullable|string|max:255',
            'siswas.*.no_wa_orang_tua_wali' => 'nullable|string|max:20',
        ], [
            'id_kelas.required' => 'Pilih kelas tujuan terlebih dahulu.',
            'siswas.required' => 'Minimal harus mengisikan 1 baris data siswa.',
            'siswas.*.nama_siswa.required' => 'Nama siswa di setiap baris yang diisi wajib ada.',
        ]);

        $createdCount = 0;
        $skipped = [];

        DB::transaction(function () use ($request, &$createdCount, &$skipped) {
            foreach ($request->siswas as $idx => $row) {
                $nama = trim($row['nama_siswa'] ?? '');
                $nis = trim($row['nis'] ?? '');
                $nisn = trim($row['nisn'] ?? '');
                $jk = trim($row['jenis_kelamin'] ?? '');
                $waSiswa = trim($row['no_wa_siswa'] ?? '');
                $ortu = trim($row['nama_orang_tua_wali'] ?? '');
                $waOrtu = trim($row['no_wa_orang_tua_wali'] ?? '');

                if (empty($nama)) continue;

                $username = !empty($nis) ? $nis : 'siswa_' . Str::slug($nama, '_') . '_' . rand(100, 999);
                
                // Cek jika username/nis sudah ada
                if (User::where('username', $username)->exists() || Siswa::where('username', $username)->exists()) {
                    $skipped[] = "Baris #" . ($idx + 1) . " ({$nama}): Username/NIS '{$username}' sudah terdaftar.";
                    continue;
                }

                $rawPassword = !empty($nis) ? $nis : '12345678';
                $email = $username . '@siswa.smkn2guguak.sch.id';

                $user = User::create([
                    'name' => $nama,
                    'username' => $username,
                    'email' => $email,
                    'password' => Hash::make($rawPassword),
                    'role' => 'siswa',
                    'status' => 'active',
                ]);

                Siswa::create([
                    'user_id' => $user->id,
                    'username' => $username,
                    'password' => $user->password,
                    'nama_siswa' => $nama,
                    'nis' => $nis ?: null,
                    'nisn' => $nisn ?: null,
                    'id_kelas' => $request->id_kelas,
                    'jenis_kelamin' => in_array($jk, ['L', 'P']) ? $jk : null,
                    'no_wa_siswa' => $waSiswa ?: null,
                    'nama_orang_tua_wali' => $ortu ?: null,
                    'no_wa_orang_tua_wali' => $waOrtu ?: null,
                    'status_siswa' => 'aktif',
                ]);

                $createdCount++;
            }
        });

        if ($createdCount === 0) {
            return redirect()->back()->withInput()->with('error', 'Tidak ada data siswa yang berhasil disimpan. ' . implode(' ', $skipped));
        }

        $msg = "Berhasil menambahkan {$createdCount} siswa beserta akun penggunanya ke kelas.";
        if (!empty($skipped)) {
            return redirect()->route('admin.siswa.index')->with('warning', $msg . " Catatan: " . implode(' ', $skipped));
        }

        return redirect()->route('admin.siswa.index')->with('success', $msg);
    }
}