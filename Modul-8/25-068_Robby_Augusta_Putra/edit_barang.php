<?php
include 'cek_login.php';
if ($_SESSION['level'] != 1) { 
    header('Location: index.php');
    exit;
}
include 'koneksi.php';

$supplier = $db->query("SELECT id, nama FROM supplier ORDER BY nama")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id          = (int) $_POST['id'];
  $kode        = trim($_POST['kode_barang']);
  $nama        = trim($_POST['nama_barang']);
  $harga       = trim($_POST['harga']);
  $stok        = trim($_POST['stok']);
  $supplier_id = (int) $_POST['supplier_id'];

  if ($kode === '' || $nama === '' || $harga === '' || $stok === '' || $supplier_id === 0) {
    $error = 'Semua kolom wajib diisi.';
  } elseif (!ctype_digit($harga) || !ctype_digit($stok)) {
    $error = 'Harga dan stok harus berupa angka bulat (tidak negatif).';
  } else {
    $cek = $db->prepare("SELECT COUNT(*) FROM barang WHERE kode_barang = ? AND id <> ?");
    $cek->execute([$kode, $id]);
    if ($cek->fetchColumn() > 0) {
      $error = 'Kode barang sudah dipakai barang lain.';
    } else {
      $stmt = $db->prepare("UPDATE barang SET kode_barang = ?, nama_barang = ?, harga = ?, stok = ?, supplier_id = ? WHERE id = ?");
      $stmt->execute([$kode, $nama, (int) $harga, (int) $stok, $supplier_id, $id]);
      header('Location: barang.php?pesan=diupdate');
      exit;
    }
  }

  // validasi gagal: tampilkan kembali isian terakhir
  $data = [
    'id' => $id, 'kode_barang' => $kode, 'nama_barang' => $nama,
    'harga' => $harga, 'stok' => $stok, 'supplier_id' => $supplier_id,
  ];
} else {
  $id = (int) ($_GET['id'] ?? 0);
  $stmt = $db->prepare("SELECT * FROM barang WHERE id = ?");
  $stmt->execute([$id]);
  $data = $stmt->fetch();

  if (!$data) {
    header('Location: barang.php');
    exit;
  }
}

include 'header.php';
?>
<h2>Edit Data Barang</h2>
<hr>

<?php if (isset($error)): ?><div class="pesan gagal"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="post">
  <input type="hidden" name="id" value="<?= (int) $data['id'] ?>">
  <div class="baris">
    <label for="kode_barang">Kode</label>
    <input type="text" id="kode_barang" name="kode_barang" class="sedang" maxlength="10" required
           value="<?= htmlspecialchars($data['kode_barang']) ?>">
  </div>
  <div class="baris">
    <label for="nama_barang">Nama</label>
    <input type="text" id="nama_barang" name="nama_barang" class="lebar" maxlength="100" required
           value="<?= htmlspecialchars($data['nama_barang']) ?>">
  </div>
  <div class="baris">
    <label for="harga">Harga</label>
    <input type="number" id="harga" name="harga" class="sedang" min="0" required
           value="<?= htmlspecialchars($data['harga']) ?>">
  </div>
  <div class="baris">
    <label for="stok">Stok</label>
    <input type="number" id="stok" name="stok" class="sedang" min="0" required
           value="<?= htmlspecialchars($data['stok']) ?>">
  </div>
  <div class="baris">
    <label for="supplier_id">Supplier</label>
    <select id="supplier_id" name="supplier_id" style="width:55%" required>
      <?php foreach ($supplier as $s): ?>
        <option value="<?= $s['id'] ?>" <?= $data['supplier_id'] == $s['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($s['nama']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="tombol">
    <button type="submit" class="btn btn-hijau">Update</button>
    <a href="barang.php" class="btn btn-merah">Batal</a>
  </div>
</form>
<?php include 'footer.php'; ?>
