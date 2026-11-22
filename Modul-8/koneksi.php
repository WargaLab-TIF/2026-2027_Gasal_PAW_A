<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "penjualan"; // sesuaikan dengan nama database di phpMyAdmin kamu

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>