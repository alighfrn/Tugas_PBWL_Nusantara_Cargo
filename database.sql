-- ==============================================
-- SISTEM INFORMASI NUSANTARA CARGO EXPRESS (NCE)
-- ==============================================

CREATE DATABASE IF NOT EXISTS `crud-oop-php` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `crud-oop-php`;

-- --------------------------------------------------------
-- 1. Tabel Pelanggan (tb_pelanggan)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tb_transaksi`;
DROP TABLE IF EXISTS `tb_pelanggan`;
CREATE TABLE `tb_pelanggan` (
  `pel_id` INT(11) NOT NULL AUTO_INCREMENT,
  `pel_nama` VARCHAR(100) NOT NULL,
  `pel_alamat` TEXT NULL,
  `pel_hp` VARCHAR(30) NULL,
  PRIMARY KEY (`pel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 2. Tabel Layanan (tb_layanan)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tb_layanan`;
CREATE TABLE `tb_layanan` (
  `lay_id` INT(11) NOT NULL AUTO_INCREMENT,
  `lay_jenis` VARCHAR(100) NOT NULL,
  `lay_harga` INT(11) NOT NULL,
  PRIMARY KEY (`lay_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 3. Tabel Users (tb_users)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tb_users`;
CREATE TABLE `tb_users` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_name` VARCHAR(50) NOT NULL UNIQUE,
  `user_password` VARCHAR(256) NOT NULL,
  `user_namalengkap` VARCHAR(100) NOT NULL,
  `user_aktif` TINYINT(3) NOT NULL DEFAULT 1,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 4. Tabel Transaksi (tb_transaksi)
-- --------------------------------------------------------
CREATE TABLE `tb_transaksi` (
  `trans_id` INT(11) NOT NULL AUTO_INCREMENT,
  `trans_id_pelanggan` INT(11) NOT NULL,
  `trans_id_layanan` INT(11) NOT NULL,
  `trans_tgl_masuk` DATE NOT NULL,
  `trans_tgl_selesai` DATE NOT NULL,
  `trans_berat` INT(11) NOT NULL,
  `trans_total` INT(11) NOT NULL,
  PRIMARY KEY (`trans_id`),
  CONSTRAINT `fk_transaksi_pelanggan` 
    FOREIGN KEY (`trans_id_pelanggan`) REFERENCES `tb_pelanggan` (`pel_id`) 
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_transaksi_layanan` 
    FOREIGN KEY (`trans_id_layanan`) REFERENCES `tb_layanan` (`lay_id`) 
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Data Awal / Seeder
-- --------------------------------------------------------

-- Akun Default:
-- 1. Username: admin      | Password: adminpassword
-- 2. Username: operator   | Password: adminpassword
INSERT INTO `tb_users` (`user_name`, `user_password`, `user_namalengkap`, `user_aktif`) VALUES
('admin', '$2y$10$j2pz7hD0BGbtzxTbg.moSu4869jkHM5LhuldB2WVCBMdYDqpzyMpy', 'Administrator Pusat NCE', 1),
('operator', '$2y$10$j2pz7hD0BGbtzxTbg.moSu4869jkHM5LhuldB2WVCBMdYDqpzyMpy', 'Operator Loket Medan', 1);

-- Data Paket Layanan Ekspedisi Kargo
INSERT INTO `tb_layanan` (`lay_jenis`, `lay_harga`) VALUES
('Kargo Darat Reguler (3-4 Hari)', 7000),
('Kargo Kilat Express Udara (1-2 Hari)', 22000),
('Kargo Muatan Besar / Truk (>20 Kg)', 5000),
('Same Day Delivery (Khusus Dalam Kota)', 15000);

-- Data Pelanggan / Pengirim
INSERT INTO `tb_pelanggan` (`pel_nama`, `pel_alamat`, `pel_hp`) VALUES
('CV. Berkah Mandiri Logistik', 'Jl. Sutomo No. 120, Medan Timur', '081234567890'),
('PT. Sentosa Distribusi Sumatera', 'Jl. Krakatau Raya No. 45, Medan', '085298765432'),
('Ali Ghufron', 'Jl. William Iskandar No. 88, Percut Sei Tuan', '082167890123');

-- Data Transaksi Resi Kargo
INSERT INTO `tb_transaksi` (`trans_id_pelanggan`, `trans_id_layanan`, `trans_tgl_masuk`, `trans_tgl_selesai`, `trans_berat`, `trans_total`) VALUES
(1, 1, '2026-11-05', '2026-11-09', 15, 105000),
(2, 2, '2026-11-08', '2026-11-09', 5, 110000),
(3, 3, '2026-11-10', '2026-11-15', 30, 150000);