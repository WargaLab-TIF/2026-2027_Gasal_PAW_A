CREATE DATABASE IF NOT EXISTS paw_modul8 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE paw_modul8;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS detail_transaksi;
DROP TABLE IF EXISTS transaksi;
DROP TABLE IF EXISTS pelanggan;
DROP TABLE IF EXISTS supplier;
DROP TABLE IF EXISTS barang;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nama VARCHAR(100) NOT NULL,
 username VARCHAR(50) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 level TINYINT NOT NULL COMMENT '1=Owner, 2=Kasir',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE barang (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nama_barang VARCHAR(120) NOT NULL,
 harga DECIMAL(12,2) NOT NULL DEFAULT 0,
 stok INT NOT NULL DEFAULT 0
);

CREATE TABLE supplier (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nama_supplier VARCHAR(120) NOT NULL,
 telepon VARCHAR(30),
 alamat TEXT
);

CREATE TABLE pelanggan (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nama_pelanggan VARCHAR(120) NOT NULL,
 telepon VARCHAR(30),
 alamat TEXT
);

CREATE TABLE transaksi (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 pelanggan_id INT NULL,
 total DECIMAL(12,2) NOT NULL DEFAULT 0,
 tanggal DATETIME NOT NULL,
 FOREIGN KEY (user_id) REFERENCES users(id),
 FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id) ON DELETE SET NULL
);

CREATE TABLE detail_transaksi (
 id INT AUTO_INCREMENT PRIMARY KEY,
 transaksi_id INT NOT NULL,
 barang_id INT NOT NULL,
 qty INT NOT NULL,
 harga DECIMAL(12,2) NOT NULL,
 FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE,
 FOREIGN KEY (barang_id) REFERENCES barang(id)
);

-- Akun demo: owner/owner123 dan kasir/kasir123
INSERT INTO users (id,nama,username,password,level) VALUES
(1,'Pemilik Toko','owner','$2y$12$TOEpKMTlbrDg1ehVi8zaye5P5qpLzIP0fEO3o1rDtxTfAuQA/xTWG',1),
(2,'Kasir Toko','kasir','$2y$12$rrb5uoPt0zqBZd8sdK68T.HjB7ugHF05woPAFCALAPljpJS1D9CGi',2);

INSERT INTO barang (id,nama_barang,harga,stok) VALUES
(1,'Pulpen',3000,38),
(2,'Buku Tulis',5000,29),
(3,'Penghapus',2000,25),
(4,'Pensil 2B',2500,42),
(5,'Penggaris 30 cm',4000,26),
(6,'Spidol Hitam',7000,18),
(7,'Buku Gambar',8500,20),
(8,'Map Plastik',3500,31),
(9,'Correction Tape',9000,15),
(10,'Stabilo',6500,22);

INSERT INTO supplier (id,nama_supplier,telepon,alamat) VALUES
(1,'CV Sumber Alat Tulis','081234567890','Surabaya'),
(2,'PT Kertas Makmur','081298765432','Sidoarjo'),
(3,'UD Berkah ATK','082233445566','Gresik'),
(4,'CV Warna stationery','085712345678','Surabaya');

INSERT INTO pelanggan (id,nama_pelanggan,telepon,alamat) VALUES
(1,'Pelanggan Umum','-','-'),
(2,'Andi Saputra','081111111111','Surabaya'),
(3,'Siti Aminah','082222222222','Sidoarjo'),
(4,'Budi Santoso','083333333333','Gresik'),
(5,'Rina Oktavia','084444444444','Surabaya'),
(6,'Dewi Lestari','085555555555','Waru, Sidoarjo');

-- Riwayat penjualan contoh selama beberapa hari terakhir.
INSERT INTO transaksi (id,user_id,pelanggan_id,total,tanggal) VALUES
(1,2,2,16000,'2026-11-14 08:35:00'),
(2,2,1,21000,'2026-11-14 10:12:00'),
(3,1,3,27500,'2026-11-15 09:20:00'),
(4,2,4,19500,'2026-11-16 13:05:00'),
(5,2,5,32000,'2026-11-17 11:42:00'),
(6,1,2,24500,'2026-11-18 14:10:00'),
(7,2,6,39000,'2026-11-19 09:15:00'),
(8,2,3,17500,'2026-11-20 15:30:00'),
(9,1,4,28500,'2026-11-21 10:45:00'),
(10,2,5,22000,'2026-11-22 12:25:00'),
(11,2,2,41500,'2026-11-23 08:50:00'),
(12,1,1,14500,'2026-11-23 09:40:00');

INSERT INTO detail_transaksi (transaksi_id,barang_id,qty,harga) VALUES
(1,1,2,3000),(1,2,2,5000),
(2,6,1,7000),(2,4,2,2500),(2,7,1,8500),
(3,2,3,5000),(3,8,1,3500),(3,4,2,2500),
(4,5,2,4000),(4,1,1,3000),(4,3,2,2000),
(5,7,2,8500),(5,9,1,9000),(5,8,2,3500),
(6,10,2,6500),(6,2,1,5000),(6,1,2,3000),
(7,6,3,7000),(7,9,1,9000),(7,4,2,2500),
(8,8,2,3500),(8,3,2,2000),(8,1,2,3000),
(9,2,3,5000),(9,5,2,4000),(9,10,1,6500),
(10,4,2,2500),(10,7,1,8500),(10,9,1,9000),(10,3,1,2000),
(11,9,2,9000),(11,6,2,7000),(11,1,2,3000),(11,8,1,3500),
(12,2,1,5000),(12,10,1,6500),(12,3,1,2000),(12,1,1,3000);

ALTER TABLE transaksi AUTO_INCREMENT = 13;
ALTER TABLE detail_transaksi AUTO_INCREMENT = 39;
