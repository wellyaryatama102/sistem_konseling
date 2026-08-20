@extends('layouts.app')
@section('title', 'Belum Ada Kelas')

@section('content')
<div class="card" style="text-align:center; padding:3rem 1.5rem;">
    <h2 style="color:var(--warning); margin-bottom:0.5rem;">Pemberitahuan Wali Kelas</h2>
    <p style="color:#64748b; max-width:500px; margin:0 auto 1.5rem auto;">
        Akun Anda belum ditugaskan sebagai Wali Kelas pada kelas manapun dalam sistem. Silakan hubungi Administrator Sekolah untuk penugasan kelas.
    </p>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logoutFormNoKelas').submit();" class="btn btn-primary">
        Logout
    </a>
    <form id="logoutFormNoKelas" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>
</div>
@endsection
