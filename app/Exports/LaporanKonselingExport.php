<?php

namespace App\Exports;

use App\Models\Konseling;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanKonselingExport implements FromCollection, WithHeadings, WithMapping
{
    protected $kelasId;

    public function __construct($kelasId = null)
    {
        $this->kelasId = $kelasId;
    }

    public function collection()
    {
        $query = Konseling::with(['siswa.user', 'siswa.kelas', 'guruBk']);
        if ($this->kelasId) {
            $query->whereHas('siswa', function ($q) {
                $q->where('kelas_id', $this->kelasId);
            });
        }
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tanggal Pelaksanaan',
            'Nama Siswa',
            'Kelas',
            'Guru BK',
            'Jenis Konseling',
            'Alasan',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->tanggal_pelaksanaan ? $row->tanggal_pelaksanaan->format('Y-m-d') : '-',
            $row->siswa->user->name ?? '-',
            $row->siswa->kelas->nama_kelas ?? '-',
            $row->guruBk->name ?? '-',
            strtoupper($row->jenis_konseling),
            $row->alasan,
            strtoupper($row->status),
        ];
    }
}
