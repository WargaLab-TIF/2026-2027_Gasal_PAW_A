<?php
include '../components/html_temp.php';
require '../route.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'] ?? '';
    $telp = $_POST['telepon'] ?? '';
    $jenis_kelamin = $_POST['jk'] ?? '';
    $alamat = $_POST['alamat'] ?? '';

    $data = $DBH->prepare("
	INSERT INTO pelanggan (nama, telp, jenis_kelamin, alamat) 
	VALUES('$nama', '$telp', '$jenis_kelamin', '$alamat')
	");
    $data->execute();

    header("Location: index.php");
    exit;
}


?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand ps-3 ps-md-5 fw-semibold" href="#">CRUD Penjualan</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link active fw-normal" aria-current="page" href="#">Pelanggan</a></li>
                <li class="nav-item"><a class="nav-link fw-normal" href="#">Transaksi</a></li>
                <li class="nav-item"><a class="nav-link fw-normal" href="#">Barang</a></li>
                <li class="nav-item"><a class="nav-link fw-normal" href="#">Supplier</a></li>
                <li class="nav-item"><a class="nav-link fw-normal" href="#">User</a></li>
                <li class="nav-item"><a class="nav-link fw-normal" href="#">Pembayaran</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-secondary mb-0">Tambah Data Pelanggan</h3>
        <a href="index.php" class="btn btn-outline-secondary px-4">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-semibold">Formulir Pelanggan Baru</h5>
        </div>
        <div class="card-body p-4">
            <form action="" method="POST">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nama" class="form-label fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="col-md-6 mt-3 mt-md-0">
                        <label for="telepon" class="form-label fw-medium">No. Telepon <span class="text-danger">*</span></label>
                        <input type="number" name="telepon" id="telepon" class="form-control" placeholder="Contoh: 081234567890" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12 mt-3 mt-md-0">
                        <label for="jk" class="form-label fw-medium">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-select" name="jk" id="jk" required>
                            <option value="" selected disabled>-- Pilih Jenis Kelamin --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="alamat" class="form-label fw-medium">Alamat Lengkap <span class="text-danger">*</span></label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat tempat tinggal pelanggan" required></textarea>
                </div>

                <hr class="text-muted">
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="reset" class="btn btn-light border">Reset</button>
                    <button type="submit" class="btn btn-primary px-4" onclick="window.location.href='index.php'">Simpan Data</button>
                </div>

            </form>
        </div>
    </div>
</div>