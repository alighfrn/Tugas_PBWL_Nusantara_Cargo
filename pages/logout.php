<?php
/**
 * Nusantara Cargo Express (NCE)
 * Halaman Logout
 */

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];
session_destroy();

// Mulai sesi baru khusus flash message
session_start();
set_flash('info', 'Anda telah berhasil keluar dari sistem. Terima kasih!');
header('Location: index.php?page=login');
exit;