@extends('layouts.app')
@section('title', 'Rekapitulasi Layanan Sekolah')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Rekapitulasi & Laporan Konseling Sekolah</h2>
        <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Rekapitulasi data statistik pelayanan konseling tingkat sekolah untuk Wakil Kesiswaan.</p>
    </div>

    <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
        <a href="{{ route('wakasis.laporan.pdf', request()->all()) }}" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:0.4rem;">
            📄 Unduh PDF
        </a>
        <a href="{{ route('wakasis.laporan.excel', request()->all()) }}" class="btn btn-success" style="display:inline-flex; align-items:center; gap:0.4rem; background-color:#10b981; border-color:#10b981; color:#fff;">
            📊 Unduh Excel (.xlsx)
        </a>
    </div>
</div>

{{-- Form Pemilihan Jenis Rekap & Filter --}}
<div class="card" style="margin-bottom:1.5rem;">
    <form action="{{ route('wakasis.rekapitulasi.index') }}" method="GET" style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">
        <div style="flex:1; min-width:240px;">
            <label style="display:block; font-size:0.875rem; font-weight:700; margin-bottom:0.35rem;">Pilih Jenis Rekap / Laporan:</label>
            <select name="tipe_rekap" class="form-control" onchange="this.form.submit()">
                <option value="rekap_sekolah" {{ ($tipeRekap ?? '') == 'rekap_sekolah' ? 'selected' : '' }}>📋 Rekapitulasi Pelayanan Konseling Sekolah</option>
                <option value="rekap_jurusan" {{ ($tipeRekap ?? '') == 'rekap_jurusan' ? 'selected' : '' }}>🏢 Rekapitulasi Layanan per Jurusan & Kelas</option>
            </select>
        </div>

        <div style="width:160px;">
            <label style="display:block; font-size:0.875rem; font-weight:700; margin-bottom:0.35rem;">Bulan:</label>
            <select name="bulan" class="form-control">
                <option value="">-- Semua Bulan --</option>
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ ($bulan ?? '') == $m ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                @endfor
            </select>
        </div>

        <div style="width:130px;">
            <label style="display:block; font-size:0.875rem; font-weight:700; margin-bottom:0.35rem;">Tahun:</label>
            <select name="tahun" class="form-control">
                <option value="">-- Tahun --</option>
                @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                    <option value="{{ $y }}" {{ ($tahun ?? '') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>

        <div style="width:190px;">
            <label style="display:block; font-size:0.875rem; font-weight:700; margin-bottom:0.35rem;">Jurusan:</label>
            <select name="id_jurusan" class="form-control">
                <option value="">-- Semua Jurusan --</option>
                @foreach($jurusans as $j)
                    <option value="{{ $j->id_jurusan }}" {{ ($idJurusan ?? '') == $j->id_jurusan ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <button type="submit" class="btn btn-primary">Terapkan Filter</button>
            @if(request()->hasAny(['tipe_rekap', 'bulan', 'tahun', 'id_jurusan']))
                <a href="{{ route('wakasis.rekapitulasi.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </div>
    </form>
</div>

{{-- Tabel Rekap Sesuai Pilihan --}}
@if(($tipeRekap ?? '') == 'rekap_jurusan')
    <div class="card" style="margin-bottom:1.5rem;">
        <h3 class="card-title" style="margin-bottom:1rem;">Rekapitulasi Pelayanan Konseling per Konsentrasi Keahlian (Jurusan) & Kelas</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jurusan</th>
                        <th>Nama Kelas</th>
                        <th>Wali Kelas</th>
                        <th>Total Siswa</th>
                        <th>Siswa Berkonseling</th>
                        <th>Tingkat Konseling (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jurusanStats as $index => $js)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $js['jurusan'] }}</strong></td>
                        <td>{{ $js['nama_kelas'] }}</td>
                        <td>{{ $js['wali_kelas'] }}</td>
                        <td>{{ $js['total_siswa'] }} Siswa</td>
                        <td><span class="badge badge-info">{{ $js['siswa_konseling'] }} Sesi</span></td>
                        <td>
                            <div style="display:flex; align-items:center; gap:0.5rem;">
                                <div style="flex:1; background:#e2e8f0; height:8px; border-radius:4px; overflow:hidden;">
                                    <div style="background:var(--primary); height:100%; width:{{ min($js['persentase'], 100) }}%;"></div>
                                </div>
                                <span style="font-weight:700; font-size:0.8rem;">{{ $js['persentase'] }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="card">
        <h3 class="card-title" style="margin-bottom:1rem;">Log Rekapitulasi Sesi Konseling Seluruh Siswa Sekolah</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>Kelas & Jurusan</th>
                        <th>Guru BK Pembimbing</th>
                        <th>Jenis Konseling</th>
                        <th>Status Sesi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sesiList as $index => $k)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><small style="font-weight:700; color:var(--primary-dark);">{{ \Carbon\Carbon::parse($k->tanggal_pelaksanaan)->format('d/m/Y') }}</small></td>
                        <td>
                            <strong>{{ $k->pengajuan->siswa->nama_siswa ?? '-' }}</strong><br>
                            <small style="color:var(--text-muted);">NIS: {{ $k->pengajuan->siswa->nis ?? '-' }}</small>
                        </td>
                        <td>
                            {{ $k->pengajuan->siswa->kelas->nama_kelas ?? '-' }}<br>
                            <small style="color:var(--text-muted);">{{ $k->pengajuan->siswa->kelas->jurusan->nama_jurusan ?? '-' }}</small>
                        </td>
                        <td>{{ $k->pengajuan && $k->pengajuan->jadwal && $k->pengajuan->jadwal->guruBk ? $k->pengajuan->jadwal->guruBk->nama_lengkap : 'Guru BK' }}</td>
                        <td><span class="badge badge-info">{{ ucfirst($k->pengajuan->jenis_konseling ?? 'Individu') }}</span></td>
                        <td>
                            @if($k->status_sesi == 'selesai')
                                <span class="badge badge-success">Selesai</span>
                            @elseif($k->status_sesi == 'terjadwal')
                                <span class="badge badge-warning">Terjadwal</span>
                            @else
                                <span class="badge badge-danger">{{ strtoupper($k->status_sesi) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:2rem; color:var(--text-muted);">
                            Belum ada data sesi konseling pada filter yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

@endsection
