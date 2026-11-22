<?php
require_once 'koneksi.php';

$supplier = $DBH->prepare("SELECT * FROM supplier");
$supplier->execute();
$data = $supplier->fetchAll();
?>

<style>
    .table-supplier { width: 100%; border-collapse: collapse; min-width: 650px; }
    .table-supplier th { background: #f1f5f9; color: #475569; padding: 15px; text-align: left; font-size: 12px; text-transform: uppercase; }
    .table-supplier td { padding: 15px; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
    .table-supplier tr:hover { background: #f8fafc; }
    .btn-edit { background: #e0f2fe; color: #0369a1; padding: 7px 12px; text-decoration: none; }
    .btn-delete { background: #fee2e2; color: #b91c1c; padding: 7px 12px; border: none; cursor: pointer; }
    .table-supplier form { display: inline; }
</style>

<div style="overflow-x:auto">
    <table class="table-supplier">
        <tr>
            <th>ID</th>
            <th>Nama Supplier</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($data as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['telp'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['alamat'] ?? '') ?></td>
            <td>
                <a class="btn-edit" href="edit_supplier.php?id=<?= $row['id'] ?>">Edit</a>
                <form method="POST" action="hapus_supplier.php" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" class="btn-delete">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach ?>
    </table>
</div>