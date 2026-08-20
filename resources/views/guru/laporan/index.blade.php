@extends('layouts.app')
@section('title', 'Laporan & Rekapitulasi Pelayanan BK')

@section('content')

{{-- Pilihan Jenis Laporan & Filter Periode Laporan --}}
<div class="card" style="margin-bottom:1.5rem; padding:1.25rem;">
    <form action="{{ route('guru.laporan.index') }}" method="GET" style="margin:0;">
        <div style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end;">
            {{-- Pilihan 2 Jenis Laporan Utama Guru BK --}}
            <div style="min-width:300px; flex:1;">
                <label style="display:block; font-size:0.78125rem; font-weight:700; color:var(--text-dark); margin-bottom:0.25rem; white-space:nowrap;">Pilih Jenis Laporan:</label>
                <select name="tipe_rekap" class="form-control" onchange="this.form.submit()" style="height:38px; width:100%;">
                    <option value="layanan_konseling" {{ ($tipeRekap ?? 'layanan_konseling') == 'layanan_konseling' ? 'selected' : '' }}>Laporan Pelayanan Konseling </option>
                    <option value="surat_panggilan" {{ ($tipeRekap ?? '') == 'surat_panggilan' ? 'selected' : '' }}>Laporan Surat Panggilan Orang Tua </option>
                </select>
            </div>

            {{-- Filter Tahun Ajaran (Arsip Historis) --}}
            <div style="min-width:180px;">
                <label style="display:block; font-size:0.78125rem; font-weight:700; color:var(--text-dark); margin-bottom:0.25rem; white-space:nowrap;">Tahun Ajaran:</label>
                <select name="id_tahun_ajaran" class="form-control" onchange="this.form.submit()" style="height:38px; width:100%;">
                    <option value="">Semua Tahun Ajaran (Arsip)</option>
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id_tahun_ajaran }}" {{ request('id_tahun_ajaran') == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                            {{ $ta->nama_tahun_ajaran }} {{ $ta->status_aktif ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Kelas --}}
            <div style="min-width:160px;">
                <label style="display:block; font-size:0.78125rem; font-weight:700; color:var(--text-dark); margin-bottom:0.25rem; white-space:nowrap;">Kelas :</label>
                <select name="id_kelas" class="form-control" onchange="this.form.submit()" style="height:38px; width:100%;">
                    <option value="">Semua Kelas</option>
                    @foreach($kelases as $kls)
                        <option value="{{ $kls->id_kelas }}" {{ request('id_kelas') == $kls->id_kelas ? 'selected' : '' }}>
                            {{ $kls->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Bulan --}}
            <div style="width:140px;">
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

            {{-- Tombol Reset Filter --}}
            @if(request()->hasAny(['id_tahun_ajaran', 'id_kelas', 'bulan']))
            <div style="flex-shrink:0;">
                <a href="{{ route('guru.laporan.index', ['tipe_rekap' => $tipeRekap]) }}" class="btn btn-secondary" style="height:38px; display:inline-flex; align-items:center; justify-content:center; white-space:nowrap; padding:0 0.85rem;">Reset Filter</a>
            </div>
            @endif
        </div>
    </form>
</div>

{{-- LEMBAR DOKUMEN LAPORAN RESMI BK --}}
<div class="card" style="background:#FFFFFF; border:1px solid #CBD5E1; padding:2rem; margin-bottom:2rem; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
    
    {{-- Judul Laporan & Tombol Aksi Ekspor --}}
    <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #E2E8F0; padding-bottom:1rem; margin-bottom:1.5rem; flex-wrap:wrap; gap:0.75rem;">
        <div>
            <h3 style="margin:0; font-size:1.25rem; font-weight:800; color:var(--primary-dark);">
                @if(($tipeRekap ?? 'layanan_konseling') == 'surat_panggilan')
                    Laporan Rekapitulasi Surat Panggilan Orang Tua
                @else
                    Laporan Rekapitulasi Pelayanan Bimbingan &amp; Konseling Lengkap
                @endif
            </h3>
            <small style="color:var(--text-muted);">
                Guru BK: <strong>{{ $guru->nama_lengkap ?? auth()->user()->name }}</strong>
            </small>
        </div>

        {{-- Tombol Unduh PDF & Ekspor Excel di Lembar Laporan --}}
        <div style="display:flex; gap:0.5rem; align-items:center;">
            <a href="{{ route('guru.laporan.pdf', request()->all()) }}" class="btn btn-primary btn-sm" target="_blank" style="display:inline-flex; align-items:center; justify-content:center; white-space:nowrap; padding:0.45rem 0.9rem;">
                Cetak PDF
            </a>
            <a href="{{ route('guru.laporan.excel', request()->all()) }}" class="btn btn-success btn-sm" style="display:inline-flex; align-items:center; justify-content:center; white-space:nowrap; padding:0.45rem 0.9rem;">
                Ekspor Excel
            </a>
        </div>
    </div>

    {{-- 1. TABEL LAPORAN PELAYANAN KONSELING --}}
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

                        $tglLahirSiswa = $c->siswa && $c->siswa->tanggal_lahir ? date('d/m/Y', strtotime($c->siswa->tanggal_lahir)) : '-';
                        $ttlSiswa = $c->siswa ? (($c->siswa->tempat_lahir ? $c->siswa->tempat_lahir . ', ' : '') . $tglLahirSiswa) : '-';

                        $detailData = [
                            'id' => $c->id_pengajuan,
                            'nama_siswa' => $c->siswa->nama_siswa ?? '-',
                            'nis' => $c->siswa->nis ?? '-',
                            'nisn' => $c->siswa->nisn ?? '-',
                            'kelas' => $c->siswa->kelas->nama_kelas ?? '-',
                            'tingkat' => $c->siswa->kelas->tingkat_kelas ?? '-',
                            'jurusan' => $c->siswa->kelas->jurusan->nama_jurusan ?? '-',
                            'jenis_kelamin' => $c->siswa->jenis_kelamin ?? '-',
                            'ttl' => $ttlSiswa,
                            'wali_kelas' => $waliKelasNama,
                            'nama_ortu' => $c->siswa->nama_orang_tua_wali ?: '-',
                            'wa_ortu' => $c->siswa->no_wa_orang_tua_wali ?: '-',
                            'alamat' => $c->siswa->alamat ?: '-',
                            'status_siswa' => strtoupper($c->siswa->status_siswa ?? 'AKTIF'),
                            
                            'tanggal_pengajuan' => $c->tanggal_pengajuan ? $c->tanggal_pengajuan->format('d F Y H:i') : '-',
                            'jenis_konseling' => ucfirst($c->jenis_konseling),
                            'sumber_pengajuan' => ucfirst(str_replace('_', ' ', $c->sumber_pengajuan)),
                            'guru_bk' => $c->jadwal->guruBk->nama_lengkap ?? '-',
                            'status_validasi' => ucfirst($c->status_pengajuan),
                            'catatan_validasi' => $c->catatan_validasi ?: '-',
                            
                            'alasan_pengajuan' => $c->alasan_pengajuan ?: '-',
                            'alasan_rujukan' => $c->alasan_rujukan ?: '-',

                            'has_sesi' => $c->sesiKonseling ? true : false,
                            'status_sesi' => $c->sesiKonseling ? strtoupper($c->sesiKonseling->status_sesi) : 'BELUM TERJADWAL',
                            'tanggal_pelaksanaan' => $c->sesiKonseling && $c->sesiKonseling->tanggal_pelaksanaan ? date('d F Y', strtotime($c->sesiKonseling->tanggal_pelaksanaan)) : '-',
                            'status_kehadiran' => $c->sesiKonseling ? ucfirst($c->sesiKonseling->status_kehadiran ?? '-') : '-',
                            'catatan_konseling' => $c->sesiKonseling ? ($c->sesiKonseling->catatan_konseling ?: '-') : '-',
                            'hasil_konseling' => $c->sesiKonseling ? ($c->sesiKonseling->hasil_konseling ?: '-') : '-',
                            'rencana_tindak_lanjut' => $c->sesiKonseling ? ($c->sesiKonseling->rencana_tindak_lanjut ?: '-') : '-',

                            'has_tindak_lanjut' => $tindakLanjutObj ? true : false,
                            'jenis_aksi' => $jenisAksi,
                            'status_tindak_lanjut' => $tindakLanjutObj ? strtoupper($tindakLanjutObj->status_tindak_lanjut) : '-',
                            'catatan_tindak_lanjut' => $tindakLanjutObj ? ($tindakLanjutObj->catatan ?: '-') : '-',

                            'has_surat' => $suratObj ? true : false,
                            'id_surat' => $suratObj ? $suratObj->id_surat : null,
                            'nomor_surat' => $noSurat,
                            'perihal_surat' => $suratObj ? $suratObj->perihal : '-',
                            'tanggal_pertemuan' => $suratObj && $suratObj->tanggal_pertemuan ? date('d F Y', strtotime($suratObj->tanggal_pertemuan)) : '-',
                            'waktu_pertemuan' => $suratObj ? ($suratObj->waktu_pertemuan ?: '-') : '-',
                            'tempat_pertemuan' => $suratObj ? ($suratObj->tempat ?: '-') : '-',
                            'status_kirim_wa' => $statusWaSurat
                        ];
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
                                    onclick="showKonselingDetail('{{ $c->id_pengajuan }}')">
                                Detail
                            </button>
                            <template id="konseling-detail-{{ $c->id_pengajuan }}">{!! json_encode($detailData) !!}</template>
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

    {{-- 2. TABEL LAPORAN SURAT PANGGILAN ORANG TUA LENGKAP --}}
    @elseif(($tipeRekap ?? '') == 'surat_panggilan')
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width:35px; text-align:center;">No</th>
                        <th style="width:130px;">Nomor Surat</th>
                        <th style="width:90px;">Tgl Terbit</th>
                        <th>Nama Siswa &amp; Kelas</th>
                        <th>Orang Tua / Kontak WA</th>
                        <th>Perihal Pemanggilan</th>
                        <th>Jadwal Pertemuan</th>
                        <th style="text-align:center; width:95px;">Status WA</th>
                        <th style="text-align:center; width:80px;">Status Surat</th>
                        <th style="text-align:center; width:110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suratList as $index => $s)
                    @php
                        $siswaObj = $s->tindakLanjut->sesiKonseling->pengajuan->siswa ?? null;
                        $guruBkObj = $s->tindakLanjut->sesiKonseling->pengajuan->jadwal->guruBk ?? ($s->guruBk ?? null);
                        
                        $suratDetailData = [
                            'id' => $s->id_surat,
                            'nomor_surat' => $s->nomor_surat,
                            'tanggal_terbit' => $s->tanggal_terbit ? date('d F Y', strtotime($s->tanggal_terbit)) : '-',
                            'perihal' => $s->perihal ?: 'Panggilan Orang Tua Siswa',
                            'isi_surat' => $s->isi_surat ?: '-',
                            'tanggal_pertemuan' => $s->tanggal_pertemuan ? date('d F Y', strtotime($s->tanggal_pertemuan)) : '-',
                            'waktu_pertemuan' => $s->waktu_pertemuan ?: '-',
                            'tempat' => $s->tempat ?: 'Ruang Bimbingan dan Konseling',
                            'status_surat' => strtoupper($s->status_surat),
                            'status_kirim_wa' => strtoupper($s->status_kirim_wa),
                            
                            'nama_siswa' => $siswaObj->nama_siswa ?? '-',
                            'nis' => $siswaObj->nis ?? '-',
                            'nisn' => $siswaObj->nisn ?? '-',
                            'kelas' => $siswaObj->kelas->nama_kelas ?? '-',
                            'jurusan' => $siswaObj->kelas->jurusan->nama_jurusan ?? '-',
                            'wali_kelas' => $siswaObj->kelas->waliKelas->nama_lengkap ?? '-',
                            'nama_ortu' => $siswaObj->nama_orang_tua_wali ?: '-',
                            'wa_ortu' => $siswaObj->no_wa_orang_tua_wali ?: '-',
                            'alamat' => $siswaObj->alamat ?: '-',
                            
                            'guru_bk' => $guruBkObj->nama_lengkap ?? '-'
                        ];
                    @endphp
                    <tr>
                        <td style="text-align:center; color:var(--text-muted);">{{ $index + 1 }}</td>
                        <td><strong>{{ $s->nomor_surat }}</strong></td>
                        <td>{{ $s->tanggal_terbit ? date('d/m/Y', strtotime($s->tanggal_terbit)) : '-' }}</td>
                        <td>
                            <strong>{{ $siswaObj->nama_siswa ?? '-' }}</strong><br>
                            <small style="color:var(--text-muted);">{{ $siswaObj->kelas->nama_kelas ?? '-' }} (NIS: {{ $siswaObj->nis ?? '-' }})</small>
                        </td>
                        <td>
                            <div>{{ $siswaObj->nama_orang_tua_wali ?: '-' }}</div>
                            <small style="color:#0284c7;">WA: {{ $siswaObj->no_wa_orang_tua_wali ?: '-' }}</small>
                        </td>
                        <td><div style="max-width:200px; font-size:0.8125rem;">{{ $s->perihal }}</div></td>
                        <td>
                            <strong>{{ $s->tanggal_pertemuan ? date('d/m/Y', strtotime($s->tanggal_pertemuan)) : '-' }}</strong><br>
                            <small style="color:var(--text-muted);">Pukul {{ $s->waktu_pertemuan ?: '-' }} WIB</small>
                        </td>
                        <td style="text-align:center;">
                            @if(strtolower($s->status_kirim_wa) == 'terkirim')
                                <span class="badge badge-success">Terkirim</span>
                            @elseif(strtolower($s->status_kirim_wa) == 'gagal')
                                <span class="badge badge-danger">Gagal</span>
                            @else
                                <span class="badge badge-secondary">{{ strtoupper($s->status_kirim_wa) }}</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <span class="badge badge-info">{{ strtoupper($s->status_surat) }}</span>
                        </td>
                        <td style="text-align:center;">
                            <div style="display:inline-flex; gap:0.25rem; align-items:center;">
                                <button type="button" class="btn btn-secondary btn-sm"
                                        onclick="showSuratDetail('{{ $s->id_surat }}')">
                                    Detail
                                </button>
                                <a href="{{ route('guru.surat.pdf', $s->id_surat) }}" target="_blank" class="btn btn-primary btn-sm" title="Cetak Surat Panggilan">
                                    PDF
                                </a>
                            </div>
                            <template id="surat-detail-{{ $s->id_surat }}">{!! json_encode($suratDetailData) !!}</template>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="text-align:center; padding:2.5rem; color:var(--text-muted);">Tidak ada data surat panggilan orang tua yang sesuai dengan filter.</td>
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

    function showKonselingDetail(idPengajuan) {
        const tmpl = document.getElementById('konseling-detail-' + idPengajuan);
        if (!tmpl) return;
        let d = {};
        try {
            d = JSON.parse(tmpl.innerHTML.trim());
        } catch(e) {
            console.error("Gagal membaca detail konseling:", e);
            return;
        }

        document.getElementById('modalDetailTitle').innerText = 'Data Lengkap Pelayanan Konseling: ' + d.nama_siswa;
        document.getElementById('modalDetailContent').innerHTML = `
            {{-- 1. IDENTITAS LENGKAP SISWA --}}
            <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:0.375rem; padding:0.85rem; margin-bottom:1rem;">
                <h6 style="margin:0 0 0.5rem 0; font-size:0.85rem; font-weight:800; color:var(--primary-dark); text-transform:uppercase;">1. Data Lengkap Siswa &amp; Rombongan Belajar</h6>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:0.4rem; font-size:0.8125rem;">
                    <div><strong>Nama Lengkap:</strong> ${d.nama_siswa}</div>
                    <div><strong>NIS / NISN:</strong> ${d.nis} / ${d.nisn}</div>
                    <div><strong>Kelas / Rombel:</strong> ${d.kelas} (${d.tingkat})</div>
                    <div><strong>Program Keahlian:</strong> ${d.jurusan}</div>
                    <div><strong>Jenis Kelamin:</strong> ${d.jenis_kelamin === 'L' ? 'Laki-laki' : (d.jenis_kelamin === 'P' ? 'Perempuan' : d.jenis_kelamin)}</div>
                    <div><strong>Tempat, Tgl Lahir:</strong> ${d.ttl}</div>
                    <div><strong>Wali Kelas:</strong> ${d.wali_kelas}</div>
                    <div><strong>Status Siswa:</strong> <span class="badge badge-success">${d.status_siswa}</span></div>
                    <div><strong>Orang Tua / Wali:</strong> ${d.nama_ortu}</div>
                    <div><strong>Kontak WA Orang Tua:</strong> <span style="color:#0284C7; font-weight:600;">${d.wa_ortu}</span></div>
                    <div style="grid-column:1/-1;"><strong>Alamat Rumah:</strong> ${d.alamat}</div>
                </div>
            </div>

            {{-- 2. PENGAJUAN & PEMBIMBING --}}
            <div style="background:#FFFFFF; border:1px solid #E2E8F0; border-radius:0.375rem; padding:0.85rem; margin-bottom:1rem;">
                <h6 style="margin:0 0 0.5rem 0; font-size:0.85rem; font-weight:800; color:var(--primary-dark); text-transform:uppercase;">2. Permohonan &amp; Guru BK Pembimbing</h6>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:0.4rem; font-size:0.8125rem;">
                    <div><strong>Tanggal Pengajuan:</strong> ${d.tanggal_pengajuan}</div>
                    <div><strong>Jenis Layanan:</strong> <span class="badge badge-info">${d.jenis_konseling}</span></div>
                    <div><strong>Sumber Pengajuan:</strong> <span class="badge badge-secondary">${d.sumber_pengajuan}</span></div>
                    <div><strong>Guru BK Pembimbing:</strong> <strong>${d.guru_bk}</strong></div>
                    <div><strong>Status Validasi:</strong> <span class="badge ${d.status_validasi.toLowerCase() === 'disetujui' ? 'badge-success' : 'badge-warning'}">${d.status_validasi}</span></div>
                    <div><strong>Catatan Validasi:</strong> ${d.catatan_validasi}</div>
                </div>
            </div>

            {{-- 3. URAIAN KASUS & RUJUKAN --}}
            <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:0.375rem; padding:0.85rem; margin-bottom:1rem;">
                <h6 style="margin:0 0 0.5rem 0; font-size:0.85rem; font-weight:800; color:var(--primary-dark); text-transform:uppercase;">3. Uraian Masalah &amp; Catatan Kasus</h6>
                <div style="font-size:0.8125rem; line-height:1.5;">
                    <div style="margin-bottom:0.5rem;">
                        <strong>Keluhan / Masalah yang Diajukan Siswa:</strong>
                        <div style="background:#FFFFFF; border:1px solid #E2E8F0; padding:0.5rem; border-radius:0.25rem; margin-top:0.25rem;">
                            ${d.alasan_pengajuan}
                        </div>
                    </div>
                    ${d.alasan_rujukan && d.alasan_rujukan !== '-' ? `
                        <div>
                            <strong>Catatan / Rujukan dari Wali Kelas:</strong>
                            <div style="background:#FFFFFF; border:1px solid #E2E8F0; padding:0.5rem; border-radius:0.25rem; margin-top:0.25rem;">
                                ${d.alasan_rujukan}
                            </div>
                        </div>
                    ` : ''}
                </div>
            </div>

            {{-- 4. PELAKSANAAN SESI KONSELING --}}
            <div style="background:#FFFFFF; border:1px solid #E2E8F0; border-radius:0.375rem; padding:0.85rem; margin-bottom:1rem;">
                <h6 style="margin:0 0 0.5rem 0; font-size:0.85rem; font-weight:800; color:var(--primary-dark); text-transform:uppercase;">4. Pelaksanaan Sesi Konseling</h6>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:0.4rem; font-size:0.8125rem; margin-bottom:0.5rem;">
                    <div><strong>Status Sesi:</strong> <span class="badge ${d.status_sesi.toLowerCase() === 'selesai' ? 'badge-success' : 'badge-info'}">${d.status_sesi}</span></div>
                    <div><strong>Tanggal Pelaksanaan:</strong> ${d.tanggal_pelaksanaan}</div>
                    <div><strong>Kehadiran Siswa:</strong> ${d.status_kehadiran}</div>
                </div>
                <div style="font-size:0.8125rem; line-height:1.5;">
                    <div style="margin-bottom:0.4rem;">
                        <strong>Ringkasan Hasil Konseling &amp; Evaluasi:</strong>
                        <div style="background:#F8FAFC; border:1px solid #E2E8F0; padding:0.5rem; border-radius:0.25rem; margin-top:0.25rem;">
                            ${d.hasil_konseling}
                        </div>
                    </div>
                    <div>
                        <strong>Rencana Tindak Lanjut (RTL):</strong>
                        <div style="background:#F8FAFC; border:1px solid #E2E8F0; padding:0.5rem; border-radius:0.25rem; margin-top:0.25rem;">
                            ${d.rencana_tindak_lanjut}
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. TINDAK LANJUT & SURAT PANGGILAN --}}
            <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:0.375rem; padding:0.85rem;">
                <h6 style="margin:0 0 0.5rem 0; font-size:0.85rem; font-weight:800; color:var(--primary-dark); text-transform:uppercase;">5. Tindak Lanjut &amp; Panggilan Orang Tua</h6>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:0.4rem; font-size:0.8125rem; margin-bottom:0.5rem;">
                    <div><strong>Jenis Aksi:</strong> <span class="badge badge-warning">${d.jenis_aksi}</span></div>
                    <div><strong>Status Tindak Lanjut:</strong> ${d.status_tindak_lanjut}</div>
                    <div style="grid-column:1/-1;"><strong>Catatan Tambahan:</strong> ${d.catatan_tindak_lanjut}</div>
                </div>
                ${d.has_surat ? `
                    <div style="background:#FFFFFF; border:1px solid #CBD5E1; border-radius:0.25rem; padding:0.65rem; margin-top:0.5rem;">
                        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
                            <div>
                                <strong>Surat Panggilan Terbit:</strong> <code>${d.nomor_surat}</code><br>
                                <small style="color:#64748B;">Jadwal: ${d.tanggal_pertemuan} (Pukul ${d.waktu_pertemuan}) di ${d.tempat_pertemuan}</small><br>
                                <small>Status WA: <span class="badge ${d.status_kirim_wa === 'TERKIRIM' ? 'badge-success' : 'badge-danger'}">${d.status_kirim_wa}</span></small>
                            </div>
                            <div>
                                <a href="/guru-bk/surat/${d.id_surat}/pdf" target="_blank" class="btn btn-primary btn-sm">
                                    Unduh Surat PDF
                                </a>
                            </div>
                        </div>
                    </div>
                ` : `
                    <div style="color:#64748B; font-size:0.8125rem; font-style:italic;">Tidak ada surat panggilan orang tua yang diterbitkan untuk kasus ini.</div>
                `}
            </div>
        `;
        document.getElementById('reportDetailModal').style.display = 'flex';
    }

    function showSuratDetail(idSurat) {
        const tmpl = document.getElementById('surat-detail-' + idSurat);
        if (!tmpl) return;
        let s = {};
        try {
            s = JSON.parse(tmpl.innerHTML.trim());
        } catch(e) {
            console.error("Gagal membaca detail surat:", e);
            return;
        }

        document.getElementById('modalDetailTitle').innerText = 'Data Lengkap Surat Panggilan: ' + s.nomor_surat;
        document.getElementById('modalDetailContent').innerHTML = `
            <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:0.375rem; padding:0.85rem; margin-bottom:1rem;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:0.5rem; margin-bottom:0.6rem;">
                    <div>
                        <h5 style="margin:0; font-weight:800; color:var(--primary-dark);">${s.nomor_surat}</h5>
                        <small style="color:#64748B;">Tanggal Terbit: ${s.tanggal_terbit} | Guru BK: ${s.guru_bk}</small>
                    </div>
                    <div>
                        <a href="/guru-bk/surat/${s.id}/pdf" target="_blank" class="btn btn-primary btn-sm">
                            Cetak Surat PDF
                        </a>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:0.4rem; font-size:0.8125rem; border-top:1px dashed #CBD5E1; padding-top:0.5rem;">
                    <div><strong>Perihal:</strong> ${s.perihal}</div>
                    <div><strong>Status Surat:</strong> <span class="badge badge-info">${s.status_surat}</span></div>
                    <div><strong>Status Pengiriman WA:</strong> <span class="badge ${s.status_kirim_wa === 'TERKIRIM' ? 'badge-success' : 'badge-secondary'}">${s.status_kirim_wa}</span></div>
                </div>
            </div>

            <div style="background:#FFFFFF; border:1px solid #E2E8F0; border-radius:0.375rem; padding:0.85rem; margin-bottom:1rem;">
                <h6 style="margin:0 0 0.5rem 0; font-size:0.85rem; font-weight:800; color:var(--primary-dark); text-transform:uppercase;">Data Siswa &amp; Orang Tua / Wali</h6>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:0.4rem; font-size:0.8125rem;">
                    <div><strong>Nama Lengkap Siswa:</strong> ${s.nama_siswa}</div>
                    <div><strong>NIS / NISN:</strong> ${s.nis} / ${s.nisn}</div>
                    <div><strong>Kelas / Rombel:</strong> ${s.kelas} (${s.jurusan})</div>
                    <div><strong>Wali Kelas:</strong> ${s.wali_kelas}</div>
                    <div><strong>Nama Orang Tua / Wali:</strong> ${s.nama_ortu}</div>
                    <div><strong>Nomor WhatsApp:</strong> <span style="color:#0284C7; font-weight:600;">${s.wa_ortu}</span></div>
                    <div style="grid-column:1/-1;"><strong>Alamat Rumah:</strong> ${s.alamat}</div>
                </div>
            </div>

            <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:0.375rem; padding:0.85rem;">
                <h6 style="margin:0 0 0.5rem 0; font-size:0.85rem; font-weight:800; color:var(--primary-dark); text-transform:uppercase;">Jadwal Pertemuan Orang Tua di Sekolah</h6>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:0.4rem; font-size:0.8125rem; margin-bottom:0.6rem;">
                    <div><strong>Hari / Tanggal Pertemuan:</strong> ${s.tanggal_pertemuan}</div>
                    <div><strong>Waktu / Pukul:</strong> ${s.waktu_pertemuan} WIB</div>
                    <div><strong>Tempat Menghadap:</strong> ${s.tempat}</div>
                </div>
                <div>
                    <strong>Isi Ringkas / Alasan Pemanggilan:</strong>
                    <div style="background:#FFFFFF; border:1px solid #E2E8F0; padding:0.5rem; border-radius:0.25rem; margin-top:0.25rem; font-size:0.8125rem; line-height:1.5;">
                        ${s.isi_surat}
                    </div>
                </div>
            </div>
        `;
        document.getElementById('reportDetailModal').style.display = 'flex';
    }
</script>

@endsection
