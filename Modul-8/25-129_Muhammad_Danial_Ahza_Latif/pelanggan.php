<?php
require_once 'koneksi.php';
require_once 'auth.php';

cekAkses([1]);

$page_title = "Data Pelanggan";
$active_page = "pelanggan";
$body_class = "bg-[#F8F9FA]";

$error = '';
$edit_data = null;

// Proses CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';

    try {
        if ($aksi === 'simpan') {
            $nama = trim($_POST['nama'] ?? '');
            $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
            $telp = trim($_POST['telp'] ?? '');
            $alamat = trim($_POST['alamat'] ?? '');

            if (
                $nama === '' ||
                !in_array($jenis_kelamin, ['L', 'P'], true) ||
                $telp === '' ||
                $alamat === ''
            ) {
                throw new Exception("Data pelanggan tidak valid.");
            }

            $stmt = $DBH->prepare(
                "INSERT INTO pelanggan
                (nama, jenis_kelamin, telp, alamat)
                VALUES (?, ?, ?, ?)"
            );

            $stmt->execute([$nama, $jenis_kelamin, $telp, $alamat]);

            header("Location: pelanggan.php");
            exit;
        }

        if ($aksi === 'update') {
            $id = (int) $_POST['id'];
            $nama = trim($_POST['nama'] ?? '');
            $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
            $telp = trim($_POST['telp'] ?? '');
            $alamat = trim($_POST['alamat'] ?? '');

            if (
                $id <= 0 ||
                $nama === '' ||
                !in_array($jenis_kelamin, ['L', 'P'], true) ||
                $telp === '' ||
                $alamat === ''
            ) {
                throw new Exception("Data pelanggan tidak valid.");
            }

            $stmt = $DBH->prepare(
                "UPDATE pelanggan
                 SET nama = ?, jenis_kelamin = ?, telp = ?, alamat = ?
                 WHERE id = ?"
            );

            $stmt->execute([
                $nama, $jenis_kelamin, $telp, $alamat, $id
            ]);

            header("Location: pelanggan.php");
            exit;
        }

        if ($aksi === 'hapus') {
            $id = (int) $_POST['id'];

            $stmt = $DBH->prepare("DELETE FROM pelanggan WHERE id = ?");
            $stmt->execute([$id]);

            header("Location: pelanggan.php");
            exit;
        }
    } catch (Throwable $e) {
        $error = "Gagal memproses data pelanggan: " . $e->getMessage();
    }
}

if (isset($_GET['edit']) && ctype_digit($_GET['edit'])) {
    $stmt = $DBH->prepare("SELECT * FROM pelanggan WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

$pelanggan = $DBH->query(
    "SELECT * FROM pelanggan ORDER BY id DESC"
)->fetchAll(PDO::FETCH_ASSOC);

include 'components/header.php';
include 'components/navbar.php';
?>

<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Data Pelanggan</h1>
    </div>

    <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-5">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Form Pelanggan -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-8">
        <h2 class="text-lg font-semibold mb-5">
            <?= $edit_data ? 'Edit Pelanggan' : 'Tambah Pelanggan' ?>
        </h2>

        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <input type="hidden" name="aksi"
                value="<?= $edit_data ? 'update' : 'simpan' ?>">

            <?php if ($edit_data): ?>
                <input type="hidden" name="id"
                    value="<?= (int) $edit_data['id'] ?>">
            <?php endif; ?>

            <div>
                <label class="block mb-2 text-sm font-medium">Nama Pelanggan</label>
                <input type="text" name="nama" required
                    value="<?= htmlspecialchars($edit_data['nama'] ?? '') ?>"
                    class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">Jenis Kelamin</label>
                <select name="jenis_kelamin" required
                    class="w-full border rounded-lg p-3">
                    <option value="">Pilih jenis kelamin</option>
                    <option value="L"
                        <?= ($edit_data['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>
                        Laki-laki
                    </option>
                    <option value="P"
                        <?= ($edit_data['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>
                        Perempuan
                    </option>
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">Nomor Telepon</label>
                <input type="text" name="telp" maxlength="12" required
                    value="<?= htmlspecialchars($edit_data['telp'] ?? '') ?>"
                    class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">Alamat</label>
                <textarea name="alamat" rows="3" required
                    class="w-full border rounded-lg p-3"><?= htmlspecialchars($edit_data['alamat'] ?? '') ?></textarea>
            </div>

            <div class="flex gap-2 md:col-span-2">
                <button type="submit"
                    class="bg-black hover:bg-gray-800 text-white px-5 py-3 rounded-lg">
                    <?= $edit_data ? 'Simpan Perubahan' : 'Tambah Pelanggan' ?>
                </button>

                <?php if ($edit_data): ?>
                    <a href="pelanggan.php"
                        class="bg-gray-200 px-5 py-3 rounded-lg">
                        Batal
                    </a>
                <?php endif; ?>
            </div>

        </form>
    </div>

    <!-- Tabel Pelanggan -->
    <div class="bg-white rounded-xl shadow-sm border overflow-x-auto">
        <div class="p-6 border-b">
            <h2 class="font-semibold text-lg">Daftar Pelanggan</h2>
        </div>

        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-4">No</th>
                    <th class="p-4">Nama</th>
                    <th class="p-4">Jenis Kelamin</th>
                    <th class="p-4">Telepon</th>
                    <th class="p-4">Alamat</th>
                    <th class="p-4">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!$pelanggan): ?>
                    <tr>
                        <td colspan="6" class="p-5 text-center text-gray-500">
                            Belum ada data pelanggan.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($pelanggan as $i => $p): ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-4"><?= $i + 1 ?></td>
                        <td class="p-4"><?= htmlspecialchars($p['nama']) ?></td>
                        <td class="p-4">
                            <?= $p['jenis_kelamin'] === 'L'
                                ? 'Laki-laki' : 'Perempuan' ?>
                        </td>
                        <td class="p-4"><?= htmlspecialchars($p['telp']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($p['alamat']) ?></td>
                        <td class="p-4">
                            <div class="flex gap-2">
                                <a href="pelanggan.php?edit=<?= $p['id'] ?>"
                                    class="text-blue-600 hover:underline">
                                    Edit
                                </a>

                                <form method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
                                    <input type="hidden" name="aksi" value="hapus">
                                    <input type="hidden" name="id"
                                        value="<?= $p['id'] ?>">

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
