<?php
// Proteksi ekstra: Pastikan hanya Level 1 (Owner) yang bisa mengeksekusi script ini
if (!isset($_SESSION['level']) || $_SESSION['level'] != 1) { 
    die("<div class='alert alert-danger'>Akses Ditolak.</div>"); 
}

// --- PROSES HAPUS DATA ---
if (isset($_GET['hapus_id'])) {
    $id = $_GET['hapus_id'];
    $stmt = $conn->prepare("DELETE FROM user WHERE id_user = :id");
    $stmt->execute([':id' => $id]);
    echo "<script>alert('Data User berhasil dihapus!'); window.location='index.php?page=user';</script>";
}

// --- PROSES TAMBAH & EDIT DATA ---
if (isset($_POST['simpan'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; 
    $level_user = $_POST['level_user'];
    $id = $_POST['id']; 

    if (empty($id)) {
        // Logika Tambah Data Baru
        $stmt = $conn->prepare("INSERT INTO user (username, password, level) VALUES (:username, :password, :level)");
        $stmt->execute([':username' => $username, ':password' => $password, ':level' => $level_user]);
        echo "<script>alert('Data User baru berhasil ditambahkan!'); window.location='index.php?page=user';</script>";
    } else {
        // Logika Edit Data
        $stmt = $conn->prepare("UPDATE user SET username=:username, password=:password, level=:level WHERE id_user=:id");
        $stmt->execute([':username' => $username, ':password' => $password, ':level' => $level_user, ':id' => $id]);
        echo "<script>alert('Data User berhasil diperbarui!'); window.location='index.php?page=user';</script>";
    }
}

// --- AMBIL DATA JIKA TOMBOL EDIT DITEKAN ---
$edit_data = null;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM user WHERE id_user = :id");
    $stmt->execute([':id' => $id]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="row">
    <!-- Kolom Form Input (Kiri) -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><?= $edit_data ? 'Edit' : 'Tambah' ?> Data User</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=user">
                    <input type="hidden" name="id" value="<?= $edit_data['id_user'] ?? '' ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= $edit_data['username'] ?? '' ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="text" name="password" class="form-control" value="<?= $edit_data['password'] ?? '' ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Level Akses</label>
                        <select name="level_user" class="form-control" required>
                            <option value="1" <?= (isset($edit_data['level']) && $edit_data['level'] == 1) ? 'selected' : '' ?>>Owner (1)</option>
                            <option value="2" <?= (isset($edit_data['level']) && $edit_data['level'] == 2) ? 'selected' : '' ?>>Kasir (2)</option>
                        </select>
                    </div>
                    
                    <button type="submit" name="simpan" class="btn btn-success w-100">Simpan Data</button>
                    <?php if($edit_data): ?>
                        <a href="index.php?page=user" class="btn btn-secondary w-100 mt-2">Batal Edit</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Tabel Data (Kanan) -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Daftar User Sistem</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Level</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Perbaikan pada query: Sesuaikan nama primary key
                            $stmt = $conn->query("SELECT * FROM user ORDER BY id_user DESC");
                            $no = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $level_text = ($row['level'] == 1) ? 'Owner' : 'Kasir';
                                // Perbaikan kutip tunggal pada class='text-center'
                                echo "<tr>
                                        <td>{$no}</td>
                                        <td>{$row['username']}</td>
                                        <td>{$level_text}</td>
                                        <td class='text-center'>
                                            <a href='index.php?page=user&edit_id={$row['id_user']}' class='btn btn-warning btn-sm'>Edit</a>
                                            <a href='index.php?page=user&hapus_id={$row['id_user']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus user ini?\")'>Hapus</a>
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