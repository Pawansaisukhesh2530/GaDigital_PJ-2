<?php
/**
 * One-time installer / migrator.
 * Run from CLI:  php database/install.php
 *
 * - Creates data/nivihomes.sqlite from schema.sql
 * - Seeds a default admin account (if none exists)
 * - Seeds site settings from the current hardcoded values (if empty)
 * Safe to re-run: it will not duplicate the admin or settings.
 */

require_once __DIR__ . '/../app/bootstrap.php';

$line = fn(string $s) => print($s . PHP_EOL);

// ---- 1. Ensure data directory ----
if (!is_dir(DATA_PATH)) {
    mkdir(DATA_PATH, 0775, true);
    $line('Created data directory: ' . DATA_PATH);
}

// ---- 2. Run schema ----
$schema = file_get_contents(__DIR__ . '/schema.sql');
if ($schema === false) {
    exit('Could not read schema.sql' . PHP_EOL);
}
db()->exec($schema);
$line('Schema applied to ' . DB_PATH);

// ---- 2b. Migrations for existing databases (add columns if missing) ----
$adminCols = [];
foreach (db()->query('PRAGMA table_info(admins)')->fetchAll() as $c) {
    $adminCols[$c['name']] = true;
}
$adminAdds = [
    'display_name'        => "ALTER TABLE admins ADD COLUMN display_name TEXT NOT NULL DEFAULT ''",
    'last_login_at'       => 'ALTER TABLE admins ADD COLUMN last_login_at TEXT',
    'password_changed_at' => 'ALTER TABLE admins ADD COLUMN password_changed_at TEXT',
];
foreach ($adminAdds as $col => $sql) {
    if (!isset($adminCols[$col])) {
        db()->exec($sql);
        $line("Migrated: added admins.$col");
    }
}
// Backfill display_name from username where empty.
db()->exec("UPDATE admins SET display_name = username WHERE display_name = '' OR display_name IS NULL");

// ---- 3. Seed default admin ----
$adminCount = (int) db()->query('SELECT COUNT(*) FROM admins')->fetchColumn();
if ($adminCount === 0) {
    $stmt = db()->prepare(
        'INSERT INTO admins (username, display_name, email, password_hash) VALUES (:u, :d, :e, :p)'
    );
    $stmt->execute([
        ':u' => 'admin',
        ':d' => 'Administrator',
        ':e' => 'hemachandra@gadigitalsolutions.com',
        ':p' => password_hash('Admin@123', PASSWORD_DEFAULT),
    ]);
    $line('Seeded default admin  ->  username: admin  password: Admin@123');
} else {
    $line('Admin already present - skipped admin seed.');
}

// ---- 4. Seed / top-up settings (idempotent: only inserts missing keys) ----
$legacy = $GLOBALS['MAIL_CONFIG'] ?? [];   // legacy app/config.mail.php (migration source)
$defaults = [
    'company_name' => 'Nivi Homes',
    'email'        => 'hemachandra@gadigitalsolutions.com',
    'phone'        => '+61 411 468 309',
    'phone_href'   => '+61411468309',
    'phone2'       => '',
    'address'      => '32/33-39 Veron st, Wentworthville, NSW, 2145',
    'hours'        => 'Monday - Friday, 07:00 AM to 5:00 PM',
    'map_url'      => 'https://www.google.com/maps?q=-33.80874252319336,150.9775848388672&z=17&hl=en',
    'map_embed'    => 'https://maps.google.com/maps?q=-33.80874252319336,150.9775848388672&z=16&output=embed',
    'facebook'     => 'https://www.facebook.com/profile.php?id=61560965706586',
    'instagram'    => 'https://www.instagram.com/nivihomes01/',
    'twitter'      => 'https://x.com/NiviHomes',
    'pinterest'    => 'https://in.pinterest.com/nivihomes/',
    'youtube'      => 'https://www.youtube.com/channel/UCWkJkbbuacTgj94lbQJOatA',
    'linkedin'     => '',
    'contact_email'=> 'hemachandra@gadigitalsolutions.com',   // enquiry recipient

    // ---- Email / SMTP settings (managed in Admin > Settings) ----
    // Migrated from the legacy config.mail.php if present, else blank so the
    // administrator configures everything from the panel (no hardcoded creds).
    'smtp_enabled'       => !empty($legacy['enabled']) ? '1' : '0',
    'smtp_host'          => (string) ($legacy['host'] ?? ''),
    'smtp_port'          => (string) ($legacy['port'] ?? '587'),
    'smtp_encryption'    => (string) ($legacy['encryption'] ?? 'tls'),
    'smtp_username'      => (string) ($legacy['username'] ?? ''),
    'smtp_password'      => (string) ($legacy['password'] ?? ''),
    'mail_from_name'     => (string) ($legacy['from_name'] ?? 'Nivi Homes'),
    'mail_reply_to_mode' => 'visitor',
];

$existing = [];
foreach (db()->query('SELECT setting_key FROM settings')->fetchAll() as $r) {
    $existing[$r['setting_key']] = true;
}
$stmt = db()->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (:k, :v)');
$added = 0;
foreach ($defaults as $k => $v) {
    if (!isset($existing[$k])) {
        $stmt->execute([':k' => $k, ':v' => $v]);
        $added++;
    }
}
$line($added > 0 ? "Seeded/topped-up {$added} settings." : 'Settings already present - nothing to add.');

$line('Install complete.');
