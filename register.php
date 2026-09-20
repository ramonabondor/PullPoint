<?php
require 'db.php';
require 'auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please fill in both a username and a password.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'That username is already taken.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
            $stmt->execute([$username, $hash]);

            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;

            header('Location: index.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>📝 Create Account</h1>

    <?php if ($error): ?>
        <p class="message error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="register.php" method="post" class="poll-card">
        <label>Username:</label>
        <input type="text" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">

        <label>Password:</label>
        <input type="password" name="password" required minlength="6">

        <label>Confirm password:</label>
        <input type="password" name="confirm_password" required minlength="6">

        <button type="submit" class="btn">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Log in</a></p>
    <a class="results-link" href="index.php">← Back to polls</a>
</div>
</body>
</html>
