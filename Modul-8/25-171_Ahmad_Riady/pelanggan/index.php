<?php
require_once '../cek_owner.php';
require_once '../koneksi.php';

$data = $DBH->prepare("SELECT * FROM pelanggan ORDER BY id ASC");
$data->execute();
$pelanggan = $data->fetchAll(PDO::FETCH_ASSOC);
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
        .table-pelanggan { width: 100%; min-width: 650px; border-collapse: collapse; background: white; }
        .table-pelanggan th { background: #f1f5f9; color: #475569; padding: 15px; text-align: left; font-size: 12px; text-transform: uppercase; }
        .table-pelanggan td { padding: 15px; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        .table-pelanggan tr:hover { background: #f8fafc; }
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
                <h1>Manajemen Data Pelanggan</h1>
            </div>
        </section>
        <section class="content">
            <div class="content-header">
                <h2>Tabel Data Pelanggan</h2>
                <a href="tambah_pelanggan.php" class="btn-add">+ TAMBAH DATA</a>
            </div>
            <table class="table-pelanggan">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($pelanggan as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['id']) ?></td>
                    <td><?= htmlspecialchars($p['nama']) ?></td>
                    <td><?= htmlspecialchars($p['jenis_kelamin']) ?></td>
                    <td><?= htmlspecialchars($p['telp']) ?></td>
                    <td><?= htmlspecialchars($p['alamat']) ?></td>
                    <td>
                        <a href="edit_pelanggan.php?id=<?= $p['id'] ?>" class="btn-edit">Edit</a>
                        <form method="POST" action="hapus_pelanggan.php" class="form-delete">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($p['id']) ?>">
                            <button type="submit" name="hapus" class="btn-delete" onclick="return confirm('Yakin ingin menghapus pelanggan ini?')">Hapus</button>
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