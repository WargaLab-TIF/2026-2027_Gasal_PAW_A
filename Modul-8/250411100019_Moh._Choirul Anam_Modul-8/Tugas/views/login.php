<?php $title = "Login"; ?>
<?php require_once __DIR__ . "/partials/header.guest.php" ?>

<h1>Sistem Penjualan</h1>

<?= render_flash() ?>

<form class="form" method="POST" action="<?= e(url('/sig-in')) ?>">
  <?= csrf_field() ?>
  <label for="username">Username</label>
  <input type="text" id="username" name="username" placeholder="Enter the username" required autofocus>

  <label for="password">Password</label>
  <input type="password" id="password" name="password" placeholder="Enter the password" required>

  <div class="aksi-form"><button type="submit" class="btn">Login</button></div>
</form>

<?php require_once __DIR__ . "/partials/footer.guest.php" ?>
