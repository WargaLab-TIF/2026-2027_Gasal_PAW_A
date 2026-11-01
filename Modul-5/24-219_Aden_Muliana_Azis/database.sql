-- Module 5 : Database - Tugas Praktikum
CREATE DATABASE IF NOT EXISTS penjualan CHARACTER SET utf8mb4;
USE penjualan;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS transaksi_detail, pembayaran, transaksi, barang, supplier, pelanggan, user;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE supplier (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nama VARCHAR(100) NOT NULL,
  telp VARCHAR(12) NOT NULL,
  alamat TEXT NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE barang (
  id INT(11) NOT NULL AUTO_INCREMENT,
  kode_barang VARCHAR(10) NOT NULL,
  nama_barang VARCHAR(100) NOT NULL,
  harga INT(11) NOT NULL,
  stok INT(11) NOT NULL,
  supplier_id INT(11) NOT NULL,
  PRIMARY KEY (id),
  KEY supplier_id (supplier_id),
  CONSTRAINT fk_barang_supplier FOREIGN KEY (supplier_id) REFERENCES supplier (id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE pelanggan (
  id VARCHAR(20) NOT NULL,
  nama VARCHAR(100) NOT NULL,
  jenis_kelamin ENUM('L','P') NOT NULL,
  telp VARCHAR(12) NOT NULL,
  alamat TEXT NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE transaksi (
  id INT(11) NOT NULL AUTO_INCREMENT,
  waktu_transaksi DATE NOT NULL,
  keterangan TEXT,
  total INT(11) NOT NULL,
  pelanggan_id VARCHAR(20) NOT NULL,
  PRIMARY KEY (id),
  KEY pelanggan_id (pelanggan_id),
  CONSTRAINT fk_transaksi_pelanggan FOREIGN KEY (pelanggan_id) REFERENCES pelanggan (id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE transaksi_detail (
  transaksi_id INT(11) NOT NULL,
  barang_id INT(11) NOT NULL,
  harga INT(11) NOT NULL,
  qty INT(11) NOT NULL,
  PRIMARY KEY (transaksi_id, barang_id),
  CONSTRAINT fk_detail_transaksi FOREIGN KEY (transaksi_id) REFERENCES transaksi (id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_detail_barang FOREIGN KEY (barang_id) REFERENCES barang (id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE pembayaran (
  id INT(11) NOT NULL AUTO_INCREMENT,
  waktu_bayar DATETIME NOT NULL,
  total INT(11) NOT NULL,
  metode ENUM('TUNAI','TRANSFER','EDC') NOT NULL,
  transaksi_id INT(11) NOT NULL,
  PRIMARY KEY (id),
  KEY transaksi_id (transaksi_id),
  CONSTRAINT fk_bayar_transaksi FOREIGN KEY (transaksi_id) REFERENCES transaksi (id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE user (
  id_user TINYINT(2) NOT NULL AUTO_INCREMENT,
  username VARCHAR(30) NOT NULL,
  password VARCHAR(35) NOT NULL,
  nama VARCHAR(50) NOT NULL,
  alamat VARCHAR(150) NOT NULL,
  hp VARCHAR(20) NOT NULL,
  level TINYINT(1) NOT NULL,
  PRIMARY KEY (id_user)
) ENGINE=InnoDB;

-- ===================== DATA =====================
INSERT INTO supplier (nama, telp, alamat) VALUES
('PT. Maju Bersama',   '08123456',   'Surabaya'),
('PT. Senang Sekali',  '081515563',  'Bangkalan'),
('PT. Segar Segar',    '0845454663', 'Surabaya'),
('CV. Sumber Rejeki',  '081234500',  'Sidoarjo'),
('PT. Jaya Abadi',     '081234501',  'Gresik'),
('CV. Mitra Sejati',   '081234502',  'Malang'),
('PT. Berkah Makmur',  '081234503',  'Pasuruan'),
('UD. Sentosa',        '081234504',  'Madura'),
('PT. Nusantara Food', '081234505',  'Jakarta'),
('CV. Cahaya Baru',    '081234506',  'Kediri');

INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES
('BRG001', 'Beras 5 Kg',         15000, 100, 1),
('BRG002', 'Minyak Goreng 2 L',  25000,  80, 2),
('BRG003', 'Gula Pasir 1 Kg',    10000, 120, 3),
('BRG004', 'Telur Ayam 1 Kg',    20000,  60, 4),
('BRG005', 'Mie Instan',          5000, 300, 5),
('BRG006', 'Susu Bubuk 800 g',   50000,  40, 6),
('BRG007', 'Kopi Bubuk 200 g',   12500,  90, 7),
('BRG008', 'Teh Celup',           8000, 150, 8),
('BRG009', 'Deterjen 2 Kg',      75000,  30, 9),
('BRG010', 'Sabun Mandi',        15000, 110, 10);

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('P001', 'Ahmad Fauzi',    'L', '081300001', 'Bangkalan'),
('P002', 'Siti Aminah',    'P', '081300002', 'Surabaya'),
('P003', 'Budi Santoso',   'L', '081300003', 'Sidoarjo'),
('P004', 'Dewi Lestari',   'P', '081300004', 'Gresik'),
('P005', 'Rudi Hartono',   'L', '081300005', 'Malang'),
('P006', 'Nur Hidayah',    'P', '081300006', 'Pamekasan'),
('P007', 'Eko Prasetyo',   'L', '081300007', 'Sampang'),
('P008', 'Rina Wulandari', 'P', '081300008', 'Sumenep'),
('P009', 'Joko Susilo',    'L', '081300009', 'Kediri'),
('P010', 'Lilis Suryani',  'P', '081300010', 'Pasuruan');

INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES
('2026-10-01', 'Pembelian sembako',      55000, 'P001'),
('2026-10-02', 'Pembelian minyak',       25000, 'P002'),
('2026-10-03', 'Pembelian gula & mie',   40000, 'P003'),
('2026-10-04', 'Pembelian telur',        40000, 'P004'),
('2026-10-05', 'Pembelian mie instan',   20000, 'P005'),
('2026-10-06', 'Pembelian susu',         50000, 'P006'),
('2026-10-07', 'Pembelian kopi',         25000, 'P007'),
('2026-10-08', 'Pembelian teh',          40000, 'P008'),
('2026-10-09', 'Pembelian deterjen',     75000, 'P009'),
('2026-10-10', 'Pembelian sabun mandi',  45000, 'P010');

INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1, 1, 15000, 2),
(1, 2, 25000, 1),
(2, 2, 25000, 1),
(3, 3, 10000, 3),
(3, 5,  5000, 2),
(4, 4, 20000, 2),
(5, 5,  5000, 4),
(6, 6, 50000, 1),
(7, 7, 12500, 2),
(8, 8,  8000, 5),
(9, 9, 75000, 1),
(10, 10, 15000, 3);

INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id) VALUES
('2026-10-01 10:00:00', 55000, 'TUNAI',    1),
('2026-10-02 11:15:00', 25000, 'TRANSFER', 2),
('2026-10-03 09:30:00', 40000, 'EDC',      3),
('2026-10-04 14:00:00', 40000, 'TUNAI',    4),
('2026-10-05 13:20:00', 20000, 'TUNAI',    5),
('2026-10-06 16:45:00', 50000, 'TRANSFER', 6),
('2026-10-07 08:10:00', 25000, 'EDC',      7),
('2026-10-08 15:05:00', 40000, 'TUNAI',    8),
('2026-10-09 12:00:00', 75000, 'TRANSFER', 9),
('2026-10-10 17:30:00', 45000, 'EDC',      10);

INSERT INTO user (username, password, nama, alamat, hp, level) VALUES
('admin',  MD5('admin123'),  'Administrator', 'Bangkalan', '081200001', 1),
('kasir1', MD5('kasir123'),  'Kasir Satu',    'Surabaya',  '081200002', 2),
('kasir2', MD5('kasir123'),  'Kasir Dua',     'Sidoarjo',  '081200003', 2),
('kasir3', MD5('kasir123'),  'Kasir Tiga',    'Gresik',    '081200004', 2),
('gudang1', MD5('gudang123'), 'Staf Gudang 1', 'Malang',   '081200005', 3),
('gudang2', MD5('gudang123'), 'Staf Gudang 2', 'Kediri',   '081200006', 3),
('owner',  MD5('owner123'),  'Pemilik Toko',  'Bangkalan', '081200007', 1),
('staf1',  MD5('staf123'),   'Staf Satu',     'Madura',    '081200008', 2),
('staf2',  MD5('staf123'),   'Staf Dua',      'Pasuruan',  '081200009', 2),
('staf3',  MD5('staf123'),   'Staf Tiga',     'Jakarta',   '081200010', 2);