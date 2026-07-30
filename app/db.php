<?php
/**
 * SQLite (PDO) connection - single shared instance.
 * Foreign keys are enabled on every connection.
 */

/**
 * Return the shared PDO connection, creating it on first call.
 */
function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    if (!is_dir(DATA_PATH)) {
        @mkdir(DATA_PATH, 0775, true);
    }

    try {
        $pdo = new PDO('sqlite:' . DB_PATH, null, null, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        // Never leak connection details to the browser.
        error_log('DB connection failed: ' . $e->getMessage());
        http_response_code(500);
        exit('Database connection error.');
    }

    // Integrity + concurrency pragmas.
    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo->exec('PRAGMA journal_mode = WAL');
    $pdo->exec('PRAGMA busy_timeout = 5000');

    return $pdo;
}
