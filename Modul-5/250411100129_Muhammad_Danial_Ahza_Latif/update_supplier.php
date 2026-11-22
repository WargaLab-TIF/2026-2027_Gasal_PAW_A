
<?php
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'] ?? '';
    $nama = trim($_POST['nama'] ?? '');
    $telp = trim($_POST['telp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    if (
        !ctype_digit((string) $id) ||
        $nama === '' ||
        $telp === '' ||
        $alamat === ''
    ) {
        die("Data supplier tidak valid.");
    }

    $query = $DBH->prepare("UPDATE supplier SET nama = :nama,   telp = :telp,   alamat = :alamat WHERE id = :id");

    $query->execute([
        ':nama' => $nama,
        ':telp' => $telp,
        ':alamat' => $alamat,
        ':id' => $id
    ]);
}

