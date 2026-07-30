<?php
/**
 * Reusable helper functions (output escaping, CSRF, flash,
 * validation, redirects, slugs). Framework-free, PHP 8+.
 */

/** Escape a value for safe HTML output. */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Redirect and stop execution. */
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/** Trim + collapse a scalar request value to a clean string. */
function input(string $key, $default = ''): string
{
    $v = $_POST[$key] ?? $_GET[$key] ?? $default;
    return is_string($v) ? trim($v) : $default;
}

/* -------------------------------------------------------------
 *  CSRF protection
 * ----------------------------------------------------------- */

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

/** Hidden input containing the CSRF token. */
function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

/** Validate a submitted CSRF token (constant-time). */
function csrf_verify(?string $token): bool
{
    return is_string($token)
        && !empty($_SESSION['_csrf'])
        && hash_equals($_SESSION['_csrf'], $token);
}

/** Abort with 419 if the POSTed CSRF token is invalid. */
function csrf_check(): void
{
    if (!csrf_verify($_POST['_csrf'] ?? null)) {
        http_response_code(419);
        exit('Invalid or expired form token. Please go back and try again.');
    }
}

/* -------------------------------------------------------------
 *  Flash messages + old input (survive one redirect)
 * ----------------------------------------------------------- */

function flash_set(string $key, $value): void
{
    $_SESSION['_flash'][$key] = $value;
}

function flash_get(string $key, $default = null)
{
    if (isset($_SESSION['_flash'][$key])) {
        $v = $_SESSION['_flash'][$key];
        unset($_SESSION['_flash'][$key]);
        return $v;
    }
    return $default;
}

function flash_has(string $key): bool
{
    return isset($_SESSION['_flash'][$key]);
}

/** Store submitted values so a form can be re-populated after a redirect. */
function old_set(array $data): void
{
    $_SESSION['_old'] = $data;
}

function old(string $key, $default = '')
{
    return $_SESSION['_old'][$key] ?? $default;
}

function old_clear(): void
{
    unset($_SESSION['_old']);
}

/* -------------------------------------------------------------
 *  Misc
 * ----------------------------------------------------------- */

/** Build a URL-safe slug from arbitrary text. */
function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return $text !== '' ? $text : 'item';
}

/** Validate an email address. */
function is_email(string $email): bool
{
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}
