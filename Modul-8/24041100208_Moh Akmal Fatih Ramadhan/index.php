<?php

require_once __DIR__ . '/auth.php';

wajibLogin();

$isOwner = (int)$_SESSION['level'] === 1;

// Menghitung jumlah barang
$totalBarang = (int)$DBH->query(
    'SELECT COUNT(*) FROM barang'
)->fetchColumn();

// Menghitung jumlah transaksi
$totalTransaksi = (int)$DBH->query(
    'SELECT COUNT(*) FROM transaksi'
)->fetchColumn();

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Home - Sistem Penjualan</title>

    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

<?php require __DIR__ . '/partials/nav.php'; ?>

<main class="container">

    <h1>Home</h1>

    <p>
        Selamat datang,
        <strong><?= e($_SESSION['nama']) ?></strong>.
        Anda login sebagai
        <strong><?= $isOwner ? 'Owner' : 'Kasir' ?></strong>.
    </p>

    <div class="cards">

        <div class="card">

            <span>Jumlah Barang</span>

            <strong><?= $totalBarang ?></strong>

        </div>

        <div class="card">

            <span>Jumlah Transaksi</span>

            <strong><?= $totalTransaksi ?></strong>

        </div>

    </div>

</main>

</body>
</html>
