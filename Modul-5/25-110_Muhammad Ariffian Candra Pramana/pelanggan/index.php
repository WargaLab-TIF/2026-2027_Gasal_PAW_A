<?php
include '../components/html_temp.php';
require '../route.php';

$pelanggan = $DBH->prepare("SELECT * FROM pelanggan");
$pelanggan->execute();
$data = $pelanggan->fetchAll();
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand ps-5 fw-semibold">CRUD Penjualan</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active fw-normal" aria-current="page" href="#">Pelanggan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-normal" href="#">Transaksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-normal" href="#">Barang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-normal" href="#">Supplier</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-normal" href="#">User</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-normal" href="#">Pembayaran</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-secondary mb-0">Tambah Data Pelanggan</h3>
        <a href="tambah_data.php" class="btn btn-primary">Tambah Data</a>
    </div>


    <table class="table table-striped">
        <thead class="table-primary">
            <tr>
                <th class="col">No</th>
                <th class="col">Nama</th>
                <th class="col-2">Jenis Kelamin</th>
                <th class="col">No Telp</th>
                <th class="col">Alamat</th>
                <th class="col-2">Aksi</th>
            </tr>
        </thead>
        <?php
        $no = 1;
        foreach ($data as $row):
        ?>
            <tbody>
                <tr>
                    <th scope="row"><?= $no++ ?></th>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['jenis_kelamin'] ?></td>
                    <td><?= $row['telp'] ?></td>
                    <td><?= $row['alamat'] ?></td>
                    <td>
                        <a href="edit_data.php?id=<?= $row['id'] ?>" class="btn btn-outline-warning"><i class="bi bi-pencil-square"></i>Edit</a> 
                        <a href="hapus_data.php?id=<?= $row['id'] ?>" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');"><i class="bi bi-trash-fill"></i>Delete</a>
                    </td>
                </tr>
            </tbody>
        <?php endforeach ?>
    </table>
</div>