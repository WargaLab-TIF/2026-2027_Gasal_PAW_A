<?php
require_once 'route.php';

$barang = $DBH->prepare("SELECT barang.*, supplier.nama AS nama_supplier 
          FROM barang 
          LEFT JOIN supplier ON barang.supplier_id = supplier.id 
          ORDER BY barang.id DESC");
$barang->execute();
$data_barang = $barang->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Master Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light pb-5">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Daftar Barang</h3>
    </div>

    <div class="card shadow-sm overflow-hidden">
        <div class="card-body p-0">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th class="text-center">Stok</th>
                        <th>Supplier</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (count($data_barang) > 0):
                        foreach ($data_barang as $row): 
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($row['kode_barang']) ?></span></td>
                            <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                            <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['stok']) ?></td>
                            <td><?= htmlspecialchars($row['nama_supplier'] ?? 'Tidak ada') ?></td>
                            <td class="text-center">
                                <a href="hapus_barang.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus <?= htmlspecialchars($row['nama_barang']) ?>?');">Hapus</a>
                            </td>
                        </tr>
                    <?php 
                        endforeach; 
                    else: 
                    ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">Belum ada data barang.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>