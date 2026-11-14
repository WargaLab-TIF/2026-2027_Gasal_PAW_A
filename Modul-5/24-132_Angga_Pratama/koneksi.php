<?php
$koneksi = mysqli_connect("localhost", "root", "", "modul05");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>