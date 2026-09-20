<?php
// db.php - database connection and initialization (SQLite)
$dbFile = __DIR__ . '/voting.db';
$dbExists = file_exists($dbFile);

try {
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection error: ' . $e->getMessage());
}

if (!$dbExists) {
    $pdo->exec("
        CREATE TABLE polls (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            question TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $pdo->exec("
        CREATE TABLE options (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            poll_id INTEGER NOT NULL,
            text TEXT NOT NULL,
            votes INTEGER DEFAULT 0,
            FOREIGN KEY (poll_id) REFERENCES polls(id)
        )
    ");

    // sample poll
    $pdo->exec("INSERT INTO polls (question) VALUES ('What is your favorite programming language?')");
    $pollId = $pdo->lastInsertId();

    $sampleOptions = ['PHP', 'JavaScript', 'Python', 'Java'];
    $stmt = $pdo->prepare("INSERT INTO options (poll_id, text) VALUES (?, ?)");
    foreach ($sampleOptions as $option) {
        $stmt->execute([$pollId, $option]);
    }
}

//make sure the users table exists for authentication
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password_hash TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");
