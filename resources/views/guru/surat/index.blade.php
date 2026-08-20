@extends('layouts.app')
@section('title', 'Surat Panggilan Orang Tua')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Surat Panggilan Orang Tua / Wali</h2>
        <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Pengelolaan surat resmi panggilan orang tua/wali siswa dan log pengiriman WhatsApp Gateway.</p>
    </div>

    <a href="{{ route('guru.surat.create') }}" class="btn btn-primary">
        + Buat Surat Panggilan Baru
    </a>
</div>

<div class="card">
    {{-- Form Filter --}}
    <form action="{{ route('guru.surat.index') }}" method="GET" style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;">
        <input type="text" name="search" class="form-control" style="flex:1; min-width:200px;"
               placeholder="Cari nomor surat atau nama siswa..."
               value="{{ request('search') }}">

        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->filled('search'))
            <a href="{{ route('guru.surat.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>

    {{-- Tabel Surat Panggilan --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nomor Surat</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Orang Tua / Wali</th>
                    <th>Jadwal Pertemuan</th>
                    <th>Status Pengiriman WA</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surats as $s)
                @php
                    $siswa = $s->tindakLanjut->sesiKonseling->pengajuan->siswa ?? null;
                @endphp
                <tr>
                    <td><code>{{ $s->nomor_surat }}</code></td>
                    <td><strong>{{ $siswa->nama_siswa ?? '-' }}</strong></td>
                    <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td>
                        <strong>{{ $siswa->nama_orang_tua_wali ?: 'Orang Tua / Wali' }}</strong><br>
                        <small style="color:var(--text-muted);">
                            {{ $siswa && $siswa->no_wa_orang_tua_wali ? 'WA: ' . $siswa->no_wa_orang_tua_wali : 'WA: Belum diisi' }}
                        </small>
                    </td>
                    <td>
                        <small style="color:var(--primary); font-weight:700;">
                            {{ \Carbon\Carbon::parse($s->tanggal_pertemuan)->format('d/m/Y') }}
                        </small><br>
                        <small style="color:var(--text-muted);">{{ substr($s->waktu_pertemuan, 0, 5) }} WIB</small>
                    </td>
                    <td>
                        @if($s->status_kirim_wa == 'terkirim')
                            <span class="badge badge-success">Terkirim WA</span>
                        @elseif($s->status_kirim_wa == 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @else
                            <span class="badge badge-danger">Gagal</span>
                        @endif
                    </td>
                    <td style="display:flex; gap:0.4rem; flex-wrap:wrap;">
                        <a href="{{ route('guru.surat.show', $s->id_surat) }}" class="btn btn-secondary btn-sm">
                            Lihat Surat
                        </a>

                        <form action="{{ route('guru.surat.kirim-wa', $s->id_surat) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-{{ $s->status_kirim_wa == 'gagal' ? 'danger' : 'success' }} btn-sm"
                                    onclick="return confirm('Kirim/Kirim Ulang notifikasi surat panggilan ini via WhatsApp Gateway?')">
                                {{ $s->status_kirim_wa == 'gagal' ? 'Kirim Ulang WA' : 'Kirim WA' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:2rem; color:var(--text-muted);">
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

@endsection
