<?php
/**
 * Admin layout header. Opens the document + shell, includes sidebar & topbar.
 * Expects: $PAGE (active nav key), $PAGE_TITLE (topbar/browser title).
 * Guarded pages must call require_admin() before including this.
 */
$PAGE       = $PAGE ?? '';
$PAGE_TITLE = $PAGE_TITLE ?? 'Admin';
$ADMIN_BASE = $ADMIN_BASE ?? '';   // '' at /admin root, '../' inside subfolders
$__user     = auth_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo e($PAGE_TITLE); ?> &middot; Nivi Homes Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(admin_url('assets/admin.css')); ?>">
</head>
<body>
<div class="admin">
    <?php require __DIR__ . '/sidebar.php'; ?>
    <div class="admin-backdrop" id="adminBackdrop"></div>
    <div class="main">
        <?php require __DIR__ . '/topbar.php'; ?>
        <div class="content">
            <?php if ($__fs = flash_get('flash_success')): ?>
                <div class="alert alert-success"><?php echo e($__fs); ?></div>
            <?php endif; ?>
            <?php if ($__fe = flash_get('flash_error')): ?>
                <div class="alert alert-error"><?php echo e($__fe); ?></div>
            <?php endif; ?>
