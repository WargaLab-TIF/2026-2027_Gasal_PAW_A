<?php
require_once dirname(__DIR__, 2) . '/auth.php';
require_once dirname(__DIR__, 2) . '/conn.php';
wajib_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . page_url('transaksi'));
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
if ($id < 1) {
    header('Location: ' . page_url('transaksi', ['msg' => 'delete-error']));
    exit;
}

mysqli_begin_transaction($koneksi);

try {
    $lock = mysqli_prepare($koneksi, 'SELECT id FROM transaksi WHERE id = ? FOR UPDATE');
    mysqli_stmt_bind_param($lock, 'i', $id);
    mysqli_stmt_execute($lock);
    $exists = mysqli_fetch_assoc(mysqli_stmt_get_result($lock));
    mysqli_stmt_close($lock);

    if (!$exists) {
        throw new RuntimeException('Transaksi tidak ditemukan.');
    }

    $paymentCheck = mysqli_prepare($koneksi, 'SELECT COUNT(*) AS jumlah FROM pembayaran WHERE transaksi_id = ?');
    mysqli_stmt_bind_param($paymentCheck, 'i', $id);
    mysqli_stmt_execute($paymentCheck);
    $payment = mysqli_fetch_assoc(mysqli_stmt_get_result($paymentCheck));
    mysqli_stmt_close($paymentCheck);

    if ((int) $payment['jumlah'] > 0) {
        mysqli_rollback($koneksi);
        header('Location: ' . page_url('transaksi', ['msg' => 'paid']));
        exit;
    }

    $items = mysqli_prepare($koneksi, 'SELECT barang_id, SUM(qty) AS qty FROM transaksi_detail WHERE transaksi_id = ? GROUP BY barang_id');
    mysqli_stmt_bind_param($items, 'i', $id);
    mysqli_stmt_execute($items);
    $itemRows = mysqli_stmt_get_result($items);

    $restore = mysqli_prepare($koneksi, 'UPDATE barang SET stok = stok + ? WHERE id = ?');
    while ($item = mysqli_fetch_assoc($itemRows)) {
        $qty = (int) $item['qty'];
        $barangId = (int) $item['barang_id'];
        mysqli_stmt_bind_param($restore, 'ii', $qty, $barangId);
        if (!mysqli_stmt_execute($restore)) {
            throw new RuntimeException('Stok barang gagal dikembalikan.');
        }
    }
    mysqli_stmt_close($restore);
    mysqli_stmt_close($items);

    $deleteDetails = mysqli_prepare($koneksi, 'DELETE FROM transaksi_detail WHERE transaksi_id = ?');
    mysqli_stmt_bind_param($deleteDetails, 'i', $id);
    mysqli_stmt_execute($deleteDetails);
    mysqli_stmt_close($deleteDetails);

    $deleteTransaction = mysqli_prepare($koneksi, 'DELETE FROM transaksi WHERE id = ?');
    mysqli_stmt_bind_param($deleteTransaction, 'i', $id);
    mysqli_stmt_execute($deleteTransaction);
    if (mysqli_stmt_affected_rows($deleteTransaction) !== 1) {
        throw new RuntimeException('Transaksi gagal dihapus.');
    }
    mysqli_stmt_close($deleteTransaction);

    mysqli_commit($koneksi);
    header('Location: ' . page_url('transaksi', ['msg' => 'deleted']));
    exit;
} catch (Throwable $error) {
    mysqli_rollback($koneksi);
    header('Location: ' . page_url('transaksi', ['msg' => 'delete-error']));
    exit;
}
