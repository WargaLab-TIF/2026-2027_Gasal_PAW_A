
<?php

require_once __DIR__ . '/../auth.php';

wajibOwner();

// Konfigurasi setiap Data Master
$config = [

    'barang' => [

        'title' => 'Data Barang',

        'table' => 'barang',

        'fields' => [

            'kode_barang' => [
                'label' => 'Kode Barang',
                'type' => 'text',
                'required' => true
            ],

            'nama_barang' => [
                'label' => 'Nama Barang',
                'type' => 'text',
                'required' => true
            ],

            'harga' => [
                'label' => 'Harga',
                'type' => 'number',
                'required' => true
            ],

            'stok' => [
                'label' => 'Stok',
                'type' => 'number',
                'required' => true
            ],

            'supplier_id' => [
                'label' => 'Supplier',
                'type' => 'supplier',
                'required' => false
            ]

        ]

    ],

    'supplier' => [

        'title' => 'Data Supplier',

        'table' => 'supplier',

        'fields' => [

            'nama_supplier' => [
                'label' => 'Nama Supplier',
                'type' => 'text',
                'required' => true
            ],

            'alamat' => [
                'label' => 'Alamat',
                'type' => 'text',
                'required' => false
            ],

            'telepon' => [
                'label' => 'Telepon',
                'type' => 'text',
                'required' => false
            ]

        ]

    ],

    'pelanggan' => [

        'title' => 'Data Pelanggan',

        'table' => 'pelanggan',

        'fields' => [

            'nama_pelanggan' => [
                'label' => 'Nama Pelanggan',
                'type' => 'text',
                'required' => true
            ],

            'alamat' => [
                'label' => 'Alamat',
                'type' => 'text',
                'required' => false
            ],

            'telepon' => [
                'label' => 'Telepon',
                'type' => 'text',
                'required' => false
            ]

        ]

    ],

    'user' => [

        'title' => 'Data User',

        'table' => 'user',

        'fields' => [

            'nama' => [
                'label' => 'Nama',
                'type' => 'text',
                'required' => true
            ],

            'username' => [
                'label' => 'Username',
                'type' => 'text',
                'required' => true
            ],

            'password' => [
                'label' => 'Password',
                'type' => 'password',
                'required' => false
            ],

            'level' => [
                'label' => 'Level',
                'type' => 'level',
                'required' => true
            ]

        ]

    ]

];

// Memastikan jenis Data Master tersedia
if (!isset($jenis, $config[$jenis])) {

    http_response_code(404);

    exit('Jenis Data Master tidak ditemukan.');

}

$cfg = $config[$jenis];

$table = $cfg['table'];

$fields = $cfg['fields'];

$message = '';

$error = '';


// PROSES TAMBAH DAN UBAH DATA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    cekCsrf();

    $action = $_POST['action'] ?? '';

    $id = (int)($_POST['id'] ?? 0);

    if ($action === 'save') {

        $values = [];

        foreach ($fields as $name => $meta) {

            $value = trim((string)($_POST[$name] ?? ''));

            // Password kosong ketika edit berarti tidak diganti
            if ($name === 'password' && $value === '' && $id > 0) {
                continue;
            }

            if ($meta['required'] && $value === '') {

                $error = $meta['label'] . ' wajib diisi.';

                break;

            }

            // Mengubah password menjadi hash
            if ($name === 'password') {

                if ($value !== '') {

                    $value = password_hash(
                        $value,
                        PASSWORD_DEFAULT
                    );

                } elseif ($id === 0) {

                    $error = 'Password wajib diisi untuk user baru.';

                }

            }

            // Validasi level user
            if (
                $name === 'level' &&
                !in_array($value, ['1', '2'], true)
            ) {

                $error = 'Level harus 1 (Owner) atau 2 (Kasir).';

            }

            // Validasi harga dan stok
            if ($name === 'harga' && $value !== '' && (float)$value < 0) {

                $error = 'Harga tidak boleh negatif.';

            }

            if ($name === 'stok' && $value !== '' && (int)$value < 0) {

                $error = 'Stok tidak boleh negatif.';

            }

            $values[$name] = (
                $value === '' && $name === 'supplier_id'
            ) ? null : $value;

        }

        if ($error === '') {

            try {

                if ($id > 0) {

                    if (!$values) {

                        throw new RuntimeException('Tidak ada data yang diubah.');

                    }

                    $sets = [];

                    foreach (array_keys($values) as $field) {

                        $sets[] = "`$field` = ?";

                    }

                    $sql = "UPDATE `$table` SET " .
                        implode(', ', $sets) .
                        " WHERE id = ?";

                    $params = array_values($values);

                    $params[] = $id;

                    $DBH->prepare($sql)->execute($params);

                    $message = 'Data berhasil diperbarui.';

                } else {

                    $columns = array_keys($values);

                    $marks = implode(
                        ',',
                        array_fill(0, count($columns), '?')
                    );

                    $sql = "INSERT INTO `$table` (`" .
                        implode('`,`', $columns) .
                        "`) VALUES ($marks)";

                    $DBH->prepare($sql)->execute(
                        array_values($values)
                    );

                    $message = 'Data berhasil ditambahkan.';

                }

            } catch (Throwable $ex) {

                $error = 'Gagal menyimpan data. Periksa kemungkinan username atau kode barang sudah digunakan.';

            }

        }

    }

    // PROSES HAPUS DATA
    elseif ($action === 'delete') {

        if (
            $jenis === 'user' &&
            $id === (int)$_SESSION['user_id']
        ) {

            $error = 'Akun yang sedang digunakan tidak boleh dihapus.';

        } else {

            try {

                $DBH->prepare(
                    "DELETE FROM `$table` WHERE id = ?"
                )->execute([$id]);

                $message = 'Data berhasil dihapus.';

            } catch (PDOException $ex) {

                $error = 'Data tidak dapat dihapus karena masih digunakan oleh transaksi atau data lain.';

            }

        }

    }

}


// MENGAMBIL DATA YANG AKAN DIUBAH
$edit = null;

if (isset($_GET['edit'])) {

    $stmt = $DBH->prepare(
        "SELECT * FROM `$table` WHERE id = ?"
    );

    $stmt->execute([(int)$_GET['edit']]);

    $edit = $stmt->fetch() ?: null;

}


// MENAMPILKAN DATA
$rows = $DBH->query(
    "SELECT * FROM `$table` ORDER BY id DESC"
)->fetchAll();


// Mengambil daftar supplier untuk Data Barang
$suppliers = $jenis === 'barang'
    ? $DBH->query(
        'SELECT id, nama_supplier FROM supplier ORDER BY nama_supplier'
    )->fetchAll()
    : [];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= e($cfg['title']) ?></title>

    <link rel="stylesheet" href="../assets/style.css">

</head>

<body>

<?php require __DIR__ . '/../partials/nav.php'; ?>

<main class="container">

    <div class="panel">

        <h1><?= e($cfg['title']) ?></h1>

        <p>
            <a href="../index.php">Kembali ke Home</a>
        </p>

        <?php if ($message): ?>

            <div class="alert success">
                <?= e($message) ?>
            </div>

        <?php endif; ?>

        <?php if ($error): ?>

            <div class="alert error">
                <?= e($error) ?>
            </div>

        <?php endif; ?>

        <h2>
            <?= $edit ? 'Ubah Data' : 'Tambah Data' ?>
        </h2>

        <form method="post">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= e(csrfToken()) ?>"
            >

            <input
                type="hidden"
                name="action"
                value="save"
            >

            <input
                type="hidden"
                name="id"
                value="<?= (int)($edit['id'] ?? 0) ?>"
            >

            <?php foreach ($fields as $name => $meta): ?>

                <label><?= e($meta['label']) ?></label>

                <?php if ($meta['type'] === 'supplier'): ?>

                    <select name="<?= e($name) ?>">

                        <option value="">
                            -- Tanpa Supplier --
                        </option>

                        <?php foreach ($suppliers as $s): ?>

                            <option
                                value="<?= (int)$s['id'] ?>"
                                <?= (string)($edit[$name] ?? '') === (string)$s['id'] ? 'selected' : '' ?>
                            >

                                <?= e($s['nama_supplier']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                <?php elseif ($meta['type'] === 'level'): ?>

                    <select name="level" required>

                        <option value="1"
                            <?= (int)($edit['level'] ?? 2) === 1 ? 'selected' : '' ?>>

                            1 - Owner

                        </option>

                        <option value="2"
                            <?= (int)($edit['level'] ?? 2) === 2 ? 'selected' : '' ?>>

                            2 - Kasir

                        </option>

                    </select>

                <?php else: ?>

                    <input
                        type="<?= e($meta['type']) ?>"
                        name="<?= e($name) ?>"
                        value="<?= $meta['type'] === 'password' ? '' : e($edit[$name] ?? '') ?>"
                        <?= $meta['required'] && !($name === 'password' && $edit) ? 'required' : '' ?>
                        <?= $name === 'password' ? 'autocomplete="new-password" placeholder="Kosongkan jika tidak ingin mengganti password"' : '' ?>
                        <?= $meta['type'] === 'number' ? 'min="0" step="any"' : '' ?>
                    >

                <?php endif; ?>

            <?php endforeach; ?>

            <button class="btn primary" type="submit">

                <?= $edit ? 'Simpan Perubahan' : 'Tambah Data' ?>

            </button>

            <?php if ($edit): ?>

                <a
                    class="btn secondary"
                    href="<?= e(basename($_SERVER['PHP_SELF'])) ?>"
                >
                    Batal
                </a>

            <?php endif; ?>

        </form>

    </div>


    <div class="panel">

        <h2>Daftar Data</h2>

        <div class="table-wrap">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <?php foreach ($fields as $name => $meta): ?>

                            <th><?= e($meta['label']) ?></th>

                        <?php endforeach; ?>

                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($rows as $row): ?>

                        <tr>

                            <td><?= (int)$row['id'] ?></td>

                            <?php foreach ($fields as $name => $meta): ?>

                                <td>

                                    <?php

                                    if ($name === 'password') {

                                        echo '••••••••';

                                    } elseif ($name === 'level') {

                                        echo (int)$row[$name] === 1
                                            ? 'Owner'
                                            : 'Kasir';

                                    } elseif ($name === 'supplier_id') {

                                        $s = $DBH->prepare(
                                            'SELECT nama_supplier FROM supplier WHERE id = ?'
                                        );

                                        $s->execute([$row[$name]]);

                                        echo e($s->fetchColumn() ?: '-');

                                    } else {

                                        echo e($row[$name] ?? '');

                                    }

                                    ?>

                                </td>

                            <?php endforeach; ?>

                            <td>

                                <div class="actions">

                                    <a
                                        class="btn secondary"
                                        href="?edit=<?= (int)$row['id'] ?>"
                                    >
                                        Ubah
                                    </a>

                                    <form
                                        method="post"
                                        class="inline"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                    >

                                        <input
                                            type="hidden"
                                            name="csrf_token"
                                            value="<?= e(csrfToken()) ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="action"
                                            value="delete"
                                        >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= (int)$row['id'] ?>"
                                        >

                                        <button
                                            class="btn danger"
                                            type="submit"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    <?php if (!$rows): ?>

                        <tr>

                            <td colspan="<?= count($fields) + 2 ?>">
                                Belum ada data.
                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</main>

</body>
</html>
