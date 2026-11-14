<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Master Supplier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        h2 {
            color: #5796a9;
            font-weight: normal;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th {
            background: #d9edf7;
            text-align: left;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .tambah, .edit, .hapus {
            color: white;
            text-decoration: none;
            padding: 7px 10px;
            border-radius: 3px;
        }

        .tambah {
            background: #70ad47;
            float: right;
        }

        .edit {
            background: #e87518;
        }

        .hapus {
            background: #c9302c;
        }
    </style>
</head>
<body>

<h2>Data Master Supplier</h2>

<a class="tambah" href="tambah.php">Tambah Data</a>
<br><br>

<table>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Telp</th>
        <th>Alamat</th>
        <th>Tindakan</th>
    </tr>

    <?php
    $no = 1;
    $data = mysqli_query($koneksi, "SELECT * FROM supplier ORDER BY id ASC");

    while ($supplier = mysqli_fetch_assoc($data)) {
    ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo htmlspecialchars($supplier['nama']); ?></td>
        <td><?php echo htmlspecialchars($supplier['telp']); ?></td>
        <td><?php echo htmlspecialchars($supplier['alamat']); ?></td>
        <td>
            <a class="edit" href="edit.php?id=<?php echo $supplier['id']; ?>">Edit</a>

            <a class="hapus"
               href="hapus.php?id=<?php echo $supplier['id']; ?>"
               onclick="return confirm('Anda yakin akan menghapus supplier ini?')">
               Hapus
            </a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>