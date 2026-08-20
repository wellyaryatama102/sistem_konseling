@extends('layouts.app')
@section('title', 'Sesi & Hasil Konseling')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
    <div>
        <h2 style="margin:0; font-size:1.5rem;">Sesi Konseling & Konseling Insidental</h2>
        <p style="color:#64748b; margin:0.25rem 0 0 0;">Catat hasil wawancara konseling dan pengaduan insidental.</p>
    </div>
</div>

<div class="card" style="margin-bottom:2rem;">
    <h3 class="card-title">+ Catat Konseling Insidental (Tidak Terjadwal)</h3>
    <form action="{{ route('guru.konseling.store-insidental') }}" method="POST">
        @csrf
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Pilih Siswa</label>
                <select name="siswa_id" class="form-control" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswas as $s)
                        <option value="{{ $s->id }}">{{ $s->user->name }} ({{ $s->kelas->nama_kelas ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Pelaksanaan</label>
                <input type="date" name="tanggal_pelaksanaan" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Alasan / Kasus Insidental</label>
            <textarea name="alasan" class="form-control" rows="2" required placeholder="Jelaskan alasan atau masalah yang terjadi..."></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Hasil Konseling / Tindakan Guru BK</label>
            <textarea name="hasil_konseling" class="form-control" rows="2" required placeholder="Jelaskan uraian hasil konseling..."></textarea>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Kesimpulan / Rencana Tindak Lanjut</label>
                <input type="text" name="kesimpulan" class="form-control" placeholder="Ringkasan atau tindak lanjut...">
            </div>
            <div class="form-group">
                <label class="form-label">Status Perkembangan Siswa</label>
                <select name="status_perkembangan" class="form-control" required>
                    <option value="dalam_pemantauan">Dalam Pemantauan</option>
                    <option value="perlu_perhatian">Perlu Perhatian Khusus</option>
                    <option value="membaik">Membaik</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Catatan Untuk Siswa (Dapat Dilihat Oleh Siswa)</label>
            <textarea name="catatan_untuk_siswa" class="form-control" rows="2" placeholder="Catatan arahan umum yang boleh dibaca siswa..."></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Catatan Rahasia Guru BK (Hanya Terlihat Oleh Guru BK)</label>
            <textarea name="catatan_rahasia" class="form-control" rows="2" placeholder="Catatan pribadi rahasia Guru BK..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Konseling Insidental</button>
    </form>
</div>

<div class="card">
    <h3 class="card-title">Riwayat Sesi Konseling</h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Siswa</th>
                    <th>Jenis</th>
                    <th>Alasan</th>
                    <th>Hasil / Kesimpulan</th>
                    <th>Status Sesi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($konselings as $k)
                <tr>
                    <td><strong>{{ $k->tanggal_pelaksanaan->format('d-m-Y') }}</strong></td>
                    <td>
                        {{ $k->siswa->user->name ?? '-' }}<br>
                        <small style="color:#64748b;">{{ $k->siswa->kelas->nama_kelas ?? '-' }}</small>
                    </td>
                    <td><span class="badge badge-info">{{ strtoupper($k->jenis_konseling) }}</span></td>
                    <td>{{ $k->alasan }}</td>
                    <td>
                        <strong>{{ $k->kesimpulan ?? '-' }}</strong>
                        <div style="font-size:0.75rem; color:#64748b;">{{ $k->hasil_konseling }}</div>
                    </td>
                    <td>
                        @if($k->status === 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @else
                            <span class="badge badge-warning">Terjadwal</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $konselings->links() }}
    </div>
</div>
@endsection
