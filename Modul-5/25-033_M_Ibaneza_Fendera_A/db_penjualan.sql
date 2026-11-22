-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Nov 2026 pada 07.54
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_penjualan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `kode_barang` varchar(10) DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id`, `kode_barang`, `nama_barang`, `harga`, `stok`, `supplier_id`) VALUES
(1, 'BRG001', 'Minyak Goreng 2L', 35000, 50, 3),
(2, 'BRG002', 'Beras Premium 5kg', 65000, 40, 4),
(3, 'BRG003', 'Gula Pasir 1kg', 14000, 100, 5),
(4, 'BRG004', 'Tepung Terigu 1kg', 12000, 60, 6),
(5, 'BRG005', 'Telur Ayam 1kg', 28000, 30, 7),
(6, 'BRG006', 'Mie Instan (Dus)', 110000, 25, 8),
(7, 'BRG007', 'Kecap Manis 600ml', 20000, 45, 9),
(8, 'BRG008', 'Saus Tomat 500ml', 15000, 40, 10),
(9, 'BRG009', 'Sabun Mandi Batang', 5000, 120, 11),
(10, 'BRG010', 'Pasta Gigi 190g', 18000, 80, 12);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` varchar(10) DEFAULT NULL,
  `telp` varchar(12) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `jenis_kelamin`, `telp`, `alamat`) VALUES
(1, 'Ahmad Fauzi', 'L', '081234567890', 'Jl. Raya Kamal No. 1, Bangkalan'),
(2, 'Siti Nurhaliza', 'P', '081345678901', 'Jl. Trunojoyo No. 45, Bangkalan'),
(3, 'Budi Santoso', 'L', '081456789012', 'Jl. Demangan Baru No. 12, Bangkalan'),
(4, 'Dewi Lestari', 'P', '081567890123', 'Jl. Pemuda Kaffa No. 8, Bangkalan'),
(5, 'Eko Prasetyo', 'L', '081678901234', 'Jl. Pangeran Suramadu No. 20, Bangkalan'),
(6, 'Fitri Handayani', 'P', '081789012345', 'Jl. Kenjeran No. 55, Surabaya'),
(7, 'Guntur Wibowo', 'L', '081890123456', 'Jl. Diponegoro No. 10, Surabaya'),
(8, 'Hesti Rahmawati', 'P', '081901234567', 'Jl. Basuki Rahmat No. 33, Surabaya'),
(9, 'Irfan Hakim', 'L', '082123456789', 'Jl. Panglima Sudirman No. 77, Madura'),
(10, 'Joko Widodo', 'L', '082234567890', 'Jl. Letjen Suprapto No. 9, Bangkalan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `waktu_bayar` datetime DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `metode` varchar(255) DEFAULT NULL,
  `transaksi_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `waktu_bayar`, `total`, `metode`, `transaksi_id`) VALUES
(1, '2026-10-01 10:30:00', 100000, 'TUNAI', 1),
(2, '2026-10-02 11:15:00', 79000, 'TRANSFER', 2),
(3, '2026-10-03 09:20:00', 49000, 'EDC', 3),
(4, '2026-10-04 14:00:00', 110000, 'TUNAI', 4),
(5, '2026-10-05 16:45:00', 43000, 'TRANSFER', 5),
(6, '2026-10-06 08:10:00', 35000, 'TUNAI', 6),
(7, '2026-10-07 13:30:00', 65000, 'EDC', 7),
(8, '2026-10-08 15:25:00', 23000, 'TUNAI', 8),
(9, '2026-10-09 11:00:00', 128000, 'TRANSFER', 9),
(10, '2026-10-10 17:05:00', 26000, 'TUNAI', 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `telp` varchar(13) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `supplier`
--

INSERT INTO `supplier` (`id`, `nama`, `telp`, `alamat`) VALUES
(3, 'PT Mantap', '08123456', 'Surabaya'),
(4, 'PT Senang', '081515563', 'Bangkalan'),
(5, 'PT Segarr', '08454546631', 'Surabayaa'),
(6, 'PT Berkah', '08111222333', 'Jakarta'),
(7, 'PT Sumber Makmur', '08223344556', 'Malang'),
(8, 'PT Pangan Utama', '08334455667', 'Sidoarjo'),
(9, 'PT Global Elektronik', '08445566778', 'Bandung'),
(10, 'PT Maju Lancar', '08556677889', 'Semarang'),
(11, 'PT Anugerah Abadi', '08667788990', 'Yogyakarta'),
(12, 'PT Sejahtera', '08778899001', 'Surabaya');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `waktu_transaksi` datetime DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `pelanggan_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id`, `waktu_transaksi`, `keterangan`, `total`, `pelanggan_id`) VALUES
(1, '2026-10-01 00:00:00', 'Pembelian Sembako Bulanan', 100000, 1),
(2, '2026-10-02 00:00:00', 'Belanja Kebutuhan Dapur', 79000, 2),
(3, '2026-10-03 00:00:00', 'Beli Minyak dan Gula', 49000, 3),
(4, '2026-10-04 00:00:00', 'Grosir Mie Instan', 110000, 4),
(5, '2026-10-05 00:00:00', 'Belanja Keperluan Rumah', 43000, 5),
(6, '2026-10-06 00:00:00', 'Pembelian Rutin', 35000, 6),
(7, '2026-10-07 00:00:00', 'Belanja Mingguan', 65000, 7),
(8, '2026-10-08 00:00:00', 'Beli Sabun dan Pasta Gigi', 23000, 8),
(9, '2026-10-09 00:00:00', 'Pembelian Sembako', 128000, 9),
(10, '2026-10-10 00:00:00', 'Belanja Harian', 26000, 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `transaksi_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `harga` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`transaksi_id`, `barang_id`, `harga`, `qty`) VALUES
(1, 1, 35000, 1),
(1, 3, 14000, 1),
(1, 5, 28000, 1),
(2, 2, 65000, 1),
(2, 4, 12000, 1),
(3, 1, 35000, 1),
(3, 3, 14000, 1),
(4, 6, 110000, 1),
(5, 7, 20000, 1),
(5, 8, 15000, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `hp` varchar(13) DEFAULT NULL,
  `level` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `alamat`, `hp`, `level`) VALUES
(1, 'admin1', 'admin123', 'Administrator Utama', 'Bangkalan', '0811111111', 1),
(2, 'kasir1', 'kasir123', 'Siti Kasir', 'Bangkalan', '0822222222', 2),
(3, 'kasir2', 'kasir123', 'Joko Kasir', 'Surabaya', '0833333333', 2),
(4, 'gudang1', 'gudang123', 'Budi Gudang', 'Bangkalan', '0844444444', 3),
(5, 'manager', 'manager123', 'Rahmat Manager', 'Surabaya', '0855555555', 1),
(6, 'kasir3', 'kasir123', 'Ani Kasir', 'Bangkalan', '0866666666', 2),
(7, 'gudang3', 'gudang123', 'Doni Gudang', 'Malang', '0877777777', 3),
(8, 'kasir4', 'kasir123', 'Rina Kasir', 'Sidoarjo', '0888888888', 2),
(9, 'gudang2', 'gudang123', 'Agus Gudang', 'Bangkalan', '0899999999', 3),
(10, 'owner', 'owner123', 'H. Syaiful Owner', 'Surabaya', '0812000000', 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_id` (`transaksi_id`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pelanggan_id` (`pelanggan_id`);

--
-- Indeks untuk tabel `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD KEY `transaksi_id` (`transaksi_id`),
  ADD KEY `barang_id` (`barang_id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD CONSTRAINT `transaksi_detail_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_detail_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
