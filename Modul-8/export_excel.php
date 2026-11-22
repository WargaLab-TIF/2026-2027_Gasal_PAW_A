<?php
// Manipulasi HTTP Response Header untuk export ke Microsoft Excel (.xls)
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Penjualan.xls");

// Panggil file koneksi PDO yang sudah kita buat sebelumnya
require 'koneksi.php';

if (!empty($_GET['tanggal_mulai']) && !empty($_GET['tanggal_selesai'])) {
    $mulai = $_GET['tanggal_mulai'];
    $selesai = $_GET['tanggal_selesai'];

    // Menarik kembali data menggunakan PDO dan Prepared Statement
    $q = "SELECT waktu_transaksi AS tgl, SUM(total) AS total_harian 
          FROM transaksi WHERE waktu_transaksi BETWEEN :mulai AND :selesai 
          GROUP BY waktu_transaksi ORDER BY tgl ASC";
    $stmt = $conn->prepare($q);
    $stmt->execute([':mulai' => $mulai, ':selesai' => $selesai]);
?>
<!-- Menyusun tabel rekapitulasi untuk ditampilkan di Excel -->
<table border="1">
    <tr>
        <th>No</th>
        <th>Total Pendapatan</th>
        <th>Tanggal Transaksi</th>
    </tr>
    <?php
    $no = 1;
    // Fetch data menggunakan metode PDO
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $no++ . "</td>";
        echo "<td>" . $row['total_harian'] . "</td>";
        echo "<td>" . $row['tgl'] . "</td>";
        echo "</tr>";
    }
    ?>
</table>
<?php } ?>