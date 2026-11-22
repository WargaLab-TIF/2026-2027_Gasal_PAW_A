CREATE DATABASE IF NOT EXISTS nomor_1;
USE nomor_1;

CREATE TABLE pelanggan (
    id INT(20) NOT NULL AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    telp VARCHAR(12) NOT NULL,
    alamat TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;


CREATE TABLE supplier (
    id INT(11) NOT NULL AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    telp VARCHAR(12) NOT NULL,
    alamat TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE barang (
    id INT(11) NOT NULL AUTO_INCREMENT,
    kode_barang VARCHAR(20) NOT NULL,
    nama_barang VARCHAR(100) NOT NULL,
    harga INT(11) NOT NULL,
    stok INT(11) NOT NULL,
    supplier_id INT(11),
    PRIMARY KEY (`id`),
    FOREIGN KEY (supplier_id)   REFERENCES supplier(id)
) ENGINE=InnoDB;

CREATE TABLE user_data (
    id_user TINYINT(2) NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    hp VARCHAR(20) NOT NULL,
    level TINYINT(1) NOT NULL,
    PRIMARY KEY (`id_user`)
) ENGINE=InnoDB;

CREATE TABLE transaksi (
    id INT(11) NOT NULL AUTO_INCREMENT,
    waktu_transaksi DATETIME NOT NULL,
    keterangan TEXT,
    total DECIMAL(12,2) NOT NULL DEFAULT 0,
    pelanggan_id INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (pelanggan_id)  REFERENCES pelanggan(id)
) ENGINE=InnoDB;

CREATE TABLE detail_transaksi (
    transaksi_id INT(11) NOT NULL,
    barang_id INT(11) NOT NULL,
    harga INT(11) NOT NULL,
    qty INT(11) NOT NULL,
    PRIMARY KEY (transaksi_id, barang_id),
    FOREIGN KEY (transaksi_id)  REFERENCES transaksi(id),
    FOREIGN KEY (barang_id)   REFERENCES barang(id)
) ENGINE=InnoDB;

CREATE TABLE pembayaran (
    id INT(11) NOT NULL AUTO_INCREMENT,
    waktu_bayar DATETIME NOT NULL,
    total DECIMAL(12,2) NOT NULL,
    metode ENUM('Tunai','Transfer','EDC') NOT NULL,
    transaksi_id INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (transaksi_id)  REFERENCES transaksi(id)
) ENGINE=InnoDB;


USE nomor_1;

INSERT INTO pelanggan (nama, jenis_kelamin, telp, alamat) VALUES
('andi', 'L', '000000000001', 'jl. 1'),
('budi', 'L', '000000000002', 'jl. 2'),
('bowo', 'P', '000000000003', 'jl. 3'),
('asep', 'P', '000000000004', 'jl. 4'),
('eko', 'L', '000000000005', 'jl. 5'),
('fitri', 'P', '000000000006', 'jl. 6'),
('bintang', 'L', '000000000007', 'jl. 7'),
('teddy', 'P', '000000000008', 'jl. 8'),
('jaya', 'L', '000000000009', 'jl. 9'),
('joko', 'L', '000000000010', 'jl. 10');


INSERT INTO supplier (nama, telp, alamat) VALUES
('PT Mencari Cinta Sejati', '02112345001', 'Jakarta'),
('CV Suka Mundur', '02112345002', 'Bandung'),
('PT Elektronik Kesetrum', '02112345003', 'Surabaya'),
('CV Tidur Bersama', '02112345004', 'Semarang'),
('PT Ketinggian', '02112345005', 'Yogyakarta'),
('CV Cahaya Sesaat', '02112345006', 'Malang'),
('PT Global Persada', '02112345007', 'Bekasi'),
('CV Sentosa Jaya', '02112345008', 'Sidoarjo'),
('PT Tugas Mandiri', '02112345009', 'Tangerang'),
('CV Sinar Kegelapan', '02112345010', 'Denpasar');


INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES
('BRG001', 'Laptop Asus', 7500000, 15, 1),
('BRG002', 'Mouse Logitech', 150000, 50, 2),
('BRG003', 'Keyboard Mechanical', 350000, 30, 3),
('BRG004', 'Monitor Samsul', 1800000, 20, 4),
('BRG005', 'Flashdisk 32TB', 75000, 100, 5),
('BRG006', 'Harddisk 1KB', 850000, 25, 6),
('BRG007', 'Headset Gaming', 250000, 40, 7),
('BRG008', 'Webcam 8-bit', 300000, 35, 8),
('BRG009', 'Printer Epstein', 2200000, 12, 9),
('BRG010', 'Galon Aqua', 450000, 28, 10);


INSERT INTO user_data (username, password, nama, alamat, hp, level) VALUES
('admin', 'ADMIN', 'Admin', 'Planet Mars', '081111111001', 1),
('kasir1', 'kasir123', 'Rina', 'Surabaya', '081111111002', 2),
('kasir2', 'kasir123', 'Doni', 'Sidoarjo', '081111111003', 2),
('kasir3', 'kasir123', 'Siti', 'Gresik', '081111111004', 2),
('staff1', 'staff123', 'Agus', 'Malang', '081111111005', 3),
('staff2', 'staff123', 'Nina', 'Kediri', '081111111006', 3),
('staff3', 'staff123', 'Hartono', 'Mojokerto', '081111111007', 3),
('manager1', 'manager123', 'Dian', 'Surabaya', '081111111008', 4),
('manager2', 'manager123', 'Fajar', 'Pasuruan', '081111111009', 4),
('staff4', 'malas', 'Lina', 'Jombang', '081111111010', 3);


INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES
('2026-10-01 00:10:00', 'Beli laptop', 7500000, 1),
('2026-10-01 01:20:00', 'Beli mouse', 300000, 2),
('2026-10-02 01:40:00', 'Beli keyboard', 700000, 3),
('2026-10-05 09:10:00', 'Beli monitor', 1800000, 4),
('2026-10-05 12:30:00', 'Beli flashdisk', 225000, 5),
('2026-10-06 07:20:00', 'Beli harddisk', 1700000, 6),
('2026-10-06 09:55:00', 'Beli headset', 500000, 7),
('2026-10-06 10:40:00', 'Beli webcam', 600000, 8),
('2026-10-06 12:10:00', 'Beli printer', 2200000, 9),
('2026-10-07 05:10:00', 'Beli speaker', 900000, 10);


INSERT INTO detail_transaksi (transaksi_id, barang_id, harga, qty) VALUES
(1, 1, 7500000, 1),
(2, 2, 150000, 2),
(3, 3, 350000, 2),
(4, 4, 1800000, 1),
(5, 5, 75000, 3),
(6, 6, 850000, 2),
(7, 7, 250000, 2),
(8, 8, 300000, 2),
(9, 9, 2200000, 1),
(10, 10, 450000, 2);


INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id) VALUES
('2026-10-01 00:10:00', 7500000, 'Transfer', 1),
('2026-10-01 01:20:00', 300000, 'Tunai', 2),
('2026-10-02 01:40:00', 700000, 'EDC', 3),
('2026-10-05 09:10:00', 1800000, 'Transfer', 4),
('2026-10-05 12:30:00', 225000, 'Tunai', 5),
('2026-10-06 07:20:00', 1700000, 'EDC', 6),
('2026-10-06 09:55:00', 500000, 'Tunai', 7),
('2026-10-06 10:40:00', 600000, 'Transfer', 8),
('2026-10-06 12:10:00', 2200000, 'EDC', 9),
('2026-10-07 05:10:00', 900000, 'Tunai', 10);

