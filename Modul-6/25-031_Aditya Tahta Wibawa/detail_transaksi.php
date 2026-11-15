<?php 
include 'koneksi.php'; 
$transaksi_id = $_GET['id'];


$master = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id = '$transaksi_id'");
$data_master = mysqli_fetch_assoc($master);


$query_used = mysqli_query($koneksi, "SELECT barang_id FROM transaksi_detail WHERE transaksi_id = '$transaksi_id'");
$used_barang = [];
while ($row = mysqli_fetch_assoc($query_used)) {
    $used_barang[] = $row['barang_id'];
}
?>
<!DOCTYPE html>
<html>
<head><title>Master Detil Transaksi</title></head>
<body style="font-family: Arial, sans-serif; margin: 20px;">
    <h2>Form Master-Detil Transaksi</h2>
    <a href="transaksi.php"><= Kembali ke Daftar Transaksi</a><br><br>

    <fieldset style="background-color: #f9f9f9; border: 1px solid #ddd; margin-bottom: 20px; width: 77%;">
        <legend><b>Data Master</b></legend>
        <table border="0" cellpadding="5">
            <tr>
                <td><b>No. Transaksi</b></td><td>: TRX-<?= $data_master['id'] ?></td>
            </tr>
            <tr>
                <td><b>Tanggal</b></td><td>: <?= $data_master['waktu_transaksi'] ?></td>
            </tr>
            <tr>
                <td><b>Total Saat Ini</b></td>
                <td style="color: blue; font-weight:bold;">: Rp <?= number_format($data_master['total'], 0, ',', '.') ?></td>
            </tr>
        </table>
    </fieldset>

    <form action="simpan_detail.php" method="POST" style="margin-bottom: 20px;">
        <input type="hidden" name="transaksi_id" value="<?= $transaksi_id ?>">
        
        <b>Tambah Item:</b>
        <select name="barang_id" required>
            <option value="">-- Pilih Barang --</option>
            <?php
            $brg = mysqli_query($koneksi, "SELECT id, nama_barang, harga, stok FROM barang");
            while ($b = mysqli_fetch_assoc($brg)) {
                $disabled = in_array($b['id'], $used_barang) ? 'disabled' : '';
                $teks_tambahan = $disabled ? '(Sudah dipakai di transaksi ini)' : "(Stok: {$b['stok']})";
                
                echo "<option value='{$b['id']}' $disabled>
                        {$b['nama_barang']} - Rp{$b['harga']} $teks_tambahan
                      </option>";
            }
            ?>
        </select>

        <input type="number" name="qty" min="1" placeholder="Qty" required style="width: 60px;">
        <button type="submit">Simpan ke Detail</button>
    </form>

    <table border="1" cellpadding="8" cellspacing="0" width="80%">
        <tr style="background-color: #f2f2f2;">
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Harga Satuan</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
        <?php
        $detail = mysqli_query($koneksi, "
            SELECT b.kode_barang, b.nama_barang, td.harga, td.qty 
            FROM transaksi_detail td 
            JOIN barang b ON td.barang_id = b.id 
            WHERE td.transaksi_id = '$transaksi_id'
        ");
        
        if(mysqli_num_rows($detail) == 0){
             echo "<tr><td colspan='5' align='center'>Belum ada item barang.</td></tr>";
        }

        while ($d = mysqli_fetch_assoc($detail)) {
            $subtotal = $d['harga'] * $d['qty'];
            echo "<tr>
                    <td align='center'>{$d['kode_barang']}</td>
                    <td>{$d['nama_barang']}</td>
                    <td align='right'>Rp " . number_format($d['harga'], 0, ',', '.') . "</td>
                    <td align='center'>{$d['qty']}</td>
                    <td align='right'>Rp " . number_format($subtotal, 0, ',', '.') . "</td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>