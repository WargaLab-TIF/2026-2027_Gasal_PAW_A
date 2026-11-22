<?php
include 'koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$id = (int) $_GET['id'];

try {
    $stmt = $koneksi->prepare("DELETE FROM supplier WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php?status=hapus_ok");
} catch (mysqli_sql_exception $e) {
    // Error 1451 = masih dipakai tabel lain (foreign key)
    if ($e->getCode() == 1451) {
        header("Location: index.php?status=hapus_gagal");
    } else {
        header("Location: index.php?status=error");
    }
}
exit;
?>