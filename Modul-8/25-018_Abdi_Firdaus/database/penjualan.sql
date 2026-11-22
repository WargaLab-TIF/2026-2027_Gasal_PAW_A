-- skema database penjualan

CREATE DATABASE IF NOT EXISTS `penjualan` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `penjualan`;

CREATE TABLE IF NOT EXISTS `supplier` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `telp` VARCHAR(12) NOT NULL,
  `alamat` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id` VARCHAR(20) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `jenis_kelamin` ENUM('L','P') NOT NULL,
  `telp` VARCHAR(12) NOT NULL,
  `alamat` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user` (
  `id_user` TINYINT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(30) NOT NULL,
  `password` VARCHAR(35) NOT NULL,
  `nama` VARCHAR(50) NOT NULL,
  `alamat` VARCHAR(150) NOT NULL,
  `hp` VARCHAR(20) NOT NULL,
  `level` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `barang` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `kode_barang` VARCHAR(10) NOT NULL,
  `nama_barang` VARCHAR(100) NOT NULL,
  `harga` INT NOT NULL,
  `stok` INT NOT NULL,
  `supplier_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_barang_supplier` (`supplier_id`),
  CONSTRAINT `fk_barang_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `transaksi` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `waktu_transaksi` DATE NOT NULL,
  `keterangan` TEXT NOT NULL,
  `total` INT NOT NULL,
  `pelanggan_id` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_transaksi_pelanggan` (`pelanggan_id`),
  CONSTRAINT `fk_transaksi_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `transaksi_detail` (
  `transaksi_id` INT NOT NULL,
  `barang_id` INT NOT NULL,
  `harga` INT NOT NULL,
  `qty` INT NOT NULL,
  PRIMARY KEY (`transaksi_id`, `barang_id`),
  KEY `fk_detail_barang` (`barang_id`),
  CONSTRAINT `fk_detail_transaksi` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_detail_barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pembayaran` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `waktu_bayar` DATETIME NOT NULL,
  `total` INT NOT NULL,
  `metode` ENUM('TUNAI','TRANSFER','EDC') NOT NULL,
  `transaksi_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pembayaran_transaksi` (`transaksi_id`),
  CONSTRAINT `fk_pembayaran_transaksi` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Awal Supplier
INSERT IGNORE INTO `supplier` (`id`, `nama`, `telp`, `alamat`) VALUES
(1, 'PT. Maju Bersama', '08123456', 'Surabaya'),
(2, 'PT. Senang Sekali', '081515563', 'Bangkalan'),
(3, 'PT. Segar Segar', '0845454663', 'Surabaya'),
(4, 'PT. Sumber Makmur', '081234567804', 'Sidoarjo'),
(5, 'PT. Jaya Abadi', '081234567805', 'Gresik'),
(6, 'PT. Berkah Mandiri', '081234567806', 'Malang'),
(7, 'PT. Cahaya Timur', '081234567807', 'Pamekasan'),
(8, 'PT. Nusantara Jaya', '081234567808', 'Sampang'),
(9, 'PT. Indo Pangan', '081234567809', 'Sumenep'),
(10, 'PT. Global Niaga', '081234567810', 'Surabaya');

-- Data Awal Pelanggan
INSERT IGNORE INTO `pelanggan` (`id`, `nama`, `jenis_kelamin`, `telp`, `alamat`) VALUES
('1', 'Budi Santoso', 'L', '081111222301', 'Bangkalan'),
('2', 'Siti Aminah', 'P', '081111222302', 'Surabaya'),
('3', 'Ahmad Fauzi', 'L', '081111222303', 'Sidoarjo'),
('4', 'Dewi Lestari', 'P', '081111222304', 'Gresik'),
('5', 'Rizky Pratama', 'L', '081111222305', 'Sampang'),
('6', 'Nurul Hidayah', 'P', '081111222306', 'Pamekasan'),
('7', 'Hendra Wijaya', 'L', '081111222307', 'Sumenep'),
('8', 'Maya Sari', 'P', '081111222308', 'Malang'),
('9', 'Fajar Nugroho', 'L', '081111222309', 'Bangkalan'),
('10', 'Putri Rahayu', 'P', '081111222310', 'Surabaya');

-- data awal user (level 1 owner, level 2 kasir)
INSERT IGNORE INTO `user` (`id_user`, `username`, `password`, `nama`, `alamat`, `hp`, `level`) VALUES
(1, 'admin', MD5('admin123'), 'Administrator', 'Surabaya', '081900000001', 1),
(2, 'kasir1', MD5('kasir123'), 'Andi Kasir', 'Bangkalan', '081900000002', 2),
(3, 'kasir2', MD5('kasir123'), 'Bela Kasir', 'Surabaya', '081900000003', 2),
(4, 'gudang1', MD5('gudang123'), 'Candra Gudang', 'Sidoarjo', '081900000004', 2),
(5, 'gudang2', MD5('gudang123'), 'Dina Gudang', 'Gresik', '081900000005', 2),
(6, 'manager', MD5('manager123'), 'Eko Manager', 'Surabaya', '081900000006', 1),
(7, 'staff1', MD5('staff123'), 'Fani Staff', 'Bangkalan', '081900000007', 2),
(8, 'staff2', MD5('staff123'), 'Gilang Staff', 'Sampang', '081900000008', 2),
(9, 'supervisor', MD5('spv123'), 'Hani Supervisor', 'Pamekasan', '081900000009', 1),
(10, 'operator', MD5('op123'), 'Ivan Operator', 'Sumenep', '081900000010', 2);

-- Data Awal Barang
INSERT IGNORE INTO `barang` (`id`, `kode_barang`, `nama_barang`, `harga`, `stok`, `supplier_id`) VALUES
(1, 'BRG001', 'Beras Premium 5Kg', 65000, 50, 1),
(2, 'BRG002', 'Minyak Goreng 2L', 35000, 80, 2),
(3, 'BRG003', 'Gula Pasir 1Kg', 16000, 100, 3),
(4, 'BRG004', 'Tepung Terigu 1Kg', 12000, 60, 4),
(5, 'BRG005', 'Kopi Bubuk 200g', 15000, 75, 5),
(6, 'BRG006', 'Teh Celup Kotak', 8000, 120, 6),
(7, 'BRG007', 'Susu Kental Manis', 11000, 90, 7),
(8, 'BRG008', 'Mie Instan Goreng', 3500, 200, 8),
(9, 'BRG009', 'Kecap Manis 500ml', 18000, 45, 9),
(10, 'BRG010', 'Sabun Mandi Cair', 22000, 65, 10);

-- Data Awal Transaksi
INSERT IGNORE INTO `transaksi` (`id`, `waktu_transaksi`, `keterangan`, `total`, `pelanggan_id`) VALUES
(1, '2026-10-01', 'Pembelian sembako bulanan', 65000, '1'),
(2, '2026-10-02', 'Pembelian minyak goreng', 70000, '2'),
(3, '2026-10-03', 'Pembelian gula pasir', 48000, '3'),
(4, '2026-10-04', 'Pembelian bahan kue', 24000, '4'),
(5, '2026-10-05', 'Pembelian kopi', 30000, '5'),
(6, '2026-10-06', 'Pembelian teh celup', 40000, '6'),
(7, '2026-10-07', 'Pembelian susu', 33000, '7'),
(8, '2026-10-08', 'Pembelian mie instan', 35000, '8'),
(9, '2026-10-09', 'Pembelian kecap manis', 36000, '9'),
(10, '2026-10-10', 'Pembelian kebutuhan mandi', 44000, '10');

-- Data Awal Detail Transaksi
INSERT IGNORE INTO `transaksi_detail` (`transaksi_id`, `barang_id`, `harga`, `qty`) VALUES
(1, 1, 65000, 1),
(2, 2, 35000, 2),
(3, 3, 16000, 3),
(4, 4, 12000, 2),
(5, 5, 15000, 2),
(6, 6, 8000, 5),
(7, 7, 11000, 3),
(8, 8, 3500, 10),
(9, 9, 18000, 2),
(10, 10, 22000, 2);

-- Data Awal Pembayaran
INSERT IGNORE INTO `pembayaran` (`id`, `waktu_bayar`, `total`, `metode`, `transaksi_id`) VALUES
(1, '2026-10-01 09:15:00', 65000, 'TUNAI', 1),
(2, '2026-10-02 10:20:00', 70000, 'TRANSFER', 2),
(3, '2026-10-03 11:05:00', 48000, 'EDC', 3),
(4, '2026-10-04 13:45:00', 24000, 'TUNAI', 4),
(5, '2026-10-05 14:10:00', 30000, 'TUNAI', 5),
(6, '2026-10-06 15:30:00', 40000, 'TRANSFER', 6),
(7, '2026-10-07 16:00:00', 33000, 'EDC', 7),
(8, '2026-10-08 18:25:00', 35000, 'TUNAI', 8),
(9, '2026-10-09 19:40:00', 36000, 'TRANSFER', 9),
(10, '2026-10-10 20:10:00', 44000, 'EDC', 10);
