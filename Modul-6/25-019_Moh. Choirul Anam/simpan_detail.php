<?php
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: transaksi.php');
    exit;
}

$transaksiId = (int)($_POST['transaksi_id'] ?? 0);
$barangId    = (int)($_POST['barang_id'] ?? 0);
$qty         = (int)($_POST['qty'] ?? 0);
$kembali     = 'transaksi_detail.php?id=' . $transaksiId;

if ($transaksiId <= 0 || $barangId <= 0 || $qty <= 0) {
    flash('error', 'Barang wajib dipilih dan qty minimal 1.');
    header('Location: ' . $kembali);
    exit;
}

try {
    $db->begin_transaction();

    // 1. Kunci baris transaksi agar perhitungan total tidak bentrok
    $stmt = $db->prepare("SELECT id FROM transaksi WHERE id = ? FOR UPDATE");
    $stmt->bind_param('i', $transaksiId);
    $stmt->execute();
    if (!$stmt->get_result()->fetch_assoc()) {
        throw new DomainException('Transaksi tidak ditemukan.');
    }

    // 2. Ambil harga barang saat ini
    $stmt = $db->prepare("SELECT harga FROM barang WHERE id = ?");
    $stmt->bind_param('i', $barangId);
    $stmt->execute();
    $barang = $stmt->get_result()->fetch_assoc();
    if (!$barang) {
        throw new DomainException('Barang tidak ditemukan.');
    }
    $harga = (int)$barang['harga'];

    // 3. Tolak jika barang yang sama sudah ada di transaksi ini
    $stmt = $db->prepare("SELECT COUNT(*) AS jml FROM transaksi_detail
                          WHERE transaksi_id = ? AND barang_id = ?");
    $stmt->bind_param('ii', $transaksiId, $barangId);
    $stmt->execute();
    if ((int)$stmt->get_result()->fetch_assoc()['jml'] > 0) {
        throw new DomainException('Barang tersebut sudah ada di transaksi ini.');
    }

    // 4. Simpan detail transaksi
    $stmt = $db->prepare("INSERT INTO transaksi_detail
                          (transaksi_id, barang_id, harga, qty)
                          VALUES (?, ?, ?, ?)");
    $stmt->bind_param('iiii', $transaksiId, $barangId, $harga, $qty);
    $stmt->execute();

    // 5. Hitung ulang total = SUM(harga * qty) seluruh detail transaksi ini,
    //    lalu simpan ke kolom total pada tabel transaksi
    $stmt = $db->prepare("UPDATE transaksi
        SET total = (SELECT COALESCE(SUM(harga * qty), 0)
                     FROM transaksi_detail WHERE transaksi_id = ?)
        WHERE id = ?");
    $stmt->bind_param('ii', $transaksiId, $transaksiId);
    $stmt->execute();

    $db->commit();
    flash('success', 'Detail transaksi disimpan dan total transaksi diperbarui.');
} catch (DomainException $e) {
    $db->rollback();
    flash('error', $e->getMessage());
} catch (mysqli_sql_exception $e) {
    $db->rollback();
    flash('error', 'Gagal menyimpan detail transaksi.');
}

header('Location: ' . $kembali);
exit;
