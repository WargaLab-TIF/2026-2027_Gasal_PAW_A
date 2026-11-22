
<?php
require_once 'koneksi.php';

$transaksi = $DBH->prepare("
    SELECT transaksi.*, pelanggan.nama AS nama_pelanggan
    FROM transaksi
    LEFT JOIN pelanggan ON transaksi.pelanggan_id = pelanggan.id
    ORDER BY transaksi.id ASC
");
$transaksi->execute();
$data = $transaksi->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f8f9fa; color: #1e293b; }
        header { background: #0f172a; color: white; padding: 25px 8%; }
        header h2 { margin: 0; font-size: 22px; }
        .container { max-width: 1200px; margin: auto; padding: 35px 20px; }
        .banner h1 { font-size: 30px; margin: 10px 0; }
        .content { background: white; border: 1px solid #e2e8f0; margin-top: 35px; padding: 25px; }
        .content-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;}
        .content-header h2 { font-size: 18px; margin: 0;}
        .content-header h2 { font-size: 18px; margin: 0; }
        .table-data { width: 100%; min-width: 650px; border-collapse: collapse; }
        .table-data th { background: #f1f5f9; color: #475569; padding: 15px; text-align: left; font-size: 12px; }
        .table-data td { padding: 15px; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        .table-data tr:hover { background: #f8fafc; }
        .btn-detail { background: #e0f2fe; color: #0369a1; padding: 7px 12px; text-decoration: none; font-weight: bold; }
        footer { text-align: center; color: #94a3b8; padding: 25px; font-size: 12px; }
    </style>
</head>
<body>
    <header>
        <h2>DATABASE MANAGEMENT</h2>
    </header>
    <main class="container">
        <section class="banner">
            <h1>Manajemen Data Transaksi</h1>
        </section>
        <section class="content">
            <div class="content-header">
                <h2>Tabel Data Transaksi</h2>
                <a href="barang.php" class="btn-detail">Manajemen Barang</a>
            </div>
            <div style="overflow-x: auto;">
                <table class="table-data">
                    <tr>
                        <th>ID</th>
                        <th>Waktu Transaksi</th>
                        <th>Pelanggan</th>
                        <th>Keterangan</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                    <?php if (count($data) > 0) { ?>
                        <?php foreach ($data as $row) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['waktu_transaksi']) ?></td>
                            <td><?= htmlspecialchars($row['nama_pelanggan'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['keterangan'] ?? '-') ?></td>
                            <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                            <td>
                                <a href="detail_transaksi.php?id=<?= urlencode($row['id']) ?>" class="btn-detail">Detail</a>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6">Belum ada data transaksi.</td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </section>
    </main>
    <footer>Rian vs EveryBody</footer>
</body>
</html>