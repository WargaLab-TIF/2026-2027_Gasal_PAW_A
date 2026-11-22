<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../index.php");
    exit();
}

if ($_SESSION['level'] != 2) {
    echo "<script>alert('Akses ditolak! Halaman ini khusus Kasir.'); window.location='../owner/home.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Home Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-box {
            background-color: white;
            border: 1px solid #000;
            border-radius: 8px;
            padding: 25px;
            margin-top: 20px;
        }
        .btn-menu {
            display: inline-block;
            text-decoration: none;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            margin-right: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="container">
        <div class="card-box">
            <h2>Selamat Datang, <?= $_SESSION['nama']; ?>!</h2>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>