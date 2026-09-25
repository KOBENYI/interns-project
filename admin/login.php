<?php
declare(strict_types=1);

require_once __DIR__ . '/config/auth.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = (string) ($_POST['password'] ?? '');

    if (!$email || strlen($password) < 8 || !login_admin($email, $password)) {
        $error = 'The email or password is incorrect.';
    } else {
        header('Location: index.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin sign in | NORAX ACADEMY</title>
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body class="login-page">
    <main class="login-card">
        <a class="brand brand-centered" href="../index.html"><span class="brand-mark">N</span><span>NORAX <b>ACADEMY</b></span></a>
        <p class="eyebrow">ADMIN CONSOLE</p>
        <h1>Welcome back</h1>
        <p class="muted">Sign in to manage your academy.</p>
        <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
        <form method="post" class="stack-form" novalidate>
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <label>Email address<input type="email" name="email" autocomplete="username" required value="<?= e($_POST['email'] ?? '') ?>"></label>
            <label>Password<input type="password" name="password" autocomplete="current-password" minlength="8" required></label>
            <button class="button button-primary button-full" type="submit">Sign in <span aria-hidden="true">→</span></button>
        </form>
        <p class="login-foot">NORAX ACADEMY · Yaoundé, Cameroon</p>
    </main>
</body>
</html>