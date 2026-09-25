<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>NORAX setup</title><link rel="stylesheet" href="assets/admin.css"></head><body class="login-page"><main class="login-card"><a class="brand brand-centered" href="../index.html"><span class="brand-mark">N</span><span>NORAX <b>ACADEMY</b></span></a><p class="eyebrow">FIRST-TIME SETUP</p><h1>Create admin account</h1><p class="muted">Run the SQL schema in phpMyAdmin first, then create the first administrator here.</p><form method="post" class="stack-form"><label>Full name<input name="name" required></label><label>Email<input type="email" name="email" required></label><label>Password<input type="password" name="password" minlength="8" required></label><button class="button button-primary button-full">Create account</button></form></main></body></html><?php
    exit;
}

$name = trim((string) ($_POST['name'] ?? ''));
$email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
$password = (string) ($_POST['password'] ?? '');
if (!$name || !$email || strlen($password) < 8) exit('Please provide a name, valid email, and password of at least 8 characters.');

$statement = db()->prepare('INSERT INTO admins (name, email, password_hash) VALUES (?, ?, ?)');
$statement->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
header('Location: login.php?created=1');