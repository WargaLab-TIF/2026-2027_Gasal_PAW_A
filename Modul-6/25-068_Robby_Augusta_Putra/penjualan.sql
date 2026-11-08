CREATE DATABASE IF NOT EXISTS penjualan CHARACTER SET utf8mb4;
USE penjualan;

DROP TABLE IF EXISTS pembayaran, transaksi_detail, transaksi, barang, supplier, pelanggan, user;

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
  CONSTRAINT fk_barang_supplier FOREIGN KEY (supplier_id) REFERENCES supplier(id)
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
  keterangan TEXT NOT NULL,
  total INT(11) NOT NULL,
  pelanggan_id VARCHAR(20) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_transaksi_pelanggan FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id)
) ENGINE=InnoDB;

CREATE TABLE transaksi_detail (
  transaksi_id INT(11) NOT NULL,
  barang_id INT(11) NOT NULL,
  harga INT(11) NOT NULL,
  qty INT(11) NOT NULL,
  PRIMARY KEY (transaksi_id, barang_id),
  CONSTRAINT fk_td_transaksi FOREIGN KEY (transaksi_id) REFERENCES transaksi(id),
  CONSTRAINT fk_td_barang FOREIGN KEY (barang_id) REFERENCES barang(id)
) ENGINE=InnoDB;

CREATE TABLE pembayaran (
  id INT(11) NOT NULL AUTO_INCREMENT,
  waktu_bayar DATETIME NOT NULL,
  total INT(11) NOT NULL,
  metode ENUM('TUNAI','TRANSFER','EDC') NOT NULL,
  transaksi_id INT(11) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_bayar_transaksi FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
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

INSERT INTO supplier (nama, telp, alamat) VALUES
('PT. Maju Bersama','08123456','Surabaya'),
('PT. Senang Sekali','081515563','Bangkalan'),
('PT. Segar Segar','0845454663','Surabaya'),
('CV. Sumber Rejeki','081233344455','Sidoarjo'),
('CV. Jaya Abadi','085711122233','Gresik'),
('PT. Tani Makmur','081322233344','Malang'),
('PT. Berkah Sentosa','082199988877','Mojokerto'),
('CV. Nusantara Pangan','085655544433','Pasuruan'),
('PT. Mitra Niaga','081277766655','Jombang'),
('CV. Cahaya Baru','087811100099','Lamongan');

INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES
('BRG001','Beras 5 kg',65000,100,1),
('BRG002','Minyak Goreng 2 L',38000,80,2),
('BRG003','Gula Pasir 1 kg',16000,120,3),
('BRG004','Tepung Terigu 1 kg',12000,90,4),
('BRG005','Telur Ayam 1 kg',28000,60,5),
('BRG006','Kopi Bubuk 200 g',22000,70,6),
('BRG007','Teh Celup 25 pcs',9000,150,7),
('BRG008','Susu UHT 1 L',18000,110,8),
('BRG009','Mie Instan',3500,300,9),
('BRG010','Sabun Mandi',4500,200,10);

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('P001','Budi Santoso','L','081200000001','Surabaya'),
('P002','Siti Aminah','P','081200000002','Sidoarjo'),
('P003','Andi Wijaya','L','081200000003','Gresik'),
('P004','Dewi Lestari','P','081200000004','Malang'),
('P005','Rudi Hartono','L','081200000005','Bangkalan'),
('P006','Rina Marlina','P','081200000006','Mojokerto'),
('P007','Agus Salim','L','081200000007','Pasuruan'),
('P008','Putri Ayu','P','081200000008','Jombang'),
('P009','Hendra Kusuma','L','081200000009','Lamongan'),
('P010','Maya Sari','P','081200000010','Surabaya');

INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES
('2026-10-01','Pembelian beras',130000,'P001'),
('2026-10-02','Pembelian minyak goreng',38000,'P002'),
('2026-10-03','Pembelian gula',48000,'P003'),
('2026-10-04','Pembelian tepung',24000,'P004'),
('2026-10-05','Pembelian telur',28000,'P005'),
('2026-10-06','Pembelian kopi',44000,'P006'),
('2026-10-07','Pembelian teh',36000,'P007'),
('2026-10-08','Pembelian susu',90000,'P008'),
('2026-10-09','Pembelian mie instan',35000,'P009'),
('2026-10-10','Pembelian sabun',13500,'P010');

INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1,1,65000,2),(2,2,38000,1),(3,3,16000,3),(4,4,12000,2),(5,5,28000,1),
(6,6,22000,2),(7,7,9000,4),(8,8,18000,5),(9,9,3500,10),(10,10,4500,3);

INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id) VALUES
('2026-10-01 10:15:00',130000,'TUNAI',1),
('2026-10-02 11:20:00',38000,'TRANSFER',2),
('2026-10-03 09:05:00',48000,'EDC',3),
('2026-10-04 14:30:00',24000,'TUNAI',4),
('2026-10-05 15:45:00',28000,'TRANSFER',5),
('2026-10-06 08:50:00',44000,'EDC',6),
('2026-10-07 13:10:00',36000,'TUNAI',7),
('2026-10-08 16:25:00',90000,'TRANSFER',8),
('2026-10-09 12:00:00',35000,'EDC',9),
('2026-10-10 17:40:00',13500,'TUNAI',10);

INSERT INTO user (username, password, nama, alamat, hp, level) VALUES
('admin',MD5('admin123'),'Administrator','Surabaya','081300000001',1),
('kasir1',MD5('kasir123'),'Kasir Satu','Sidoarjo','081300000002',2),
('kasir2',MD5('kasir123'),'Kasir Dua','Gresik','081300000003',2),
('gudang1',MD5('gudang123'),'Staf Gudang 1','Malang','081300000004',3),
('gudang2',MD5('gudang123'),'Staf Gudang 2','Bangkalan','081300000005',3),
('manajer',MD5('manajer123'),'Manajer Toko','Surabaya','081300000006',1),
('kasir3',MD5('kasir123'),'Kasir Tiga','Mojokerto','081300000007',2),
('kasir4',MD5('kasir123'),'Kasir Empat','Pasuruan','081300000008',2),
('gudang3',MD5('gudang123'),'Staf Gudang 3','Jombang','081300000009',3),
('owner',MD5('owner123'),'Pemilik Toko','Lamongan','081300000010',1);
