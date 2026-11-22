<?php
require_once __DIR__ . '/partials/header.auth.php';

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<main class="container my-4">
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= e($error); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <h4>Selamat datang, <?= e($_SESSION['nama']); ?></h4>
            <p class="mb-0 text-muted">
                Anda login sebagai <?= $_SESSION['level'] == 1 ? 'Owner' : 'Kasir'; ?>.
            </p>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/partials/footer.auth.php'; ?>