<?php
// 1. PENGATURAN HEADER WAJIB DI PALING ATAS
// Tidak boleh ada spasi atau HTML sebelum baris ini
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Penjualan.xls");
header("Pragma: no-cache");
header("Expires: 0");

// 2. KONEKSI DATABASE
// Jika Anda menggunakan file koneksi.php sendiri, hapus 3 baris ke bawah ini 
// dan ganti dengan: include 'koneksi.php';
$koneksi = mysqli_connect("localhost", "root", "", "penjualan");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (!empty($_GET['tanggal_mulai']) && !empty($_GET['tanggal_selesai'])) {
    $mulai = $_GET['tanggal_mulai'];
    $selesai = $_GET['tanggal_selesai'];

    $q = "SELECT waktu_transaksi AS tgl, SUM(total) AS total_harian 
          FROM transaksi WHERE waktu_transaksi BETWEEN '$mulai' AND '$selesai' 
          GROUP BY waktu_transaksi ORDER BY tgl ASC";
    $res = mysqli_query($koneksi, $q);
?>
<!-- Tabel akan otomatis dikonversi menjadi format Excel oleh browser -->
<table border="1">
    <tr>
        <th>No</th>
        <th>Total Pendapatan</th>
        <th>Tanggal Transaksi</th>
    </tr>
    <?php
    $no = 1;
    while ($row = mysqli_fetch_assoc($res)) {
        echo "<tr>";
        echo "<td>" . $no++ . "</td>";
        echo "<td>" . $row['total_harian'] . "</td>";
        echo "<td>" . $row['tgl'] . "</td>";
        echo "</tr>";
    }
    ?>
</table>
<?php } ?>