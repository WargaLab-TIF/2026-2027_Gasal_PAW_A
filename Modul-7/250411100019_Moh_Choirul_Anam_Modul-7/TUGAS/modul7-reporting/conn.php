<?php
// Koneksi database (PDO). Sesuaikan user dan password dengan MySQL kamu.
$host = "localhost";
$db   = "ppenjualan";
$user = "root";
$pass = "";

$conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
