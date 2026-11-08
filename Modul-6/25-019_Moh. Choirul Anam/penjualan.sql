-- =====================================================
-- Database : penjualan
-- Modul 6  : Pengelolaan Master Detil Data
-- =====================================================
DROP DATABASE IF EXISTS penjualan;
CREATE DATABASE penjualan CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE penjualan;

-- ---------- STRUKTUR TABEL ----------
CREATE TABLE supplier (
  id     INT NOT NULL AUTO_INCREMENT,
  nama   VARCHAR(100) NOT NULL,
  telp   VARCHAR(12),
  alamat TEXT,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE barang (
  id          INT NOT NULL AUTO_INCREMENT,
  kode_barang VARCHAR(10) NOT NULL,
  nama_barang VARCHAR(100) NOT NULL,
  harga       INT NOT NULL,
  stok        INT NOT NULL DEFAULT 0,
  supplier_id INT NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uk_kode_barang (kode_barang),
  KEY idx_barang_supplier (supplier_id),
  CONSTRAINT fk_barang_supplier FOREIGN KEY (supplier_id)
    REFERENCES supplier (id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE pelanggan (
  id            VARCHAR(20) NOT NULL,
  nama          VARCHAR(100) NOT NULL,
  jenis_kelamin ENUM('L','P') NOT NULL,
  telp          VARCHAR(12),
  alamat        TEXT,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE transaksi (
  id              INT NOT NULL AUTO_INCREMENT,
  waktu_transaksi DATE NOT NULL,
  keterangan      TEXT,
  total           INT NOT NULL DEFAULT 0,
  pelanggan_id    VARCHAR(20) NOT NULL,
  PRIMARY KEY (id),
  KEY idx_transaksi_pelanggan (pelanggan_id),
  CONSTRAINT fk_transaksi_pelanggan FOREIGN KEY (pelanggan_id)
    REFERENCES pelanggan (id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE transaksi_detail (
  transaksi_id INT NOT NULL,
  barang_id    INT NOT NULL,
  harga        INT NOT NULL,
  qty          INT NOT NULL,
  PRIMARY KEY (transaksi_id, barang_id),
  KEY idx_detail_barang (barang_id),
  CONSTRAINT fk_detail_transaksi FOREIGN KEY (transaksi_id)
    REFERENCES transaksi (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_detail_barang FOREIGN KEY (barang_id)
    REFERENCES barang (id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE pembayaran (
  id           INT NOT NULL AUTO_INCREMENT,
  waktu_bayar  DATETIME NOT NULL,
  total        INT NOT NULL,
  metode       ENUM('TUNAI','TRANSFER','EDC') NOT NULL,
  transaksi_id INT NOT NULL,
  PRIMARY KEY (id),
  KEY idx_pembayaran_transaksi (transaksi_id),
  CONSTRAINT fk_pembayaran_transaksi FOREIGN KEY (transaksi_id)
    REFERENCES transaksi (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE user (
  id_user  TINYINT NOT NULL AUTO_INCREMENT,
  username VARCHAR(30) NOT NULL,
  password VARCHAR(35) NOT NULL,
  nama     VARCHAR(50) NOT NULL,
  alamat   VARCHAR(150),
  hp       VARCHAR(20),
  level    TINYINT(1) NOT NULL DEFAULT 2,
  PRIMARY KEY (id_user),
  UNIQUE KEY uk_username (username)
) ENGINE=InnoDB;

-- ---------- DATA MASTER ----------
INSERT INTO supplier (id, nama, telp, alamat) VALUES
(1, 'PT Sumber Pangan Makmur', '031555101', 'Jl. Raya Surabaya No. 10, Surabaya'),
(2, 'CV Berkah Jaya', '031555102', 'Jl. Pahlawan No. 25, Sidoarjo'),
(3, 'PT Mitra Sembako', '031555103', 'Jl. Veteran No. 8, Gresik'),
(4, 'UD Sentosa Abadi', '031555104', 'Jl. Diponegoro No. 17, Bangkalan'),
(5, 'CV Cahaya Mulia', '031555105', 'Jl. Trunojoyo No. 3, Pamekasan');

INSERT INTO barang (id, kode_barang, nama_barang, harga, stok, supplier_id) VALUES
(1, 'BRG001', 'Beras Premium 5kg', 68000, 50, 1),
(2, 'BRG002', 'Minyak Goreng 2L', 38000, 40, 1),
(3, 'BRG003', 'Gula Pasir 1kg', 17500, 60, 2),
(4, 'BRG004', 'Kopi Bubuk 200g', 15000, 35, 2),
(5, 'BRG005', 'Teh Celup 25s', 8500, 80, 3),
(6, 'BRG006', 'Mie Instan Goreng', 3500, 200, 3),
(7, 'BRG007', 'Sabun Mandi', 6000, 90, 4),
(8, 'BRG008', 'Deterjen 800g', 21000, 45, 5),
(9, 'BRG009', 'Pasta Gigi 120g', 12500, 70, 4),
(10, 'BRG010', 'Sikat Gigi', 9000, 55, 5);

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('PLG001', 'Siti Aminah', 'P', '081234560001', 'Jl. Mawar No. 1, Pamekasan'),
('PLG002', 'Budi Santoso', 'L', '081234560002', 'Jl. Melati No. 2, Sampang'),
('PLG003', 'Dewi Lestari', 'P', '081234560003', 'Jl. Kenanga No. 3, Bangkalan'),
('PLG004', 'Ahmad Fauzi', 'L', '081234560004', 'Jl. Anggrek No. 4, Sumenep'),
('PLG005', 'Rina Marlina', 'P', '081234560005', 'Jl. Dahlia No. 5, Pamekasan');

INSERT INTO user (id_user, username, password, nama, alamat, hp, level) VALUES
(1, 'admin', MD5('admin123'), 'Administrator', 'Bangkalan', '081300000001', 1),
(2, 'kasir1', MD5('kasir123'), 'Kasir Satu', 'Pamekasan', '081300000002', 2),
(3, 'kasir2', MD5('kasir123'), 'Kasir Dua', 'Sampang', '081300000003', 2),
(4, 'gudang1', MD5('gudang123'), 'Staf Gudang', 'Sumenep', '081300000004', 3),
(5, 'owner', MD5('owner123'), 'Pemilik Toko', 'Surabaya', '081300000005', 1);

-- ---------- DATA TRANSAKSI (12 transaksi) ----------
INSERT INTO transaksi (id, waktu_transaksi, keterangan, total, pelanggan_id) VALUES
(1, '2026-10-01', 'Belanja bulanan', 179000, 'PLG001'),
(2, '2026-10-03', 'Kebutuhan warung', 182500, 'PLG002'),
(3, '2026-10-05', 'Belanja mingguan', 65500, 'PLG003'),
(4, '2026-10-08', 'Pesanan kantor', 230000, 'PLG004'),
(5, '2026-10-10', 'Belanja harian', 47000, 'PLG005'),
(6, '2026-10-14', 'Stok dapur', 195000, 'PLG001'),
(7, '2026-10-17', 'Kebutuhan warung', 338500, 'PLG002'),
(8, '2026-10-21', 'Belanja mingguan', 66000, 'PLG003'),
(9, '2026-10-25', 'Pesanan kantor', 376000, 'PLG004'),
(10, '2026-11-02', 'Belanja harian', 87500, 'PLG005'),
(11, '2026-11-05', 'Belanja bulanan', 139000, 'PLG001'),
(12, '2026-11-07', 'Pesanan baru (belum ada detail)', 0, 'PLG003');

INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1, 1, 68000, 1), (1, 2, 38000, 2), (1, 3, 17500, 2),
(2, 6, 3500, 40), (2, 5, 8500, 5),
(3, 3, 17500, 1), (3, 4, 15000, 2), (3, 7, 6000, 3),
(4, 4, 15000, 5), (4, 5, 8500, 10), (4, 3, 17500, 4),
(5, 6, 3500, 10), (5, 7, 6000, 2),
(6, 1, 68000, 2), (6, 2, 38000, 1), (6, 8, 21000, 1),
(7, 6, 3500, 60), (7, 3, 17500, 3), (7, 2, 38000, 2),
(8, 8, 21000, 2), (8, 7, 6000, 4),
(9, 1, 68000, 3), (9, 4, 15000, 3), (9, 5, 8500, 6), (9, 2, 38000, 2),
(10, 3, 17500, 2), (10, 6, 3500, 15),
(11, 1, 68000, 1), (11, 2, 38000, 1), (11, 8, 21000, 1), (11, 7, 6000, 2);

INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id) VALUES
('2026-10-01 11:15:00', 179000, 'TUNAI', 1),
('2026-10-03 12:15:00', 182500, 'TRANSFER', 2),
('2026-10-05 13:15:00', 65500, 'TUNAI', 3),
('2026-10-08 14:15:00', 230000, 'EDC', 4),
('2026-10-10 15:15:00', 47000, 'TUNAI', 5),
('2026-10-14 16:15:00', 195000, 'TRANSFER', 6),
('2026-10-17 17:15:00', 338500, 'TRANSFER', 7),
('2026-10-21 18:15:00', 66000, 'TUNAI', 8),
('2026-10-25 19:15:00', 376000, 'EDC', 9),
('2026-11-02 10:15:00', 87500, 'TUNAI', 10),
('2026-11-05 11:15:00', 139000, 'EDC', 11);
