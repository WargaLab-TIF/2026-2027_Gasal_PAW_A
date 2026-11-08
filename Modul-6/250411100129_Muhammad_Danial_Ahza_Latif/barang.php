<?php

require_once 'koneksi.php';

$page_title = "Data Barang";
$active_page = "barang";
$body_class = "bg-[#F8F9FA]";
$nav_container = "max-w-5xl";
$show_back_link = true;

// Mengambil seluruh data barang
$query = $DBH->prepare("
    SELECT
        barang.*,
        supplier.nama AS nama_supplier
    FROM barang
    LEFT JOIN supplier ON barang.supplier_id = supplier.id
    ORDER BY barang.id DESC
");

$query->execute();
$barang = $query->fetchAll();

include 'components/header.php';
?>

<div class="bg-white p-6 rounded-xl shadow">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Data Barang</h1>
        </div>

        <a href="tambah-barang.php" class="bg-slate-900 text-white px-5 py-3 rounded-lg font-semibold hover:bg-slate-700">Tambah Barang</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-wider">
                <tr>
                    <th class="py-3 px-4 font-bold">No</th>
                    <th class="py-3 px-4 font-bold">Kode Barang</th>
                    <th class="py-3 px-4 font-bold">Nama Barang</th>
                    <th class="py-3 px-4 font-bold">Harga</th>
                    <th class="py-3 px-4 font-bold">Stok</th>
                    <th class="py-3 px-4 font-bold">Supplier</th>
                    <th class="py-3 px-4 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $no = 1; ?>
                <?php foreach ($barang as $row): ?>
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-4 font-mono font-bold text-slate-400">
                            <?= $no++ ?>
                        </td>
                        <td class="py-4 px-4 font-mono text-slate-600">
                            <?= htmlspecialchars($row['kode_barang']) ?>
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-bold text-slate-900">
                                <?= htmlspecialchars($row['nama_barang']) ?>
                            </span>
                        </td>
                        <td class="py-4 px-4 font-medium text-slate-700">
                            Rp <?= htmlspecialchars($row['harga']) ?>
                        </td>
                        <td class="py-4 px-4 text-slate-600">
                            <?= htmlspecialchars($row['stok']) ?>
                        </td>
                        <td class="py-4 px-4 text-slate-600">
                            <?= htmlspecialchars($row['nama_supplier'] ?? '-') ?>
                        </td>
                        <td class="py-4 px-4 text-right space-x-3">
                            <a href="hapus_barang.php?id=<?= $row['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')" class="text-rose-600 hover:underline font-bold uppercase text-[11px]">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($barang)): ?>
                    <tr>
                        <td colspan="7" class="py-6 text-center text-slate-400">
                            Belum ada data barang.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
