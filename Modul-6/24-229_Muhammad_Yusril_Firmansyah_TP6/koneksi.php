<?php
$host = 'localhost';
$dbname = 'database';
$username = 'root'; // Sesuaikan dengan username database Anda
$password = '';     // Sesuaikan dengan password database Anda

try {
    // Membuat objek koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Mengatur mode error PDO menjadi Exception agar mudah mendeteksi kesalahan
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Mengatur default fetch mode ke associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // echo "Koneksi database berhasil!"; // Uncomment baris ini jika ingin mengetes koneksi
} catch (PDOException $e) {
    // Tangkap error jika koneksi gagal
    die("Koneksi database gagal: " . $e->getMessage());
}
?>