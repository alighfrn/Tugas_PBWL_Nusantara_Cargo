<?php
/**
 * Nusantara Cargo Express (NCE)
 * Front Controller & Routing Sentral
 * 
 * Mata Kuliah : Pemrograman Berbasis Web Lanjutan (PBWL)
 * Dosen       : Suendri, M.Kom
 * Kampus      : UIN Sumatera Utara Medan
 */

declare(strict_types=1);

// Load Konfigurasi & Koneksi Database
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';

// Load Model Classes (OOP Layer)
require_once __DIR__ . '/classes/BaseModel.php';
require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/classes/Pelanggan.php';
require_once __DIR__ . '/classes/Layanan.php';
require_once __DIR__ . '/classes/Transaksi.php';

// Ambil parameter rute dari URL (?page=...)
$page = isset($_GET['page']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', (string)$_GET['page']) : 'dashboard';

// Daftar Pemetaan Rute Sistem
$routes = [
    'login'           => __DIR__ . '/pages/login.php',
    'logout'          => __DIR__ . '/pages/logout.php',
    'dashboard'       => __DIR__ . '/pages/dashboard.php',
    'pelanggan'       => __DIR__ . '/pages/pelanggan.php',
    'layanan'         => __DIR__ . '/pages/layanan.php',
    'transaksi'       => __DIR__ . '/pages/transaksi.php',
    'transaksi_cetak' => __DIR__ . '/pages/transaksi_cetak.php',
    'users'           => __DIR__ . '/pages/users.php',
];

// Validasi Rute & Eksekusi Halaman
if (array_key_exists($page, $routes) && file_exists($routes[$page])) {
    require_once $routes[$page];
} else {
    // Jika rute tidak sah
    if (!is_logged_in()) {
        header('Location: index.php?page=login');
    } else {
        set_flash('warning', 'Halaman yang Anda tuju tidak ditemukan. Anda telah dialihkan ke Dashboard.');
        header('Location: index.php?page=dashboard');
    }
    exit;
}