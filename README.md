# Nusantara Cargo Express (NCE) 🚚📦
### Sistem Informasi Manajemen Ekspedisi & Kargo Berbasis Web (PHP OOP & MySQL PDO)

Aplikasi web sistem informasi operasional ekspedisi dan kargo (**Nusantara Cargo Express**) yang dibangun menggunakan konsep **Object-Oriented Programming (OOP)** murni dengan koneksi database **PDO Singleton Pattern** dan arsitektur **Front Controller Routing**.

Proyek ini disusun untuk memenuhi tugas mata kuliah **Pemrograman Berbasis Web Lanjutan (PBWL)**.

---

## 🎯 Fitur Utama

- 🔐 **Autentikasi & Otorisasi:**
  - Login & Logout dengan enkripsi keamanan password hash (`password_hash` / `password_verify`).
  - Session guard untuk melindungi rute dan data internal sistem.
- 👥 **Manajemen Pengguna (Users):**
  - CRUD Akun Admin dan Operator loket dengan kontrol status akun (Aktif / Non-aktif).
- 📦 **Manajemen Layanan Kargo:**
  - Pengaturan jenis paket pengiriman (Kargo Darat Reguler, Kilat Udara, Truk Muatan Besar, Same Day Delivery) beserta tarif harga per-Kg.
- 🏢 **Manajemen Data Pelanggan:**
  - Pencatatan dan kelola data pengirim/pelanggan (Nama, Kontak, dan Alamat).
- 📝 **Manajemen Transaksi Pengiriman:**
  - Pembuatan resi pengiriman kargo baru dengan kalkulasi biaya otomatis berdasarkan bobot/berat (Kg) dan jenis layanan.
  - Estimasi tanggal masuk dan selesai pengiriman.
- 🖨️ **Cetak Resi & Faktur Pengiriman:**
  - Cetak bukti resi pengiriman kargo dengan tampilan rapi, siap cetak (print-ready) dan barcode/informasi lengkap.
- 📊 **Dashboard Analitik:**
  - Ringkasan total transaksi, total pelanggan, jumlah armada/layanan aktif, dan monitoring aktivitas terbaru.

---

## 🛠️ Teknologi & Arsitektur

- **Bahasa Pemrograman:** PHP 8+ (Pure OOP)
- **Database Engine:** MySQL / MariaDB
- **Database Driver:** PHP Data Objects (PDO) dengan *Singleton Pattern*
- **Arsitektur:** Front Controller (`index.php?page=...`) & Model-View Layer Sederhana
- **Frontend & UI:** HTML5, CSS3, JavaScript, FontAwesome Icons

---

## 📂 Struktur Direktori

```text
Tugas_PBWL_Nusantara_Cargo/
├── assets/                  # File statis (CSS, JavaScript, Gambar)
│   ├── css/
│   ├── js/
│   └── images/
├── classes/                 # Lapisan Model OOP
│   ├── BaseModel.php        # Abstract base model
│   ├── Layanan.php          # Model data layanan kargo
│   ├── Pelanggan.php        # Model data pelanggan
│   ├── Transaksi.php        # Model logika transaksi & resi
│   └── User.php             # Model autentikasi & pengguna
├── config/                  # Konfigurasi sistem & koneksi database
│   ├── config.php           # Pengaturan variabel global & helper
│   └── Database.php         # Koneksi database PDO (Singleton)
├── includes/                # Komponen layout modular
│   ├── header.php
│   ├── sidebar.php
│   ├── footer.php
│   └── alert.php
├── pages/                   # Modul controller & view halaman
│   ├── dashboard.php
│   ├── layanan.php
│   ├── pelanggan.php
│   ├── transaksi.php
│   ├── transaksi_cetak.php
│   ├── users.php
│   ├── login.php
│   └── logout.php
├── database.sql             # Skema tabel database dan data seeder
├── index.php                # Front controller & central router
└── README.md
```

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

### 1. Prasyarat Sistem
- Web Server: **Apache** (disarankan menggunakan XAMPP / Laragon).
- PHP Version: **PHP 8.0** atau lebih baru.
- MySQL / MariaDB Server.

### 2. Langkah Setup
1. **Clone atau Pindahkan Folder Proyek:**
   Pindahkan folder `Tugas_PBWL_Nusantara_Cargo` ke direktori web server Anda:
   - XAMPP: `C:/xampp/htdocs/Tugas_PBWL_Nusantara_Cargo`
   - Laragon: `C:/laragon/www/Tugas_PBWL_Nusantara_Cargo`

2. **Import Database:**
   - Jalankan MySQL di XAMPP / Laragon.
   - Buka **phpMyAdmin** (`http://localhost/phpmyadmin`).
   - Buat database baru bernama: `crud-oop-php` (atau biarkan query otomatis membuatnya).
   - Import file **`database.sql`** yang ada di root folder proyek ini.

3. **Konfigurasi Database (Jika Diperlukan):**
   Jika menggunakan port atau password MySQL yang berbeda, sesuaikan di file `config/config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_PORT', '3306');
   define('DB_NAME', 'crud-oop-php');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

4. **Akses Aplikasi:**
   Buka browser dan kunjungi:
   ```text
   http://localhost/Tugas_PBWL_Nusantara_Cargo/
   ```

---

## 🔑 Akun Login Default

| Role | Username | Password |
| :--- | :--- | :--- |
| **Administrator Pusat** | `admin` | `adminpassword` |
| **Operator Loket** | `operator` | `adminpassword` |

---
