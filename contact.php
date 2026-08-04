<?php
$CURRENT_PAGE = 'contact';
$PAGE_TITLE = 'Contact Us | Nivi Homes';

// Backend (session ON for CSRF + rate limiting)
require_once __DIR__ . '/app/bootstrap.php';
require_once __DIR__ . '/app/enquiries.php';
require_once __DIR__ . '/app/mail.php';

$errors    = [];
$formError = '';
$old       = ['fullName' => '', 'email' => '', 'phone' => '', 'message' => ''];
$sent      = isset($_GET['sent']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = [
        'fullName' => (string) ($_POST['fullName'] ?? ''),
        'email'    => (string) ($_POST['email'] ?? ''),
        'phone'    => (string) ($_POST['phone'] ?? ''),
        'message'  => (string) ($_POST['message'] ?? ''),
    ];

    if (!csrf_verify($_POST['_csrf'] ?? null)) {
        $formError = 'Your session expired. Please try again.';
    } elseif (trim((string) ($_POST['website'] ?? '')) !== '') {
        redirect('contact.php?sent=1');
    } elseif (!empty($_SESSION['last_enquiry_at']) && (time() - $_SESSION['last_enquiry_at']) < 20) {
        $formError = 'You just sent a message. Please wait a moment before sending another.';
    } else {
        $res    = enquiry_validate($_POST);
        $errors = $res['errors'];
        if (!$errors) {
            $ip  = substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45);
            $id  = enquiry_create($res['data'], $ip);
            $_SESSION['last_enquiry_at'] = time();
            mail_send_enquiry(array_merge($res['data'], ['id' => $id, 'created_at' => date('Y-m-d H:i:s')]));
            redirect('contact.php?sent=1');
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<!-- ===================== PAGE BANNER ===================== -->
<?php
$BANNER_TITLE = 'Contact Us';
$BANNER_CRUMB = '<a href="index.php">Home</a> &gt; Contact';
$BANNER_IMG = asset('images/banners/contact-banner.webp');
require __DIR__ . '/includes/banner.php';
?>

<!-- ===================== CONTACT SPLIT LAYOUT ===================== -->
<section class="ct-section">
    <div class="ct-split">
        <!-- LEFT: Info panel -->
        <div class="ct-info">
            <div class="ct-info-inner">
                <h1 class="ct-heading">Get in touch</h1>
                <p class="ct-intro">Have a project in mind, or just want to talk through the possibilities? Our office is open and ready to hear from you.</p>

                <div class="ct-list">
                    <div class="ct-item">
                        <span class="ct-icon"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
                        <div>
                            <span class="ct-label">Office</span>
                            <a href="<?php echo $SITE['map']; ?>" target="_blank" rel="noopener"><?php echo $SITE['address']; ?></a>
                        </div>
                    </div>
                    <div class="ct-item">
                        <span class="ct-icon"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span>
                        <div>
                            <span class="ct-label">Hours</span>
                            <span><?php echo $SITE['hours']; ?></span>
                        </div>
                    </div>
                    <div class="ct-item">
                        <span class="ct-icon"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3 19.5 19.5 0 01-6-6 19.8 19.8 0 01-3-8.6A2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .4 1.9.7 2.8a2 2 0 01-.5 2.1L8.1 9.9a16 16 0 006 6l1.3-1.2a2 2 0 012.1-.5c.9.3 1.8.6 2.8.7a2 2 0 011.7 2z"/></svg></span>
                        <div>
                            <span class="ct-label">Phone</span>
                            <a href="tel:<?php echo $SITE['phone_href']; ?>"><?php echo $SITE['phone']; ?></a>
                        </div>
                    </div>
                    <div class="ct-item">
                        <span class="ct-icon"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 6l10 7 10-7"/></svg></span>
                        <div>
                            <span class="ct-label">Email</span>
                            <a href="mailto:<?php echo $SITE['email']; ?>"><?php echo $SITE['email']; ?></a>
                        </div>
                    </div>
                </div>

                <div class="ct-socials">
                    <a href="<?php echo $SOCIAL['facebook']; ?>" target="_blank" rel="noopener" aria-label="Facebook">f</a>
                    <a href="<?php echo $SOCIAL['instagram']; ?>" target="_blank" rel="noopener" aria-label="Instagram">ig</a>
                    <a href="<?php echo $SOCIAL['twitter']; ?>" target="_blank" rel="noopener" aria-label="X">x</a>
                    <a href="<?php echo $SOCIAL['pinterest']; ?>" target="_blank" rel="noopener" aria-label="Pinterest">p</a>
                    <a href="<?php echo $SOCIAL['youtube']; ?>" target="_blank" rel="noopener" aria-label="YouTube">yt</a>
                </div>

                <div class="ct-map-thumb">
                    <iframe title="Nivi Homes office" src="<?php echo e($SITE['map_embed']); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>

        <!-- RIGHT: Form panel -->
        <div class="ct-form-panel">
            <div class="ct-form-inner">
                <h2 class="ct-form-heading">Send a Message</h2>
                <p class="ct-form-subtitle">Fill in the form and our team will get back to you shortly.</p>

                <form id="contactForm" method="post" action="contact.php" novalidate>
                    <?php echo csrf_field(); ?>
                    <div class="hp-field" aria-hidden="true" style="position:absolute!important;left:-9999px!important;top:auto;width:1px;height:1px;overflow:hidden;">
                        <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    </div>

                    <div class="ct-form-row">
                        <div class="ct-field">
                            <label for="fullName">Full Name *</label>
                            <input type="text" id="fullName" name="fullName" placeholder="Your Name" value="<?php echo e($old['fullName']); ?>" required>
                            <?php if (isset($errors['fullName'])): ?><span class="form-error"><?php echo e($errors['fullName']); ?></span><?php endif; ?>
                        </div>
                        <div class="ct-field">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" placeholder="Your Email Address" value="<?php echo e($old['email']); ?>" required>
                            <?php if (isset($errors['email'])): ?><span class="form-error"><?php echo e($errors['email']); ?></span><?php endif; ?>
                        </div>
                    </div>

                    <div class="ct-field">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" placeholder="Your Phone Number" value="<?php echo e($old['phone']); ?>">
                        <?php if (isset($errors['phone'])): ?><span class="form-error"><?php echo e($errors['phone']); ?></span><?php endif; ?>
                    </div>

                    <div class="ct-field">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" maxlength="180" placeholder="Enter your message..."><?php echo e($old['message']); ?></textarea>
                        <div class="ct-char-count">0 / 180</div>
                        <?php if (isset($errors['message'])): ?><span class="form-error"><?php echo e($errors['message']); ?></span><?php endif; ?>
                    </div>

                    <button type="submit" class="ct-submit">Send Message</button>

                    <?php
                    $noteClass = $sent ? ' success' : ($formError || $errors ? ' error' : '');
                    $noteText  = $sent
                        ? 'Thank you! Your message has been sent. We will be in touch shortly.'
                        : ($formError !== '' ? $formError : ($errors ? 'Please correct the highlighted fields.' : ''));
                    ?>
                    <?php if ($noteText): ?>
                    <p class="form-note<?php echo $noteClass; ?>" role="status" aria-live="polite"><?php echo e($noteText); ?></p>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
