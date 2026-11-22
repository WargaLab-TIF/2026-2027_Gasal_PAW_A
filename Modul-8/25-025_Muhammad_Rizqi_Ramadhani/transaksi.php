<?php
require_once __DIR__ . "/koneksi.php";
require_once __DIR__ . "/auth.php";
requireLevel([1, 2]);

$stmt = $conn->query("SELECT t.*, p.nama AS nama_pelanggan
    FROM transaksi t
    LEFT JOIN pelanggan p ON t.pelanggan_id = p.id
    ORDER BY t.waktu_transaksi DESC, t.id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 6px 10px; color: white; text-decoration: none; border-radius: 4px; display: inline-block; font-size: 14px; }
        .btn-success { background: #28a745; margin-bottom: 10px; }
        .btn-primary { background: #007bff; padding: 4px 8px; font-size: 12px; }
    </style>
</head>
<body>
    <?php require_once __DIR__ . "/navbar.php"; ?>

    <h2>Data Transaksi</h2>
    <a href="transaksi_baru.php" class="btn btn-success">+ Tambah Data</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Keterangan</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td>#<?= htmlspecialchars((string) $row['id']) ?></td>
            <td><?= htmlspecialchars((string) ($row['waktu_transaksi'] ?? '')) ?></td>
            <td><?= htmlspecialchars((string) ($row['nama_pelanggan'] ?? '-')) ?></td>
            <td><?= htmlspecialchars((string) ($row['keterangan'] ?? '')) ?></td>
            <td>Rp <?= number_format((int) ($row['total'] ?? 0), 0, ',', '.') ?></td>
            <td><a href="detail.php?id=<?= urlencode((string) $row['id']) ?>" class="btn btn-primary">Lihat Detail</a></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>