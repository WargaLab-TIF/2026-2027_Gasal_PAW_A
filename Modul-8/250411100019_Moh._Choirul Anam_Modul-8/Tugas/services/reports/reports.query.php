<?php
// Query data laporan penjualan.
// Dipakai oleh views/reports/reports.php dan services/reports/reports.export.php.
// Memakai koneksi PDO ($conn) dari conn.php.

function reports_tanggal_valid($tgl)
{
  $d = DateTime::createFromFormat('Y-m-d', (string)$tgl);
  return $d && $d->format('Y-m-d') === $tgl;
}

function reports_ambil_laporan(PDO $conn, $mulai, $selesai)
{
  $hasil = ['rekap' => [], 'total_pendapatan' => 0, 'total_pelanggan' => 0];

  // Agregasi total pendapatan per hari
  $stmt = $conn->prepare(
    "SELECT DATE(waktu_transaksi) AS tgl, SUM(total) AS total_harian
     FROM transaksi
     WHERE waktu_transaksi BETWEEN :mulai AND :selesai
     GROUP BY DATE(waktu_transaksi)
     ORDER BY tgl ASC"
  );
  $stmt->execute([':mulai' => $mulai, ':selesai' => $selesai]);
  foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
    $hasil['rekap'][] = $r;
    $hasil['total_pendapatan'] += (int)$r['total_harian'];
  }

  // Jumlah pelanggan unik pada periode
  $stmt2 = $conn->prepare(
    "SELECT COUNT(DISTINCT pelanggan_id) AS jml
     FROM transaksi
     WHERE waktu_transaksi BETWEEN :mulai AND :selesai"
  );
  $stmt2->execute([':mulai' => $mulai, ':selesai' => $selesai]);
  $hasil['total_pelanggan'] = (int)$stmt2->fetchColumn();

  return $hasil;
}
