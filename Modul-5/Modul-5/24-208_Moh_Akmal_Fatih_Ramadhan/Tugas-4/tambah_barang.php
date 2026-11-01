
<?php
include "../Tugas-3/koneksi.php";

if (isset($_POST['simpan'])) {

    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $query = mysqli_prepare(
        $koneksi,
        "INSERT INTO supplier (nama, telp, alamat) VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param($query, "sss", $nama, $telp, $alamat);

    if (mysqli_stmt_execute($query)) {
        echo "<script>
                alert('Data supplier berhasil ditambahkan!');
                window.location='../Tugas-3/index.php';
              </script>";
        exit;
    } else {
        echo "Data gagal ditambahkan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Master Supplier</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #555;
            margin: 30px;
        }

        h2 {
            color: #6996a5;
            font-size: 22px;
            font-weight: normal;
            margin-left: 20px;
        }

        hr {
            border: 0;
            border-top: 1px solid #eee;
            margin-bottom: 20px;
        }

        .form-container {
            width: 500px;
            margin: auto;
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        label {
            width: 110px;
            text-align: right;
            margin-right: 15px;
            font-size: 12px;
            font-weight: bold;
        }

        input {
            width: 220px;
            padding: 7px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: inset 0 1px 1px rgba(0,0,0,0.08);
        }

        .tombol {
            margin-left: 125px;
            margin-top: 5px;
        }

        button {
            padding: 7px 13px;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
        }

        .simpan {
            background-color: #5cb85c;
        }

        .batal {
            background-color: #d9534f;
            text-decoration: none;
            color: white;
            padding: 7px 13px;
            border-radius: 4px;
            font-size: 13px;
        }
    </style>
</head>

<body>

    <h2>Tambah Data Master Supplier Baru</h2>

    <hr>

    <div class="form-container">

        <form method="POST">

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama"
                       placeholder="Nama" required>
            </div>

            <div class="form-group">
                <label>Telp</label>
                <input type="text" name="telp"
                       placeholder="telp" required>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat"
                       placeholder="alamat" required>
            </div>

            <div class="tombol">
                <button type="submit" name="simpan" class="simpan">
                    Simpan
                </button>

                <a href="../Tugas-3/index.php" class="batal">
                    Batal
                </a>
            </div>

        </form>

    </div>

</body>
</html>
