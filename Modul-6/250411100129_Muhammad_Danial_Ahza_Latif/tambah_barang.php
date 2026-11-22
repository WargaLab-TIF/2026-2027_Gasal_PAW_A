<?php

require_once 'koneksi.php';

$transaksi_id = $_POST['transaksi_id'] ?? '';
$barang_id = $_POST['barang_id'] ?? '';
$qty = $_POST['qty'] ?? '';

if (
    !ctype_digit((string) $transaksi_id) ||
    !ctype_digit((string) $barang_id) ||
    !ctype_digit((string) $qty) ||
    (int) $qty < 1
) {
    die("Data detail transaksi tidak valid.");
}

$query = $DBH->prepare("
    SELECT *
    FROM transaksi
    WHERE id = :id
");
$query->execute([':id' => $transaksi_id]);

if (!$query->fetch()) {
    die("Transaksi tidak ditemukan.");
}

$query = $DBH->prepare("
    SELECT *
    FROM barang
    WHERE id = :id
");
$query->execute([':id' => $barang_id]);

$barang = $query->fetch();

if (!$barang) {
    die("Barang tidak ditemukan.");
}

$harga = $barang['harga'];

try {
    $DBH->beginTransaction();

    $data = $DBH->prepare("
        INSERT INTO detail_transaksi
        (transaksi_id, barang_id, harga, qty)
        VALUES
        (:transaksi_id, :barang_id, :harga, :qty)
    ");

    $data->execute([
        ':transaksi_id' => $transaksi_id,
        ':barang_id' => $barang_id,
        ':harga' => $harga,
        ':qty' => $qty
    ]);

    $data = $DBH->prepare("
        UPDATE transaksi
        SET total = (
            SELECT COALESCE(SUM(harga * qty), 0)
            FROM detail_transaksi
            WHERE transaksi_id = :id
        )
        WHERE id = :id
    ");

    $data->execute([
        ':id' => $transaksi_id
    ]);

    $DBH->commit();

    header("Location: detail_transaksi.php?id=" . urlencode($transaksi_id));
    exit;

} catch (PDOException $e) {
    $DBH->rollBack();
    die("Gagal menambahkan detail transaksi: " . $e->getMessage());
}
