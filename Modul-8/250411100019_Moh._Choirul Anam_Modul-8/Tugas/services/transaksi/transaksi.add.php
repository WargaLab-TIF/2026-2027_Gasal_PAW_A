<?php
// Simpan transaksi baru. Dipanggil lewat /api/transaksi/add (POST)

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
  redirect("/transaksi");
}
csrf_check();

$back = "/transaksi/add";

$pelanggan_id = trim($_POST['pelanggan_id'] ?? '');
$tanggal      = trim($_POST['waktu_transaksi'] ?? '');
$keterangan   = trim($_POST['keterangan'] ?? '');
$metode       = $_POST['metode'] ?? '';

$d = DateTime::createFromFormat('Y-m-d', $tanggal);
if ($pelanggan_id === '' || !$d || $d->format('Y-m-d') !== $tanggal || !in_array($metode, ['TUNAI', 'TRANSFER', 'EDC'], true)) {
  set_flash('error', 'Pelanggan, tanggal, dan metode pembayaran wajib diisi dengan benar.');
  redirect($back);
}

// Kumpulkan baris barang (baris kosong diabaikan, barang yang sama digabung)
$items   = [];
$barangs = (array) ($_POST['barang_id'] ?? []);
$qtys    = (array) ($_POST['qty'] ?? []);
foreach ($barangs as $i => $barang_id) {
  if ($barang_id === '') {
    continue;
  }
  $qty = (int) ($qtys[$i] ?? 0);
  if ($qty <= 0) {
    set_flash('error', 'Qty setiap barang yang dipilih harus lebih dari 0.');
    redirect($back);
  }
  $items[(int) $barang_id] = ($items[(int) $barang_id] ?? 0) + $qty;
}
if (!$items) {
  set_flash('error', 'Pilih minimal satu barang.');
  redirect($back);
}

// Semua tabel disimpan dalam satu transaksi database: gagal satu -> batal semua
try {
  $conn->beginTransaction();

  $total  = 0;
  $detail = [];
  foreach ($items as $barang_id => $qty) {
    $s = $conn->prepare("SELECT id, nama_barang, harga, stok FROM barang WHERE id = ? FOR UPDATE");
    $s->execute([$barang_id]);
    $b = $s->fetch();
    if (!$b) {
      throw new RuntimeException('Barang tidak ditemukan.');
    }
    if ((int) $b['stok'] < $qty) {
      throw new RuntimeException("Stok {$b['nama_barang']} tidak cukup (tersisa {$b['stok']}).");
    }
    $detail[] = ['id' => $barang_id, 'harga' => (int) $b['harga'], 'qty' => $qty];
    $total   += (int) $b['harga'] * $qty;   // total dihitung di server, bukan dari form
  }

  $conn->prepare("INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES (?, ?, ?, ?)")
       ->execute([$tanggal, $keterangan === '' ? null : $keterangan, $total, $pelanggan_id]);
  $transaksi_id = (int) $conn->lastInsertId();

  $insDetail = $conn->prepare("INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES (?, ?, ?, ?)");
  $updStok   = $conn->prepare("UPDATE barang SET stok = stok - ? WHERE id = ?");
  foreach ($detail as $row) {
    $insDetail->execute([$transaksi_id, $row['id'], $row['harga'], $row['qty']]);
    $updStok->execute([$row['qty'], $row['id']]);
  }

  $conn->prepare("INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id) VALUES (NOW(), ?, ?, ?)")
       ->execute([$total, $metode, $transaksi_id]);

  $conn->commit();
} catch (RuntimeException $e) {
  $conn->rollBack();
  set_flash('error', $e->getMessage());
  redirect($back);
} catch (PDOException $e) {
  if ($conn->inTransaction()) {
    $conn->rollBack();
  }
  if ($e->getCode() === '23000') {
    set_flash('error', 'Pelanggan atau barang tidak valid.');
    redirect($back);
  }
  throw $e;
}

set_flash('success', 'Transaksi berhasil disimpan. Total ' . rupiah($total) . '.');
redirect("/transaksi");
