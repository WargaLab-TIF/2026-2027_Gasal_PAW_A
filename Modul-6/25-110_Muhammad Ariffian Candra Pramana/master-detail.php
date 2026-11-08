<?php
require_once "route.php";

$transaksi_id = isset($_GET['id']) ? $_GET['id'] : 1;

if (isset($_POST['simpan-detail'])) {
    $barang_id = $_POST['barang_id'];
    $qty = $_POST['qty'];

    $stmtHarga = $DBH->prepare("SELECT harga FROM barang WHERE id = ?");
    $stmtHarga->execute([$barang_id]);
    $harga = $stmtHarga->fetchColumn();

    try {
        $DBH->beginTransaction();

        $stmtInsert = $DBH->prepare("INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES (?, ?, ?, ?)");
        $stmtInsert->execute([$transaksi_id, $barang_id, $harga, $qty]);

        $stmtHitungTotal = $DBH->prepare("SELECT SUM(harga * qty) FROM transaksi_detail WHERE transaksi_id = ?");
        $stmtHitungTotal->execute([$transaksi_id]);
        $total_baru = $stmtHitungTotal->fetchColumn();

        $total_baru = $total_baru ? $total_baru : 0;

        $stmtUpdateMaster = $DBH->prepare("UPDATE transaksi SET total = ? WHERE id = ?");
        $stmtUpdateMaster->execute([$total_baru, $transaksi_id]);

        $DBH->commit();
    } catch (Exception $e) {
        $DBH->rollBack();
        die("Gagal menyimpan data: " . $e->getMessage());
    }

    header("Location: master-detail.php?id=" . $transaksi_id);
    exit;
}

$transaksi = $DBH->prepare("SELECT transaksi.*, pelanggan.nama as nama_pelanggan FROM transaksi join pelanggan on transaksi.pelanggan_id = pelanggan.id where transaksi.id = ?");
$transaksi->execute([$transaksi_id]);
$master = $transaksi->fetch();

$detail = $DBH->prepare("SELECT transaksi_detail.*, barang.kode_barang, barang.nama_barang FROM transaksi_detail JOIN barang ON transaksi_detail.barang_id = barang.id WHERE transaksi_detail.transaksi_id = ?");
$detail->execute([$transaksi_id]);
$details = $detail->fetchall();

$barang_terinput = [];
foreach ($details as $d) {
    $barang_terinput[] = $d['barang_id'];
}

$barang = $DBH->prepare("SELECT id, kode_barang, nama_barang, harga FROM barang");
$barang->execute();
$semua_barang = $barang->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<style>
    .btn-outline-warning {
        --bs-btn-hover-color: #fff;
    }
</style>

<body>
    <div class="container mt-5">
        <p class="h3 fw-bold">Master Detail</p>
        <div class="card mt-4 shadow-sm">
            <div class="card-header fw-medium text-bg-primary">Tabel Transaksi(Master)</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"><strong>ID Transaksi:</strong> <?= htmlspecialchars($transaksi_id) ?></div>
                    <div class="col-md-3"><strong>Tanggal:</strong> <?= htmlspecialchars($master['waktu_transaksi']) ?></div>
                    <div class="col-md-3"><strong>Pelanggan:</strong> <?= htmlspecialchars($master['nama_pelanggan']) ?></div>
                    <div class="col-md-3"><strong>Keterangan:</strong> <?= htmlspecialchars($master['keterangan']) ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card mt-4 shadow-sm">
                    <div class="card-header fw-medium text-light bg-success">Form Input Detail</div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="md-3">
                                <label for="barang" class="form-label">Pilih Barang</label>
                                <select name="barang_id" id="barang" class="form-select" required>
                                    <option value="" disabled selected hidden>-- Pilih Barang --</option>
                                    <?php foreach ($semua_barang as $item):
                                        $is_disable = in_array($item['id'], $barang_terinput) ? 'disabled' : '';
                                        $keterangan = $is_disable ? '(Sudah Ditambahkan)' : '';
                                    ?>
                                        <option value="<?= $item['id'] ?>" <?= $is_disable ?>>
                                            <?= htmlspecialchars($item['kode_barang'] . '-' . $item['nama_barang']) ?> <?= $keterangan ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="qty" class="form-label">Quantity</label>
                                <input type="number" name="qty" id="qty" class="form-control" min="1" required>
                            </div>
                            <button type="submit" name="simpan-detail" class="btn btn-outline-success w-100 fw-medium">Tambah ke Keranjang</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card mt-4 shadow-sm">
                    <div class="card-header bg-warning fw-medium text-white">Tabel Detail Transaksi(Detail)</div>
                    <div class="card-body p-0">
                        <table class="table table-hover table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $grand_total = 0;
                                if (count($details) > 0):
                                    foreach ($details as $row):
                                        $subtotal = $row['harga'] * $row['qty'];
                                        $grand_total += $subtotal;
                                ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($row['kode_barang']) ?></td>
                                            <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                                            <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                            <td><?= htmlspecialchars($row['qty']) ?></td>
                                            <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="table-warning fw-bold">
                                        <td colspan="5" class="text-end">Total Belanja:</td>
                                        <td>Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada barang di transaksi ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</html>