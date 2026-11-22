<?php
if (!isset($_SESSION['level']) || $_SESSION['level'] != 1) { 
    die("<div class='alert alert-danger'>Akses Ditolak.</div>"); 
}

if (isset($_GET['hapus_id'])) {
    $id = $_GET['hapus_id'];
    $stmt = $conn->prepare("DELETE FROM supplier WHERE id = :id");
    $stmt->execute([':id' => $id]);
    echo "<script>alert('Data Supplier berhasil dihapus!'); window.location='index.php?page=supplier';</script>";
}

if (isset($_POST['simpan_supplier'])) {
    $nama_supplier = $_POST['nama_supplier'];
    $alamat = $_POST['alamat']; 
    $no_telp = $_POST['no_telp'];
    $id = $_POST['id']; 

    if (empty($id)) {
        $stmt = $conn->prepare("INSERT INTO supplier (nama, alamat, telp) VALUES (:nama, :alamat, :telp)");
        $stmt->execute([':nama' => $nama_supplier, ':alamat' => $alamat, ':telp' => $no_telp]);
        echo "<script>alert('Supplier baru berhasil ditambahkan!'); window.location='index.php?page=supplier';</script>";
    } else {
        $stmt = $conn->prepare("UPDATE supplier SET nama=:nama, alamat=:alamat, telp=:telp WHERE id=:id");
        $stmt->execute([':nama' => $nama_supplier, ':alamat' => $alamat, ':telp' => $no_telp, ':id' => $id]);
        echo "<script>alert('Data Supplier berhasil diperbarui!'); window.location='index.php?page=supplier';</script>";
    }
}

$edit_supplier = null;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM supplier WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $edit_supplier = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><?= $edit_supplier ? 'Edit' : 'Tambah' ?> Data Supplier</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=supplier">
                    <input type="hidden" name="id" value="<?= $edit_supplier['id'] ?? '' ?>">
                    <div class="mb-3">
                        <label class="form-label">Nama Supplier</label>
                        <input type="text" name="nama_supplier" class="form-control" value="<?= $edit_supplier['nama'] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2" required><?= $edit_supplier['alamat'] ?? '' ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telp" class="form-control" value="<?= $edit_supplier['telp'] ?? '' ?>" required>
                    </div>
                    <button type="submit" name="simpan_supplier" class="btn btn-info text-white w-100">Simpan Supplier</button>
                    <?php if($edit_supplier): ?>
                        <a href="index.php?page=supplier" class="btn btn-secondary w-100 mt-2">Batal Edit</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Daftar Supplier</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th>No</th>
                                <th>Nama Supplier</th>
                                <th>Alamat</th>
                                <th>No. Telp</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->query("SELECT * FROM supplier ORDER BY id DESC");
                            $no = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>
                                        <td>{$no}</td>
                                        <td>{$row['nama']}</td>
                                        <td>{$row['alamat']}</td>
                                        <td>{$row['telp']}</td>
                                        <td class='text-center'>
                                            <a href='index.php?page=supplier&edit_id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                            <a href='index.php?page=supplier&hapus_id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin hapus supplier ini?\")'>Hapus</a>
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