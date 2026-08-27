@extends('layouts.app')
@section('title', 'Tindak Lanjut & Surat Panggilan')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
    <div>
        <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Tindak Lanjut &amp; Surat Panggilan Orang Tua</h2>
        <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Pengelolaan surat resmi pemanggilan orang tua/wali siswa dan monitoring rencana aksi tindak lanjut (RTL).</p>
    </div>

    @if(request('tab', 'surat') === 'surat')
        <a href="{{ route('guru.surat.create') }}" class="btn btn-primary">
            + Buat Surat Panggilan Baru
        </a>
    @endif
</div>

@php
    $currentTab = request('tab', 'surat');
@endphp

{{-- Tab Navigasi --}}
<div style="display:flex; gap:0.5rem; margin-bottom:1.25rem; border-bottom:2px solid #E2E8F0; padding-bottom:0;">
    <a href="{{ route('guru.tindak-lanjut.index', ['tab' => 'surat']) }}" 
       style="padding:0.65rem 1.25rem; font-weight:700; font-size:0.875rem; text-decoration:none; border-bottom:3px solid {{ $currentTab === 'surat' ? 'var(--primary)' : 'transparent' }}; color:{{ $currentTab === 'surat' ? 'var(--primary)' : '#64748B' }}; margin-bottom:-2px;">
        Surat Panggilan Orang Tua
    </a>
    <a href="{{ route('guru.tindak-lanjut.index', ['tab' => 'rtl']) }}" 
       style="padding:0.65rem 1.25rem; font-weight:700; font-size:0.875rem; text-decoration:none; border-bottom:3px solid {{ $currentTab === 'rtl' ? 'var(--primary)' : 'transparent' }}; color:{{ $currentTab === 'rtl' ? 'var(--primary)' : '#64748B' }}; margin-bottom:-2px;">
        Rencana Tindak Lanjut (RTL)
    </a>
</div>

{{-- TAB 1: SURAT PANGGILAN ORANG TUA --}}
@if($currentTab === 'surat')
<div class="card">
    {{-- Form Filter & Pencarian Surat --}}
    <form action="{{ route('guru.tindak-lanjut.index') }}" method="GET" style="display:flex; gap:0.75rem; margin-bottom:1.5rem; flex-wrap:wrap; align-items:center;">
        <input type="hidden" name="tab" value="surat">
        
        <div style="flex:1; min-width:260px;">
            <input type="text" name="search_surat" class="form-control"
                   placeholder="Cari nomor surat atau nama siswa..."
                   value="{{ request('search_surat') }}" style="height:38px;">
        </div>

        <button type="submit" class="btn btn-primary" style="height:38px; padding:0 1.25rem;">Cari</button>
        @if(request()->filled('search_surat'))
            <a href="{{ route('guru.tindak-lanjut.index', ['tab' => 'surat']) }}" class="btn btn-secondary" style="height:38px; display:inline-flex; align-items:center; padding:0 1rem;">Reset</a>
        @endif
    </form>

    {{-- Tabel Surat Panggilan --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width:35px; text-align:center;">No</th>
                    <th>Nomor Surat</th>
                    <th>Nama Siswa &amp; Kelas</th>
                    <th>Orang Tua / Kontak WA</th>
                    <th>Jadwal Pertemuan</th>
                    <th style="text-align:center;">Status WA</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surats as $index => $s)
                @php
                    $siswa = $s->tindakLanjut->sesiKonseling->pengajuan->siswa ?? null;
                @endphp
                <tr>
                    <td style="text-align:center; color:var(--text-muted);">{{ $index + 1 }}</td>
                    <td><code>{{ $s->nomor_surat }}</code></td>
                    <td>
                        <strong>{{ $siswa->nama_siswa ?? '-' }}</strong><br>
                        <small style="color:var(--text-muted);">{{ $siswa->kelas->nama_kelas ?? '-' }} (NIS: {{ $siswa->nis ?? '-' }})</small>
                    </td>
                    <td>
                        <strong>{{ $siswa->nama_orang_tua_wali ?: 'Orang Tua / Wali' }}</strong><br>
                        <small style="color:#0284c7;">
                            {{ $siswa && $siswa->no_wa_orang_tua_wali ? 'WA: ' . $siswa->no_wa_orang_tua_wali : 'WA: Belum diisi' }}
                        </small>
                    </td>
                    <td>
                        <small style="color:var(--primary); font-weight:700;">
                            {{ \Carbon\Carbon::parse($s->tanggal_pertemuan)->format('d/m/Y') }}
                        </small><br>
                        <small style="color:var(--text-muted);">Pukul {{ substr($s->waktu_pertemuan, 0, 5) }} WIB</small>
                    </td>
                    <td style="text-align:center;">
                        @if($s->status_kirim_wa == 'terkirim')
                            <span class="badge badge-success">Terkirim WA</span>
                        @elseif($s->status_kirim_wa == 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @else
                            <span class="badge badge-danger">Gagal</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <div style="display:inline-flex; gap:0.35rem; align-items:center;">
                            <a href="{{ route('guru.surat.show', $s->id_surat) }}" class="btn btn-secondary btn-sm">
                                Lihat Surat
                            </a>
                            <a href="{{ route('guru.surat.pdf', $s->id_surat) }}" target="_blank" class="btn btn-primary btn-sm">
                                PDF
                            </a>
                            <form action="{{ route('guru.surat.kirim-wa', $s->id_surat) }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" class="btn btn-{{ $s->status_kirim_wa == 'gagal' ? 'danger' : 'success' }} btn-sm"
                                        onclick="return confirm('Kirim/Kirim Ulang notifikasi surat panggilan ini via WhatsApp Gateway?')">
                                    {{ $s->status_kirim_wa == 'gagal' ? 'Kirim Ulang WA' : 'Kirim WA' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:2.5rem; color:var(--text-muted);">
                        Belum ada surat panggilan orang tua/wali yang diterbitkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.25rem;">
        {{ $surats->links() }}
    </div>
</div>

{{-- TAB 2: RENCANA TINDAK LANJUT (RTL) --}}
@else
<div class="card">
    {{-- Form Filter Tindak Lanjut --}}
    <form action="{{ route('guru.tindak-lanjut.index') }}" method="GET" style="display:flex; gap:0.75rem; margin-bottom:1.5rem; flex-wrap:wrap; align-items:center;">
        <input type="hidden" name="tab" value="rtl">

        <div style="width:200px;">
            <select name="jenis_aksi" class="form-control" style="height:38px;">
                <option value="">-- Semua Jenis Aksi --</option>
                <option value="selesai" {{ request('jenis_aksi') == 'selesai' ? 'selected' : '' }}>Selesai (Tuntas)</option>
                <option value="sesi_lanjutan" {{ request('jenis_aksi') == 'sesi_lanjutan' ? 'selected' : '' }}>Sesi Konseling Lanjutan</option>
                <option value="surat_ortu" {{ request('jenis_aksi') == 'surat_ortu' ? 'selected' : '' }}>Surat Panggilan Orang Tua</option>
            </select>
        </div>

        <div style="width:200px;">
            <select name="status" class="form-control" style="height:38px;">
                <option value="">-- Semua Status --</option>
                <option value="belum_ditindaklanjuti" {{ request('status') == 'belum_ditindaklanjuti' ? 'selected' : '' }}>Belum Ditindaklanjuti</option>
                <option value="terjadwal" {{ request('status') == 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="height:38px; padding:0 1.25rem;">Filter</button>
        @if(request()->hasAny(['jenis_aksi', 'status']))
            <a href="{{ route('guru.tindak-lanjut.index', ['tab' => 'rtl']) }}" class="btn btn-secondary" style="height:38px; display:inline-flex; align-items:center; padding:0 1rem;">Reset</a>
        @endif
    </form>

    {{-- Tabel Tindak Lanjut --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width:35px; text-align:center;">No</th>
                    <th>Nama Siswa &amp; Kelas</th>
                    <th>Jenis Aksi Tindak Lanjut</th>
                    <th>Jadwal / Surat Terkait</th>
                    <th style="text-align:center;">Status RTL</th>
                    <th>Catatan Guru BK</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tindakLanjuts as $index => $t)
                <tr>
                    <td style="text-align:center; color:var(--text-muted);">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $t->sesiKonseling->pengajuan->siswa->nama_siswa ?? '-' }}</strong><br>
                        <small style="color:var(--text-muted);">{{ $t->sesiKonseling->pengajuan->siswa->kelas->nama_kelas ?? '-' }} (NIS: {{ $t->sesiKonseling->pengajuan->siswa->nis ?? '-' }})</small>
                    </td>
                    <td>
                        @if($t->jenis_aksi == 'selesai')
                            <span class="badge badge-success">Selesai Tuntas</span>
                        @elseif($t->jenis_aksi == 'sesi_lanjutan')
                            <span class="badge badge-info">Sesi Lanjutan</span>
                        @elseif($t->jenis_aksi == 'surat_ortu')
                            <span class="badge badge-gold">Panggilan Ortua &amp; Konseling Lanjutan</span>
                        @endif
                    </td>
                    <td>
                        @if($t->jadwal)
                            <div style="font-size:0.8rem; color:var(--primary); font-weight:700;">
                                Slot Lanjutan: {{ \Carbon\Carbon::parse($t->jadwal->tanggal_tersedia)->format('d/m/Y') }} ({{ substr($t->jadwal->jam_mulai, 0, 5) }} WIB)
                            </div>
                        @endif
                        @if($t->suratPanggilans->count() > 0)
                            <a href="{{ route('guru.surat.show', $t->suratPanggilans->first()->id_surat) }}" style="font-size:0.8rem; color:var(--primary); font-weight:700; display:block;">
                                Surat: {{ $t->suratPanggilans->first()->nomor_surat }}
                            </a>
                        @elseif($t->jenis_aksi == 'surat_ortu')
                            <a href="{{ route('guru.surat.create', ['tindak_lanjut_id' => $t->id_tindak_lanjut]) }}" class="btn btn-warning btn-sm" style="color:#000; margin-top:0.2rem;">
                                + Terbitkan Surat
                            </a>
                        @elseif(!$t->jadwal)
                            <small style="color:var(--text-muted);">-</small>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @if($t->status_tindak_lanjut == 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @elseif($t->status_tindak_lanjut == 'terjadwal')
                            <span class="badge badge-info">Terjadwal</span>
                        @else
                            <span class="badge badge-warning">Belum Ditindaklanjuti</span>
                        @endif
                    </td>
                    <td style="max-width:220px; font-size:0.8125rem; color:var(--text-muted);">
                        {{ $t->catatan ?: '-' }}
                    </td>
                    <td style="text-align:center;">
                        <button type="button" class="btn btn-primary btn-sm"
                                onclick="openEditModal({{ $t->id_tindak_lanjut }}, '{{ $t->status_tindak_lanjut }}', '{{ addslashes($t->catatan ?? '') }}')">
                            Update Status
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:2.5rem; color:var(--text-muted);">
                        Belum ada data rencana tindak lanjut.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.25rem;">
        {{ $tindakLanjuts->links() }}
    </div>
</div>
@endif

{{-- Modal Edit Status Tindak Lanjut --}}
<div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:0.5rem; max-width:500px; width:100%; padding:1.5rem; margin:1rem;">
        <h3 style="margin-top:0; font-size:1.25rem; font-weight:800; color:var(--primary-dark);">Update Status Tindak Lanjut</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Status Tindak Lanjut</label>
                <select name="status_tindak_lanjut" id="modal_status" class="form-control" required>
                    <option value="belum_ditindaklanjuti">Belum Ditindaklanjuti</option>
                    <option value="terjadwal">Terjadwal</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan Tambahan Guru BK</label>
                <textarea name="catatan" id="modal_catatan" class="form-control" rows="3"></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.5rem; margin-top:1.5rem;">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, status, catatan) {
        document.getElementById('editForm').action = '/guru-bk/tindak-lanjut/' + id;
        document.getElementById('modal_status').value = status;
        document.getElementById('modal_catatan').value = catatan;
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>

@endsection
