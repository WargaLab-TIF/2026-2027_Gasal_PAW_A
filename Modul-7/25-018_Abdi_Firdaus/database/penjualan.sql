-- Database: `penjualan`

CREATE DATABASE IF NOT EXISTS `penjualan` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `penjualan`;

CREATE TABLE IF NOT EXISTS `supplier` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `telp` varchar(12) NOT NULL,
  `alamat` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `barang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(10) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `harga` int NOT NULL,
  `stok` int NOT NULL,
  `supplier_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplier_id` (`supplier_id`),
  CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `telp` varchar(12) NOT NULL,
  `alamat` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user` (
  `id_user` tinyint NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(35) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `alamat` varchar(150) NOT NULL,
  `hp` varchar(20) NOT NULL,
  `level` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `transaksi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `waktu_transaksi` date NOT NULL,
  `keterangan` text NOT NULL,
  `total` int NOT NULL,
  `pelanggan_id` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pelanggan_id` (`pelanggan_id`),
  CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `transaksi_detail` (
  `transaksi_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `harga` int NOT NULL,
  `qty` int NOT NULL,
  PRIMARY KEY (`transaksi_id`,`barang_id`),
  KEY `barang_id` (`barang_id`),
  CONSTRAINT `transaksi_detail_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transaksi_detail_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pembayaran` (
  `id` int NOT NULL AUTO_INCREMENT,
  `waktu_bayar` datetime NOT NULL,
  `total` int NOT NULL,
  `metode` enum('TUNAI','TRANSFER','EDC') NOT NULL,
  `transaksi_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksi_id` (`transaksi_id`),
  CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Supplier
INSERT IGNORE INTO `supplier` (`id`, `nama`, `telp`, `alamat`) VALUES
(1, 'PT. Maju Bersama', '08123456', 'Surabaya'),
(2, 'PT. Senang Sekali', '081515563', 'Bangkalan');

-- Data Barang
INSERT IGNORE INTO `barang` (`id`, `kode_barang`, `nama_barang`, `harga`, `stok`, `supplier_id`) VALUES
(1, 'BRG001', 'Buku Tulis Sinar Dunia 58 Lbr', 6500, 100, 1),
(2, 'BRG002', 'Pulpen Standard AE7', 6500, 150, 1),
(3, 'BRG003', 'Kertas HVS A4 70gr', 13000, 80, 2);

-- Data User
INSERT IGNORE INTO `user` (`id_user`, `username`, `password`, `nama`, `alamat`, `hp`, `level`) VALUES
(1, 'admin', MD5('admin123'), 'Administrator', 'Bangkalan', '081234567890', 1);

-- Data Pelanggan (9 Pelanggan Unik sesuai contoh Modul 7)
INSERT IGNORE INTO `pelanggan` (`id`, `nama`, `jenis_kelamin`, `telp`, `alamat`) VALUES
('PLG001', 'Budi Santoso', 'L', '081234567801', 'Jl. Raya Telang No. 1, Bangkalan'),
('PLG002', 'Siti Aminah', 'P', '081234567802', 'Jl. Raya Kamal No. 12, Bangkalan'),
('PLG003', 'Ahmad Fauzi', 'L', '081234567803', 'Jl. Soekarno Hatta No. 5, Bangkalan'),
('PLG004', 'Dewi Lestari', 'P', '081234567804', 'Jl. Trunojoyo No. 8, Bangkalan'),
('PLG005', 'Rizky Pratama', 'L', '081234567805', 'Jl. Pemuda Kaffa No. 19, Bangkalan'),
('PLG006', 'Nurul Hidayah', 'P', '081234567806', 'Jl. Halim Perdana Kusuma No. 3, Bangkalan'),
('PLG007', 'Hendra Wijaya', 'L', '081234567807', 'Jl. KH. Moh. Kholil No. 21, Bangkalan'),
('PLG008', 'Fitriani Putri', 'P', '081234567808', 'Jl. RE. Martadinata No. 10, Bangkalan'),
('PLG009', 'Dimas Saputra', 'L', '081234567809', 'Jl. Panglima Sudirman No. 14, Bangkalan');

-- Data Transaksi sesuai Gambar 1 Modul 7 (Total: Rp 169.000, 9 Pelanggan Unik pada 2023-09-01 s/d 2023-09-09)
INSERT IGNORE INTO `transaksi` (`id`, `waktu_transaksi`, `keterangan`, `total`, `pelanggan_id`) VALUES
(1, '2023-09-01', 'Pembelian alat tulis dan buku', 26000, 'PLG001'),
(2, '2023-09-01', 'Pembelian perlengkapan kantor', 13000, 'PLG002'),
(3, '2023-09-02', 'Pembelian kertas HVS dan tinta', 39000, 'PLG003'),
(4, '2023-09-02', 'Pembelian map dan amplop', 13000, 'PLG004'),
(5, '2023-09-03', 'Pembelian buku catatan', 13000, 'PLG005'),
(6, '2023-09-04', 'Pembelian spidol dan penghapus', 13000, 'PLG006'),
(7, '2023-09-05', 'Pembelian paket alat tulis', 13000, 'PLG007'),
(8, '2023-09-05', 'Pembelian binder dan isi ulang', 13000, 'PLG008'),
(9, '2023-09-06', 'Pembelian pulpen dan penggaris', 13000, 'PLG009'),
(10, '2023-09-09', 'Pembelian kebutuhan administrasi', 13000, 'PLG001');

