<?php

// Validasi format tanggal Y-m-d, kembalikan null jika tidak valid
function parseDate($value)
{
  $date = DateTime::createFromFormat("Y-m-d", (string) $value);

  return ($date && $date->format("Y-m-d") === $value) ? $value : null;
}

// Rekap penjualan harian + ringkasan total pada rentang tanggal
function getPenjualanReport($DBH, $mulai, $selesai)
{
  $rekapSQL = <<<SQL
    SELECT
      waktu_transaksi AS tgl,
      SUM(total) AS total
    FROM transaksi
    WHERE waktu_transaksi BETWEEN ? AND ?
    GROUP BY waktu_transaksi
    ORDER BY waktu_transaksi ASC;
  SQL;

  $summarySQL = <<<SQL
    SELECT
      COUNT(*) AS total,
      COUNT(DISTINCT pelanggan_id) AS pelanggan,
      COALESCE(SUM(total), 0) AS pendapatan
    FROM transaksi
    WHERE waktu_transaksi BETWEEN ? AND ?;
  SQL;

  $rekap = $DBH->prepare($rekapSQL);
  $rekap->execute([$mulai, $selesai]);

  $summary = $DBH->prepare($summarySQL);
  $summary->execute([$mulai, $selesai]);

  return [$rekap->fetchAll(PDO::FETCH_ASSOC), $summary->fetch(PDO::FETCH_ASSOC)];
}
