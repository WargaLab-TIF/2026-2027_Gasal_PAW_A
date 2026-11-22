<?php

require_once __DIR__ . "/../../route.php";
$pageTitle = "Data Transaksi";
$data = $DBH->query("
  SELECT
    t.id,
    t.waktu_transaksi,
    t.keterangan,
    t.total,
    p.nama AS pelanggan_nama,
    (SELECT COUNT(*) FROM transaksi_detail AS td WHERE td.transaksi_id = t.id) AS jumlah_item
  FROM transaksi AS t
  LEFT JOIN pelanggan AS p ON t.pelanggan_id = p.id
  ORDER BY t.id ASC
")->fetchAll();

require_once __DIR__ . "/../partials/header.php";
?>

<main class="container py-5">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
      <h1 class="h3 fw-bold text-secondary mb-1">Data Transaksi</h1>
      <p class="text-muted mb-0">Kelola transaksi penjualan beserta detail barangnya.</p>
    </div>
    <a href="<?= appUrl("transaksi", "add") ?>" class="btn btn-primary px-4">
      <i class="bi bi-plus-lg me-1"></i> Tambah Transaksi
    </a>
  </div>

  <?php require __DIR__ . "/../partials/alert.php" ?>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-primary">
          <tr>
            <th class="text-center" style="width: 60px;">No</th>
            <th class="text-center">ID</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Keterangan</th>
            <th class="text-center">Item</th>
            <th class="text-end">Total</th>
            <th class="text-center" style="width: 230px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$data): ?>
            <tr>
              <td colspan="8" class="text-center text-muted py-4">Belum ada data transaksi.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($data as $i => $row): ?>
              <tr>
                <td class="text-center"><?= $i + 1 ?></td>
                <td class="text-center"><span class="badge text-bg-secondary">#<?= (int) $row["id"] ?></span></td>
                <td class="text-nowrap"><?= sanitazion(formatTanggal($row["waktu_transaksi"])) ?></td>
                <td><?= sanitazion($row["pelanggan_nama"] ?? "-") ?></td>
                <td class="text-truncate" style="max-width: 220px;"
                  title="<?= sanitazion($row["keterangan"]) ?>"><?= sanitazion($row["keterangan"] ?: "-") ?></td>
                <td class="text-center">
                  <span class="badge <?= (int) $row["jumlah_item"] > 0 ? "text-bg-success" : "text-bg-secondary" ?>">
                    <?= (int) $row["jumlah_item"] ?>
                  </span>
                </td>
                <td class="text-end text-nowrap fw-medium"><?= formatRupiah($row["total"]) ?></td>
                <td class="text-center text-nowrap">
                  <a href="<?= appUrl("transaksi", "detail", ["id" => $row["id"]]) ?>"
                    class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-list-check"></i> Detail
                  </a>
                  <a href="<?= appUrl("transaksi", "edit", ["id" => $row["id"]]) ?>"
                    class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>
                  <a href="<?= apiUrl("transaksi", "delete", ["id" => $row["id"]]) ?>"
                    class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini beserta seluruh detailnya?');">
                    <i class="bi bi-trash"></i> Hapus
                  </a>
                </td>
              </tr>
            <?php endforeach ?>
          <?php endif ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<?php require_once __DIR__ . "/../partials/footer.php" ?>
