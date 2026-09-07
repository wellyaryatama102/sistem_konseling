@extends('layouts.app')
@section('title', 'Input Masal Akun Siswa')

@section('content')
<div style="max-width:1100px; margin:0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
        <div>
            <h2 style="margin:0; font-size:1.35rem; font-weight:800;">Input Masal Siswa Per Kelas</h2>
            <p style="color:#64748b; margin:0; font-size:0.8rem;">Tambahkan banyak akun siswa sekaligus. Username & Password default adalah NIS.</p>
        </div>
        <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">&larr; Kembali</a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom:1rem;">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.siswa.bulk.store') }}" method="POST">
        @csrf
        <!-- PILIH KELAS -->
        <div class="card" style="margin-bottom:1rem; padding:1rem;">
            <label class="form-label" style="font-size:0.85rem; font-weight:700;">Kelas Tujuan *</label>
            <select name="id_kelas" class="form-control" required style="max-width:400px;">
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelases as $k)
                    <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                        {{ $k->nama_kelas }} ({{ $k->jurusan->nama_jurusan ?? '-' }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- TABEL INPUT MASAL -->
        <div class="card" style="padding:1rem;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
                <h4 style="margin:0; font-size:0.95rem; font-weight:700;">Daftar Siswa Baru</h4>
                <div>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="addRows(1)">+ 1 Baris</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="addRows(5)">+ 5 Baris</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table" style="width:100%;">
                    <thead>
                        <tr style="background:#f8fafc; font-size:0.8rem;">
                            <th style="width:35px; text-align:center;">#</th>
                            <th>Nama Siswa *</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th style="width:70px;">L/P</th>
                            <th>No. WA Siswa</th>
                            <th>Nama Ortu</th>
                            <th>No. WA Ortu</th>
                            <th style="width:40px; text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bulk_tbody">
                        @for($i = 0; $i < 5; $i++)
                        <tr>
                            <td style="text-align:center;" class="row-num">{{ $i + 1 }}</td>
                            <td><input type="text" name="siswas[{{ $i }}][nama_siswa]" class="form-control" placeholder="Nama Lengkap" {{ $i == 0 ? 'required' : '' }}></td>
                            <td><input type="text" name="siswas[{{ $i }}][nis]" class="form-control" placeholder="NIS"></td>
                            <td><input type="text" name="siswas[{{ $i }}][nisn]" class="form-control" placeholder="NISN"></td>
                            <td>
                                <select name="siswas[{{ $i }}][jenis_kelamin]" class="form-control">
                                    <option value="">-</option>
                                    <option value="L">L</option>
                                    <option value="P">P</option>
                                </select>
                            </td>
                            <td><input type="text" name="siswas[{{ $i }}][no_wa_siswa]" class="form-control" placeholder="08xxx"></td>
                            <td><input type="text" name="siswas[{{ $i }}][nama_orang_tua_wali]" class="form-control" placeholder="Nama Ortu"></td>
                            <td><input type="text" name="siswas[{{ $i }}][no_wa_orang_tua_wali]" class="form-control" placeholder="08xxx"></td>
                            <td style="text-align:center;">
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">&times;</button>
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            <div style="display:flex; justify-content:space-between; margin-top:1rem;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="addRows(5)">+ Tambah 5 Baris Lagi</button>
                <div>
                    <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary" style="font-weight:700;">Simpan Semua Akun</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let rowCounter = 5;

    function addRows(count) {
        const tbody = document.getElementById('bulk_tbody');
        for (let i = 0; i < count; i++) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="text-align:center;" class="row-num">${rowCounter + 1}</td>
                <td><input type="text" name="siswas[${rowCounter}][nama_siswa]" class="form-control" placeholder="Nama Lengkap"></td>
                <td><input type="text" name="siswas[${rowCounter}][nis]" class="form-control" placeholder="NIS"></td>
                <td><input type="text" name="siswas[${rowCounter}][nisn]" class="form-control" placeholder="NISN"></td>
                <td>
                    <select name="siswas[${rowCounter}][jenis_kelamin]" class="form-control">
                        <option value="">-</option>
                        <option value="L">L</option>
                        <option value="P">P</option>
                    </select>
                </td>
                <td><input type="text" name="siswas[${rowCounter}][no_wa_siswa]" class="form-control" placeholder="08xxx"></td>
                <td><input type="text" name="siswas[${rowCounter}][nama_orang_tua_wali]" class="form-control" placeholder="Nama Ortu"></td>
                <td><input type="text" name="siswas[${rowCounter}][no_wa_orang_tua_wali]" class="form-control" placeholder="08xxx"></td>
                <td style="text-align:center;"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">&times;</button></td>
            `;
            tbody.appendChild(tr);
            rowCounter++;
        }
        renumberRows();
    }

    function removeRow(btn) {
        const tbody = document.getElementById('bulk_tbody');
        if (tbody.querySelectorAll('tr').length <= 1) {
            alert('Minimal tersisa 1 baris.');
            return;
        }
        btn.closest('tr').remove();
        renumberRows();
    }

    function renumberRows() {
        document.querySelectorAll('#bulk_tbody tr').forEach((row, idx) => {
            const cell = row.querySelector('.row-num');
            if (cell) cell.textContent = idx + 1;
        });
    }
</script>
@endsection