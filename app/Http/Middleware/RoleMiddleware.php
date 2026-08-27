<?php

namespace App\Http\Middleware;

//menagambil informasi
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * FUNGSI FILE INI:
 * Mengamankan halaman aplikasi agar hanya dapat diakses oleh pengguna dengan role/hak akses yang sesuai.
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->status !== 'active') {
            auth()->logout();
            return redirect()->route('login')->withErrors(['username' => 'Akun Anda sedang nonaktif. Silakan hubungi administrator.']);
        }

        if (!in_array($request->user()->role, $roles)) {
            $userRole = $request->user()->role;
            $targetRoute = $this->getRoleDashboardRoute($userRole);

            return redirect($targetRoute)->with('warning', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }

    //menentukan halaman dashboad 
    protected function getRoleDashboardRoute(string $role): string
    {
        switch ($role) {
            case 'admin':
                return route('admin.dashboard');
            case 'guru_bk':
                return route('guru.dashboard');
            case 'wali_kelas':
                return route('wali.dashboard');
            case 'siswa':
                return route('siswa.dashboard');
            case 'wakasis':
                return route('wakasis.dashboard');
            case 'kepala_sekolah':
                return route('kepsek.dashboard');
            default:
                auth()->logout();
                return route('login');
        }
    }
}
