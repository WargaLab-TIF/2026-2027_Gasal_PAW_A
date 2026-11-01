<?php
// Sesuaikan jika username/password MySQL Anda berbeda
$koneksi = mysqli_connect('localhost', 'root', '', 'tm');
if (!$koneksi) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}
mysqli_set_charset($koneksi, 'utf8mb4');
