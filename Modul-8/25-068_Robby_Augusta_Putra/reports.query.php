<?php

require_once __DIR__ . '/utils.php';

function getRekapHarian(string $mulai, string $selesai): array
{
    global $db;

    $sql = "SELECT DATE(waktu_transaksi) AS tgl,
                   SUM(total)            AS total_harian
            FROM transaksi
            WHERE DATE(waktu_transaksi) BETWEEN ? AND ?
            GROUP BY DATE(waktu_transaksi)
            ORDER BY tgl ASC";

    $stmt = $db->prepare($sql);
    $stmt->execute([$mulai, $selesai]);
    $rekap = $stmt->fetchAll();

    foreach ($rekap as &$row) {
        $row['total_harian'] = (int) $row['total_harian'];
    }
    unset($row);

    return $rekap;
}

function getRingkasan(string $mulai, string $selesai): array
{
    global $db;

    $sql = "SELECT COUNT(DISTINCT pelanggan_id) AS jumlah_pelanggan,
                   COALESCE(SUM(total), 0)      AS total_pendapatan
            FROM transaksi
            WHERE DATE(waktu_transaksi) BETWEEN ? AND ?";

    $stmt = $db->prepare($sql);
    $stmt->execute([$mulai, $selesai]);
    $row = $stmt->fetch();

    return [
        'jumlah_pelanggan' => (int) ($row['jumlah_pelanggan'] ?? 0),
        'total_pendapatan' => (int) ($row['total_pendapatan'] ?? 0),
    ];
}
