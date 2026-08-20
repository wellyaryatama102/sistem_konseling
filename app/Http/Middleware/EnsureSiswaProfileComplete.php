<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSiswaProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'siswa') {
            $siswa = $user->siswa;
            if (!$siswa || !$siswa->is_profile_complete || empty($siswa->no_wa_ortu)) {
                if (!$request->routeIs('siswa.profile*') && !$request->routeIs('profile*') && !$request->routeIs('logout')) {
                    return redirect()->route('profile.edit')->with('warning', 'Anda wajib melengkapi profil dan Nomor WhatsApp Orang Tua/Wali terlebih dahulu.');
                }
            }
        }

        return $next($request);
    }
}
