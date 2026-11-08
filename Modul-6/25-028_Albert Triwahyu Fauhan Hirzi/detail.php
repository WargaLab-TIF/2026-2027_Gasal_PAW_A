<?php
include 'koneksi.php';

// Ambil ID Transaksi dari URL
$id_transaksi = $_GET['id'] ?? 0;

// Proses Simpan Detail Barang Baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['barang_id'])) {
    $barang_id = $_POST['barang_id'];
    $qty = $_POST['qty'];
    
    // Ambil harga dari tabel barang master
    $q_harga = mysqli_query($conn, "SELECT harga FROM barang WHERE id = '$barang_id'");
    $harga = mysqli_fetch_assoc($q_harga)['harga'];
    
    // Insert ke tabel transaksi_detail
    $query_insert = "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES ('$id_transaksi', '$barang_id', '$harga', '$qty')";
    mysqli_query($conn, $query_insert);
    
    // TUGAS 4: Update total otomatis pada tabel transaksi
    $q_total = mysqli_query($conn, "SELECT SUM(harga * qty) AS total_baru FROM transaksi_detail WHERE transaksi_id = '$id_transaksi'");
    $total_baru = mysqli_fetch_assoc($q_total)['total_baru'];
    if (!$total_baru) $total_baru = 0; // Antisipasi jika kosong
    
    mysqli_query($conn, "UPDATE transaksi SET total = '$total_baru' WHERE id = '$id_transaksi'");
    
    // Refresh halaman agar data terbaru termuat
    header("Location: detail.php?id=$id_transaksi");
    exit();
}

// Ambil array barang yang sudah ada di transaksi_detail agar bisa di-disabled (Tugas 2)
$q_terpakai = mysqli_query($conn, "SELECT barang_id FROM transaksi_detail WHERE transaksi_id = '$id_transaksi'");
$barang_terpakai = [];
while ($row = mysqli_fetch_assoc($q_terpakai)) {
    $barang_terpakai[] = $row['barang_id'];
}

$query_barang = mysqli_query($conn, "SELECT * FROM barang");
$query_detail = mysqli_query($conn, "SELECT td.*, b.nama_barang FROM transaksi_detail td JOIN barang b ON td.barang_id = b.id WHERE td.transaksi_id = '$id_transaksi'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Transaksi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar"></div>
    <div class="container">
        <h1>Input Detail Transaksi #<?php echo $id_transaksi; ?></h1>
        <a href="index.php" class="btn-tambah" style="background-color: #004b87;">Kembali ke Transaksi</a>
        
        <div style="clear:both; margin-bottom: 20px;"></div>
        
        <form method="POST" style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border: 1px solid #ccc; display: inline-block;">
            <label style="font-weight: bold;">Pilih Barang:</label>
            <select name="barang_id" required style="padding: 6px;">
                <option value="">-- Pilih Barang --</option>
                <?php 
                while($b = mysqli_fetch_assoc($query_barang)): 
                    $disabled = in_array($b['id'], $barang_terpakai) ? "disabled" : "";
                ?>
                    <option value="<?php echo $b['id']; ?>" <?php echo $disabled; ?>>
                        <?php echo $b['nama_barang']; ?> - Rp <?php echo number_format($b['harga'], 0, ',', '.'); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            
            <label style="font-weight: bold; margin-left: 15px;">Qty:</label>
            <input type="number" name="qty" required min="1" value="1" style="padding: 6px; width: 60px;">
            
            <button type="submit" class="btn btn-simpan" style="margin-left: 15px;">Tambah ke Nota</button>
        </form>
        
        <h3>Daftar Barang di Transaksi Ini</h3>
        <table>
            <tr>
                <th>Barang</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
            <?php 
            $grand_total = 0;
            while($d = mysqli_fetch_assoc($query_detail)): 
                $subtotal = $d['harga'] * $d['qty'];
                $grand_total += $subtotal;
            ?>
            <tr>
                <td><?php echo $d['nama_barang']; ?></td>
                <td>Rp <?php echo number_format($d['harga'], 0, ',', '.'); ?></td>
                <td><?php echo $d['qty']; ?></td>
                <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
            </tr>
            <?php endwhile; ?>
            <tr>
                <th colspan="3" style="text-align: right;">Total Nota</th>
                <th>Rp <?php echo number_format($grand_total, 0, ',', '.'); ?></th>
            </tr>
        </table>
    </div>
</body>
</html>