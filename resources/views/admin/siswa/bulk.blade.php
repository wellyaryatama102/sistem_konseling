@extends('layouts.app')
@section('title', 'Input Masal Akun Siswa Per Kelas')

@section('content')

<div style="max-width:1100px; margin:0 auto;">
    <div style="margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <div>
            <h2 style="margin:0; font-size:1.5rem; font-weight:800; color:var(--primary-dark);">Input Masal Siswa Per Kelas</h2>
            <p style="color:var(--text-muted); margin:0.25rem 0 0 0; font-size:0.875rem;">Tambahkan banyak akun siswa sekaligus dalam satu formulir untuk kelas yang dipilih.</p>
        </div>
        <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">
            &larr; Kembali ke Master Siswa
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.siswa.bulk.store') }}" method="POST">
        @csrf

        <div class="card" style="margin-bottom: 1.5rem;">
            <h4 style="margin-top:0; margin-bottom:1rem; color:var(--primary-dark); font-size:1.1rem;">Pilih Kelas Tujuan</h4>
            <div style="max-width:500px;">
                <label class="form-label">Kelas <span style="color:var(--danger);">*</span></label>
                <select name="id_kelas" class="form-control" required style="font-weight:600; font-size:1rem;">
                    <option value="">-- Pilih Kelas Tujuan --</option>
                    @foreach($kelases as $k)
                        <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} ({{ $k->jurusan->nama_jurusan ?? '-' }})
                        </option>
                    @endforeach
                </select>
                <small style="color:var(--text-muted); display:block; margin-top:0.25rem;">Catatan: Akun login default akan menggunakan <strong>NIS</strong> sebagai Username dan Password.</small>
            </div>
        </div>

        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:0.5rem;">
                <h4 style="margin:0; color:var(--primary-dark); font-size:1.1rem;">Daftar Siswa Baru</h4>
                <div style="display:flex; gap:0.5rem;">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="addRows(1)">+ 1 Baris</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="addRows(5)">+ 5 Baris</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table" id="bulk_table" style="width:100%;">
                    <thead>
                        <tr style="background:var(--bg-light, #f8fafc); text-align:left;">
                            <th style="width:40px; text-align:center;">#</th>
                            <th style="min-width:200px;">Nama Siswa <span style="color:var(--danger);">*</span></th>
                            <th style="min-width:120px;">NIS (Username/Pass)</th>
                            <th style="min-width:120px;">NISN</th>
                            <th style="width:90px;">L/P</th>
                            <th style="min-width:130px;">No. WA Siswa</th>
                            <th style="min-width:160px;">Nama Ortu/Wali</th>
                            <th style="min-width:130px;">No. WA Ortu</th>
                            <th style="width:50px; text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bulk_tbody">
                        @for($i = 0; $i < 5; $i++)
                        <tr>
                            <td style="text-align:center; font-weight:600; color:var(--text-muted);" class="row-num">{{ $i + 1 }}</td>
                            <td>
                                <input type="text" name="siswas[{{ $i }}][nama_siswa]" class="form-control" placeholder="Nama Lengkap" {{ $i == 0 ? 'required' : '' }}>
                            </td>
                            <td>
                                <input type="text" name="siswas[{{ $i }}][nis]" class="form-control" placeholder="NIS">
                            </td>
                            <td>
                                <input type="text" name="siswas[{{ $i }}][nisn]" class="form-control" placeholder="NISN">
                            </td>
                            <td>
                                <select name="siswas[{{ $i }}][jenis_kelamin]" class="form-control">
                                    <option value="">-</option>
                                    <option value="L">L</option>
                                    <option value="P">P</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="siswas[{{ $i }}][no_wa_siswa]" class="form-control" placeholder="08xxx">
                            </td>
                            <td>
                                <input type="text" name="siswas[{{ $i }}][nama_orang_tua_wali]" class="form-control" placeholder="Nama Ortu">
                            </td>
                            <td>
                                <input type="text" name="siswas[{{ $i }}][no_wa_orang_tua_wali]" class="form-control" placeholder="08xxx">
                            </td>
                            <td style="text-align:center;">
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)" title="Hapus baris ini">&times;</button>
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:1.5rem; padding-top:1rem; border-top:1px solid var(--border-color);">
                <button type="button" class="btn btn-secondary btn-sm" onclick="addRows(5)">+ Tambah 5 Baris Lagi</button>
                <div style="display:flex; gap:1rem;">
                    <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Semua Akun Siswa</button>
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
            const index = rowCounter;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="text-align:center; font-weight:600; color:var(--text-muted);" class="row-num">${index + 1}</td>
                <td><input type="text" name="siswas[${index}][nama_siswa]" class="form-control" placeholder="Nama Lengkap"></td>
                <td><input type="text" name="siswas[${index}][nis]" class="form-control" placeholder="NIS"></td>
                <td><input type="text" name="siswas[${index}][nisn]" class="form-control" placeholder="NISN"></td>
                <td>
                    <select name="siswas[${index}][jenis_kelamin]" class="form-control">
                        <option value="">-</option>
                        <option value="L">L</option>
                        <option value="P">P</option>
                    </select>
                </td>
                <td><input type="text" name="siswas[${index}][no_wa_siswa]" class="form-control" placeholder="08xxx"></td>
                <td><input type="text" name="siswas[${index}][nama_orang_tua_wali]" class="form-control" placeholder="Nama Ortu"></td>
                <td><input type="text" name="siswas[${index}][no_wa_orang_tua_wali]" class="form-control" placeholder="08xxx"></td>
                <td style="text-align:center;">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)" title="Hapus baris ini">&times;</button>
                </td>
            `;
            tbody.appendChild(tr);
            rowCounter++;
        }
        renumberRows();
    }

    function removeRow(btn) {
        const tbody = document.getElementById('bulk_tbody');
        if (tbody.querySelectorAll('tr').length <= 1) {
            alert('Minimal harus tersisa 1 baris input.');
            return;
        }
        const tr = btn.closest('tr');
        tr.remove();
        renumberRows();
    }

    function renumberRows() {
        const rows = document.querySelectorAll('#bulk_tbody tr');
        rows.forEach((row, idx) => {
            const cell = row.querySelector('.row-num');
            if (cell) cell.textContent = idx + 1;
        });
    }
</script>

@endsection
