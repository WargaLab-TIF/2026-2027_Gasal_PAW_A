<?php
include 'koneksi.php';

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM supplier WHERE id='$id'";
    mysqli_query($koneksi, $query);
    echo "<script>alert('Data supplier berhasil dihapus!'); window.location='index.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Master Supplier</title>
    <style>
        body {
            font-family: Arial, sans-serif; margin: 20px; 
        }
        table {
            width: 100%; border-collapse: collapse; margin-top: 15px; 
        }
        th, td {
         border: 1px solid black; padding: 10px; text-align: left; 
        }
        th {
            background-color: skyblue; 
        }
        td{
            padding: 15px;
        }
        .tombol_tambah{
            background-color: green;
            color: white;
            border-radius: 5px;
            text-decoration: none;   
            padding: 10px;
            float: right;   
            margin-bottom: 10px;
         }
        .tombol_hapus {
            background-color: red;
            color: white;
            border-radius: 5px;
            text-decoration: none;   
            padding: 10px;
         }
        .tombol_edit{
            background-color: orange;
            color: white;
            border-radius: 5px;
            text-decoration: none;   
            padding: 10px;
        }
    </style>
</head>
<body>

    <h1>Data Master Supplier</h1>
    
    <a href="tambah_supplier.php" class="tombol_tambah">Tambah Data</a>

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
        $data = mysqli_query($koneksi, "SELECT * FROM supplier");
        while ($row = mysqli_fetch_assoc($data)) {
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $row['nama']; ?></td>
            <td><?php echo $row['telp']; ?></td>
            <td><?php echo $row['alamat']; ?></td>
            <td>
                <a href="edit_supplier.php?id=<?php echo $row['id']; ?>" class="tombol_edit">Edit</a>
                <a href="index.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Anda yakin akan menghapus supplier ini?');" class="tombol_hapus" name="hapus">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>