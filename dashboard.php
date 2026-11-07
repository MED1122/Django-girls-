<?php
require_once 'config.php';
require_once 'functions.php';
require_login();
$stmt = $pdo->prepare('SELECT full_name, email, created_at FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
if (!$user) { $_SESSION = []; session_destroy(); header('Location: login.php'); exit; }
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard | Django Girls Portal</title><link rel="stylesheet" href="style.css"></head><body>
<header class="topbar"><div class="brand"><span class="logo">D</span><span>Django Girls<br><small>Learning Community</small></span></div><nav><a href="dashboard.php">Dashboard</a><a href="logout.php">Log out</a></nav></header>
<main class="container"><section class="card"><h1>Hello, <?= e($user['full_name']) ?>!</h1><p>You are successfully signed in. This is your personal dashboard.</p>
<div class="grid"><div class="stat"><h2>Account name</h2><p><?= e($user['full_name']) ?></p></div><div class="stat"><h2>Email address</h2><p><?= e($user['email']) ?></p></div><div class="stat"><h2>Member since</h2><p><?= e(date('d M Y', strtotime($user['created_at']))) ?></p></div></div>
<a class="button" href="logout.php">Securely log out</a></section></main>
<footer>© [Your First Name] [Your Last Name], [Program], Semester 05, [ID], 2026</footer></body></html>
