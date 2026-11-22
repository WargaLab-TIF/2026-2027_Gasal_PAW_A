<?php
cekAkses([1]); 

$data = $DBH->query("SELECT id_user, username, nama, alamat, hp, level FROM user ORDER BY id_user ASC")->fetchAll();

require_once __DIR__ . '/../partials/header.auth.php';
?>

<main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Data User</h2>
        <a href="<?= url('/user/tambah'); ?>" class="btn btn-primary">Tambah User</a>
    </div>

    <?php tampil_pesan(); ?>

    <div class="table-responsive">
    <table class="table table-bordered bg-white text-center align-middle">
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>HP</th>
            <th>Level</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($data as $no => $row): ?>
            <tr>
                <td><?= $no + 1; ?></td>
                <td><?= e($row['username']); ?></td>
                <td><?= e($row['nama']); ?></td>
                <td><?= e($row['alamat']); ?></td>
                <td><?= e($row['hp']); ?></td>
                <td>
                    <?php
                    if ($row['level'] == 1)      echo 'Owner';
                    elseif ($row['level'] == 2)  echo 'Kasir';
                    else                         echo 'Level ' . e($row['level']);
                    ?>
                </td>
                <td>
                    <a href="<?= url('/user/edit'); ?>?id=<?= $row['id_user']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <?php if ($row['id_user'] != $_SESSION['id_user']): ?>
                        <form method="POST" action="<?= url('/user/hapus'); ?>" class="d-inline"
                              onsubmit="return confirm('Yakin hapus user ini?');">
                            <input type="hidden" name="id" value="<?= $row['id_user']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    </div>
</main>

<?php require_once __DIR__ . '/../partials/footer.auth.php'; ?>
