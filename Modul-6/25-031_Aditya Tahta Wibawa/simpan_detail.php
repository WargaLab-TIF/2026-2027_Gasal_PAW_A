<?php
include 'koneksi.php';

$transaksi_id = $_POST['transaksi_id'];
$barang_id = $_POST['barang_id'];
$qty = $_POST['qty'];

$query_harga = mysqli_query($koneksi, "SELECT harga, stok FROM barang WHERE id = '$barang_id'");
$data_harga = mysqli_fetch_assoc($query_harga);
$harga = $data_harga['harga'];
$stok = $data_harga['stok'];

if ($qty > $stok) {
    echo "<!DOCTYPE html><html><body>
          <script>
            alert('GAGAL MENAMBAHKAN! Jumlah QTY ($qty) melebihi stok barang yang tersedia ($stok).');
            window.location.href = 'detail_transaksi.php?id=" . $transaksi_id . "';
          </script>
          </body></html>";
    exit;
}

mysqli_query($koneksi, "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) 
                        VALUES ('$transaksi_id', '$barang_id', '$harga', '$qty')");

mysqli_query($koneksi, "
    UPDATE transaksi 
    SET total = (
        SELECT IFNULL(SUM(harga * qty), 0) 
        FROM transaksi_detail 
        WHERE transaksi_id = '$transaksi_id'
    ) 
    WHERE id = '$transaksi_id'
");


header("Location: detail_transaksi.php?id=" . $transaksi_id);
exit;
?>