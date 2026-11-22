<?php
if (!isset($_SESSION['level']) || $_SESSION['level'] != 1) { 
    die("<div class='alert alert-danger'>Akses Ditolak.</div>"); 
}

if (isset($_GET['hapus_id'])) {
    $id = $_GET['hapus_id'];
    // Asumsi nama primary key adalah 'id'. Sesuaikan jika berbeda di database!
    $stmt = $conn->prepare("DELETE FROM barang WHERE id = :id");
    $stmt->execute([':id' => $id]);
    echo "<script>alert('Data Barang berhasil dihapus!'); window.location='index.php?page=barang';</script>";
}

if (isset($_POST['simpan_barang'])) {
    $nama_barang = $_POST['nama_barang'];
    $harga = $_POST['harga']; 
    $stok = $_POST['stok'];
    $id = $_POST['id']; 

    if (empty($id)) {
        $stmt = $conn->prepare("INSERT INTO barang (nama_barang, harga, stok) VALUES (:nama_barang, :harga, :stok)");
        $stmt->execute([':nama_barang' => $nama_barang, ':harga' => $harga, ':stok' => $stok]);
        echo "<script>alert('Barang baru berhasil ditambahkan!'); window.location='index.php?page=barang';</script>";
    } else {
        $stmt = $conn->prepare("UPDATE barang SET nama_barang=:nama_barang, harga=:harga, stok=:stok WHERE id=:id");
        $stmt->execute([':nama_barang' => $nama_barang, ':harga' => $harga, ':stok' => $stok, ':id' => $id]);
        echo "<script>alert('Data Barang berhasil diperbarui!'); window.location='index.php?page=barang';</script>";
    }
}

$edit_barang = null;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM barang WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $edit_barang = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><?= $edit_barang ? 'Edit' : 'Tambah' ?> Data Barang</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=barang">
                    <input type="hidden" name="id" value="<?= $edit_barang['id'] ?? '' ?>">
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" value="<?= $edit_barang['nama_barang'] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" value="<?= $edit_barang['harga'] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" value="<?= $edit_barang['stok'] ?? '' ?>" required>
                    </div>
                    <button type="submit" name="simpan_barang" class="btn btn-success w-100">Simpan Barang</button>
                    <?php if($edit_barang): ?>
                        <a href="index.php?page=barang" class="btn btn-secondary w-100 mt-2">Batal Edit</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Daftar Barang Tersedia</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->query("SELECT * FROM barang ORDER BY id DESC");
                            $no = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $format_harga = number_format($row['harga'], 0, ',', '.');
                                echo "<tr>
                                        <td>{$no}</td>
                                        <td>{$row['nama_barang']}</td>
                                        <td>Rp. {$format_harga}</td>
                                        <td>{$row['stok']}</td>
                                        <td class='text-center'>
                                            <a href='index.php?page=barang&edit_id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                            <a href='index.php?page=barang&hapus_id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin hapus barang ini?\")'>Hapus</a>
                                        </td>
                                      </tr>";
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>