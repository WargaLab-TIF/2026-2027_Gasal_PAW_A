-- Jalankan SEKALI setelah penjualan.sql dan penjualan_dummy_data.sql.
USE penjualan;

-- Kolom password semula VARCHAR(35) (hanya muat MD5). Hash password_hash() butuh 60+ karakter.
ALTER TABLE `user` MODIFY password VARCHAR(255) NOT NULL;

-- Akun kasir (level 2) untuk uji hak akses. Akun admin (level 1) sudah ada di data dummy.
-- Password masih MD5 dari data dummy; otomatis di-upgrade ke password_hash() saat login pertama.
INSERT IGNORE INTO `user` (username, password, nama, alamat, hp, level) VALUES
('kasir', MD5('kasir123'), 'Kasir Toko', 'Pamekasan', '081300000001', 2);
