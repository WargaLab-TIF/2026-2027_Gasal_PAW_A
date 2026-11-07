<?php
$koneksi = mysqli_connect("localhost", "root", "", "tp_6");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>