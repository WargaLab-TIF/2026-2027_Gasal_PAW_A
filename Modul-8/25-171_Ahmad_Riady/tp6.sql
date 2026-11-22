-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2026 at 08:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tp5`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `kode_barang` varchar(30) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `stok` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `kode_barang`, `nama_barang`, `harga`, `stok`, `supplier_id`) VALUES
(1, 'BRG001', 'Mouse Wireless', 150000.00, 20, 1),
(2, 'BRG002', 'Keyboard Mechanical', 350000.00, 15, 2),
(3, 'BRG003', 'Monitor 24 Inch', 1850000.00, 8, 3),
(4, 'BRG004', 'Laptop Lenovo', 6500000.00, 10, 4),
(5, 'BRG005', 'Flashdisk 64GB', 95000.00, 30, 5),
(6, 'BRG006', 'SSD 512GB', 750000.00, 12, 6),
(7, 'BRG007', 'Headset', 250000.00, 18, 7),
(8, 'BRG008', 'Webcam', 400000.00, 10, 8),
(9, 'BRG009', 'Printer', 2200000.00, 5, 9),
(10, 'BRG010', 'Speaker', 300000.00, 14, 10);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `telp` varchar(12) NOT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `jenis_kelamin`, `telp`, `alamat`) VALUES
(1, 'Rian', 'L', '085233373927', 'Sumenep'),
(2, 'Ayik', 'L', '087268364513', 'Malang'),
(3, 'Nafa', 'P', '081234567892', 'Pamekasan'),
(4, 'Ira', 'L', '081234567893', 'Sampang'),
(5, 'Dewi', 'P', '081234567894', 'Sumenep'),
(6, 'Alya', 'L', '081234567895', 'Gresik'),
(7, 'Putri', 'P', '081234567896', 'Lamongan'),
(8, 'Fajar', 'L', '081234567897', 'Madura'),
(9, 'Nadia', 'P', '081234567898', 'Surabaya'),
(10, 'Dimas', 'L', '081234567899', 'Bangkalan');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `waktu_bayar` datetime NOT NULL,
  `total` int(11) NOT NULL,
  `metode` enum('Tunai','Transfer','EDC') NOT NULL,
  `transaksi_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `waktu_bayar`, `total`, `metode`, `transaksi_id`) VALUES
(1, '2026-10-20 09:05:00', 300000, 'Tunai', 1),
(2, '2026-10-21 10:05:00', 350000, 'Transfer', 2),
(3, '2026-10-22 11:05:00', 1850000, 'EDC', 3),
(4, '2026-10-23 12:05:00', 6500000, 'Tunai', 4),
(5, '2026-10-24 13:05:00', 285000, 'Transfer', 5),
(6, '2026-10-25 14:05:00', 1500000, 'EDC', 6),
(7, '2026-10-26 15:05:00', 500000, 'Tunai', 7),
(8, '2026-10-27 09:35:00', 400000, 'Transfer', 8),
(9, '2026-10-28 10:35:00', 2200000, 'EDC', 9),
(10, '2026-10-29 11:35:00', 600000, 'Tunai', 10);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `telp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `nama`, `telp`, `alamat`) VALUES
(1, 'PT Sumber Makmur', '081234567801', 'Surabaya'),
(2, 'CV Jaya Abadi', '081234567802', 'Bangkalan'),
(3, 'PT Maju Bersama', '081234567803', 'Sidoarjo'),
(4, 'CV Berkah Jaya', '081234567804', 'Pamekasan'),
(5, 'PT Sentosa', '081234567805', 'Gresik'),
(6, 'CV Mitra Sejahtera', '081234567806', 'Lamongan'),
(7, 'PT Teknologi Nusantara', '081234567807', 'Malang'),
(8, 'CV Sinar Baru', '081234567808', 'Madura'),
(9, 'PT Cipta Komputer', '081234567809', 'Surabaya'),
(10, 'CV Digital Mandiri', '081234567810', 'Bangkalan');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `waktu_transaksi` datetime NOT NULL,
  `keterangan` text DEFAULT NULL,
  `total` int(11) NOT NULL,
  `pelanggan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `waktu_transaksi`, `keterangan`, `total`, `pelanggan_id`) VALUES
(1, '2026-10-20 09:00:00', 'Pembelian Mouse', 300000, 1),
(2, '2026-10-21 10:00:00', 'Pembelian Keyboard', 350000, 2),
(3, '2026-10-22 11:00:00', 'Pembelian Monitor', 1850000, 3),
(4, '2026-10-23 12:00:00', 'Pembelian Laptop', 6500000, 4),
(5, '2026-10-24 13:00:00', 'Pembelian Flashdisk', 285000, 5),
(6, '2026-10-25 14:00:00', 'Pembelian SSD', 1500000, 6),
(7, '2026-10-26 15:00:00', 'Pembelian Headset', 500000, 7),
(8, '2026-10-27 09:30:00', 'Pembelian Webcam', 400000, 8),
(9, '2026-10-28 10:30:00', 'Pembelian Printer', 2200000, 9),
(10, '2026-10-29 11:30:00', 'Pembelian Speaker', 600000, 10);

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
(1, 1, 150000, 2),
(2, 2, 350000, 1),
(3, 3, 1850000, 1),
(4, 4, 6500000, 1),
(5, 5, 95000, 3),
(6, 6, 750000, 2),
(7, 7, 250000, 2),
(8, 8, 400000, 1),
(9, 9, 2200000, 1),
(10, 10, 300000, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` tinyint(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `hp` varchar(20) DEFAULT NULL,
  `level` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `alamat`, `hp`, `level`) VALUES
(1, 'admin', 'admin123', 'Administrator', 'Bangkalan', '081234567801', 0),
(2, 'rian', 'rian123', 'Rian', 'Surabaya', '081234567802', 0),
(3, 'ayik', 'ayik123', 'ayik', 'Madura', '081234567803', 0),
(4, 'budi', 'budi123', 'Budi', 'Sampang', '081234567804', 0),
(5, 'ira', 'ira123', 'ira', 'Pamekasan', '081234567805', 0),
(6, 'dewi', 'dewi123', 'Dewi', 'Sumenep', '081234567806', 0),
(7, 'nafa', 'nafa123', 'nafa', 'Gresik', '081234567807', 0),
(8, 'putri', 'putri123', 'Putri', 'Lamongan', '081234567808', 0),
(9, 'fajar', 'fajar123', 'Fajar', 'Malang', '081234567809', 0),
(10, 'nadia', 'nadia123', 'Nadia', 'Surabaya', '081234567810', 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` tinyint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
