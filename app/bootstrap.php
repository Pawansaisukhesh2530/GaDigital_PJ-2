<?php
/**
 * Application bootstrap.
 * Loads config, database, helpers and starts a hardened session.
 * Safe to include from anywhere (idempotent).
 */

if (defined('NIVI_BOOTSTRAPPED')) {
    return;
}
define('NIVI_BOOTSTRAPPED', true);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

/**
 * Start a session once, with secure cookie parameters.
 */
function session_boot(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    // Detect HTTPS: direct or behind a reverse proxy (Render, Cloudflare, etc.)
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['SERVER_PORT'] ?? null) == 443)
        || (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

    // Use a writable, project-local session directory (the default
    // C:\xampp\tmp may not be writable in every environment).
    $sessDir = DATA_PATH . '/sessions';
    if (!is_dir($sessDir)) {
        @mkdir($sessDir, 0775, true);
    }
    if (is_dir($sessDir) && is_writable($sessDir)) {
        session_save_path($sessDir);
    }

    session_name(SESSION_NAME);
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => $https,
    ]);
    session_start();
}

// Auto-start a session for web requests only. CLI scripts (installer,
// seeders) don't need sessions and may not have a writable save path.
// Public read-only pages can opt out by defining NIVI_NO_SESSION first.
if (PHP_SAPI !== 'cli' && !defined('NIVI_NO_SESSION')) {
    session_boot();
}
