<?php
require_once dirname(__DIR__, 2) . '/conn.php';
require_once dirname(__DIR__, 2) . '/auth.php';
wajib_owner();

$config = [
    'barang' => [
        'title' => 'Barang',
        'pk' => 'id',
        'columns' => ['id', 'kode_barang', 'nama_barang', 'harga', 'stok', 'supplier_id'],
        'fields' => [
            'kode_barang' => ['Kode Barang', 'text', true],
            'nama_barang' => ['Nama Barang', 'text', true],
            'harga' => ['Harga', 'number', true],
            'stok' => ['Stok', 'number', true],
            'supplier_id' => ['Supplier', 'supplier', false],
        ],
    ],
    'supplier' => [
        'title' => 'Supplier',
        'pk' => 'id',
        'columns' => ['id', 'nama', 'telp', 'alamat'],
        'fields' => [
            'nama' => ['Nama Supplier', 'text', true],
            'telp' => ['Telepon', 'text', false],
            'alamat' => ['Alamat', 'textarea', false],
        ],
    ],
    'pelanggan' => [
        'title' => 'Pelanggan',
        'pk' => 'id',
        'columns' => ['id', 'nama', 'jenis_kelamin', 'telp', 'alamat'],
        'fields' => [
            'id' => ['ID Pelanggan', 'text', true],
            'nama' => ['Nama Pelanggan', 'text', true],
            'jenis_kelamin' => ['Jenis Kelamin', 'gender', true],
            'telp' => ['Telepon', 'text', false],
            'alamat' => ['Alamat', 'textarea', false],
        ],
    ],
    'user' => [
        'title' => 'User',
        'pk' => 'id_user',
        'columns' => ['id_user', 'username', 'nama', 'alamat', 'hp', 'level'],
        'fields' => [
            'username' => ['Username', 'text', true],
            'password' => ['Password', 'password', true],
            'nama' => ['Nama', 'text', true],
            'alamat' => ['Alamat', 'textarea', false],
            'hp' => ['Nomor HP', 'text', false],
            'level' => ['Level', 'level', true],
        ],
    ],
];

$tabel = $_GET['tabel'] ?? '';
if (!isset($config[$tabel])) {
    http_response_code(404);
    exit('Data master tidak ditemukan.');
}

$meta = $config[$tabel];
$notice = '';
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        http_response_code(403);
        exit('Permintaan tidak valid. Muat ulang halaman.');
    }

    $action = $_POST['action'] ?? '';
    $id = $action === 'save'
        ? ($_POST['record_id'] ?? '')
        : ($_POST['id'] ?? '');

    try {
        if ($action === 'delete') {
            if ($tabel === 'user' && (int) $id === (int) $_SESSION['user']['id_user']) {
                throw new RuntimeException('Akun yang sedang digunakan tidak dapat dihapus.');
            }

            $stmt = mysqli_prepare($koneksi, "DELETE FROM `$tabel` WHERE `{$meta['pk']}` = ?");
            mysqli_stmt_bind_param($stmt, 's', $id);
            mysqli_stmt_execute($stmt);
            if (mysqli_stmt_affected_rows($stmt) < 1) throw new RuntimeException('Data tidak ditemukan.');
            mysqli_stmt_close($stmt);
            $notice = 'Data berhasil dihapus.';
        } elseif ($action === 'save' && $tabel === 'pelanggan') {
            $editing = $id !== '';
            $customerId = trim($_POST['id'] ?? '');
            $nama = trim($_POST['nama'] ?? '');
            $jenisKelamin = $_POST['jenis_kelamin'] ?? '';
            $telp = trim($_POST['telp'] ?? '');
            $alamat = trim($_POST['alamat'] ?? '');

            if ($customerId === '' || $nama === '') {
                throw new RuntimeException('ID dan nama pelanggan wajib diisi.');
            }
            if (!in_array($jenisKelamin, ['L', 'P'], true)) {
                throw new RuntimeException('Pilih jenis kelamin pelanggan.');
            }
            if (strlen($customerId) > 20 || strlen($nama) > 100) {
                throw new RuntimeException('ID pelanggan maksimal 20 karakter dan nama maksimal 100 karakter.');
            }
            if (strlen($telp) > 12) {
                throw new RuntimeException('Nomor telepon maksimal 12 karakter sesuai struktur database.');
            }

            if ($editing) {
                $query = 'UPDATE pelanggan
                          SET id = ?, nama = ?, jenis_kelamin = ?, telp = ?, alamat = ?
                          WHERE id = ?';
                $stmt = mysqli_prepare($koneksi, $query);
                mysqli_stmt_bind_param($stmt, 'ssssss', $customerId, $nama, $jenisKelamin, $telp, $alamat, $id);
            } else {
                $query = 'INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat)
                          VALUES (?, ?, ?, ?, ?)';
                $stmt = mysqli_prepare($koneksi, $query);
                mysqli_stmt_bind_param($stmt, 'sssss', $customerId, $nama, $jenisKelamin, $telp, $alamat);
            }

            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            header('Location: ' . page_url('master', [
                'tabel' => $tabel,
                'msg' => $editing ? 'updated' : 'created',
            ]));
            exit;
        } elseif ($action === 'save') {
            $editing = $id !== '';
            $values = [];
            $fields = [];
            foreach ($meta['fields'] as $name => $field) {
                $value = trim((string) ($_POST[$name] ?? ''));
                if ($name === 'password' && $editing && $value === '') continue;
                if ($field[2] && $value === '' && $name !== 'supplier_id') {
                    throw new RuntimeException($field[0] . ' wajib diisi.');
                }
                if ($name === 'harga' || $name === 'stok') {
                    if (!ctype_digit($value)) {
                        throw new RuntimeException($field[0] . ' harus berupa bilangan bulat positif atau nol.');
                    }
                    $value = (int) $value;
                } elseif ($name === 'level') {
                    if (!in_array($value, ['1', '2'], true)) {
                        throw new RuntimeException('Level user harus 1 (Owner) atau 2 (Kasir).');
                    }
                    $value = (int) $value;
                } elseif ($name === 'jenis_kelamin' && !in_array($value, ['L','P'], true)) {
                    throw new RuntimeException('Jenis kelamin harus dipilih.');
                } elseif ($name === 'supplier_id' && $value === '') {
                    $value = null;
                } elseif ($name === 'password') {
                    $value = password_hash($value, PASSWORD_DEFAULT);
                }
                $fields[] = $name;
                $values[] = $value;
            }
            if ($editing) {
                $sets = array_map(static fn ($field) => "`$field` = ?", $fields);
                $sql = "UPDATE `$tabel` SET " . implode(', ', $sets) . " WHERE `{$meta['pk']}` = ?";
                $values[] = $id;
            } else {
                $sql = "INSERT INTO `$tabel` (`" . implode('`,`', $fields) . "`) VALUES (" . implode(',', array_fill(0, count($fields), '?')) . ")";
            }
            if ($tabel === 'user' && in_array('password', $fields, true)) {
                mysqli_query($koneksi, 'ALTER TABLE `user` MODIFY `password` VARCHAR(255)');
            }
            $stmt = mysqli_prepare($koneksi, $sql);
            $types = str_repeat('s', count($values));
            $bind = [$stmt, $types];
            foreach ($values as &$value) {
                $bind[] = &$value;
            }
            call_user_func_array('mysqli_stmt_bind_param', $bind);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            header('Location: ' . page_url('master', ['tabel'=>$tabel, 'msg'=>$editing ? 'updated' : 'created']));
            exit;
        }
    } catch (Throwable $error) {
        if ($error instanceof mysqli_sql_exception && (int) $error->getCode() === 1062) {
            $notice = $tabel === 'pelanggan'
                ? 'ID pelanggan sudah digunakan. Gunakan ID yang berbeda.'
                : 'Data dengan nilai tersebut sudah tersedia.';
        } elseif ($error instanceof mysqli_sql_exception) {
            $notice = $tabel === 'pelanggan'
                ? 'Data pelanggan gagal disimpan: ' . $error->getMessage()
                : 'Data tidak dapat dihapus atau disimpan karena masih digunakan atau nilainya tidak sesuai.';
        } else {
            $notice = $error->getMessage();
        }
    }
}
if (isset($_GET['msg'])) {
    $notice = $_GET['msg'] === 'updated' ? 'Data berhasil diperbarui.' : 'Data berhasil ditambahkan.';
}

$editRow = null;
if (isset($_GET['edit'])) {
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM `$tabel` WHERE `{$meta['pk']}` = ?");
    $editId = (string) $_GET['edit'];
    mysqli_stmt_bind_param($stmt, 's', $editId);
    mysqli_stmt_execute($stmt);
    $editRow = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    if (!$editRow) {
        $notice = 'Data yang akan diedit tidak ditemukan.';
    }
}

$result = mysqli_query($koneksi, 'SELECT `' . implode('`,`', $meta['columns']) . "` FROM `$tabel` ORDER BY 1");
$supplierResult = $tabel === 'barang'
    ? mysqli_query($koneksi, 'SELECT id, nama FROM supplier ORDER BY nama')
    : null;
$judul = 'Data ' . $meta['title'];
require dirname(__DIR__) . '/partials/header.php';
?>
<section class="page">
  <div class="panel">
    <div class="table-heading">
      <div>
        <div class="eyebrow">Data Master</div>
        <h1 class="container-title"><?= e($judul) ?></h1>
      </div>
      <a class="btn btn-primary" href="<?= e(page_url('master', ['tabel' => $tabel, 'new' => 1])) ?>#form-master">
        + Tambah Data
      </a>
    </div>
    <?php if ($notice): ?>
      <div class="notice"><?= e($notice) ?></div>
    <?php endif; ?>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>No</th>
            <?php foreach ($meta['columns'] as $field): ?>
              <th><?= e($field === 'level' ? 'Hak Akses' : $field) ?></th>
            <?php endforeach; ?>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; ?>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td class="row-number"><?= $no++ ?></td>
              <?php foreach ($meta['columns'] as $field): ?>
                <td>
                  <?php if ($field === 'password'): ?>
                    â€¢â€¢â€¢â€¢â€¢â€¢
                  <?php elseif ($field === 'level'): ?>
                    <?= (int) $row[$field] === 1 ? 'Owner' : 'Kasir' ?>
                  <?php elseif ($tabel === 'barang' && $field === 'supplier_id'): ?>
                    <?php
                    $supplierQuery = mysqli_query(
                        $koneksi,
                        'SELECT nama FROM supplier WHERE id=' . (int) $row[$field]
                    );
                    $supplier = mysqli_fetch_assoc($supplierQuery);
                    echo e($supplier['nama'] ?? '-');
                    ?>
                  <?php else: ?>
                    <?= e($row[$field]) ?>
                  <?php endif; ?>
                </td>
              <?php endforeach; ?>
              <td class="actions">
                <a
                  class="action-edit"
                  href="<?= e(page_url('master', ['tabel' => $tabel, 'edit' => $row[$meta['pk']]])) ?>#form-master"
                >Edit</a>
                <form method="post" onsubmit="return confirm('Hapus data ini?')">
                  <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= e($row[$meta['pk']]) ?>">
                  <button class="action-delete" type="submit">Hapus</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
          <?php if (mysqli_num_rows($result) === 0): ?>
            <tr>
              <td colspan="<?= count($meta['columns']) + 2 ?>" class="empty-state">Belum ada data.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <?php
    $showForm = isset($_GET['edit'])
        || isset($_GET['new'])
        || (($_POST['action'] ?? '') === 'save');
    ?>
    <?php if ($showForm): ?>
      <form id="form-master" class="master-form" method="post">
        <h2><?= $editRow ? 'Edit' : 'Tambah' ?> <?= e($meta['title']) ?></h2>
        <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="record_id" value="<?= e($editRow[$meta['pk']] ?? '') ?>">

        <?php foreach ($meta['fields'] as $name => $field): ?>
          <?php
          $value = $_POST[$name] ?? $editRow[$name] ?? '';
          $required = $field[2] && !($name === 'password' && $editRow);
          ?>
          <label>
            <?= e($field[0]) ?>
            <?php if ($field[1] === 'textarea'): ?>
              <textarea name="<?= e($name) ?>" <?= $required ? 'required' : '' ?>><?= e($value) ?></textarea>
            <?php elseif ($field[1] === 'supplier'): ?>
              <select name="supplier_id">
                <option value="">-- Tanpa Supplier --</option>
                <?php mysqli_data_seek($supplierResult, 0); ?>
                <?php while ($supplier = mysqli_fetch_assoc($supplierResult)): ?>
                  <option
                    value="<?= (int) $supplier['id'] ?>"
                    <?= (string) $value === (string) $supplier['id'] ? 'selected' : '' ?>
                  ><?= e($supplier['nama']) ?></option>
                <?php endwhile; ?>
              </select>
            <?php elseif ($field[1] === 'gender'): ?>
              <select name="jenis_kelamin" required>
                <option value="">-- Pilih --</option>
                <option value="L" <?= $value === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="P" <?= $value === 'P' ? 'selected' : '' ?>>Perempuan</option>
              </select>
            <?php elseif ($field[1] === 'level'): ?>
              <select name="level" required>
                <option value="1" <?= (string) $value === '1' ? 'selected' : '' ?>>1 - Owner</option>
                <option value="2" <?= (string) $value === '2' ? 'selected' : '' ?>>2 - Kasir</option>
              </select>
            <?php else: ?>
              <input
                name="<?= e($name) ?>"
                type="<?= e($field[1]) ?>"
                value="<?= $field[1] === 'password' ? '' : e($value) ?>"
                <?= $required ? 'required' : '' ?>
                <?= $field[1] === 'number' ? 'min="0"' : '' ?>
                <?= $tabel === 'pelanggan' && $name === 'id' ? 'maxlength="20"' : '' ?>
                <?= $name === 'telp' ? 'maxlength="12"' : '' ?>
                placeholder="<?= $name === 'password' && $editRow ? 'Kosongkan jika tidak diganti' : '' ?>"
              >
            <?php endif; ?>
          </label>
        <?php endforeach; ?>

        <div class="form-actions">
          <button class="btn btn-primary" type="submit">Simpan</button>
          <a class="btn btn-cancel" href="<?= e(page_url('master', ['tabel' => $tabel])) ?>">Batal</a>
        </div>
      </form>
    <?php endif; ?>
  </div>
</section>
<?php require dirname(__DIR__) . '/partials/footer.php'; ?>
