<?php
require 'db.php';
require 'auth.php';
requireLogin();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = trim($_POST['question'] ?? '');
    $optionsText = array_filter(array_map('trim', $_POST['options'] ?? []));

    if ($question === '' || count($optionsText) < 2) {
        $error = 'Please enter a question and at least 2 options.';
    } else {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO polls (question) VALUES (?)");
        $stmt->execute([$question]);
        $pollId = $pdo->lastInsertId();

        $stmtOption = $pdo->prepare("INSERT INTO options (poll_id, text) VALUES (?, ?)");
        foreach ($optionsText as $text) {
            $stmtOption->execute([$pollId, $text]);
        }
        $pdo->commit();

        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create New Poll</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>➕ New Poll</h1>

    <?php if ($error): ?>
        <p class="message error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="create_poll.php" method="post" class="poll-card">
        <label>Question:</label>
        <input type="text" name="question" required placeholder="e.g. What is your favorite color?">

        <label>Options (minimum 2):</label>
        <input type="text" name="options[]" placeholder="Option 1">
        <input type="text" name="options[]" placeholder="Option 2">
        <input type="text" name="options[]" placeholder="Option 3 (optional)">
        <input type="text" name="options[]" placeholder="Option 4 (optional)">

        <button type="submit" class="btn">Create poll</button>
    </form>

    <a class="results-link" href="index.php">← Back to polls</a>
</div>
</body>
</html>
