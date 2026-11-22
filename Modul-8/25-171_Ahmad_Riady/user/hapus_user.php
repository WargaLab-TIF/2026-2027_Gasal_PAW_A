<?php
require_once '../cek_owner.php';
require_once '../koneksi.php';

if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    $data = $DBH->prepare("SELECT username FROM user WHERE id_user = :id");
    $data->execute([':id' => $id]);
    $user = $data->fetch();

    if ($user && $user['username'] != 'admin') {
        $hapus = $DBH->prepare("DELETE FROM user WHERE id_user = :id");
        $hapus->execute([':id' => $id]);
    }

    header('Location: index.php');
    exit;
}
?>