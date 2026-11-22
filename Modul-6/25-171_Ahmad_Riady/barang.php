
<?php
require_once 'koneksi.php';

$pesan = '';

if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    $cek = $DBH->prepare("SELECT COUNT(*) FROM transaksi_detail WHERE barang_id = :id");
    $cek->execute([':id' => $id]);
    $jumlah = $cek->fetchColumn();

    if ($jumlah > 0) {
        $pesan = "Barang tidak dapat dihapus karena masih digunakan dalam transaksi!";
    } else {
        $hapus = $DBH->prepare("DELETE FROM barang WHERE id = :id");
        $hapus->execute([':id' => $id]);
        $pesan = "Barang berhasil dihapus!";
    }
}

$data = $DBH->prepare("SELECT * FROM barang ORDER BY id ASC");
$data->execute();
$barang = $data->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f8f9fa; color: #1e293b; }
        header { background: #0f172a; color: white; padding: 25px 8%; }
        .container { max-width: 1200px; margin: auto; padding: 35px 20px; }
        .content { background: white; border: 1px solid #e2e8f0; padding: 25px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f1f5f9; color: #475569; text-align: left; }
        th, td { padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        button { background: #fee2e2; color: #b91c1c; padding: 8px 12px; border: none; cursor: pointer; }
        .pesan { padding: 10px; background: #f1f5f9; margin-bottom: 20px; }
        a { color: #0369a1; text-decoration: none; }
    </style>
</head>
<body>
<header>
    <h2>DATABASE MANAGEMENT</h2>
</header>
<main class="container">
    <p><a href="index.php">Kembali ke Transaksi</a></p>
    <section class="content">
        <h2>Manajemen Data Barang</h2>

        <?php if ($pesan != '') { ?>
            <p class="pesan"><?= htmlspecialchars($pesan) ?></p>
        <?php } ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($barang as $row) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['kode_barang']) ?></td>
                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                <td><?= $row['stok'] ?></td>
                <td>
                    <form method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" name="hapus">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </section>
</main>
</body>
</html>