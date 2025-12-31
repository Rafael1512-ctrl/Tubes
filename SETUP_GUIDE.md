# Setup Guide - Klinik Gigi

## Prasyarat
- PHP 8.2.12 (sudah terinstall di XAMPP)
- Composer 2.9.2 (sudah terinstall)
- MySQL/MariaDB (XAMPP)

## Status Setup ✅
Semua langkah setup sudah selesai dilakukan! Aplikasi siap digunakan.

## Yang Sudah Dilakukan

### 1. ✅ Install Dependencies
```bash
composer install
```
Semua package Laravel dan dependencies sudah terinstall dengan sukses.

### 2. ✅ Konfigurasi Environment
File `.env` sudah dikonfigurasi dengan setting berikut:
- **Database**: MySQL
- **Host**: 127.0.0.1
- **Port**: 3306
- **Database Name**: dbklinikgigi
- **Username**: root
- **Password**: (kosong)

### 3. ✅ Generate Application Key
```bash
php artisan key:generate
```
Application key sudah di-generate untuk enkripsi.

### 4. ✅ Setup Database
- Database `dbklinikgigi` sudah dibuat
- File `db_mysql.sql` sudah diimport
- Koneksi database berhasil

### 5. ✅ Cache Configuration
```bash
php artisan config:cache
```
Konfigurasi sudah di-cache untuk performa optimal.

### 6. ✅ Server Development Berjalan
Server Laravel sudah running di: **http://127.0.0.1:8000**

## Cara Menjalankan Aplikasi

### Start Development Server
```bash
cd d:\TestTekmul\Tubes\klinik-gigi
php artisan serve
```

Aplikasi akan berjalan di: **http://127.0.0.1:8000**

### Menghentikan Server
Tekan `Ctrl + C` di terminal

## Perintah Artisan yang Berguna

### Melihat Status Database
```bash
php artisan migrate:status
```

### Clear Cache (jika diperlukan)
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Membuat Migration Baru
```bash
php artisan make:migration nama_migration
```

### Membuat Controller
```bash
php artisan make:controller NamaController
```

### Membuat Model
```bash
php artisan make:model NamaModel
```

## Struktur Database

Database `dbklinikgigi` memiliki tabel utama:
- **users** - Tabel user untuk autentikasi
- **pasien** - Data pasien
- **pegawai** - Data pegawai (Admin dan Dokter)
- **jadwal** - Jadwal praktik dokter
- **booking** - Booking appointment pasien
- **rekammedis** - Rekam medis pasien
- **obat** - Master data obat
- **tindakan** - Master data tindakan medis
- **pembayaran** - Data pembayaran

## Akun Default (dari database)

### Admin
- PegawaiID: A-001
- Nama: Hans Maulana Budiputra
- User ID: 1

### Dokter Gigi
- PegawaiID: D-001
- Nama: Rafael
- User ID: 2

### Pasien
- PasienID: P-2025-00001
- Nama: Errvin Junius
- User ID: 3

## Troubleshooting

### Jika ada error koneksi database:
1. Pastikan MySQL di XAMPP sudah running
2. Check credentials di file `.env`
3. Jalankan: `php artisan config:clear`

### Jika ada error permission:
```bash
# Di PowerShell (Run as Administrator)
icacls storage /grant Users:F /t
icacls bootstrap/cache /grant Users:F /t
```

### Jika perlu re-import database:
```bash
# Drop database dulu
C:\xampp\mysql\bin\mysql.exe -u root -e "DROP DATABASE dbklinikgigi;"

# Buat database baru
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE dbklinikgigi;"

# Import SQL file
C:\xampp\mysql\bin\mysql.exe -u root dbklinikgigi < db_mysql.sql
```

## Notes
- Migration `2025_12_26_162100_add_user_id_to_pasien_table` masih pending, tapi database sudah memiliki struktur yang benar dari file SQL
- Aplikasi menggunakan Laravel 12.44.0
- Menggunakan stored procedures untuk operasi database tertentu

## Next Steps
1. Buat routes untuk halaman-halaman aplikasi
2. Setup authentication jika belum ada
3. Develop frontend views
4. Test fitur-fitur aplikasi

---
**Status**: ✅ Aplikasi siap untuk development!
**Server**: http://127.0.0.1:8000
