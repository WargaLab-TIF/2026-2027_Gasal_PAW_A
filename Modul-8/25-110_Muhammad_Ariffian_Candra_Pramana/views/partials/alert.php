<?php
$statusMessages = [
  "created" => "Data berhasil ditambahkan.",
  "updated" => "Data berhasil diperbarui.",
  "deleted" => "Data berhasil dihapus.",
];

$status = $_GET["status"] ?? null;
$error = trim((string) ($_GET["error"] ?? ""));
?>

<?php if ($status !== null && isset($statusMessages[$status])): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-1"></i><?= sanitazion($statusMessages[$status]) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
  </div>
<?php endif ?>

<?php if ($error !== ""): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle me-1"></i><?= sanitazion($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
  </div>
<?php endif ?>
