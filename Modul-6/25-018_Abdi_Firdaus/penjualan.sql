-- ==========================================================
-- SKEMA DATABASE: penjualan
-- Tugas Praktikum Modul 6 - Pengelolaan Master Detil Data
-- ==========================================================

-- Membuat database penjualan jika belum ada dan menggunakannya
CREATE DATABASE IF NOT EXISTS `penjualan`;
USE `penjualan`;

-- Menghapus tabel jika sudah ada (urutan dari tabel anak ke tabel induk)
DROP TABLE IF EXISTS `pembayaran`;
DROP TABLE IF EXISTS `transaksi_detail`;
DROP TABLE IF EXISTS `transaksi`;
DROP TABLE IF EXISTS `barang`;
DROP TABLE IF EXISTS `supplier`;
DROP TABLE IF EXISTS `pelanggan`;
DROP TABLE IF EXISTS `user`;

-- ==========================================================
-- 1. MEMBUAT TABEL-TABEL BESERTA RELASINYA (DDL)
-- ==========================================================

-- a. Tabel pelanggan
CREATE TABLE `pelanggan` (
    `id` VARCHAR(20) NOT NULL,                   -- Primary Key bertipe varchar(20) sesuai diagram
    `nama` VARCHAR(100) NOT NULL,                -- Nama pelanggan bertipe varchar(100)
    `jenis_kelamin` ENUM('L', 'P') NOT NULL,     -- Jenis kelamin bertipe enum('L','P')
    `telp` VARCHAR(12) NOT NULL,                 -- Nomor telepon bertipe varchar(12)
    `alamat` TEXT NOT NULL,                      -- Alamat pelanggan bertipe text
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Trigger untuk mengisi id pelanggan secara otomatis (Auto Increment)
DELIMITER $$
CREATE TRIGGER `trg_pelanggan_auto_id` BEFORE INSERT ON `pelanggan`
FOR EACH ROW
BEGIN
    DECLARE next_id INT;
    -- Jika saat INSERT kolom id dikosongkan, otomatis isi dengan urutan angka berikutnya
    IF NEW.id IS NULL OR NEW.id = '' THEN
        SELECT IFNULL(MAX(CAST(id AS UNSIGNED)), 0) + 1 INTO next_id FROM `pelanggan`;
        SET NEW.id = CAST(next_id AS CHAR);
    END IF;
END$$
DELIMITER ;

-- b. Tabel supplier
CREATE TABLE `supplier` (
    `id` INT NOT NULL AUTO_INCREMENT,            -- Primary Key bertipe int (Auto Increment)
    `nama` VARCHAR(100) NOT NULL,                -- Nama supplier bertipe varchar(100)
    `telp` VARCHAR(12) NOT NULL,                 -- Nomor telepon bertipe varchar(12)
    `alamat` TEXT NOT NULL,                      -- Alamat supplier bertipe text
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- c. Tabel user
CREATE TABLE `user` (
    `id_user` TINYINT NOT NULL AUTO_INCREMENT,   -- Primary Key bertipe tinyint (Auto Increment)
    `username` VARCHAR(30) NOT NULL,             -- Username bertipe varchar(30)
    `password` VARCHAR(35) NOT NULL,             -- Password bertipe varchar(35)
    `nama` VARCHAR(50) NOT NULL,                 -- Nama user bertipe varchar(50)
    `alamat` VARCHAR(150) NOT NULL,              -- Alamat user bertipe varchar(150)
    `hp` VARCHAR(20) NOT NULL,                   -- Nomor HP bertipe varchar(20)
    `level` TINYINT(1) NOT NULL,                 -- Level akses bertipe tinyint(1)
    PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- d. Tabel barang
CREATE TABLE `barang` (
    `id` INT NOT NULL AUTO_INCREMENT,            -- Primary Key bertipe int (Auto Increment)
    `kode_barang` VARCHAR(10) NOT NULL,          -- Kode barang bertipe varchar(10)
    `nama_barang` VARCHAR(100) NOT NULL,         -- Nama barang bertipe varchar(100)
    `harga` INT NOT NULL,                        -- Harga barang bertipe int
    `stok` INT NOT NULL,                         -- Stok barang bertipe int
    `supplier_id` INT NOT NULL,                  -- Foreign Key bertipe int ke supplier(id)
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_barang_supplier`
        FOREIGN KEY (`supplier_id`) REFERENCES `supplier`(`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- e. Tabel transaksi
CREATE TABLE `transaksi` (
    `id` INT NOT NULL AUTO_INCREMENT,            -- Primary Key bertipe int (Auto Increment)
    `waktu_transaksi` DATE NOT NULL,             -- Waktu transaksi bertipe date
    `keterangan` TEXT NOT NULL,                  -- Keterangan bertipe text
    `total` INT NOT NULL,                        -- Total harga bertipe int
    `pelanggan_id` VARCHAR(20) NOT NULL,         -- Foreign Key bertipe varchar(20) ke pelanggan(id)
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_transaksi_pelanggan`
        FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan`(`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- f. Tabel transaksi_detail
CREATE TABLE `transaksi_detail` (
    `transaksi_id` INT NOT NULL,                 -- Composite PK & FK bertipe int ke transaksi(id)
    `barang_id` INT NOT NULL,                    -- Composite PK & FK bertipe int ke barang(id)
    `harga` INT NOT NULL,                        -- Harga barang bertipe int
    `qty` INT NOT NULL,                          -- Jumlah barang bertipe int
    PRIMARY KEY (`transaksi_id`, `barang_id`),
    CONSTRAINT `fk_detail_transaksi`
        FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi`(`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_detail_barang`
        FOREIGN KEY (`barang_id`) REFERENCES `barang`(`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- g. Tabel pembayaran
CREATE TABLE `pembayaran` (
    `id` INT NOT NULL AUTO_INCREMENT,                          -- Primary Key bertipe int (Auto Increment)
    `waktu_bayar` DATETIME NOT NULL,                           -- Waktu bayar bertipe datetime
    `total` INT NOT NULL,                                      -- Total bayar bertipe int
    `metode` ENUM('TUNAI', 'TRANSFER', 'EDC') NOT NULL,        -- Metode bayar bertipe enum('TUNAI','TRANSFER','EDC')
    `transaksi_id` INT NOT NULL,                               -- Foreign Key bertipe int ke transaksi(id)
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_pembayaran_transaksi`
        FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi`(`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ==========================================================
-- 2. MENGISI DATA MASTER DAN TRANSAKSI (MINIMAL 5 MASTER, 10 TRANSAKSI)
-- ==========================================================

-- Mengisi 10 data ke tabel supplier
INSERT INTO `supplier` (`id`, `nama`, `telp`, `alamat`) VALUES
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

-- Mengisi 10 data ke tabel pelanggan
INSERT INTO `pelanggan` (`nama`, `jenis_kelamin`, `telp`, `alamat`) VALUES
('Budi Santoso', 'L', '081111222301', 'Bangkalan'),
('Siti Aminah', 'P', '081111222302', 'Surabaya'),
('Ahmad Fauzi', 'L', '081111222303', 'Sidoarjo'),
('Dewi Lestari', 'P', '081111222304', 'Gresik'),
('Rizky Pratama', 'L', '081111222305', 'Sampang'),
('Nurul Hidayah', 'P', '081111222306', 'Pamekasan'),
('Hendra Wijaya', 'L', '081111222307', 'Sumenep'),
('Maya Sari', 'P', '081111222308', 'Malang'),
('Fajar Nugroho', 'L', '081111222309', 'Bangkalan'),
('Putri Rahayu', 'P', '081111222310', 'Surabaya');

-- Mengisi 10 data ke tabel user
INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `alamat`, `hp`, `level`) VALUES
(1, 'admin', 'admin123', 'Administrator', 'Surabaya', '081900000001', 1),
(2, 'kasir1', 'kasir123', 'Andi Kasir', 'Bangkalan', '081900000002', 2),
(3, 'kasir2', 'kasir123', 'Bela Kasir', 'Surabaya', '081900000003', 2),
(4, 'gudang1', 'gudang123', 'Candra Gudang', 'Sidoarjo', '081900000004', 2),
(5, 'gudang2', 'gudang123', 'Dina Gudang', 'Gresik', '081900000005', 2),
(6, 'manager', 'manager123', 'Eko Manager', 'Surabaya', '081900000006', 1),
(7, 'staff1', 'staff123', 'Fani Staff', 'Bangkalan', '081900000007', 2),
(8, 'staff2', 'staff123', 'Gilang Staff', 'Sampang', '081900000008', 2),
(9, 'supervisor', 'spv123', 'Hani Supervisor', 'Pamekasan', '081900000009', 1),
(10, 'operator', 'op123', 'Ivan Operator', 'Sumenep', '081900000010', 2);

-- Mengisi 10 data ke tabel barang
INSERT INTO `barang` (`id`, `kode_barang`, `nama_barang`, `harga`, `stok`, `supplier_id`) VALUES
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

-- Mengisi 10 data ke tabel transaksi
INSERT INTO `transaksi` (`id`, `waktu_transaksi`, `keterangan`, `total`, `pelanggan_id`) VALUES
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

-- Mengisi 10 data ke tabel transaksi_detail
INSERT INTO `transaksi_detail` (`transaksi_id`, `barang_id`, `harga`, `qty`) VALUES
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

-- Mengisi 10 data ke tabel pembayaran
INSERT INTO `pembayaran` (`id`, `waktu_bayar`, `total`, `metode`, `transaksi_id`) VALUES
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
