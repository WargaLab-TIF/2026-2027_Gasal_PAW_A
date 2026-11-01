
<?php
include "koneksi.php";

$query = mysqli_query($koneksi, "SELECT * FROM supplier");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Master Supplier</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #555;
            margin: 30px;
        }

        h2 {
            color: #6996a5;
            font-size: 18px;
            font-weight: normal;
        }

        .tambah {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }

        .tambah a {
            background-color: #5cb85c;
            color: white;
            padding: 7px 12px;
            text-decoration: none;
            border-radius: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #d9edf7;
            color: #555;
            text-align: left;
            padding: 10px;
        }

        td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .edit {
            background-color: #f07818;
            color: white;
            padding: 7px 12px;
            text-decoration: none;
            border-radius: 3px;
        }

        .hapus {
            background-color: #c9302c;
            color: white;
            padding: 7px 12px;
            text-decoration: none;
            border-radius: 3px;
        }
    </style>
</head>

<body>

    <h2>Data Master Supplier</h2>

    <div class="tambah">
        <a href="../Tugas-4/tambah_barang.php">Tambah Data</a>
    </div>

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

        while ($data = mysqli_fetch_assoc($query)) {
        ?>

        <tr>
            <td><?php echo $no++; ?></td>

            <td>
                <?php echo htmlspecialchars($data['nama']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($data['telp']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($data['alamat']); ?>
            </td>

            <td>
                <a class="edit"
                   href="../Tugas-5/edit_barang.php?id=<?php echo $data['id']; ?>">
                    Edit
                </a>

                <a class="hapus"
                   href="../Tugas-6/hapus_barang.php?id=<?php echo $data['id']; ?>"
                   onclick="return confirm('Anda yakin akan menghapus supplier ini?')">
                    Hapus
                </a>
            </td>
        </tr>

        <?php
        }
        ?>

    </table>

</body>
</html>
