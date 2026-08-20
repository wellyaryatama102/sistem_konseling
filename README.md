# Sistem Informasi Konseling Siswa Terintegrasi Notifikasi WhatsApp — SMKN 2 Guguak

Sistem Informasi Konseling berbasis web yang dirancang khusus untuk **SMK Negeri 2 Guguak**. Sistem ini terintegrasi penuh dengan WhatsApp Gateway service abstraction dan mendukung 6 peran pengguna aktif + 1 aktor eksternal (Orang Tua/Wali).

---

## 🚀 Fitur Utama & Peran Pengguna

| Role | Hak Akses Utama |
| --- | --- |
| **Admin** | Kelola akun pengguna, atur role, status aktif/nonaktif, reset password, audit log. |
| **Guru BK** | Atur data kelas, buat slot jadwal konseling, validasi pengajuan siswa, catat konseling insidental & hasil konseling, kelola riwayat perkembangan historis, buat & kirim Surat Panggilan via WA (PDF), ekspor laporan rekapitulasi (PDF & Excel). |
| **Siswa** | Wajib lengkapi profil (NIS, NISN, WA Ortu/Wali), cari slot konseling tersedia, ajukan jadwal & alasan konseling, ubah/batalkan pengajuan. |
| **Wali Kelas** | Pemantauan siswa kelas yang diampu, rujukan laporan konseling insidental ke Guru BK. |
| **Wakasis** | Pemantauan perkembangan siswa seluruh kelas (multiclass), filter & ekspor laporan PDF/Excel. |
| **Kepala Sekolah** | Dashboard eksekutif agregat (total sesi, sesi selesai, insidental, surat panggilan, statistik per kelas), ekspor laporan PDF & Excel. *(Informasi rahasia catatan konseling individual dilindungi)*. |
| **Orang Tua/Wali** | *Tanpa Akun Login*. Menerima notifikasi otomatis & Surat Panggilan Orang Tua resmi via WhatsApp Gateway. |

---

## 🔑 Akun Demo Development / Testing

Semua akun demo di bawah ini telah disiapkan melalui database seeder (`php artisan db:seed`):

| Role | Username | Password |
| --- | --- | --- |
| **Admin** | `admin` | `password` |
| **Guru BK** | `gurubk` | `password` |
| **Wali Kelas 1 (X PPLG 1)** | `walikelas1` | `password` |
| **Wali Kelas 2 (XI AKL 2)** | `walikelas2` | `password` |
| **Siswa 1 (Andi Saputra)** | `siswa1` | `password` |
| **Siswa 2 (Budi Santoso)** | `siswa2` | `password` |
| **Wakasis** | `wakasis` | `password` |
| **Kepala Sekolah** | `kepsek` | `password` |

---

## 🛠️ Persyaratan Sistem (System Requirements)

- **PHP**: `8.2` atau `8.3` (Extension terpasang: `pdo_mysql`, `fileinfo`, `gd`, `mbstring`, `openssl`)
- **Web Server / Environment**: XAMPP / Laragon / Apache
- **Database**: MySQL 5.7+ atau MariaDB
- **Composer**: Version 2.x

---

## ⚙️ Petunjuk Instalasi Local (XAMPP / Laragon)

1. **Clone / Buka Direktori Project**:
   ```bash
   cd C:\laragon\www\sistem_konseling
   ```

2. **Konfigurasi Environment `.env`**:
   Pastikan file `.env` mengarah ke database MySQL Anda:
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sistem_konseling
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. **Jalankan Migrasi Database & Seeder**:
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Jalankan Aplikasi Server**:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses di browser melalui URL `http://127.0.0.1:8000`.

---

## 💬 Konfigurasi WhatsApp Gateway Service Abstraction

Sistem menggunakan arsitektur **WhatsApp Gateway Service Abstraction** (`WhatsAppGatewayInterface`). Provider dapat dikonfigurasi melalui file `.env`:

```ini
# Mode Simulasi Log (Development)
WHATSAPP_GATEWAY_ENABLED=false
WHATSAPP_GATEWAY_PROVIDER=log

# Mode Production API (Fonnte)
# WHATSAPP_GATEWAY_ENABLED=true
# WHATSAPP_GATEWAY_PROVIDER=fonnte
# WHATSAPP_GATEWAY_URL=https://api.fonnte.com/send
# WHATSAPP_GATEWAY_TOKEN=YOUR_FONNTE_API_TOKEN
```

- Ketika `WHATSAPP_GATEWAY_ENABLED=false`, notifikasi tidak melakukan HTTP request luar dan dicatat secara aman dalam tabel `wa_logs` serta log Laravel (`storage/logs/laravel.log`).
- Log detail status pengiriman WhatsApp dapat diaudit dari database `wa_logs`.

---

## ⏰ Pengingat Otomatis H-1 (Laravel Scheduler)

Untuk mengirimkan notifikasi pengingat H-1 jadwal konseling secara otomatis kepada siswa via WhatsApp:

```bash
php artisan reminders:send
```

Untuk menjalankan scheduler secara otomatis di XAMPP / Local Development:
```bash
php artisan schedule:work
```

---

## 🧪 Pengujian Otomatis (Automated Testing)

Uji seluruh alur sistem dengan menjalankan PHPUnit / Artisan test:

```bash
php artisan test
```

Semua 5 pengujian (Seeder DB, Redirection Auth, Block Unauthorized RBAC, Response Status) dijamin **PASS 100%**.
