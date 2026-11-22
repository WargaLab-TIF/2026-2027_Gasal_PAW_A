<?php
require_once 'koneksi.php';
require_once 'auth.php';

cekAkses([1]);

$page_title = "Data Barang";
$active_page = "barang";
$body_class = "bg-[#F8F9FA]";

$error = '';
$edit_data = null;

$supplier = $DBH->query(
    "SELECT id, nama FROM supplier ORDER BY nama ASC"
)->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';

    try {
        if ($aksi === 'simpan') {
            $kode = trim($_POST['kode_barang'] ?? '');
            $nama = trim($_POST['nama_barang'] ?? '');
            $harga = (int) ($_POST['harga'] ?? 0);
            $stok = (int) ($_POST['stok'] ?? 0);
            $supplier_id = $_POST['supplier_id'] !== ''
                ? (int) $_POST['supplier_id']
                : null;

            if ($kode === '' || $nama === '' || $harga < 0 || $stok < 0) {
                throw new Exception("Data barang tidak valid.");
            }

            $stmt = $DBH->prepare(
                "INSERT INTO barang
                (kode_barang, nama_barang, harga, stok, supplier_id)
                VALUES (?, ?, ?, ?, ?)"
            );

            $stmt->execute([
                $kode, $nama, $harga, $stok, $supplier_id
            ]);

            header("Location: barang.php");
            exit;
        }

        if ($aksi === 'update') {
            $id = (int) $_POST['id'];
            $kode = trim($_POST['kode_barang'] ?? '');
            $nama = trim($_POST['nama_barang'] ?? '');
            $harga = (int) ($_POST['harga'] ?? 0);
            $stok = (int) ($_POST['stok'] ?? 0);
            $supplier_id = $_POST['supplier_id'] !== ''
                ? (int) $_POST['supplier_id']
                : null;

            if ($id <= 0 || $kode === '' || $nama === '' ||
                $harga < 0 || $stok < 0) {
                throw new Exception("Data barang tidak valid.");
            }

            $stmt = $DBH->prepare(
                "UPDATE barang
                 SET kode_barang = ?, nama_barang = ?,
                     harga = ?, stok = ?, supplier_id = ?
                 WHERE id = ?"
            );

            $stmt->execute([
                $kode, $nama, $harga, $stok, $supplier_id, $id
            ]);

            header("Location: barang.php");
            exit;
        }

        if ($aksi === 'hapus') {
            $id = (int) $_POST['id'];

            $stmt = $DBH->prepare("DELETE FROM barang WHERE id = ?");
            $stmt->execute([$id]);

            header("Location: barang.php");
            exit;
        }
    } catch (Throwable $e) {
        $error = "Gagal memproses data: " . $e->getMessage();
    }
}

if (isset($_GET['edit']) && ctype_digit($_GET['edit'])) {
    $stmt = $DBH->prepare("SELECT * FROM barang WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

$query = "
    SELECT b.*, s.nama AS nama_supplier
    FROM barang b
    LEFT JOIN supplier s ON b.supplier_id = s.id
    ORDER BY b.id DESC
";

$barang = $DBH->query($query)->fetchAll(PDO::FETCH_ASSOC);

function rupiahBarang($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

include 'components/header.php';
include 'components/navbar.php';
?>

<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Data Barang</h1>
    </div>

    <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-5">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Form Barang -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-8">
        <h2 class="text-lg font-semibold mb-5">
            <?= $edit_data ? 'Edit Barang' : 'Tambah Barang' ?>
        </h2>

        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <input type="hidden" name="aksi"
                value="<?= $edit_data ? 'update' : 'simpan' ?>">

            <?php if ($edit_data): ?>
                <input type="hidden" name="id"
                    value="<?= (int) $edit_data['id'] ?>">
            <?php endif; ?>

            <div>
                <label class="block mb-2 text-sm font-medium">Kode Barang</label>
                <input type="text" name="kode_barang" required
                    value="<?= htmlspecialchars($edit_data['kode_barang'] ?? '') ?>"
                    class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">Nama Barang</label>
                <input type="text" name="nama_barang" required
                    value="<?= htmlspecialchars($edit_data['nama_barang'] ?? '') ?>"
                    class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">Harga</label>
                <input type="number" name="harga" min="0" required
                    value="<?= htmlspecialchars($edit_data['harga'] ?? '') ?>"
                    class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">Stok</label>
                <input type="number" name="stok" min="0" required
                    value="<?= htmlspecialchars($edit_data['stok'] ?? '') ?>"
                    class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">Supplier</label>
                <select name="supplier_id"
                    class="w-full border rounded-lg p-3">
                    <option value="">Tidak ada supplier</option>

                    <?php foreach ($supplier as $s): ?>
                        <option value="<?= $s['id'] ?>"
                            <?= isset($edit_data['supplier_id']) &&
                                $edit_data['supplier_id'] == $s['id']
                                ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="bg-black hover:bg-gray-800 text-white px-5 py-3 rounded-lg">
                    <?= $edit_data ? 'Simpan Perubahan' : 'Tambah Barang' ?>
                </button>

                <?php if ($edit_data): ?>
                    <a href="barang.php"
                        class="bg-gray-200 px-5 py-3 rounded-lg">
                        Batal
                    </a>
                <?php endif; ?>
            </div>

        </form>
    </div>

    <!-- Tabel Barang -->
    <div class="bg-white rounded-xl shadow-sm border overflow-x-auto">
        <div class="p-6 border-b">
            <h2 class="font-semibold text-lg">Daftar Barang</h2>
        </div>

        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-4">No</th>
                    <th class="p-4">Kode</th>
                    <th class="p-4">Nama Barang</th>
                    <th class="p-4">Harga</th>
                    <th class="p-4">Stok</th>
                    <th class="p-4">Supplier</th>
                    <th class="p-4">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!$barang): ?>
                    <tr>
                        <td colspan="7" class="p-5 text-center text-gray-500">
                            Belum ada data barang.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($barang as $i => $b): ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-4"><?= $i + 1 ?></td>
                        <td class="p-4"><?= htmlspecialchars($b['kode_barang']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($b['nama_barang']) ?></td>
                        <td class="p-4"><?= rupiahBarang($b['harga']) ?></td>
                        <td class="p-4"><?= (int) $b['stok'] ?></td>
                        <td class="p-4">
                            <?= htmlspecialchars($b['nama_supplier'] ?? '-') ?>
                        </td>
                        <td class="p-4">
                            <div class="flex gap-2">
                                <a href="barang.php?edit=<?= $b['id'] ?>"
                                    class="text-blue-600 hover:underline">
                                    Edit
                                </a>

                                <form method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                    <input type="hidden" name="aksi" value="hapus">
                                    <input type="hidden" name="id"
                                        value="<?= $b['id'] ?>">

                                    <button type="submit"
                                        class="text-red-600 hover:underline">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'components/footer.php'; ?>
