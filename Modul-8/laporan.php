<style> 
    /* Menyembunyikan tombol, form, dan navbar saat dicetak */
    @media print { 
        .no-print, .navbar { display: none !important; } 
    } 
</style> 

<!-- Panggil Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 

<div class="card shadow-sm">
    <div class="card-header no-print">
        <h4 class="mb-0">Filter Laporan Penjualan</h4>
    </div>
    <div class="card-body">
        <div class="no-print"> 
            <!-- Form filter rentang tanggal (tambah hidden input 'page' agar tetap di halaman laporan) -->
            <form method="GET" action="index.php" class="row g-3 align-items-end"> 
                <input type="hidden" name="page" value="laporan">
                <div class="col-auto">
                    <label class="form-label">Dari:</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="<?= $_GET['tanggal_mulai'] ?? '' ?>" required> 
                </div>
                <div class="col-auto">
                    <label class="form-label">Sampai:</label>
                    <input type="date" name="tanggal_selesai" class="form-control" value="<?= $_GET['tanggal_selesai'] ?? '' ?>" required> 
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Tampilkan</button> 
                </div>
            </form> 
            
            <div class="mt-3">
                <button onclick="window.print()" class="btn btn-secondary">Cetak</button> 
                <a href="export_excel.php?tanggal_mulai=<?= $_GET['tanggal_mulai'] ?? '' ?>&tanggal_selesai=<?= $_GET['tanggal_selesai'] ?? '' ?>" class="btn btn-success">Excel</a> 
            </div>
        </div> 

        <?php 
        $labels = []; $data_total = []; $rekap = []; 
        $total_pendapatan = 0; $total_pelanggan = 0; 
        
        if (!empty($_GET['tanggal_mulai']) && !empty($_GET['tanggal_selesai'])) { 
            $mulai = $_GET['tanggal_mulai']; 
            $selesai = $_GET['tanggal_selesai']; 
            
            // Query agregasi total pendapatan harian menggunakan PDO
            $q = "SELECT waktu_transaksi AS tgl, SUM(total) AS total_harian 
                  FROM transaksi WHERE waktu_transaksi BETWEEN :mulai AND :selesai 
                  GROUP BY waktu_transaksi ORDER BY tgl ASC"; 
            $stmt = $conn->prepare($q);
            $stmt->execute([':mulai' => $mulai, ':selesai' => $selesai]);
            
            while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                $labels[] = $r['tgl']; 
                $data_total[] = $r['total_harian']; 
                $rekap[] = $r; 
                $total_pendapatan += $r['total_harian']; 
            } 
            
            // Query jumlah pelanggan unik menggunakan PDO
            $qp = "SELECT COUNT(DISTINCT pelanggan_id) AS jml FROM transaksi 
                   WHERE waktu_transaksi BETWEEN :mulai AND :selesai"; 
            $stmt_p = $conn->prepare($qp);
            $stmt_p->execute([':mulai' => $mulai, ':selesai' => $selesai]);
            $rp = $stmt_p->fetch(PDO::FETCH_ASSOC); 
            $total_pelanggan = $rp['jml'] ?? 0; 
        } 
        ?> 

        <?php if (!empty($labels)): ?> 
        <h4 class="mt-5">Grafik Penjualan</h4> 
        <div style="width: 100%; max-width: 700px; height: 350px;">
            <canvas id="myChart"></canvas>
        </div> 
        <script> 
            const ctx = document.getElementById('myChart').getContext('2d'); 
            new Chart(ctx, { 
                type: 'bar', 
                data: { 
                    labels: <?= json_encode($labels); ?>, 
                    datasets: [{ 
                        label: 'Total Pendapatan', 
                        data: <?= json_encode($data_total); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }] 
                } 
            }); 
        </script> 
        
        <h4 class="mt-5">Rekapitulasi</h4> 
        <table class="table table-bordered table-striped mt-3"> 
            <thead class="table-dark">
                <tr><th>No</th><th>Total Pendapatan</th><th>Tanggal Transaksi</th></tr> 
            </thead>
            <tbody>
            <?php $no = 1; foreach ($rekap as $row): ?> 
            <tr> 
                <td><?= $no++; ?></td> 
                <td>Rp. <?= number_format($row['total_harian'], 0, ',', '.'); ?></td> 
                <td><?= $row['tgl']; ?></td> 
            </tr> 
            <?php endforeach; ?> 
            </tbody>
        </table> 
        
        <div class="alert alert-info mt-4" style="width: max-content;">
            <p class="mb-1"><b>Jumlah Pelanggan Unik:</b> <?= $total_pelanggan; ?> Orang</p>
            <p class="mb-0"><b>Total Pendapatan Keseluruhan:</b> Rp. <?= number_format($total_pendapatan, 0, ',', '.'); ?></p>
        </div>
        <?php endif; ?> 
    </div>
</div>