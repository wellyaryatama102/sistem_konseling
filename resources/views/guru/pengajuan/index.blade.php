@extends('layouts.app')
@section('title', 'Pengajuan Konseling')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Pengajuan Layanan Konseling</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Verifikasi dan kelola permohonan pengajuan konseling dari siswa maupun rujukan wali kelas.</p>
</div>

<div class="card">
    {{-- Form Filter --}}
    <form action="{{ route('guru.pengajuan.index') }}" method="GET" style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;">
        <input type="text" name="search" class="form-control" style="flex:1; min-width:200px;"
               placeholder="Cari nama siswa atau NIS..."
               value="{{ request('search') }}">

        <select name="status" class="form-control" style="width:200px;">
            <option value="">-- Semua Status --</option>
            <option value="menunggu_validasi" {{ request('status') == 'menunggu_validasi' ? 'selected' : '' }}>Menunggu Validasi</option>
            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('guru.pengajuan.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>

    {{-- Tabel Pengajuan --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Sumber & Jenis</th>
                    <th>Alasan Pengajuan</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Jadwal Terpilih</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuans as $p)
                <tr>
                    <td>
                        <strong>{{ $p->siswa->nama_siswa ?? '-' }}</strong><br>
                        <small style="color:var(--text-muted);">NIS: {{ $p->siswa->nis ?? '-' }}</small>
                    </td>
                    <td>{{ $p->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td>
                        <span class="badge badge-gold">{{ ucfirst($p->sumber_pengajuan) }}</span><br>
                        <small style="color:var(--text-muted); text-transform:capitalize;">{{ $p->jenis_konseling }}</small>
                    </td>
                    <td style="max-width:220px; font-size:0.85rem;">
                        {{ $p->alasan_pengajuan }}
                        @if($p->alasan_rujukan)
                            <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.25rem; font-style:italic;">
                                Catatan Rujukan: {{ $p->alasan_rujukan }}
                            </div>
                        @endif
                    </td>
                    <td><small style="color:var(--text-muted);">{{ $p->created_at ? $p->created_at->format('d/m/Y H:i') : '-' }}</small></td>
                    <td>
                        @if($p->jadwal)
                            <small style="color:var(--primary); font-weight:700;">
                                {{ \Carbon\Carbon::parse($p->jadwal->tanggal_tersedia)->format('d/m/Y') }}
                            </small><br>
                            <small style="color:var(--text-muted);">
                                {{ substr($p->jadwal->jam_mulai, 0, 5) }} - {{ substr($p->jadwal->jam_selesai, 0, 5) }} WIB
                            </small>
                        @else
                            <span class="badge badge-info">Insidental</span>
                        @endif
                    </td>
                    <td>
                        @if($p->status_pengajuan == 'menunggu_validasi')
                            <span class="badge badge-warning">Menunggu</span>
                        @elseif($p->status_pengajuan == 'disetujui')
                            <span class="badge badge-success">Disetujui</span>
                        @elseif($p->status_pengajuan == 'ditolak')
                            <span class="badge badge-danger">Ditolak</span>
                        @else
                            <span class="badge badge-info">{{ strtoupper($p->status_pengajuan) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($p->status_pengajuan == 'menunggu_validasi')
                            <div style="display:flex; gap:0.4rem;">
                                {{-- Tombol Setujui --}}
                                <form action="{{ route('guru.pengajuan.validasi', $p->id_pengajuan) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="disetujui">
                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Setujui pengajuan konseling siswa ini?')">
                                        Setujui
                                    </button>
                                </form>

                                {{-- Tombol Tolak --}}
                                <button type="button" class="btn btn-danger btn-sm"
                                        onclick="openRejectModal({{ $p->id_pengajuan }}, '{{ addslashes($p->siswa->nama_siswa ?? 'Siswa') }}')">
                                    Tolak
                                </button>
                            </div>
                        @else
                            <small style="color:var(--text-muted); font-style:italic;">
                                {{ $p->catatan_validasi ? 'Catatan: ' . $p->catatan_validasi : 'Tervalidasi' }}
                            </small>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:2rem; color:var(--text-muted);">
                        Belum ada pengajuan konseling terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.25rem;">
        {{ $pengajuans->links() }}
    </div>
</div>

{{-- Modal Tolak Pengajuan --}}
<div id="rejectModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:99; align-items:center; justify-content:center;">
    <div style="background:white; padding:1.5rem; border-radius:0.5rem; max-width:450px; width:90%; box-shadow:0 10px 25px rgba(0,0,0,0.15);">
        <h3 style="margin-top:0; font-size:1.25rem; font-weight:700; color:var(--primary-dark);">Tolak Pengajuan Konseling</h3>
        <p style="color:var(--text-muted); font-size:0.875rem;" id="rejectModalSiswaName"></p>

        <form id="rejectForm" method="POST" action="">
            @csrf
            <input type="hidden" name="action" value="ditolak">

            <div class="form-group">
                <label class="form-label">Alasan / Catatan Penolakan</label>
                <textarea name="catatan_validasi" class="form-control" rows="3" placeholder="Masukkan alasan penolakan..." required></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.5rem; margin-top:1rem;">
                <button type="button" onclick="closeRejectModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal(id, siswaName) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    const text = document.getElementById('rejectModalSiswaName');
    
    form.action = '/guru-bk/pengajuan/' + id + '/validasi';
    text.textContent = 'Beri catatan penolakan untuk ' + siswaName + ':';
    modal.style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}
</script>

@endsection
