<?php
require_once 'koneksi.php';
require_once 'auth.php';

cekAkses([1]);

$page_title = "Data Supplier";
$active_page = "supplier";
$body_class = "bg-[#F8F9FA]";

$error = '';
$edit_data = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';

    try {
        if ($aksi === 'simpan') {
            $nama = trim($_POST['nama'] ?? '');
            $telp = trim($_POST['telp'] ?? '');
            $alamat = trim($_POST['alamat'] ?? '');

            if ($nama === '' || $telp === '' || $alamat === '') {
                throw new Exception("Semua kolom wajib diisi.");
            }

            $stmt = $DBH->prepare(
                "INSERT INTO supplier (nama, telp, alamat)
                 VALUES (?, ?, ?)"
            );

            $stmt->execute([$nama, $telp, $alamat]);

            header("Location: supplier.php");
            exit;
        }

        if ($aksi === 'update') {
            $id = (int) $_POST['id'];
            $nama = trim($_POST['nama'] ?? '');
            $telp = trim($_POST['telp'] ?? '');
            $alamat = trim($_POST['alamat'] ?? '');

            if ($id <= 0 || $nama === '' || $telp === '' || $alamat === '') {
                throw new Exception("Data supplier tidak valid.");
            }

            $stmt = $DBH->prepare(
                "UPDATE supplier
                 SET nama = ?, telp = ?, alamat = ?
                 WHERE id = ?"
            );

            $stmt->execute([$nama, $telp, $alamat, $id]);

            header("Location: supplier.php");
            exit;
        }

        if ($aksi === 'hapus') {
            $id = (int) $_POST['id'];

            $stmt = $DBH->prepare("DELETE FROM supplier WHERE id = ?");
            $stmt->execute([$id]);

            header("Location: supplier.php");
            exit;
        }
    } catch (Throwable $e) {
        $error = "Gagal memproses data supplier: " . $e->getMessage();
    }
}

if (isset($_GET['edit']) && ctype_digit($_GET['edit'])) {
    $stmt = $DBH->prepare("SELECT * FROM supplier WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

$supplier = $DBH->query(
    "SELECT * FROM supplier ORDER BY id DESC"
)->fetchAll(PDO::FETCH_ASSOC);

include 'components/header.php';
include 'components/navbar.php';
?>

<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Data Supplier</h1>

    </div>

    <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-5">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Form Supplier -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-8">
        <h2 class="text-lg font-semibold mb-5">
            <?= $edit_data ? 'Edit Supplier' : 'Tambah Supplier' ?>
        </h2>

        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <input type="hidden" name="aksi"
                value="<?= $edit_data ? 'update' : 'simpan' ?>">

            <?php if ($edit_data): ?>
                <input type="hidden" name="id"
                    value="<?= (int) $edit_data['id'] ?>">
            <?php endif; ?>

            <div>
                <label class="block mb-2 text-sm font-medium">Nama Supplier</label>
                <input type="text" name="nama" required
                    value="<?= htmlspecialchars($edit_data['nama'] ?? '') ?>"
                    class="w-full border rounded-lg p-3"
                    placeholder="Masukkan nama supplier">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">Nomor Telepon</label>
                <input type="text" name="telp" required maxlength="12"
                    value="<?= htmlspecialchars($edit_data['telp'] ?? '') ?>"
                    class="w-full border rounded-lg p-3"
                    placeholder="Masukkan nomor telepon">
            </div>

            <div class="md:col-span-2">
                <label class="block mb-2 text-sm font-medium">Alamat</label>
                <textarea name="alamat" rows="3" required
                    class="w-full border rounded-lg p-3"
                    placeholder="Masukkan alamat supplier"><?= htmlspecialchars($edit_data['alamat'] ?? '') ?></textarea>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="bg-black hover:bg-gray-800 text-white px-5 py-3 rounded-lg">
                    <?= $edit_data ? 'Simpan Perubahan' : 'Tambah Supplier' ?>
                </button>

                <?php if ($edit_data): ?>
                    <a href="supplier.php"
                        class="bg-gray-200 px-5 py-3 rounded-lg">
                        Batal
                    </a>
                <?php endif; ?>
            </div>

        </form>
    </div>

    <!-- Tabel Supplier -->
    <div class="bg-white rounded-xl shadow-sm border overflow-x-auto">
        <div class="p-6 border-b">
            <h2 class="font-semibold text-lg">Daftar Supplier</h2>
        </div>

        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-4">No</th>
                    <th class="p-4">Nama Supplier</th>
                    <th class="p-4">Telepon</th>
                    <th class="p-4">Alamat</th>
                    <th class="p-4">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!$supplier): ?>
                    <tr>
                        <td colspan="5" class="p-5 text-center text-gray-500">
                            Belum ada data supplier.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($supplier as $i => $s): ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-4"><?= $i + 1 ?></td>
                        <td class="p-4"><?= htmlspecialchars($s['nama']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($s['telp']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($s['alamat']) ?></td>
                        <td class="p-4">
                            <div class="flex gap-2">
                                <a href="supplier.php?edit=<?= $s['id'] ?>"
                                    class="text-blue-600 hover:underline">
                                    Edit
                                </a>

                                <form method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                                    <input type="hidden" name="aksi" value="hapus">
                                    <input type="hidden" name="id"
                                        value="<?= $s['id'] ?>">

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
