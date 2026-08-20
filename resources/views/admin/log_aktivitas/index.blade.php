@extends('layouts.app')
@section('title', 'Log Aktivitas Sistem')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem;">Log Aktivitas & Notifikasi Sistem</h2>
    <p style="color:#64748b; margin:0.25rem 0 0 0; font-size:0.875rem;">Riwayat pengiriman notifikasi WhatsApp dan log aktivitas sistem.</p>
</div>

<div class="card">
    {{-- Form Filter --}}
    <form action="{{ route('admin.log-aktivitas.index') }}" method="GET" style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;">
        <input type="text" name="search" class="form-control" style="flex:1; min-width:200px;"
               placeholder="Cari penerima, no. WA, pesan..."
               value="{{ request('search') }}">

        <select name="penerima_tipe" class="form-control" style="width:160px;">
            <option value="">-- Penerima --</option>
            <option value="guru_bk" {{ request('penerima_tipe') == 'guru_bk' ? 'selected' : '' }}>Guru BK</option>
            <option value="siswa" {{ request('penerima_tipe') == 'siswa' ? 'selected' : '' }}>Siswa</option>
            <option value="ortu" {{ request('penerima_tipe') == 'ortu' ? 'selected' : '' }}>Orang Tua</option>
        </select>

        <select name="status" class="form-control" style="width:160px;">
            <option value="">-- Status Dispatch --</option>
            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Terkirim (Sent)</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal (Failed)</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->hasAny(['search', 'penerima_tipe', 'status']))
            <a href="{{ route('admin.log-aktivitas.index') }}" class="btn" style="background:#e2e8f0; color:#334155;">Reset</a>
        @endif
    </form>

    {{-- Tabel Logs --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Waktu Log</th>
                    <th>Penerima</th>
                    <th>No. WhatsApp</th>
                    <th>Tipe Notifikasi</th>
                    <th>Isi Pesan Summary</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td><small style="color:#64748b;">{{ $log->created_at->format('d/m/Y H:i:s') }}</small></td>
                    <td>
                        <strong>{{ $log->penerima_nama }}</strong><br>
                        <small style="color:#64748b; text-transform:uppercase;">({{ $log->penerima_tipe }})</small>
                    </td>
                    <td><code>{{ $log->no_wa }}</code></td>
                    <td><span class="badge badge-info">{{ str_replace('_', ' ', $log->jenis_notifikasi) }}</span></td>
                    <td style="max-width:300px; font-size:0.8rem; color:#334155;">
                        {{ \Illuminate\Support\Str::limit($log->isi_pesan, 80) }}
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
                    <td colspan="6" style="text-align:center; padding:2rem; color:#64748b;">
                        Belum ada data log aktivitas atau notifikasi sistem terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div style="margin-top:1.25rem;">
        {{ $logs->links() }}
    </div>
</div>

@endsection
