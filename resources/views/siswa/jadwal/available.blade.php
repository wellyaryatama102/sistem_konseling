@extends('layouts.app')
@section('title', 'Cari Slot Konseling')

@section('content')
<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Pilih Slot Jadwal Layanan Konseling</h2>
    <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Pilih slot ketersediaan waktu yang disediakan Guru BK untuk berkonsultasi.</p>
</div>

<div class="card">
    <div class="grid-2">
        @forelse($slots as $slot)
        <div style="border:1px solid var(--border-color); border-radius:0.5rem; padding:1.25rem; background-color:#FFFFFF; box-shadow:0 1px 3px rgba(0,0,0,0.03);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                <span class="badge badge-success">Tersedia</span>
                <span style="font-size:0.75rem; color:var(--text-muted); font-weight:600;">Guru BK: {{ $slot->guruBk->nama_lengkap ?? 'Guru BK' }}</span>
            </div>
            <h4 style="margin:0.5rem 0 0.25rem 0; font-size:1.125rem; font-weight:700; color:var(--primary-dark);">
                {{ \Carbon\Carbon::parse($slot->tanggal_tersedia)->format('d F Y') }}
            </h4>
            <div style="font-size:0.875rem; color:var(--text-muted); margin-bottom:1rem;">
                Waktu Pelayanan: <strong>{{ substr($slot->jam_mulai, 0, 5) }} - {{ substr($slot->jam_selesai, 0, 5) }} WIB</strong>
            </div>

            <form action="{{ route('siswa.jadwal.ajukan', $slot->id_jadwal) }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom:0.75rem;">
                    <label class="form-label" style="font-size:0.75rem;">Jenis Layanan</label>
                    <select name="jenis_konseling" class="form-control" style="font-size:0.85rem;" required>
                        <option value="individu">Konseling Individu</option>
                        <option value="kelompok">Konseling Kelompok</option>
                        <option value="insidental">Konseling Insidental</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0.75rem;">
                    <label class="form-label" style="font-size:0.75rem;">Alasan Pengajuan</label>
                    <input type="text" name="alasan_pengajuan" class="form-control" placeholder="Tuliskan pokok permasalahan atau topik konsultasi..." required style="font-size:0.85rem;">
                </div>
                <button type="submit" class="btn btn-primary btn-sm" style="width:100%; justify-content:center;">
                    Ajukan Jadwal Ini &rarr;
                </button>
            </form>
        </div>
        @empty
        <div style="grid-column: 1 / -1; text-align:center; padding:3rem; color:var(--text-muted);">
            Belum ada slot jadwal ketersediaan yang dibuka saat ini. Silakan kembali lagi nanti atau hubungi Guru BK di sekolah.
        </div>
        @endforelse
    </div>

    <div style="margin-top:1.5rem;">
        {{ $slots->links() }}
    </div>
</div>
@endsection
