</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <!-- Brand + socials -->
        <div class="footer-col footer-brand">
            <a href="index.php" class="footer-logo-link">
                <img src="<?php echo asset('images/logo/logo.png'); ?>" alt="Nivi Homes logo" width="180" height="84" loading="lazy">
            </a>
            <ul class="social-list" aria-label="Social media">
                <li><a href="<?php echo $SOCIAL['facebook']; ?>" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M13.5 21v-7h2.4l.4-2.8h-2.8V9.4c0-.8.2-1.4 1.4-1.4h1.5V5.5c-.3 0-1.2-.1-2.2-.1-2.2 0-3.7 1.3-3.7 3.8v2H8.2V14h2.7v7h2.6z"/></svg></a></li>
                <li><a href="<?php echo $SOCIAL['instagram']; ?>" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 1.8.3 2.2.4.5.2.9.5 1.3.9.4.4.7.8.9 1.3.2.4.4 1 .4 2.2.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.3 1.8-.4 2.2-.2.5-.5.9-.9 1.3-.4.4-.8.7-1.3.9-.4.2-1 .4-2.2.4-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-1.8-.3-2.2-.4-.5-.2-.9-.5-1.3-.9-.4-.4-.7-.8-.9-1.3-.2-.4-.4-1-.4-2.2C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c.1-1.2.3-1.8.4-2.2.2-.5.5-.9.9-1.3.4-.4.8-.7 1.3-.9.4-.2 1-.4 2.2-.4C8.4 2.2 8.8 2.2 12 2.2zm0 1.8c-3.1 0-3.5 0-4.7.1-1.1.1-1.7.2-2.1.4-.5.2-.9.4-1.3.8-.4.4-.6.8-.8 1.3-.2.4-.3 1-.4 2.1C2.6 8.5 2.6 8.9 2.6 12s0 3.5.1 4.7c.1 1.1.2 1.7.4 2.1.2.5.4.9.8 1.3.4.4.8.6 1.3.8.4.2 1 .3 2.1.4 1.2.1 1.6.1 4.7.1s3.5 0 4.7-.1c1.1-.1 1.7-.2 2.1-.4.5-.2.9-.4 1.3-.8.4-.4.6-.8.8-1.3.2-.4.3-1 .4-2.1.1-1.2.1-1.6.1-4.7s0-3.5-.1-4.7c-.1-1.1-.2-1.7-.4-2.1-.2-.5-.4-.9-.8-1.3-.4-.4-.8-.6-1.3-.8-.4-.2-1-.3-2.1-.4-1.2-.1-1.6-.1-4.7-.1zm0 3.1a4.9 4.9 0 110 9.8 4.9 4.9 0 010-9.8zm0 8.1a3.2 3.2 0 100-6.4 3.2 3.2 0 000 6.4zm6.3-8.3a1.15 1.15 0 11-2.3 0 1.15 1.15 0 012.3 0z"/></svg></a></li>
                <li><a href="<?php echo $SOCIAL['twitter']; ?>" target="_blank" rel="noopener" aria-label="X (Twitter)"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M17.5 3h3l-6.6 7.5L21.7 21H16l-4.3-5.6L6.8 21H3.7l7-8-7.2-10H9l3.9 5.2L17.5 3zm-1 16h1.7L7.6 4.7H5.8L16.5 19z"/></svg></a></li>
                <li><a href="<?php echo $SOCIAL['pinterest']; ?>" target="_blank" rel="noopener" aria-label="Pinterest"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 2.2A9.8 9.8 0 008.5 21c-.1-.8-.2-2 0-2.9l1.2-5s-.3-.6-.3-1.5c0-1.4.8-2.4 1.8-2.4.9 0 1.3.6 1.3 1.4 0 .9-.6 2.2-.9 3.4-.2 1 .5 1.8 1.5 1.8 1.8 0 3.1-1.9 3.1-4.6 0-2.4-1.7-4.1-4.2-4.1a4.3 4.3 0 00-4.5 4.3c0 .9.3 1.4.8 2 .2.2.2.3.2.6l-.2.9c-.1.3-.3.4-.6.2-1.2-.5-1.7-1.9-1.7-3.4 0-2.5 2.1-5.5 6.3-5.5 3.4 0 5.6 2.4 5.6 5 0 3.4-1.9 6-4.7 6-1 0-1.9-.5-2.2-1.1l-.6 2.4c-.2.8-.7 1.7-1 2.3A9.8 9.8 0 1012 2.2z"/></svg></a></li>
                <li><a href="<?php echo $SOCIAL['youtube']; ?>" target="_blank" rel="noopener" aria-label="YouTube"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M23 12s0-3.3-.4-4.8c-.2-.9-.9-1.5-1.7-1.7C19.4 5 12 5 12 5s-7.4 0-8.9.4c-.8.2-1.5.9-1.7 1.8C1 8.7 1 12 1 12s0 3.3.4 4.8c.2.9.9 1.5 1.7 1.7 1.5.5 8.9.5 8.9.5s7.4 0 8.9-.4c.8-.2 1.5-.9 1.7-1.7.4-1.6.4-4.9.4-4.9zM9.7 15V9l6.2 3-6.2 3z"/></svg></a></li>
            </ul>
        </div>

        <!-- Quicklinks -->
        <div class="footer-col">
            <h2 class="footer-title">Quicklinks</h2>
            <ul class="footer-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">Nivi Homes Story</a></li>
                <li><a href="inclusions.php">Our Inclusions</a></li>
                <li><a href="services.php">Our Services</a></li>
                <li><a href="projects.php">Projects</a></li>
            </ul>
        </div>

        <!-- Contact -->
        <div class="footer-col">
            <h2 class="footer-title">Contact Us</h2>
            <ul class="footer-contact">
                <li><a href="mailto:<?php echo $SITE['email']; ?>">Email: <?php echo $SITE['email']; ?></a></li>
                <li><a href="tel:<?php echo $SITE['phone_href']; ?>">Phone: <?php echo $SITE['phone']; ?></a></li>
            </ul>
        </div>

        <!-- Gallery -->
        <div class="footer-col">
            <h2 class="footer-title">Gallery</h2>
            <div class="footer-gallery">
                <?php foreach ($GALLERY as $g): ?>
                <a href="<?php echo asset($g); ?>" class="footer-gallery-item">
                    <img src="<?php echo asset($g); ?>" alt="Nivi Homes project" width="70" height="70" loading="lazy">
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="footer-bar">
        <div class="container footer-bar-inner">
            <p>&copy; Copyright NIVI Homes. All Rights Reserved.</p>
            <p>Designed &amp; Developed by <a href="https://gadigitalsolutions.com/" target="_blank" rel="noopener">GA Digital Solutions.</a></p>
        </div>
    </div>
</footer>

<button class="back-to-top" id="backToTop" aria-label="Back to top">
    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
</button>

<script src="<?php echo asset('js/menu.js'); ?>"></script>
<script src="<?php echo asset('js/slider.js'); ?>"></script>
<script src="<?php echo asset('js/gallery.js'); ?>"></script>
<script src="<?php echo asset('js/contact.js'); ?>"></script>
<script src="<?php echo asset('js/main.js'); ?>"></script>
</body>
</html>
