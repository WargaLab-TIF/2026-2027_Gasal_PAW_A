<?php
// ==========================================================
// FILE UTAMA MASTER-DETAIL: index.php
// Tugas Praktikum Modul 6 - Pengelolaan Master Detil Data (PDO)
// ==========================================================

require_once 'koneksi.php';

// 1. Mengambil seluruh data Master Transaksi beserta nama pelanggan
$stmt_transaksi = $DBH->prepare("
    SELECT transaksi.*, pelanggan.nama AS nama_pelanggan
    FROM transaksi
    LEFT JOIN pelanggan ON transaksi.pelanggan_id = pelanggan.id
    ORDER BY transaksi.id ASC
");
$stmt_transaksi->execute();
$data_transaksi = $stmt_transaksi->fetchAll();

// Menentukan ID transaksi yang sedang dipilih untuk ditampilkan detailnya
$transaksi_id = isset($_GET['transaksi_id']) ? $_GET['transaksi_id'] : $data_transaksi[0]['id'];

// 2. Proses Simpan Detail Transaksi (Jika diinput langsung dari form di halaman utama)
if (isset($_POST['simpan_detail'])) {
    $id_trans  = $_POST['transaksi_id'];
    $id_barang = $_POST['barang_id'];
    $qty       = $_POST['qty'];

    // Mengambil harga satuan dari tabel barang
    $stmt_harga = $DBH->prepare("SELECT harga FROM barang WHERE id = '$id_barang'");
    $stmt_harga->execute();
    $brg        = $stmt_harga->fetch();
    $harga      = $brg['harga'];

    // Menyimpan detail baru ke tabel transaksi_detail
    $insert = $DBH->prepare("
        INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty)
        VALUES ('$id_trans', '$id_barang', '$harga', '$qty')
    ");
    $insert->execute();

    // Tugas Nomor 4: Menghitung ulang total (harga * qty) dari seluruh detail milik transaksi yang sama
    $stmt_total = $DBH->prepare("
        SELECT SUM(harga * qty) AS total_baru
        FROM transaksi_detail
        WHERE transaksi_id = '$id_trans'
    ");
    $stmt_total->execute();
    $hasil_total = $stmt_total->fetch();
    $total_baru  = $hasil_total['total_baru'];

    // Meng-update kolom total pada tabel transaksi
    $update = $DBH->prepare("UPDATE transaksi SET total = '$total_baru' WHERE id = '$id_trans'");
    $update->execute();

    header("Location: index.php?transaksi_id=$id_trans");
    exit;
}

// 3. Mengambil data Detail Transaksi sesuai transaksi_id yang dipilih
$stmt_detail = $DBH->prepare("
    SELECT transaksi_detail.*, barang.nama_barang
    FROM transaksi_detail
    INNER JOIN barang ON transaksi_detail.barang_id = barang.id
    WHERE transaksi_detail.transaksi_id = '$transaksi_id'
");
$stmt_detail->execute();
$data_detail = $stmt_detail->fetchAll();

// Menyimpan daftar barang_id yang sudah ada di transaksi ini agar dinonaktifkan (disabled) di dropdown
$barang_terpakai = [];
foreach ($data_detail as $d) {
    $barang_terpakai[] = $d['barang_id'];
}

// 4. Mengambil seluruh data Barang beserta nama supplier
$stmt_barang = $DBH->prepare("
    SELECT barang.*, supplier.nama AS nama_supplier
    FROM barang
    LEFT JOIN supplier ON barang.supplier_id = supplier.id
    ORDER BY barang.id ASC
");
$stmt_barang->execute();
$data_barang = $stmt_barang->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengelolaan Master Detil Data</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            max-width: 1100px;
        }
        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            border-bottom: 2px solid #edf2f7;
            padding-bottom: 10px;
        }
        .grid-2-kolom {
            display: grid;
            grid-template-columns: 1.3fr 1fr;
            gap: 20px;
            align-items: start;
        }
        tr.baris-aktif {
            background-color: #bee3f8;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- ========================================================== -->
    <!-- BAGIAN 1: TABEL MASTER TRANSAKSI                           -->
    <!-- ========================================================== -->
    <div class="section-card">
        <div class="header-bar">
            <h2>Data Master Transaksi</h2>
            <div>
                <a href="tambah_transaksi.php" class="btn btn-hijau">+ Tambah Transaksi</a>
                <a href="tambah_detail.php?transaksi_id=<?= $transaksi_id ?>" class="btn btn-biru">+ Tambah Detail Transaksi</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Transaksi</th>
                    <th>Waktu Transaksi</th>
                    <th>Nama Pelanggan</th>
                    <th>Keterangan</th>
                    <th>Total</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($data_transaksi as $row): ?>
                <tr class="<?= ($row['id'] == $transaksi_id) ? 'baris-aktif' : '' ?>">
                    <td><?= $no++ ?></td>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['waktu_transaksi'] ?></td>
                    <td><?= $row['nama_pelanggan'] ?></td>
                    <td><?= $row['keterangan'] ?></td>
                    <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                    <td>
                        <!-- Tombol untuk memilih Master dan menampilkan Detail-nya -->
                        <a href="index.php?transaksi_id=<?= $row['id'] ?>" class="btn btn-sm btn-biru">Lihat Detail</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- ========================================================== -->
    <!-- BAGIAN 2: TABEL DETAIL TRANSAKSI & FORM INPUT DETAIL       -->
    <!-- ========================================================== -->
    <div class="section-card">
        <div class="header-bar">
            <h2>Data Detail Transaksi (ID Transaksi: #<?= $transaksi_id ?>)</h2>
        </div>

        <div class="grid-2-kolom">
            <!-- Tabel Detail Transaksi -->
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>Transaksi ID</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Sub Total (Harga x Qty)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_detail as $d): ?>
                        <tr>
                            <td><?= $d['transaksi_id'] ?></td>
                            <td><?= $d['nama_barang'] ?></td>
                            <td>Rp <?= number_format($d['harga'], 0, ',', '.') ?></td>
                            <td><?= $d['qty'] ?></td>
                            <td>Rp <?= number_format($d['harga'] * $d['qty'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Form Input Detail Transaksi -->
            <div>
                <h3 style="margin-top:0; color:#2c5282;">Form Input Detail Transaksi</h3>
                <form method="POST" action="">
                    <div class="form-group">
                        <label>ID Transaksi</label>
                        <select name="transaksi_id" onchange="window.location.href='index.php?transaksi_id=' + this.value" required>
                            <?php foreach ($data_transaksi as $t): ?>
                                <option value="<?= $t['id'] ?>" <?= ($t['id'] == $transaksi_id) ? 'selected' : '' ?>>
                                    ID #<?= $t['id'] ?> - <?= $t['keterangan'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Pilih Barang</label>
                        <!-- Tugas Nomor 2: Barang yang sudah ada di transaksi ini otomatis disabled -->
                        <select name="barang_id" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php foreach ($data_barang as $b):
                                $sudah_dipilih = in_array($b['id'], $barang_terpakai);
                            ?>
                                <option value="<?= $b['id'] ?>" <?= $sudah_dipilih ? 'disabled' : '' ?>>
                                    <?= $b['nama_barang'] ?> - Rp <?= number_format($b['harga'], 0, ',', '.') ?>
                                    <?= $sudah_dipilih ? '(Sudah dipilih)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Qty</label>
                        <input type="number" name="qty" min="1" placeholder="Masukkan jumlah barang" required>
                    </div>

                    <button type="submit" name="simpan_detail" class="btn btn-hijau">Simpan Detail</button>
                </form>
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- BAGIAN 3: TABEL BARANG & FITUR HAPUS                       -->
    <!-- ========================================================== -->
    <div class="section-card">
        <div class="header-bar">
            <h2>Data Master Barang</h2>
            <a href="tambah_barang.php" class="btn btn-hijau">+ Tambah Barang</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Nama Supplier</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data_barang as $b): ?>
                <tr>
                    <td><?= $b['id'] ?></td>
                    <td><?= $b['kode_barang'] ?></td>
                    <td><?= $b['nama_barang'] ?></td>
                    <td>Rp <?= number_format($b['harga'], 0, ',', '.') ?></td>
                    <td><?= $b['stok'] ?></td>
                    <td><?= $b['nama_supplier'] ?></td>
                    <td>
                        <!-- Tugas Nomor 3: Tombol hapus barang yang mengecek tabel transaksi_detail -->
                        <a href="hapus_barang.php?id=<?= $b['id'] ?>"
                           class="btn btn-sm btn-merah"
                           onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                            Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
