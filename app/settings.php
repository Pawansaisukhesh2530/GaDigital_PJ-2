<?php
/**
 * Settings data-access layer (key/value in the `settings` table).
 * Powers the admin Settings page and the public $SITE / $SOCIAL arrays.
 */

/** All settings as an associative map [key => value]. Cached per request. */
function settings_all(bool $fresh = false): array
{
    static $cache = null;
    if ($cache !== null && !$fresh) {
        return $cache;
    }
    $cache = [];
    try {
        foreach (db()->query('SELECT setting_key, setting_value FROM settings')->fetchAll() as $row) {
            $cache[$row['setting_key']] = $row['setting_value'];
        }
    } catch (Throwable $e) {
        $cache = [];   // DB unavailable -> callers fall back to defaults
    }
    return $cache;
}

/** A single setting value, or $default if unset/empty-missing. */
function setting(string $key, string $default = ''): string
{
    $all = settings_all();
    return array_key_exists($key, $all) ? (string) $all[$key] : $default;
}

/**
 * Upsert a set of [key => value] pairs. Unlisted keys are untouched.
 * Uses prepared statements inside a transaction.
 */
function settings_update(array $pairs): void
{
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $st = $pdo->prepare(
            'INSERT INTO settings (setting_key, setting_value) VALUES (:k, :v)
             ON CONFLICT(setting_key) DO UPDATE SET setting_value = :v'
        );
        foreach ($pairs as $k => $v) {
            $st->execute([':k' => $k, ':v' => (string) $v]);
        }
        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
    settings_all(true);   // refresh cache
}

/** Derive a tel: href from a display phone number. */
function settings_phone_href(string $phone): string
{
    $href = preg_replace('/[^0-9+]/', '', $phone);
    return $href ?: '';
}

/** Build the public $SITE array from settings, backed by hardcoded defaults. */
function settings_site(array $defaults = []): array
{
    $s = settings_all();
    $get = fn(string $k, string $d = '') => (isset($s[$k]) && $s[$k] !== '') ? $s[$k] : $d;

    return [
        'name'       => $get('company_name', $defaults['name'] ?? 'Nivi Homes'),
        'email'      => $get('email',        $defaults['email'] ?? ''),
        'phone'      => $get('phone',        $defaults['phone'] ?? ''),
        'phone_href' => $get('phone_href',   $defaults['phone_href'] ?? settings_phone_href($get('phone', $defaults['phone'] ?? ''))),
        'phone2'     => $get('phone2',       $defaults['phone2'] ?? ''),
        'address'    => $get('address',      $defaults['address'] ?? ''),
        'hours'      => $get('hours',        $defaults['hours'] ?? ''),
        'map'        => $get('map_url',      $defaults['map'] ?? ''),
        'map_embed'  => $get('map_embed',    $defaults['map_embed'] ?? ''),
    ];
}

/** Build the public $SOCIAL array from settings, backed by hardcoded defaults. */
function settings_social(array $defaults = []): array
{
    $s = settings_all();
    $get = fn(string $k, string $d = '') => (isset($s[$k]) && $s[$k] !== '') ? $s[$k] : $d;

    return [
        'facebook'  => $get('facebook',  $defaults['facebook'] ?? ''),
        'instagram' => $get('instagram', $defaults['instagram'] ?? ''),
        'twitter'   => $get('twitter',   $defaults['twitter'] ?? ''),
        'pinterest' => $get('pinterest', $defaults['pinterest'] ?? ''),
        'youtube'   => $get('youtube',   $defaults['youtube'] ?? ''),
        'linkedin'  => $get('linkedin',  $defaults['linkedin'] ?? ''),
    ];
}

/**
 * Validate + normalise the settings form submission.
 * Returns ['data' => [key=>value...], 'errors' => [field=>msg...]].
 */
function settings_validate(array $post): array
{
    $errors = [];
    $t = fn(string $k) => trim((string) ($post[$k] ?? ''));

    $data = [
        'company_name'  => $t('company_name'),
        'email'         => $t('email'),
        'contact_email' => $t('contact_email'),
        'phone'         => $t('phone'),
        'phone2'        => $t('phone2'),
        'address'       => $t('address'),
        'hours'         => $t('hours'),
        'map_url'       => $t('map_url'),
        'map_embed'     => $t('map_embed'),
        'facebook'      => $t('facebook'),
        'instagram'     => $t('instagram'),
        'twitter'       => $t('twitter'),
        'linkedin'      => $t('linkedin'),
        'youtube'       => $t('youtube'),
        'pinterest'     => $t('pinterest'),
        // ---- Email / SMTP settings ----
        'mail_from_name'     => $t('mail_from_name'),
        'smtp_host'          => $t('smtp_host'),
        'smtp_port'          => $t('smtp_port'),
        'smtp_encryption'    => strtolower($t('smtp_encryption')),
        'smtp_username'      => $t('smtp_username'),
        'mail_reply_to_mode' => $t('mail_reply_to_mode'),
        'smtp_enabled'       => !empty($post['smtp_enabled']) ? '1' : '0',
    ];

    // SMTP password: only overwrite when a new value is supplied, so it is
    // never wiped by saving the form (and never displayed back to the user).
    $pw = (string) ($post['smtp_password'] ?? '');
    if ($pw !== '') {
        $data['smtp_password'] = $pw;
    }

    // Required
    if ($data['company_name'] === '') {
        $errors['company_name'] = 'Company name is required.';
    }
    if ($data['email'] === '') {
        $errors['email'] = 'Company email is required.';
    } elseif (!is_email($data['email'])) {
        $errors['email'] = 'Enter a valid email address.';
    }
    if ($data['contact_email'] !== '' && !is_email($data['contact_email'])) {
        $errors['contact_email'] = 'Enter a valid email address.';
    }
    if ($data['phone'] === '') {
        $errors['phone'] = 'Primary phone number is required.';
    } elseif (mb_strlen($data['phone']) > 40) {
        $errors['phone'] = 'Phone number is too long.';
    }
    if ($data['phone2'] !== '' && mb_strlen($data['phone2']) > 40) {
        $errors['phone2'] = 'Phone number is too long.';
    }

    // Social URL fields (optional, but must be valid http/https URLs when present)
    $urlFields = [
        'facebook' => 'Facebook URL', 'instagram' => 'Instagram URL', 'twitter' => 'X (Twitter) URL',
        'linkedin' => 'LinkedIn URL', 'youtube' => 'YouTube URL', 'pinterest' => 'Pinterest URL',
    ];
    foreach ($urlFields as $f => $label) {
        if ($data[$f] !== '' && !preg_match('#^https?://#i', $data[$f])) {
            $errors[$f] = $label . ' must start with http:// or https://';
        }
    }

    // ---- Google Maps URL (address link — opens in a new tab) ----
    // Accepts maps.app.goo.gl / goo.gl/maps share links, maps.google.com,
    // and google.com/maps links.
    if ($data['map_url'] !== '') {
        if (!preg_match('#^https?://#i', $data['map_url'])) {
            $errors['map_url'] = 'Google Maps URL must start with http:// or https://';
        } elseif (!preg_match('#(google\.[a-z.]+/maps|maps\.google\.|maps\.app\.goo\.gl|goo\.gl/maps)#i', $data['map_url'])) {
            $errors['map_url'] = 'Enter a Google Maps link (e.g. maps.google.com, google.com/maps, or a maps.app.goo.gl share link).';
        }
    }

    // ---- Google Maps EMBED URL (iframe src — must be embeddable) ----
    // Google only renders embed URLs inside an iframe: the "Embed a map" form
    // (…/maps/embed?pb=…) or a query URL with &output=embed. Plain map links or
    // maps.app.goo.gl share links are refused by Google (blank map), so reject
    // them here with guidance instead of storing a broken value.
    if ($data['map_embed'] !== '') {
        if (!preg_match('#^https?://#i', $data['map_embed'])) {
            $errors['map_embed'] = 'Google Maps embed URL must start with http:// or https://';
        } elseif (!preg_match('#(google\.[a-z.]+/maps/embed|[?&]output=embed)#i', $data['map_embed'])) {
            $errors['map_embed'] = 'This is not an embeddable map link. In Google Maps choose Share → Embed a map, then copy the URL inside src="…" (it contains /maps/embed?pb=…). A maps.app.goo.gl share link will not display.';
        }
    }

    // ---- Email / SMTP validation ----
    if ($data['smtp_encryption'] !== '' && !in_array($data['smtp_encryption'], ['tls', 'ssl'], true)) {
        $errors['smtp_encryption'] = 'Encryption must be TLS, SSL, or None.';
    }
    if ($data['mail_reply_to_mode'] !== '' && !in_array($data['mail_reply_to_mode'], ['visitor', 'company'], true)) {
        $errors['mail_reply_to_mode'] = 'Invalid reply-to option.';
    }
    if ($data['smtp_port'] !== '') {
        if (!ctype_digit($data['smtp_port']) || (int) $data['smtp_port'] < 1 || (int) $data['smtp_port'] > 65535) {
            $errors['smtp_port'] = 'Port must be a number between 1 and 65535.';
        }
    }
    if ($data['smtp_username'] !== '' && mb_strlen($data['smtp_username']) > 190) {
        $errors['smtp_username'] = 'SMTP username is too long.';
    }
    // When SMTP is switched on, host and port are required to send anything.
    if ($data['smtp_enabled'] === '1') {
        if ($data['smtp_host'] === '') {
            $errors['smtp_host'] = 'SMTP host is required when email sending is enabled.';
        }
        if ($data['smtp_port'] === '') {
            $errors['smtp_port'] = 'SMTP port is required when email sending is enabled.';
        }
    }

    // Derived
    $data['phone_href'] = settings_phone_href($data['phone']);

    return ['data' => $data, 'errors' => $errors];
}
