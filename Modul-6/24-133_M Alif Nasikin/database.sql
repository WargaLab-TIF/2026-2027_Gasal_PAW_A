-- =====================================================
-- 1. MEMBUAT DATABASE
-- =====================================================

CREATE DATABASE IF NOT EXISTS penjualan;

USE penjualan;


-- =====================================================
-- 2. MENGHAPUS TABEL LAMA JIKA SUDAH ADA
-- =====================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS pembayaran;
DROP TABLE IF EXISTS transaksi_detail;
DROP TABLE IF EXISTS transaksi;
DROP TABLE IF EXISTS barang;
DROP TABLE IF EXISTS pelanggan;
DROP TABLE IF EXISTS supplier;
DROP TABLE IF EXISTS `user`;

SET FOREIGN_KEY_CHECKS = 1;


-- =====================================================
-- 3. MEMBUAT TABEL SUPPLIER
-- =====================================================

CREATE TABLE supplier (
    id INT NOT NULL AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    telp VARCHAR(12) NOT NULL,
    alamat TEXT NOT NULL,

    PRIMARY KEY (id)
) ENGINE=InnoDB;


-- =====================================================
-- 4. MEMBUAT TABEL BARANG
-- =====================================================

CREATE TABLE barang (
    id INT NOT NULL AUTO_INCREMENT,
    kode_barang VARCHAR(10) NOT NULL,
    nama_barang VARCHAR(100) NOT NULL,
    harga INT NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    supplier_id INT NOT NULL,

    PRIMARY KEY (id),
    UNIQUE KEY uk_kode_barang (kode_barang),

    CONSTRAINT fk_barang_supplier
    FOREIGN KEY (supplier_id)
    REFERENCES supplier(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB;


-- =====================================================
-- 5. MEMBUAT TABEL PELANGGAN
-- =====================================================

CREATE TABLE pelanggan (
    id VARCHAR(20) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L','P') NOT NULL,
    telp VARCHAR(12) NOT NULL,
    alamat TEXT NOT NULL,

    PRIMARY KEY (id)
) ENGINE=InnoDB;


-- =====================================================
-- 6. MEMBUAT TABEL TRANSAKSI
-- =====================================================

CREATE TABLE transaksi (
    id INT NOT NULL AUTO_INCREMENT,
    waktu_transaksi DATE NOT NULL,
    keterangan TEXT NULL,
    total INT NOT NULL DEFAULT 0,
    pelanggan_id VARCHAR(20) NOT NULL,

    PRIMARY KEY (id),

    CONSTRAINT fk_transaksi_pelanggan
    FOREIGN KEY (pelanggan_id)
    REFERENCES pelanggan(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB;


-- =====================================================
-- 7. MEMBUAT TABEL TRANSAKSI DETAIL
-- =====================================================

CREATE TABLE transaksi_detail (
    transaksi_id INT NOT NULL,
    barang_id INT NOT NULL,
    harga INT NOT NULL,
    qty INT NOT NULL,

    PRIMARY KEY (transaksi_id, barang_id),

    CONSTRAINT fk_detail_transaksi
    FOREIGN KEY (transaksi_id)
    REFERENCES transaksi(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

    CONSTRAINT fk_detail_barang
    FOREIGN KEY (barang_id)
    REFERENCES barang(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB;


-- =====================================================
-- 8. MEMBUAT TABEL PEMBAYARAN
-- =====================================================

CREATE TABLE pembayaran (
    id INT NOT NULL AUTO_INCREMENT,
    waktu_bayar DATETIME NOT NULL,
    total INT NOT NULL,
    metode ENUM('TUNAI','TRANSFER','EDC') NOT NULL,
    transaksi_id INT NOT NULL,

    PRIMARY KEY (id),

    CONSTRAINT fk_pembayaran_transaksi
    FOREIGN KEY (transaksi_id)
    REFERENCES transaksi(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;


-- =====================================================
-- 9. MEMBUAT TABEL USER
-- =====================================================

CREATE TABLE `user` (
    id_user TINYINT NOT NULL AUTO_INCREMENT,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(35) NOT NULL,
    nama VARCHAR(50) NOT NULL,
    alamat VARCHAR(150) NOT NULL,
    hp VARCHAR(20) NOT NULL,
    level TINYINT(1) NOT NULL DEFAULT 1,

    PRIMARY KEY (id_user),
    UNIQUE KEY uk_username (username)
) ENGINE=InnoDB;


-- =====================================================
-- 10. INSERT DATA SUPPLIER
-- =====================================================

INSERT INTO supplier (nama, telp, alamat) VALUES
('PT Sumber Pangan', '031555101', 'Jl. Raya Surabaya No. 10, Surabaya'),
('CV Mitra Sejahtera', '031555102', 'Jl. Ahmad Yani No. 21, Surabaya'),
('PT Indo Distribusi', '031555103', 'Jl. Diponegoro No. 5, Sidoarjo'),
('UD Berkah Jaya', '031555104', 'Jl. Pahlawan No. 8, Gresik'),
('CV Maju Bersama', '031555105', 'Jl. Trunojoyo No. 3, Bangkalan');


-- =====================================================
-- 11. INSERT DATA BARANG
-- =====================================================

INSERT INTO barang
(kode_barang, nama_barang, harga, stok, supplier_id)
VALUES
('BRG001', 'Beras 5 Kg', 62000, 50, 1),
('BRG002', 'Minyak Goreng 2 L', 38000, 40, 1),
('BRG003', 'Gula Pasir 1 Kg', 16000, 60, 2),
('BRG004', 'Kopi Bubuk 200 g', 22000, 35, 2),
('BRG005', 'Mie Instan Dus', 95000, 25, 3),
('BRG006', 'Sabun Mandi', 6000, 100, 4),
('BRG007', 'Deterjen 1 Kg', 27000, 45, 4),
('BRG008', 'Teh Celup 25 pcs', 9500, 80, 5),
('BRG009', 'Garam 500 g', 4000, 70, 5),
('BRG010', 'Kecap Manis 600 ml', 18000, 30, 3);


-- =====================================================
-- 12. INSERT DATA PELANGGAN
-- =====================================================

INSERT INTO pelanggan
(id, nama, jenis_kelamin, telp, alamat)
VALUES
('PLG001', 'Budi Santoso', 'L', '081234560001', 'Jl. Mawar No. 1, Bangkalan'),
('PLG002', 'Siti Aminah', 'P', '081234560002', 'Jl. Melati No. 2, Bangkalan'),
('PLG003', 'Ahmad Fauzi', 'L', '081234560003', 'Jl. Kenanga No. 3, Sampang'),
('PLG004', 'Dewi Lestari', 'P', '081234560004', 'Jl. Anggrek No. 4, Pamekasan'),
('PLG005', 'Rudi Hartono', 'L', '081234560005', 'Jl. Dahlia No. 5, Sumenep');


-- =====================================================
-- 13. INSERT DATA USER
-- =====================================================

INSERT INTO `user`
(username, password, nama, alamat, hp, level)
VALUES
('admin', MD5('admin123'), 'Administrator', 'Jl. Kampus No. 1', '081200000001', 1),
('kasir1', MD5('kasir123'), 'Kasir Satu', 'Jl. Kampus No. 2', '081200000002', 2),
('kasir2', MD5('kasir456'), 'Kasir Dua', 'Jl. Kampus No. 3', '081200000003', 2),
('gudang', MD5('gudang123'), 'Staf Gudang', 'Jl. Kampus No. 4', '081200000004', 3),
('owner', MD5('owner123'), 'Pemilik Toko', 'Jl. Kampus No. 5', '081200000005', 1);


-- =====================================================
-- 14. INSERT DATA TRANSAKSI
-- =====================================================

INSERT INTO transaksi
(id, waktu_transaksi, keterangan, total, pelanggan_id)
VALUES
(1, '2026-10-01', 'Belanja bulanan', 140000, 'PLG001'),
(2, '2026-10-03', 'Belanja dapur', 82000, 'PLG002'),
(3, '2026-10-05', 'Stok warung', 95000, 'PLG003'),
(4, '2026-10-08', 'Kebutuhan mandi/cuci', 76000, 'PLG004'),
(5, '2026-10-10', 'Belanja mingguan', 138000, 'PLG005'),
(6, '2026-10-14', 'Gula dan teh', 57500, 'PLG001'),
(7, '2026-10-17', 'Kopi dan deterjen', 76000, 'PLG002'),
(8, '2026-10-20', 'Stok mie warung', 190000, 'PLG003'),
(9, '2026-10-24', 'Sabun dan gula', 92000, 'PLG004'),
(10, '2026-10-28', 'Belanja akhir bulan', 198000, 'PLG005');


-- =====================================================
-- 15. INSERT DATA TRANSAKSI DETAIL
-- =====================================================

INSERT INTO transaksi_detail
(transaksi_id, barang_id, harga, qty)
VALUES
(1, 1, 62000, 2),
(1, 3, 16000, 1),

(2, 2, 38000, 1),
(2, 4, 22000, 2),

(3, 5, 95000, 1),

(4, 6, 6000, 5),
(4, 7, 27000, 1),
(4, 8, 9500, 2),

(5, 1, 62000, 1),
(5, 2, 38000, 2),

(6, 3, 16000, 3),
(6, 8, 9500, 1),

(7, 4, 22000, 1),
(7, 7, 27000, 2),

(8, 5, 95000, 2),

(9, 6, 6000, 10),
(9, 3, 16000, 2),

(10, 2, 38000, 3),
(10, 1, 62000, 1),
(10, 4, 22000, 1);


-- =====================================================
-- 16. INSERT DATA PEMBAYARAN
-- =====================================================

INSERT INTO pembayaran
(waktu_bayar, total, metode, transaksi_id)
VALUES
('2026-10-01 10:15:00', 140000, 'TUNAI', 1),
('2026-10-03 14:20:00', 82000, 'TRANSFER', 2),
('2026-10-05 09:05:00', 95000, 'EDC', 3),
('2026-10-08 11:30:00', 76000, 'TUNAI', 4),
('2026-10-10 13:45:00', 138000, 'TRANSFER', 5),
('2026-10-14 15:10:00', 57500, 'EDC', 6),
('2026-10-17 09:25:00', 76000, 'TUNAI', 7),
('2026-10-20 16:00:00', 190000, 'TRANSFER', 8),
('2026-10-24 10:40:00', 92000, 'EDC', 9),
('2026-10-28 14:15:00', 198000, 'TUNAI', 10);


-- =====================================================
-- 17. QUERY PENGUJIAN DATA
-- =====================================================

-- Menampilkan seluruh supplier
SELECT * FROM supplier;

-- Menampilkan seluruh barang
SELECT * FROM barang;

-- Menampilkan seluruh pelanggan
SELECT * FROM pelanggan;

-- Menampilkan seluruh user
SELECT * FROM `user`;

-- Menampilkan seluruh transaksi
SELECT * FROM transaksi;

-- Menampilkan seluruh detail transaksi
SELECT * FROM transaksi_detail;

-- Menampilkan seluruh pembayaran
SELECT * FROM pembayaran;


-- =====================================================
-- 18. QUERY JOIN TRANSAKSI
-- =====================================================

SELECT
    t.id AS id_transaksi,
    t.waktu_transaksi,
    p.nama AS nama_pelanggan,
    b.nama_barang,
    td.harga,
    td.qty,
    (td.harga * td.qty) AS subtotal,
    t.total AS total_transaksi
FROM transaksi t
JOIN pelanggan p
    ON t.pelanggan_id = p.id
JOIN transaksi_detail td
    ON t.id = td.transaksi_id
JOIN barang b
    ON td.barang_id = b.id
ORDER BY t.id;


-- =====================================================
-- 19. MENGUJI KESESUAIAN TOTAL TRANSAKSI
-- =====================================================

SELECT
    t.id AS id_transaksi,
    t.total AS total_tersimpan,
    COALESCE(SUM(td.harga * td.qty), 0) AS total_perhitungan,
    CASE
        WHEN t.total = COALESCE(SUM(td.harga * td.qty), 0)
        THEN 'SESUAI'
        ELSE 'TIDAK SESUAI'
    END AS status_total
FROM transaksi t
LEFT JOIN transaksi_detail td
    ON t.id = td.transaksi_id
GROUP BY t.id, t.total
ORDER BY t.id;


-- =====================================================
-- 20. MENGUJI DATA PEMBAYARAN
-- =====================================================

SELECT
    p.id AS id_pembayaran,
    p.waktu_bayar,
    p.metode,
    p.total AS total_bayar,
    t.id AS id_transaksi,
    t.total AS total_transaksi
FROM pembayaran p
JOIN transaksi t
    ON p.transaksi_id = t.id
ORDER BY p.id;


-- =====================================================
-- SELESAI
-- DATABASE PENJUALAN BERHASIL DIBUAT
-- =====================================================
