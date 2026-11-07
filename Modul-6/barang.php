<!DOCTYPE html>
<html>
<head>
    <title>Data Barang</title>
</head>
<body>
    <?php
    // Koneksi ke database MySQL sesuai pola di file Materi
    $koneksi = mysqli_connect("localhost", "root", "", "penjualan");

    // Periksa koneksi
    if (mysqli_connect_error()) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    // =========================================================================
    // SOAL NO 3: Fitur Hapus Barang dengan Pengecekan ke Tabel transaksi_detail
    // =========================================================================
    if (isset($_GET['hapus_id'])) {
        $id_hapus = $_GET['hapus_id'];

        // 1. Periksa apakah barang_id sudah dipakai pada tabel transaksi_detail
        $query_cek = "SELECT barang_id FROM transaksi_detail WHERE barang_id = '$id_hapus'";
        $result_cek = mysqli_query($koneksi, $query_cek);

        // 2. Jika ditemukan (jumlah baris > 0), tolak penghapusan dan beri notifikasi
        if (mysqli_num_rows($result_cek) > 0) {
            echo "<p style='color: red;'><b>Notifikasi: Data barang gagal dihapus karena barang tersebut masih digunakan pada transaksi!</b></p>";
        } else {
            // 3. Jika belum dipakai, hapus data barang seperti biasa
            $query_hapus = "DELETE FROM barang WHERE id = '$id_hapus'";
            mysqli_query($koneksi, $query_hapus);
            echo "<p style='color: green;'><b>Notifikasi: Data barang berhasil dihapus.</b></p>";
        }
    }
    ?>

    <p><a href="index.php">Kembali ke Halaman Master-Detail Transaksi</a></p>

    <h1>Daftar Barang</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>ID Supplier</th>
            <th>Aksi</th>
        </tr>
        <?php
        // Query untuk mengambil data barang
        $query_barang = "SELECT id, kode_barang, nama_barang, harga, stok, supplier_id FROM barang";
        $result_barang = mysqli_query($koneksi, $query_barang);

        // Tampilkan data barang
        while ($row = mysqli_fetch_assoc($result_barang)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['kode_barang'] . "</td>";
            echo "<td>" . $row['nama_barang'] . "</td>";
            echo "<td>" . $row['harga'] . "</td>";
            echo "<td>" . $row['stok'] . "</td>";
            echo "<td>" . $row['supplier_id'] . "</td>";
            echo "<td><a href='barang.php?hapus_id=" . $row['id'] . "' onclick=\"return confirm('Yakin ingin menghapus barang ini?');\">Hapus</a></td>";
            echo "</tr>";
        }

        mysqli_close($koneksi);
        ?>
    </table>
</body>
</html>