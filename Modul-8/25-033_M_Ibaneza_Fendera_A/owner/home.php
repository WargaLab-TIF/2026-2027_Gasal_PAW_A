<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../index.php");
    exit();
}

if ($_SESSION['level'] != 1) {
    echo "<script>alert('Akses ditolak! Halaman ini khusus Owner.'); window.location='../kasir/home.php';</script>";
    exit();
}

$nama_user = $_SESSION['nama'];
$level = $_SESSION['level'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Sistem Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <?php include '../navbar.php'; ?>

    <div class="container">
        <div class="p-5 mb-4 bg-white rounded-3 shadow-sm border">
            <div class="container-fluid py-3">
                <h1 class="display-6 fw-bold">Selamat Datang, <?= htmlspecialchars($nama_user); ?>!</h1>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>