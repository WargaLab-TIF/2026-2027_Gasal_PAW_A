<?php
require_once 'koneksi.php';

$page_title = "Tambah Detail Transaksi";
$active_page = "edit";
$body_class = "bg-[#F8F9FA]";
$nav_container = "max-w-5xl";
$show_back_link = true;

$id = $_GET['id'] ?? '';

if (!ctype_digit((string) $id)) {
    die("ID Transaksi tidak valid.");
}

// Mengambil data transaksi
$query = $DBH->prepare("
    SELECT *
    FROM transaksi
    WHERE id = :id
");
$query->execute([':id' => $id]);

$transaksi = $query->fetch();

if (!$transaksi) {
    die("Data transaksi tidak ditemukan.");
}

// Mengambil barang yang belum ditambahkan
$query = $DBH->prepare("
    SELECT *
    FROM barang
    WHERE id NOT IN (
        SELECT barang_id
        FROM detail_transaksi
        WHERE transaksi_id = :id
    )
    ORDER BY nama_barang ASC
");
$query->execute([':id' => $id]);

$barang = $query->fetchAll();

include 'components/header.php';
?>

<div class="bg-white p-6 rounded-xl shadow">
    <h1 class="text-2xl font-bold mb-5">
        Tambah Detail Transaksi
    </h1>

    <div class="mb-6 p-4 bg-slate-50 rounded-lg">
        <p class="text-gray-500 text-sm">ID Transaksi</p>
        <p class="font-semibold">
            <?= htmlspecialchars($transaksi['id']) ?>
        </p>

        <p class="text-gray-500 text-sm mt-3">Tanggal Transaksi</p>
        <p class="font-semibold">
            <?= htmlspecialchars($transaksi['waktu_transaksi']) ?>
        </p>
    </div>

    <form action="tambah_barang.php" method="POST">

        <input
            type="hidden"
            name="transaksi_id"
            value="<?= htmlspecialchars($id) ?>"
        >

        <div class="mb-5">
            <label class="block font-semibold mb-2">
                Pilih Barang
            </label>

            <select
                name="barang_id"
                required
                class="w-full border border-slate-300 rounded-lg p-3"
            >
                <option value="">-- Pilih Barang --</option>

                <?php foreach ($barang as $row): ?>
                    <option value="<?= htmlspecialchars($row['id']) ?>">
                        <?= htmlspecialchars($row['kode_barang']) ?>
                        -
                        <?= htmlspecialchars($row['nama_barang']) ?>
                        (Rp <?= number_format($row['harga'], 0, ',', '.') ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <?php if (empty($barang)): ?>
                <p class="text-sm text-rose-600 mt-2">
                    Semua barang sudah ditambahkan ke transaksi ini.
                </p>
            <?php endif; ?>
        </div>

        <div class="mb-6">
            <label class="block font-semibold mb-2">
                Jumlah Barang (Qty)
            </label>

            <input
                type="number"
                name="qty"
                min="1"
                required
                class="w-full border border-slate-300 rounded-lg p-3"
                placeholder="Masukkan jumlah barang"
            >
        </div>

        <div class="flex gap-3">
            <button
                type="submit"
                <?= empty($barang) ? 'disabled' : '' ?>
                class="bg-slate-900 text-white px-5 py-3 rounded-lg font-semibold hover:bg-slate-700 disabled:opacity-50"
            >
                Simpan Detail
            </button>

            <a
                href="detail_transaksi.php?id=<?= $transaksi['id'] ?>"
                class="bg-slate-200 text-slate-700 px-5 py-3 rounded-lg font-semibold hover:bg-slate-300"
            >
                Batal
            </a>
        </div>
    </form>
</div>
```