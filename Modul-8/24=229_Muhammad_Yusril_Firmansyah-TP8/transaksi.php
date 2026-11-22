<?php
require_once "includes/auth.php";
wajibLogin();
require_once "config/koneksi.php";
require_once "includes/header.php";
$sql = "SELECT t.id_transaksi,t.waktu_transaksi,t.keterangan,t.total,p.nama_pelanggan,u.nama
        FROM transaksi t
        JOIN pelanggan p ON t.id_pelanggan=p.id_pelanggan
        JOIN `user` u ON t.id_user=u.id_user
        ORDER BY t.waktu_transaksi DESC";
$data = $conn->query($sql);
?>
<div class="card">
    <h1>Data Transaksi</h1>
    <p class="muted">Halaman ini dapat diakses oleh Owner dan Kasir. Menampilkan transaksi yang tersimpan di database.</p>
    <table>
        <tr><th>ID</th><th>Waktu</th><th>Pelanggan</th><th>Kasir</th><th>Keterangan</th><th>Total</th></tr>
        <?php while($r=$data->fetch_assoc()): ?>
        <tr>
            <td><?= (int)$r['id_transaksi'] ?></td>
            <td><?= htmlspecialchars($r['waktu_transaksi']) ?></td>
            <td><?= htmlspecialchars($r['nama_pelanggan']) ?></td>
            <td><?= htmlspecialchars($r['nama']) ?></td>
            <td><?= htmlspecialchars($r['keterangan'] ?? '-') ?></td>
            <td>Rp <?= number_format((int)$r['total'],0,',','.') ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php require_once "includes/footer.php"; ?>
