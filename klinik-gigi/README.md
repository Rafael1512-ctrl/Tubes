# Klinik Gigi Zenith - Dental Care Management System

Sistem Manajemen Klinik Gigi Zenith adalah aplikasi berbasis web yang dirancang untuk memudahkan operasional klinik, mulai dari pendaftaran pasien, penjadwalan dokter, rekam medis, hingga manajemen pembayaran.

## 🚀 Fitur Utama

### 1. Dashboard Pasien
- **WhatsApp Gateway**: Tombol bantuan langsung menuju customer service.
- **Booking Online**: Membuat janji temu secara mandiri.
- **Riwayat Medis**: Melihat catatan rekam medis pribadi.
- **Notifikasi**: Pengingat jadwal dan pengumuman klinik.

### 2. Dashboard Dokter
- **Antrian Pasien**: Manajemen antrian praktik hari ini.
- **Pusat Rekam Medis**: Input diagnosa, tindakan, dan catatan medis.
- **Jadwal Praktik**: Melihat jadwal kerja mingguan.
- **Notifikasi**: Informasi aktivitas praktek terbaru.

### 3. Dashboard Admin
- **Manajemen User**: Pengaturan akun Pasien, Dokter, dan Admin.
- **Manajemen Jadwal & Booking**: Pengaturan jadwal dokter dan verifikasi janji temu.
- **Manajemen Obat & Stok**: Inventarisasi obat-obatan klinik.
- **Manajemen Tindakan**: Pengaturan jenis layanan dan harga.
- **Keuangan & Laporan**: Laporan pendapatan, pembelian obat, dan invoice pembayaran.
- **Broadcast**: Mengirim pengumuman ke seluruh pasien/dokter.

---

## 🛠️ Prasyarat (Requirements)

Sebelum menjalankan aplikasi, pastikan Anda telah menginstal:
- **PHP** >= 8.2 (Disarankan menggunakan XAMPP terbaru)
- **Composer** (Dependency Manager untuk PHP)
- **MySQL** (XAMPP Control Panel)
- **Web Browser** (Chrome/Edge/Firefox)

---

## ⚙️ Instruksi Instalasi (Setup Pertama Kali)

1. **Clone atau Ekstrak Project**
   Buka terminal atau command prompt, lalu masuk ke folder project:
   ```bash
   cd path/to/klinik-gigi
   ```

2. **Install Dependencies**
   Jalankan perintah berikut untuk mengunduh package yang dibutuhkan:
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` dan sesuaikan pengaturan database Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=dbklinikgigi
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Install & Compile Assets (Opsional)**
   Jika frontend tidak muncul dengan benar, lakukan:
   ```bash
   npm install
   npm run dev
   ```

6. **Persiapan Database**
   - Aktifkan **MySQL** di XAMPP Control Panel.
   - Buka **phpMyAdmin** (`localhost/phpmyadmin`).
   - Buat database baru dengan nama `dbklinikgigi`.
   - Import file `db_mysql.sql` yang ada di root folder project ke database tersebut.

---

## 🏁 Cara Menjalankan Program

Setelah instalasi selesai, ikuti langkah berikut setiap kali ingin menjalankan aplikasi:

1. Pastikan **Apache** dan **MySQL** di XAMPP dalam status **RUNNING**.
2. Jalankan server Laravel dengan perintah:
   ```bash
   php artisan serve
   ```
3. Buka browser dan akses alamat:
   **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔑 Akun Uji Coba (Default Login)

| Role | Username (Phone/Email) | Password |
| :--- | :--- | :--- |
| **Admin** | hansenmaulana10@gmail.com | admin123 |
| **Dokter** | hansenmaulana9@gmail.com | dokter123 |
| **Pasien** | 2472052@gmail.com | pasien123 |

---

## 📁 Struktur Direktori Penting
- `app/Http/Controllers`: Logika bisnis aplikasi.
- `resources/views`: Tampilan UI (Blade Files).
- `routes/web.php`: Daftar rute navigasi.
- `public/images`: Asset gambar yang digunakan.

---
**Zenith Dental** - *Senyum Sehat Anda adalah Prioritas Kami.*
