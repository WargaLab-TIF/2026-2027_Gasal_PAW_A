<?php
require_once 'koneksi.php';

$id = $_POST['id'];

$data = $DBH->prepare("DELETE FROM supplier WHERE id = :id");
$data->execute([':id' => $id]);

header('Location: index.php');
exit;
?>