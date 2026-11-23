<?php
/**
 * Nusantara Cargo Express (NCE)
 * Database Connection (PDO Singleton Pattern)
 */

declare(strict_types=1);

class Database {
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                die("
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 20px; border: 1px solid #f87171; background: #fef2f2; border-radius: 8px;'>
                        <h3 style='color: #991b1b; margin-top: 0;'>Koneksi Database Gagal!</h3>
                        <p style='color: #4b5563;'>Pastikan MySQL di XAMPP sudah berjalan dan database <strong>" . htmlspecialchars(DB_NAME) . "</strong> sudah di-import.</p>
                        <p style='color: #6b7280; font-size: 13px;'>Pesan Kesalahan: <code>" . htmlspecialchars($e->getMessage()) . "</code></p>
                    </div>
                ");
            }
        }

        return self::$instance;
    }
}