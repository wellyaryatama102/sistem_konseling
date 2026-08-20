@extends('layouts.app')
@section('title', 'Riwayat Konseling')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Riwayat Konseling</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Direktori data siswa dan histori pelaksanaan sesi bimbingan konseling SMKN 2 Guguak.</p>
</div>

{{-- Tab Navigasi --}}
<div style="display:flex; gap:0.5rem; border-bottom:2px solid var(--border-color); margin-bottom:1.5rem;">
    <a href="{{ route('guru.siswa.index', ['tab' => 'siswa']) }}" 
       style="padding:0.75rem 1.25rem; font-weight:700; font-size:0.925rem; text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem; border-bottom:3px solid {{ $activeTab === 'siswa' ? 'var(--primary)' : 'transparent' }}; color:{{ $activeTab === 'siswa' ? 'var(--primary)' : 'var(--text-muted)' }}; margin-bottom:-2px;">
        <span>👥 Direktori Siswa</span>
        <span class="badge {{ $activeTab === 'siswa' ? 'badge-primary' : 'badge-secondary' }}" style="font-size:0.75rem;">
            {{ $siswas->total() }}
        </span>
    </a>
    <a href="{{ route('guru.siswa.index', ['tab' => 'riwayat']) }}" 
       style="padding:0.75rem 1.25rem; font-weight:700; font-size:0.925rem; text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem; border-bottom:3px solid {{ $activeTab === 'riwayat' ? 'var(--primary)' : 'transparent' }}; color:{{ $activeTab === 'riwayat' ? 'var(--primary)' : 'var(--text-muted)' }}; margin-bottom:-2px;">
        <span>📜 Log Riwayat Konseling</span>
        <span class="badge {{ $activeTab === 'riwayat' ? 'badge-primary' : 'badge-secondary' }}" style="font-size:0.75rem;">
            {{ $riwayats->total() }}
        </span>
    </a>
</div>

@if($activeTab === 'siswa')
    {{-- TAB 1: DIREKTORI SISWA --}}
    <div class="card">
        {{-- Form Filter Siswa --}}
        <form action="{{ route('guru.siswa.index') }}" method="GET" style="display:flex; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap; align-items:center;">
            <input type="hidden" name="tab" value="siswa">
            <input type="text" name="search" class="form-control" style="flex:1; min-width:200px;"
                   placeholder="Cari NIS, NISN, atau Nama Siswa..."
                   value="{{ request('search') }}">

            <select name="kelas_id" class="form-control" style="width:180px;">
                <option value="">-- Semua Kelas --</option>
                @foreach($kelases as $k)
                    <option value="{{ $k->id_kelas }}" {{ request('kelas_id') == $k->id_kelas ? 'selected' : '' }}>
                        {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">Filter</button>
            @if(request()->hasAny(['search', 'kelas_id']))
                <a href="{{ route('guru.siswa.index', ['tab' => 'siswa']) }}" class="btn btn-secondary">Reset</a>
            @endif
        </form>

        {{-- Tabel Data Siswa --}}
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama Siswa</th>
                        <th>NIS / NISN</th>
                        <th>Kelas & Jurusan</th>
                        <th>Kontak Siswa & Ortu</th>
                        <th style="text-align:center;">Sesi Konseling</th>
                        <th>Status</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $index => $s)
                    <tr>
                        <td>{{ $siswas->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $s->nama_siswa }}</strong><br>
                            <small style="color:var(--text-muted);">
                                {{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : ($s->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
                            </small>
                        </td>
                        <td>
                            <span style="font-weight:700; color:var(--primary-dark);">{{ $s->nis ?? '-' }}</span><br>
                            <small style="color:var(--text-muted);">NISN: {{ $s->nisn ?? '-' }}</small>
                        </td>
                        <td>
                            <strong>{{ $s->kelas->nama_kelas ?? '-' }}</strong><br>
                            <small style="color:var(--text-muted);">{{ $s->kelas->jurusan->nama_jurusan ?? '-' }}</small>
                        </td>
                        <td>
                            <small>Siswa: <code>{{ $s->no_wa_siswa ?: '-' }}</code></small><br>
                            <small>Ortu: <code>{{ $s->no_wa_orang_tua_wali ?: '-' }}</code></small>
                        </td>
                        <td style="text-align:center;">
                            @if($s->total_sesi_selesai > 0)
                                <span class="badge badge-success" title="{{ $s->total_sesi_selesai }} sesi selesai dilaksanakan">
                                    {{ $s->total_sesi_selesai }} Sesi Selesai
                                </span>
                            @elseif($s->total_pengajuan > 0)
                                <span class="badge badge-warning" title="{{ $s->total_pengajuan }} pengajuan">
                                    {{ $s->total_pengajuan }} Pengajuan
                                </span>
                            @else
                                <span class="badge badge-secondary">Belum Pernah</span>
                            @endif
                        </td>
                        <td>
                            @if($s->status_siswa == 'aktif')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-warning">{{ strtoupper($s->status_siswa) }}</span>
                            @endif
                        </td>
                        <td style="text-align:center; white-space:nowrap;">
                            <a href="{{ route('guru.siswa.show', $s->id_siswa) }}" class="btn btn-sm btn-primary" style="display:inline-flex; align-items:center; gap:0.35rem;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Rekam Jejak
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:2.5rem; color:var(--text-muted);">
                            Tidak ada data siswa ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:1.25rem;">
            {{ $siswas->links() }}
        </div>
    </div>

@else
    {{-- TAB 2: LOG RIWAYAT SESI KONSELING --}}
    <div class="card">
        {{-- Form Filter Riwayat --}}
        <form action="{{ route('guru.siswa.index') }}" method="GET" style="display:flex; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap; align-items:center;">
            <input type="hidden" name="tab" value="riwayat">
            <input type="text" name="search_riwayat" class="form-control" style="flex:1; min-width:200px;"
                   placeholder="Cari NIS, NISN, atau Nama Siswa..."
                   value="{{ request('search_riwayat') }}">

            <select name="kelas_riwayat" class="form-control" style="width:170px;">
                <option value="">-- Semua Kelas --</option>
                @foreach($kelases as $k)
                    <option value="{{ $k->id_kelas }}" {{ request('kelas_riwayat') == $k->id_kelas ? 'selected' : '' }}>
                        {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="tanggal_riwayat" class="form-control" style="width:160px;" value="{{ request('tanggal_riwayat') }}">

            <select name="status_riwayat" class="form-control" style="width:160px;">
                <option value="">-- Semua Status --</option>
                <option value="selesai" {{ request('status_riwayat') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="terjadwal" {{ request('status_riwayat') == 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                <option value="dibatalkan" {{ request('status_riwayat') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>

            <button type="submit" class="btn btn-primary">Filter</button>
            @if(request()->hasAny(['search_riwayat', 'kelas_riwayat', 'tanggal_riwayat', 'status_riwayat']))
                <a href="{{ route('guru.siswa.index', ['tab' => 'riwayat']) }}" class="btn btn-secondary">Reset</a>
            @endif
        </form>

        {{-- Tabel Riwayat Konseling --}}
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Guru BK</th>
                        <th>Jenis Layanan</th>
                        <th>Tgl Pelaksanaan</th>
                        <th>Hasil & Tindak Lanjut</th>
                        <th>Status Sesi</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayats as $index => $r)
                    <tr>
                        <td>{{ $riwayats->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $r->pengajuan->siswa->nama_siswa ?? '-' }}</strong><br>
                            <small style="color:var(--text-muted);">NIS: {{ $r->pengajuan->siswa->nis ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $r->pengajuan->siswa->kelas->nama_kelas ?? '-' }}</span>
                        </td>
                        <td>
                            <small><strong>{{ $r->pengajuan && $r->pengajuan->jadwal && $r->pengajuan->jadwal->guruBk ? $r->pengajuan->jadwal->guruBk->nama_lengkap : 'Guru BK' }}</strong></small>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ ucfirst($r->pengajuan->jenis_konseling ?? 'Individu') }}</span>
                        </td>
                        <td>
                            <small style="font-weight:700; color:var(--primary-dark);">
                                {{ \Carbon\Carbon::parse($r->tanggal_pelaksanaan)->format('d/m/Y') }}
                            </small>
                        </td>
                        <td style="max-width:240px; font-size:0.85rem;">
                            <strong>Hasil:</strong> {{ \Illuminate\Support\Str::limit($r->hasil_konseling ?? '-', 50) }}<br>
                            @if($r->rencana_tindak_lanjut)
                                <small style="color:var(--text-muted);"><strong>RTL:</strong> {{ \Illuminate\Support\Str::limit($r->rencana_tindak_lanjut, 40) }}</small>
                            @endif
                        </td>
                        <td>
                            @if($r->status_sesi == 'selesai')
                                <span class="badge badge-success">Selesai</span>
                            @elseif($r->status_sesi == 'terjadwal')
                                <span class="badge badge-warning">Terjadwal</span>
                            @elseif($r->status_sesi == 'dibatalkan')
                                <span class="badge badge-danger">Dibatalkan</span>
                            @else
                                <span class="badge badge-secondary">{{ strtoupper($r->status_sesi) }}</span>
                            @endif
                        </td>
                        <td style="text-align:center; white-space:nowrap;">
                            @if($r->pengajuan && $r->pengajuan->id_siswa)
                                <a href="{{ route('guru.siswa.show', $r->pengajuan->id_siswa) }}" class="btn btn-sm btn-primary" style="display:inline-flex; align-items:center; gap:0.35rem;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Detail Siswa
                                </a>
                            @else
                                <span style="color:var(--text-muted); font-size:0.8rem;">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align:center; padding:2.5rem; color:var(--text-muted);">
                            Belum ada data riwayat sesi konseling ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:1.25rem;">
            {{ $riwayats->links() }}
        </div>
    </div>
@endif

@endsection
