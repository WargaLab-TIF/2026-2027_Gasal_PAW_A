<?php
// Form tambah / ubah Data Master. Variabel dari router: $conn, $entity, $action, $cfg

$isEdit = ($action === 'edit');
$row    = [];

if ($isEdit) {
  $stmt = $conn->prepare("SELECT * FROM `{$cfg['table']}` WHERE `{$cfg['pk']}` = ?");
  $stmt->execute([$_GET['id'] ?? '']);
  $row = $stmt->fetch();
  if (!$row) {
    require_once __DIR__ . "/../404.php";
    return;
  }
}

// Akun yang sedang login tidak boleh mengubah level-nya sendiri
$lockLevel = ($entity === 'users' && $isEdit && (int) $row['id_user'] === $_SESSION['user']['id']);

$title = ($isEdit ? "Ubah " : "Tambah ") . $cfg['title'];

?>
<?php require_once __DIR__ . "/../partials/header.auth.php" ?>

<h1><?= e($title) ?></h1>
<?= render_flash() ?>

<form class="form" method="POST" action="<?= e(url("/api/$entity/" . ($isEdit ? 'edit' : 'add'))) ?>">
  <?= csrf_field() ?>

  <?php if ($isEdit): ?>
    <input type="hidden" name="id" value="<?= e($row[$cfg['pk']]) ?>">
  <?php elseif (!empty($cfg['pk_manual'])): ?>
    <label for="id"><?= e($cfg['pk_label'] ?? 'ID') ?></label>
    <input type="text" id="id" name="id" maxlength="20" required>
  <?php endif ?>

  <?php foreach ($cfg['fields'] as $name => $f): ?>
    <?php
    $required = empty($f['optional']);
    $value    = $row[$name] ?? '';
    $options  = [];
    if ($f['type'] === 'select') {
      $options = $f['options'] ?? $conn->query($f['options_sql'])->fetchAll(PDO::FETCH_KEY_PAIR);
    }
    ?>
    <label for="<?= e($name) ?>"><?= e($f['label']) ?></label>

    <?php if ($f['type'] === 'textarea'): ?>
      <textarea id="<?= e($name) ?>" name="<?= e($name) ?>" rows="3" <?= $required ? 'required' : '' ?>><?= e($value) ?></textarea>

    <?php elseif ($f['type'] === 'select'): ?>
      <select id="<?= e($name) ?>" name="<?= e($name) ?>" <?= $required ? 'required' : '' ?> <?= ($lockLevel && $name === 'level') ? 'disabled' : '' ?>>
        <?php foreach ($options as $k => $optLabel): ?>
          <option value="<?= e($k) ?>" <?= (string) $value === (string) $k ? 'selected' : '' ?>><?= e($optLabel) ?></option>
        <?php endforeach ?>
      </select>
      <?php if ($lockLevel && $name === 'level'): ?><small>Level akun yang sedang login tidak dapat diubah.</small><?php endif ?>

    <?php elseif ($f['type'] === 'password'): ?>
      <input type="password" id="<?= e($name) ?>" name="<?= e($name) ?>" autocomplete="new-password" <?= $isEdit ? '' : 'required' ?>>
      <?php if ($isEdit): ?><small>Kosongkan jika tidak ingin mengganti password.</small><?php endif ?>

    <?php else: ?>
      <input type="<?= $f['type'] === 'number' ? 'number' : 'text' ?>" id="<?= e($name) ?>" name="<?= e($name) ?>"
        value="<?= e($value) ?>" <?= isset($f['max']) ? 'maxlength="' . (int) $f['max'] . '"' : '' ?>
        <?= $f['type'] === 'number' ? 'min="0"' : '' ?> <?= $required ? 'required' : '' ?>>
    <?php endif ?>
  <?php endforeach ?>

  <div class="aksi-form">
    <button type="submit" class="btn">Simpan</button>
    <a class="btn btn-abu" href="<?= e(url("/$entity")) ?>">Batal</a>
  </div>
</form>

<?php require_once __DIR__ . "/../partials/footer.auth.php" ?>
