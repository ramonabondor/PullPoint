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

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        $error = 'Incorrect username or password.';
    } else {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Log In</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>🔑 Log In</h1>

    <?php if ($error): ?>
        <p class="message error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="login.php" method="post" class="poll-card">
        <label>Username:</label>
        <input type="text" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">Log in</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>
    <a class="results-link" href="index.php">← Back to polls</a>
</div>
</body>
</html>
