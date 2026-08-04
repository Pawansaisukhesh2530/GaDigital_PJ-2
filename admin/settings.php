<?php
/** Admin - Site Settings. Centralised company info, contact, social & maps. */
require __DIR__ . '/init.php';
require_admin();

$PAGE       = 'settings';
$PAGE_TITLE = 'Settings';
$ADMIN_BASE = '';

$errors = [];
// Current values from the DB (used to pre-fill the form; overridden by
// posted values when a save fails validation so entries aren't lost).
$v = settings_all();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = (string) ($_POST['action'] ?? 'save');

    if ($action === 'send_test') {
        // Uses the currently SAVED settings (admin should save first).
        $res = mail_send_test();
        if ($res['sent']) {
            flash_set('flash_success', 'Test email sent successfully to ' . $res['to'] . '.');
        } else {
            $why = $res['error'] !== '' ? $res['error'] : mail_reason_label($res['reason']);
            flash_set('flash_error', 'Test email failed: ' . $why);
        }
        redirect('settings.php');
    }

    // Default: save settings.
    $res    = settings_validate($_POST);
    $v      = $res['data'];
    $errors = $res['errors'];

    if (!$errors) {
        try {
            settings_update($v);
            flash_set('flash_success', 'Settings saved. Changes are now live across the website.');
        } catch (Throwable $e) {
            error_log('Settings save failed: ' . $e->getMessage());
            flash_set('flash_error', 'Could not save settings. The database may be read-only or locked. Please try again.');
        }
        redirect('settings.php');
    } else {
        flash_set('flash_error', 'Please correct the highlighted fields.');
    }
}

/** Small helper: current field value (POST re-fill or DB). */
$val = fn(string $k) => (string) ($v[$k] ?? '');

require __DIR__ . '/partials/header.php';
?>
<div class="page-head">
    <h2 class="page-title">Site Settings</h2>
</div>

<form method="post" action="settings.php" class="card-form" novalidate>
    <?php echo csrf_field(); ?>
    <input type="hidden" name="action" value="save">

    <h3 class="section-label">Company Information</h3>
    <div class="form-grid">
        <div class="field">
            <label for="company_name">Company Name <span class="req">*</span></label>
            <input type="text" id="company_name" name="company_name" value="<?php echo e($val('company_name')); ?>" required>
            <?php if (isset($errors['company_name'])): ?><span class="field-err"><?php echo e($errors['company_name']); ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="email">Company Email <span class="req">*</span></label>
            <input type="email" id="email" name="email" value="<?php echo e($val('email')); ?>" required>
            <?php if (isset($errors['email'])): ?><span class="field-err"><?php echo e($errors['email']); ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="phone">Primary Phone <span class="req">*</span></label>
            <input type="text" id="phone" name="phone" value="<?php echo e($val('phone')); ?>" required>
            <?php if (isset($errors['phone'])): ?><span class="field-err"><?php echo e($errors['phone']); ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="phone2">Secondary Phone</label>
            <input type="text" id="phone2" name="phone2" value="<?php echo e($val('phone2')); ?>">
            <?php if (isset($errors['phone2'])): ?><span class="field-err"><?php echo e($errors['phone2']); ?></span><?php endif; ?>
        </div>
        <div class="field field-wide">
            <label for="address">Company Address</label>
            <input type="text" id="address" name="address" value="<?php echo e($val('address')); ?>">
        </div>
        <div class="field field-wide">
            <label for="hours">Business Hours</label>
            <input type="text" id="hours" name="hours" value="<?php echo e($val('hours')); ?>">
            <span class="hint">e.g. Monday - Friday, 07:00 AM to 5:00 PM</span>
        </div>
    </div>

    <h3 class="section-label">Google Maps</h3>
    <div class="form-grid">
        <div class="field field-wide">
            <label for="map_url">Google Maps URL</label>
            <input type="text" id="map_url" name="map_url" value="<?php echo e($val('map_url')); ?>">
            <span class="hint">Opened when a visitor clicks the address. Use a normal Google Maps link or a share link (e.g. <code>https://maps.app.goo.gl/&hellip;</code>).</span>
            <?php if (isset($errors['map_url'])): ?><span class="field-err"><?php echo e($errors['map_url']); ?></span><?php endif; ?>
        </div>
        <div class="field field-wide">
            <label for="map_embed">Google Maps Embed URL</label>
            <input type="text" id="map_embed" name="map_embed" value="<?php echo e($val('map_embed')); ?>">
            <span class="hint">Used for the interactive map on the Contact page. In Google Maps open <strong>Share &rarr; Embed a map</strong> and paste the URL from inside <code>src="&hellip;"</code> (it contains <code>/maps/embed?pb=</code>). A <code>maps.app.goo.gl</code> share link will <strong>not</strong> display here.</span>
            <?php if (isset($errors['map_embed'])): ?><span class="field-err"><?php echo e($errors['map_embed']); ?></span><?php endif; ?>
        </div>
    </div>

    <h3 class="section-label">Social Media</h3>
    <div class="form-grid">
        <?php
        $socials = [
            'facebook'  => 'Facebook URL',
            'instagram' => 'Instagram URL',
            'twitter'   => 'X (Twitter) URL',
            'linkedin'  => 'LinkedIn URL',
            'youtube'   => 'YouTube URL',
            'pinterest' => 'Pinterest URL',
        ];
        foreach ($socials as $k => $label):
        ?>
        <div class="field">
            <label for="<?php echo $k; ?>"><?php echo e($label); ?></label>
            <input type="text" id="<?php echo $k; ?>" name="<?php echo $k; ?>" value="<?php echo e($val($k)); ?>">
            <?php if (isset($errors[$k])): ?><span class="field-err"><?php echo e($errors[$k]); ?></span><?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <h3 class="section-label">Email Settings</h3>
    <div class="form-grid">
        <div class="field">
            <label for="contact_email">Enquiry Email</label>
            <input type="email" id="contact_email" name="contact_email" value="<?php echo e($val('contact_email')); ?>">
            <span class="hint">All contact-form submissions are sent here. Falls back to the company email if blank.</span>
            <?php if (isset($errors['contact_email'])): ?><span class="field-err"><?php echo e($errors['contact_email']); ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="mail_from_name">From Name</label>
            <input type="text" id="mail_from_name" name="mail_from_name" value="<?php echo e($val('mail_from_name')); ?>">
            <span class="hint">Sender name shown on outgoing mail. Defaults to the company name.</span>
        </div>
        <div class="field field-check">
            <label class="checkbox">
                <input type="checkbox" name="smtp_enabled" value="1" <?php echo $val('smtp_enabled') === '1' ? 'checked' : ''; ?>>
                Enable email sending (SMTP)
            </label>
            <span class="hint">When off, enquiries are still saved but no email is sent.</span>
        </div>
        <div class="field">
            <label for="mail_reply_to_mode">Reply-To</label>
            <select id="mail_reply_to_mode" name="mail_reply_to_mode">
                <?php $rtm = $val('mail_reply_to_mode') ?: 'visitor'; ?>
                <option value="visitor" <?php echo $rtm === 'visitor' ? 'selected' : ''; ?>>Visitor's email (reply goes to the enquirer)</option>
                <option value="company" <?php echo $rtm === 'company' ? 'selected' : ''; ?>>Company email</option>
            </select>
            <?php if (isset($errors['mail_reply_to_mode'])): ?><span class="field-err"><?php echo e($errors['mail_reply_to_mode']); ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="smtp_host">SMTP Host</label>
            <input type="text" id="smtp_host" name="smtp_host" value="<?php echo e($val('smtp_host')); ?>" placeholder="smtp.gmail.com">
            <?php if (isset($errors['smtp_host'])): ?><span class="field-err"><?php echo e($errors['smtp_host']); ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="smtp_port">SMTP Port</label>
            <input type="text" id="smtp_port" name="smtp_port" value="<?php echo e($val('smtp_port')); ?>" placeholder="587">
            <span class="hint">587 for TLS, 465 for SSL.</span>
            <?php if (isset($errors['smtp_port'])): ?><span class="field-err"><?php echo e($errors['smtp_port']); ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="smtp_encryption">Encryption</label>
            <select id="smtp_encryption" name="smtp_encryption">
                <?php $enc = strtolower($val('smtp_encryption')); ?>
                <option value="tls" <?php echo $enc === 'tls' ? 'selected' : ''; ?>>TLS</option>
                <option value="ssl" <?php echo $enc === 'ssl' ? 'selected' : ''; ?>>SSL</option>
                <option value="" <?php echo $enc === '' ? 'selected' : ''; ?>>None</option>
            </select>
            <?php if (isset($errors['smtp_encryption'])): ?><span class="field-err"><?php echo e($errors['smtp_encryption']); ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="smtp_username">SMTP Username</label>
            <input type="text" id="smtp_username" name="smtp_username" value="<?php echo e($val('smtp_username')); ?>" autocomplete="off">
            <span class="hint">Usually the full email address of the sending account.</span>
            <?php if (isset($errors['smtp_username'])): ?><span class="field-err"><?php echo e($errors['smtp_username']); ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="smtp_password">SMTP Password</label>
            <input type="password" id="smtp_password" name="smtp_password" value="" autocomplete="new-password" placeholder="<?php echo $val('smtp_password') !== '' ? '******** (unchanged)' : 'not set'; ?>">
            <span class="hint">
                <?php echo $val('smtp_password') !== '' ? 'A password is saved. Leave blank to keep it, or type a new one to replace it.' : 'For Gmail use an App Password (not your login password).'; ?>
            </span>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Settings</button>
        <a class="btn btn-ghost" href="dashboard.php">Cancel</a>
    </div>
</form>

<form method="post" action="settings.php" class="card-form" style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="action" value="send_test">
    <div>
        <strong>Send Test Email</strong>
        <div class="hint">Sends a test message to the Enquiry Email using the currently <em>saved</em> settings. Save first if you just made changes.</div>
    </div>
    <button type="submit" class="btn btn-ghost">Send Test Email</button>
</form>
<?php require __DIR__ . '/partials/footer.php'; ?>
