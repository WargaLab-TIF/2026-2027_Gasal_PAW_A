<?php
// query laporan penjualan

// cek format tanggal Y-m-d
function parseDate($value)
{
  $date = DateTime::createFromFormat("Y-m-d", (string) $value);
  return ($date && $date->format("Y-m-d") === $value) ? $value : null;
}

// ambil rekap harian dan total penjualan
function getSalesReport($conn, $tanggal_mulai, $tanggal_selesai)
{
  $rekapSQL = <<<SQL
    SELECT
      DATE(waktu_transaksi) AS tgl,
      SUM(total) AS total_pendapatan
    FROM transaksi
    WHERE DATE(waktu_transaksi) BETWEEN ? AND ?
    GROUP BY DATE(waktu_transaksi)
    ORDER BY tgl ASC;
  SQL;

  $summarySQL = <<<SQL
    SELECT
      COUNT(DISTINCT pelanggan_id) AS pelanggan,
      COALESCE(SUM(total), 0) AS pendapatan
    FROM transaksi
    WHERE DATE(waktu_transaksi) BETWEEN ? AND ?;
  SQL;

  $rekap = $conn->prepare($rekapSQL);
  $rekap->execute([$tanggal_mulai, $tanggal_selesai]);

  $summary = $conn->prepare($summarySQL);
  $summary->execute([$tanggal_mulai, $tanggal_selesai]);

  return [$rekap->fetchAll(), $summary->fetch()];
}

// ambil tanggal transaksi paling awal dan akhir
function getTransactionDateRange($conn)
{
  return $conn->query("
    SELECT MIN(DATE(waktu_transaksi)) AS min_tgl, MAX(DATE(waktu_transaksi)) AS max_tgl
    FROM transaksi
  ")->fetch() ?: ['min_tgl' => null, 'max_tgl' => null];
}
