# Voting / Poll System

A simple PHP voting/poll application using SQLite for storage — no external database server required.

## Features

- Create polls with a question and multiple options
- Vote on any active poll
- View live results with percentage bars
- One vote per poll per browser (enforced via cookie)
- Zero configuration — the database is created automatically on first run

## Requirements

- PHP 7.4 or higher
- The `pdo_sqlite` PHP extension (enabled by default in most PHP installs, including XAMPP)

## Files

| File | Purpose |
|---|---|
| `db.php` | Connects to the SQLite database and creates the tables (plus a sample poll) on first run |
| `index.php` | Home page — lists all polls and lets you vote |
| `vote.php` | Handles the vote submission |
| `results.php` | Shows results with percentage bars for a given poll |
| `create_poll.php` | Form to create a new poll with custom options |
| `style.css` | Styling for all pages |
| `voting.db` | SQLite database file (created automatically — not included) |

## Running the app

### Option A — XAMPP (recommended, no setup needed)

1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** from the XAMPP Control Panel.
2. Copy this whole folder into `C:\xampp\htdocs\poll-app` (Windows) or `/Applications/XAMPP/htdocs/poll-app` (Mac).
3. Open `http://localhost/poll-app/index.php` in your browser.

### Option B — PHP's built-in server

1. Make sure PHP is installed and available in your terminal (`php -v` should print a version).
2. Open a terminal in this folder and run:
   ```
   php -S localhost:8000
   ```
3. Open `http://localhost:8000/index.php` in your browser.

## Notes

- The database file `voting.db` is created automatically the first time the app runs, along with one sample poll ("What is your favorite programming language?").
- To reset all data, simply delete `voting.db` and reload the page — it will be recreated.
- The one-vote-per-browser limit uses cookies, so it can be bypassed by clearing cookies or using a different browser/device. For stricter control (e.g. one vote per user account), you'd need to add a login system.
