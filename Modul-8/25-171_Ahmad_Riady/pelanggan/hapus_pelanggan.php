<?php
require_once '../cek_owner.php';
require_once '../koneksi.php';

if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    $data = $DBH->prepare("DELETE FROM pelanggan WHERE id = :id");
    $data->execute([':id' => $id]);

    header('Location: index.php');
    exit;
}
?>