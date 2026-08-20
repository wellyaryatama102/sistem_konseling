@extends('layouts.app')
@section('title', 'Laporan & Rekapitulasi Sistem')

@section('content')

{{-- Jenis Laporan & Filter Periode Laporan --}}
<div class="card" style="margin-bottom:1.5rem; padding:1.25rem;">
    <form action="{{ route('admin.laporan.index') }}" method="GET" style="margin:0;">
        <div style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end;">
            {{-- Pilihan 2 Jenis Laporan Utama --}}
            <div style="min-width:280px; flex:1;">
                <label style="display:block; font-size:0.78125rem; font-weight:700; color:var(--text-dark); margin-bottom:0.25rem; white-space:nowrap;">Pilih Jenis Laporan:</label>
                <select name="tipe_rekap" class="form-control" onchange="this.form.submit()" style="height:38px; width:100%;">
                    <option value="layanan_konseling" {{ ($tipeRekap ?? 'layanan_konseling') == 'layanan_konseling' ? 'selected' : '' }}>Laporan Pelayanan Konseling</option>
                    <option value="siswa_kelas" {{ ($tipeRekap ?? '') == 'siswa_kelas' ? 'selected' : '' }}>Laporan Data Kelas &amp; Siswa </option>
                </select>
            </div>

            {{-- Filter Tahun Ajaran --}}
            <div style="min-width:180px;">
                <label style="display:block; font-size:0.78125rem; font-weight:700; color:var(--text-dark); margin-bottom:0.25rem; white-space:nowrap;">Tahun Ajaran:</label>
                <select name="id_tahun_ajaran" class="form-control" onchange="this.form.submit()" style="height:38px; width:100%;">
                    <option value="">Semua Tahun Ajaran </option>
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id_tahun_ajaran }}" {{ request('id_tahun_ajaran') == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                            {{ $ta->nama_tahun_ajaran }} {{ $ta->status_aktif ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Kelas --}}
            <div style="min-width:160px;">
                <label style="display:block; font-size:0.78125rem; font-weight:700; color:var(--text-dark); margin-bottom:0.25rem; white-space:nowrap;">Kelas:</label>
                <select name="id_kelas" class="form-control" onchange="this.form.submit()" style="height:38px; width:100%;">
                    <option value="">Semua Kelas</option>
                    @foreach($kelases as $kls)
                        <option value="{{ $kls->id_kelas }}" {{ request('id_kelas') == $kls->id_kelas ? 'selected' : '' }}>
                            {{ $kls->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Semester (Hanya untuk Laporan Data Kelas & Siswa) --}}
            @if(($tipeRekap ?? '') == 'siswa_kelas')
            <div style="width:140px;">
                <label style="display:block; font-size:0.78125rem; font-weight:700; color:var(--text-dark); margin-bottom:0.25rem; white-space:nowrap;">Semester:</label>
                <select name="semester" class="form-control" onchange="this.form.submit()" style="height:38px; width:100%;">
                    <option value="">Semua Semester</option>
                    <option value="ganjil" {{ request('semester') == 'ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                    <option value="genap" {{ request('semester') == 'genap' ? 'selected' : '' }}>Semester Genap</option>
                </select>
            </div>
            @endif

            {{-- Filter Bulan (Hanya untuk Laporan Pelayanan Konseling) --}}
            @if(($tipeRekap ?? 'layanan_konseling') == 'layanan_konseling')
            <div style="width:130px;">
                <label style="display:block; font-size:0.78125rem; font-weight:700; color:var(--text-dark); margin-bottom:0.25rem; white-space:nowrap;">Bulan:</label>
                <select name="bulan" class="form-control" onchange="this.form.submit()" style="height:38px; width:100%;">
                    <option value="">Semua Bulan</option>
                    @php
                        $namaBulan = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    @foreach($namaBulan as $num => $bName)
                        <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $bName }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Tombol Reset Filter --}}
            @if(request()->hasAny(['id_tahun_ajaran', 'id_kelas', 'semester', 'bulan']))
            <div style="flex-shrink:0;">
                <a href="{{ route('admin.laporan.index', ['tipe_rekap' => $tipeRekap]) }}" class="btn btn-secondary" style="height:38px; display:inline-flex; align-items:center; justify-content:center; white-space:nowrap; padding:0 0.85rem;">Reset Filter</a>
            </div>
            @endif
        </div>
    </form>
</div>

{{-- LEMBAR DOKUMEN LAPORAN --}}
<div class="card" style="background:#FFFFFF; border:1px solid #CBD5E1; padding:2rem; margin-bottom:2rem; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
    
    {{-- Judul Laporan & Tombol Aksi Ekspor --}}
    <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #E2E8F0; padding-bottom:1rem; margin-bottom:1.5rem; flex-wrap:wrap; gap:0.75rem;">
        <div>
            <h3 style="margin:0; font-size:1.25rem; font-weight:800; color:var(--primary-dark);">
                @if(($tipeRekap ?? 'layanan_konseling') == 'siswa_kelas')
                    Laporan Data Rombongan Belajar &amp; Siswa Per Kelas
                @else
                    Laporan Rekapitulasi Pelayanan Bimbingan &amp; Konseling Lengkap
                @endif
            </h3>
        </div>

        {{-- Tombol Unduh PDF & Ekspor Excel di Lembar Laporan --}}
        <div style="display:flex; gap:0.5rem; align-items:center;">
            <a href="{{ route('admin.laporan.pdf', request()->all()) }}" class="btn btn-primary btn-sm" target="_blank" style="display:inline-flex; align-items:center; justify-content:center; white-space:nowrap; padding:0.45rem 0.9rem;">
                Cetak PDF
            </a>
            <a href="{{ route('admin.laporan.excel', request()->all()) }}" class="btn btn-success btn-sm" style="display:inline-flex; align-items:center; justify-content:center; white-space:nowrap; padding:0.45rem 0.9rem;">
                Ekspor Excel
            </a>
        </div>
    </div>

    {{-- 1. TABEL LAPORAN PELAYANAN KONSELING--}}
    @if(($tipeRekap ?? 'layanan_konseling') == 'layanan_konseling')
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width:35px; text-align:center;">No</th>
                        <th style="width:90px;">Tanggal</th>
                        <th>Nama Siswa &amp; Kelas</th>
                        <th>Guru BK Pembimbing</th>
                        <th>Jenis Layanan &amp; Sumber</th>
                        <th>Permasalahan / Kasus</th>
                        <th style="text-align:center; width:90px;">Validasi</th>
                        <th style="text-align:center; width:95px;">Sesi</th>
                        <th style="text-align:center; width:90px;">Tindak Lanjut</th>
                        <th style="text-align:center; width:70px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($konselings as $index => $c)
                    @php
                        $waliKelasNama = $c->siswa->kelas->waliKelas->nama_lengkap ?? ($c->waliKelas->nama_lengkap ?? '-');
                        $tindakLanjutObj = $c->sesiKonseling ? $c->sesiKonseling->tindakLanjuts->first() : null;
                        $jenisAksi = $tindakLanjutObj ? ucfirst(str_replace('_', ' ', $tindakLanjutObj->jenis_aksi)) : '-';
                        $suratObj = $tindakLanjutObj ? $tindakLanjutObj->suratPanggilan : null;
                        $noSurat = $suratObj ? $suratObj->nomor_surat : '-';
                        $statusWaSurat = $suratObj ? strtoupper($suratObj->status_kirim_wa) : '-';
                    @endphp
                    <tr>
                        <td style="text-align:center; color:var(--text-muted);">{{ $index + 1 }}</td>
                        <td>{{ $c->tanggal_pengajuan ? $c->tanggal_pengajuan->format('d/m/Y') : '-' }}</td>
                        <td>
                            <strong>{{ $c->siswa->nama_siswa ?? 'Data Siswa Dihapus' }}</strong><br>
                            <small style="color:var(--text-muted);">{{ $c->siswa->kelas->nama_kelas ?? '-' }} ({{ $c->siswa->nis ?? '-' }})</small>
                        </td>
                        <td>{{ $c->jadwal->guruBk->nama_lengkap ?? '-' }}</td>
                        <td>
                            <span class="badge badge-info">{{ $c->jenis_konseling }}</span><br>
                            <small style="color:var(--text-muted);">Sumber: {{ ucfirst(str_replace('_', ' ', $c->sumber_pengajuan)) }}</small>
                        </td>
                        <td>
                            <div style="max-width:230px; font-size:0.8125rem; line-height:1.35;">
                                {{ \Illuminate\Support\Str::limit($c->alasan_pengajuan, 60) }}
                            </div>
                        </td>
                        <td style="text-align:center;">
                            @if($c->status_pengajuan == 'disetujui')
                                <span class="badge badge-success">Disetujui</span>
                            @elseif($c->status_pengajuan == 'menunggu_validasi')
                                <span class="badge badge-warning">Menunggu</span>
                            @else
                                <span class="badge badge-danger">{{ ucfirst($c->status_pengajuan) }}</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($c->sesiKonseling)
                                <span class="badge badge-{{ $c->sesiKonseling->status_sesi == 'selesai' ? 'success' : 'info' }}">
                                    {{ ucfirst($c->sesiKonseling->status_sesi) }}
                                </span>
                            @else
                                <span style="color:var(--text-muted); font-size:0.75rem;">Belum Terjadwal</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($tindakLanjutObj)
                                <span class="badge badge-warning">{{ $jenisAksi }}</span>
                            @else
                                <span style="color:var(--text-muted); font-size:0.75rem;">-</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <button type="button" class="btn btn-secondary btn-sm"
                                    onclick="showKonselingDetail(
                                        '{{ addslashes($c->siswa->nama_siswa ?? '-') }}',
                                        '{{ $c->siswa->nis ?? '-' }}',
                                        '{{ addslashes($c->siswa->kelas->nama_kelas ?? '-') }}',
                                        '{{ addslashes($c->siswa->kelas->jurusan->nama_jurusan ?? '-') }}',
                                        '{{ addslashes($waliKelasNama) }}',
                                        '{{ $c->siswa->no_wa_orang_tua_wali ?? '-' }}',
                                        '{{ $c->tanggal_pengajuan ? $c->tanggal_pengajuan->format('d F Y H:i') : '-' }}',
                                        '{{ ucfirst($c->jenis_konseling) }}',
                                        '{{ ucfirst(str_replace('_', ' ', $c->sumber_pengajuan)) }}',
                                        '{{ addslashes($c->jadwal->guruBk->nama_lengkap ?? '-') }}',
                                        '{{ ucfirst($c->status_pengajuan) }}',
                                        '{{ addslashes($c->alasan_pengajuan) }}',
                                        '{{ addslashes($c->alasan_rujukan ?: '-') }}',
                                        '{{ $c->sesiKonseling ? strtoupper($c->sesiKonseling->status_sesi) : 'BELUM TERJADWAL' }}',
                                        '{{ $c->sesiKonseling && $c->sesiKonseling->tanggal_pelaksanaan ? date('d F Y', strtotime($c->sesiKonseling->tanggal_pelaksanaan)) : '-' }}',
                                        '{{ addslashes($c->sesiKonseling->hasil_konseling ?: '-') }}',
                                        '{{ addslashes($jenisAksi) }}',
                                        '{{ addslashes($noSurat) }}',
                                        '{{ addslashes($statusWaSurat) }}'
                                    )">
                                Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="text-align:center; padding:2.5rem; color:var(--text-muted);">Tidak ada rekaman data pelayanan konseling yang sesuai dengan filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    {{-- 2. TABEL LAPORAN DATA KELAS & SISWA--}}
    @elseif(($tipeRekap ?? '') == 'siswa_kelas')
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px; text-align:center;">No</th>
                        <th>Nama Kelas</th>
                        <th style="text-align:center; width:80px;">Tingkat</th>
                        <th>Program Keahlian</th>
                        <th>Tahun Ajaran</th>
                        <th>Wali Kelas</th>
                        <th style="text-align:center; width:65px;">L</th>
                        <th style="text-align:center; width:65px;">P</th>
                        <th style="text-align:center; width:100px;">Total Siswa</th>
                        <th style="text-align:center; width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelasSummary as $index => $k)
                    <tr>
                        <td style="text-align:center; color:var(--text-muted);">{{ $index + 1 }}</td>
                        <td><strong>{{ $k->nama_kelas }}</strong></td>
                        <td style="text-align:center;"><span class="badge badge-info">{{ $k->tingkat_kelas }}</span></td>
                        <td>{{ $k->jurusan->nama_jurusan ?? '-' }}</td>
                        <td>{{ $k->tahunAjaran->nama_tahun_ajaran ?? '-' }}</td>
                        <td>{{ $k->waliKelas->nama_lengkap ?? 'Belum Ditentukan' }}</td>
                        <td style="text-align:center;">{{ $k->siswas_laki_count }}</td>
                        <td style="text-align:center;">{{ $k->siswas_perempuan_count }}</td>
                        <td style="text-align:center; font-weight:700;">{{ $k->siswas_count }} Siswa</td>
                        <td style="text-align:center;">
                            <button type="button" class="btn btn-secondary btn-sm"
                                    onclick="showKelasDetail('{{ $k->id_kelas }}', '{{ addslashes($k->nama_kelas) }}', '{{ $k->tingkat_kelas }}', '{{ addslashes($k->jurusan->nama_jurusan ?? '-') }}', '{{ addslashes($k->waliKelas->nama_lengkap ?? '-') }}', '{{ addslashes($k->tahunAjaran->nama_tahun_ajaran ?? '-') }}', '{{ $k->siswas_laki_count }}', '{{ $k->siswas_perempuan_count }}', '{{ $k->siswas_count }}')">
                                Detail
                            </button>
                            <template id="kelas-siswa-data-{{ $k->id_kelas }}">{!! json_encode($k->siswas->map(function($s, $idx) {
                                $tglLahir = $s->tanggal_lahir ? date('d/m/Y', strtotime($s->tanggal_lahir)) : '-';
                                $ttl = ($s->tempat_lahir ? $s->tempat_lahir . ', ' : '') . $tglLahir;
                                return [
                                    'no' => $idx + 1,
                                    'nis' => $s->nis ?: '-',
                                    'nisn' => $s->nisn ?: '-',
                                    'nama' => $s->nama_siswa,
                                    'gender' => $s->jenis_kelamin ?: '-',
                                    'ttl' => $ttl,
                                    'ortu' => $s->nama_orang_tua_wali ?: '-',
                                    'waOrtu' => $s->no_wa_orang_tua_wali ?: '-',
                                    'alamat' => $s->alamat ?: '-',
                                    'status' => strtoupper($s->status_siswa ?: 'AKTIF')
                                ];
                            })) !!}</template>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="text-align:center; padding:2.5rem; color:var(--text-muted);">Tidak ada data rombongan belajar yang sesuai dengan filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- MODAL DETAIL UNIVERSAL UNTUK PREVIEW LAPORAN --}}
<div id="reportDetailModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.6); z-index:999; align-items:center; justify-content:center; padding:1rem;">
    <div style="background:#FFFFFF; border-radius:0.5rem; max-width:960px; width:100%; padding:1.75rem; box-shadow:0 20px 25px -5px rgba(0,0,0,0.15); max-height:90vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #E2E8F0; padding-bottom:0.85rem; margin-bottom:1.25rem;">
            <h4 id="modalDetailTitle" style="margin:0; font-size:1.15rem; font-weight:800; color:var(--primary-dark);">Rincian Data Laporan</h4>
            <button type="button" onclick="closeReportDetailModal()" style="background:none; border:none; font-size:1.5rem; cursor:pointer; color:#64748B; line-height:1;">&times;</button>
        </div>
        
        <div id="modalDetailContent" style="font-size:0.875rem; line-height:1.6; color:#1E293B;">
            {{-- Konten Diinjeksi via Javascript --}}
        </div>

        <div style="margin-top:1.5rem; text-align:right; border-top:1px solid #E2E8F0; padding-top:0.85rem;">
            <button type="button" onclick="closeReportDetailModal()" class="btn btn-secondary">Tutup</button>
        </div>
    </div>
</div>

<script>
    function closeReportDetailModal() {
        document.getElementById('reportDetailModal').style.display = 'none';
    }

    function showKelasDetail(idKelas, nama, tingkat, jurusan, walas, thnAjaran, laki, perempuan, total) {
        let siswaList = [];
        const templateEl = document.getElementById('kelas-siswa-data-' + idKelas);
        if (templateEl && templateEl.innerHTML) {
            try {
                siswaList = JSON.parse(templateEl.innerHTML.trim());
            } catch(e) {
                console.error("Gagal parse data siswa:", e);
                siswaList = [];
            }
        }

        let rowsHtml = '';
        if (siswaList && siswaList.length > 0) {
            siswaList.forEach(s => {
                rowsHtml += `
                    <tr style="border-bottom:1px solid #F1F5F9;">
                        <td style="text-align:center; padding:0.6rem 0.5rem; color:#64748B;">${s.no}</td>
                        <td style="padding:0.6rem 0.5rem;">
                            <div style="font-weight:600; color:#0F172A;">${s.nis}</div>
                            <small style="color:#64748B;">NISN: ${s.nisn}</small>
                        </td>
                        <td style="padding:0.6rem 0.5rem;"><strong style="color:var(--primary-dark);">${s.nama}</strong></td>
                        <td style="text-align:center; padding:0.6rem 0.5rem; font-weight:700;">${s.gender}</td>
                        <td style="padding:0.6rem 0.5rem;"><small style="color:#334155;">${s.ttl}</small></td>
                        <td style="padding:0.6rem 0.5rem;">
                            <div style="font-weight:500;">${s.ortu}</div>
                            <small style="color:#0284C7;">WA: ${s.waOrtu}</small>
                        </td>
                        <td style="padding:0.6rem 0.5rem;"><small style="color:#475569;">${s.alamat}</small></td>
                        <td style="text-align:center; padding:0.6rem 0.5rem;">
                            <span class="badge ${s.status === 'AKTIF' ? 'badge-success' : (s.status === 'LULUS' ? 'badge-info' : 'badge-danger')}">${s.status}</span>
                        </td>
                    </tr>
                `;
            });
        } else {
            rowsHtml = `<tr><td colspan="8" style="text-align:center; padding:2rem; color:#64748B;">Belum ada data siswa yang terdaftar di kelas ini.</td></tr>`;
        }

        document.getElementById('modalDetailTitle').innerText = 'Data Lengkap Rombongan Belajar: ' + nama;
        document.getElementById('modalDetailContent').innerHTML = `
            <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:0.375rem; padding:1rem; margin-bottom:1.25rem;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:0.75rem;">
                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:0.6rem; font-size:0.875rem; flex:1;">
                        <div><strong>Kelas / Rombel:</strong> ${nama} (${tingkat})</div>
                        <div><strong>Program Keahlian:</strong> ${jurusan}</div>
                        <div><strong>Tahun Ajaran:</strong> ${thnAjaran}</div>
                        <div><strong>Wali Kelas:</strong> ${walas}</div>
                        <div style="grid-column:1/-1; padding-top:0.25rem; border-top:1px dashed #CBD5E1;">
                            <strong>Total Siswa Terdaftar:</strong> ${total} Siswa (${laki} Laki-laki · ${perempuan} Perempuan)
                        </div>
                    </div>

                    <div style="display:flex; gap:0.4rem; align-items:center; flex-shrink:0;">
                        <a href="{{ route('admin.laporan.pdf') }}?tipe_rekap=siswa_kelas&id_kelas=${idKelas}" target="_blank" class="btn btn-primary btn-sm" style="display:inline-flex; align-items:center; gap:0.3rem; padding:0.35rem 0.75rem; font-size:0.8125rem;">
                            Cetak PDF Kelas
                        </a>
                        <a href="{{ route('admin.laporan.excel') }}?tipe_rekap=siswa_kelas&id_kelas=${idKelas}" class="btn btn-success btn-sm" style="display:inline-flex; align-items:center; gap:0.3rem; padding:0.35rem 0.75rem; font-size:0.8125rem;">
                            Ekspor Excel Kelas
                        </a>
                    </div>
                </div>
            </div>

            <h5 style="margin:0 0 0.6rem 0; font-size:0.95rem; font-weight:700; color:var(--primary-dark);">Daftar Seluruh Siswa Kelas ${nama} Berdasarkan Database (${siswaList.length} Siswa):</h5>
            <div style="max-height:380px; overflow-y:auto; border:1px solid #E2E8F0; border-radius:0.375rem;">
                <table style="width:100%; border-collapse:collapse; font-size:0.8125rem;">
                    <thead style="position:sticky; top:0; background:#F1F5F9; border-bottom:1px solid #CBD5E1; z-index:1;">
                        <tr>
                            <th style="width:35px; text-align:center; padding:0.6rem 0.5rem;">No</th>
                            <th style="width:95px; padding:0.6rem 0.5rem;">NIS / NISN</th>
                            <th style="padding:0.6rem 0.5rem;">Nama Lengkap Siswa</th>
                            <th style="width:40px; text-align:center; padding:0.6rem 0.5rem;">L/P</th>
                            <th style="width:120px; padding:0.6rem 0.5rem;">Tempat, Tgl Lahir</th>
                            <th style="padding:0.6rem 0.5rem;">Orang Tua / No WA</th>
                            <th style="padding:0.6rem 0.5rem;">Alamat</th>
                            <th style="width:80px; text-align:center; padding:0.6rem 0.5rem;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${rowsHtml}
                    </tbody>
                </table>
            </div>
        `;
        document.getElementById('reportDetailModal').style.display = 'flex';
    }

    function showKonselingDetail(siswa, nis, kelas, jurusan, walas, waOrtu, tglPengajuan, jenis, sumber, gurubk, statusValidasi, alasan, rujukan, statusSesi, tglSesi, hasil, jenisAksi, noSurat, statusWaSurat) {
        document.getElementById('modalDetailTitle').innerText = 'Rincian Lengkap Pelayanan Konseling';
        document.getElementById('modalDetailContent').innerHTML = `
            <div style="margin-bottom:1rem; border-bottom:1px solid #E2E8F0; padding-bottom:0.75rem;">
                <h6 style="margin:0 0 0.4rem 0; font-size:0.85rem; font-weight:700; color:var(--primary-dark); text-transform:uppercase;">1. Data Siswa &amp; Rombel</h6>
                <table style="width:100%; font-size:0.84rem;">
                    <tr><td style="width:160px; font-weight:600; padding:0.2rem 0;">Nama Lengkap Siswa</td><td>: <strong>${siswa}</strong> (NIS: ${nis})</td></tr>
                    <tr><td style="font-weight:600; padding:0.2rem 0;">Kelas &amp; Jurusan</td><td>: ${kelas} (${jurusan})</td></tr>
                    <tr><td style="font-weight:600; padding:0.2rem 0;">Wali Kelas / No WA Ortu</td><td>: ${walas} (WA: ${waOrtu})</td></tr>
                </table>
            </div>

            <div style="margin-bottom:1rem; border-bottom:1px solid #E2E8F0; padding-bottom:0.75rem;">
                <h6 style="margin:0 0 0.4rem 0; font-size:0.85rem; font-weight:700; color:var(--primary-dark); text-transform:uppercase;">2. Permohonan &amp; Guru BK Pembimbing</h6>
                <table style="width:100%; font-size:0.84rem;">
                    <tr><td style="width:160px; font-weight:600; padding:0.2rem 0;">Tanggal Pengajuan</td><td>: ${tglPengajuan}</td></tr>
                    <tr><td style="font-weight:600; padding:0.2rem 0;">Jenis Layanan &amp; Sumber</td><td>: <span class="badge badge-info">${jenis}</span> (Sumber: ${sumber})</td></tr>
                    <tr><td style="font-weight:600; padding:0.2rem 0;">Guru BK Pembimbing</td><td>: <strong>${gurubk}</strong></td></tr>
                    <tr><td style="font-weight:600; padding:0.2rem 0;">Status Validasi</td><td>: <span class="badge ${statusValidasi.toLowerCase() === 'disetujui' ? 'badge-success' : 'badge-warning'}">${statusValidasi}</span></td></tr>
                </table>
            </div>

            <div style="margin-bottom:1rem; border-bottom:1px solid #E2E8F0; padding-bottom:0.75rem;">
                <h6 style="margin:0 0 0.4rem 0; font-size:0.85rem; font-weight:700; color:var(--primary-dark); text-transform:uppercase;">3. Uraian Kasus &amp; Rujukan</h6>
                <table style="width:100%; font-size:0.84rem;">
                    <tr><td style="width:160px; font-weight:600; padding:0.2rem 0; vertical-align:top;">Alasan / Masalah</td><td style="vertical-align:top;">: ${alasan}</td></tr>
                    <tr><td style="font-weight:600; padding:0.2rem 0; vertical-align:top;">Catatan Rujukan</td><td style="vertical-align:top;">: ${rujukan}</td></tr>
                </table>
            </div>

            <div style="margin-bottom:1rem; border-bottom:1px solid #E2E8F0; padding-bottom:0.75rem;">
                <h6 style="margin:0 0 0.4rem 0; font-size:0.85rem; font-weight:700; color:var(--primary-dark); text-transform:uppercase;">4. Pelaksanaan Sesi Konseling</h6>
                <table style="width:100%; font-size:0.84rem;">
                    <tr><td style="width:160px; font-weight:600; padding:0.2rem 0;">Status Sesi</td><td>: <span class="badge ${statusSesi.toLowerCase() === 'selesai' ? 'badge-success' : 'badge-info'}">${statusSesi}</span></td></tr>
                    <tr><td style="font-weight:600; padding:0.2rem 0;">Tanggal Pelaksanaan</td><td>: ${tglSesi}</td></tr>
                    <tr><td style="font-weight:600; padding:0.2rem 0; vertical-align:top;">Hasil &amp; Evaluasi</td><td style="vertical-align:top;">: ${hasil}</td></tr>
                </table>
            </div>

            <div>
                <h6 style="margin:0 0 0.4rem 0; font-size:0.85rem; font-weight:700; color:var(--primary-dark); text-transform:uppercase;">5. Tindak Lanjut &amp; Panggilan Orang Tua</h6>
                <table style="width:100%; font-size:0.84rem;">
                    <tr><td style="width:160px; font-weight:600; padding:0.2rem 0;">Rencana Tindak Lanjut</td><td>: ${jenisAksi}</td></tr>
                    <tr><td style="font-weight:600; padding:0.2rem 0;">Nomor Surat Panggilan</td><td>: <code>${noSurat}</code></td></tr>
                    <tr><td style="font-weight:600; padding:0.2rem 0;">Status Transmisi WA</td><td>: <span class="badge ${statusWaSurat === 'TERKIRIM' ? 'badge-success' : 'badge-secondary'}">${statusWaSurat}</span></td></tr>
                </table>
            </div>
        `;
        document.getElementById('reportDetailModal').style.display = 'flex';
    }
</script>

@endsection
