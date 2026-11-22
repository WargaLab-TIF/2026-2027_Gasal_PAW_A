<?php
include 'koneksi.php';

$data = mysqli_query($koneksi, "SELECT * FROM supplier ORDER BY id");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Master Supplier</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px 40px; color: #333; }
        h2 { color: #3a87ad; font-weight: normal; }
        .atas { display: flex; justify-content: space-between;
                align-items: center; border-bottom: 1px solid #eee; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #d9edf7; text-align: left; }
        th, td { border: 1px solid #ddd; padding: 10px; font-size: 14px; }
        .btn { color: #fff; padding: 6px 12px; border-radius: 3px;
               text-decoration: none; font-size: 13px; }
        .tambah { background: #5cb85c; }
        .edit   { background: #e8590c; }
        .hapus  { background: #d9534f; }
    </style>
</head>
<body>
    <div class="atas">
        <h2>Data Master Supplier</h2>
        <a href="tambah.php" class="btn tambah">Tambah Data</a>
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
        while ($row = mysqli_fetch_assoc($data)) {
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo htmlspecialchars($row['nama']); ?></td>
            <td><?php echo htmlspecialchars($row['telp']); ?></td>
            <td><?php echo htmlspecialchars($row['alamat']); ?></td>
            <td>
                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn edit">Edit</a>
                <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn hapus"
                    onclick="return confirm('Anda yakin akan menghapus supplier ini?');">Hapus</a>
               </td>
        </tr>
        <?php } ?>
    </table>
</body>
<?php
$pesan = [
    'hapus_ok'    => ['Data supplier berhasil dihapus.', '#5cb85c'],
    'hapus_gagal' => ['Supplier tidak bisa dihapus karena masih memiliki barang.', '#d9534f'],
    'error'       => ['Terjadi kesalahan saat menghapus data.', '#d9534f'],
];
if (isset($_GET['status']) && isset($pesan[$_GET['status']])) {
    $p = $pesan[$_GET['status']];
    echo "<div style='background:{$p[1]};color:#fff;padding:10px;margin-bottom:10px;border-radius:3px;'>{$p[0]}</div>";
}
?>
</html>