<?php
// Memanggil file koneksi database
include 'koneksi.php';

// Mengambil seluruh data dari tabel supplier
$query  = "SELECT * FROM supplier";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Master Supplier</title>
    <style>
        /* Mengatur tampilan dasar halaman */
        body {
            font-family: Arial, sans-serif;
            margin: 30px auto;
            max-width: 850px;
            color: #333;
        }
        /* Mengatur tata letak judul dan tombol tambah data */
        .header-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        h2 {
            color: #31708f;
            font-weight: normal;
            margin: 0;
        }
        /* Mengatur tampilan tabel data */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px 12px;
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #d9edf7;
            color: #333;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        /* Mengatur tampilan tombol aksi */
        .btn {
            display: inline-block;
            padding: 6px 12px;
            text-decoration: none;
            color: #fff;
            border-radius: 4px;
            font-size: 13px;
            border: none;
            cursor: pointer;
        }
        .btn-tambah {
            background-color: #5cb85c;
        }
        .btn-edit {
            background-color: #ec682c;
            margin-right: 4px;
        }
        .btn-hapus {
            background-color: #c9302c;
        }
    </style>
</head>
<body>

    <!-- Bagian judul halaman dan tombol tambah data -->
    <div class="header-box">
        <h2>Data Master Supplier</h2>
        <a href="tambah.php" class="btn btn-tambah">Tambah Data</a>
    </div>

    <!-- Tabel untuk menampilkan daftar data supplier -->
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama</th>
                <th width="20%">Telp</th>
                <th width="25%">Alamat</th>
                <th width="20%">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1; // Variabel untuk penomoran baris tabel
            // Menampilkan setiap baris data hasil query
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                <td><?php echo htmlspecialchars($row['telp']); ?></td>
                <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                <td>
                    <!-- Tombol untuk mengarahkan ke halaman edit data -->
                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                    
                    <!-- Tombol untuk menghapus data disertai popup konfirmasi -->
                    <a href="hapus.php?id=<?php echo $row['id']; ?>" 
                       class="btn btn-hapus" 
                       onclick="return confirm('Anda yakin akan menghapus supplier ini?');">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

</body>
</html>
