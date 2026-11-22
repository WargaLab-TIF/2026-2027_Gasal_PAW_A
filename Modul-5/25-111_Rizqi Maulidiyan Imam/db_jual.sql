-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2026 at 03:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_jual`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `kode_barang` varchar(10) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `kode_barang`, `nama_barang`, `harga`, `stok`, `supplier_id`) VALUES
(1, 'BRG001', 'Minyak Goreng 2L', 35000, 50, 1),
(2, 'BRG002', 'Air Mineral 1L', 25000, 26, 2),
(3, 'BRG003', 'SGM 2kg', 65000, 40, 3),
(4, 'BRG004', 'Kantong Plastik 1kg', 70000, 84, 4),
(5, 'BRG005', 'Beras Premium 5kg', 80000, 34, 5),
(6, 'BRG006', 'Air Galon 1L', 55000, 24, 6),
(7, 'BRG007', 'Tepung 4kg', 37000, 56, 7),
(8, 'BRG008', 'Gula 2kg', 50000, 24, 8),
(9, 'BRG009', 'Telur 1kg', 35000, 37, 9),
(10, 'BRG0010', 'Susu 1L', 45000, 48, 10);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `telp` varchar(12) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `jenis_kelamin`, `telp`, `alamat`) VALUES
(1, 'Rudi Hartono', 'L', '081210001001', 'Jl. Pemuda No. 3, Bangkalan'),
(2, 'Lina Marlina', 'P', '081210001002', 'Jl. Merdeka No. 17, Bangkalan'),
(3, 'Bayu Setiawan', 'L', '081210001003', 'Jl. Ahmad Yani No. 24, Surabaya'),
(4, 'Maya Anggraini', 'P', '081210001004', 'Jl. Pahlawan No. 8, Sampang'),
(5, 'Dimas Prakoso', 'L', '081210001005', 'Jl. Sudirman No. 41, Pamekasan'),
(6, 'Putri Ayu Lestari', 'P', '081210001006', 'Jl. Gajah Mada No. 12, Sumenep'),
(7, 'Hendra Gunawan', 'L', '081210001007', 'Jl. Veteran No. 30, Surabaya'),
(8, 'Nadia Safitri', 'P', '081210001008', 'Jl. Kartini No. 5, Bangkalan'),
(9, 'Taufik Rahman', 'L', '081210001009', 'Jl. Pattimura No. 19, Sidoarjo'),
(10, 'Wulan Sari', 'P', '081210001010', 'Jl. Mawar No. 2, Bangkalan');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `waktu_bayar` datetime NOT NULL,
  `total` int(11) NOT NULL,
  `metode` enum('TUNAI','TRANSFER','EDC') NOT NULL,
  `transaksi_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `waktu_bayar`, `total`, `metode`, `transaksi_id`) VALUES
(1, '2026-10-01 10:30:00', 215000, 'TRANSFER', 1),
(2, '2026-10-02 10:30:00', 165000, 'EDC', 2),
(3, '2026-10-03 10:30:00', 160000, 'TUNAI', 3),
(4, '2026-10-04 10:30:00', 124000, 'TRANSFER', 4),
(5, '2026-10-05 10:30:00', 125000, 'EDC', 5),
(6, '2026-10-06 10:30:00', 220000, 'TUNAI', 6),
(7, '2026-10-07 10:30:00', 265000, 'TRANSFER', 7),
(8, '2026-10-08 10:30:00', 145000, 'EDC', 8),
(9, '2026-10-09 10:30:00', 147000, 'TUNAI', 9),
(10, '2026-10-10 10:30:00', 205000, 'TRANSFER', 10);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `telp` varchar(12) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `nama`, `telp`, `alamat`) VALUES
(1, 'PT. Ben Clipp', '08123456', 'Surabaya'),
(2, 'PT. Ikhlasin Aja', '081263237', 'Bangkalan'),
(3, 'PT. Suka Jajan', '08143792', 'Malang'),
(4, 'PT. Sejahtera', '08589246', 'Sidoarjo'),
(5, 'PT. When yah', '08598952', 'Pasuruan'),
(6, 'PT. Jalan Jalan', '08508773', 'Tuban'),
(7, 'PT. Basmalah', '084772904', 'Burneh'),
(8, 'PT. Iman Jaya', '080876280', 'Burneh'),
(9, 'PT. Senja Wijaya', '081515563', 'Bangkalan'),
(10, 'PT. Apalah Daya', '08189893', 'Bangkalan');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `waktu_transaksi` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `total` int(11) NOT NULL,
  `pelanggan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `waktu_transaksi`, `keterangan`, `total`, `pelanggan_id`) VALUES
(1, '2026-10-01', 'Belanja kebutuhan bulanan', 215000, 1),
(2, '2026-10-02', 'Belanja dapur', 165000, 2),
(3, '2026-10-03', 'Pembelian grosir', 160000, 3),
(4, '2026-10-04', 'Belanja mingguan', 124000, 4),
(5, '2026-10-05', 'Keperluan rumah', 125000, 5),
(6, '2026-10-06', 'Pembelian rutin', 220000, 6),
(7, '2026-10-07', 'Belanja harian', 265000, 7),
(8, '2026-10-08', 'Pembelian sembako', 145000, 8),
(9, '2026-10-09', 'Belanja akhir pekan', 147000, 9),
(10, '2026-10-10', 'Pembelian eceran', 205000, 10);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `transaksi_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`transaksi_id`, `barang_id`, `harga`, `qty`) VALUES
(1, 1, 35000, 2),
(1, 3, 65000, 1),
(1, 5, 80000, 1),
(2, 2, 25000, 1),
(2, 4, 70000, 2),
(3, 6, 55000, 1),
(3, 9, 35000, 3),
(4, 7, 37000, 2),
(4, 8, 50000, 1),
(5, 1, 35000, 1),
(5, 10, 45000, 2),
(6, 2, 25000, 1),
(6, 3, 65000, 3),
(7, 4, 70000, 1),
(7, 5, 80000, 2),
(7, 9, 35000, 1),
(8, 8, 50000, 2),
(8, 10, 45000, 1),
(9, 6, 55000, 2),
(9, 7, 37000, 1),
(10, 3, 65000, 1),
(10, 9, 35000, 4);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` tinyint(2) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(35) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` varchar(150) DEFAULT NULL,
  `hp` varchar(20) DEFAULT NULL,
  `level` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `alamat`, `hp`, `level`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'Rudi Admin', 'Bangkalan', '081300000001', 1),
(2, 'kasir1', 'de28f8f7998f23ab4194b51a6029416f', 'Lina Kasir', 'Bangkalan', '081300000002', 2),
(3, 'kasir2', 'de28f8f7998f23ab4194b51a6029416f', 'Bayu Kasir', 'Surabaya', '081300000003', 2),
(4, 'kasir3', 'de28f8f7998f23ab4194b51a6029416f', 'Maya Kasir', 'Sampang', '081300000004', 2),
(5, 'gudang1', 'cbb7449d78314665f9e7c7dd0a18a68a', 'Dimas Gudang', 'Pamekasan', '081300000005', 3),
(6, 'gudang2', 'cbb7449d78314665f9e7c7dd0a18a68a', 'Putri Gudang', 'Sumenep', '081300000006', 3),
(7, 'gudang3', 'cbb7449d78314665f9e7c7dd0a18a68a', 'Hendra Gudang', 'Surabaya', '081300000007', 3),
(8, 'manager', '0795151defba7a4b5dfa89170de46277', 'Nadia Manager', 'Bangkalan', '081300000008', 1),
(9, 'owner', '5be057accb25758101fa5eadbbd79503', 'Taufik Owner', 'Sidoarjo', '081300000009', 1),
(10, 'sales1', '0ad80eb119d9bf7775aa23786b05b391', 'Wulan Sales', 'Bangkalan', '081300000010', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

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
  ADD KEY `transaksi_id` (`transaksi_id`);

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
  ADD KEY `pelanggan_id` (`pelanggan_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` tinyint(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`);

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`);

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
