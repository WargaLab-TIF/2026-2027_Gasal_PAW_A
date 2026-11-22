<?php
// Simpan (tambah / ubah) Data Master. Dipanggil lewat /api/{entity}/add dan /api/{entity}/edit
// Variabel dari router: $conn, $entity, $action, $cfg

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
  redirect("/$entity");
}
csrf_check();

$isEdit = ($action === 'edit');
$id     = trim($_POST['id'] ?? '');
$back   = $isEdit ? "/$entity/edit?id=" . urlencode($id) : "/$entity/add";

// Berhenti dengan pesan error, kembali ke form
$fail = function (string $message) use ($back) {
  set_flash('error', $message);
  redirect($back);
};

// Akun yang sedang login tidak boleh mengubah level-nya sendiri (cegah terkunci)
if ($entity === 'users' && $isEdit && (int) $id === $_SESSION['user']['id']) {
  $_POST['level'] = (string) $_SESSION['user']['level'];
}

if ($isEdit && $id === '') {
  $fail('Data tidak valid.');
}
if (!$isEdit && !empty($cfg['pk_manual'])) {   // ID diisi manual (pelanggan)
  if ($id === '' || strlen($id) > 20 || !preg_match('/^[A-Za-z0-9_-]+$/', $id)) {
    $fail('ID wajib diisi (huruf/angka, maksimal 20 karakter).');
  }
}

// Validasi semua field sesuai definisi di config/master.php
$values        = [];
$passwordField = null;
foreach ($cfg['fields'] as $name => $f) {
  if ($f['type'] === 'password') {
    $passwordField = $name;
    continue;
  }
  $v = trim($_POST[$name] ?? '');

  if ($v === '') {
    if (empty($f['optional'])) {
      $fail($f['label'] . ' wajib diisi.');
    }
    $values[$name] = null;
    continue;
  }
  if ($f['type'] === 'number' && (!ctype_digit($v) || strlen($v) > 9)) {
    $fail($f['label'] . ' harus berupa angka bulat (0 atau lebih).');
  }
  if (isset($f['max']) && mb_strlen($v) > $f['max']) {
    $fail($f['label'] . ' maksimal ' . $f['max'] . ' karakter.');
  }
  if (isset($f['options']) && !array_key_exists($v, $f['options'])) {
    $fail($f['label'] . ' tidak valid.');
  }
  $values[$name] = $v;
}

// Password di-hash dengan password_hash(); saat ubah, kosong = tidak diganti
if ($passwordField !== null) {
  $pw = $_POST[$passwordField] ?? '';
  if (!$isEdit && $pw === '') {
    $fail('Password wajib diisi.');
  }
  if ($pw !== '') {
    $values[$passwordField] = password_hash($pw, PASSWORD_DEFAULT);
  }
}

try {
  if ($isEdit) {
    $sets = implode(', ', array_map(fn($c) => "`$c` = ?", array_keys($values)));
    $stmt = $conn->prepare("UPDATE `{$cfg['table']}` SET $sets WHERE `{$cfg['pk']}` = ?");
    $stmt->execute([...array_values($values), $id]);
  } else {
    if (!empty($cfg['pk_manual'])) {
      $values = [$cfg['pk'] => $id] + $values;
    }
    $cols  = implode(', ', array_map(fn($c) => "`$c`", array_keys($values)));
    $marks = implode(', ', array_fill(0, count($values), '?'));
    $stmt  = $conn->prepare("INSERT INTO `{$cfg['table']}` ($cols) VALUES ($marks)");
    $stmt->execute(array_values($values));
  }
} catch (PDOException $e) {
  if ($e->getCode() === '23000') {   // UNIQUE / FOREIGN KEY
    $fail('Data gagal disimpan: ID/kode/username sudah dipakai atau data referensi tidak valid.');
  }
  throw $e;
}

// Jika mengubah akun sendiri, sinkronkan session agar nama di navbar ikut berubah
if ($entity === 'users' && $isEdit && (int) $id === $_SESSION['user']['id']) {
  $_SESSION['user']['username'] = $values['username'];
  $_SESSION['user']['nama']     = $values['nama'];
}

set_flash('success', 'Data berhasil disimpan.');
redirect("/$entity");
