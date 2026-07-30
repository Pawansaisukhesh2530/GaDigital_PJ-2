<?php
/**
 * Secure image upload helpers.
 * Validates MIME type, extension and size; stores files with unique names
 * inside assets/uploads/projects/. Framework-free, PHP 8+.
 *
 * Public API:
 *   upload_store(array $file, string $type = 'img'): array  // ['ok','filename','error']
 *   upload_public(string $filename): string                 // root-relative URL
 *   upload_delete(string $filename): void
 *   upload_copy_existing(string $sourcePath): ?string        // for the seeder
 */

/** Ensure the upload directory exists and return its path. */
function upload_dir(): string
{
    if (!is_dir(UPLOAD_DIR)) {
        @mkdir(UPLOAD_DIR, 0775, true);
    }
    return UPLOAD_DIR;
}

function upload_ext_for_mime(string $mime): ?string
{
    return ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;
}

function upload_unique_name(string $type, string $ext): string
{
    $type = preg_replace('/[^a-z0-9]+/', '', strtolower($type)) ?: 'img';
    return $type . '-' . date('Ymd') . '-' . bin2hex(random_bytes(6)) . '.' . $ext;
}

/**
 * Validate + store one uploaded image ($_FILES entry).
 * $type is used only as a filename prefix ('cover', 'gallery', ...).
 * Returns ['ok' => bool, 'filename' => string|null, 'error' => string|null].
 */
function upload_store(array $file, string $type = 'img'): array
{
    $fail = fn(string $m) => ['ok' => false, 'filename' => null, 'error' => $m];

    if (!isset($file['error']) || is_array($file['error'])) {
        return $fail('Invalid upload.');
    }
    switch ($file['error']) {
        case UPLOAD_ERR_OK:            break;
        case UPLOAD_ERR_NO_FILE:       return $fail('No file was uploaded.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:     return $fail('The file is too large.');
        default:                       return $fail('Upload failed. Please try again.');
    }
    if (($file['size'] ?? 0) > UPLOAD_MAX_BYTES) {
        return $fail('Image exceeds the maximum size of ' . round(UPLOAD_MAX_BYTES / 1048576) . ' MB.');
    }
    if (!is_uploaded_file($file['tmp_name'])) {
        return $fail('Invalid upload source.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']) ?: '';
    if (!in_array($mime, UPLOAD_ALLOWED_MIME, true)) {
        return $fail('Only JPG, PNG and WEBP images are allowed.');
    }
    $ext = upload_ext_for_mime($mime);
    if ($ext === null || @getimagesize($file['tmp_name']) === false) {
        return $fail('The file is not a valid image.');
    }

    $name = upload_unique_name($type, $ext);
    $dest = upload_dir() . '/' . $name;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return $fail('Could not save the uploaded file.');
    }
    @chmod($dest, 0644);
    return ['ok' => true, 'filename' => $name, 'error' => null];
}

function upload_copy_existing(string $sourcePath, string $type = 'seed'): ?string
{
    if (!is_file($sourcePath)) {
        return null;
    }
    $ext = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
    if ($ext === 'jpeg') {
        $ext = 'jpg';
    }
    if (!in_array($ext, UPLOAD_ALLOWED_EXT, true)) {
        $ext = 'jpg';
    }
    $name = upload_unique_name($type, $ext);
    $dest = upload_dir() . '/' . $name;
    return copy($sourcePath, $dest) ? $name : null;
}

/** Safely delete an uploaded file by name (kept within the uploads dir). */
function upload_delete(string $filename): void
{
    $filename = basename($filename);
    if ($filename === '') {
        return;
    }
    $path = UPLOAD_DIR . '/' . $filename;
    if (is_file($path)) {
        @unlink($path);
    }
}

/**
 * Root-relative public URL for an uploaded file, e.g.
 * "assets/uploads/projects/cover-20260101-abc.jpg".
 * Frontend pages (at project root) use it directly; admin pages prepend $ROOT.
 */
function upload_public(string $filename): string
{
    return UPLOAD_URL . '/' . basename($filename);
}
