<?php
include 'koneksi.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;


$cek = mysqli_query($koneksi, "SELECT COUNT(*) as jumlah FROM transaksi_detail WHERE barang_id = '$id'");
$data = mysqli_fetch_assoc($cek);


echo "<!DOCTYPE html><html><head><title>Proses Hapus</title></head><body>";

if ($data['jumlah'] > 0) {
    echo "<script>
            alert('GAGAL DIHAPUS! Barang ini masih digunakan pada detail transaksi.');
            window.location.href = 'barang.php';
          </script>";
} else {
    $hapus = mysqli_query($koneksi, "DELETE FROM barang WHERE id = '$id'");
    
    if ($hapus) {
        echo "<script>
                alert('Data Barang berhasil dihapus!');
                window.location.href = 'barang.php';
              </script>";
    } else {
        echo "<script>
                alert('KESALAHAN SISTEM: " . mysqli_error($koneksi) . "');
                window.location.href = 'barang.php';
              </script>";
    }
}

echo "</body></html>";
exit;
?>