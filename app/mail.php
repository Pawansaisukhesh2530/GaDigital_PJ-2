<?php
/**
 * Outbound email via PHPMailer + SMTP.
 *
 * All SMTP configuration is read DYNAMICALLY from the settings table
 * (managed in Admin > Settings > Email Settings). Nothing is hardcoded.
 *
 * Design goal: email is best-effort. The enquiry is ALWAYS saved first;
 * if SMTP is disabled, unconfigured, PHPMailer is missing, or sending
 * fails, we log the reason and return a failure — the caller still shows
 * the visitor a success message so no enquiry is ever lost.
 */

require_once __DIR__ . '/settings.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as MailException;

/** Append a line to the mail log (best-effort). Never logs secrets. */
function mail_log(string $message): void
{
    $dir = DATA_PATH . '/logs';
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    @file_put_contents($dir . '/mail.log', '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL, FILE_APPEND);
}

/** Load the 3 PHPMailer classes if present. Returns true if available. */
function mail_phpmailer_available(): bool
{
    if (class_exists(PHPMailer::class)) {
        return true;
    }
    $src = APP_PATH . '/lib/PHPMailer/src';
    foreach (['Exception.php', 'PHPMailer.php', 'SMTP.php'] as $f) {
        if (!is_file($src . '/' . $f)) {
            return false;
        }
        require_once $src . '/' . $f;
    }
    return class_exists(PHPMailer::class);
}

/**
 * Resolve the live mail configuration from the settings table.
 * Falls back to the legacy app/config.mail.php array only for values the
 * database does not yet define (smooth migration).
 */
function mail_settings(): array
{
    $s   = function_exists('settings_all') ? settings_all() : [];
    $leg = $GLOBALS['MAIL_CONFIG'] ?? [];
    $get = fn(string $k, string $d = '') => (isset($s[$k]) && $s[$k] !== '') ? (string) $s[$k] : $d;

    $host     = $get('smtp_host', (string) ($leg['host'] ?? ''));
    $username = $get('smtp_username', (string) ($leg['username'] ?? ''));
    $enabled  = array_key_exists('smtp_enabled', $s)
        ? ($s['smtp_enabled'] === '1')
        : !empty($leg['enabled']);

    // Enquiry recipient: dedicated enquiry email, else company email, else fallback.
    $enquiryTo = $get('contact_email', $get('email', ADMIN_EMAIL_FALLBACK));

    return [
        'enabled'       => $enabled,
        'host'          => $host,
        'port'          => (int) $get('smtp_port', (string) ($leg['port'] ?? 587)),
        'encryption'    => $get('smtp_encryption', (string) ($leg['encryption'] ?? 'tls')),
        'username'      => $username,
        'password'      => $get('smtp_password', (string) ($leg['password'] ?? '')),
        // Gmail/most providers require the From to be the authenticated account.
        'from_email'    => $username !== '' ? $username : $get('email', (string) ($leg['from_email'] ?? 'no-reply@localhost')),
        'from_name'     => $get('mail_from_name', $get('company_name', (string) ($leg['from_name'] ?? 'Nivi Homes'))),
        'reply_to_mode' => $get('mail_reply_to_mode', 'visitor'),   // 'visitor' | 'company'
        'enquiry_to'    => $enquiryTo,
        'company_email' => $get('email', ''),
    ];
}

/** Human-readable label for a failure reason code. */
function mail_reason_label(string $reason): string
{
    return [
        'smtp_disabled'     => 'Email sending is turned off in Settings.',
        'smtp_unconfigured' => 'SMTP host is not configured.',
        'phpmailer_missing' => 'Mailer library is missing on the server.',
        'no_recipient'      => 'No recipient email is configured.',
        'send_failed'       => 'The mail server rejected the message.',
    ][$reason] ?? $reason;
}

/**
 * Why a configuration is not ready to send. Returns '' when ready.
 */
function mail_not_ready_reason(array $cfg): string
{
    if (!$cfg['enabled']) {
        return 'smtp_disabled';
    }
    if ($cfg['host'] === '') {
        return 'smtp_unconfigured';
    }
    if (!mail_phpmailer_available()) {
        return 'phpmailer_missing';
    }
    return '';
}

/**
 * Build a configured PHPMailer instance (SMTP + envelope) from $cfg.
 * Throws only on PHPMailer construction issues (caught by callers).
 */
function mail_make(array $cfg): PHPMailer
{
    mail_phpmailer_available();
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = $cfg['host'];
    $mail->Port       = (int) $cfg['port'];
    $mail->CharSet    = 'UTF-8';
    $mail->Timeout    = 30;          // seconds — generous for cloud environments
    $mail->SMTPKeepAlive = false;

    if ($cfg['username'] !== '') {
        $mail->SMTPAuth = true;
        $mail->Username = $cfg['username'];
        $mail->Password = $cfg['password'];
    } else {
        $mail->SMTPAuth = false;
    }
    if (!empty($cfg['encryption'])) {
        $mail->SMTPSecure = $cfg['encryption'];   // 'tls' | 'ssl'
    } else {
        $mail->SMTPAutoTLS = false;
    }

    // Ensure PHP uses the system CA bundle for TLS verification.
    // Required in Docker containers where the default path may not be set.
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer'       => true,
            'verify_peer_name'  => true,
            'allow_self_signed' => false,
            'cafile'            => '/etc/ssl/certs/ca-certificates.crt',
        ],
    ];

    $mail->setFrom($cfg['from_email'] ?: 'no-reply@localhost', $cfg['from_name'] ?: 'Website');
    return $mail;
}

/** Plain-text fallback body for an enquiry. */
function mail_enquiry_text(array $en): string
{
    return implode("\n", [
        'New Contact Form Submission',
        '',
        'Name:    ' . ($en['name'] ?? ''),
        'Email:   ' . ($en['email'] ?? ''),
        'Phone:   ' . ($en['phone'] ?? '-'),
        'Message: ' . ($en['message'] ?? ''),
        '',
        'Submitted On: ' . ($en['created_at'] ?? date('Y-m-d H:i:s')),
        'IP Address:   ' . ($en['ip_address'] ?? ($en['ip'] ?? '-')),
    ]);
}

/** Professional HTML body for an enquiry (all values escaped). */
function mail_enquiry_html(array $en): string
{
    $row = function (string $label, string $value): string {
        return '<tr>'
            . '<td style="padding:10px 14px;background:#f7f7f4;border:1px solid #eee;font-weight:600;color:#282828;width:150px;vertical-align:top;">' . e($label) . '</td>'
            . '<td style="padding:10px 14px;border:1px solid #eee;color:#54595F;">' . nl2br(e($value)) . '</td>'
            . '</tr>';
    };
    $ip = (string) ($en['ip_address'] ?? ($en['ip'] ?? '-'));

    return '<!DOCTYPE html><html><body style="margin:0;padding:0;background:#f2f2f2;font-family:Arial,Helvetica,sans-serif;">'
        . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f2f2f2;padding:24px 0;"><tr><td align="center">'
        . '<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:8px;overflow:hidden;border:1px solid #e6e6e6;">'
        . '<tr><td style="background:#282828;padding:20px 24px;color:#D9CE83;font-size:18px;font-weight:700;">Nivi Homes</td></tr>'
        . '<tr><td style="padding:24px;">'
        . '<h2 style="margin:0 0 6px;color:#282828;font-size:18px;">New Contact Form Submission</h2>'
        . '<p style="margin:0 0 18px;color:#7A7A7A;font-size:13px;">You have received a new enquiry from the website.</p>'
        . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font-size:14px;">'
        . $row('Name', (string) ($en['name'] ?? ''))
        . $row('Email', (string) ($en['email'] ?? ''))
        . $row('Phone', (string) ($en['phone'] ?? '-'))
        . $row('Message', (string) ($en['message'] ?? ''))
        . $row('Submitted On', (string) ($en['created_at'] ?? date('Y-m-d H:i:s')))
        . $row('IP Address', $ip)
        . '</table>'
        . '</td></tr>'
        . '<tr><td style="background:#f7f7f4;padding:14px 24px;color:#9a9a9a;font-size:12px;">This message was sent automatically from the Nivi Homes contact form.</td></tr>'
        . '</table></td></tr></table></body></html>';
}

/**
 * Send the admin notification for a new enquiry (HTML + reply-to).
 * @return array ['sent' => bool, 'reason' => string, 'error' => string]
 */
function mail_send_enquiry(array $enquiry): array
{
    $cfg = mail_settings();
    $to  = trim($cfg['enquiry_to']) ?: ADMIN_EMAIL_FALLBACK;
    $id  = $enquiry['id'] ?? '?';

    $reason = mail_not_ready_reason($cfg);
    if ($reason !== '') {
        mail_log("Enquiry #{$id} saved; email skipped ({$reason}).");
        return ['sent' => false, 'reason' => $reason, 'error' => ''];
    }

    try {
        $mail = mail_make($cfg);
        $mail->addAddress($to);

        // Reply-To: visitor (default) so admin can reply directly, or company.
        if ($cfg['reply_to_mode'] === 'company' && $cfg['company_email'] !== '') {
            $mail->addReplyTo($cfg['company_email'], $cfg['from_name']);
        } elseif (!empty($enquiry['email'])) {
            $mail->addReplyTo($enquiry['email'], $enquiry['name'] ?? '');
        }

        $mail->isHTML(true);
        $mail->Subject = "New Website Enquiry \xE2\x80\x93 Nivi Homes";   // en-dash
        $mail->Body    = mail_enquiry_html($enquiry);
        $mail->AltBody = mail_enquiry_text($enquiry);

        $mail->send();
        mail_log("Sent enquiry #{$id} notification to {$to}.");
        return ['sent' => true, 'reason' => 'ok', 'error' => ''];
    } catch (\Throwable $ex) {
        $err = isset($mail) ? ($mail->ErrorInfo ?: $ex->getMessage()) : $ex->getMessage();
        mail_log("Send FAILED for enquiry #{$id} to {$to}: " . $err);
        return ['sent' => false, 'reason' => 'send_failed', 'error' => $err];
    }
}

/**
 * Generic plain-text sender used for admin OTP delivery and other
 * transactional messages. Never throws.
 * @return array ['sent' => bool, 'reason' => string, 'error' => string]
 */
function mail_send_raw(string $to, string $subject, string $body): array
{
    $cfg = mail_settings();
    if ($to === '') {
        return ['sent' => false, 'reason' => 'no_recipient', 'error' => ''];
    }
    $reason = mail_not_ready_reason($cfg);
    if ($reason !== '') {
        return ['sent' => false, 'reason' => $reason, 'error' => ''];
    }

    try {
        $mail = mail_make($cfg);
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->send();
        mail_log("Sent transactional email to {$to} (subject: {$subject}).");
        return ['sent' => true, 'reason' => 'ok', 'error' => ''];
    } catch (\Throwable $ex) {
        $err = isset($mail) ? ($mail->ErrorInfo ?: $ex->getMessage()) : $ex->getMessage();
        mail_log("Transactional send FAILED to {$to}: " . $err);
        return ['sent' => false, 'reason' => 'send_failed', 'error' => $err];
    }
}

/**
 * Send a test email using the currently SAVED settings, to the configured
 * enquiry email. Surfaces the PHPMailer error for the admin UI.
 * Includes SMTP debug output in the mail log for troubleshooting.
 * @return array ['sent' => bool, 'reason' => string, 'error' => string, 'to' => string]
 */
function mail_send_test(): array
{
    $cfg = mail_settings();
    $to  = trim($cfg['enquiry_to']) ?: ADMIN_EMAIL_FALLBACK;

    $reason = mail_not_ready_reason($cfg);
    if ($reason !== '') {
        return ['sent' => false, 'reason' => $reason, 'error' => '', 'to' => $to];
    }

    // Log diagnostic info (never log the password).
    mail_log("=== TEST EMAIL ATTEMPT ===");
    mail_log("Host: {$cfg['host']} | Port: {$cfg['port']} | Enc: {$cfg['encryption']} | User: {$cfg['username']}");
    mail_log("PHP OpenSSL: " . (extension_loaded('openssl') ? 'YES (' . OPENSSL_VERSION_TEXT . ')' : 'NO'));
    mail_log("CA bundle: " . (is_file('/etc/ssl/certs/ca-certificates.crt') ? 'present' : 'MISSING'));

    // Test DNS resolution
    $dnsResult = @dns_get_record($cfg['host'], DNS_A | DNS_AAAA);
    if ($dnsResult === false || empty($dnsResult)) {
        mail_log("DNS lookup FAILED for {$cfg['host']}");
    } else {
        $ips = array_map(fn($r) => $r['ip'] ?? ($r['ipv6'] ?? '?'), $dnsResult);
        mail_log("DNS resolved {$cfg['host']} -> " . implode(', ', $ips));
    }

    try {
        $mail = mail_make($cfg);

        // Capture SMTP debug output to a buffer (level 2 = client+server)
        $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
        $debugOutput = '';
        $mail->Debugoutput = function ($str, $level) use (&$debugOutput) {
            // Strip credentials from debug output
            if (stripos($str, 'PASSWORD') !== false || stripos($str, 'AUTH') !== false) {
                $str = preg_replace('/(?:USER|PASS|AUTH\s+\S+)\s+.*/i', '$0 [REDACTED]', $str);
            }
            $debugOutput .= trim($str) . "\n";
        };

        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Nivi Homes - SMTP test email';
        $mail->Body    = '<p style="font-family:Arial,sans-serif;color:#282828;">'
            . 'This is a test email from your Nivi Homes admin panel. '
            . 'If you can read this, your SMTP settings are working correctly.</p>'
            . '<p style="font-family:Arial,sans-serif;color:#7A7A7A;font-size:13px;">Sent: ' . e(date('Y-m-d H:i:s')) . '</p>';
        $mail->AltBody = 'This is a test email from your Nivi Homes admin panel. SMTP settings are working.';
        $mail->send();
        mail_log("Test email sent to {$to}.");
        if ($debugOutput !== '') {
            mail_log("SMTP Debug:\n" . $debugOutput);
        }
        return ['sent' => true, 'reason' => 'ok', 'error' => '', 'to' => $to];
    } catch (\Throwable $ex) {
        $err = isset($mail) ? ($mail->ErrorInfo ?: $ex->getMessage()) : $ex->getMessage();
        mail_log("Test email FAILED to {$to}: " . $err);
        if (!empty($debugOutput)) {
            mail_log("SMTP Debug:\n" . $debugOutput);
        }

        // Detect Render free-tier SMTP port block
        if (stripos($err, 'Network is unreachable') !== false
            || stripos($err, 'Could not connect') !== false) {
            $err .= ' | HINT: Render free-tier blocks outbound SMTP (ports 25/465/587). '
                   . 'Upgrade to a paid instance type to enable SMTP, or use an HTTP-based email API.';
            mail_log("DIAGNOSIS: Likely Render free-tier SMTP port block. Ports 25, 465, 587 are firewalled on free plans.");
        }

        return ['sent' => false, 'reason' => 'send_failed', 'error' => $err, 'to' => $to];
    }
}
