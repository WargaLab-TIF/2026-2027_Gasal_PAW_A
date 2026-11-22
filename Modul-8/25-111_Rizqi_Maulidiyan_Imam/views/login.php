<?php
require_once __DIR__ . '/partials/header.guest.php';

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-4">Login</h4>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= e($error); ?></div>
                    <?php endif; ?>

                    <form method="POST" action="<?= url('/sign-in'); ?>">
                        <div class="mb-3">
                            <label class="form-label" for="username">Username</label>
                            <input class="form-control" type="text" id="username" name="username"
                                   placeholder="Masukkan username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input class="form-control" type="password" id="password" name="password"
                                   placeholder="Masukkan password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/partials/footer.guest.php'; ?>