<?php
/**
 * Admin login page.
 * - Already authenticated users are sent to the dashboard.
 * - Handles POST: CSRF check, validation, auth attempt.
 */
require __DIR__ . '/init.php';

// Authenticated users should never see the login page.
if (auth_check()) {
    redirect(admin_url('dashboard.php'));
}

$error    = flash_get('login_error', '');
$notice   = isset($_GET['logout']) ? 'You have been logged out successfully.' : '';
$identity = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $identity = input('identity');
    $password = (string) ($_POST['password'] ?? '');

    if ($identity === '' || $password === '') {
        $error = 'Please enter your username/email and password.';
    } elseif (auth_attempt($identity, $password)) {
        redirect(admin_url('dashboard.php'));
    } else {
        $error = 'Invalid credentials. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Sign in &middot; Nivi Homes Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(admin_url('assets/admin.css')); ?>">
</head>
<body>
<div class="login-wrap">
    <div class="login-card">
        <div class="login-logo"><strong>NIVI<span>.</span> Homes</strong></div>
        <h1>Admin Sign In</h1>
        <p class="sub">Enter your credentials to access the panel</p>

        <?php if ($notice): ?>
            <div class="alert alert-success"><?php echo e($notice); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo e($error); ?></div>
        <?php endif; ?>

        <form method="post" action="<?php echo e(admin_url('index.php')); ?>" novalidate>
            <?php echo csrf_field(); ?>
            <div class="field">
                <label for="identity">Username or Email</label>
                <input type="text" id="identity" name="identity" value="<?php echo e($identity); ?>" autocomplete="username" autofocus required>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        </form>
    </div>
</div>
</body>
</html>
