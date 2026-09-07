<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\{Kelas, Admin, GuruBk, WaliKelas, Wakasis, Kepsek, Siswa};

/**
 * FUNGSI FILE INI:
 * Menampilkan dan memperbarui data profil pengguna mandiri serta mengubah kata sandi akun.
 */
class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $role = $user->role;

        $modelMap = [
            'admin'          => Admin::class,
            'guru_bk'        => GuruBk::class,
            'wali_kelas'     => WaliKelas::class,
            'wakasis'        => Wakasis::class,
            'kepala_sekolah' => Kepsek::class,
            'siswa'          => Siswa::class,
        ];

        $modelClass = $modelMap[$role] ?? null;
        $defaultData = $role === 'siswa'
            ? ['username' => $user->username, 'password' => $user->password, 'nama_siswa' => $user->name, 'status_siswa' => 'aktif']
            : ['username' => $user->username, 'password' => $user->password, 'nama_lengkap' => $user->name, 'email' => $user->email];

        $profile = $modelClass ? $modelClass::firstOrCreate(['user_id' => $user->id], $defaultData) : null;
        $kelases = $role === 'siswa' ? Kelas::all() : [];
        $kelas = $role === 'wali_kelas' ? Kelas::where('id_wali_kelas', $profile?->id_wali_kelas)->first() : null;

        return view('profile.edit', compact('user', 'profile', 'kelases', 'kelas'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $role = $user->role;

        $userRules = [
            'name'     => 'required|string|max:255',
            'username' => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($user->id)],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ];

        $profileRules = match (true) {
            in_array($role, ['admin', 'guru_bk', 'wali_kelas', 'wakasis', 'kepala_sekolah']) => [
                'nip' => 'nullable|string|max:50', 'jenis_kelamin' => 'nullable|in:L,P',
                'tempat_lahir' => 'nullable|string|max:100', 'tanggal_lahir' => 'nullable|date',
                'no_hp' => 'nullable|string|max:20', 'alamat' => 'nullable|string',
                'foto_profil' => 'nullable|image|max:2048'
            ],
            $role === 'siswa' => [
                'nis' => ['required', 'string', 'max:50', Rule::unique('siswa', 'nis')->ignore($user->siswa->id_siswa ?? null, 'id_siswa')],
                'nisn' => ['nullable', 'string', 'max:50', Rule::unique('siswa', 'nisn')->ignore($user->siswa->id_siswa ?? null, 'id_siswa')],
                'jenis_kelamin' => 'required|in:L,P', 'tempat_lahir' => 'nullable|string|max:100',
                'tanggal_lahir' => 'nullable|date', 'alamat' => 'nullable|string',
                'no_wa_siswa' => 'nullable|string|max:20', 'id_kelas' => 'nullable|exists:kelas,id_kelas',
                'nama_orang_tua_wali' => 'nullable|string|max:255', 'no_wa_orang_tua_wali' => 'required|string|max:20',
                'foto_siswa' => 'nullable|image|max:2048'
            ],
            default => []
        };

        $validated = $request->validate(array_merge($userRules, $profileRules));

        // Update Users
        $user->update([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
        ]);

        // Update Model Spesifik Sesuai Role
        if ($role === 'siswa') {
            $siswa = $user->siswa ?? new Siswa(['user_id' => $user->id]);
            $siswa->fill($validated);
            $siswa->nama_siswa = $user->name;
            if ($request->hasFile('foto_siswa')) {
                $siswa->foto_siswa = $request->file('foto_siswa')->store('profiles', 'public');
            }
            $siswa->save();
        } else {
            $profile = match ($role) {
                'admin'          => $user->admin ?? new Admin(['user_id' => $user->id]),
                'guru_bk'        => $user->guruBk ?? new GuruBk(['user_id' => $user->id]),
                'wali_kelas'     => $user->waliKelas ?? new WaliKelas(['user_id' => $user->id]),
                'wakasis'        => $user->wakasis ?? new Wakasis(['user_id' => $user->id]),
                'kepala_sekolah' => $user->kepsek ?? new Kepsek(['user_id' => $user->id]),
                default          => null,
            };

            if ($profile) {
                $profile->fill($validated);
                $profile->username = $user->username;
                $profile->nama_lengkap = $user->name;
                $profile->email = $user->email;
                
                // Pemetaan khusus atribut NIP/NUPTK untuk Wali Kelas
                if ($role === 'wali_kelas') {
                    $profile->nip_nuptk = $validated['nip'] ?? null;
                }

                if ($request->hasFile('foto_profil')) {
                    $profile->foto_profil = $request->file('foto_profil')->store('profiles', 'public');
                }
                $profile->save();
            }
        }

        return back()->with('success', 'Profil Anda telah berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password'         => 'required|string|min:6|confirmed',
        ], [
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok.',
            'password.min'                      => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed'                => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        auth()->user()->update(['password' => Hash::make($validated['password'])]);

        return back()->with('success', 'Kata sandi Anda telah berhasil diubah.');
    }
}