-- Data dummy (hasil rekap sama dengan contoh di modul: 9 pelanggan, total Rp 169.000)
USE penjualan;

INSERT INTO supplier (id, nama, telp, alamat) VALUES
(1, 'CV Sumber Makmur', '031555111', 'Surabaya'),
(2, 'PT Jaya Abadi',    '031555222', 'Sidoarjo');

INSERT INTO barang (id, kode_barang, nama_barang, harga, stok, supplier_id) VALUES
(1, 'BRG001', 'Minyak Goreng 1L', 13000, 100, 1),
(2, 'BRG002', 'Gula Pasir 1Kg',   15000,  80, 1),
(3, 'BRG003', 'Beras 5Kg',        65000,  50, 2);

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('P001', 'Andi Saputra',   'L', '081200000001', 'Pamekasan'),
('P002', 'Budi Santoso',   'L', '081200000002', 'Sampang'),
('P003', 'Citra Dewi',     'P', '081200000003', 'Bangkalan'),
('P004', 'Dewi Lestari',   'P', '081200000004', 'Sumenep'),
('P005', 'Eko Prasetyo',   'L', '081200000005', 'Pamekasan'),
('P006', 'Fitri Handayani','P', '081200000006', 'Sampang'),
('P007', 'Gilang Ramadhan','L', '081200000007', 'Bangkalan'),
('P008', 'Hana Pertiwi',   'P', '081200000008', 'Sumenep'),
('P009', 'Indra Wijaya',   'L', '081200000009', 'Pamekasan');

INSERT INTO transaksi (id, waktu_transaksi, keterangan, total, pelanggan_id) VALUES
(1, '2023-09-01', 'Pembelian minyak goreng', 26000, 'P001'),
(2, '2023-09-01', 'Pembelian minyak goreng', 13000, 'P002'),
(3, '2023-09-02', 'Pembelian minyak goreng', 39000, 'P003'),
(4, '2023-09-02', 'Pembelian minyak goreng', 13000, 'P004'),
(5, '2023-09-03', 'Pembelian minyak goreng', 13000, 'P005'),
(6, '2023-09-04', 'Pembelian minyak goreng', 13000, 'P006'),
(7, '2023-09-05', 'Pembelian minyak goreng', 26000, 'P007'),
(8, '2023-09-06', 'Pembelian minyak goreng', 13000, 'P008'),
(9, '2023-09-09', 'Pembelian minyak goreng', 13000, 'P009');

INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1, 1, 13000, 2),
(2, 1, 13000, 1),
(3, 1, 13000, 3),
(4, 1, 13000, 1),
(5, 1, 13000, 1),
(6, 1, 13000, 1),
(7, 1, 13000, 2),
(8, 1, 13000, 1),
(9, 1, 13000, 1);

INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id) VALUES
('2023-09-01 09:10:00', 26000, 'TUNAI',    1),
('2023-09-01 11:30:00', 13000, 'TRANSFER', 2),
('2023-09-02 10:05:00', 39000, 'EDC',      3),
('2023-09-02 14:20:00', 13000, 'TUNAI',    4),
('2023-09-03 08:45:00', 13000, 'TUNAI',    5),
('2023-09-04 13:15:00', 13000, 'TRANSFER', 6),
('2023-09-05 16:00:00', 26000, 'EDC',      7),
('2023-09-06 09:40:00', 13000, 'TUNAI',    8),
('2023-09-09 15:25:00', 13000, 'TRANSFER', 9);

-- Password disimpan MD5 (kolom hanya 35 karakter), hanya untuk data dummy
INSERT INTO user (username, password, nama, alamat, hp, level) VALUES
('admin', MD5('admin123'), 'Administrator', 'Pamekasan', '081300000000', 1);
