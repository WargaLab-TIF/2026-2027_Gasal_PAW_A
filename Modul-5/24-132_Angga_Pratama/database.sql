CREATE DATABASE IF NOT EXISTS penjualan;
USE penjualan;

CREATE TABLE pelanggan (
    id VARCHAR(20) PRIMARY KEY,
    nama VARCHAR(100),
    jenis_kelamin ENUM('L','P'),
    telp VARCHAR(12),
    alamat TEXT
) ENGINE=InnoDB;

CREATE TABLE supplier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    telp VARCHAR(12),
    alamat TEXT
) ENGINE=InnoDB;

CREATE TABLE barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(10),
    nama_barang VARCHAR(100),
    harga INT,
    stok INT,
    supplier_id INT,
    FOREIGN KEY (supplier_id) REFERENCES supplier(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    waktu_transaksi DATE,
    keterangan TEXT,
    total INT,
    pelanggan_id VARCHAR(20),
    FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE transaksi_detail (
    transaksi_id INT,
    barang_id INT,
    harga INT,
    qty INT,
    PRIMARY KEY (transaksi_id, barang_id),
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (barang_id) REFERENCES barang(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE pembayaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    waktu_bayar DATETIME,
    total INT,
    metode ENUM('TUNAI','TRANSFER','EDC'),
    transaksi_id INT,
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE user (
    id_user TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30),
    password VARCHAR(35),
    nama VARCHAR(50),
    alamat VARCHAR(150),
    hp VARCHAR(20),
    level TINYINT(1)
) ENGINE=InnoDB;

INSERT INTO pelanggan VALUES
('PLG001','Andi','L','081234567801','Surabaya'),
('PLG002','Budi','L','081234567802','Gresik'),
('PLG003','Citra','P','081234567803','Lamongan'),
('PLG004','Dina','P','081234567804','Tuban'),
('PLG005','Eko','L','081234567805','Sidoarjo'),
('PLG006','Fitri','P','081234567806','Malang'),
('PLG007','Gilang','L','081234567807','Mojokerto'),
('PLG008','Hani','P','081234567808','Jombang'),
('PLG009','Irfan','L','081234567809','Bojonegoro'),
('PLG010','Jihan','P','081234567810','Kediri');

INSERT INTO supplier (nama,telp,alamat) VALUES
('PT. Maju Bersama','08123456','Surabaya'),
('PT. Senang Sekali','081515563','Bangkalan'),
('PT. Segar Segar','0845454663','Surabaya'),
('CV. Sumber Makmur','0812345671','Gresik'),
('PT. Berkah Jaya','0812345672','Lamongan'),
('CV. Mitra Usaha','0812345673','Tuban'),
('PT. Karya Abadi','0812345674','Sidoarjo'),
('CV. Indo Jaya','0812345675','Malang'),
('PT. Sukses Mandiri','0812345676','Mojokerto'),
('CV. Sentosa','0812345677','Bojonegoro');

INSERT INTO barang (kode_barang,nama_barang,harga,stok,supplier_id) VALUES
('BRG001','Buku Tulis',5000,100,1),
('BRG002','Pulpen',3000,150,2),
('BRG003','Pensil',2000,120,3),
('BRG004','Penghapus',1500,80,4),
('BRG005','Penggaris',4000,90,5),
('BRG006','Spidol',7000,60,6),
('BRG007','Map',2500,75,7),
('BRG008','Buku Gambar',8000,50,8),
('BRG009','Stabilo',6000,40,9),
('BRG010','Kertas HVS',45000,30,10);

INSERT INTO transaksi (waktu_transaksi,keterangan,total,pelanggan_id) VALUES
('2026-10-01','Pembelian alat tulis',10000,'PLG001'),
('2026-10-02','Pembelian buku',15000,'PLG002'),
('2026-10-03','Pembelian pulpen',9000,'PLG003'),
('2026-10-04','Pembelian pensil',6000,'PLG004'),
('2026-10-05','Pembelian perlengkapan',12000,'PLG005'),
('2026-10-06','Pembelian alat tulis',14000,'PLG006'),
('2026-10-07','Pembelian buku gambar',16000,'PLG007'),
('2026-10-08','Pembelian kertas',45000,'PLG008'),
('2026-10-09','Pembelian perlengkapan',12000,'PLG009'),
('2026-10-10','Pembelian alat tulis',8000,'PLG010');

INSERT INTO transaksi_detail VALUES
(1,1,5000,2),
(2,1,5000,3),
(3,2,3000,3),
(4,3,2000,3),
(5,5,4000,3),
(6,6,7000,2),
(7,8,8000,2),
(8,10,45000,1),
(9,9,6000,2),
(10,4,1500,2);

INSERT INTO pembayaran (waktu_bayar,total,metode,transaksi_id) VALUES
('2026-10-01 09:00:00',10000,'TUNAI',1),
('2026-10-02 10:00:00',15000,'TRANSFER',2),
('2026-10-03 11:00:00',9000,'EDC',3),
('2026-10-04 12:00:00',6000,'TUNAI',4),
('2026-10-05 13:00:00',12000,'TRANSFER',5),
('2026-10-06 14:00:00',14000,'EDC',6),
('2026-10-07 09:30:00',16000,'TUNAI',7),
('2026-10-08 10:30:00',45000,'TRANSFER',8),
('2026-10-09 11:30:00',12000,'EDC',9),
('2026-10-10 12:30:00',8000,'TUNAI',10);

INSERT INTO user (username,password,nama,alamat,hp,level) VALUES
('admin1','admin123','Admin Satu','Surabaya','0811111111',1),
('admin2','admin123','Admin Dua','Gresik','0811111112',1),
('user1','user123','User Satu','Lamongan','0811111113',0),
('user2','user123','User Dua','Tuban','0811111114',0),
('user3','user123','User Tiga','Sidoarjo','0811111115',0),
('user4','user123','User Empat','Malang','0811111116',0),
('user5','user123','User Lima','Jombang','0811111117',0),
('user6','user123','User Enam','Kediri','0811111118',0),
('user7','user123','User Tujuh','Bojonegoro','0811111119',0),
('user8','user123','User Delapan','Mojokerto','0811111120',0);