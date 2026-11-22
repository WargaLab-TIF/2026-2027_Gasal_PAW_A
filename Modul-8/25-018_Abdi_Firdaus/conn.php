<?php
// conn.php - koneksi database

$host = "localhost";
$port = 3306;
$username = "root";
$password = "";
$database = "penjualan";

$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $conn = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4", $username, $password, $options);
} catch (PDOException $e) {
  // buat database kalau belum ada
  $conn = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $username, $password, $options);
  $conn->exec("CREATE DATABASE IF NOT EXISTS `$database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
  $conn->exec("USE `$database`");
}

// import sql awal kalau tabel belum ada
$cekTabelUser = $conn->query("SHOW TABLES LIKE 'user'")->fetchAll();
$cekTabelTransaksi = $conn->query("SHOW TABLES LIKE 'transaksi'")->fetchAll();
if (empty($cekTabelUser) || empty($cekTabelTransaksi)) {
  $sqlFile = __DIR__ . "/database/penjualan.sql";
  if (file_exists($sqlFile)) {
    $sqlContent = file_get_contents($sqlFile);
    $sqlClean = preg_replace('/^\s*--.*$/m', '', $sqlContent);
    $statements = array_filter(array_map('trim', explode(';', $sqlClean)));
    foreach ($statements as $statement) {
      if ($statement !== '') {
        $conn->exec($statement);
      }
    }
  }
}

// ubah password lama yang belum md5
$conn->exec(
  "UPDATE `user` SET `password` = MD5(`password`) WHERE CHAR_LENGTH(`password`) != 32 OR `password` NOT REGEXP '^[0-9a-fA-F]{32}$'"
);

// isi user default kalau tabel masih kosong
$jumlahUser = (int) $conn->query("SELECT COUNT(*) FROM `user`")->fetchColumn();
if ($jumlahUser === 0) {
  $stmtSeed = $conn->prepare(
    "INSERT INTO `user` (`username`, `password`, `nama`, `alamat`, `hp`, `level`) VALUES (?, MD5(?), ?, ?, ?, ?)"
  );
  $stmtSeed->execute(['admin', 'admin123', 'Administrator', 'Surabaya', '081900000001', 1]);
  $stmtSeed->execute(['kasir1', 'kasir123', 'Andi Kasir', 'Bangkalan', '081900000002', 2]);
}
