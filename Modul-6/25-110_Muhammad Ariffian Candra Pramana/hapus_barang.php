<?php
require_once 'route.php'; 

if (isset($_GET['id'])) {
    $barang_id = $_GET['id'];

    try {
        $stmtCek = $DBH->prepare("SELECT COUNT(*) FROM transaksi_detail WHERE barang_id = ?");
        $stmtCek->execute([$barang_id]);
        $jumlah_terpakai = $stmtCek->fetchColumn();

        if ($jumlah_terpakai > 0) {
            echo "<script>
                    alert('GAGAL MENGHAPUS! Barang ini tidak bisa dihapus karena sedang digunakan pada {$jumlah_terpakai} riwayat transaksi.');
                    window.location.href = 'index_barang.php'; // Ubah sesuai nama file tabel barang Anda
                  </script>";
        } else {
            $stmtHapus = $DBH->prepare("DELETE FROM barang WHERE id = ?");
            $stmtHapus->execute([$barang_id]);

            echo "<script>
                    alert('Sukses! Data barang berhasil dihapus.');
                    window.location.href = 'index_barang.php'; // Ubah sesuai nama file tabel barang Anda
                  </script>";
        }

    } catch (PDOException $e) {
        die("Error Database: " . $e->getMessage());
    }
} else {
    header("Location: index_barang.php");
    exit;
}
?>