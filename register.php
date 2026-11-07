<?php
require_once 'config.php';
require_once 'functions.php';
$errors = [];
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($name === '' || mb_strlen($name) > 120) $errors[] = 'Enter your name (maximum 120 characters).';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (strlen($password) < 8) $errors[] = 'Password must contain at least 8 characters.';
    if ($password !== $confirm) $errors[] = 'The passwords do not match.';

    if (!$errors) {
        try {
            $stmt = $pdo->prepare('INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$pdo->lastInsertId();
            $_SESSION['full_name'] = $name;
            header('Location: dashboard.php');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') $errors[] = 'That email address is already registered.';
            else { error_log($e->getMessage()); $errors[] = 'Registration failed. Please try again.'; }
        }
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sign up | Django Girls Portal</title><link rel="stylesheet" href="style.css"></head><body>
<header class="topbar"><div class="brand"><span class="logo">D</span><span>Django Girls<br><small>Learning Community</small></span></div><nav><a href="index.php">Home</a><a href="login.php">Log in</a></nav></header>
<main class="container"><section class="card form-wrap"><h1>Create your account</h1><p>Register to access your personal dashboard.</p>
<?php if ($errors): ?><div class="notice"><?php foreach ($errors as $error): ?><div><?= e($error) ?></div><?php endforeach; ?></div><?php endif; ?>
<form method="post" action="register.php">
<label for="full_name">Full name</label><input id="full_name" name="full_name" required maxlength="120" autocomplete="name" value="<?= e($name) ?>">
<label for="email">Email address</label><input id="email" type="email" name="email" required autocomplete="email" value="<?= e($email) ?>">
<label for="password">Password (at least 8 characters)</label><input id="password" type="password" name="password" required minlength="8" autocomplete="new-password">
<label for="confirm_password">Confirm password</label><input id="confirm_password" type="password" name="confirm_password" required minlength="8" autocomplete="new-password">
<button type="submit">Create account</button></form><p>Already registered? <a href="login.php">Log in</a>.</p></section></main>
<footer>© [Your First Name] [Your Last Name], [Program], Semester 05, [ID], 2026</footer></body></html>
