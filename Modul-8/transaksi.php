<?php
// Proteksi halaman (Kasir dan Owner boleh akses, tapi pastikan login)
if (!isset($_SESSION['level'])) { 
    die("<div class='alert alert-danger'>Akses Ditolak.</div>"); 
}

// --- PROSES TAMBAH TRANSAKSI ---
if (isset($_POST['tambah_transaksi'])) {
    $pelanggan_id = $_POST['pelanggan_id'];
    $waktu_transaksi = $_POST['waktu_transaksi']; 
    $total = $_POST['total'];

    $stmt = $conn->prepare("INSERT INTO transaksi (pelanggan_id, waktu_transaksi, total) VALUES (:pelanggan, :waktu, :total)");
    $stmt->execute([':pelanggan' => $pelanggan_id, ':waktu' => $waktu_transaksi, ':total' => $total]);
    echo "<script>alert('Transaksi baru berhasil dicatat!'); window.location='index.php?page=transaksi';</script>";
}

// --- PROSES HAPUS TRANSAKSI ---
if (isset($_GET['hapus_id'])) {
    $id = $_GET['hapus_id'];
    // Asumsi primary key di tabel transaksi kamu adalah 'id'. 
    // Jika namanya 'id_transaksi', ganti kata 'id' di bawah menjadi 'id_transaksi'.
    $stmt = $conn->prepare("DELETE FROM transaksi WHERE id = :id");
    $stmt->execute([':id' => $id]);
    echo "<script>alert('Transaksi berhasil dihapus!'); window.location='index.php?page=transaksi';</script>";
}
?>

<div class="row">
    <!-- Form Tambah Transaksi -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Input Transaksi Baru</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=transaksi">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Transaksi</label>
                        <input type="date" name="waktu_transaksi" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih Pelanggan</label>
                        <select name="pelanggan_id" class="form-control" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            <?php
                            // PERBAIKAN: Memanggil kolom 'nama' dan 'id' sesuai dengan struktur tabel pelanggan-mu
                            $stmt_p = $conn->query("SELECT * FROM pelanggan ORDER BY nama ASC");
                            while ($p = $stmt_p->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$p['id']}'>{$p['nama']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Belanja (Rp)</label>
                        <input type="number" name="total" class="form-control" placeholder="Contoh: 150000" required>
                    </div>
                    <button type="submit" name="tambah_transaksi" class="btn btn-primary w-100">Catat Transaksi</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Transaksi -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Riwayat Transaksi Terakhir</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Pelanggan</th>
                                <th>Total (Rp)</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // PERBAIKAN: Menyesuaikan join dengan kolom 'id' dan 'nama' pada tabel pelanggan
                            $q = "SELECT t.*, p.nama 
                                  FROM transaksi t 
                                  LEFT JOIN pelanggan p ON t.pelanggan_id = p.id 
                                  ORDER BY t.waktu_transaksi DESC";
                            $stmt = $conn->query($q);
                            
                            $no = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $nama_p = isset($row['nama']) && $row['nama'] ? $row['nama'] : 'Pelanggan Dihapus/Umum';
                                $format_total = number_format($row['total'], 0, ',', '.');
                                
                                // Pengecekan nama ID transaksi yang luwes (berjaga-jaga jika ID-nya 'id' atau 'id_transaksi')
                                $pk_transaksi = isset($row['id_transaksi']) ? $row['id_transaksi'] : (isset($row['id']) ? $row['id'] : '');
                                
                                echo "<tr>
                                        <td>{$no}</td>
                                        <td>{$row['waktu_transaksi']}</td>
                                        <td>{$nama_p}</td>
                                        <td>Rp. {$format_total}</td>
                                        <td class='text-center'>
                                            <a href='index.php?page=transaksi&hapus_id={$pk_transaksi}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin hapus transaksi ini?\")'>Hapus</a>
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