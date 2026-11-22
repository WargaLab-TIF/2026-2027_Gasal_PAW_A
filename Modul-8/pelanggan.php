<?php
if (!isset($_SESSION['level']) || $_SESSION['level'] != 1) { 
    die("<div class='alert alert-danger'>Akses Ditolak.</div>"); 
}

if (isset($_GET['hapus_id'])) {
    $id = $_GET['hapus_id'];
    $stmt = $conn->prepare("DELETE FROM pelanggan WHERE id = :id");
    $stmt->execute([':id' => $id]);
    echo "<script>alert('Data Pelanggan berhasil dihapus!'); window.location='index.php?page=pelanggan';</script>";
}

if (isset($_POST['simpan_pelanggan'])) {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat']; 
    $no_telp = $_POST['no_telp'];
    $id = $_POST['id']; 
    
    // Defaulting jenis kelamin for now to prevent null errors on insert
    $jenis_kelamin = 'L'; 

    if (empty($id)) {
        $stmt = $conn->prepare("INSERT INTO pelanggan (nama, jenis_kelamin, telp, alamat) VALUES (:nama, :jk, :telp, :alamat)");
        $stmt->execute([':nama' => $nama_pelanggan, ':jk' => $jenis_kelamin, ':telp' => $no_telp, ':alamat' => $alamat]);
        echo "<script>alert('Pelanggan baru berhasil ditambahkan!'); window.location='index.php?page=pelanggan';</script>";
    } else {
        $stmt = $conn->prepare("UPDATE pelanggan SET nama=:nama, alamat=:alamat, telp=:telp WHERE id=:id");
        $stmt->execute([':nama' => $nama_pelanggan, ':alamat' => $alamat, ':telp' => $no_telp, ':id' => $id]);
        echo "<script>alert('Data Pelanggan berhasil diperbarui!'); window.location='index.php?page=pelanggan';</script>";
    }
}

$edit_pelanggan = null;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM pelanggan WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $edit_pelanggan = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><?= $edit_pelanggan ? 'Edit' : 'Tambah' ?> Data Pelanggan</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=pelanggan">
                    <input type="hidden" name="id" value="<?= $edit_pelanggan['id'] ?? '' ?>">
                    <div class="mb-3">
                        <label class="form-label">Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" class="form-control" value="<?= $edit_pelanggan['nama'] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2" required><?= $edit_pelanggan['alamat'] ?? '' ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telp" class="form-control" value="<?= $edit_pelanggan['telp'] ?? '' ?>" required>
                    </div>
                    <button type="submit" name="simpan_pelanggan" class="btn btn-warning w-100">Simpan Pelanggan</button>
                    <?php if($edit_pelanggan): ?>
                        <a href="index.php?page=pelanggan" class="btn btn-secondary w-100 mt-2">Batal Edit</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Daftar Pelanggan</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th>No</th>
                                <th>Nama Pelanggan</th>
                                <th>Alamat</th>
                                <th>No. Telp</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->query("SELECT * FROM pelanggan ORDER BY id DESC");
                            $no = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>
                                        <td>{$no}</td>
                                        <td>{$row['nama']}</td>
                                        <td>{$row['alamat']}</td>
                                        <td>{$row['telp']}</td>
                                        <td class='text-center'>
                                            <a href='index.php?page=pelanggan&edit_id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                            <a href='index.php?page=pelanggan&hapus_id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin hapus pelanggan ini?\")'>Hapus</a>
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