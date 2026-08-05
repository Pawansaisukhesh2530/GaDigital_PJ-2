<?php
/**
 * Reusable CTA + Enquiry Form component.
 * Used on design-details pages and other detail pages.
 *
 * Expects (set before including):
 *   $CTA_FORM_ACTION - form action URL (e.g., 'design-details.php?d=akira-21')
 *   $old             - array of old form values ['fullName', 'email', 'phone', 'message']
 *   $errors          - array of field error messages
 *   $sent            - bool, whether message was sent successfully
 *   $formError       - string, general form error message
 */
$CTA_FORM_ACTION = $CTA_FORM_ACTION ?? '';
$old    = $old ?? ['fullName' => '', 'email' => '', 'phone' => '', 'message' => ''];
$errors = $errors ?? [];
$sent   = $sent ?? false;
$formError = $formError ?? '';
?>
<!-- ===================== CTA + ENQUIRY SECTION ===================== -->
<section class="cta-enquiry">
    <div class="container">
        <div class="cta-enquiry-grid">
            <!-- Left: Description + Image -->
            <div class="cta-enquiry-left" data-anim="fadeInLeft">
                <p class="cta-enquiry-text">At Nivi Homes, we offer a thoughtfully curated range of single and double storey designs to suit every lifestyle. Whether you prefer the ease of open-plan living on one level or the spacious elegance of a two-storey layout, our homes are crafted with care and functionality in mind. From compact and efficient to bold and luxurious, each design reflects our commitment to comfort, quality, and contemporary living. Discover the perfect space to grow, connect, and create lasting memories with Nivi Homes.</p>
                <div class="cta-enquiry-image">
                    <img src="<?php echo asset('images/backgrounds/varahi.webp'); ?>" alt="Nivi Homes - Family Life" loading="lazy">
                    <span class="cta-enquiry-img-label">Nivi Homes · Family Life</span>
                </div>
            </div>

            <!-- Right: Contact Form -->
            <div class="cta-enquiry-right" data-anim="fadeInRight">
                <form id="ctaEnquiryForm" method="post" action="<?php echo e($CTA_FORM_ACTION); ?>" novalidate>
                    <?php echo csrf_field(); ?>
                    <div class="hp-field" aria-hidden="true" style="position:absolute!important;left:-9999px!important;top:auto;width:1px;height:1px;overflow:hidden;">
                        <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    </div>
                    <div class="cta-form-group">
                        <label for="ctaFullName">Full Name *</label>
                        <input type="text" id="ctaFullName" name="fullName" placeholder="Your Name" value="<?php echo e($old['fullName']); ?>" required>
                        <?php if (isset($errors['fullName'])): ?><span class="form-error"><?php echo e($errors['fullName']); ?></span><?php endif; ?>
                    </div>
                    <div class="cta-form-group">
                        <label for="ctaEmail">Email Address *</label>
                        <input type="email" id="ctaEmail" name="email" placeholder="Your Email Address" value="<?php echo e($old['email']); ?>" required>
                        <?php if (isset($errors['email'])): ?><span class="form-error"><?php echo e($errors['email']); ?></span><?php endif; ?>
                    </div>
                    <div class="cta-form-group">
                        <label for="ctaPhone">Phone Number</label>
                        <input type="text" id="ctaPhone" name="phone" placeholder="Your Phone Number" value="<?php echo e($old['phone']); ?>">
                        <?php if (isset($errors['phone'])): ?><span class="form-error"><?php echo e($errors['phone']); ?></span><?php endif; ?>
                    </div>
                    <div class="cta-form-group">
                        <label for="ctaMessage">Message</label>
                        <textarea id="ctaMessage" name="message" maxlength="180" placeholder="Enter your message..."><?php echo e($old['message']); ?></textarea>
                        <div class="cta-char-count">0 / 180</div>
                        <?php if (isset($errors['message'])): ?><span class="form-error"><?php echo e($errors['message']); ?></span><?php endif; ?>
                    </div>
                    <button type="submit" class="cta-submit-btn">Send Message</button>
                    <?php
                    $noteClass = $sent ? ' success' : (($formError || $errors) ? ' error' : '');
                    $noteText  = $sent
                        ? 'Thank you! Your message has been sent. We will be in touch shortly.'
                        : ($formError !== '' ? $formError : ($errors ? 'Please correct the highlighted fields and try again.' : ''));
                    ?>
                    <?php if ($noteText): ?>
                    <p class="form-note<?php echo $noteClass; ?>" role="status" aria-live="polite"><?php echo e($noteText); ?></p>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</section>
