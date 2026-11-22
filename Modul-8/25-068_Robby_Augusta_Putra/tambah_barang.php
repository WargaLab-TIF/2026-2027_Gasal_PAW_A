<?php
include 'cek_login.php';
if ($_SESSION['level'] != 1) { 
    header('Location: index.php');
    exit;
}
include 'koneksi.php';

$supplier = $db->query("SELECT id, nama FROM supplier ORDER BY nama")->fetchAll();

$kode = $nama = $harga = $stok = $supplier_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    $cek = $db->prepare("SELECT COUNT(*) FROM barang WHERE kode_barang = ?");
    $cek->execute([$kode]);
    if ($cek->fetchColumn() > 0) {
      $error = 'Kode barang sudah dipakai.';
    } else {
      $stmt = $db->prepare("INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([$kode, $nama, (int) $harga, (int) $stok, $supplier_id]);
      header('Location: barang.php?pesan=tersimpan');
      exit;
    }
  }
}

include 'header.php';
?>
<h2>Tambah Data Barang Baru</h2>
<hr>

<?php if (isset($error)): ?><div class="pesan gagal"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="post">
  <div class="baris">
    <label for="kode_barang">Kode</label>
    <input type="text" id="kode_barang" name="kode_barang" class="sedang" placeholder="Kode barang" maxlength="10" required
           value="<?= htmlspecialchars($kode) ?>">
  </div>
  <div class="baris">
    <label for="nama_barang">Nama</label>
    <input type="text" id="nama_barang" name="nama_barang" class="lebar" placeholder="Nama barang" maxlength="100" required
           value="<?= htmlspecialchars($nama) ?>">
  </div>
  <div class="baris">
    <label for="harga">Harga</label>
    <input type="number" id="harga" name="harga" class="sedang" placeholder="Harga" min="0" required
           value="<?= htmlspecialchars($harga) ?>">
  </div>
  <div class="baris">
    <label for="stok">Stok</label>
    <input type="number" id="stok" name="stok" class="sedang" placeholder="Stok" min="0" required
           value="<?= htmlspecialchars($stok) ?>">
  </div>
  <div class="baris">
    <label for="supplier_id">Supplier</label>
    <select id="supplier_id" name="supplier_id" style="width:55%" required>
      <option value="">-- Pilih Supplier --</option>
      <?php foreach ($supplier as $s): ?>
        <option value="<?= $s['id'] ?>" <?= $supplier_id == $s['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($s['nama']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="tombol">
    <button type="submit" class="btn btn-hijau">Simpan</button>
    <a href="barang.php" class="btn btn-merah">Batal</a>
  </div>
</form>
<?php include 'footer.php'; ?>
