<?php
require_once 'koneksi.php';
require_once 'auth.php';

cekAkses([1]);

$page_title = "Data User";
$active_page = "user";
$body_class = "bg-[#F8F9FA]";

$error = '';
$edit_data = null;

// Proses CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';

    try {
        if ($aksi === 'simpan') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $nama = trim($_POST['nama'] ?? '');
            $alamat = trim($_POST['alamat'] ?? '');
            $hp = trim($_POST['hp'] ?? '');
            $level = (int) ($_POST['level'] ?? 0);

            if (
                $username === '' ||
                $password === '' ||
                $nama === '' ||
                $alamat === '' ||
                $hp === '' ||
                !in_array($level, [1, 2, 3, 4], true)
            ) {
                throw new Exception("Semua data pengguna wajib diisi.");
            }

            $stmt = $DBH->prepare(
                "INSERT INTO user_data
                (username, password, nama, alamat, hp, level)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            $stmt->execute([
                $username, $password, $nama, $alamat, $hp, $level
            ]);

            header("Location: user.php");
            exit;
        }

        if ($aksi === 'update') {
            $id = (int) $_POST['id'];
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $nama = trim($_POST['nama'] ?? '');
            $alamat = trim($_POST['alamat'] ?? '');
            $hp = trim($_POST['hp'] ?? '');
            $level = (int) ($_POST['level'] ?? 0);

            if (
                $id <= 0 ||
                $username === '' ||
                $nama === '' ||
                $alamat === '' ||
                $hp === '' ||
                !in_array($level, [1, 2, 3, 4], true)
            ) {
                throw new Exception("Data pengguna tidak valid.");
            }

            if ($password !== '') {
                $stmt = $DBH->prepare(
                    "UPDATE user_data
                     SET username = ?, password = ?, nama = ?,
                         alamat = ?, hp = ?, level = ?
                     WHERE id_user = ?"
                );

                $stmt->execute([
                    $username, $password, $nama,
                    $alamat, $hp, $level, $id
                ]);
            } else {
                $stmt = $DBH->prepare(
                    "UPDATE user_data
                     SET username = ?, nama = ?, alamat = ?,
                         hp = ?, level = ?
                     WHERE id_user = ?"
                );

                $stmt->execute([
                    $username, $nama, $alamat, $hp, $level, $id
                ]);
            }

            if ($id === (int) $_SESSION['id_user']) {
                $_SESSION['user_login'] = $nama;
                $_SESSION['username'] = $username;
                $_SESSION['level'] = $level;
            }

            header("Location: user.php");
            exit;
        }

        if ($aksi === 'hapus') {
            $id = (int) $_POST['id'];

            if ($id === (int) $_SESSION['id_user']) {
                throw new Exception("Akun yang sedang digunakan tidak dapat dihapus.");
            }

            $stmt = $DBH->prepare(
                "DELETE FROM user_data WHERE id_user = ?"
            );

            $stmt->execute([$id]);

            header("Location: user.php");
            exit;
        }
    } catch (Throwable $e) {
        $error = "Gagal memproses data pengguna: " . $e->getMessage();
    }
}

if (isset($_GET['edit']) && ctype_digit($_GET['edit'])) {
    $stmt = $DBH->prepare(
        "SELECT * FROM user_data WHERE id_user = ?"
    );

    $stmt->execute([$_GET['edit']]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

$users = $DBH->query(
    "SELECT id_user, username, nama, alamat, hp, level
     FROM user_data
     ORDER BY id_user DESC"
)->fetchAll(PDO::FETCH_ASSOC);

include 'components/header.php';
include 'components/navbar.php';
?>

<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Data User</h1>
    </div>

    <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-5">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Form User -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-8">
        <h2 class="text-lg font-semibold mb-5">
            <?= $edit_data ? 'Edit Pengguna' : 'Tambah Pengguna' ?>
        </h2>

        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <input type="hidden" name="aksi"
                value="<?= $edit_data ? 'update' : 'simpan' ?>">

            <?php if ($edit_data): ?>
                <input type="hidden" name="id"
                    value="<?= (int) $edit_data['id_user'] ?>">
            <?php endif; ?>

            <div>
                <label class="block mb-2 text-sm font-medium">Username</label>
                <input type="text" name="username" required
                    value="<?= htmlspecialchars($edit_data['username'] ?? '') ?>"
                    class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">
                    Password
                    <?php if ($edit_data): ?>
                        <span class="text-gray-500 font-normal">
                            (Kosongkan jika tidak diubah)
                        </span>
                    <?php endif; ?>
                </label>

                <input type="password" name="password"
                    <?= $edit_data ? '' : 'required' ?>
                    class="w-full border rounded-lg p-3"
                    placeholder="Masukkan password">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">Nama Lengkap</label>
                <input type="text" name="nama" required
                    value="<?= htmlspecialchars($edit_data['nama'] ?? '') ?>"
                    class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">Nomor HP</label>
                <input type="text" name="hp" required
                    value="<?= htmlspecialchars($edit_data['hp'] ?? '') ?>"
                    class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">Alamat</label>
                <textarea name="alamat" rows="3" required
                    class="w-full border rounded-lg p-3"><?= htmlspecialchars($edit_data['alamat'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">Level Pengguna</label>
                <select name="level" required
                    class="w-full border rounded-lg p-3">

                    <option value="">Pilih level</option>

                    <?php
                    $level_terpilih = (int) ($edit_data['level'] ?? 0);
                    ?>

                    <option value="1" <?= $level_terpilih === 1 ? 'selected' : '' ?>>
                        1 - Owner
                    </option>

                    <option value="2" <?= $level_terpilih === 2 ? 'selected' : '' ?>>
                        2 - Kasir
                    </option>

                    <option value="3" <?= $level_terpilih === 3 ? 'selected' : '' ?>>
                        3 - Staff
                    </option>

                    <option value="4" <?= $level_terpilih === 4 ? 'selected' : '' ?>>
                        4 - Manager
                    </option>
                </select>
            </div>

            <div class="flex gap-2 md:col-span-2">
                <button type="submit"
                    class="bg-black hover:bg-gray-800 text-white px-5 py-3 rounded-lg">
                    <?= $edit_data ? 'Simpan Perubahan' : 'Tambah Pengguna' ?>
                </button>

                <?php if ($edit_data): ?>
                    <a href="user.php"
                        class="bg-gray-200 px-5 py-3 rounded-lg">
                        Batal
                    </a>
                <?php endif; ?>
            </div>

        </form>
    </div>

    <!-- Tabel User -->
    <div class="bg-white rounded-xl shadow-sm border overflow-x-auto">
        <div class="p-6 border-b">
            <h2 class="font-semibold text-lg">Daftar Pengguna</h2>
        </div>

        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-4">No</th>
                    <th class="p-4">Username</th>
                    <th class="p-4">Nama</th>
                    <th class="p-4">Alamat</th>
                    <th class="p-4">Nomor HP</th>
                    <th class="p-4">Level</th>
                    <th class="p-4">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!$users): ?>
                    <tr>
                        <td colspan="7" class="p-5 text-center text-gray-500">
                            Belum ada data pengguna.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($users as $i => $u): ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-4"><?= $i + 1 ?></td>
                        <td class="p-4"><?= htmlspecialchars($u['username']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($u['nama']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($u['alamat']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($u['hp']) ?></td>
                        <td class="p-4"><?= (int) $u['level'] ?></td>
                        <td class="p-4">
                            <div class="flex gap-2">
                                <a href="user.php?edit=<?= $u['id_user'] ?>"
                                    class="text-blue-600 hover:underline">
                                    Edit
                                </a>

                                <?php if ((int) $u['id_user'] !== (int) $_SESSION['id_user']): ?>
                                    <form method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                        <input type="hidden" name="aksi" value="hapus">
                                        <input type="hidden" name="id"
                                            value="<?= $u['id_user'] ?>">

                                        <button type="submit"
                                            class="text-red-600 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'components/footer.php'; ?>
