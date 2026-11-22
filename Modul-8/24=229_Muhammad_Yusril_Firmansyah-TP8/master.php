<?php
require_once "includes/auth.php";
wajibOwner();
require_once "config/koneksi.php";

$master = [
    'barang' => [
        'judul' => 'Data Barang', 'tabel' => 'barang', 'pk' => 'id_barang',
        'kolom' => ['kode_barang'=>'Kode Barang','nama_barang'=>'Nama Barang','harga'=>'Harga','stok'=>'Stok','id_supplier'=>'Supplier'],
        'tipe' => ['kode_barang'=>'s','nama_barang'=>'s','harga'=>'i','stok'=>'i','id_supplier'=>'i']
    ],
    'supplier' => [
        'judul' => 'Data Supplier', 'tabel' => 'supplier', 'pk' => 'id_supplier',
        'kolom' => ['nama_supplier'=>'Nama Supplier','telp'=>'Telepon','alamat'=>'Alamat'],
        'tipe' => ['nama_supplier'=>'s','telp'=>'s','alamat'=>'s']
    ],
    'pelanggan' => [
        'judul' => 'Data Pelanggan', 'tabel' => 'pelanggan', 'pk' => 'id_pelanggan',
        'kolom' => ['nama_pelanggan'=>'Nama Pelanggan','jenis_kelamin'=>'Jenis Kelamin','telp'=>'Telepon','alamat'=>'Alamat'],
        'tipe' => ['nama_pelanggan'=>'s','jenis_kelamin'=>'s','telp'=>'s','alamat'=>'s']
    ],
    'user' => [
        'judul' => 'Data User', 'tabel' => 'user', 'pk' => 'id_user',
        'kolom' => ['username'=>'Username','password'=>'Password','nama'=>'Nama','alamat'=>'Alamat','hp'=>'Nomor HP','level'=>'Level'],
        'tipe' => ['username'=>'s','password'=>'s','nama'=>'s','alamat'=>'s','hp'=>'s','level'=>'i']
    ]
];

$jenis = $_GET['jenis'] ?? 'barang';
if (!isset($master[$jenis])) { http_response_code(404); exit("Jenis data tidak ditemukan."); }
$m = $master[$jenis];
$pesan = "";
$editData = null;

function ambilSupplier($conn) {
    return $conn->query("SELECT id_supplier, nama_supplier FROM supplier ORDER BY nama_supplier");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    if ($aksi === 'hapus') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $conn->prepare("DELETE FROM `{$m['tabel']}` WHERE `{$m['pk']}`=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) $pesan = "Data berhasil dihapus.";
        else $pesan = "Data gagal dihapus. Pastikan data tidak sedang digunakan transaksi atau relasi lain.";
    } elseif ($aksi === 'simpan') {
        $id = (int)($_POST['id'] ?? 0);
        $values = [];
        foreach ($m['kolom'] as $field => $label) {
            if ($field === 'password') {
                $raw = $_POST[$field] ?? '';
                if ($id > 0 && $raw === '') continue;
                $values[$field] = password_hash($raw, PASSWORD_DEFAULT);
            } else {
                $values[$field] = trim($_POST[$field] ?? '');
            }
        }

        if ($id > 0) {
            $sets = [];
            foreach (array_keys($values) as $field) $sets[] = "`$field`=?";
            $sql = "UPDATE `{$m['tabel']}` SET " . implode(',', $sets) . " WHERE `{$m['pk']}`=?";
            $stmt = $conn->prepare($sql);
            $types = '';
            $params = [];
            foreach ($values as $field => $value) { $types .= $m['tipe'][$field]; $params[] = $value; }
            $types .= 'i'; $params[] = $id;
            $stmt->bind_param($types, ...$params);
        } else {
            $fields = array_keys($values);
            $marks = implode(',', array_fill(0, count($fields), '?'));
            $sql = "INSERT INTO `{$m['tabel']}` (`" . implode('`,`', $fields) . "`) VALUES ($marks)";
            $stmt = $conn->prepare($sql);
            $types = ''; $params = [];
            foreach ($values as $field => $value) { $types .= $m['tipe'][$field]; $params[] = $value; }
            $stmt->bind_param($types, ...$params);
        }
        if ($stmt->execute()) {
            header("Location: master.php?jenis=" . urlencode($jenis) . "&ok=1");
            exit;
        } else {
            $pesan = "Gagal menyimpan data: " . $conn->error;
        }
    }
}

if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM `{$m['tabel']}` WHERE `{$m['pk']}`=?");
    $stmt->bind_param("i", $id); $stmt->execute();
    $editData = $stmt->get_result()->fetch_assoc();
}
if (isset($_GET['ok'])) $pesan = "Data berhasil disimpan.";

$select = $conn->query("SELECT * FROM `{$m['tabel']}` ORDER BY `{$m['pk']}` DESC");
require_once "includes/header.php";
?>
<div class="card">
    <h1><?= htmlspecialchars($m['judul']) ?></h1>
    <p class="muted">Menu Data Master hanya dapat diakses oleh Owner.</p>
    <p>
        <?php foreach ($master as $key=>$item): ?>
            <a class="btn btn-small <?= $key===$jenis?'btn-secondary':'' ?>" href="master.php?jenis=<?= $key ?>"><?= htmlspecialchars($item['judul']) ?></a>
        <?php endforeach; ?>
    </p>
    <?php if ($pesan): ?><div class="alert"><?= htmlspecialchars($pesan) ?></div><?php endif; ?>
    <h2><?= $editData ? 'Edit Data' : 'Tambah Data' ?></h2>
    <form method="POST">
        <input type="hidden" name="aksi" value="simpan">
        <input type="hidden" name="id" value="<?= (int)($editData[$m['pk']] ?? 0) ?>">
        <?php foreach ($m['kolom'] as $field=>$label): ?>
            <label><?= htmlspecialchars($label) ?></label>
            <?php if ($field === 'id_supplier'): ?>
                <select name="id_supplier" required>
                    <option value="">Pilih Supplier</option>
                    <?php $sup=ambilSupplier($conn); while($s=$sup->fetch_assoc()): ?>
                    <option value="<?= $s['id_supplier'] ?>" <?= (isset($editData[$field]) && $editData[$field]==$s['id_supplier'])?'selected':'' ?>><?= htmlspecialchars($s['nama_supplier']) ?></option>
                    <?php endwhile; ?>
                </select>
            <?php elseif ($field === 'jenis_kelamin'): ?>
                <select name="jenis_kelamin" required>
                    <option value="L" <?= (($editData[$field] ?? '')==='L')?'selected':'' ?>>Laki-laki</option>
                    <option value="P" <?= (($editData[$field] ?? '')==='P')?'selected':'' ?>>Perempuan</option>
                </select>
            <?php elseif ($field === 'level'): ?>
                <select name="level" required>
                    <option value="1" <?= ((int)($editData[$field] ?? 0)===1)?'selected':'' ?>>1 - Owner</option>
                    <option value="2" <?= ((int)($editData[$field] ?? 0)===2)?'selected':'' ?>>2 - Kasir</option>
                </select>
            <?php elseif ($field === 'alamat'): ?>
                <textarea name="alamat"><?= htmlspecialchars($editData[$field] ?? '') ?></textarea>
            <?php else: ?>
                <input name="<?= $field ?>" <?= $field==='password'?'type="password"':'type="text"' ?>
                    value="<?= $field==='password'?'':htmlspecialchars($editData[$field] ?? '') ?>"
                    <?= $field==='password' && $editData ? '' : 'required' ?>
                    placeholder="<?= $field==='password' && $editData ? 'Kosongkan jika tidak ingin mengganti password' : '' ?>">
            <?php endif; ?>
        <?php endforeach; ?>
        <button class="btn" type="submit">Simpan</button>
        <?php if ($editData): ?><a class="btn btn-secondary" href="master.php?jenis=<?= $jenis ?>">Batal</a><?php endif; ?>
    </form>
</div>
<div class="card">
    <h2>Daftar <?= htmlspecialchars($m['judul']) ?></h2>
    <table>
        <tr><th>ID</th><?php foreach($m['kolom'] as $label): ?><th><?= htmlspecialchars($label) ?></th><?php endforeach; ?><th>Aksi</th></tr>
        <?php while($row=$select->fetch_assoc()): ?>
        <tr>
            <td><?= (int)$row[$m['pk']] ?></td>
            <?php foreach($m['kolom'] as $field=>$label): ?>
                <td>
                <?php
                if ($field==='password') echo '••••••••';
                elseif ($field==='id_supplier') {
                    $sid=(int)$row[$field];
                    $sr=$conn->query("SELECT nama_supplier FROM supplier WHERE id_supplier=$sid")->fetch_assoc();
                    echo htmlspecialchars($sr['nama_supplier'] ?? '-');
                } elseif ($field==='level') echo (int)$row[$field]===1?'Owner':'Kasir';
                else echo htmlspecialchars((string)$row[$field]);
                ?>
                </td>
            <?php endforeach; ?>
            <td>
                <a class="btn btn-small" href="master.php?jenis=<?= $jenis ?>&edit=<?= (int)$row[$m['pk']] ?>">Edit</a>
                <form method="POST" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    <input type="hidden" name="aksi" value="hapus"><input type="hidden" name="id" value="<?= (int)$row[$m['pk']] ?>">
                    <button class="btn btn-danger btn-small" type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php require_once "includes/footer.php"; ?>
