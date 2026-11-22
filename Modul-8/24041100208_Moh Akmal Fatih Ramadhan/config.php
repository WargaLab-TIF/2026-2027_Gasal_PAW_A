<?php

$HOST = 'localhost';
$USER = 'root';
$PASS = '';
$DBNAME = 'db_penjualan';

$DSN = "mysql:host={$HOST};dbname={$DBNAME};charset=utf8mb4";

try {
    $DBH = new PDO($DSN, $USER, $PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

} catch (PDOException $e) {

    http_response_code(500);

    exit(
        'Koneksi database gagal. Periksa MySQL dan pengaturan config.php. Detail: ' .
        htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
    );
}
