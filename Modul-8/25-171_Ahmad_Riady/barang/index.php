<?php
require_once '../cek_owner.php';
require_once '../koneksi.php';

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
$barang = $data->fetchAll(PDO::FETCH_ASSOC);
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
        .table-barang { width: 100%; min-width: 650px; border-collapse: collapse; background: white; }
        .table-barang th { background: #f1f5f9; color: #475569; padding: 15px; text-align: left; font-size: 12px; text-transform: uppercase; }
        .table-barang td { padding: 15px; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        .table-barang tr:hover { background: #f8fafc; }
        .btn-edit { background: #e0f2fe; color: #0369a1; padding: 7px 12px; text-decoration: none; font-weight: bold; }
        .btn-delete { background: #fee2e2; color: #b91c1c; padding: 7px 12px; border: none; cursor: pointer; font-weight: bold; }
        .btn-edit:hover { background: #bae6fd; }
        .btn-delete:hover { background: #fecaca; }
        .form-delete { display: inline; }
        .pesan { padding: 12px; background: #f1f5f9; margin-bottom: 20px; font-size: 13px; }
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
                <h1>Manajemen Data Barang</h1>
            </div>
        </section>
        <section class="content">
            <div class="content-header">
                <h2>Tabel Data Barang</h2>
                <a href="tambah_barang.php" class="btn-add">+ TAMBAH DATA</a>
            </div>
            <?php if ($pesan != ''): ?>
                <p class="pesan"><?= htmlspecialchars($pesan) ?></p>
            <?php endif; ?>
            <table class="table-barang">
                <tr>
                    <th>ID</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($barang as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['kode_barang']) ?></td>
                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($row['stok']) ?></td>
                    <td>
                        <a href="edit_barang.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                        <form method="POST" action="index.php" class="form-delete" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                            <button type="submit" name="hapus" class="btn-delete">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </main>
    <footer>Rian vs Everybody</footer>
</body>
</html>