<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username atau email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginInput = $request->input('username');
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $fieldType => $loginInput,
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->status !== 'active') {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Akun Anda sedang nonaktif. Silakan hubungi administrator.',
                ])->onlyInput('username');
            }

            $request->session()->regenerate();
            return $this->redirectUser($user);
        }

        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function redirectUser($user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'guru_bk':
                return redirect()->route('guru.dashboard');
            case 'wali_kelas':
                return redirect()->route('wali.dashboard');
            case 'siswa':
                return redirect()->route('siswa.dashboard');
            case 'wakasis':
                return redirect()->route('wakasis.dashboard');
            case 'kepala_sekolah':
                return redirect()->route('kepsek.dashboard');
            default:
                Auth::logout();
                return redirect()->route('login')->withErrors(['username' => 'Role tidak dikenal.']);
        }
    }
}
