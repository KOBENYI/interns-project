<?php
declare(strict_types=1);

require_once __DIR__ . '/config/auth.php';
require_login();

$pdo = db();
$message = '';
$error = '';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf();
        $action = (string) ($_POST['action'] ?? '');

        if ($action === 'create') {
            $name = trim((string) ($_POST['name'] ?? ''));
            $email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
            $password = (string) ($_POST['password'] ?? '');

            if (!$name || !$email || strlen($password) < 8) {
                throw new RuntimeException('Enter a name, valid email, and password of at least 8 characters.');
            }

            $statement = $pdo->prepare('INSERT INTO admins (name, email, password_hash) VALUES (?, ?, ?)');
            $statement->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            $message = 'Administrator added successfully.';
        } elseif ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id === (int) $_SESSION['admin_id']) {
                throw new RuntimeException('Your current administrator account cannot be deleted.');
            }

            $statement = $pdo->prepare('DELETE FROM admins WHERE id = ?');
            $statement->execute([$id]);
            $message = 'Administrator removed.';
        }
    }
} catch (Throwable $exception) {
    $error = $exception->getCode() === '23000' ? 'That email address is already in use.' : $exception->getMessage();
}

$admins = $pdo->query('SELECT id, name, email, created_at FROM admins ORDER BY created_at DESC')->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Management | NORAX ACADEMY</title>
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<div class="app">
    <aside class="sidebar">
        <a class="brand" href="index.php"><span class="brand-mark">N</span><span>NORAX <b>ACADEMY</b></span></a>
        <nav>
            <div class="nav-label">ADMINISTRATION</div>
            <a class="nav-link active" href="management.php"><span class="nav-icon">♙</span>Management</a>
            <a class="nav-link" href="index.php"><span class="nav-icon">▦</span>Back to dashboard</a>
        </nav>
        <div class="sidebar-bottom">
            <div class="admin-mini"><span class="avatar"><?= e(initials((string) $_SESSION['admin_name'])) ?></span><span><?= e($_SESSION['admin_name']) ?></span></div>
            <a class="logout-link" href="logout.php">↪ Sign out</a>
        </div>
    </aside>
    <main class="main">
        <header class="topbar"><div class="top-actions"><span class="breadcrumb">Workspace / <strong>Management</strong></span></div><div class="top-actions"><a href="index.php" title="Dashboard">⌂</a><span class="avatar"><?= e(initials((string) $_SESSION['admin_name'])) ?></span></div></header>
        <section class="content">
            <div class="heading-row"><div><h1>Management</h1><p class="subtitle">Control who can access the academy workspace.</p></div></div>
            <?php if ($message): ?><div class="alert alert-success"><?= e($message) ?></div><?php endif; ?>
            <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
            <div class="dashboard-grid">
                <div class="panel">
                    <div class="panel-head"><h2>Administrator accounts</h2><span class="muted"><?= count($admins) ?> accounts</span></div>
                    <div class="table-wrap"><table><thead><tr><th>Name</th><th>Email</th><th>Added</th><th>Action</th></tr></thead><tbody>
                    <?php foreach ($admins as $admin): ?><tr><td><div class="person"><span class="avatar"><?= e(initials($admin['name'])) ?></span><?= e($admin['name']) ?><?= (int) $admin['id'] === (int) $_SESSION['admin_id'] ? ' <small class="muted">(you)</small>' : '' ?></div></td><td><?= e($admin['email']) ?></td><td><?= e(date('d M Y', strtotime($admin['created_at']))) ?></td><td><?php if ((int) $admin['id'] !== (int) $_SESSION['admin_id']): ?><form method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int) $admin['id'] ?>"><button class="button button-danger button-small" data-confirm="Remove this administrator?">Remove</button></form><?php else: ?><span class="muted">Current account</span><?php endif; ?></td></tr><?php endforeach; ?>
                    </tbody></table></div>
                </div>
                <div class="panel" id="add-form"><div class="panel-head"><h2>Add administrator</h2></div><div class="panel-body"><form method="post" class="stack-form"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="create"><label>Full name<input name="name" required></label><label>Email address<input type="email" name="email" required></label><label>Temporary password<input type="password" name="password" minlength="8" required></label><button class="button button-primary" type="submit">Add administrator</button></form></div></div>
            </div>
        </section>
    </main>
</div>
<script src="assets/admin.js"></script>
</body>
</html>
