<?php
/**
 * Application configuration & path constants.
 * Loaded by app/bootstrap.php before anything else.
 */

// ---- Paths ----
define('BASE_PATH', dirname(__DIR__));                 // project root (nivi-homes/)
define('APP_PATH',  BASE_PATH . '/app');
define('DATA_PATH', BASE_PATH . '/data');
define('DB_PATH',   DATA_PATH . '/nivihomes.sqlite');

// Upload locations (filesystem + public URL prefix relative to project root)
define('UPLOAD_DIR', BASE_PATH . '/assets/uploads/projects');
define('UPLOAD_URL', 'assets/uploads/projects');       // used with the asset base

// ---- Upload rules ----
define('UPLOAD_MAX_BYTES', 5 * 1024 * 1024);           // 5 MB per image
const UPLOAD_ALLOWED_EXT  = ['jpg', 'jpeg', 'png', 'webp'];
const UPLOAD_ALLOWED_MIME = ['image/jpeg', 'image/png', 'image/webp'];

// ---- Session ----
define('SESSION_NAME', 'nivi_session');

// ---- Fallback admin notification email (overridden by settings.contact_email) ----
define('ADMIN_EMAIL_FALLBACK', 'hemachandra@gadigitalsolutions.com');

// ---- SMTP / mail config (legacy file-based fallback for migration) ----
$__smtp_file = APP_PATH . '/config.mail.php';
$GLOBALS['MAIL_CONFIG'] = is_file($__smtp_file)
    ? (require $__smtp_file)
    : ['enabled' => false];
