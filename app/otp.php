<?php
/**
 * One-time verification code (OTP) service for admin account changes.
 *
 * Design: channel-agnostic. Today only the 'email' channel is wired up
 * (via app/mail.php). An 'sms' channel (Twilio, MSG91, ...) can be added
 * by implementing otp_deliver_sms() and routing it in otp_deliver() —
 * no change to the verification logic is required.
 *
 * Codes are 6 digits, hashed at rest (password_hash), single-use, and
 * expire after OTP_TTL seconds. Requesting a new code for the same
 * admin+purpose invalidates any earlier unused codes.
 */

define('OTP_TTL', 5 * 60);   // 5 minutes
define('OTP_LENGTH', 6);

/**
 * Create + store a fresh OTP for an admin/purpose and return the
 * plaintext code (to be delivered). Previous unused codes for the same
 * purpose are invalidated first.
 *
 * @param string $payload optional data to carry (e.g. the pending new email)
 */
function otp_generate(int $adminId, string $purpose, string $payload = '', string $channel = 'email'): string
{
    $pdo = db();

    // Invalidate earlier unused codes for this admin+purpose.
    $pdo->prepare(
        "UPDATE admin_otps SET used_at = datetime('now')
          WHERE admin_id = :a AND purpose = :p AND used_at IS NULL"
    )->execute([':a' => $adminId, ':p' => $purpose]);

    $code = str_pad((string) random_int(0, 10 ** OTP_LENGTH - 1), OTP_LENGTH, '0', STR_PAD_LEFT);

    // expires_at is computed with SQLite's own clock (UTC) so it stays
    // consistent with created_at/used_at and with every expiry comparison.
    $pdo->prepare(
        "INSERT INTO admin_otps (admin_id, purpose, channel, code_hash, payload, expires_at)
         VALUES (:a, :p, :c, :h, :pl, datetime('now', :ttl))"
    )->execute([
        ':a'   => $adminId,
        ':p'   => $purpose,
        ':c'   => $channel,
        ':h'   => password_hash($code, PASSWORD_DEFAULT),
        ':pl'  => $payload,
        ':ttl' => '+' . OTP_TTL . ' seconds',
    ]);

    return $code;
}

/**
 * Verify a submitted code for an admin/purpose.
 * On success the code is marked used (single-use) and its row returned.
 *
 * @return array ['ok' => bool, 'error' => string, 'payload' => string]
 */
function otp_verify(int $adminId, string $purpose, string $code): array
{
    $code = trim($code);
    if ($code === '') {
        return ['ok' => false, 'error' => 'Enter the verification code.', 'payload' => ''];
    }

    // Expiry is evaluated by SQLite (UTC) to match how the row was stored.
    $st = db()->prepare(
        "SELECT *, CASE WHEN expires_at <= datetime('now') THEN 1 ELSE 0 END AS is_expired
           FROM admin_otps
          WHERE admin_id = :a AND purpose = :p AND used_at IS NULL
          ORDER BY id DESC LIMIT 1"
    );
    $st->execute([':a' => $adminId, ':p' => $purpose]);
    $row = $st->fetch();

    if (!$row) {
        return ['ok' => false, 'error' => 'No active verification code. Please request a new one.', 'payload' => ''];
    }
    if ((int) $row['is_expired'] === 1) {
        return ['ok' => false, 'error' => 'This code has expired. Please request a new one.', 'payload' => ''];
    }
    if (!password_verify($code, $row['code_hash'])) {
        return ['ok' => false, 'error' => 'Incorrect verification code.', 'payload' => ''];
    }

    // Success -> single-use: mark it consumed now.
    db()->prepare("UPDATE admin_otps SET used_at = datetime('now') WHERE id = :id")
        ->execute([':id' => $row['id']]);

    return ['ok' => true, 'error' => '', 'payload' => (string) $row['payload']];
}

/** Live (unused, unexpired) code row for admin+purpose, or null. */
function otp_pending_info(int $adminId, string $purpose): ?array
{
    $st = db()->prepare(
        "SELECT payload, expires_at FROM admin_otps
          WHERE admin_id = :a AND purpose = :p AND used_at IS NULL AND expires_at > datetime('now')
          ORDER BY id DESC LIMIT 1"
    );
    $st->execute([':a' => $adminId, ':p' => $purpose]);
    $row = $st->fetch();
    return $row ?: null;
}

/** True if there is a live (unused, unexpired) code for admin+purpose. */
function otp_pending(int $adminId, string $purpose): bool
{
    return otp_pending_info($adminId, $purpose) !== null;
}

/** Cancel (invalidate) any live codes for admin+purpose. */
function otp_cancel(int $adminId, string $purpose): void
{
    db()->prepare(
        "UPDATE admin_otps SET used_at = datetime('now')
          WHERE admin_id = :a AND purpose = :p AND used_at IS NULL"
    )->execute([':a' => $adminId, ':p' => $purpose]);
}

function otp_deliver(array $admin, string $purpose, string $code, string $channel = 'email', ?string $toOverride = null): array
{
    if ($channel === 'email') {
        return otp_deliver_email($admin, $purpose, $code, $toOverride);
    }
    // Future: if ($channel === 'sms') return otp_deliver_sms(...);
    return ['sent' => false, 'reason' => 'unsupported_channel'];
}

/** Email channel implementation. */
function otp_deliver_email(array $admin, string $purpose, string $code, ?string $toOverride = null): array
{
    $to   = $toOverride ?: (string) ($admin['email'] ?? '');
    $name = (string) ($admin['display_name'] ?? $admin['username'] ?? 'Administrator');

    $label = $purpose === 'email_change' ? 'email address change' : 'password change';
    $mins  = (int) (OTP_TTL / 60);

    $subject = 'Your Nivi Homes admin verification code';
    $body = implode("\n", [
        "Hi {$name},",
        '',
        "Use the following verification code to confirm your {$label}:",
        '',
        "    {$code}",
        '',
        "This code expires in {$mins} minutes and can be used once.",
        "If you did not request this, you can ignore this email and your account stays unchanged.",
    ]);

    $res = ['sent' => false, 'reason' => 'phpmailer_missing'];
    if (function_exists('mail_send_raw')) {
        $res = mail_send_raw($to, $subject, $body);
    }

    // Dev fallback: make the code retrievable when email is not delivered.
    if (!$res['sent'] && function_exists('mail_log')) {
        mail_log("OTP ({$purpose}) for admin '{$name}' <{$to}>: {$code} [email not delivered: {$res['reason']}]");
    }

    return $res;
}
