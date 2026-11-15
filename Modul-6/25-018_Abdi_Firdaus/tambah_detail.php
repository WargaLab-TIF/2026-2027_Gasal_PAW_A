<?php
// ==========================================================
// FILE TAMBAH DETAIL TRANSAKSI: tambah_detail.php
// Tugas Praktikum Nomor 2 & Nomor 4 (PDO):
// - Dropdown barang diambil dari tabel barang, barang yang sudah
//   ada pada transaksi tersebut dinonaktifkan (disabled).
// - Setiap detail baru disimpan, sistem otomatis menghitung ulang
//   total (harga * qty) dan meng-update kolom total di tabel transaksi.
// ==========================================================

require_once 'koneksi.php';

// Mengambil daftar seluruh data transaksi (master)
$stmt_transaksi = $DBH->prepare("SELECT * FROM transaksi");
$stmt_transaksi->execute();
$transaksi = $stmt_transaksi->fetchAll();

// Menentukan ID transaksi yang sedang dipilih
$transaksi_id = isset($_GET['transaksi_id']) ? $_GET['transaksi_id'] : $transaksi[0]['id'];

// Memeriksa apakah tombol Simpan Detail telah ditekan
if (isset($_POST['simpan'])) {
    $transaksi_id = $_POST['transaksi_id'];
    $barang_id    = $_POST['barang_id'];
    $qty          = $_POST['qty'];

    // 1. Mengambil harga satuan barang dari tabel barang (fetch 1 baris)
    $stmt_barang = $DBH->prepare("SELECT harga FROM barang WHERE id = '$barang_id'");
    $stmt_barang->execute();
    $data_barang = $stmt_barang->fetch();
    $harga       = $data_barang['harga'];

    // 2. Menyimpan data baru ke tabel transaksi_detail
    $insert = $DBH->prepare("
        INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty)
        VALUES ('$transaksi_id', '$barang_id', '$harga', '$qty')
    ");
    $insert->execute();

    // 3. Tugas Nomor 4: Menghitung ulang total transaksi dengan menjumlahkan (harga * qty)
    //    dari seluruh detail milik transaksi yang sama
    $stmt_total = $DBH->prepare("
        SELECT SUM(harga * qty) AS total_baru
        FROM transaksi_detail
        WHERE transaksi_id = '$transaksi_id'
    ");
    $stmt_total->execute();
    $data_total = $stmt_total->fetch();
    $total_baru = $data_total['total_baru'];

    // 4. Menyimpan hasil perhitungan ulang ke kolom total pada tabel transaksi
    $update = $DBH->prepare("
        UPDATE transaksi
        SET total = '$total_baru'
        WHERE id = '$transaksi_id'
    ");
    $update->execute();

    header("Location: index.php?transaksi_id=$transaksi_id");
    exit;
}

// Tugas Nomor 2: Mengambil daftar barang_id yang sudah dipakai pada transaksi_id terpilih
$stmt_dipakai = $DBH->prepare("SELECT barang_id FROM transaksi_detail WHERE transaksi_id = '$transaksi_id'");
$stmt_dipakai->execute();
$data_dipakai = $stmt_dipakai->fetchAll();

// Menyimpan barang_id yang sudah terpakai ke dalam array sederhana
$barang_terpakai = [];
foreach ($data_dipakai as $d) {
    $barang_terpakai[] = $d['barang_id'];
}

// Mengambil seluruh data barang untuk ditampilkan pada dropdown
$stmt_all_barang = $DBH->prepare("SELECT * FROM barang");
$stmt_all_barang->execute();
$barang = $stmt_all_barang->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Detail Transaksi</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            max-width: 600px;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Tambah Detail Transaksi</h2>

    <!-- Form input detail transaksi -->
    <form method="POST" action="">
        <!-- Dropdown Pilih ID Transaksi -->
        <div class="form-group">
            <label>ID Transaksi</label>
            <!-- Saat transaksi diganti, halaman dimuat ulang agar daftar barang yang disabled menyesuaikan -->
            <select name="transaksi_id" onchange="window.location.href='tambah_detail.php?transaksi_id=' + this.value" required>
                <?php foreach ($transaksi as $t): ?>
                    <option value="<?= $t['id'] ?>" <?= ($t['id'] == $transaksi_id) ? 'selected' : '' ?>>
                        ID #<?= $t['id'] ?> - <?= $t['keterangan'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Dropdown Pilih Barang -->
        <div class="form-group">
            <label>Pilih Barang</label>
            <select name="barang_id" required>
                <option value="">-- Pilih Barang --</option>
                <?php foreach ($barang as $b):
                    // Mengecek apakah barang sudah ada di dalam array $barang_terpakai
                    $sudah_ada = in_array($b['id'], $barang_terpakai);
                ?>
                    <option value="<?= $b['id'] ?>" <?= $sudah_ada ? 'disabled' : '' ?>>
                        <?= $b['nama_barang'] ?> - Rp <?= number_format($b['harga'], 0, ',', '.') ?>
                        <?= $sudah_ada ? '(Sudah dipilih)' : '' ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Input Jumlah Barang (Qty) -->
        <div class="form-group">
            <label>Qty (Jumlah Beli)</label>
            <input type="number" name="qty" min="1" placeholder="Masukkan jumlah beli" required>
        </div>

        <button type="submit" name="simpan" class="btn btn-biru">Tambah Detail</button>
        <a href="index.php?transaksi_id=<?= $transaksi_id ?>" class="btn btn-merah">Batal</a>
    </form>
</div>

</body>
</html>
