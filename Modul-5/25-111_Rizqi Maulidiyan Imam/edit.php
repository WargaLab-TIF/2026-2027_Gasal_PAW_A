<?php
include 'koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$id = (int) $_GET['id'];

if (isset($_POST['update'])) {
    $nama   = $_POST['nama'];
    $telp   = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $stmt = $koneksi->prepare("UPDATE supplier SET nama = ?, telp = ?, alamat = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nama, $telp, $alamat, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal update: " . $stmt->error;
    }
    $stmt->close();
}

$stmt = $koneksi->prepare("SELECT nama, telp, alamat FROM supplier WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Master Supplier</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px 40px; color: #333; }
        h2 { color: #3a87ad; font-weight: normal; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .baris { display: flex; align-items: flex-start; margin-bottom: 15px; }
        label { width: 120px; text-align: right; padding: 8px 15px 0 0; font-weight: bold; font-size: 14px; }
        input[type="text"], textarea { width: 400px; padding: 8px; border: 1px solid #ccc;
                                       border-radius: 3px; box-sizing: border-box; font-family: inherit; }
        .btn { color: #fff; padding: 7px 14px; border-radius: 3px; border: none;
               text-decoration: none; font-size: 13px; cursor: pointer; }
        .update { background: #5cb85c; }
        .batal  { background: #d9534f; }
        .tombol { margin-left: 135px; }
        .error  { color: #d9534f; margin: 10px 0 10px 135px; }
    </style>
</head>
<body>
    <h2>Edit Data Master Supplier</h2>

    <?php if (isset($error)) { echo "<div class='error'>" . htmlspecialchars($error) . "</div>"; } ?>

    <form method="POST" action="">
        <div class="baris">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" maxlength="100" required
                   value="<?php echo htmlspecialchars($row['nama']); ?>">
        </div>
        <div class="baris">
            <label for="telp">Telp</label>
            <input type="text" id="telp" name="telp" maxlength="12" required
                   value="<?php echo htmlspecialchars($row['telp']); ?>">
        </div>
        <div class="baris">
            <label for="alamat">Alamat</label>
            <textarea id="alamat" name="alamat" rows="3" required><?php echo htmlspecialchars($row['alamat']); ?></textarea>
        </div>
        <div class="tombol">
            <button type="submit" name="update" class="btn update">Update</button>
            <a href="index.php" class="btn batal">Batal</a>
        </div>
    </form>
</body>
</html>