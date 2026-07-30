<?php
/**
 * Admin account data-access, validation, and helper layer.
 * Powers the admin "My Account" page (profile, email, password changes).
 */

/** Full admin row by id, or null. */
function admin_get(int $id): ?array
{
    $st = db()->prepare(
        'SELECT id, username, display_name, email, last_login_at, password_changed_at, created_at
           FROM admins WHERE id = :id LIMIT 1'
    );
    $st->execute([':id' => $id]);
    $row = $st->fetch();
    return $row ?: null;
}

/** Verify a plaintext password against the stored hash for an admin. */
function admin_verify_password(int $id, string $password): bool
{
    if ($password === '') {
        return false;
    }
    $st = db()->prepare('SELECT password_hash FROM admins WHERE id = :id LIMIT 1');
    $st->execute([':id' => $id]);
    $hash = $st->fetchColumn();
    return $hash !== false && password_verify($password, (string) $hash);
}

/** Is $username taken by an admin other than $exceptId? */
function admin_username_exists(string $username, int $exceptId): bool
{
    $st = db()->prepare('SELECT COUNT(*) FROM admins WHERE username = :u AND id != :id');
    $st->execute([':u' => $username, ':id' => $exceptId]);
    return (int) $st->fetchColumn() > 0;
}

/** Update username + display name. */
function admin_update_profile(int $id, string $username, string $displayName): void
{
    db()->prepare(
        "UPDATE admins SET username = :u, display_name = :d, updated_at = datetime('now') WHERE id = :id"
    )->execute([':u' => $username, ':d' => $displayName, ':id' => $id]);
}

/** Update email address. */
function admin_update_email(int $id, string $email): void
{
    db()->prepare(
        "UPDATE admins SET email = :e, updated_at = datetime('now') WHERE id = :id"
    )->execute([':e' => $email, ':id' => $id]);
}

/** Update password (expects an already-hashed value). */
function admin_update_password(int $id, string $passwordHash): void
{
    db()->prepare(
        "UPDATE admins SET password_hash = :h, password_changed_at = datetime('now'), updated_at = datetime('now') WHERE id = :id"
    )->execute([':h' => $passwordHash, ':id' => $id]);
}

/* -------------------------------------------------------------
 *  Validation helpers
 * ----------------------------------------------------------- */

/** Validate a username. Returns error string or '' if valid. */
function account_validate_username(string $username): string
{
    if ($username === '') {
        return 'Username is required.';
    }
    if (mb_strlen($username) < 3 || mb_strlen($username) > 30) {
        return 'Username must be between 3 and 30 characters.';
    }
    if (!preg_match('/^[A-Za-z0-9._-]+$/', $username)) {
        return 'Username may only contain letters, numbers, dots, hyphens and underscores.';
    }
    return '';
}

/** Validate password strength. Returns error string or '' if valid. */
function account_validate_password(string $pw): string
{
    if (mb_strlen($pw) < 8) {
        return 'Password must be at least 8 characters.';
    }
    if (!preg_match('/[A-Z]/', $pw) || !preg_match('/[a-z]/', $pw) || !preg_match('/[0-9]/', $pw)) {
        return 'Password must include an uppercase letter, a lowercase letter, and a number.';
    }
    return '';
}

/**
 * Build a user-facing notice explaining OTP delivery status.
 * Used by the admin account page after requesting email/password change codes.
 */
function account_delivery_notice(array $res, string $email): string
{
    if (!empty($res['sent'])) {
        return 'A verification code has been sent to ' . $email . '. It expires in 5 minutes.';
    }
    $reason = $res['reason'] ?? '';
    if ($reason === 'smtp_disabled') {
        $why = 'Email sending is turned off in Settings, so the code was written to data/logs/mail.log.';
    } elseif ($reason === 'smtp_unconfigured') {
        $why = 'SMTP is not fully configured in Settings, so the code was written to data/logs/mail.log.';
    } else {
        $detail = $res['error'] ?? '';
        $why = 'The email could not be sent' . ($detail !== '' ? ' (' . $detail . ')' : '')
             . '. The code was written to data/logs/mail.log so you can still continue.';
    }
    return 'A verification code was generated (expires in 5 minutes). ' . $why;
}
