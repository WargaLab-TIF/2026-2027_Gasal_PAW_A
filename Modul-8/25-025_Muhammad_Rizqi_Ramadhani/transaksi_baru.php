<?php
require_once __DIR__ . "/koneksi.php";
require_once __DIR__ . "/auth.php";
requireLevel([1, 2]);

$pesan = "";
$barangList = $conn->query("SELECT id, kode_barang, nama_barang, harga, stok FROM barang ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
$pelangganList = $conn->query("SELECT id, nama FROM pelanggan ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

$selectedPelanggan = trim($_POST["pelanggan_id"] ?? ($_GET["pelanggan_id"] ?? ""));
$sudahDibeli = [];

if ($selectedPelanggan !== "") {
    $stmtBeli = $conn->prepare(
        "SELECT DISTINCT td.barang_id
         FROM transaksi_detail td
         JOIN transaksi t ON td.transaksi_id = t.id
         WHERE t.pelanggan_id = ?"
    );
    $stmtBeli->execute([$selectedPelanggan]);
    $sudahDibeli = array_map("intval", $stmtBeli->fetchAll(PDO::FETCH_COLUMN, 0));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pelangganId = trim($_POST["pelanggan_id"] ?? "");
    $barangId    = (int) ($_POST["barang_id"] ?? 0);
    $qty         = (int) ($_POST["qty"] ?? 0);
    $tanggal     = $_POST["waktu_transaksi"] ?: date("Y-m-d");
    $keterangan  = trim($_POST["keterangan"] ?? "");

    $stmt = $conn->prepare("SELECT harga, stok FROM barang WHERE id = ?");
    $stmt->execute([$barangId]);
    $barang = $stmt->fetch(PDO::FETCH_ASSOC);

    $cek = $conn->prepare(
        "SELECT COUNT(*)
         FROM transaksi_detail td
         JOIN transaksi t ON td.transaksi_id = t.id
         WHERE t.pelanggan_id = ? AND td.barang_id = ?"
    );
    $cek->execute([$pelangganId, $barangId]);

    if ($pelangganId === "" || !$barang || $qty <= 0) {
        $pesan = "Lengkapi data pelanggan, barang, dan jumlah.";
    } elseif ($cek->fetchColumn() > 0) {
        $pesan = "Pelanggan ini sudah pernah membeli barang tersebut.";
    } elseif ($qty > (int) $barang["stok"]) {
        $pesan = "Stok tidak cukup.";
    } else {
        $total = (int) $barang["harga"] * $qty;

        $stmt = $conn->prepare("INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$tanggal, $keterangan ?: null, $total, $pelangganId]);

        $idTransaksi = (int) $conn->lastInsertId();
        $stmt = $conn->prepare("INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES (?, ?, ?, ?)");
        $stmt->execute([$idTransaksi, $barangId, (int) $barang["harga"], $qty]);

        $stmt = $conn->prepare("UPDATE barang SET stok = stok - ? WHERE id = ?");
        $stmt->execute([$qty, $barangId]);

        header("Location: transaksi.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .btn { padding: 6px 10px; color: white; text-decoration: none; border: none; border-radius: 4px; display: inline-block; font-size: 14px; cursor: pointer; }
        .btn-success { background: #28a745; }
        .btn-secondary { background: #6c757d; margin-bottom: 15px; }
        .form-container { background: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin-bottom: 20px; max-width: 500px; }
        .form-group { margin-bottom: 10px; }
        label { display: block; margin-bottom: 3px; font-weight: bold; }
        input, select { width: 100%; padding: 6px; box-sizing: border-box; }
        .alert-error { color: #dc3545; background: #f8d7da; border: 1px solid #f5c6cb; padding: 8px 12px; border-radius: 4px; margin-bottom: 15px; max-width: 500px; }
    </style>
</head>
<body>
    <?php require_once __DIR__ . "/navbar.php"; ?>

    <h2>Tambah Transaksi Baru</h2>
    <a href="transaksi.php" class="btn btn-secondary">&laquo; Kembali ke Data Transaksi</a>

    <?php if ($pesan !== ""): ?>
        <div class="alert-error"><?= htmlspecialchars($pesan) ?></div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST">
            <div class="form-group">
                <label>Tanggal:</label>
                <input type="date" name="waktu_transaksi" value="<?= date("Y-m-d") ?>" required>
            </div>

            <div class="form-group">
                <label>Pelanggan:</label>
                <select name="pelanggan_id" onchange="if(this.value){window.location='transaksi.php?pelanggan_id='+encodeURIComponent(this.value);}" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    <?php foreach ($pelangganList as $p): ?>
                        <option value="<?= htmlspecialchars($p["id"]) ?>" <?= $selectedPelanggan === (string) $p["id"] ? "selected" : "" ?>>
                            <?= htmlspecialchars($p["id"] . " - " . $p["nama"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Barang:</label>
                <select name="barang_id" required>
                    <option value="">-- Pilih Barang --</option>
                    <?php foreach ($barangList as $b): ?>
                        <?php $terkunci = in_array((int) $b["id"], $sudahDibeli, true); ?>
                        <option value="<?= (int) $b["id"] ?>" <?= $terkunci ? "disabled" : "" ?>>
                            <?= htmlspecialchars($b["nama_barang"]) ?> (Rp <?= number_format((int) $b["harga"], 0, ",", ".") ?>, Stok: <?= (int) $b["stok"] ?>)<?= $terkunci ? " - Sudah dibeli" : "" ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Jumlah:</label>
                <input type="number" name="qty" min="1" required>
            </div>

            <div class="form-group">
                <label>Keterangan:</label>
                <input type="text" name="keterangan">
            </div>

            <button type="submit" class="btn btn-success">Simpan Transaksi</button>
        </form>
    </div>
</body>
</html>
