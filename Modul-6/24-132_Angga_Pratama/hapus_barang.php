<?php
include 'koneksi.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$cek = mysqli_query($koneksi, "
    SELECT * FROM transaksi_detail
    WHERE barang_id = $id
");

if (mysqli_num_rows($cek) > 0) {

    echo "<script>
        alert('Barang tidak dapat dihapus karena masih digunakan dalam transaksi!');
        window.location='barang.php';
    </script>";

} else {

    $hapus = mysqli_query($koneksi, "
        DELETE FROM barang WHERE id = $id
    ");

    if ($hapus) {
        echo "<script>
            alert('Data barang berhasil dihapus!');
            window.location='barang.php';
        </script>";
    } else {
        echo "Data gagal dihapus: " . mysqli_error($koneksi);
    }
}
?>