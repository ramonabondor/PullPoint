<?php
//session handling helpers
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() : bool {
    return isset($_SESSION['user_id']);
}

function currentUsername(): ?string
{
    return $_SESSION['username'] ?? null;
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

?>