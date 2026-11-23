<?php
/**
 * Nusantara Cargo Express (NCE)
 * Konfigurasi Utama & Helper Sistem
 */

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Jakarta');

// Pengaturan Konfigurasi Aplikasi
define('APP_NAME', 'Nusantara Cargo Express');
define('APP_SHORT', 'NCE');
define('APP_TAGLINE', 'Sistem Informasi Manajemen Ekspedisi & Kargo');
define('APP_VERSION', '1.0');

// Konfigurasi Database MySQL
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'crud-oop-php');
define('DB_USER', 'root');
define('DB_PASS', '');

// Base URL deteksi dinamis
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$baseUrl = rtrim($protocol . $host . $scriptDir, '/');
define('BASE_URL', $baseUrl);

/**
 * Helper URL
 */
function base_url(string $path = ''): string {
    return BASE_URL . ($path ? '/' . ltrim($path, '/') : '');
}

/**
 * Flash Message Notification
 */
function set_flash(string $type, string $message): void {
    $_SESSION['flash_message'] = [
        'type' => $type, // success, danger, warning, info
        'message' => $message
    ];
}

function get_flash(): ?array {
    if (isset($_SESSION['flash_message'])) {
        $msg = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $msg;
    }
    return null;
}

/**
 * Autentikasi & Hak Akses (Role-Based Access Control)
 */
function is_logged_in(): bool {
    return isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
}

function current_user(): ?array {
    return $_SESSION['user_data'] ?? null;
}

function require_login(): void {
    if (!is_logged_in()) {
        set_flash('warning', 'Silakan login terlebih dahulu untuk mengakses sistem.');
        header('Location: index.php?page=login');
        exit;
    }
}

/**
 * Menentukan Hak Akses Administrator
 * Di tabel database tidak ada kolom role, sehingga akun ber-username 'admin' atau user_id 1 adalah Administrator.
 */
function is_admin(): bool {
    $user = current_user();
    if (!$user) return false;
    return strtolower((string)$user['user_name']) === 'admin' || (int)$user['user_id'] === 1;
}

function require_admin(): void {
    require_login();
    if (!is_admin()) {
        set_flash('danger', 'Akses ditolak! Menu tersebut hanya boleh diakses oleh Administrator Pusat.');
        header('Location: index.php?page=dashboard');
        exit;
    }
}

/**
 * Formatting Helper
 */
function format_rupiah(int|float $angka): string {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function format_tanggal(?string $date): string {
    if (!$date) return '-';
    $timestamp = strtotime($date);
    if (!$timestamp) return $date;
    
    $bulanIndo = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $d = date('d', $timestamp);
    $m = (int)date('m', $timestamp);
    $y = date('Y', $timestamp);
    
    return $d . ' ' . $bulanIndo[$m] . ' ' . $y;
}

/**
 * Helper Status Pengiriman Kargo
 * Menghitung status apakah barang masih 'Dalam Perjalanan' atau 'Terkirim'
 */
function get_status_kargo(string $tglSelesai): array {
    $today = date('Y-m-d');
    if ($today >= $tglSelesai) {
        return [
            'status'       => 'Terkirim',
            'is_delivered' => true,
            'label'        => 'Terkirim',
            'badge'        => 'badge-soft-success',
            'icon'         => 'bi-check-circle-fill'
        ];
    } else {
        return [
            'status'       => 'Dalam Pengiriman',
            'is_delivered' => false,
            'label'        => 'Dalam Perjalanan',
            'badge'        => 'badge-soft-primary',
            'icon'         => 'bi-truck'
        ];
    }
}