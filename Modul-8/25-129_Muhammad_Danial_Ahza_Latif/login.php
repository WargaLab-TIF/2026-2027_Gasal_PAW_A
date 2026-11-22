<?php
require_once 'koneksi.php';
require_once 'auth.php';

cekBelumLogin();

$page_title = "Login";
$active_page = "login";
$body_class = "bg-[#F8F9FA]";

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Username dan password wajib diisi!";
    } else {
        $stmt = $DBH->prepare(
            "SELECT * FROM user_data WHERE username = ? LIMIT 1"
        );

        $stmt->execute([$username]);
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (
            $user_data &&
            $password === $user_data['password'] &&
            in_array((int) $user_data['level'], [1, 2], true)
        ) {
            session_regenerate_id(true);

            $_SESSION['user_login'] = $user_data['nama'];
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['id_user'] = $user_data['id_user'];
            $_SESSION['level'] = (int) $user_data['level'];

            header("Location: index.php");
            exit;
        } else {
            $error = "Username atau password salah!";
        }
    }
}

include 'components/header.php';
?>



<main class="py-10 px-4 sm:px-8 flex-1 flex items-center justify-center">

    <div class="w-full max-w-md mx-auto bg-white border border-slate-200 shadow-sm p-8 sm:p-12">

        <div class="pb-6 mb-8 border-b border-slate-100 text-center">

            <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-slate-900 font-serif">
                Login
            </h1>

        </div>

        <?php if ($error !== ''): ?>

            <div class="mb-6 p-3 border border-rose-200 bg-rose-50 text-rose-600 text-xs font-semibold">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <form method="POST" action="" class="space-y-6">

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Username <span class="text-rose-500">*</span>
                </label>

                <input type="text" name="username" required autocomplete="username"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    class="w-full px-4 py-2.5 border border-slate-200 text-xs text-slate-900 focus:outline-none focus:border-slate-900 placeholder:text-slate-400"
                >
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Password <span class="text-rose-500">*</span>
                </label>

                <input type="password" name="password" required
               class="w-full px-4 py-2.5 border border-slate-200 text-xs text-slate-900 focus:outline-none focus:border-slate-900 placeholder:text-slate-400"
                >
            </div>

            <div class="pt-6 border-t border-slate-100">

                <button
                    type="submit"
                    class="w-full px-8 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-widest transition-colors shadow-sm flex items-center justify-center gap-2"
                >

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4m-4-4 4-4m0 0-4-4m4 4H3" />
                    </svg>

                    <span>Login</span>

                </button>

            </div>

        </form>

        <!-- Footer Login -->
        <div class="mt-8 pt-5 border-t border-slate-100 text-center"></div>

    </div>

</main>

<?php include 'components/footer.php'; ?>
