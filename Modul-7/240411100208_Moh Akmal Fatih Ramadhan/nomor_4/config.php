<?php

$HOST = 'localhost';
$USER = 'root';
$PASS = '';
$DBNAME = 'db_penjualan';

$DSN = "mysql:host=$HOST;dbname=$DBNAME;charset=utf8mb4";

try {

    $DBH = new PDO($DSN, $USER, $PASS);

    $DBH->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $DBH->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch (PDOException $e) {

    die("Koneksi database gagal: " . $e->getMessage());

}
