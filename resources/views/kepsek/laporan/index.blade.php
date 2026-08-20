@extends('layouts.app')
@section('title', 'Laporan Eksekutif')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Laporan Eksekutif Pelayanan Konseling Siswa</h2>
        <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Laporan menyeluruh untuk Kepala Sekolah SMKN 2 Guguak.</p>
    </div>

    <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
        <a href="{{ route('kepsek.laporan.pdf', request()->all()) }}" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:0.4rem;">
            📄 Unduh PDF
        </a>
        <a href="{{ route('kepsek.laporan.excel', request()->all()) }}" class="btn btn-success" style="display:inline-flex; align-items:center; gap:0.4rem; background-color:#10b981; border-color:#10b981; color:#fff;">
            📊 Unduh Excel (.xlsx)
        </a>
    </div>
</div>

{{-- Form Pemilihan Jenis Rekap & Filter --}}
<div class="card" style="margin-bottom:1.5rem;">
    <form action="{{ route('kepsek.laporan.index') }}" method="GET" style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">
        <div style="flex:1; min-width:240px;">
            <label style="display:block; font-size:0.875rem; font-weight:700; margin-bottom:0.35rem;">Pilih Jenis Rekap / Laporan:</label>
            <select name="tipe_rekap" class="form-control" onchange="this.form.submit()">
                <option value="rekap_eksekutif" {{ ($tipeRekap ?? '') == 'rekap_eksekutif' ? 'selected' : '' }}>📋 Rekapitulasi Eksekutif Pelayanan Siswa</option>
                <option value="kinerja_guru_bk" {{ ($tipeRekap ?? '') == 'kinerja_guru_bk' ? 'selected' : '' }}>👨‍🏫 Evaluasi Kinerja Guru BK</option>
                <option value="pemetaan_bidang" {{ ($tipeRekap ?? '') == 'pemetaan_bidang' ? 'selected' : '' }}>🧭 Pemetaan Masalah & Bidang Bimbingan</option>
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
            <label style="display:block; font-size:0.875rem; font-weight:700; margin-bottom:0.35rem;">Tahun Ajaran:</label>
            <select name="id_tahun_ajaran" class="form-control">
                <option value="">-- Semua Thn Ajaran --</option>
                @foreach($tahunAjarans as $ta)
                    <option value="{{ $ta->id_tahun_ajaran }}" {{ ($idTahunAjaran ?? '') == $ta->id_tahun_ajaran ? 'selected' : '' }}>{{ $ta->nama_tahun_ajaran }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <button type="submit" class="btn btn-primary">Terapkan Filter</button>
            @if(request()->hasAny(['tipe_rekap', 'bulan', 'tahun', 'id_tahun_ajaran']))
                <a href="{{ route('kepsek.laporan.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </div>
    </form>
</div>

{{-- Tabel Pratinjau Rekap Sesuai Pilihan --}}
@if(($tipeRekap ?? '') == 'kinerja_guru_bk')
    <div class="card">
        <h3 class="card-title" style="margin-bottom:1rem;">Evaluasi Kinerja & Beban Kerja Guru Bimbingan Konseling</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Guru BK</th>
                        <th>NIP</th>
                        <th>Slot Ketersediaan</th>
                        <th>Sesi Terlaksana</th>
                        <th>Tindak Lanjut & Surat</th>
                        <th>Tingkat Efektivitas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kinerjaGuru as $index => $kg)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $kg['nama'] }}</strong></td>
                        <td>{{ $kg['nip'] ?? '-' }}</td>
                        <td><span class="badge badge-info">{{ $kg['slot_dibuka'] }} Slot</span></td>
                        <td><span class="badge badge-success">{{ $kg['sesi_selesai'] }} Sesi</span></td>
                        <td><span class="badge badge-warning">{{ $kg['tindak_lanjut'] }} Kasus</span></td>
                        <td>
                            <div style="display:flex; align-items:center; gap:0.5rem;">
                                <div style="flex:1; background:#e2e8f0; height:8px; border-radius:4px; overflow:hidden;">
                                    <div style="background:#10b981; height:100%; width:{{ min($kg['efektivitas'], 100) }}%;"></div>
                                </div>
                                <span style="font-weight:700; font-size:0.85rem;">{{ $kg['efektivitas'] }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@elseif(($tipeRekap ?? '') == 'pemetaan_bidang')
    <div class="card">
        <h3 class="card-title" style="margin-bottom:1rem;">Pemetaan Bidang Bimbingan & Rekomendasi Program Sekolah</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Bidang Bimbingan</th>
                        <th>Jumlah Kasus</th>
                        <th>Persentase (%)</th>
                        <th>Rekomendasi Program Tindak Lanjut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pemetaanBidang as $index => $pb)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $pb['bidang'] }}</strong></td>
                        <td><span class="badge badge-info">{{ $pb['total'] }} Kasus</span></td>
                        <td>
                            <div style="display:flex; align-items:center; gap:0.5rem;">
                                <div style="flex:1; background:#e2e8f0; height:8px; border-radius:4px; overflow:hidden; min-width:80px;">
                                    <div style="background:var(--primary); height:100%; width:{{ min($pb['persentase'], 100) }}%;"></div>
                                </div>
                                <span style="font-weight:700; font-size:0.85rem;">{{ $pb['persentase'] }}%</span>
                            </div>
                        </td>
                        <td style="font-size:0.875rem;">{{ $pb['rekomendasi'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="card">
        <h3 class="card-title" style="margin-bottom:1rem;">Histori Pelaksanaan Layanan Konseling Siswa</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Guru BK Pembimbing</th>
                        <th>Jenis Layanan</th>
                        <th>Hasil Konseling</th>
                        <th>Status Sesi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sesiList as $idx => $s)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td><small style="font-weight:700; color:var(--primary-dark);">{{ \Carbon\Carbon::parse($s->tanggal_pelaksanaan)->format('d/m/Y') }}</small></td>
                        <td><strong>{{ $s->pengajuan->siswa->nama_siswa ?? '-' }}</strong></td>
                        <td>{{ $s->pengajuan->siswa->kelas->nama_kelas ?? '-' }}</td>
                        <td>{{ $s->pengajuan && $s->pengajuan->jadwal && $s->pengajuan->jadwal->guruBk ? $s->pengajuan->jadwal->guruBk->nama_lengkap : 'Guru BK' }}</td>
                        <td><span class="badge badge-info">{{ ucfirst($s->pengajuan->jenis_konseling ?? 'Individu') }}</span></td>
                        <td style="max-width:220px; font-size:0.825rem;">{{ $s->hasil_konseling ?? '-' }}</td>
                        <td>
                            @if($s->status_sesi == 'selesai')
                                <span class="badge badge-success">Selesai</span>
                            @elseif($s->status_sesi == 'terjadwal')
                                <span class="badge badge-warning">Terjadwal</span>
                            @else
                                <span class="badge badge-danger">{{ strtoupper($s->status_sesi) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" style="text-align:center; padding:2rem; color:var(--text-muted);">Tidak ada data laporan konseling sesuai filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

@endsection
