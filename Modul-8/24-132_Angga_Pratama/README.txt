PROJECT PRAKTIKUM 8 PAW - SESSION DAN SECURITY

Teknologi: PHP Native, MySQL, PDO, HTML, CSS.

CARA MENJALANKAN DI LARAGON
1. Ekstrak folder PAW_Modul8 ke C:\laragon\www\
2. Jalankan Apache dan MySQL dari Laragon.
3. Buka http://localhost/phpmyadmin
4. Import file database.sql.
5. Jika username/password MySQL Laragon berbeda dari default, ubah config/database.php.
6. Buka http://localhost/tp-8/auth/login.php
7. Jika nama folder project diubah, sesuaikan BASE_URL pada includes/functions.php.

AKUN DEMO
Owner: username owner | password owner123
Kasir: username kasir | password kasir123

HAK AKSES
- Owner (level 1): Home, Data Master (Barang, Supplier, Pelanggan, User), Transaksi, Laporan.
- Kasir (level 2): Home, Transaksi, Laporan.
- Semua halaman internal memeriksa session. Halaman data master memeriksa level Owner di sisi server.
- User yang sudah login diarahkan ke Home jika membuka halaman login.
- Logout menghapus session dan mengarahkan kembali ke login.

CATATAN
Password disimpan menggunakan password_hash() dan diverifikasi dengan password_verify(), bukan MD5.
Contoh transaksi/laporan dibuat sederhana agar dapat diuji mandiri karena modul transaksi/laporan sebelumnya tidak disertakan.
File SQL berisi struktur tabel dan data awal.

Catatan: Alamat dasar project ditentukan otomatis. Project dapat dijalankan meskipun folder tp-8 berada di dalam folder praktikum yang memiliki spasi.


DATA DUMMY
Database.sql sudah berisi data contoh:
- 2 akun (Owner dan Kasir)
- 10 barang, 4 supplier, 6 pelanggan
- 12 transaksi dengan detail barang
- Tanggal transaksi contoh 14-23 November 2026

PENTING: database.sql membuat ulang tabel (DROP TABLE) agar data demo konsisten.
Jika sudah memiliki data sendiri, backup database terlebih dahulu sebelum import.