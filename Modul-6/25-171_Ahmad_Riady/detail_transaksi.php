
<?php
require_once 'koneksi.php';

$id = $_GET['id'] ?? '';
if ($id == '') {
    die("ID transaksi tidak ditemukan");
}

$query = $DBH->prepare("
    SELECT transaksi.*, pelanggan.nama AS nama_pelanggan
    FROM transaksi
    LEFT JOIN pelanggan ON transaksi.pelanggan_id = pelanggan.id
    WHERE transaksi.id = :id
");
$query->execute([':id' => $id]);
$transaksi = $query->fetch();

if (!$transaksi) {
    die("Data transaksi tidak ditemukan");
}

$pesan = '';

if (isset($_POST['simpan'])) {
    $barang_id = $_POST['barang_id'];
    $qty = (int) $_POST['qty'];

    if ($qty < 1) {
        $pesan = "Jumlah barang minimal 1";
    } else {
        $cek = $DBH->prepare("
            SELECT COUNT(*) FROM transaksi_detail
            WHERE transaksi_id = :transaksi_id AND barang_id = :barang_id
        ");
        $cek->execute([
            ':transaksi_id' => $id,
            ':barang_id' => $barang_id
        ]);

        if ($cek->fetchColumn() > 0) {
            $pesan = "Barang sudah ada dalam transaksi";
        } else {
            $barang = $DBH->prepare("SELECT harga FROM barang WHERE id = :id");
            $barang->execute([':id' => $barang_id]);
            $harga = $barang->fetchColumn();

            if ($harga === false) {
                $pesan = "Barang tidak ditemukan";
            } else {
                try {
                    $DBH->beginTransaction();

                    $tambah = $DBH->prepare("
                        INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty)
                        VALUES (:transaksi_id, :barang_id, :harga, :qty)
                    ");
                    $tambah->execute([
                        ':transaksi_id' => $id,
                        ':barang_id' => $barang_id,
                        ':harga' => $harga,
                        ':qty' => $qty
                    ]);

                    $total = $DBH->prepare("
                        SELECT COALESCE(SUM(harga * qty), 0)
                        FROM transaksi_detail
                        WHERE transaksi_id = :id
                    ");
                    $total->execute([':id' => $id]);
                    $jumlah = $total->fetchColumn();

                    $update = $DBH->prepare("
                        UPDATE transaksi SET total = :total WHERE id = :id
                    ");
                    $update->execute([
                        ':total' => $jumlah,
                        ':id' => $id
                    ]);

                    $DBH->commit();

                    header("Location: detail_transaksi.php?id=" . urlencode($id));
                    exit;
                } catch (PDOException $err) {
                    $DBH->rollBack();
                    $pesan = "Gagal menyimpan: " . $err->getMessage();
                }
            }
        }
    }
}

$detail = $DBH->prepare("
    SELECT transaksi_detail.*, barang.kode_barang, barang.nama_barang
    FROM transaksi_detail
    INNER JOIN barang ON transaksi_detail.barang_id = barang.id
    WHERE transaksi_detail.transaksi_id = :id
    ORDER BY barang.id ASC
");
$detail->execute([':id' => $id]);
$data = $detail->fetchAll();

$barang = $DBH->prepare("
    SELECT b.id, b.kode_barang, b.nama_barang, b.harga
    FROM barang b
    WHERE NOT EXISTS (
        SELECT 1 FROM transaksi_detail td
        WHERE td.transaksi_id = :id AND td.barang_id = b.id
    )
    ORDER BY b.nama_barang ASC
");
$barang->execute([':id' => $id]);
$daftar_barang = $barang->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f8f9fa; color: #1e293b; }
        header { background: #0f172a; color: white; padding: 25px 8%; }
        .container { max-width: 1200px; margin: auto; padding: 35px 20px; }
        .content { background: white; border: 1px solid #e2e8f0; margin-bottom: 25px; padding: 25px; }
        h2 { font-size: 20px; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #f1f5f9; color: #475569; text-align: left; }
        th, td { padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        input, select { padding: 10px; border: 1px solid #cbd5e1; margin: 7px 0 15px; }
        button { background: #0f172a; color: white; border: 0; padding: 10px 18px; cursor: pointer; }
        .btn-back { color: #0369a1; text-decoration: none; }
        .pesan { color: #dc2626; margin-bottom: 15px; }
        .total { text-align: right; font-weight: bold; margin-top: 20px; font-size: 18px; }
    </style>
</head>
<body>
<header>
    <h2>DATABASE MANAGEMENT</h2>
</header>
<main class="container">
    <p><a href="index.php" class="btn-back">Kembali ke Transaksi</a></p>

    <section class="content">
        <h2>Informasi Transaksi</h2>
        <p>ID Transaksi: <?= htmlspecialchars($transaksi['id']) ?></p>
        <p>Pelanggan: <?= htmlspecialchars($transaksi['nama_pelanggan'] ?? '-') ?></p>
        <p>Waktu: <?= htmlspecialchars($transaksi['waktu_transaksi']) ?></p>
        <p>Keterangan: <?= htmlspecialchars($transaksi['keterangan'] ?? '-') ?></p>
    </section>

    <section class="content">
        <h2>Tambah Detail Barang</h2>

        <?php if ($pesan != '') { ?>
            <p class="pesan"><?= htmlspecialchars($pesan) ?></p>
        <?php } ?>

        <form method="POST">
            <label>Barang</label><br>
            <select name="barang_id" required>
                <option value="">Pilih Barang</option>
                <?php foreach ($daftar_barang as $row) { ?>
                    <option value="<?= $row['id'] ?>">
                        <?= htmlspecialchars($row['nama_barang']) ?> -
                        Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                    </option>
                <?php } ?>
            </select><br>

            <label>Jumlah</label><br>
            <input type="number" name="qty" min="1" value="1" required><br>

            <button type="submit" name="simpan">Simpan Detail</button>
        </form>
    </section>

    <section class="content">
        <h2>Daftar Detail Transaksi</h2>
        <table>
            <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($data as $row) { ?>
            <tr>
                <td><?= htmlspecialchars($row['kode_barang']) ?></td>
                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                <td><?= $row['qty'] ?></td>
                <td>Rp <?= number_format($row['harga'] * $row['qty'], 0, ',', '.') ?></td>
            </tr>
            <?php } ?>
            <?php if (count($data) == 0) { ?>
            <tr>
                <td colspan="5">Belum ada detail transaksi.</td>
            </tr>
            <?php } ?>
        </table>

        <div class="total">
            Total: Rp <?= number_format($transaksi['total'], 0, ',', '.') ?>
        </div>
    </section>
</main>
</body>
</html>