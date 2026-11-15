CREATE DATABASE IF NOT EXISTS penjualan;
USE penjualan;

CREATE TABLE supplier (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100),
  telp VARCHAR(12),
  alamat TEXT
);

CREATE TABLE barang (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_barang VARCHAR(10),
  nama_barang VARCHAR(100),
  harga INT,
  stok INT,
  supplier_id INT
);

CREATE TABLE pelanggan (
  id VARCHAR(20) PRIMARY KEY,
  nama VARCHAR(100),
  jenis_kelamin ENUM('L','P'),
  telp VARCHAR(12),
  alamat TEXT
);

CREATE TABLE transaksi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  waktu_transaksi DATE,
  keterangan TEXT,
  total INT,
  pelanggan_id VARCHAR(20)
);

CREATE TABLE transaksi_detail (
  transaksi_id INT,
  barang_id INT,
  harga INT,
  qty INT
);

CREATE TABLE pembayaran (
  id INT AUTO_INCREMENT PRIMARY KEY,
  waktu_bayar DATETIME,
  total INT,
  metode ENUM('TUNAI','TRANSFER','EDC'),
  transaksi_id INT
);

CREATE TABLE user (
  id_user TINYINT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(30),
  password VARCHAR(35),
  nama VARCHAR(50),
  alamat VARCHAR(150),
  hp VARCHAR(20),
  level TINYINT(1)
);

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('P001', 'Ahmad Fauzi',    'L', '081234567801', 'Bangkalan'),
('P002', 'Siti Aisyah',    'P', '081234567802', 'Bangkalan'),
('P003', 'Budi Santoso',   'L', '081234567803', 'Sampang'),
('P004', 'Dewi Lestari',   'P', '081234567804', 'Pamekasan'),
('P005', 'Rizky Pratama',  'L', '081234567805', 'Sumenep'),
('P006', 'Nur Hidayah',    'P', '081234567806', 'Bangkalan'),
('P007', 'Hasan Basri',    'L', '081234567807', 'Sampang'),
('P008', 'Lina Marlina',   'P', '081234567808', 'Surabaya'),
('P009', 'Yusuf Ramadhan', 'L', '081234567809', 'Bangkalan');

INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES
('2023-08-31', 'Penjualan harian', 13000, 'P002'),
('2023-09-01', 'Penjualan harian', 13000, 'P001'),
('2023-09-01', 'Penjualan harian', 26000, 'P002'),
('2023-09-02', 'Penjualan harian', 26000, 'P003'),
('2023-09-02', 'Penjualan harian', 13000, 'P001'),
('2023-09-02', 'Penjualan harian', 13000, 'P004'),
('2023-09-03', 'Penjualan harian', 13000, 'P005'),
('2023-09-04', 'Penjualan harian', 13000, 'P008'),
('2023-09-05', 'Penjualan harian', 26000, 'P006'),
('2023-09-06', 'Penjualan harian', 13000, 'P007'),
('2023-09-09', 'Penjualan harian', 13000, 'P009'),
('2023-10-02', 'Penjualan harian', 26000, 'P001');
