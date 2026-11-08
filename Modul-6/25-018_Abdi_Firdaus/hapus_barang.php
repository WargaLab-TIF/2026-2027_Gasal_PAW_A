<?php
// ==========================================================
// FILE HAPUS BARANG: hapus_barang.php
// Memeriksa apakah barang sudah dipakai di tabel transaksi_detail
// sebelum menghapus data barang menggunakan PDO.
// ==========================================================

require_once 'koneksi.php';

// Memeriksa apakah ada parameter id barang yang dikirim melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Mengecek apakah barang tersebut sudah dipakai pada tabel transaksi_detail
    $cek = $DBH->prepare("SELECT * FROM transaksi_detail WHERE barang_id = '$id'");
    $cek->execute();
    $data_terkait = $cek->fetchAll();

    // Jika data ditemukan (jumlah baris > 0), maka barang sedang dipakai dan tidak boleh dihapus
    if (count($data_terkait) > 0) {
        echo "<script>
                alert('Data barang tidak dapat dihapus karena masih digunakan pada detail transaksi!');
                window.location.href = 'index.php';
              </script>";
        exit;
    }

    // 2. Jika belum dipakai pada transaksi_detail, hapus data barang seperti biasa
    $hapus = $DBH->prepare("DELETE FROM barang WHERE id = '$id'");
    $hapus->execute();

    echo "<script>
            alert('Data barang berhasil dihapus!');
            window.location.href = 'index.php';
          </script>";
    exit;
}

header("Location: index.php");
exit;
?>
