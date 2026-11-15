<?php
// ==========================================================
// FILE ROUTER UTAMA: index.php
// ==========================================================

try {
  // Memuat file koneksi database PDO dan fungsi helper
  require_once __DIR__ . "/conn.php";
  require_once __DIR__ . "/utils.php";

  // Mengambil path URL yang sedang diakses (default ke "/")
  $path = $_SERVER['PATH_INFO'] ?? "/";

  // Mengarahkan request ke halaman atau layanan (service) yang sesuai
  switch ($path) {
    case '/':
    case '/reports':
      // Menampilkan halaman utama laporan penjualan (grafik, tabel rekap, & total)
      require_once __DIR__ . "/views/reports/reports.php";
      break;

    case '/api/reports/export':
      // Menjalankan proses ekspor data laporan ke file Microsoft Excel (.xls)
      require_once __DIR__ . "/services/reports/reports.export.php";
      break;

    default:
      // Menampilkan halaman 404 jika rute URL tidak ditemukan
      require_once __DIR__ . "/views/404.php";
      break;
  }

  // Menutup koneksi database PDO setelah selesai digunakan
  $conn = null;
} catch (PDOException $e) {
  // Menampilkan pesan error apabila terjadi kegagalan pada database
  echo $e->getMessage();
}
