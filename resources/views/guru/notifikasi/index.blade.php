@extends('layouts.app')
@section('title', 'Log Notifikasi WhatsApp')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Log Notifikasi WhatsApp Gateway</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Riwayat pengiriman notifikasi otomatis persetujuan jadwal, pembatalan, dan surat panggilan.</p>
</div>

<div class="card">
    {{-- Form Filter --}}
    <form action="{{ route('guru.notifikasi.index') }}" method="GET" style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;">
        <select name="penerima_tipe" class="form-control" style="width:200px;">
            <option value="">-- Semua Penerima --</option>
            <option value="siswa" {{ request('penerima_tipe') == 'siswa' ? 'selected' : '' }}>Siswa</option>
            <option value="orang_tua" {{ request('penerima_tipe') == 'orang_tua' ? 'selected' : '' }}>Orang Tua / Wali</option>
            <option value="guru_bk" {{ request('penerima_tipe') == 'guru_bk' ? 'selected' : '' }}>Guru BK</option>
        </select>

        <select name="status" class="form-control" style="width:160px;">
            <option value="">-- Semua Status --</option>
            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Terkirim</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->hasAny(['penerima_tipe', 'status']))
            <a href="{{ route('guru.notifikasi.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>

    {{-- Tabel Log Notifikasi --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Waktu Kirim</th>
                    <th>Penerima & Tipe</th>
                    <th>Nomor WhatsApp</th>
                    <th>Jenis Pesan</th>
                    <th>Isi Pesan Notifikasi</th>
                    <th>Status Gateway</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td><small style="color:var(--text-muted); font-weight:600;">{{ $log->created_at ? $log->created_at->format('d/m/Y H:i') : '-' }}</small></td>
                    <td>
                        <strong>{{ $log->penerima_nama }}</strong><br>
                        <span class="badge badge-info">{{ ucfirst($log->penerima_tipe) }}</span>
                    </td>
                    <td><code>{{ $log->no_wa }}</code></td>
                    <td>
                        <span class="badge badge-gold">{{ strtoupper(str_replace('_', ' ', $log->jenis_notifikasi)) }}</span>
                    </td>
                    <td style="max-width:320px; font-size:0.825rem; color:var(--text-dark);">
                        {{ $log->isi_pesan }}
                    </td>
                    <td>
                        @if($log->status == 'sent')
                            <span class="badge badge-success">Terkirim</span>
                        @elseif($log->status == 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @else
                            <span class="badge badge-danger">Gagal</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:2rem; color:var(--text-muted);">
                        Belum ada riwayat pengiriman notifikasi WhatsApp.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.25rem;">
        {{ $logs->links() }}
    </div>
</div>

@endsection
