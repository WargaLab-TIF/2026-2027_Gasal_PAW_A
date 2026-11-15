<?php
// Memanggil file koneksi database
include 'koneksi.php';

// Memeriksa apakah tombol Simpan telah ditekan
if (isset($_POST['simpan'])) {
    // Mengambil data yang dikirim melalui form
    $nama   = $_POST['nama'];
    $telp   = $_POST['telp'];
    $alamat = $_POST['alamat'];

    // Query untuk menyimpan data supplier baru ke tabel supplier
    $query = "INSERT INTO supplier (nama, telp, alamat) VALUES ('$nama', '$telp', '$alamat')";
    
    // Mengeksekusi query dan mengarahkan kembali ke halaman utama jika berhasil
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal menambahkan data: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Master Supplier Baru</title>
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
        /* Mengatur tampilan tombol Simpan dan Batal */
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
        .btn-simpan {
            background-color: #5cb85c;
            margin-right: 4px;
        }
        .btn-batal {
            background-color: #c9302c;
        }
    </style>
</head>
<body>

    <h2>Tambah Data Master Supplier Baru</h2>

    <!-- Form input untuk menambah data supplier baru -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" class="input-nama" placeholder="Nama" required>
        </div>

        <div class="form-group">
            <label for="telp">Telp</label>
            <input type="text" id="telp" name="telp" class="input-pendek" placeholder="telp" required>
        </div>

        <div class="form-group">
            <label for="alamat">Alamat</label>
            <input type="text" id="alamat" name="alamat" class="input-pendek" placeholder="alamat" required>
        </div>

        <div class="form-actions">
            <!-- Tombol Simpan untuk mengirim data dan Batal untuk kembali ke halaman utama -->
            <button type="submit" name="simpan" class="btn btn-simpan">Simpan</button>
            <a href="index.php" class="btn btn-batal">Batal</a>
        </div>
    </form>

</body>
</html>
