
<?php
require_once 'koneksi.php';

$id = $_GET['id'] ?? '';

$cek = $DBH->prepare("SELECT id FROM supplier WHERE id = :id");
$cek->execute([':id' => $id]);

$query = $DBH->prepare("DELETE FROM supplier WHERE id = :id");
$query->execute([':id' => $id]);
exit;

