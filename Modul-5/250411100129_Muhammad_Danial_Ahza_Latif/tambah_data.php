<?php

require_once 'koneksi.php';

$nama = $_POST['nama'] ?? '';;
$telp = $_POST['telp'] ?? '';;
$alamat = $_POST['alamat'] ?? '';;
$data = $DBH->prepare("INSERT INTO supplier (nama, telp, alamat)    VALUES (:nama, :telp, :alamat)");
$data->execute([
    ':nama' => $nama,
    ':telp' => $telp,
    ':alamat' => $alamat
]);
