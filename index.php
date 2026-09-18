<?php
require 'db.php';

$polls = $pdo->query("SELECT * FROM polls ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

$message = '';
if (isset($_GET['error']) && $_GET['error'] === 'no_selection') {
    $message = 'Please select an option before voting.';
}
if (isset($_GET['voted']) && $_GET['voted'] === '1') {
    $message = 'Your vote has been recorded. Thank you!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Voting / Poll System</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>🗳️ Voting System</h1>

    <div class="admin-link">
        <a href="create_poll.php">+ Create a new poll</a>
    </div>

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (empty($polls)): ?>
        <p>There are no polls at the moment.</p>
    <?php endif; ?>

    <?php foreach ($polls as $poll): ?>
        <?php
        $options = $pdo->prepare("SELECT * FROM options WHERE poll_id = ?");
        $options->execute([$poll['id']]);
        $options = $options->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="poll-card">
            <h2><?= htmlspecialchars($poll['question']) ?></h2>
            <form action="vote.php" method="post">
                <input type="hidden" name="poll_id" value="<?= $poll['id'] ?>">
                <?php foreach ($options as $option): ?>
                    <label class="option">
                        <input type="radio" name="option_id" value="<?= $option['id'] ?>" required>
                        <?= htmlspecialchars($option['text']) ?>
                    </label>
                <?php endforeach; ?>
                <button type="submit" class="btn">Vote</button>
            </form>
            <a class="results-link" href="results.php?id=<?= $poll['id'] ?>">View results →</a>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
