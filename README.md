# Sistem Informasi Keuangan (KeuanganApp)

Aplikasi berbasis web untuk mencatat, mengelola, dan melaporkan arus kas (pemasukan & pengeluaran). Dibangun menggunakan **Laravel**, **Livewire 3**, dan **Tailwind CSS**, aplikasi ini dirancang ringan, interaktif, dan mudah digunakan.

## Fitur Utama

- 📊 **Dashboard Interaktif**: Visualisasi grafik arus kas (Pemasukan vs Pengeluaran) per bulan.
- 💰 **Manajemen Transaksi**: Pencatatan transaksi masuk, keluar, dan saldo awal.
- 🧾 **Kwitansi Cerdas**:
  - Auto-generate nomor kwitansi.
  - Fungsi "Terbilang" otomatis untuk nominal uang (misal: "Satu juta rupiah").
  - Dukungan cetak kwitansi tunggal maupun masal (bulk print).
- 🖨️ **Laporan Keuangan Khusus**:
  - Tampilan cetak laporan (print view) hitam putih yang profesional, sederhana, dan persis seperti standar instansi (tabel saldo otomatis, kolom tanda tangan).
- ⚙️ **Pengaturan Dinamis (Settings)**:
  - Manajemen nama aplikasi, nama ketua, nama bendahara, dan nama kota langsung dari antarmuka (UI).
  - Dilengkapi sistem *Cache* pintar agar tidak membebani database.
- 🏷️ **Kategori & Penerima**: Kelola daftar tipe laporan/kategori dan daftar penerima dana secara dinamis.
- 🔒 **Keamanan (Otentikasi)**: Dilengkapi sistem *Login* dan halaman Kelola Pengguna (CRUD User) untuk membatasi hak akses.

---

## Persyaratan Sistem

Pastikan sistem/server Anda telah menginstal:
- **PHP** >= 8.2
- **Composer** (untuk dependensi PHP)
- **Node.js & NPM** (untuk kompilasi Tailwind CSS)
- **Database Server** (MySQL / SQLite / PostgreSQL)

---

## Cara Instalasi

Ikuti langkah-langkah berikut untuk menjalankan aplikasi ini di komputer lokal Anda:

### 1. Clone & Masuk ke Direktori
```bash
git clone https://github.com/amuadib/keuangan
cd keuangan
```

### 2. Install Dependensi PHP & Node.js
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
Salin file konfigurasi bawaan dan hasilkan kunci aplikasi (App Key).
```bash
cp .env.example .env
php artisan key:generate
```

Buka file `.env` menggunakan teks editor pilihan Anda dan sesuaikan konfigurasi database.
*Contoh menggunakan SQLite (tanpa perlu setup MySQL):*
```env
DB_CONNECTION=sqlite
# Kosongkan DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
```
*(Jika menggunakan SQLite, pastikan file `database/database.sqlite` tersedia).*

### 4. Migrasi & Seed Database
Jalankan migrasi dan seeder untuk membuat seluruh tabel yang dibutuhkan beserta **akun admin bawaan**.
```bash
php artisan migrate --seed
```

### 5. Kompilasi Aset Frontend (Tailwind CSS)
```bash
npm run build
```
*(Atau gunakan `npm run dev` jika Anda ingin melakukan koding/pengembangan).*

### 6. Jalankan Aplikasi
```bash
php artisan serve
```

Aplikasi kini dapat diakses melalui browser Anda di alamat: **`http://127.0.0.1:8000`**

### Kredensial Login Bawaan
Gunakan informasi akun berikut untuk masuk pertama kali:
- **Email**: `admin@admin.com`
- **Password**: `password`
*(Segera ubah password atau buat akun baru di menu Pengguna setelah Anda berhasil masuk!)*

---

## Stack Teknologi

- [Laravel](https://laravel.com) - Framework PHP
- [Livewire](https://livewire.laravel.com/) - Frontend Interaktif
- [Tailwind CSS](https://tailwindcss.com) - Framework Utility-first CSS
- [Alpine.js](https://alpinejs.dev) - Framework JS ringan (bawaan Livewire)
- [Chart.js](https://www.chartjs.org/) - Visualisasi grafik
