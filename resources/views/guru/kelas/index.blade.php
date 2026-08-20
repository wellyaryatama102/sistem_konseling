@extends('layouts.app')
@section('title', 'Kelola Data Kelas')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
    <div>
        <h2 style="margin:0; font-size:1.5rem;">Manajemen Data Kelas</h2>
        <p style="color:#64748b; margin:0.25rem 0 0 0;">Tambah dan atur kelas serta penugasan Wali Kelas.</p>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h3 class="card-title">Daftar Kelas Active</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Kelas</th>
                        <th>Tahun Ajaran</th>
                        <th>Wali Kelas</th>
                        <th>Siswa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kelases as $k)
                    <tr>
                        <td><strong>{{ $k->nama_kelas }}</strong></td>
                        <td>{{ $k->tahun_ajaran }}</td>
                        <td>{{ $k->waliKelas->name ?? 'Belum Ditentukan' }}</td>
                        <td><span class="badge badge-info">{{ $k->siswas_count }} Siswa</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h3 class="card-title">Tambah Kelas Baru</h3>
        <form action="{{ route('guru.kelas.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Kelas (e.g. X PPLG 1)</label>
                <input type="text" name="nama_kelas" class="form-control" required placeholder="Contoh: X PPLG 1">
            </div>
            <div class="form-group">
                <label class="form-label">Tingkat</label>
                <select name="tingkat" class="form-control" required>
                    <option value="X">Tingkat X</option>
                    <option value="XI">Tingkat XI</option>
                    <option value="XII">Tingkat XII</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Jurusan</label>
                <input type="text" name="jurusan" class="form-control" required placeholder="Contoh: PPLG">
            </div>
            <div class="form-group">
                <label class="form-label">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" class="form-control" value="2026/2027" required>
            </div>
            <div class="form-group">
                <label class="form-label">Wali Kelas</label>
                <select name="wali_kelas_id" class="form-control">
                    <option value="">-- Pilih Wali Kelas --</option>
                    @foreach($waliKelasList as $w)
                        <option value="{{ $w->id }}">{{ $w->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Kelas</button>
        </form>
    </div>
</div>
@endsection
