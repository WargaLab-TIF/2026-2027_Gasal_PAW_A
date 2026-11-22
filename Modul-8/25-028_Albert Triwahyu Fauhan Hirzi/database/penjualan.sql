SET FOREIGN_KEY_CHECKS=0;

DROP DATABASE IF EXISTS penjualan;
CREATE DATABASE penjualan;
USE penjualan;

CREATE TABLE user(
    id_user TINYINT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30),
    password VARCHAR(255),
    nama VARCHAR(50),
    alamat VARCHAR(150),
    hp VARCHAR(20),
    level TINYINT(1)
);

-- Akun awal praktikum (password akan di-hash otomatis saat pertama kali login).
INSERT INTO user (username, password, nama, alamat, hp, level) VALUES
('owner', 'owner123', 'Owner', '', '', 1),
('kasir', 'kasir123', 'Kasir', '', '', 2);

CREATE TABLE pelanggan(
    id VARCHAR(20) PRIMARY KEY,
    nama VARCHAR(100),
    jenis_kelamin ENUM('L','P'),
    telp VARCHAR(12),
    alamat TEXT
);

CREATE TABLE supplier(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    telp VARCHAR(12),
    alamat TEXT
);

CREATE TABLE barang(
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(10),
    nama_barang VARCHAR(100),
    harga INT,
    stok INT,
    supplier_id INT,
    FOREIGN KEY (supplier_id) REFERENCES supplier(id)
);

CREATE TABLE transaksi(
    id INT AUTO_INCREMENT PRIMARY KEY,
    waktu_transaksi DATE,
    keterangan TEXT,
    total INT,
    pelanggan_id VARCHAR(20),
    FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id)
);

CREATE TABLE pembayaran(
    id INT AUTO_INCREMENT PRIMARY KEY,
    waktu_bayar DATETIME,
    total INT,
    metode enum('TUNAI','TRANSFER','EDC'),
    transaksi_id INT,
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
);

CREATE TABLE transaksi_detail(
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    transaksi_id INT,
    barang_id INT,
    harga INT,
    qty INT,
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id),
    FOREIGN KEY (barang_id) REFERENCES barang(id)
);

SET FOREIGN_KEY_CHECKS=1;
