<?php
/**
 * Authentication & session-guard functions for the admin panel.
 * Depends on app/bootstrap.php (session, db, helpers) being loaded.
 */

// Inactivity timeout: 30 minutes.
define('AUTH_TIMEOUT', 30 * 60);

/**
 * Attempt to log in with a username-or-email + password.
 * Returns true on success. On success the session id is regenerated
 * (prevents session fixation).
 */
function auth_attempt(string $identity, string $password): bool
{
    $identity = trim($identity);
    if ($identity === '' || $password === '') {
        return false;
    }

    $stmt = db()->prepare(
        'SELECT id, username, display_name, email, password_hash
           FROM admins
          WHERE username = :id OR email = :id
          LIMIT 1'
    );
    $stmt->execute([':id' => $identity]);
    $admin = $stmt->fetch();

    if (!$admin || !password_verify($password, $admin['password_hash'])) {
        return false;
    }

    // Transparently upgrade legacy hashes if the algorithm changes.
    if (password_needs_rehash($admin['password_hash'], PASSWORD_DEFAULT)) {
        $new = password_hash($password, PASSWORD_DEFAULT);
        $up  = db()->prepare('UPDATE admins SET password_hash = :h, updated_at = datetime(\'now\') WHERE id = :id');
        $up->execute([':h' => $new, ':id' => $admin['id']]);
    }

    // Record last login timestamp.
    try {
        db()->prepare("UPDATE admins SET last_login_at = datetime('now') WHERE id = :id")
            ->execute([':id' => $admin['id']]);
    } catch (\Throwable $e) {
        // ignore if column not present yet
    }

    // Fresh session id on privilege change.
    session_regenerate_id(true);
    $_SESSION['admin_id']           = (int) $admin['id'];
    $_SESSION['admin_username']     = $admin['username'];
    $_SESSION['admin_display_name'] = $admin['display_name'] ?? $admin['username'];
    $_SESSION['admin_email']        = $admin['email'];
    $_SESSION['last_activity']      = time();

    return true;
}

/** Is there a valid, non-expired admin session? */
function auth_check(): bool
{
    if (empty($_SESSION['admin_id'])) {
        return false;
    }
    // Enforce inactivity timeout.
    $last = $_SESSION['last_activity'] ?? 0;
    if (time() - $last > AUTH_TIMEOUT) {
        // Clear auth data but keep the session alive so the notice survives
        // the redirect; regenerate the id to avoid fixation.
        $_SESSION = [];
        session_regenerate_id(true);
        flash_set('login_error', 'Your session expired due to inactivity. Please sign in again.');
        return false;
    }
    $_SESSION['last_activity'] = time();
    return true;
}

/** The current admin row (id, username, email) or null. */
function auth_user(): ?array
{
    if (!auth_check()) {
        return null;
    }
    return [
        'id'           => $_SESSION['admin_id'],
        'username'     => $_SESSION['admin_username'] ?? '',
        'display_name' => $_SESSION['admin_display_name'] ?? ($_SESSION['admin_username'] ?? ''),
        'email'        => $_SESSION['admin_email'] ?? '',
    ];
}

/**
 * Guard an admin page. Redirects unauthenticated users to the login,
 * preserving why they were bounced (timeout vs. login-required).
 */
function require_admin(): void
{
    if (!auth_check()) {
        redirect(admin_url('index.php'));
    }
}

/** Completely destroy the current session (logout). */
function auth_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires'  => time() - 42000,
            'path'     => $p['path'],
            'domain'   => $p['domain'],
            'secure'   => $p['secure'],
            'httponly' => $p['httponly'],
            'samesite' => $p['samesite'] ?? 'Lax',
        ]);
    }
    session_destroy();
}

/**
 * Absolute URL path to a file relative to the ADMIN ROOT (/admin/), derived
 * from the running script. Works from any admin sub-folder depth, with or
 * without a trailing slash, and regardless of the folder the site is hosted in.
 *
 *   /admin/index.php            -> admin_url('dashboard.php')      = /admin/dashboard.php
 *   /admin/projects/edit.php    -> admin_url('index.php')          = /admin/index.php
 *   /sub/admin/projects/edit.php-> admin_url('assets/admin.css')   = /sub/admin/assets/admin.css
 */
function admin_url(string $path = ''): string
{
    $sn  = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/admin/index.php');
    $pos = strrpos($sn, '/admin/');
    $base = $pos !== false ? substr($sn, 0, $pos + 7) : '/admin/';   // includes trailing slash
    return $base . ltrim($path, '/');
}
