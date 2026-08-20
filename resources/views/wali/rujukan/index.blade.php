@extends('layouts.app')
@section('title', 'Rujukan ke Guru BK')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h2 style="margin:0; font-size:1.5rem;">Rujukan Siswa ke Guru BK</h2>
        <p style="color:#64748b; margin:0.25rem 0 0 0; font-size:0.875rem;">Pengajuan rujukan layanan konseling untuk siswa di kelas {{ $kelas->nama_kelas }}.</p>
    </div>

    <a href="{{ route('wali.rujukan.create') }}" class="btn btn-primary">
        + Buat Rujukan Baru
    </a>
</div>

<div class="card">
    {{-- Form Filter --}}
    <form action="{{ route('wali.rujukan.index') }}" method="GET" style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;">
        <input type="text" name="search" class="form-control" style="flex:1; min-width:200px;"
               placeholder="Cari nama siswa..."
               value="{{ request('search') }}">

        <select name="status" class="form-control" style="width:180px;">
            <option value="">-- Semua Status --</option>
            <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
            <option value="Ditindaklanjuti" {{ request('status') == 'Ditindaklanjuti' ? 'selected' : '' }}>Ditindaklanjuti</option>
            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('wali.rujukan.index') }}" class="btn" style="background:#e2e8f0; color:#334155;">Reset</a>
        @endif
    </form>

    {{-- Tabel Rujukan --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Tanggal Rujukan</th>
                    <th>Alasan Rujukan</th>
                    <th>Tingkat Perhatian</th>
                    <th>Status Rujukan</th>
                    <th>Catatan Guru BK</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rujukans as $r)
                <tr>
                    <td><strong>{{ $r->siswa->user->name ?? '-' }}</strong></td>
                    <td><small style="color:#64748b;">{{ \Carbon\Carbon::parse($r->tanggal_rujukan)->format('d/m/Y') }}</small></td>
                    <td style="max-width:180px; font-size:0.85rem;">{{ \Illuminate\Support\Str::limit($r->alasan_rujukan, 45) }}</td>
                    <td>
                        @if($r->tingkat_perhatian == 'Tinggi')
                            <span class="badge badge-danger">Tinggi</span>
                        @elseif($r->tingkat_perhatian == 'Sedang')
                            <span class="badge badge-warning">Sedang</span>
                        @else
                            <span class="badge badge-info">Rendah</span>
                        @endif
                    </td>
                    <td>
                        @if($r->status == 'Menunggu')
                            <span class="badge badge-warning">Menunggu</span>
                        @elseif($r->status == 'Diterima')
                            <span class="badge badge-info">Diterima</span>
                        @elseif($r->status == 'Ditindaklanjuti')
                            <span class="badge badge-primary" style="background:#2563eb; color:white;">Ditindaklanjuti</span>
                        @else
                            <span class="badge badge-success">Selesai</span>
                        @endif
                    </td>
                    <td style="max-width:180px; font-size:0.8rem; color:#64748b;">
                        {{ $r->catatan_guru_bk ?? '-' }}
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm" style="background:#0284c7; color:white;"
                                onclick="openRujukanDetail({{ json_encode($r) }}, '{{ addslashes($r->siswa->user->name ?? '') }}')">
                            Detail
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:2rem; color:#64748b;">
                        Belum ada data rujukan konseling terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.25rem;">
        {{ $rujukans->links() }}
    </div>
</div>

{{-- Modal Detail Rujukan --}}
<div id="rujukanDetailModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:99; align-items:center; justify-content:center;">
    <div style="background:white; padding:1.5rem; border-radius:0.5rem; max-width:550px; width:90%;">
        <h3 style="margin-top:0; font-size:1.25rem;">Detail Rujukan ke Guru BK</h3>
        
        <table style="width:100%; margin-bottom:1rem; font-size:0.875rem;">
            <tr><td style="font-weight:600; width:155px; padding:0.25rem 0;">Nama Siswa</td><td id="rdNama">: -</td></tr>
            <tr><td style="font-weight:600; padding:0.25rem 0;">Tanggal Rujukan</td><td id="rdTanggal">: -</td></tr>
            <tr><td style="font-weight:600; padding:0.25rem 0;">Tingkat Perhatian</td><td id="rdTingkat">: -</td></tr>
            <tr><td style="font-weight:600; padding:0.25rem 0;">Alasan Rujukan</td><td id="rdAlasan">: -</td></tr>
            <tr><td style="font-weight:600; padding:0.25rem 0;">Deskripsi Singkat</td><td id="rdDeskripsi">: -</td></tr>
            <tr><td style="font-weight:600; padding:0.25rem 0;">Status Rujukan</td><td id="rdStatus">: -</td></tr>
            <tr><td style="font-weight:600; padding:0.25rem 0;">Catatan Guru BK</td><td id="rdCatatan">: -</td></tr>
        </table>

        <div style="display:flex; justify-content:flex-end;">
            <button type="button" onclick="closeRujukanDetailModal()" class="btn" style="background:#e2e8f0; color:#334155;">Tutup</button>
        </div>
    </div>
</div>

<script>
function openRujukanDetail(r, nama) {
    document.getElementById('rdNama').textContent = ': ' + nama;
    document.getElementById('rdTanggal').textContent = ': ' + (r.tanggal_rujukan || '-');
    document.getElementById('rdTingkat').textContent = ': ' + (r.tingkat_perhatian || '-');
    document.getElementById('rdAlasan').textContent = ': ' + (r.alasan_rujukan || '-');
    document.getElementById('rdDeskripsi').textContent = ': ' + (r.deskripsi_singkat || '-');
    document.getElementById('rdStatus').textContent = ': ' + (r.status || '-');
    document.getElementById('rdCatatan').textContent = ': ' + (r.catatan_guru_bk || '-');
    document.getElementById('rujukanDetailModal').style.display = 'flex';
}
function closeRujukanDetailModal() { document.getElementById('rujukanDetailModal').style.display = 'none'; }
</script>

@endsection
