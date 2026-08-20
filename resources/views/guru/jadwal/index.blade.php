@extends('layouts.app')
@section('title', 'Jadwal & Agenda Konseling')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
    <div>
        <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Jadwal &amp; Agenda Konseling Guru BK</h2>
        <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Kelola agenda sesi konseling siswa terdaftar dan atur slot ketersediaan waktu pelayanan BK.</p>
    </div>
</div>

@php
    $currentTab = request('tab', 'agenda');
@endphp

{{-- Tab Navigasi --}}
<div style="display:flex; gap:0.5rem; margin-bottom:1.25rem; border-bottom:2px solid #E2E8F0; padding-bottom:0;">
    <a href="{{ route('guru.jadwal.index', ['tab' => 'agenda']) }}" 
       style="padding:0.65rem 1.25rem; font-weight:700; font-size:0.875rem; text-decoration:none; border-bottom:3px solid {{ $currentTab === 'agenda' ? 'var(--primary)' : 'transparent' }}; color:{{ $currentTab === 'agenda' ? 'var(--primary)' : '#64748B' }}; margin-bottom:-2px;">
        Agenda Konseling Terjadwal
    </a>
    <a href="{{ route('guru.jadwal.index', ['tab' => 'ketersediaan']) }}" 
       style="padding:0.65rem 1.25rem; font-weight:700; font-size:0.875rem; text-decoration:none; border-bottom:3px solid {{ $currentTab === 'ketersediaan' ? 'var(--primary)' : 'transparent' }}; color:{{ $currentTab === 'ketersediaan' ? 'var(--primary)' : '#64748B' }}; margin-bottom:-2px;">
        Slot Ketersediaan Waktu Saya
    </a>
</div>

{{-- TAB 1: AGENDA KONSELING TERJADWAL --}}
@if($currentTab === 'agenda')
<div class="card">
    {{-- Form Filter --}}
    <form action="{{ route('guru.jadwal.index') }}" method="GET" style="display:flex; gap:0.75rem; margin-bottom:1.5rem; flex-wrap:wrap; align-items:center;">
        <input type="hidden" name="tab" value="agenda">

        <div style="min-width:160px;">
            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}" style="height:38px;">
        </div>

        <div style="width:160px;">
            <select name="status" class="form-control" style="height:38px;">
                <option value="">-- Semua Status --</option>
                <option value="terjadwal" {{ request('status') == 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        <div style="width:180px;">
            <select name="kelas_id" class="form-control" style="height:38px;">
                <option value="">-- Semua Kelas --</option>
                @foreach($kelases as $k)
                    <option value="{{ $k->id_kelas }}" {{ request('kelas_id') == $k->id_kelas ? 'selected' : '' }}>
                        {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="height:38px; padding:0 1.25rem;">Filter</button>
        @if(request()->hasAny(['tanggal', 'status', 'kelas_id']))
            <a href="{{ route('guru.jadwal.index', ['tab' => 'agenda']) }}" class="btn btn-secondary" style="height:38px; display:inline-flex; align-items:center; padding:0 1rem;">Reset</a>
        @endif
    </form>

    {{-- Tabel Jadwal Konseling --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width:35px; text-align:center;">No</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Tanggal Pelaksanaan</th>
                    <th>Waktu / Jam</th>
                    <th>Alasan / Permasalahan</th>
                    <th style="text-align:center; width:110px;">Status Sesi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $index => $j)
                <tr>
                    <td style="text-align:center; color:var(--text-muted);">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $j->pengajuan->siswa->nama_siswa ?? '-' }}</strong><br>
                        <small style="color:var(--text-muted);">NIS: {{ $j->pengajuan->siswa->nis ?? '-' }}</small>
                    </td>
                    <td>{{ $j->pengajuan->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td><small style="font-weight:700; color:var(--primary-dark);">{{ \Carbon\Carbon::parse($j->tanggal_pelaksanaan)->format('d F Y') }}</small></td>
                    <td>
                        <small style="color:var(--primary); font-weight:600;">
                            {{ $j->pengajuan && $j->pengajuan->jadwal ? substr($j->pengajuan->jadwal->jam_mulai, 0, 5) . ' - ' . substr($j->pengajuan->jadwal->jam_selesai, 0, 5) . ' WIB' : 'Insidental' }}
                        </small>
                    </td>
                    <td style="max-width:240px; font-size:0.8125rem;">
                        {{ $j->pengajuan->alasan_pengajuan ?? '-' }}
                    </td>
                    <td style="text-align:center;">
                        @if($j->status_sesi == 'terjadwal')
                            <span class="badge badge-info">Terjadwal</span>
                        @elseif($j->status_sesi == 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @elseif($j->status_sesi == 'dibatalkan')
                            <span class="badge badge-danger">Dibatalkan</span>
                        @else
                            <span class="badge badge-warning">{{ strtoupper($j->status_sesi) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:2.5rem; color:var(--text-muted);">
                        Belum ada agenda sesi konseling terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.25rem;">
        {{ $jadwals->links() }}
    </div>
</div>

{{-- TAB 2: SLOT KETERSEDIAAN SAYA --}}
@else
<div class="grid-2" style="gap:1.5rem; align-items:start;">
    {{-- Form Tambah Ketersediaan --}}
    <div class="card">
        <h3 class="card-title" style="margin-bottom:1rem;">Tambah Slot Waktu Tersedia</h3>
        <form action="{{ route('guru.ketersediaan.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Tanggal Pelayanan</label>
                <input type="date" name="tanggal_tersedia" class="form-control"
                       value="{{ old('tanggal_tersedia', date('Y-m-d')) }}" required>
            </div>

            <div class="grid-2" style="gap:1rem; grid-template-columns: 1fr 1fr; margin-bottom:1rem;">
                <div>
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control"
                           value="{{ old('jam_mulai', '08:00') }}" required>
                </div>
                <div>
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control"
                           value="{{ old('jam_selesai', '09:00') }}" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Tambah Slot Waktu</button>
        </form>
    </div>

    {{-- Tabel Ketersediaan --}}
    <div class="card">
        <h3 class="card-title" style="margin-bottom:1rem;">Daftar Slot Ketersediaan Terdaftar</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Hari / Tanggal</th>
                        <th>Waktu (Mulai - Selesai)</th>
                        <th style="text-align:center;">Status Slot</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ketersediaans as $s)
                    <tr>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($s->tanggal_tersedia)->format('d/m/Y') }}</strong>
                        </td>
                        <td>
                            <code>{{ substr($s->jam_mulai, 0, 5) }} - {{ substr($s->jam_selesai, 0, 5) }} WIB</code>
                        </td>
                        <td style="text-align:center;">
                            @if($s->status_slot == 'tersedia')
                                <span class="badge badge-success">Tersedia</span>
                            @elseif($s->status_slot == 'terisi')
                                <span class="badge badge-warning">Terisi Siswa</span>
                            @else
                                <span class="badge badge-info">{{ strtoupper($s->status_slot) }}</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($s->status_slot == 'tersedia')
                                <form action="{{ route('guru.ketersediaan.destroy', $s->id_jadwal) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus slot ketersediaan ini?')">
                                        Hapus
                                    </button>
                                </form>
                            @else
                                <small style="color:var(--text-muted);">-</small>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:2rem; color:var(--text-muted);">
                            Belum ada slot ketersediaan yang ditambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:1.25rem;">
            {{ $ketersediaans->links() }}
        </div>
    </div>
</div>
@endif

@endsection
