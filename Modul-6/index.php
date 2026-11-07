<!DOCTYPE html>
<html>
<head>
    <title>Master-Detail Transaksi</title>
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
    // SOAL NO 4: Proses Input Detail Transaksi & Otomatis Update Total Transaksi
    // =========================================================================
    if (isset($_POST['simpan_detail'])) {
        $transaksi_id = $_POST['transaksi_id'];
        $barang_id    = $_POST['barang_id'];
        $qty          = $_POST['qty'];

        // Ambil harga satuan dari tabel barang berdasarkan barang_id yang dipilih
        $query_harga  = "SELECT harga FROM barang WHERE id = '$barang_id'";
        $result_harga = mysqli_query($koneksi, $query_harga);
        $data_barang  = mysqli_fetch_assoc($result_harga);
        $harga        = $data_barang['harga'];

        // 1. Simpan data baru ke tabel transaksi_detail
        $query_insert = "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) 
                         VALUES ('$transaksi_id', '$barang_id', '$harga', '$qty')";
        mysqli_query($koneksi, $query_insert);

        // 2. Hitung ulang total dari seluruh detail milik transaksi yang sama
        $query_hitung  = "SELECT SUM(harga * qty) AS total_baru 
                          FROM transaksi_detail 
                          WHERE transaksi_id = '$transaksi_id'";
        $result_hitung = mysqli_query($koneksi, $query_hitung);
        $data_hitung   = mysqli_fetch_assoc($result_hitung);
        $total_baru    = $data_hitung['total_baru'];

        // 3. Simpan hasil perhitungan ke kolom total pada tabel transaksi
        $query_update_total = "UPDATE transaksi 
                               SET total = '$total_baru' 
                               WHERE id = '$transaksi_id'";
        mysqli_query($koneksi, $query_update_total);
    }

    // Menangkap ID transaksi (Master) yang sedang dipilih (default ke ID 1)
    if (isset($_GET['transaksi_id'])) {
        $id_terpilih = $_GET['transaksi_id'];
    } else {
        $id_terpilih = 1;
    }
    ?>

    <p><a href="barang.php">Ke Halaman Data Barang (Fitur Hapus Soal No 3)</a></p>

    <!-- ========================================================================= -->
    <!-- BAGIAN 1: DATA MASTER (Tabel Transaksi)                                   -->
    <!-- ========================================================================= -->
    <h1>Daftar Transaksi (Master)</h1>
    <table border="1">
        <tr>
            <th>ID Transaksi</th>
            <th>Waktu Transaksi</th>
            <th>Keterangan</th>
            <th>Total</th>
            <th>ID Pelanggan</th>
            <th>Aksi</th>
        </tr>
        <?php
        // Query untuk mengambil data master transaksi
        $query_transaksi = "SELECT id, waktu_transaksi, keterangan, total, pelanggan_id FROM transaksi";
        $result_transaksi = mysqli_query($koneksi, $query_transaksi);

        // Tampilkan data transaksi
        while ($row = mysqli_fetch_assoc($result_transaksi)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['waktu_transaksi'] . "</td>";
            echo "<td>" . $row['keterangan'] . "</td>";
            echo "<td>" . $row['total'] . "</td>";
            echo "<td>" . $row['pelanggan_id'] . "</td>";
            // Tombol Load untuk menampilkan detail sesuai foreign key transaksi_id
            echo "<td><a href='index.php?transaksi_id=" . $row['id'] . "'>Lihat Detail</a></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <!-- ========================================================================= -->
    <!-- BAGIAN 2: DATA DETAIL (Tabel Transaksi Detail) & FORM INPUT DETAIL        -->
    <!-- ========================================================================= -->
    <h1>Daftar Detail Transaksi (ID Transaksi: <?php echo $id_terpilih; ?>)</h1>
    <table border="1">
        <tr>
            <th>ID Transaksi</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
        <?php
        // Query untuk mengambil data detail sesuai kondisi WHERE transaksi_id yang dipilih
        $query_detail = "SELECT transaksi_detail.transaksi_id, barang.nama_barang, transaksi_detail.harga, transaksi_detail.qty 
                         FROM transaksi_detail 
                         INNER JOIN barang ON transaksi_detail.barang_id = barang.id 
                         WHERE transaksi_detail.transaksi_id = '$id_terpilih'";
        $result_detail = mysqli_query($koneksi, $query_detail);

        // Tampilkan data transaksi_detail
        while ($row = mysqli_fetch_assoc($result_detail)) {
            $subtotal = $row['harga'] * $row['qty'];
            echo "<tr>";
            echo "<td>" . $row['transaksi_id'] . "</td>";
            echo "<td>" . $row['nama_barang'] . "</td>";
            echo "<td>" . $row['harga'] . "</td>";
            echo "<td>" . $row['qty'] . "</td>";
            echo "<td>" . $subtotal . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <!-- ========================================================================= -->
    <!-- SOAL NO 2: Form Input Detail dengan Dropdown Barang (Disable jika dipakai)-->
    <!-- ========================================================================= -->
    <h2>Tambah Detail Transaksi (Untuk ID Transaksi: <?php echo $id_terpilih; ?>)</h2>
    <form method="POST" action="index.php?transaksi_id=<?php echo $id_terpilih; ?>">
        <input type="hidden" name="transaksi_id" value="<?php echo $id_terpilih; ?>">
        <table border="1">
            <tr>
                <td>Pilih Barang</td>
                <td>
                    <select name="barang_id" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php
                        // Ambil semua data barang untuk dropdown
                        $query_barang = "SELECT id, nama_barang, harga FROM barang";
                        $result_barang = mysqli_query($koneksi, $query_barang);

                        while ($row_barang = mysqli_fetch_assoc($result_barang)) {
                            $id_brg = $row_barang['id'];

                            // Cek apakah barang ini sudah ada di transaksi_detail untuk transaksi_id yang sedang aktif
                            $query_cek = "SELECT barang_id FROM transaksi_detail 
                                          WHERE transaksi_id = '$id_terpilih' AND barang_id = '$id_brg'";
                            $result_cek = mysqli_query($koneksi, $query_cek);

                            // Jika jumlah baris > 0, berarti sudah dipakai -> tambahkan atribut disabled
                            if (mysqli_num_rows($result_cek) > 0) {
                                echo "<option value='" . $row_barang['id'] . "' disabled>" 
                                     . $row_barang['nama_barang'] . " - Rp" . $row_barang['harga'] . " (Sudah dipilih)</option>";
                            } else {
                                echo "<option value='" . $row_barang['id'] . "'>" 
                                     . $row_barang['nama_barang'] . " - Rp" . $row_barang['harga'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Qty</td>
                <td><input type="number" name="qty" min="1" required></td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit" name="simpan_detail">Simpan Detail</button>
                </td>
            </tr>
        </table>
    </form>

    <?php
    mysqli_close($koneksi);
    ?>
</body>
</html>