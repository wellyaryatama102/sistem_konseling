<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

/**
 * FUNGSI FILE INI:
 * Memproses impor data masal siswa per kelas dari file Excel/CSV.
 * Otomatis membuat akun User (role: siswa) dan record Siswa terkait.
 */
class SiswaImport implements ToCollection, WithStartRow
{
    protected int $idKelas;
    protected string $defaultPasswordSetting;
    protected int $importedCount = 0;
    protected array $errors = [];

    public function __construct(int $idKelas, string $defaultPasswordSetting = 'nis')
    {
        $this->idKelas = $idKelas;
        $this->defaultPasswordSetting = $defaultPasswordSetting;
    }

    public function startRow(): int
    {
        return 2; // Lewati baris 1 (Header)
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // Nomor baris di Excel

            $namaSiswa = trim($row[0] ?? '');
            $nis = trim($row[1] ?? '');
            $nisn = trim($row[2] ?? '');
            $jenisKelamin = strtoupper(trim($row[3] ?? ''));
            $noWaSiswa = trim($row[4] ?? '');
            $namaOrtu = trim($row[5] ?? '');
            $noWaOrtu = trim($row[6] ?? '');
            $customUsername = trim($row[7] ?? '');
            $customPassword = trim($row[8] ?? '');

            // Abaikan baris kosong
            if (empty($namaSiswa) && empty($nis)) {
                continue;
            }

            // Validasi Data Minimum
            if (empty($namaSiswa)) {
                $this->errors[] = "Baris #{$rowNum}: Nama siswa wajib diisi.";
                continue;
            }

            // Tentukan Username
            $username = !empty($customUsername) ? $customUsername : (!empty($nis) ? $nis : 'siswa_' . Str::slug($namaSiswa, '_') . '_' . rand(100, 999));
            
            // Cek Keunikan Username
            if (User::where('username', $username)->exists() || Siswa::where('username', $username)->exists()) {
                $this->errors[] = "Baris #{$rowNum} ({$namaSiswa}): Username/NIS '{$username}' sudah digunakan di sistem.";
                continue;
            }

            // Cek Keunikan NIS (jika ada)
            if (!empty($nis) && Siswa::where('nis', $nis)->exists()) {
                $this->errors[] = "Baris #{$rowNum} ({$namaSiswa}): NIS '{$nis}' sudah terdaftar.";
                continue;
            }

            // Tentukan Password
            $rawPassword = !empty($customPassword) ? $customPassword : (!empty($nis) ? $nis : '12345678');
            if ($this->defaultPasswordSetting !== 'nis' && empty($customPassword)) {
                $rawPassword = $this->defaultPasswordSetting;
            }

            // Sanitasi Jenis Kelamin
            if (!in_array($jenisKelamin, ['L', 'P'])) {
                $jenisKelamin = null;
            }

            // Generate Email Sintetis jika tidak diisi
            $email = $username . '@siswa.smkn2guguak.sch.id';

            try {
                DB::transaction(function () use ($namaSiswa, $username, $email, $rawPassword, $nis, $nisn, $jenisKelamin, $noWaSiswa, $namaOrtu, $noWaOrtu) {
                    $user = User::create([
                        'name' => $namaSiswa,
                        'username' => $username,
                        'email' => $email,
                        'password' => Hash::make($rawPassword),
                        'role' => 'siswa',
                        'status' => 'active',
                    ]);

                    Siswa::create([
                        'user_id' => $user->id,
                        'username' => $username,
                        'password' => $user->password,
                        'nama_siswa' => $namaSiswa,
                        'nis' => $nis ?: null,
                        'nisn' => $nisn ?: null,
                        'id_kelas' => $this->idKelas,
                        'jenis_kelamin' => $jenisKelamin,
                        'no_wa_siswa' => $noWaSiswa ?: null,
                        'nama_orang_tua_wali' => $namaOrtu ?: null,
                        'no_wa_orang_tua_wali' => $noWaOrtu ?: null,
                        'status_siswa' => 'aktif',
                    ]);
                });

                $this->importedCount++;
            } catch (\Exception $e) {
                $this->errors[] = "Baris #{$rowNum} ({$namaSiswa}): Gagal menyimpan data ({$e->getMessage()}).";
            }
        }
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
