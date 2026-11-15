<?php
$host = "localhost";
$user = "root";       // Sesuaikan dengan user XAMPP
$pass = "";           // Sesuaikan dengan password XAMPP (kosongkan jika default)
$db   = "db_penjualan"; 

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>