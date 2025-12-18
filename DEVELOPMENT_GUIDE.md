# Panduan Menjalankan Proyek Laravel - Klinik Gigi

## Setup Awal (Hanya Sekali)

### 1. Install Dependencies
```bash
composer install
```

### 2. Setup Environment
```bash
# Copy file .env.example menjadi .env
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Konfigurasi Database (Opsional)
Edit file `.env` dan sesuaikan dengan database Anda:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Jalankan Migration (Jika ada database)
```bash
php artisan migrate
```

---

## Cara Menjalankan Server (Setiap Kali Development)

### Metode 1: Laravel Development Server (RECOMMENDED)
```bash
php artisan serve
```
- Server akan berjalan di: **http://127.0.0.1:8000**
- Tekan `Ctrl+C` untuk menghentikan server

### Metode 2: Menggunakan XAMPP/WAMP
1. Pastikan Apache dan MySQL sudah running
2. Copy folder proyek ke `C:\xampp\htdocs\`
3. Akses via browser: **http://localhost/Tubes/public**

---

## Cara Melihat Perubahan Secara Real-Time

### Untuk CSS/JavaScript:
1. **Hard Refresh Browser** setelah mengubah file CSS:
   - Windows: `Ctrl + F5` atau `Ctrl + Shift + R`
   - Mac: `Cmd + Shift + R`

2. **Disable Cache di Browser** (Untuk Development):
   - Chrome: Buka DevTools (F12) → Network tab → Centang "Disable cache"
   - Firefox: Buka DevTools (F12) → Network tab → Centang "Disable HTTP Cache"

3. **Gunakan Versioning** (Sudah diterapkan):
   - File CSS sudah menggunakan `?v=2` untuk cache busting
   - Ubah angka versi setiap kali ada perubahan besar

### Untuk File Blade/PHP:
- Perubahan akan langsung terlihat setelah refresh browser biasa (F5)
- Tidak perlu restart server

---

## URL Penting

- **Landing Page**: http://127.0.0.1:8000
- **Login**: http://127.0.0.1:8000/login
- **Register**: http://127.0.0.1:8000/register
- **Dashboard**: http://127.0.0.1:8000/dashboard

---

## Tips Development

### 1. Gunakan Browser DevTools
- Tekan `F12` untuk membuka DevTools
- Tab **Elements**: Lihat struktur HTML dan CSS yang diterapkan
- Tab **Console**: Lihat error JavaScript
- Tab **Network**: Lihat file yang dimuat dan statusnya

### 2. Clear Cache Laravel (Jika ada masalah)
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### 3. Lihat Error Log
```bash
# Lihat log Laravel
tail -f storage/logs/laravel.log
```

### 4. Mode Debug
Pastikan di file `.env`:
```
APP_DEBUG=true
APP_ENV=local
```

---

## Workflow Development Ideal

1. **Jalankan Server**:
   ```bash
   php artisan serve
   ```

2. **Buka Browser**:
   - Buka http://127.0.0.1:8000
   - Buka DevTools (F12)
   - Disable cache di Network tab

3. **Edit File**:
   - Edit file CSS di `public/css/`
   - Edit file Blade di `resources/views/`

4. **Lihat Perubahan**:
   - Hard refresh browser (`Ctrl + F5`)
   - Atau refresh biasa jika edit file Blade

5. **Commit Changes** (Opsional):
   ```bash
   git add .
   git commit -m "Update: deskripsi perubahan"
   git push
   ```

---

## Troubleshooting

### Server tidak bisa dijalankan
- Pastikan port 8000 tidak digunakan aplikasi lain
- Gunakan port lain: `php artisan serve --port=8080`

### Perubahan CSS tidak terlihat
- Hard refresh browser (`Ctrl + F5`)
- Clear browser cache
- Ubah versi CSS di file Blade: `?v=3`, `?v=4`, dst

### Error "Class not found"
```bash
composer dump-autoload
```

### Error Database
- Cek konfigurasi `.env`
- Pastikan database sudah dibuat
- Jalankan migration: `php artisan migrate`

---

## Struktur File Penting

```
Tubes/
├── public/
│   ├── css/
│   │   ├── modern.css      # CSS untuk landing page
│   │   └── dashboard.css   # CSS untuk dashboard
│   └── images/             # Gambar/assets
├── resources/
│   └── views/
│       ├── welcome.blade.php        # Landing page
│       ├── layouts/
│       │   └── app.blade.php        # Layout utama
│       ├── auth/
│       │   ├── login.blade.php      # Halaman login
│       │   └── register.blade.php   # Halaman register
│       └── dashboard/
│           └── index.blade.php      # Dashboard
├── routes/
│   └── web.php             # Definisi routes
└── .env                    # Konfigurasi environment
```

---

## Catatan Penting

✅ **Server sudah berjalan di**: http://127.0.0.1:8000
✅ **CSS sudah diperbarui** dengan desain modern
✅ **Cache busting** sudah diterapkan (`?v=2`)

🔥 **Untuk melihat perubahan**: Selalu lakukan **Hard Refresh** (`Ctrl + F5`) setelah mengubah CSS!
