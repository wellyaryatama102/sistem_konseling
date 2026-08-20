@extends('layouts.app')
@section('title', 'Pemantauan Siswa')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="margin:0; font-size:1.5rem;">Pemantauan Kondisi Siswa Sekolah</h2>
    <p style="color:#64748b; margin:0.25rem 0 0 0; font-size:0.875rem;">Monitoring gambaran umum kondisi dan perkembangan siswa tingkat sekolah (Read-Only).</p>
</div>

<div class="card">
    {{-- Form Filter --}}
    <form action="{{ route('wakasis.pemantauan.index') }}" method="GET" style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;">
        <input type="text" name="search" class="form-control" style="flex:1; min-width:200px;"
               placeholder="Cari nama siswa..."
               value="{{ request('search') }}">

        <select name="kelas_id" class="form-control" style="width:160px;">
            <option value="">-- Semua Kelas --</option>
            @foreach($kelases as $k)
                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
            @endforeach
        </select>

        <select name="jurusan" class="form-control" style="width:160px;">
            <option value="">-- Jurusan --</option>
            @foreach($jurusans as $j)
                <option value="{{ $j }}" {{ request('jurusan') == $j ? 'selected' : '' }}>{{ $j }}</option>
            @endforeach
        </select>

        <select name="status" class="form-control" style="width:170px;">
            <option value="">-- Status Pemantauan --</option>
            <option value="Baik" {{ request('status') == 'Baik' ? 'selected' : '' }}>Baik</option>
            <option value="Stabil" {{ request('status') == 'Stabil' ? 'selected' : '' }}>Stabil</option>
            <option value="Perlu Perhatian" {{ request('status') == 'Perlu Perhatian' ? 'selected' : '' }}>Perlu Perhatian</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request()->hasAny(['search', 'kelas_id', 'jurusan', 'status']))
            <a href="{{ route('wakasis.pemantauan.index') }}" class="btn" style="background:#e2e8f0; color:#334155;">Reset</a>
        @endif
    </form>

    {{-- Tabel Pemantauan --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>Status Pemantauan</th>
                    <th>Tanggal Pemantauan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemantauans as $pm)
                <tr>
                    <td><strong>{{ $pm->siswa->user->name ?? '-' }}</strong></td>
                    <td>{{ $pm->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $pm->siswa->kelas->jurusan ?? '-' }}</td>
                    <td>
                        @if($pm->status_pemantauan == 'Perlu Perhatian')
                            <span class="badge badge-danger">Perlu Perhatian</span>
                        @elseif($pm->status_pemantauan == 'Baik')
                            <span class="badge badge-success">Baik</span>
                        @else
                            <span class="badge badge-info">Stabil</span>
                        @endif
                    </td>
                    <td><small style="color:#64748b;">{{ \Carbon\Carbon::parse($pm->tanggal_pemantauan)->format('d/m/Y') }}</small></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:2rem; color:#64748b;">
                        Belum ada catatan pemantauan siswa terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.25rem;">
        {{ $pemantauans->links() }}
    </div>
</div>

@endsection
