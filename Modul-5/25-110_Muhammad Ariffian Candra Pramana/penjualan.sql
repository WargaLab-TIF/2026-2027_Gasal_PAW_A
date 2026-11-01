-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 01, 2026 at 12:34 PM
-- Server version: 8.0.30
-- PHP Version: 8.4.20RC1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `penjualan`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int NOT NULL,
  `kode_barang` varchar(10) DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `stok` int DEFAULT NULL,
  `supplier_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `kode_barang`, `nama_barang`, `harga`, `stok`, `supplier_id`) VALUES
(1, 'BRG001', 'Mie Instan Kuah', 3000, 500, 2),
(2, 'BRG002', 'Sabun Mandi Cair', 15000, 100, 3),
(3, 'BRG003', 'Kecap Manis 520ml', 22000, 80, 9),
(4, 'BRG004', 'Kopi Sachet', 12000, 200, 5),
(5, 'BRG005', 'Susu UHT 1L', 18000, 150, 8),
(6, 'BRG006', 'Teh Celup Kotak', 8000, 120, 3),
(7, 'BRG007', 'Minyak Goreng 2L', 34000, 60, 2),
(8, 'BRG008', 'Deterjen Bubuk 1kg', 28000, 90, 1),
(9, 'BRG009', 'Snack Kacang', 10000, 300, 4),
(10, 'BRG010', 'Air Mineral 600ml', 3500, 1000, 10);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `telp` varchar(12) DEFAULT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `jenis_kelamin`, `telp`, `alamat`) VALUES
(1, 'Ahmad Hidayat', 'L', '085712345671', 'Perum Indah Blok A1'),
(2, 'Nisa Sabyan', 'P', '085712345672', 'Perum Indah Blok A2'),
(3, 'Reza Rahadian', 'L', '085712345673', 'Perum Indah Blok B1'),
(4, 'Chelsea Islan', 'P', '085712345674', 'Perum Indah Blok B2'),
(5, 'Iqbaal Ramadhan', 'L', '085712345675', 'Perum Indah Blok C1'),
(6, 'Dian Sastro', 'P', '085712345676', 'Perum Indah Blok C2'),
(7, 'Chicco Jerikho', 'L', '085712345677', 'Perum Indah Blok D1'),
(8, 'Raisa Andriana', 'P', '085712345678', 'Perum Indah Blok D2'),
(9, 'Vino G Bastian', 'L', '085712345679', 'Perum Indah Blok E1'),
(10, 'Isyana Sarasvati', 'P', '085712345680', 'Perum Indah Blok E2');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int NOT NULL,
  `waktu_bayar` datetime DEFAULT NULL,
  `total` int DEFAULT NULL,
  `metode` enum('TUNAI','TRANSFER','EDC') DEFAULT NULL,
  `transaksi_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `waktu_bayar`, `total`, `metode`, `transaksi_id`) VALUES
(1, '2024-01-10 09:15:00', 30000, 'TUNAI', 1),
(2, '2024-01-11 11:30:20', 68000, 'TRANSFER', 2),
(3, '2024-01-12 14:45:10', 22000, 'EDC', 3),
(4, '2024-01-13 16:20:00', 36000, 'TUNAI', 4),
(5, '2024-01-14 10:05:30', 20000, 'TRANSFER', 5),
(6, '2024-01-15 08:30:00', 43000, 'TUNAI', 6),
(7, '2024-01-16 19:10:45', 16000, 'EDC', 7),
(8, '2024-01-17 12:25:00', 17500, 'TUNAI', 8),
(9, '2024-01-18 15:50:15', 30000, 'TRANSFER', 9),
(10, '2024-01-19 17:40:00', 54000, 'EDC', 10);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `telp` varchar(14) DEFAULT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `nama`, `telp`, `alamat`) VALUES
(1, 'PT Wings Food', '02188887771', 'Kawasan Industri Pulo Gadung'),
(2, 'PT Indofood', '02188887772', 'Kawasan Industri MM2100'),
(3, 'PT Unilever', '02188887773', 'Kawasan Industri Jababeka'),
(4, 'PT Garuda Food', '02188887774', 'Kawasan Industri KIIC Karawang'),
(5, 'PT Mayora', '02188887775', 'Tangerang Industrial Estate'),
(6, 'CV Makmur Jaya', '0223344551', 'Cibaduyut, Bandung'),
(7, 'UD Sumber Rejeki', '0314455661', 'Rungkut, Surabaya'),
(8, 'PT Nestle Indo', '0219988771', 'Cikupa, Tangerang'),
(9, 'PT ABC President', '0215566771', 'Karawang Barat'),
(10, 'CV Bintang Terang', '0245566772', 'Kawasan Candi, Semarang');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int NOT NULL,
  `waktu_transaksi` date DEFAULT NULL,
  `keterangan` text,
  `total` int DEFAULT NULL,
  `pelanggan_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `waktu_transaksi`, `keterangan`, `total`, `pelanggan_id`) VALUES
(1, '2024-01-10', 'Pembelian Rutin', 30000, 1),
(2, '2024-01-11', 'Belanja Bulanan', 68000, 2),
(3, '2024-01-12', 'Beli Kebutuhan', 22000, 3),
(4, '2024-01-13', 'Eceran', 36000, 4),
(5, '2024-01-14', 'Beli Snack', 20000, 5),
(6, '2024-01-15', 'Kebutuhan Mandi', 43000, 6),
(7, '2024-01-16', 'Stok Dapur', 16000, 7),
(8, '2024-01-17', 'Belanja Cepat', 17500, 8),
(9, '2024-01-18', 'Borong Mie', 30000, 9),
(10, '2024-01-19', 'Acara Keluarga', 54000, 10);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `transaksi_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `harga` int DEFAULT NULL,
  `qty` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`transaksi_id`, `barang_id`, `harga`, `qty`) VALUES
(1, 1, 3000, 10),
(2, 7, 34000, 2),
(3, 3, 22000, 1),
(4, 4, 12000, 3),
(5, 9, 10000, 2),
(6, 2, 15000, 1),
(6, 8, 28000, 1),
(7, 6, 8000, 2),
(8, 10, 3500, 5),
(9, 1, 3000, 10),
(10, 5, 18000, 3);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` tinyint NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(35) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` varchar(150) DEFAULT NULL,
  `hp` varchar(20) DEFAULT NULL,
  `level` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `alamat`, `hp`, `level`) VALUES
(1, 'admin1', '5f4dcc3b5aa765d61d8327deb882cf99', 'Budi Santoso', 'Jl. Merdeka No. 1, Jakarta', '081111111111', 1),
(2, 'kasir1', '5f4dcc3b5aa765d61d8327deb882cf99', 'Siti Aminah', 'Jl. Sudirman No. 2, Bandung', '081222222222', 2),
(3, 'kasir2', '5f4dcc3b5aa765d61d8327deb882cf99', 'Andi Darmawan', 'Jl. Pahlawan No. 3, Surabaya', '081333333333', 2),
(4, 'gudang1', '5f4dcc3b5aa765d61d8327deb882cf99', 'Joko Widodo', 'Jl. Veteran No. 4, Semarang', '081444444444', 3),
(5, 'admin2', '5f4dcc3b5aa765d61d8327deb882cf99', 'Rina Wati', 'Jl. Melati No. 5, Yogyakarta', '081555555555', 1),
(6, 'kasir3', '5f4dcc3b5aa765d61d8327deb882cf99', 'Hendra Gunawan', 'Jl. Anggrek No. 6, Malang', '081666666666', 2),
(7, 'kasir4', '5f4dcc3b5aa765d61d8327deb882cf99', 'Dewi Lestari', 'Jl. Mawar No. 7, Bali', '081777777777', 2),
(8, 'gudang2', '5f4dcc3b5aa765d61d8327deb882cf99', 'Rizky Febian', 'Jl. Kenanga No. 8, Medan', '081888888888', 3),
(9, 'admin3', '5f4dcc3b5aa765d61d8327deb882cf99', 'Maya Sari', 'Jl. Flamboyan No. 9, Makassar', '081999999999', 1),
(10, 'kasir5', '5f4dcc3b5aa765d61d8327deb882cf99', 'Fajar Ramadhan', 'Jl. Teratai No. 10, Palembang', '081000000000', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_supplier` (`supplier_id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_transaksi` (`transaksi_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pelanggan` (`pelanggan_id`);

--
-- Indexes for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD PRIMARY KEY (`transaksi_id`,`barang_id`),
  ADD KEY `barang_id` (`barang_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` tinyint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `fk_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`);

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_transaksi` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`);

--
-- Constraints for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD CONSTRAINT `transaksi_detail_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`),
  ADD CONSTRAINT `transaksi_detail_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
