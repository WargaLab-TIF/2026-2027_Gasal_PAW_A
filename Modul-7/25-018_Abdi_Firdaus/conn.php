<?php
// ==========================================================
// FILE KONEKSI DATABASE (PDO): conn.php
// ==========================================================

// Konfigurasi kredensial server database MySQL
$host = "localhost";
$port = 3306;
$username = "root";
$password = "";
$database = "penjualan";

// Opsi PDO: tampilkan error sebagai Exception & ambil hasil sebagai array asosiatif
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  // Membuka koneksi ke database penjualan menggunakan PDO
  $conn = new PDO("mysql:host=$host:$port;dbname=$database;charset=utf8mb4", $username, $password, $options);
} catch (PDOException $e) {
  // Jika database penjualan belum ada, buat database terlebih dahulu lalu gunakan
  $conn = new PDO("mysql:host=$host:$port;charset=utf8mb4", $username, $password, $options);
  $conn->exec("CREATE DATABASE IF NOT EXISTS `$database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
  $conn->exec("USE `$database`");
}

// Mengecek apakah tabel transaksi sudah tersedia; jika belum, jalankan file SQL awal
$cekTabel = $conn->query("SHOW TABLES LIKE 'transaksi'")->fetchAll();
if (empty($cekTabel)) {
  $sqlFile = __DIR__ . "/database/penjualan.sql";
  if (file_exists($sqlFile)) {
    $sqlContent = file_get_contents($sqlFile);
    // Menghapus baris komentar SQL (-- ...) lalu mengeksekusi tiap query satu per satu
    $sqlClean = preg_replace('/^\s*--.*$/m', '', $sqlContent);
    $statements = array_filter(array_map('trim', explode(';', $sqlClean)));
    foreach ($statements as $statement) {
      if ($statement !== '') {
        $conn->exec($statement);
      }
    }
  }
}
