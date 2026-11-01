<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $DBH->prepare("DELETE FROM supplier WHERE id = :id");
        $stmt->execute([':id' => $id]);

        header("Location: supplier.php");
        exit();
        
    } catch (PDOException $e) {
        echo "Gagal menghapus data: " . $e->getMessage();
    }
} else {
    header("Location: supplier.php");
    exit();
}
?>