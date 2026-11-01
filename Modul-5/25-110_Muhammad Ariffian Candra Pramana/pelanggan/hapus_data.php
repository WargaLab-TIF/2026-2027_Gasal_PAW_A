<?php
require '../route.php';

$id = $_GET['id'] ?? '';

if ($id) {
    try {
        $hapus = $DBH->prepare("DELETE FROM pelanggan WHERE id = ?");
        
        $hapus->execute([$id]);

        echo "<script>
                alert('Data pelanggan berhasil dihapus!'); 
                window.location.href='index.php';
              </script>";
        exit;

    } catch (PDOException $e) {
        echo "<script>
                alert('Gagal menghapus data! Kesalahan: " . $e->getMessage() . "'); 
                window.location.href='index.php';
              </script>";
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>