<?php

if (!function_exists('parseDate')) {
    function parseDate($value)
    {
        $date = DateTime::createFromFormat("Y-m-d", (string) $value);
        return ($date && $date->format("Y-m-d") === $value) ? $value : null;
    }
}

if (!function_exists('sanitazion')) {
    function sanitazion($data)
    {
        return htmlspecialchars((string) $data, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('getLoanReport')) {
    function getLoanReport($conn, $mulai, $selesai)
    {
        $rekapSQL = "SELECT waktu_transaksi AS tgl, COUNT(*) AS total, SUM(total) AS pendapatan_harian FROM transaksi WHERE waktu_transaksi BETWEEN ? AND ? GROUP BY waktu_transaksi ORDER BY waktu_transaksi ASC";
        $summarySQL = "SELECT COUNT(*) AS total, COUNT(DISTINCT pelanggan_id) AS pelanggan, COALESCE(SUM(total), 0) AS pendapatan FROM transaksi WHERE waktu_transaksi BETWEEN ? AND ?";

        $rekap = $conn->prepare($rekapSQL);
        $rekap->execute([$mulai, $selesai]);

        $summary = $conn->prepare($summarySQL);
        $summary->execute([$mulai, $selesai]);

        return [
            $rekap->fetchAll(PDO::FETCH_ASSOC) ?: [], 
            $summary->fetch(PDO::FETCH_ASSOC) ?: ["total" => 0, "pelanggan" => 0, "pendapatan" => 0]
        ];
    }
}