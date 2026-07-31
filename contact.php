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
        // Honeypot filled -> silently treat as done (likely a bot); nothing saved.
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
            redirect('contact.php?sent=1');   // Post/Redirect/Get - blocks duplicate submits
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

<!-- ===================== OUR OFFICE LOCATION ===================== -->
<section class="section contact-section">
    <div class="container">
        <div class="text-center" data-anim="fadeIn">
            <h2 class="section-title gold">Our Office Location</h2>
        </div>

        <div class="contact-grid" style="margin-top:40px;">
            <!-- Form -->
            <div class="contact-form-wrap" data-anim="fadeInLeft">
                <form id="contactForm" method="post" action="contact.php" novalidate>
                    <?php echo csrf_field(); ?>
                    <div class="hp-field" aria-hidden="true" style="position:absolute!important;left:-9999px!important;top:auto;width:1px;height:1px;overflow:hidden;">
                        <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    </div>
                    <div class="form-group">
                        <label for="fullName">Full Name *</label>
                        <input type="text" id="fullName" name="fullName" placeholder="Your Name" value="<?php echo e($old['fullName']); ?>" required>
                        <?php if (isset($errors['fullName'])): ?><span class="form-error"><?php echo e($errors['fullName']); ?></span><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" placeholder="Your Email Address" value="<?php echo e($old['email']); ?>" required>
                        <?php if (isset($errors['email'])): ?><span class="form-error"><?php echo e($errors['email']); ?></span><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" placeholder="Your Phone Number" value="<?php echo e($old['phone']); ?>">
                        <?php if (isset($errors['phone'])): ?><span class="form-error"><?php echo e($errors['phone']); ?></span><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" maxlength="180" placeholder="Enter your message..."><?php echo e($old['message']); ?></textarea>
                        <div class="char-count">0 / 180</div>
                        <?php if (isset($errors['message'])): ?><span class="form-error"><?php echo e($errors['message']); ?></span><?php endif; ?>
                    </div>
                    <button type="submit" class="btn-send">Send Message</button>
                    <?php
                    $noteClass = $sent ? ' success' : ($formError || $errors ? ' error' : '');
                    $noteText  = $sent
                        ? 'Thank you! Your message has been sent. We will be in touch shortly.'
                        : ($formError !== '' ? $formError : ($errors ? 'Please correct the highlighted fields and try again.' : ''));
                    ?>
                    <p class="form-note<?php echo $noteClass; ?>" role="status" aria-live="polite"><?php echo e($noteText); ?></p>
                </form>
            </div>

            <!-- Office Hours -->
            <div class="contact-info-card" data-anim="fadeInRight">
                <h3>Office Hours</h3>
                <div class="contact-info-item">
                    <span class="ci-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
                    <p><a href="<?php echo $SITE['map']; ?>" target="_blank" rel="noopener"><?php echo $SITE['address']; ?></a></p>
                </div>
                <div class="contact-info-item">
                    <span class="ci-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span>
                    <p><?php echo $SITE['hours']; ?></p>
                </div>
                <div class="contact-info-item">
                    <span class="ci-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3 19.5 19.5 0 01-6-6 19.8 19.8 0 01-3-8.6A2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .4 1.9.7 2.8a2 2 0 01-.5 2.1L8.1 9.9a16 16 0 006 6l1.3-1.2a2 2 0 012.1-.5c.9.3 1.8.6 2.8.7a2 2 0 011.7 2z"/></svg></span>
                    <p><a href="tel:<?php echo $SITE['phone_href']; ?>"><?php echo $SITE['phone']; ?></a></p>
                </div>
                <div class="contact-info-item">
                    <span class="ci-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 6l10 7 10-7"/></svg></span>
                    <p><a href="mailto:<?php echo $SITE['email']; ?>"><?php echo $SITE['email']; ?></a></p>
                </div>

                <ul class="social-list">
                    <li><a href="<?php echo $SOCIAL['facebook']; ?>" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M13.5 21v-7h2.4l.4-2.8h-2.8V9.4c0-.8.2-1.4 1.4-1.4h1.5V5.5c-.3 0-1.2-.1-2.2-.1-2.2 0-3.7 1.3-3.7 3.8v2H8.2V14h2.7v7h2.6z"/></svg></a></li>
                    <li><a href="<?php echo $SOCIAL['instagram']; ?>" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 1.8.3 2.2.4.5.2.9.5 1.3.9.4.4.7.8.9 1.3.2.4.4 1 .4 2.2.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.3 1.8-.4 2.2-.2.5-.5.9-.9 1.3-.4.4-.8.7-1.3.9-.4.2-1 .4-2.2.4-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-1.8-.3-2.2-.4-.5-.2-.9-.5-1.3-.9-.4-.4-.7-.8-.9-1.3-.2-.4-.4-1-.4-2.2C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c.1-1.2.3-1.8.4-2.2.2-.5.5-.9.9-1.3.4-.4.8-.7 1.3-.9.4-.2 1-.4 2.2-.4C8.4 2.2 8.8 2.2 12 2.2zm0 4.9a4.9 4.9 0 100 9.8 4.9 4.9 0 000-9.8zm0 8.1a3.2 3.2 0 110-6.4 3.2 3.2 0 010 6.4zm6.3-8.3a1.15 1.15 0 11-2.3 0 1.15 1.15 0 012.3 0z"/></svg></a></li>
                    <li><a href="<?php echo $SOCIAL['twitter']; ?>" target="_blank" rel="noopener" aria-label="X"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M17.5 3h3l-6.6 7.5L21.7 21H16l-4.3-5.6L6.8 21H3.7l7-8-7.2-10H9l3.9 5.2L17.5 3z"/></svg></a></li>
                    <li><a href="<?php echo $SOCIAL['pinterest']; ?>" target="_blank" rel="noopener" aria-label="Pinterest"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 2C6.5 2 2 6.5 2 12c0 4.1 2.5 7.7 6 9.2-.1-.8-.2-2 0-2.9l1.2-5s-.3-.6-.3-1.5c0-1.4.8-2.4 1.8-2.4.9 0 1.3.6 1.3 1.4 0 .9-.5 2.1-.8 3.3-.2.9.5 1.7 1.4 1.7 1.7 0 2.9-2.2 2.9-4.8 0-2-1.3-3.5-3.8-3.5-2.8 0-4.5 2.1-4.5 4.4 0 .8.2 1.4.6 1.8.2.2.2.3.1.5l-.2.9c-.1.3-.3.4-.5.3-1.4-.6-2-2.1-2-3.8 0-2.8 2.4-6.2 7-6.2 3.7 0 6.2 2.7 6.2 5.6 0 3.8-2.1 6.6-5.2 6.6-1 0-2-.6-2.3-1.2l-.6 2.5c-.2.8-.8 1.9-1.2 2.5.9.3 1.8.4 2.8.4 5.5 0 10-4.5 10-10S17.5 2 12 2z"/></svg></a></li>
                    <li><a href="<?php echo $SOCIAL['youtube']; ?>" target="_blank" rel="noopener" aria-label="YouTube"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M23 12s0-3.3-.4-4.8c-.2-.9-.9-1.5-1.7-1.7C19.4 5 12 5 12 5s-7.4 0-8.9.4c-.8.2-1.5.9-1.7 1.8C1 8.7 1 12 1 12s0 3.3.4 4.8c.2.9.9 1.5 1.7 1.7 1.5.5 8.9.5 8.9.5s7.4 0 8.9-.4c.8-.2 1.5-.9 1.7-1.7.4-1.6.4-4.9.4-4.9zM9.7 15V9l6.2 3-6.2 3z"/></svg></a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- ===================== MAP ===================== -->
<section class="map-wrap" data-anim="fadeIn">
    <div class="container">
        <iframe title="Nivi Homes office location" src="<?php echo e($SITE['map_embed']); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
