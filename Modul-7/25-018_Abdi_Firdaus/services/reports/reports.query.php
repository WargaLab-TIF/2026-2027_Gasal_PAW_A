<?php
// ==========================================================
// FILE QUERY LAPORAN PENJUALAN: reports.query.php
// ==========================================================

// Fungsi untuk memvalidasi apakah input tanggal berformat Y-m-d (contoh: 2023-09-01)
// Mengembalikan nilai tanggal jika valid, atau null jika format tidak sesuai
function parseDate($value)
{
  $date = DateTime::createFromFormat("Y-m-d", (string) $value);
  return ($date && $date->format("Y-m-d") === $value) ? $value : null;
}

// Fungsi untuk mengambil data rekapitulasi penjualan harian dan total kumulatif dari database
function getSalesReport($conn, $tanggal_mulai, $tanggal_selesai)
{
  // 1. Query agregasi untuk menjumlahkan total pendapatan per hari (GROUP BY tanggal)
  $rekapSQL = <<<SQL
    SELECT
      DATE(waktu_transaksi) AS tgl,
      SUM(total) AS total_pendapatan
    FROM transaksi
    WHERE DATE(waktu_transaksi) BETWEEN ? AND ?
    GROUP BY DATE(waktu_transaksi)
    ORDER BY tgl ASC;
  SQL;

  // 2. Query ringkasan kumulatif untuk menghitung jumlah pelanggan unik (COUNT DISTINCT)
  //    dan akumulasi total pendapatan keseluruhan (SUM) pada periode yang dipilih
  $summarySQL = <<<SQL
    SELECT
      COUNT(DISTINCT pelanggan_id) AS pelanggan,
      COALESCE(SUM(total), 0) AS pendapatan
    FROM transaksi
    WHERE DATE(waktu_transaksi) BETWEEN ? AND ?;
  SQL;

  // Menjalankan prepared statement untuk query rekapitulasi harian
  $rekap = $conn->prepare($rekapSQL);
  $rekap->execute([$tanggal_mulai, $tanggal_selesai]);

  // Menjalankan prepared statement untuk query ringkasan total kumulatif
  $summary = $conn->prepare($summarySQL);
  $summary->execute([$tanggal_mulai, $tanggal_selesai]);

  // Mengembalikan hasil kedua query: [daftar baris harian, baris ringkasan total]
  return [$rekap->fetchAll(), $summary->fetch()];
}
