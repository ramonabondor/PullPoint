<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$optionId = $_POST['option_id'] ?? null;
$pollId = $_POST['poll_id'] ?? null;

if (!$optionId || !$pollId) {
    header('Location: index.php?error=no_selection');
    exit;
}

// Simple anti-multiple-voting check, based on a cookie (per browser)
$cookieName = 'voted_poll_' . intval($pollId);
if (isset($_COOKIE[$cookieName])) {
    header('Location: results.php?id=' . intval($pollId) . '&message=already_voted');
    exit;
}

$stmt = $pdo->prepare("UPDATE options SET votes = votes + 1 WHERE id = ? AND poll_id = ?");
$stmt->execute([intval($optionId), intval($pollId)]);

// set a cookie valid for 1 year, so this browser can't vote again
setcookie($cookieName, '1', time() + (365 * 24 * 60 * 60), '/');

header('Location: results.php?id=' . intval($pollId) . '&voted=1');
exit;
