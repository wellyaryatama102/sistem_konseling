<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return Auth::check() ? $this->redirectUser(Auth::user()) : view('auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username atau email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);
        $input = $request->input('username');
        $fieldType = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (Auth::attempt([$fieldType => $input, 'password' => $request->password], $request->boolean('remember'))) {
            $user = Auth::user();
            if ($user->status !== 'active') {
                Auth::logout();
                return back()->withErrors(['username' => 'Akun Anda sedang nonaktif. Silakan hubungi administrator.'])->onlyInput('username');
            }
            $request->session()->regenerate();
            return $this->redirectUser($user);
        }
        return back()->withErrors(['username' => 'Username atau password yang Anda masukkan salah.'])->onlyInput('username');
    }
    public function redirectUser($user)
    {
        $routes = [
            'admin'          => 'admin.dashboard',
            'guru_bk'        => 'guru.dashboard',
            'wali_kelas'     => 'wali.dashboard',
            'siswa'          => 'siswa.dashboard',
            'wakasis'        => 'wakasis.dashboard',
            'kepala_sekolah' => 'kepsek.dashboard',
        ];
        if (array_key_exists($user->role, $routes)) {
            return redirect()->route($routes[$user->role]);
        }
        Auth::logout();
        return redirect()->route('login')->withErrors(['username' => 'Role tidak dikenal.']);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}