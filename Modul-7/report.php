<!DOCTYPE html> 
<html> 
<head> 
    <title>Laporan Penjualan</title> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    <style> 
        table { border-collapse: collapse; width: 100%; margin-top: 15px; } 
        th, td { border: 1px solid #000; padding: 6px; text-align: left; } 
        /* Menyembunyikan tombol dan form saat dicetak */
        @media print { .no-print { display: none !important; } } 
    </style> 
</head> 
<body> 
    <div class="no-print"> 
        <h1>Filter Laporan Penjualan</h1> 
        <!-- Form filter rentang tanggal -->
        <form method="GET"> 
            Dari: <input type="date" name="tanggal_mulai" value="<?= $_GET['tanggal_mulai'] ?? '' ?>" required> 
            Sampai: <input type="date" name="tanggal_selesai" value="<?= $_GET['tanggal_selesai'] ?? '' ?>" required> 
            <button type="submit">Tampilkan</button> 
        </form> 
        <br>
        <!-- Fitur Cetak (Print) dan Ekspor Microsoft Excel -->
        <button onclick="window.print()">Cetak</button> 
        <a href="export_excel.php?tanggal_mulai=<?= $_GET['tanggal_mulai'] ?? '' ?>&tanggal_selesai=<?= $_GET['tanggal_selesai'] ?? '' ?>"><button>Excel</button></a> 
    </div> 

    <?php 
    // Sesuaikan dengan koneksi database Anda
    include 'koneksi.php';
    $koneksi = mysqli_connect("localhost", "root", "", "penjualan"); 
    if (mysqli_connect_error()) { 
        die("Koneksi database gagal: " . mysqli_connect_error()); 
    } 
    
    $labels = []; $data_total = []; $rekap = []; 
    $total_pendapatan = 0; $total_pelanggan = 0; 
    
    if (!empty($_GET['tanggal_mulai']) && !empty($_GET['tanggal_selesai'])) { 
        $mulai = $_GET['tanggal_mulai']; 
        $selesai = $_GET['tanggal_selesai']; 
        
        // Query agregasi total pendapatan harian sesuai rentang waktu filter
        $q = "SELECT waktu_transaksi AS tgl, SUM(total) AS total_harian 
              FROM transaksi WHERE waktu_transaksi BETWEEN '$mulai' AND '$selesai' 
              GROUP BY waktu_transaksi ORDER BY tgl ASC"; 
        $res = mysqli_query($koneksi, $q); 
        
        while ($r = mysqli_fetch_assoc($res)) { 
            $labels[] = $r['tgl']; 
            $data_total[] = $r['total_harian']; 
            $rekap[] = $r; 
            $total_pendapatan += $r['total_harian']; 
        } 
        
        // Query jumlah pelanggan unik (tanpa duplikasi)
        $qp = "SELECT COUNT(DISTINCT pelanggan_id) AS jml FROM transaksi 
               WHERE waktu_transaksi BETWEEN '$mulai' AND '$selesai'"; 
        $rp = mysqli_fetch_assoc(mysqli_query($koneksi, $qp)); 
        $total_pelanggan = $rp['jml'] ?? 0; 
    } 
    ?> 

    <?php if (!empty($labels)): ?> 
    <!-- Visualisasi grafik batang (bar chart) -->
    <h2>Grafik Penjualan</h2> 
    <div style="width: 600px; height: 300px;"><canvas id="myChart"></canvas></div> 
    <script> 
        const ctx = document.getElementById('myChart').getContext('2d'); 
        new Chart(ctx, { 
            type: 'bar', 
            data: { 
                labels: <?= json_encode($labels); ?>, 
                datasets: [{ label: 'Total Pendapatan', data: <?= json_encode($data_total); ?> }] 
            } 
        }); 
    </script> 
    
    <!-- Tabel Rekapitulasi Data Penjualan Harian -->
    <h2>Rekapitulasi</h2> 
    <table> 
        <tr><th>No</th><th>Total Pendapatan</th><th>Tanggal Transaksi</th></tr> 
        <?php $no = 1; foreach ($rekap as $row): ?> 
        <tr> 
            <td><?= $no++; ?></td> 
            <td>Rp. <?= number_format($row['total_harian'], 0, ',', '.'); ?></td> 
            <td><?= $row['tgl']; ?></td> 
        </tr> 
        <?php endforeach; ?> 
    </table> 
    
    <!-- Card Ringkasan Informasi Total Kumulatif -->
    <div style="border: 1px solid #000; padding: 10px; margin-top: 15px; width: max-content;">
        <p><b>Jumlah Pelanggan Unik:</b> <?= $total_pelanggan; ?> Orang</p>
        <p><b>Total Pendapatan Keseluruhan:</b> Rp. <?= number_format($total_pendapatan, 0, ',', '.'); ?></p>
    </div>
    <?php endif; ?> 
</body> 
</html>