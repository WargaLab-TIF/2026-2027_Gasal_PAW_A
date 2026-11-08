<?php

require_once 'koneksi.php';

$id = $_GET['id'] ?? '';

if (!ctype_digit((string) $id)) {
    die("ID Barang tidak valid.");
}

$query = $DBH->prepare("
    SELECT id
    FROM barang
    WHERE id = :id
");

$query->execute([
    ':id' => $id
]);

if (!$query->fetch()) {
    echo "<script>
        alert('Data barang tidak ditemukan!');
        window.location.href = 'barang.php';
    </script>";
    exit;
}

$query = $DBH->prepare("
    SELECT COUNT(*)
    FROM detail_transaksi
    WHERE barang_id = :id
");

$query->execute([
    ':id' => $id
]);

$jumlah = $query->fetchColumn();

if ($jumlah > 0) {
    echo "<script>
        alert('Barang tidak dapat dihapus karena masih digunakan pada transaksi!');
        window.location.href = 'barang.php';
    </script>";
    exit;
}

$query = $DBH->prepare("
    DELETE FROM barang
    WHERE id = :id
");

$query->execute([
    ':id' => $id
]);

echo "<script>
    alert('Data barang berhasil dihapus!');
    window.location.href = 'barang.php';
</script>";

exit;
