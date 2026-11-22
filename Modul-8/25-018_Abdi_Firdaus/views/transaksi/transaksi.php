<?php
// halaman transaksi dan detailnya

$pageTitle = 'Transaksi';

// ambil daftar transaksi beserta nama pelanggan
$dataTransaksi = $conn->query("
  SELECT transaksi.*, pelanggan.nama AS nama_pelanggan
  FROM transaksi
  LEFT JOIN pelanggan ON transaksi.pelanggan_id = pelanggan.id
  ORDER BY transaksi.id DESC
")->fetchAll();

// tentukan transaksi yang sedang dipilih
$requestedId = isset($_GET['transaksi_id']) ? (int) $_GET['transaksi_id'] : 0;
$transaksiId = 0;
if (!empty($dataTransaksi)) {
  $validIds = array_map('intval', array_column($dataTransaksi, 'id'));
  $transaksiId = in_array($requestedId, $validIds, true) ? $requestedId : $validIds[0];
}

// ambil detail barang untuk transaksi terpilih
$dataDetail = [];
$barangTerpakai = [];
if ($transaksiId > 0) {
  $stmtDetail = $conn->prepare("
    SELECT transaksi_detail.*, barang.nama_barang
    FROM transaksi_detail
    INNER JOIN barang ON transaksi_detail.barang_id = barang.id
    WHERE transaksi_detail.transaksi_id = ?
    ORDER BY barang.nama_barang ASC
  ");
  $stmtDetail->execute([$transaksiId]);
  $dataDetail = $stmtDetail->fetchAll();
  $barangTerpakai = array_map('intval', array_column($dataDetail, 'barang_id'));
}

// daftar barang untuk dropdown detail
$dataBarang = $conn->query("SELECT id, kode_barang, nama_barang, harga, stok FROM barang ORDER BY id ASC")->fetchAll();

require_once __DIR__ . "/../partials/header.auth.php";
?>

<main class="container my-8">
  <!-- tabel transaksi -->
  <div class="card-panel p-6 mb-7">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-5">
      <h1 class="fs-2xl mb-0">Data Master Transaksi</h1>
      <div class="d-flex gap-2 flex-wrap">
        <a class="btn btn-solid theme-primary" href="<?= routeUrl("/transaksi/add") ?>">+ Tambah Transaksi</a>
        <?php if ($transaksiId > 0): ?>
          <a class="btn btn-outline theme-primary"
            href="<?= routeUrl("/transaksi/detail/add") ?>?transaksi_id=<?= $transaksiId ?>">+ Tambah Detail Transaksi</a>
        <?php endif ?>
      </div>
    </div>

    <?php if (empty($dataTransaksi)): ?>
      <div class="alert theme-info mb-0">Belum ada data transaksi. Klik <strong>+ Tambah Transaksi</strong> untuk memulai.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table mb-0">
          <thead>
            <tr>
              <th>No</th>
              <th>ID Transaksi</th>
              <th>Waktu Transaksi</th>
              <th>Nama Pelanggan</th>
              <th>Keterangan</th>
              <th>Total</th>
              <th class="text-end">Tindakan</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($dataTransaksi as $i => $row): ?>
              <tr class="<?= ((int) $row['id'] === $transaksiId) ? 'baris-aktif' : '' ?>">
                <td><?= $i + 1 ?></td>
                <td>#<?= sanitazion($row['id']) ?></td>
                <td><?= sanitazion($row['waktu_transaksi']) ?></td>
                <td><?= sanitazion($row['nama_pelanggan'] ?? '-') ?></td>
                <td><?= sanitazion($row['keterangan']) ?></td>
                <td><?= formatRupiah($row['total']) ?></td>
                <td class="text-end">
                  <a class="btn btn-text theme-primary"
                    href="<?= routeUrl("/transaksi") ?>?transaksi_id=<?= (int) $row['id'] ?>">Lihat Detail</a>
                  <a class="btn btn-text theme-danger"
                    href="<?= routeUrl("/api/transaksi/delete") ?>?id=<?= (int) $row['id'] ?>"
                    onclick="return confirm('Yakin ingin menghapus transaksi #<?= (int) $row['id'] ?> beserta seluruh detailnya?');">Hapus</a>
                </td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    <?php endif ?>
  </div>

  <!-- detail transaksi dan form input -->
  <?php if ($transaksiId > 0): ?>
    <div class="card-panel p-6">
      <h2 class="fs-xl mb-5">Data Detail Transaksi (ID Transaksi: #<?= $transaksiId ?>)</h2>

      <div class="row">
        <!-- tabel detail -->
        <div class="lg:col-7 mb-6">
          <?php if (empty($dataDetail)): ?>
            <div class="alert theme-warning mb-0">Belum ada item barang pada transaksi #<?= $transaksiId ?>.</div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table mb-0">
                <thead>
                  <tr>
                    <th>Transaksi ID</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Sub Total</th>
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($dataDetail as $d): ?>
                    <tr>
                      <td>#<?= sanitazion($d['transaksi_id']) ?></td>
                      <td><?= sanitazion($d['nama_barang']) ?></td>
                      <td><?= formatRupiah($d['harga']) ?></td>
                      <td><?= sanitazion($d['qty']) ?></td>
                      <td><?= formatRupiah((int) $d['harga'] * (int) $d['qty']) ?></td>
                      <td class="text-end">
                        <a class="btn btn-text theme-danger"
                          href="<?= routeUrl("/api/transaksi/detail/delete") ?>?transaksi_id=<?= (int) $d['transaksi_id'] ?>&barang_id=<?= (int) $d['barang_id'] ?>"
                          onclick="return confirm('Hapus barang ini dari detail transaksi?');">Hapus</a>
                      </td>
                    </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
            </div>
          <?php endif ?>
        </div>

        <!-- form tambah detail -->
        <div class="lg:col-5">
          <div class="p-5 border rounded">
            <h3 class="fs-lg mb-4">Form Input Detail Transaksi</h3>
            <form method="POST" action="<?= routeUrl("/api/transaksi/detail/add") ?>">
              <div class="form-field mb-4">
                <label class="form-label" for="transaksi_id">ID Transaksi</label>
                <select class="form-control" id="transaksi_id" name="transaksi_id"
                  onchange="window.location.href='<?= routeUrl('/transaksi') ?>?transaksi_id=' + this.value" required>
                  <?php foreach ($dataTransaksi as $t): ?>
                    <option value="<?= (int) $t['id'] ?>" <?= ((int) $t['id'] === $transaksiId) ? 'selected' : '' ?>>
                      ID #<?= sanitazion($t['id']) ?> - <?= sanitazion($t['keterangan']) ?>
                    </option>
                  <?php endforeach ?>
                </select>
              </div>

              <div class="form-field mb-4">
                <label class="form-label" for="barang_id">Pilih Barang</label>
                <select class="form-control" id="barang_id" name="barang_id" required>
                  <option value="">-- Pilih Barang --</option>
                  <?php foreach ($dataBarang as $b):
                    $sudahDipilih = in_array((int) $b['id'], $barangTerpakai, true);
                    ?>
                    <option value="<?= (int) $b['id'] ?>" <?= $sudahDipilih ? 'disabled' : '' ?>>
                      <?= sanitazion($b['nama_barang']) ?> - <?= formatRupiah($b['harga']) ?>
                      <?= $sudahDipilih ? '(Sudah dipilih)' : '' ?>
                    </option>
                  <?php endforeach ?>
                </select>
              </div>

              <div class="form-field mb-5">
                <label class="form-label" for="qty">Qty</label>
                <input class="form-control" type="number" id="qty" name="qty" min="1" placeholder="Masukkan jumlah barang"
                  required>
              </div>

              <button type="submit" class="btn btn-solid theme-primary w-100">Simpan Detail</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php endif ?>
</main>

<?php require_once __DIR__ . "/../partials/footer.auth.php" ?>
