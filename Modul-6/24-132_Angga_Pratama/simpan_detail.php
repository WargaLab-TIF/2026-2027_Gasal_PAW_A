<?php
include 'koneksi.php';

$transaksi_id = (int) $_POST['transaksi_id'];
$barang_id = (int) $_POST['barang_id'];
$qty = (int) $_POST['qty'];

if ($transaksi_id <= 0 || $barang_id <= 0 || $qty <= 0) {
    die("Data input tidak valid!");
}

// Mengambil harga barang
$cek = mysqli_query($koneksi, "
    SELECT * FROM barang WHERE id = $barang_id
");

$data_barang = mysqli_fetch_assoc($cek);

if (!$data_barang) {
    die("Barang tidak ditemukan!");
}

// Memeriksa transaksi
$cek_transaksi = mysqli_query($koneksi, "
    SELECT * FROM transaksi WHERE id = $transaksi_id
");

if (mysqli_num_rows($cek_transaksi) == 0) {
    die("Transaksi tidak ditemukan!");
}

$cek_detail = mysqli_query($koneksi, "
    SELECT * FROM transaksi_detail
    WHERE transaksi_id = $transaksi_id
    AND barang_id = $barang_id
");

if (mysqli_num_rows($cek_detail) > 0) {
    die("Barang sudah dimasukkan ke transaksi ini!");
}

$harga = $data_barang['harga'];

$simpan = mysqli_query($koneksi, "
    INSERT INTO transaksi_detail
    (transaksi_id, barang_id, harga, qty)
    VALUES ($transaksi_id, $barang_id, $harga, $qty)
");

if ($simpan) {

    mysqli_query($koneksi, "
        UPDATE transaksi
        SET total = (
            SELECT COALESCE(SUM(harga * qty), 0)
            FROM transaksi_detail
            WHERE transaksi_id = $transaksi_id
        )
        WHERE id = $transaksi_id
    ");

    header("Location: detail_transaksi.php?id=$transaksi_id");
    exit;

} else {
    echo "Gagal menyimpan detail: " . mysqli_error($koneksi);
}
?>