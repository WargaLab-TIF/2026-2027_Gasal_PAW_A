<?php
require_once 'koneksi.php';

$jumlah = $DBH->prepare("SELECT COUNT(*) FROM supplier");
$jumlah->execute();
$total_supplier = $jumlah->fetchColumn();

$jumlah_barang = $DBH->prepare("SELECT COUNT(*) FROM barang");
$jumlah_barang->execute();
$total_barang = $jumlah_barang->fetchColumn();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TP5</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f8f9fa; color: #1e293b; }
        header { background: #0f172a; color: white; padding: 25px 8%; }
        header h2 { margin: 0; font-size: 22px; }
        .container { max-width: 1200px; margin: auto; padding: 35px 20px; }
        .banner h1 { font-size: 30px; margin: 10px 0; }
        .content { background: white; border: 1px solid #e2e8f0; margin-top: 35px; padding: 25px; }
        .content-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px; }
        .content-header h2 { font-size: 18px; text-transform: uppercase; margin: 0; }
        .btn-add { background: #0f172a; color: white; padding: 12px 18px; text-decoration: none; font-size: 12px; font-weight: bold; }
        .btn-add:hover { background: #334155; }
        .table-supplier { width: 100%; min-width: 650px; border-collapse: collapse; background: white; }
        .table-supplier th { background: #f1f5f9; color: #475569; padding: 15px; text-align: left; font-size: 12px; text-transform: uppercase; }
        .table-supplier td { padding: 15px; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        .table-supplier tr:hover { background: #f8fafc; }
        .btn-edit { background: #e0f2fe; color: #0369a1; padding: 7px 12px; text-decoration: none; font-weight: bold; }
        .btn-delete { background: #fee2e2; color: #b91c1c; padding: 7px 12px; border: none; cursor: pointer; font-weight: bold; }
        .btn-edit:hover { background: #bae6fd; }
        .btn-delete:hover { background: #fecaca; }
        .form-delete { display: inline; }
        footer { text-align: center; color: #94a3b8; padding: 25px; font-size: 12px; }
        @media (max-width: 600px) {
            .banner h1 { font-size: 23px; }
            .content { padding: 15px; }
            .content-header h2 { font-size: 16px; }
        }
    </style>
</head>
<body>
    <header>
        <h2>DATABASE MANAGEMENT</h2>
    </header>
    <main class="container">
        <section class="banner">
            <div>
                <h1>Manajemen Data Supplier</h1>
            </div>
        </section>
        <section class="content">
            <div class="content-header">
                <h2>Tabel Data Supplier</h2>
                <a href="tambah_supplier.php" class="btn-add">+ TAMBAH DATA</a>
            </div>
            <?php include 'table.inc'; ?>
        </section>
    </main>
    <footer>Rian vs Everybody</footer>
</body>
</html>