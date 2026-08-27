<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Admin;
use App\Models\GuruBk;
use App\Models\WaliKelas;
use App\Models\Wakasis;
use App\Models\Kepsek;
use App\Models\Kelas;
use App\Models\JadwalKetersediaan;
use App\Models\PengajuanKonseling;
use App\Models\SesiKonseling;
use App\Models\WaLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

/**
 * FUNGSI FILE INI:
 * Menangani kelola data akun pengguna (CRUD User) dari seluruh role serta statistik dashboard Admin.
 */
class UserController extends Controller
{
    //Dashboard Admin
    public function dashboard()
    {
        $tahunAjaranAktif = \App\Models\TahunAjaran::where('status_aktif', true)->first();

        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'inactive_users' => User::where('status', 'inactive')->count(),
            'total_siswa' => Siswa::where('status_siswa', 'aktif')->count(),
            'total_kelas' => Kelas::count(),
            'total_jurusan' => \App\Models\Jurusan::count(),
            'guru_bk' => GuruBk::count(),
            'wali_kelas' => WaliKelas::count(),
            'wakasis' => Wakasis::count(),
            'kepala_sekolah' => Kepsek::count(),
            'tahun_ajaran_aktif' => $tahunAjaranAktif ? $tahunAjaranAktif->nama_tahun_ajaran : '-',
        ];

        $recentUsers = User::orderBy('created_at', 'desc')->take(6)->get();
        $recentLogs = WaLog::orderBy('created_at', 'desc')->take(6)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentLogs'));
    }



    // Menampilkan daftar pengguna
    public function index(Request $request)
    {
        $query = User::query();

        // Pencarian berdasarkan nama pengguna
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Urutan hierarki role: Admin -> Kepala Sekolah -> Wakasis -> Guru BK -> Wali Kelas -> Siswa
        $roleOrderSql = "CASE role 
            WHEN 'admin' THEN 1 
            WHEN 'kepsek' THEN 2 
            WHEN 'kepala_sekolah' THEN 2 
            WHEN 'wakasis' THEN 3 
            WHEN 'guru_bk' THEN 4 
            WHEN 'wali_kelas' THEN 5 
            WHEN 'siswa' THEN 6 
            ELSE 7 
        END";

        $users = $query->orderByRaw($roleOrderSql)
            ->orderBy('name', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }
    
    // Form export pengguna
    public function export()
        {
            return Excel::download(new UsersExport, 'Data_Pengguna.xlsx');
        }
    //Form tambah pengguna
    public function create()
    {
        return view('admin.users.create');
    }

    // Menyimpan pengguna baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => 'required|string|min:6',
            'role' => ['required', Rule::in(['admin', 'guru_bk', 'wali_kelas', 'siswa', 'wakasis', 'kepala_sekolah'])],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        // Sinkronkan ke entitas
        $this->syncUserEntity($user);

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil didaftarkan.');
    }

    //Form edit pengguna
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Memperbarui data pengguna
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'guru_bk', 'wali_kelas', 'siswa', 'wakasis', 'kepala_sekolah'])],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        $user->save();
        $this->syncUserEntity($user);

        return redirect()->route('admin.users.index')->with('success', 'Data akun pengguna berhasil diperbarui.');
    }

    private function syncUserEntity(User $user)
    {
        if ($user->role === 'admin') {
            Admin::updateOrCreate(['user_id' => $user->id], [
                'username' => $user->username,
                'password' => $user->password,
                'nama_lengkap' => $user->name,
                'email' => $user->email,
            ]);
        } elseif ($user->role === 'guru_bk') {
            GuruBk::updateOrCreate(['user_id' => $user->id], [
                'username' => $user->username,
                'password' => $user->password,
                'nama_lengkap' => $user->name,
                'email' => $user->email,
            ]);
        } elseif ($user->role === 'wali_kelas') {
            WaliKelas::updateOrCreate(['user_id' => $user->id], [
                'username' => $user->username,
                'password' => $user->password,
                'nama_lengkap' => $user->name,
                'email' => $user->email,
            ]);
        } elseif ($user->role === 'wakasis') {
            Wakasis::updateOrCreate(['user_id' => $user->id], [
                'username' => $user->username,
                'password' => $user->password,
                'nama_lengkap' => $user->name,
                'email' => $user->email,
            ]);
        } elseif ($user->role === 'kepala_sekolah') {
            Kepsek::updateOrCreate(['user_id' => $user->id], [
                'username' => $user->username,
                'password' => $user->password,
                'nama_lengkap' => $user->name,
                'email' => $user->email,
            ]);
        } elseif ($user->role === 'siswa') {
            Siswa::updateOrCreate(['user_id' => $user->id], [
                'username' => $user->username,
                'password' => $user->password,
                'nama_siswa' => $user->name,
                'status_siswa' => 'aktif',
            ]);
        }
    }

    //Mengubah status pengguna
    public function toggleStatus(User $user)
    {
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        $message = $newStatus === 'active' ? 'Status akun berhasil diaktifkan.' : 'Status akun berhasil dinonaktifkan.';
        return back()->with('success', $message);
    }

    // Reset password pengguna
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'new_password' => ['required', 'string', 'min:6'],
        ]);

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return back()->with('success', 'Kata sandi pengguna berhasil direset.');
    }
}