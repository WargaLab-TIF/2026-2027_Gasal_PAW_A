<?php
// Daftar Data Master. Variabel dari router: $conn, $entity, $cfg

$rows  = $conn->query($cfg['list_sql'])->fetchAll();
$title = $cfg['title'];

?>
<?php require_once __DIR__ . "/../partials/header.auth.php" ?>

<div class="page-head">
  <h1><?= e($cfg['title']) ?></h1>
  <a class="btn" href="<?= e(url("/$entity/add")) ?>">Tambah</a>
</div>

<?= render_flash() ?>

<?php if (!$rows): ?>
  <p>Belum ada data.</p>
<?php else: ?>
  <table>
    <tr>
      <th>No</th>
      <?php foreach ($cfg['columns'] as $label): ?><th><?= e($label) ?></th><?php endforeach ?>
      <th class="aksi">Aksi</th>
    </tr>
    <?php $no = 1; foreach ($rows as $row): ?>
      <tr>
        <td><?= $no++ ?></td>
        <?php foreach ($cfg['columns'] as $col => $label): ?>
          <td><?= in_array($col, $cfg['rupiah'] ?? [], true) ? e(rupiah($row[$col])) : e($row[$col]) ?></td>
        <?php endforeach ?>
        <td class="aksi">
          <a class="btn btn-kecil" href="<?= e(url("/$entity/edit?id=" . urlencode($row[$cfg['pk']]))) ?>">Ubah</a>
          <form class="inline" method="POST" action="<?= e(url("/api/$entity/delete")) ?>"
            onsubmit="return confirm('Hapus data ini?')">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= e($row[$cfg['pk']]) ?>">
            <button type="submit" class="btn btn-kecil btn-merah">Hapus</button>
          </form>
        </td>
      </tr>
    <?php endforeach ?>
  </table>
<?php endif ?>

<?php require_once __DIR__ . "/../partials/footer.auth.php" ?>
