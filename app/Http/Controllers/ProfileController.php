<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Kelas;
use App\Models\Admin;
use App\Models\GuruBk;
use App\Models\WaliKelas;
use App\Models\Wakasis;
use App\Models\Kepsek;
use App\Models\Siswa;

class ProfileController extends Controller
{
    /**
     * Display the profile edit page.
     */
    public function edit()
    {
        $user = auth()->user();
        $role = $user->role;
        $profile = null;
        $kelases = [];
        $kelas = null;

        if ($role === 'admin') {
            $profile = Admin::firstOrCreate(
                ['user_id' => $user->id],
                ['username' => $user->username, 'password' => $user->password, 'nama_lengkap' => $user->name, 'email' => $user->email]
            );
        } elseif ($role === 'guru_bk') {
            $profile = GuruBk::firstOrCreate(
                ['user_id' => $user->id],
                ['username' => $user->username, 'password' => $user->password, 'nama_lengkap' => $user->name, 'email' => $user->email]
            );
        } elseif ($role === 'wali_kelas') {
            $profile = WaliKelas::firstOrCreate(
                ['user_id' => $user->id],
                ['username' => $user->username, 'password' => $user->password, 'nama_lengkap' => $user->name, 'email' => $user->email]
            );
            $kelas = Kelas::where('id_wali_kelas', $profile->id_wali_kelas)->first();
        } elseif ($role === 'wakasis') {
            $profile = Wakasis::firstOrCreate(
                ['user_id' => $user->id],
                ['username' => $user->username, 'password' => $user->password, 'nama_lengkap' => $user->name, 'email' => $user->email]
            );
        } elseif ($role === 'kepala_sekolah') {
            $profile = Kepsek::firstOrCreate(
                ['user_id' => $user->id],
                ['username' => $user->username, 'password' => $user->password, 'nama_lengkap' => $user->name, 'email' => $user->email]
            );
        } elseif ($role === 'siswa') {
            $profile = Siswa::firstOrCreate(
                ['user_id' => $user->id],
                ['username' => $user->username, 'password' => $user->password, 'nama_siswa' => $user->name, 'status_siswa' => 'aktif']
            );
            $kelases = Kelas::all();
        }

        return view('profile.edit', compact('user', 'profile', 'kelases', 'kelas'));
    }

    /**
     * Update the user profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $role = $user->role;

        // Base user validation
        $userRules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ];

        // Role-specific profile validation
        $profileRules = [];

        if ($role === 'admin') {
            $profileRules = [
                'nip' => ['nullable', 'string', 'max:50'],
                'no_hp' => ['nullable', 'string', 'max:20'],
                'alamat' => ['nullable', 'string'],
                'foto_profil' => ['nullable', 'image', 'max:2048'],
            ];
        } elseif (in_array($role, ['guru_bk', 'wali_kelas', 'wakasis', 'kepala_sekolah'])) {
            $profileRules = [
                'nip' => ['nullable', 'string', 'max:50'],
                'jenis_kelamin' => ['required', 'in:L,P'],
                'tempat_lahir' => ['required', 'string', 'max:100'],
                'tanggal_lahir' => ['required', 'date'],
                'no_hp' => ['required', 'string', 'max:20'],
                'alamat' => ['required', 'string'],
                'foto_profil' => ['nullable', 'image', 'max:2048'],
            ];
        } elseif ($role === 'siswa') {
            $profileRules = [
                'nis' => ['required', 'string', 'max:50', Rule::unique('siswa', 'nis')->ignore($user->siswa->id_siswa ?? null, 'id_siswa')],
                'nisn' => ['nullable', 'string', 'max:50', Rule::unique('siswa', 'nisn')->ignore($user->siswa->id_siswa ?? null, 'id_siswa')],
                'jenis_kelamin' => ['required', 'in:L,P'],
                'tempat_lahir' => ['nullable', 'string', 'max:100'],
                'tanggal_lahir' => ['nullable', 'date'],
                'alamat' => ['nullable', 'string'],
                'no_wa_siswa' => ['nullable', 'string', 'max:20'],
                'id_kelas' => ['nullable', 'exists:kelas,id_kelas'],
                'nama_orang_tua_wali' => ['nullable', 'string', 'max:255'],
                'no_wa_orang_tua_wali' => ['required', 'string', 'max:20'],
                'foto_siswa' => ['nullable', 'image', 'max:2048'],
            ];
        }

        $validated = $request->validate(array_merge($userRules, $profileRules));

        // Save User model
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->save();

        // Update ERD specific model
        if ($role === 'admin') {
            $admin = $user->admin ?? new Admin(['user_id' => $user->id]);
            $admin->username = $user->username;
            $admin->nama_lengkap = $user->name;
            $admin->email = $user->email;
            $admin->nip = $validated['nip'] ?? null;
            $admin->no_hp = $validated['no_hp'] ?? null;
            $admin->alamat = $validated['alamat'] ?? null;
            if ($request->hasFile('foto_profil')) {
                $admin->foto_profil = $request->file('foto_profil')->store('profiles', 'public');
            }
            $admin->save();
        } elseif ($role === 'guru_bk') {
            $guru = $user->guruBk ?? new GuruBk(['user_id' => $user->id]);
            $guru->username = $user->username;
            $guru->nama_lengkap = $user->name;
            $guru->email = $user->email;
            $guru->nip = $validated['nip'] ?? null;
            $guru->jenis_kelamin = $validated['jenis_kelamin'];
            $guru->tempat_lahir = $validated['tempat_lahir'];
            $guru->tanggal_lahir = $validated['tanggal_lahir'];
            $guru->no_hp = $validated['no_hp'];
            $guru->alamat = $validated['alamat'];
            if ($request->hasFile('foto_profil')) {
                $guru->foto_profil = $request->file('foto_profil')->store('profiles', 'public');
            }
            $guru->save();
        } elseif ($role === 'wali_kelas') {
            $wali = $user->waliKelas ?? new WaliKelas(['user_id' => $user->id]);
            $wali->username = $user->username;
            $wali->nama_lengkap = $user->name;
            $wali->email = $user->email;
            $wali->nip_nuptk = $validated['nip'] ?? null;
            $wali->jenis_kelamin = $validated['jenis_kelamin'];
            $wali->tempat_lahir = $validated['tempat_lahir'];
            $wali->tanggal_lahir = $validated['tanggal_lahir'];
            $wali->no_hp = $validated['no_hp'];
            $wali->alamat = $validated['alamat'];
            if ($request->hasFile('foto_profil')) {
                $wali->foto_profil = $request->file('foto_profil')->store('profiles', 'public');
            }
            $wali->save();
        } elseif ($role === 'wakasis') {
            $wakasis = $user->wakasis ?? new Wakasis(['user_id' => $user->id]);
            $wakasis->username = $user->username;
            $wakasis->nama_lengkap = $user->name;
            $wakasis->email = $user->email;
            $wakasis->nip = $validated['nip'] ?? null;
            $wakasis->jenis_kelamin = $validated['jenis_kelamin'];
            $wakasis->tempat_lahir = $validated['tempat_lahir'];
            $wakasis->tanggal_lahir = $validated['tanggal_lahir'];
            $wakasis->no_hp = $validated['no_hp'];
            $wakasis->alamat = $validated['alamat'];
            if ($request->hasFile('foto_profil')) {
                $wakasis->foto_profil = $request->file('foto_profil')->store('profiles', 'public');
            }
            $wakasis->save();
        } elseif ($role === 'kepala_sekolah') {
            $kepsek = $user->kepsek ?? new Kepsek(['user_id' => $user->id]);
            $kepsek->username = $user->username;
            $kepsek->nama_lengkap = $user->name;
            $kepsek->email = $user->email;
            $kepsek->nip = $validated['nip'] ?? null;
            $kepsek->jenis_kelamin = $validated['jenis_kelamin'];
            $kepsek->tempat_lahir = $validated['tempat_lahir'];
            $kepsek->tanggal_lahir = $validated['tanggal_lahir'];
            $kepsek->no_hp = $validated['no_hp'];
            $kepsek->alamat = $validated['alamat'];
            if ($request->hasFile('foto_profil')) {
                $kepsek->foto_profil = $request->file('foto_profil')->store('profiles', 'public');
            }
            $kepsek->save();
        } elseif ($role === 'siswa') {
            $siswa = $user->siswa ?? new Siswa(['user_id' => $user->id]);
            $siswa->nama_siswa = $user->name;
            $siswa->nis = $validated['nis'];
            $siswa->nisn = $validated['nisn'] ?? null;
            $siswa->jenis_kelamin = $validated['jenis_kelamin'];
            $siswa->tempat_lahir = $validated['tempat_lahir'] ?? null;
            $siswa->tanggal_lahir = $validated['tanggal_lahir'] ?? null;
            $siswa->alamat = $validated['alamat'] ?? null;
            $siswa->no_wa_siswa = $validated['no_wa_siswa'] ?? null;
            if (!empty($validated['id_kelas'])) {
                $siswa->id_kelas = $validated['id_kelas'];
            }
            $siswa->nama_orang_tua_wali = $validated['nama_orang_tua_wali'] ?? null;
            $siswa->no_wa_orang_tua_wali = $validated['no_wa_orang_tua_wali'];
            if ($request->hasFile('foto_siswa')) {
                $siswa->foto_siswa = $request->file('foto_siswa')->store('profiles', 'public');
            }
            $siswa->save();
        }

        return back()->with('success', 'Profil Anda telah berhasil diperbarui.');
    }

    /**
     * Update the user password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user = auth()->user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('success', 'Kata sandi Anda telah berhasil diubah.');
    }
}
