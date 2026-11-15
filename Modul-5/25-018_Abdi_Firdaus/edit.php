<?php
// Memanggil file koneksi database
include 'koneksi.php';

// Mengambil id supplier dari parameter URL
$id = $_GET['id'];

// Mengambil data supplier berdasarkan id untuk ditampilkan pada form
$result = mysqli_query($conn, "SELECT * FROM supplier WHERE id = '$id'");
$data   = mysqli_fetch_assoc($result);

// Memeriksa apakah tombol Update telah ditekan
if (isset($_POST['update'])) {
    // Mengambil data perubahan dari input form
    $nama   = $_POST['nama'];
    $telp   = $_POST['telp'];
    $alamat = $_POST['alamat'];

    // Query untuk memperbarui data supplier berdasarkan id
    $query = "UPDATE supplier SET nama='$nama', telp='$telp', alamat='$alamat' WHERE id='$id'";
    
    // Mengeksekusi query dan mengarahkan kembali ke halaman utama jika berhasil
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Master Supplier</title>
    <style>
        /* Mengatur tampilan dasar halaman */
        body {
            font-family: Arial, sans-serif;
            margin: 30px auto;
            max-width: 700px;
            color: #333;
        }
        h2 {
            color: #31708f;
            font-weight: normal;
            border-bottom: 1px solid #eee;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }
        /* Mengatur tata letak baris input form */
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .form-group label {
            width: 140px;
            text-align: right;
            padding-right: 20px;
            font-weight: bold;
            font-size: 14px;
        }
        .form-group input {
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        .input-nama {
            width: 300px;
        }
        .input-pendek {
            width: 220px;
        }
        .form-actions {
            margin-left: 160px;
            margin-top: 15px;
        }
        /* Mengatur tampilan tombol Update dan Batal */
        .btn {
            display: inline-block;
            padding: 8px 14px;
            text-decoration: none;
            color: #fff;
            border-radius: 4px;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }
        .btn-update {
            background-color: #5cb85c;
            margin-right: 4px;
        }
        .btn-batal {
            background-color: #c9302c;
        }
    </style>
</head>
<body>

    <h2>Edit Data Master Supplier</h2>

    <!-- Form untuk mengubah data supplier -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" class="input-nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
        </div>

        <div class="form-group">
            <label for="telp">Telp</label>
            <input type="text" id="telp" name="telp" class="input-pendek" value="<?php echo htmlspecialchars($data['telp']); ?>" required>
        </div>

        <div class="form-group">
            <label for="alamat">Alamat</label>
            <input type="text" id="alamat" name="alamat" class="input-pendek" value="<?php echo htmlspecialchars($data['alamat']); ?>" required>
        </div>

        <div class="form-actions">
            <!-- Tombol Update untuk menyimpan perubahan dan Batal untuk kembali ke halaman utama -->
            <button type="submit" name="update" class="btn btn-update">Update</button>
            <a href="index.php" class="btn btn-batal">Batal</a>
        </div>
    </form>

</body>
</html>
