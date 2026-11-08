<?php

require_once 'koneksi.php';

$id = $_GET['id'] ?? '';

if (!ctype_digit((string) $id)) {
    die("ID Barang tidak valid.");
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
    echo "
        <script>
            alert('Barang tidak dapat dihapus karena masih digunakan pada transaksi!');
            window.location.href = 'index-barang.php';
        </script>
    ";
    exit;
}

$query = $DBH->prepare("
    DELETE FROM barang
    WHERE id = :id
");

$query->execute([
    ':id' => $id
]);

if ($query->rowCount() > 0) {
    echo "
        <script>
            alert('Data barang berhasil dihapus!');
            window.location.href = 'index-barang.php';
        </script>
    ";
} else {
    echo "
        <script>
            alert('Data barang tidak ditemukan!');
            window.location.href = 'index-barang.php';
        </script>
    ";
}

exit;
