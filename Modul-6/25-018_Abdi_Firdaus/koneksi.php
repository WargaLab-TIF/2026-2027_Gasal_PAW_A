<?php
// ==========================================================
// FILE KONEKSI DATABASE PDO: koneksi.php
// Tugas Praktikum Modul 6 - Pengelolaan Master Detil Data
// ==========================================================

// Konfigurasi konstanta koneksi database MySQL
const HOST   = 'localhost';
const USER   = 'root';
const PASS   = '';
const DBNAME = 'penjualan';

// Data Source Name (DSN) untuk koneksi MySQL menggunakan PDO
const DSN = "mysql:host=" . HOST . ";dbname=" . DBNAME;

// Opsi konfigurasi PDO:
// - ERRMODE_EXCEPTION: Menampilkan error sebagai Exception
// - FETCH_ASSOC: Mengambil data dalam bentuk array asosiatif
const OPTIONS = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Membuat objek koneksi PDO ($DBH) di dalam blok try-catch
try {
    $DBH = new PDO(DSN, USER, PASS, OPTIONS);
} catch (PDOException $err) {
    echo "Koneksi Gagal: " . $err->getMessage();
    exit;
}
?>
