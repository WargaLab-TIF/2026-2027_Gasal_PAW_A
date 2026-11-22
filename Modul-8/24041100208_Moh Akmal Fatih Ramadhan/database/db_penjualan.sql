-- =====================================
-- DATABASE MODUL 8 SISTEM PENJUALAN
-- =====================================

CREATE DATABASE IF NOT EXISTS db_penjualan
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE db_penjualan;

-- Menonaktifkan pengecekan foreign key
SET FOREIGN_KEY_CHECKS = 0;

-- Menghapus tabel lama
DROP TABLE IF EXISTS pembayaran;
DROP TABLE IF EXISTS transaksi_detail;
DROP TABLE IF EXISTS transaksi;
DROP TABLE IF EXISTS barang;
DROP TABLE IF EXISTS supplier;
DROP TABLE IF EXISTS pelanggan;
DROP TABLE IF EXISTS user;

SET FOREIGN_KEY_CHECKS = 1;


-- =====================================
-- 1. TABEL USER
-- =====================================

CREATE TABLE user (

    id INT AUTO_INCREMENT PRIMARY KEY,

    nama VARCHAR(100) NOT NULL,

    username VARCHAR(50) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    level TINYINT NOT NULL
    COMMENT '1=Owner, 2=Kasir',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB;


-- =====================================
-- 2. TABEL SUPPLIER
-- =====================================

CREATE TABLE supplier (

    id INT AUTO_INCREMENT PRIMARY KEY,

    nama_supplier VARCHAR(100) NOT NULL,

    alamat TEXT NULL,

    telepon VARCHAR(25) NULL

) ENGINE=InnoDB;


-- =====================================
-- 3. TABEL PELANGGAN
-- =====================================

CREATE TABLE pelanggan (

    id INT AUTO_INCREMENT PRIMARY KEY,

    nama_pelanggan VARCHAR(100) NOT NULL,

    alamat TEXT NULL,

    telepon VARCHAR(25) NULL

) ENGINE=InnoDB;


-- =====================================
-- 4. TABEL BARANG
-- =====================================

CREATE TABLE barang (

    id INT AUTO_INCREMENT PRIMARY KEY,

    kode_barang VARCHAR(30) NOT NULL UNIQUE,

    nama_barang VARCHAR(100) NOT NULL,

    harga DECIMAL(15,2) NOT NULL DEFAULT 0,

    stok INT NOT NULL DEFAULT 0,

    supplier_id INT NULL,

    CONSTRAINT fk_barang_supplier

    FOREIGN KEY (supplier_id)

    REFERENCES supplier(id)

    ON UPDATE CASCADE

    ON DELETE SET NULL

) ENGINE=InnoDB;


-- =====================================
-- 5. TABEL TRANSAKSI
-- =====================================

CREATE TABLE transaksi (

    id INT AUTO_INCREMENT PRIMARY KEY,

    pelanggan_id INT NULL,

    user_id INT NOT NULL,

    waktu_transaksi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    total DECIMAL(15,2) NOT NULL DEFAULT 0,

    CONSTRAINT fk_transaksi_pelanggan

    FOREIGN KEY (pelanggan_id)

    REFERENCES pelanggan(id)

    ON UPDATE CASCADE

    ON DELETE SET NULL,

    CONSTRAINT fk_transaksi_user

    FOREIGN KEY (user_id)

    REFERENCES user(id)

    ON UPDATE CASCADE

    ON DELETE RESTRICT

) ENGINE=InnoDB;


-- =====================================
-- 6. TABEL TRANSAKSI DETAIL
-- =====================================

CREATE TABLE transaksi_detail (

    id INT AUTO_INCREMENT PRIMARY KEY,

    transaksi_id INT NOT NULL,

    barang_id INT NOT NULL,

    harga DECIMAL(15,2) NOT NULL,

    qty INT NOT NULL,

    subtotal DECIMAL(15,2) NOT NULL,

    CONSTRAINT fk_detail_transaksi

    FOREIGN KEY (transaksi_id)

    REFERENCES transaksi(id)

    ON UPDATE CASCADE

    ON DELETE CASCADE,

    CONSTRAINT fk_detail_barang

    FOREIGN KEY (barang_id)

    REFERENCES barang(id)

    ON UPDATE CASCADE

    ON DELETE RESTRICT

) ENGINE=InnoDB;


-- =====================================
-- 7. TABEL PEMBAYARAN
-- =====================================

CREATE TABLE pembayaran (

    id INT AUTO_INCREMENT PRIMARY KEY,

    transaksi_id INT NOT NULL,

    jumlah_bayar DECIMAL(15,2) NOT NULL,

    metode VARCHAR(30) NOT NULL DEFAULT 'Tunai',

    waktu_bayar DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_pembayaran_transaksi

    FOREIGN KEY (transaksi_id)

    REFERENCES transaksi(id)

    ON UPDATE CASCADE

    ON DELETE CASCADE

) ENGINE=InnoDB;


-- =====================================
-- CONTOH DATA SUPPLIER
-- =====================================

INSERT INTO supplier
(nama_supplier, alamat, telepon)

VALUES

('PT Sumber Makmur', 'Bangkalan', '081234567890');


-- =====================================
-- CONTOH DATA PELANGGAN
-- =====================================

INSERT INTO pelanggan
(nama_pelanggan, alamat, telepon)

VALUES

('Pelanggan Umum', '-', '-'),

('Andi', 'Bangkalan', '081234567891');


-- =====================================
-- CONTOH DATA BARANG
-- =====================================

INSERT INTO barang
(kode_barang, nama_barang, harga, stok, supplier_id)

VALUES

('BRG001', 'Buku Tulis', 5000, 100, 1),

('BRG002', 'Pulpen', 3000, 100, 1),

('BRG003', 'Pensil', 2000, 100, 1);
