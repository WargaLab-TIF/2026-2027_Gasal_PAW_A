-- =====================================================
-- Database TP8 (Modul 8: Session & Security)
-- Import lewat phpMyAdmin (tab Import) atau: mysql -u root < database.sql
-- =====================================================
CREATE DATABASE IF NOT EXISTS TP8 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE TP8;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS transaksi;
DROP TABLE IF EXISTS barang;
DROP TABLE IF EXISTS supplier;
DROP TABLE IF EXISTS pelanggan;
DROP TABLE IF EXISTS `user`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `user` (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50)  NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,          -- disimpan sebagai hash (password_hash)
    nama     VARCHAR(100) NOT NULL,
    level    TINYINT      NOT NULL DEFAULT 2  -- 1 = owner, 2 = kasir
) ENGINE=InnoDB;

CREATE TABLE barang (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(20)  NOT NULL,
    nama_barang VARCHAR(100) NOT NULL,
    harga       INT UNSIGNED NOT NULL DEFAULT 0,
    stok        INT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE supplier (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    nama_supplier VARCHAR(100) NOT NULL,
    alamat        VARCHAR(255) NULL,
    telepon       VARCHAR(20)  NULL
) ENGINE=InnoDB;

CREATE TABLE pelanggan (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(100) NOT NULL,
    alamat         VARCHAR(255) NULL,
    telepon        VARCHAR(20)  NULL
) ENGINE=InnoDB;

CREATE TABLE transaksi (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    tanggal      DATE NOT NULL,
    pelanggan_id INT NULL,
    total        INT UNSIGNED NOT NULL DEFAULT 0,
    FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------- Data awal ----------
-- owner : username "owner" / password "owner123"
-- kasir : username "kasir" / password "kasir123"
INSERT INTO `user` (username, password, nama, level) VALUES
('owner', '$2y$12$wpVayRIaw5snF7NaoAwWoORkFqEv5r/LFRao6f6lP8BdMIBTUbCry', 'Owner Toko', 1),
('kasir', '$2y$12$0SOrRwUp3yIr9TtAtqnhv.Xuwr57qTj6LoARd4uMDoI1.SBT.sQd6', 'Kasir Toko', 2);

INSERT INTO barang (kode_barang, nama_barang, harga, stok) VALUES
('BRG01', 'Sabun Mandi', 5000, 50),
('BRG02', 'Shampoo', 15000, 30);

INSERT INTO supplier (nama_supplier, alamat, telepon) VALUES
('PT Sumber Pangan', 'Jl. Raya Darmo 10, Surabaya', '031-5551234'),
('CV Maju Jaya', 'Jl. Ahmad Yani 45, Sidoarjo', '031-8885678');

INSERT INTO pelanggan (nama_pelanggan, alamat, telepon) VALUES
('Siti Aminah', 'Jl. Mawar 3, Bangkalan', '081234567890'),
('Agus Santoso', 'Jl. Melati 8, Bangkalan', '082198765432');

INSERT INTO transaksi (tanggal, pelanggan_id, total) VALUES
('2025-01-01', 1, 30000),
('2025-01-02', NULL, 45000);
