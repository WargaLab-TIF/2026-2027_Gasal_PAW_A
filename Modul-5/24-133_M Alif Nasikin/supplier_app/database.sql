-- MODULE 5 : DATABASE - Skema + data contoh (min. 10 data per tabel)
CREATE DATABASE IF NOT EXISTS penjualan;
USE penjualan;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS pembayaran, transaksi_detail, transaksi, barang, supplier, pelanggan, user;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE supplier (
  id     INT(11)      NOT NULL AUTO_INCREMENT,
  nama   VARCHAR(100) NOT NULL,
  telp   VARCHAR(12)  NOT NULL,
  alamat TEXT         NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE pelanggan (
  id            VARCHAR(20)  NOT NULL,
  nama          VARCHAR(100) NOT NULL,
  jenis_kelamin ENUM('L','P') NOT NULL,
  telp          VARCHAR(12)  NOT NULL,
  alamat        TEXT         NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE barang (
  id          INT(11)      NOT NULL AUTO_INCREMENT,
  kode_barang VARCHAR(10)  NOT NULL,
  nama_barang VARCHAR(100) NOT NULL,
  harga       INT(11)      NOT NULL,
  stok        INT(11)      NOT NULL,
  supplier_id INT(11)      NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_barang_supplier FOREIGN KEY (supplier_id)
    REFERENCES supplier(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE transaksi (
  id              INT(11)     NOT NULL AUTO_INCREMENT,
  waktu_transaksi DATE        NOT NULL,
  keterangan      TEXT        NOT NULL,
  total           INT(11)     NOT NULL,
  pelanggan_id    VARCHAR(20) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_transaksi_pelanggan FOREIGN KEY (pelanggan_id)
    REFERENCES pelanggan(id) ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE transaksi_detail (
  transaksi_id INT(11) NOT NULL,
  barang_id    INT(11) NOT NULL,
  harga        INT(11) NOT NULL,
  qty          INT(11) NOT NULL,
  PRIMARY KEY (transaksi_id, barang_id),
  CONSTRAINT fk_detail_transaksi FOREIGN KEY (transaksi_id)
    REFERENCES transaksi(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_detail_barang FOREIGN KEY (barang_id)
    REFERENCES barang(id) ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE pembayaran (
  id           INT(11)  NOT NULL AUTO_INCREMENT,
  waktu_bayar  DATETIME NOT NULL,
  total        INT(11)  NOT NULL,
  metode       ENUM('TUNAI','TRANSFER','EDC') NOT NULL,
  transaksi_id INT(11)  NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_bayar_transaksi FOREIGN KEY (transaksi_id)
    REFERENCES transaksi(id) ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE user (
  id_user  TINYINT(2)   NOT NULL AUTO_INCREMENT,
  username VARCHAR(30)  NOT NULL,
  password VARCHAR(35)  NOT NULL,
  nama     VARCHAR(50)  NOT NULL,
  alamat   VARCHAR(150) NOT NULL,
  hp       VARCHAR(20)  NOT NULL,
  level    TINYINT(1)   NOT NULL COMMENT '1=admin, 2=kasir, 3=owner',
  PRIMARY KEY (id_user)
) ENGINE=InnoDB;

-- ===================== DATA MASTER =====================
INSERT INTO supplier (nama, telp, alamat) VALUES
('PT. Maju Bersama',    '08123456',     'Surabaya'),
('PT. Senang Sekali',   '081515563',    'Bangkalan'),
('PT. Segar Segar',     '0845454663',   'Surabaya'),
('CV. Sumber Rejeki',   '081230001111', 'Sidoarjo'),
('PT. Berkah Jaya',     '082140002222', 'Gresik'),
('CV. Mitra Abadi',     '085730003333', 'Malang'),
('PT. Cahaya Utama',    '081330004444', 'Mojokerto'),
('UD. Tani Makmur',     '087850005555', 'Lamongan'),
('PT. Sinar Pangan',    '031555666',    'Surabaya'),
('CV. Harapan Baru',    '081940007777', 'Jombang');

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('PLG001', 'Budi Santoso',   'L', '081234560001', 'Jl. Darmo No. 10, Surabaya'),
('PLG002', 'Siti Aminah',    'P', '081234560002', 'Jl. Kenjeran No. 25, Surabaya'),
('PLG003', 'Agus Pratama',   'L', '081234560003', 'Jl. Rungkut No. 8, Surabaya'),
('PLG004', 'Dewi Lestari',   'P', '081234560004', 'Jl. Gubeng No. 12, Surabaya'),
('PLG005', 'Rudi Hartono',   'L', '081234560005', 'Jl. Wonokromo No. 3, Surabaya'),
('PLG006', 'Rina Wulandari', 'P', '081234560006', 'Jl. Manyar No. 41, Surabaya'),
('PLG007', 'Eko Prasetyo',   'L', '081234560007', 'Jl. Tenggilis No. 19, Surabaya'),
('PLG008', 'Nur Hidayah',    'P', '081234560008', 'Jl. Mulyorejo No. 6, Surabaya'),
('PLG009', 'Bambang Susilo', 'L', '081234560009', 'Jl. Sukolilo No. 77, Surabaya'),
('PLG010', 'Maya Sari',      'P', '081234560010', 'Jl. Dukuh Kupang No. 5, Surabaya');

INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES
('BRG001', 'Beras Premium 5kg', 65000, 100, 1),
('BRG002', 'Minyak Goreng 2L',  38000,  80, 2),
('BRG003', 'Gula Pasir 1kg',    16000, 150, 3),
('BRG004', 'Telur Ayam 1kg',    28000,  60, 4),
('BRG005', 'Tepung Terigu 1kg', 12000, 120, 5),
('BRG006', 'Kopi Bubuk 200g',   22000,  90, 6),
('BRG007', 'Teh Celup 25s',      9000, 200, 7),
('BRG008', 'Susu UHT 1L',       18000,  75, 8),
('BRG009', 'Mie Instan 5pcs',   15000, 300, 9),
('BRG010', 'Sabun Mandi 3pcs',  14000, 110, 10);

INSERT INTO user (username, password, nama, alamat, hp, level) VALUES
('admin',   MD5('admin123'),  'Administrator',  'Jl. Raya Darmo 1, Surabaya',  '081200000001', 1),
('owner',   MD5('owner123'),  'Hendra Wijaya',  'Jl. Pemuda 20, Surabaya',     '081200000002', 3),
('kasir01', MD5('kasir123'),  'Ani Wijaya',     'Jl. Ketintang 5, Surabaya',   '081200000003', 2),
('kasir02', MD5('kasir123'),  'Candra Putra',   'Jl. Jemursari 9, Surabaya',   '081200000004', 2),
('kasir03', MD5('kasir123'),  'Dian Permata',   'Jl. Menur 14, Surabaya',      '081200000005', 2),
('kasir04', MD5('kasir123'),  'Fajar Nugroho',  'Jl. Barata Jaya 2, Surabaya', '081200000006', 2),
('kasir05', MD5('kasir123'),  'Gita Savitri',   'Jl. Klampis 31, Surabaya',    '081200000007', 2),
('kasir06', MD5('kasir123'),  'Hadi Kurniawan', 'Jl. Nginden 18, Surabaya',    '081200000008', 2),
('kasir07', MD5('kasir123'),  'Indra Gunawan',  'Jl. Karang Menjangan 7, Sby', '081200000009', 2),
('kasir08', MD5('kasir123'),  'Joko Susanto',   'Jl. Pucang Anom 11, Surabaya','081200000010', 2);

-- ===================== DATA TRANSAKSI =====================
INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES
('2026-10-01', 'Belanja bulanan',        97000, 'PLG001'),
('2026-10-03', 'Beli minyak goreng',     76000, 'PLG002'),
('2026-10-05', 'Bahan kue',              64000, 'PLG003'),
('2026-10-08', 'Stok kopi dan teh',      62000, 'PLG004'),
('2026-10-10', 'Susu untuk anak',        72000, 'PLG005'),
('2026-10-12', 'Kebutuhan harian',       59000, 'PLG006'),
('2026-10-15', 'Beras untuk warung',    130000, 'PLG007'),
('2026-10-18', 'Gula dan teh',           89000, 'PLG008'),
('2026-10-22', 'Minyak dan kopi',        60000, 'PLG009'),
('2026-10-28', 'Belanja akhir bulan',    75000, 'PLG010');

INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1, 1, 65000, 1), (1, 3, 16000, 2),
(2, 2, 38000, 2),
(3, 4, 28000, 1), (3, 5, 12000, 3),
(4, 6, 22000, 2), (4, 7,  9000, 2),
(5, 8, 18000, 4),
(6, 9, 15000, 3), (6, 10, 14000, 1),
(7, 1, 65000, 2),
(8, 3, 16000, 5), (8, 7,  9000, 1),
(9, 2, 38000, 1), (9, 6, 22000, 1),
(10, 5, 12000, 2), (10, 8, 18000, 2), (10, 9, 15000, 1);

INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id) VALUES
('2026-10-01 10:15:00',  97000, 'TUNAI',    1),
('2026-10-03 11:30:00',  76000, 'TRANSFER', 2),
('2026-10-05 09:45:00',  64000, 'TUNAI',    3),
('2026-10-08 14:20:00',  62000, 'EDC',      4),
('2026-10-10 16:05:00',  72000, 'TUNAI',    5),
('2026-10-12 13:10:00',  59000, 'TRANSFER', 6),
('2026-10-15 08:50:00', 130000, 'EDC',      7),
('2026-10-18 15:35:00',  89000, 'TUNAI',    8),
('2026-10-22 12:00:00',  60000, 'TRANSFER', 9),
('2026-10-28 17:25:00',  75000, 'EDC',     10);
