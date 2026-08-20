@extends('layouts.app')
@section('title', 'Pengaturan Sistem & WhatsApp Gateway')

@section('content')

<div style="max-width:850px;">
    <div style="margin-bottom:1.5rem;">
        <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Pengaturan Sistem & WhatsApp Gateway</h2>
        <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Konfigurasi identitas sekolah, tahun ajaran aktif, dan integrasi WhatsApp Gateway API.</p>
    </div>

    {{-- 1. Form Konfigurasi Utama --}}
    <div class="card" style="margin-bottom:1.5rem;">
        <form action="{{ route('admin.pengaturan.update') }}" method="POST">
            @csrf

            <h3 class="card-title" style="border-bottom:1px solid var(--border-color); padding-bottom:0.75rem; margin-bottom:1rem; color:var(--primary-dark);">
                Identitas Sekolah & Aplikasi
            </h3>
            
            <div class="form-group">
                <label class="form-label">Nama Sekolah / Instansi</label>
                <input type="text" name="nama_sekolah" class="form-control"
                       value="{{ old('nama_sekolah', $settings['nama_sekolah']) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Tahun Ajaran Aktif</label>
                <input type="text" name="tahun_ajaran_aktif" class="form-control"
                       value="{{ old('tahun_ajaran_aktif', $settings['tahun_ajaran_aktif']) }}" required>
            </div>

            <hr style="border:0; border-top:1px solid var(--border-color); margin:1.5rem 0;">

            <h3 class="card-title" style="border-bottom:1px solid var(--border-color); padding-bottom:0.75rem; margin-bottom:1rem; color:var(--primary-dark);">
                Integrasi WhatsApp Gateway (Fonnte)
            </h3>
            <p style="color:var(--text-muted); font-size:0.875rem; margin-bottom:1rem;">
                Sistem ini menggunakan gateway resmi <strong>Fonnte</strong> (<a href="https://fonnte.com" target="_blank" style="color:var(--primary); text-decoration:underline;">fonnte.com</a>) untuk mengirimkan notifikasi otomatis ke Guru BK, Siswa, dan Orang Tua.
            </p>

            <div class="form-group">
                <label class="form-label">Status Koneksi Gateway Saat Ini</label>
                <div>
                    @if($settings['status_type'] == 'success')
                        <span class="badge badge-success" style="font-size:0.875rem; padding:0.4rem 0.75rem;">
                            {{ $settings['status_gateway'] }}
                        </span>
                        @if(!empty($settings['device_info']))
                            <div style="margin-top:0.75rem; background:#F8FAFC; border:1px solid #E2E8F0; border-radius:0.375rem; padding:0.75rem 1rem; font-size:0.8125rem;">
                                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:0.5rem;">
                                    <div><strong>Nama Perangkat:</strong> {{ $settings['device_info']['name'] ?? '-' }}</div>
                                    <div><strong>No. WhatsApp:</strong> {{ $settings['device_info']['device'] ?? '-' }}</div>
                                    <div><strong>Sisa Kuota:</strong> {{ $settings['device_info']['quota'] ?? '-' }} pesan</div>
                                    <div><strong>Masa Berlaku:</strong> {{ $settings['device_info']['expired'] ?? '-' }}</div>
                                </div>
                            </div>
                        @endif
                    @elseif($settings['status_type'] == 'warning')
                        <span class="badge badge-warning" style="font-size:0.875rem; padding:0.4rem 0.75rem;">
                            {{ $settings['status_gateway'] }}
                        </span>
                    @else
                        <span class="badge badge-danger" style="font-size:0.875rem; padding:0.4rem 0.75rem;">
                            {{ $settings['status_gateway'] }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">API Gateway Endpoint URL</label>
                <input type="url" name="wa_gateway_url" class="form-control"
                       value="{{ old('wa_gateway_url', $settings['wa_gateway_url']) }}">
                <small style="color:var(--text-muted);">Default Fonnte: <code>https://api.fonnte.com/send</code></small>
            </div>

            <div class="form-group">
                <label class="form-label">Fonnte API Token / Key</label>
                <div style="position:relative;">
                    <input type="password" name="wa_gateway_token" id="wa_gateway_token" class="form-control"
                           value="{{ old('wa_gateway_token', $settings['wa_gateway_token']) }}" style="padding-right:2.75rem;" placeholder="Masukkan Token Fonnte Anda">
                    <button type="button"
                            onclick="togglePasswordVisibility('wa_gateway_token', this)"
                            style="position:absolute; right:0.75rem; top:50%; transform:translateY(-50%); border:0; background:none; cursor:pointer; color:var(--text-muted); display:flex; align-items:center; justify-content:center; width:1.5rem; height:1.5rem;"
                            title="Tampilkan / Sembunyikan Token">
                        <svg class="eye-open" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg class="eye-closed" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                        </svg>
                    </button>
                </div>
                <small style="color:var(--text-muted);">Dapatkan token dari dashboard Fonnte Anda pada menu <strong>Device</strong>.</small>
            </div>

            <div style="margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
            </div>
        </form>
    </div>

    {{-- 2. Card Uji Coba Pengiriman WhatsApp --}}
    <div class="card">
        <h3 class="card-title" style="border-bottom:1px solid var(--border-color); padding-bottom:0.75rem; margin-bottom:0.5rem; color:var(--primary-dark);">
            Uji Coba Pengiriman Notifikasi WhatsApp Langsung
        </h3>
        <p style="color:var(--text-muted); font-size:0.875rem; margin-bottom:1.25rem;">
            Gunakan form ini untuk mencoba mengirimkan pesan uji coba ke nomor WhatsApp Anda sendiri guna memastikan gateway telah terhubung 100%.
        </p>

        <form action="{{ route('admin.pengaturan.test-wa') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nomor WhatsApp Penerima (Contoh: 081234567890)</label>
                <input type="text" name="no_wa_tujuan" class="form-control" placeholder="08xxxxxxxxxx" required>
            </div>

            <div class="form-group">
                <label class="form-label">Isi Pesan Uji Coba (Opsional)</label>
                <textarea name="pesan_tes" class="form-control" rows="3" placeholder="Biarkan kosong untuk menggunakan teks default"></textarea>
            </div>

            <button type="submit" class="btn btn-gold" style="font-weight:700; display:inline-flex; align-items:center; gap:0.5rem;">
                <span>Kirim Pesan Uji Coba Sekarang</span>
            </button>
        </form>
    </div>
</div>

@endsection
