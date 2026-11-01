<?php
// Konfigurasi koneksi ke database MySQL
$host = "localhost"; // Nama host server database
$user = "root";      // Username default MySQL XAMPP
$pass = "";          // Password default MySQL XAMPP
$db   = "penjualan"; // Nama database yang digunakan

// Membuat koneksi ke database menggunakan fungsi mysqli_connect
$conn = mysqli_connect($host, $user, $pass, $db);

// Memeriksa apakah koneksi berhasil atau gagal
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
