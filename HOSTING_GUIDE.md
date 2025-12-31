# Panduan Hosting Klinik Gigi

Dokumen ini menjelaskan cara menghosting aplikasi Laravel Klinik Gigi dengan mudah menggunakan berbagai metode.

## 1. Hosting Menggunakan Docker (Rekomendasi)
Ini adalah cara termudah jika Anda memiliki VPS (Virtual Private Server) seperti DigitalOcean, Linode, atau AWS.

### Prasyarat:
- Sudah terinstal Docker dan Docker Compose di server.

### Langkah-langkah:
1. Salin seluruh folder project ke server.
2. Buat file `.env` (copy dari `.env.example`).
3. Jalankan perintah:
   ```bash
   docker-compose up -d --build
   ```
4. Aplikasi akan berjalan di port `8000`.

---

## 2. Hosting Tradisional di Shared Hosting (cPanel)
Jika Anda menggunakan hosting murah dengan cPanel:

1. **Persiapan Lokal:**
   - Jalankan `npm run build` di komputer lokal Anda.
2. **Upload:**
   - Kompres semua file (kecuali folder `node_modules` dan `tests`) menjadi `.zip`.
   - Upload ke File Manager di cPanel.
   - Ekstrak di luar folder `public_html` (untuk keamanan).
3. **Konfigurasi Public:**
   - Pindahkan isi dari folder `public` ke dalam folder `public_html`.
   - Edit `index.php` di `public_html` dan sesuaikan path ke `vendor/autoload.php` dan `bootstrap/app.php`.
4. **Database:**
   - Buat database di MySQL Databases cPanel.
   - Sesuaikan konfigurasi di file `.env`.

---

## 3. Otomatisasi dengan GitHub Actions
Kami telah menyiapkan workflow di `.github/workflows/deploy.yml`.

### Cara Mengaktifkan:
1. Push project Anda ke GitHub.
2. Buka Tab **Settings > Secrets and variables > Actions** di GitHub.
3. Tambahkan secret berikut:
   - `HOST`: IP Address server Anda.
   - `USERNAME`: Username SSH Anda.
   - `SSH_PRIVATE_KEY`: Private key SSH Anda.
4. Setiap kali Anda melakukan `git push` ke branch `main`, GitHub akan otomatis mencoba mendeploy ke server.

---

## 4. Script Deployment (`deploy.sh`)
Gunakan script ini di VPS untuk update aplikasi secara manual dengan satu perintah:
```bash
./deploy.sh
```

---

## Tips Keamanan:
- Pastikan `APP_DEBUG=false` di file `.env` saat live.
- Selalu jalankan `php artisan config:cache` dan `php artisan route:cache` setelah update di server untuk performa terbaik.
