<?php
require_once 'config.php';
require_once 'functions.php';
$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Enter a valid email address and password.';
    } else {
        $stmt = $pdo->prepare('SELECT id, full_name, email, password_hash FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            header('Location: dashboard.php');
            exit;
        }
        $error = 'Incorrect email address or password.';
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Log in | Django Girls Portal</title><link rel="stylesheet" href="style.css"></head><body>
<header class="topbar"><div class="brand"><span class="logo">D</span><span>Django Girls<br><small>Learning Community</small></span></div><nav><a href="index.php">Home</a><a href="register.php">Sign up</a></nav></header>
<main class="container"><section class="card form-wrap"><h1>Welcome back</h1><p>Log in to continue to your dashboard.</p>
<?php if ($error): ?><div class="notice"><?= e($error) ?></div><?php endif; ?>
<form method="post" action="login.php">
<label for="email">Email address</label><input id="email" type="email" name="email" required autocomplete="email" value="<?= e($email) ?>">
<label for="password">Password</label><input id="password" type="password" name="password" required autocomplete="current-password">
<button type="submit">Log in</button></form><p>New here? <a href="register.php">Create an account</a>.</p></section></main>
<footer>© [Your First Name] [Your Last Name], [Program], Semester 05, [ID], 2026</footer></body></html>
