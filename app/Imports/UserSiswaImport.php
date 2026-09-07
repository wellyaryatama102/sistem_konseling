<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

/**
 * FUNGSI FILE INI:
 * Memproses impor masal akun pengguna siswa (role: siswa) di Manajemen Pengguna.
 * Otomatis menyinkronkan data ke tabel Siswa.
 */
class UserSiswaImport implements ToCollection, WithStartRow
{
    protected ?int $defaultIdKelas;
    protected string $defaultPasswordSetting;
    protected int $importedCount = 0;
    protected array $errors = [];

    public function __construct(?int $defaultIdKelas = null, string $defaultPasswordSetting = 'username')
    {
        $this->defaultIdKelas = $defaultIdKelas;
        $this->defaultPasswordSetting = $defaultPasswordSetting;
    }

    public function startRow(): int
    {
        return 2; // Lewati Header Baris 1
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;

            $namaLengkap = trim($row[0] ?? '');
            $username = trim($row[1] ?? '');
            $customEmail = trim($row[2] ?? '');
            $customPassword = trim($row[3] ?? '');
            $namaKelas = trim($row[4] ?? '');

            // Abaikan baris kosong
            if (empty($namaLengkap) && empty($username)) {
                continue;
            }

            // Validasi Data Utama
            if (empty($namaLengkap)) {
                $this->errors[] = "Baris #{$rowNum}: Nama lengkap siswa wajib diisi.";
                continue;
            }

            if (empty($username)) {
                $username = 'siswa_' . Str::slug($namaLengkap, '_') . '_' . rand(100, 999);
            }

            // Cek Keunikan Username
            if (User::where('username', $username)->exists()) {
                $this->errors[] = "Baris #{$rowNum} ({$namaLengkap}): Username '{$username}' sudah digunakan.";
                continue;
            }

            // Email & Password
            $email = !empty($customEmail) ? $customEmail : $username . '@siswa.smkn2guguak.sch.id';
            
            if (User::where('email', $email)->exists()) {
                $email = $username . '_' . rand(10, 99) . '@siswa.smkn2guguak.sch.id';
            }

            $rawPassword = !empty($customPassword) ? $customPassword : $username;
            if ($this->defaultPasswordSetting !== 'username' && empty($customPassword)) {
                $rawPassword = $this->defaultPasswordSetting;
            }

            // Cari ID Kelas jika nama kelas diisi di Excel
            $idKelas = $this->defaultIdKelas;
            if (!empty($namaKelas)) {
                $kelasObj = Kelas::where('nama_kelas', 'like', "%{$namaKelas}%")->first();
                if ($kelasObj) {
                    $idKelas = $kelasObj->id_kelas;
                }
            }

            try {
                DB::transaction(function () use ($namaLengkap, $username, $email, $rawPassword, $idKelas) {
                    $user = User::create([
                        'name' => $namaLengkap,
                        'username' => $username,
                        'email' => $email,
                        'password' => Hash::make($rawPassword),
                        'role' => 'siswa',
                        'status' => 'active',
                    ]);

                    // Sync ke tabel Siswa
                    Siswa::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'username' => $user->username,
                            'password' => $user->password,
                            'nama_siswa' => $user->name,
                            'nis' => is_numeric($username) ? $username : null,
                            'id_kelas' => $idKelas,
                            'status_siswa' => 'aktif',
                        ]
                    );
                });

                $this->importedCount++;
            } catch (\Exception $e) {
                $this->errors[] = "Baris #{$rowNum} ({$namaLengkap}): Gagal membuat akun ({$e->getMessage()}).";
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
