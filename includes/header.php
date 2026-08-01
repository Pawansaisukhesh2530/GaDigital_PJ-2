<?php
require_once __DIR__ . '/config.php';
$CURRENT_PAGE = $CURRENT_PAGE ?? '';
$PAGE_TITLE   = $PAGE_TITLE ?? 'Nivi Homes';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Nivi Homes - custom homes, duplexes, knock down rebuilds and granny flats across NSW.">
    <title><?php echo htmlspecialchars($PAGE_TITLE); ?></title>

    <link rel="icon" type="image/svg+xml" href="<?php echo asset('images/logo/favicon.svg'); ?>">

    <!-- Poppins (matches original Elementor global typography) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">

    <?php $__v = '?v=2'; ?>
    <link rel="stylesheet" href="<?php echo asset('css/style.css') . $__v; ?>">
    <link rel="stylesheet" href="<?php echo asset('css/animations.css') . $__v; ?>">
    <link rel="stylesheet" href="<?php echo asset('css/responsive.css') . $__v; ?>">
</head>
<body class="page-<?php echo htmlspecialchars($CURRENT_PAGE); ?>">
<a class="skip-link" href="#main">Skip to content</a>

<?php require __DIR__ . '/navbar.php'; ?>

<main id="main">
