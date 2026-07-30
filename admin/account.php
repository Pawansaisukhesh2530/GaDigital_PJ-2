<?php
require __DIR__ . '/init.php';
require_admin();

$PAGE       = 'account';
$PAGE_TITLE = 'My Account';
$ADMIN_BASE = '';

$me  = auth_user();
$uid = (int) $me['id'];

$fresh = admin_get($uid);
if ($fresh) {
    $_SESSION['admin_username']     = $fresh['username'];
    $_SESSION['admin_display_name'] = $fresh['display_name'];
    $_SESSION['admin_email']        = $fresh['email'];
    $me = [
        'id'           => $uid,
        'username'     => $fresh['username'],
        'display_name' => $fresh['display_name'],
        'email'        => $fresh['email'],
    ];
}

$errP = [];  // profile
$errE = [];  // email
$errW = [];  // password

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = (string) ($_POST['action'] ?? '');

    /* -------- Profile: username + display name -------- */
    if ($action === 'profile_save') {
        $username    = trim((string) ($_POST['username'] ?? ''));
        $displayName = trim((string) ($_POST['display_name'] ?? ''));
        $current     = (string) ($_POST['current_password'] ?? '');

        if (!admin_verify_password($uid, $current)) {
            $errP['current_password'] = 'Current password is incorrect.';
        }
        if ($e = account_validate_username($username)) {
            $errP['username'] = $e;
        } elseif (admin_username_exists($username, $uid)) {
            $errP['username'] = 'That username is already taken.';
        }
        if ($displayName === '') {
            $errP['display_name'] = 'Display name is required.';
        } elseif (mb_strlen($displayName) > 60) {
            $errP['display_name'] = 'Display name is too long.';
        }

        if (!$errP) {
            admin_update_profile($uid, $username, $displayName);
            $_SESSION['admin_username']     = $username;
            $_SESSION['admin_display_name'] = $displayName;
            flash_set('flash_success', 'Profile updated.');
            redirect('account.php');
        }
    }

    /* -------- Email change: step 1 (request code) -------- */
    elseif ($action === 'email_request') {
        $newEmail = trim((string) ($_POST['new_email'] ?? ''));
        $current  = (string) ($_POST['current_password'] ?? '');

        if (!admin_verify_password($uid, $current)) {
            $errE['current_password'] = 'Current password is incorrect.';
        }
        if ($newEmail === '') {
            $errE['new_email'] = 'Enter the new email address.';
        } elseif (!is_email($newEmail)) {
            $errE['new_email'] = 'Enter a valid email address.';
        } elseif (strcasecmp($newEmail, (string) $me['email']) === 0) {
            $errE['new_email'] = 'That is already your current email address.';
        }

        if (!$errE) {
            $code = otp_generate($uid, 'email_change', $newEmail);
            $res  = otp_deliver($me, 'email_change', $code, 'email', (string) $me['email']);
            flash_set('flash_success', account_delivery_notice($res, (string) $me['email'])
                . ' Enter it below to confirm changing your email to ' . $newEmail . '.');
            redirect('account.php');
        }
    }

    /* -------- Email change: step 2 (confirm code) -------- */
    elseif ($action === 'email_confirm') {
        $code = (string) ($_POST['otp_code'] ?? '');
        $res  = otp_verify($uid, 'email_change', $code);
        if (!$res['ok']) {
            $errE['otp_code'] = $res['error'];
        } else {
            $newEmail = trim($res['payload']);
            if ($newEmail !== '' && is_email($newEmail)) {
                admin_update_email($uid, $newEmail);
                $_SESSION['admin_email'] = $newEmail;
                flash_set('flash_success', 'Email address updated to ' . $newEmail . '.');
            } else {
                flash_set('flash_error', 'The pending email was invalid. Please start again.');
            }
            redirect('account.php');
        }
    }

    elseif ($action === 'email_cancel') {
        otp_cancel($uid, 'email_change');
        flash_set('flash_success', 'Email change cancelled.');
        redirect('account.php');
    }

    /* -------- Password change: step 1 (request code) -------- */
    elseif ($action === 'password_request') {
        $current = (string) ($_POST['current_password'] ?? '');
        if (!admin_verify_password($uid, $current)) {
            $errW['current_password'] = 'Current password is incorrect.';
        }
        if (!$errW) {
            $code = otp_generate($uid, 'password_change');
            $res  = otp_deliver($me, 'password_change', $code, 'email');
            flash_set('flash_success', account_delivery_notice($res, (string) $me['email'])
                . ' Enter it below with your new password.');
            redirect('account.php');
        }
    }

    /* -------- Password change: step 2 (confirm code + new password) -------- */
    elseif ($action === 'password_confirm') {
        $code    = (string) ($_POST['otp_code'] ?? '');
        $new     = (string) ($_POST['new_password'] ?? '');
        $confirm = (string) ($_POST['confirm_password'] ?? '');

        // Validate the new password FIRST so a live OTP is only consumed
        // once everything else is valid.
        if ($e = account_validate_password($new)) {
            $errW['new_password'] = $e;
        }
        if ($confirm !== $new) {
            $errW['confirm_password'] = 'Passwords do not match.';
        }

        if (!$errW) {
            $res = otp_verify($uid, 'password_change', $code);
            if (!$res['ok']) {
                $errW['otp_code'] = $res['error'];
            } else {
                admin_update_password($uid, password_hash($new, PASSWORD_DEFAULT));
                flash_set('flash_success', 'Password changed successfully. It is now active.');
                redirect('account.php');
            }
        }
    }

    elseif ($action === 'password_cancel') {
        otp_cancel($uid, 'password_change');
        flash_set('flash_success', 'Password change cancelled.');
        redirect('account.php');
    }
}

// Fresh state for rendering.
$admin        = admin_get($uid);
$emailPending = otp_pending_info($uid, 'email_change');
$pwPending    = otp_pending($uid, 'password_change');

// If the user just submitted a request step that FAILED validation (e.g. a
// wrong current password), keep them on the request form with the error
// visible instead of jumping to the code-entry stage of a leftover code.
$postedAction     = $_SERVER['REQUEST_METHOD'] === 'POST' ? (string) ($_POST['action'] ?? '') : '';
$showEmailConfirm = $emailPending && !($postedAction === 'email_request' && $errE);
$showPwConfirm    = $pwPending    && !($postedAction === 'password_request' && $errW);

$fmt = function (?string $ts): string {
    if (!$ts) {
        return 'Never';
    }
    $t = strtotime($ts);
    return $t ? date('j M Y, g:i A', $t) : 'Never';
};

require __DIR__ . '/partials/header.php';
?>
<div class="page-head">
    <h2 class="page-title">My Account</h2>
</div>

<div class="account-grid">

    <!-- Profile information -->
    <section class="card-form">
        <h3 class="section-label">Profile Information</h3>
        <form method="post" action="account.php" novalidate>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="profile_save">
            <div class="form-grid">
                <div class="field">
                    <label for="username">Username <span class="req">*</span></label>
                    <input type="text" id="username" name="username" value="<?php echo e($_POST['username'] ?? $admin['username']); ?>" required>
                    <?php if (isset($errP['username'])): ?><span class="field-err"><?php echo e($errP['username']); ?></span><?php endif; ?>
                </div>
                <div class="field">
                    <label for="display_name">Display Name <span class="req">*</span></label>
                    <input type="text" id="display_name" name="display_name" value="<?php echo e($_POST['display_name'] ?? $admin['display_name']); ?>" required>
                    <?php if (isset($errP['display_name'])): ?><span class="field-err"><?php echo e($errP['display_name']); ?></span><?php endif; ?>
                </div>
                <div class="field field-wide">
                    <label for="p_current">Current Password <span class="req">*</span></label>
                    <input type="password" id="p_current" name="current_password" autocomplete="current-password" required>
                    <span class="hint">Confirm your password to save profile changes.</span>
                    <?php if (isset($errP['current_password'])): ?><span class="field-err"><?php echo e($errP['current_password']); ?></span><?php endif; ?>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Profile</button>
            </div>
        </form>

        <div class="meta-list">
            <div><span>Email</span><strong><?php echo e($admin['email']); ?></strong></div>
            <div><span>Last login</span><strong><?php echo e($fmt($admin['last_login_at'])); ?></strong></div>
            <div><span>Password last changed</span><strong><?php echo e($fmt($admin['password_changed_at'])); ?></strong></div>
        </div>
    </section>

    <!-- Change email -->
    <section class="card-form">
        <h3 class="section-label">Change Email Address</h3>

        <?php if (!$showEmailConfirm): ?>
        <form method="post" action="account.php" novalidate>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="email_request">
            <div class="field">
                <label for="new_email">New Email Address <span class="req">*</span></label>
                <input type="email" id="new_email" name="new_email" value="<?php echo e($_POST['new_email'] ?? ''); ?>" required>
                <?php if (isset($errE['new_email'])): ?><span class="field-err"><?php echo e($errE['new_email']); ?></span><?php endif; ?>
            </div>
            <div class="field">
                <label for="e_current">Current Password <span class="req">*</span></label>
                <input type="password" id="e_current" name="current_password" autocomplete="current-password" required>
                <span class="hint">A verification code will be emailed to your current address (<?php echo e($admin['email']); ?>).</span>
                <?php if (isset($errE['current_password'])): ?><span class="field-err"><?php echo e($errE['current_password']); ?></span><?php endif; ?>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Send Verification Code</button>
            </div>
        </form>
        <?php else: ?>
        <p class="hint" style="margin-bottom:14px;">
            A code was sent to <strong><?php echo e($admin['email']); ?></strong> to confirm changing your email to
            <strong><?php echo e($emailPending['payload']); ?></strong>.
        </p>
        <form method="post" action="account.php" novalidate>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="email_confirm">
            <div class="field">
                <label for="e_otp">Verification Code <span class="req">*</span></label>
                <input type="text" id="e_otp" name="otp_code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" required>
                <?php if (isset($errE['otp_code'])): ?><span class="field-err"><?php echo e($errE['otp_code']); ?></span><?php endif; ?>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Confirm New Email</button>
            </div>
        </form>
        <form method="post" action="account.php" style="margin-top:8px;">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="email_cancel">
            <button type="submit" class="btn btn-ghost">Cancel</button>
        </form>
        <?php endif; ?>
    </section>

    <!-- Change password -->
    <section class="card-form">
        <h3 class="section-label">Change Password</h3>

        <?php if (!$showPwConfirm): ?>
        <form method="post" action="account.php" novalidate>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="password_request">
            <div class="field">
                <label for="w_current">Current Password <span class="req">*</span></label>
                <input type="password" id="w_current" name="current_password" autocomplete="current-password" required>
                <span class="hint">We'll email a one-time code to <?php echo e($admin['email']); ?> before you set a new password.</span>
                <?php if (isset($errW['current_password'])): ?><span class="field-err"><?php echo e($errW['current_password']); ?></span><?php endif; ?>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Send Verification Code</button>
            </div>
        </form>
        <?php else: ?>
        <p class="hint" style="margin-bottom:14px;">A verification code was sent to <strong><?php echo e($admin['email']); ?></strong>. Enter it with your new password.</p>
        <form method="post" action="account.php" novalidate>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="password_confirm">
            <div class="field">
                <label for="w_otp">Verification Code <span class="req">*</span></label>
                <input type="text" id="w_otp" name="otp_code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" required>
                <?php if (isset($errW['otp_code'])): ?><span class="field-err"><?php echo e($errW['otp_code']); ?></span><?php endif; ?>
            </div>
            <div class="field">
                <label for="new_password">New Password <span class="req">*</span></label>
                <input type="password" id="new_password" name="new_password" autocomplete="new-password" required>
                <span class="hint">Minimum 8 characters, with upper &amp; lower case letters and a number.</span>
                <?php if (isset($errW['new_password'])): ?><span class="field-err"><?php echo e($errW['new_password']); ?></span><?php endif; ?>
            </div>
            <div class="field">
                <label for="confirm_password">Confirm New Password <span class="req">*</span></label>
                <input type="password" id="confirm_password" name="confirm_password" autocomplete="new-password" required>
                <?php if (isset($errW['confirm_password'])): ?><span class="field-err"><?php echo e($errW['confirm_password']); ?></span><?php endif; ?>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Password</button>
            </div>
        </form>
        <form method="post" action="account.php" style="margin-top:8px;">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="password_cancel">
            <button type="submit" class="btn btn-ghost">Cancel</button>
        </form>
        <?php endif; ?>
    </section>

</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
