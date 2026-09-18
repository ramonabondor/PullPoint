<?php
require 'db.php';

$pollId = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM polls WHERE id = ?");
$stmt->execute([$pollId]);
$poll = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$poll) {
    die('Poll not found. <a href="index.php">Back</a>');
}

$stmt = $pdo->prepare("SELECT * FROM options WHERE poll_id = ? ORDER BY votes DESC");
$stmt->execute([$pollId]);
$options = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalVotes = array_sum(array_column($options, 'votes'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Results - <?= htmlspecialchars($poll['question']) ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>📊 Results</h1>
    <h2><?= htmlspecialchars($poll['question']) ?></h2>

    <?php if (isset($_GET['message']) && $_GET['message'] === 'already_voted'): ?>
        <p class="message">You've already voted on this poll from this browser.</p>
    <?php elseif (isset($_GET['voted'])): ?>
        <p class="message">Your vote has been recorded. Thank you!</p>
    <?php endif; ?>

    <div class="results-card">
        <?php foreach ($options as $option): ?>
            <?php
            $percent = $totalVotes > 0 ? round(($option['votes'] / $totalVotes) * 100, 1) : 0;
            ?>
            <div class="result-bar">
                <div class="bar-info">
                    <span><?= htmlspecialchars($option['text']) ?></span>
                    <span><?= $option['votes'] ?> votes (<?= $percent ?>%)</span>
                </div>
                <div class="bar-background">
                    <div class="bar-fill" style="width: <?= $percent ?>%;"></div>
                </div>
            </div>
        <?php endforeach; ?>

        <p class="total">Total votes: <?= $totalVotes ?></p>
    </div>

    <a class="results-link" href="index.php">← Back to polls</a>
</div>
</body>
</html>
