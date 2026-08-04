<?php
$CURRENT_PAGE = 'services';
$PAGE_TITLE = 'Custom Homes | Nivi Homes';
require __DIR__ . '/includes/header.php';
?>

<!-- ===================== PAGE BANNER ===================== -->
<?php
$BANNER_TITLE = 'Custom Homes';
$BANNER_CRUMB = '<a href="index.php">Home</a> &gt; <a href="services.php">Our Services</a> &gt; Custom Homes';
$BANNER_IMG = asset('images/banners/custom-homes-banner.webp');
require __DIR__ . '/includes/banner.php';
?>

<!-- ===================== INTRO ===================== -->
<section class="svc-intro">
    <div class="svc-container">
        <p data-anim="fadeIn">Design your dream home from the ground up with Nivi Homes&rsquo; custom home building services. We specialize in creating bespoke residences that reflect your unique vision, lifestyle, and architectural preferences. From initial concept to final construction, our team is dedicated to delivering a home that exceeds your expectations in every detail.</p>
    </div>
</section>

<!-- ===================== FEATURES (bordered card) ===================== -->
<section class="svc-features">
    <div class="svc-container">
        <div class="svc-card" data-anim="fadeIn">
            <div class="svc-card-media">
                <div class="svc-card-img-bg"></div>
                <div class="svc-card-img">
                    <img src="<?php echo asset('images/services/ch-features.webp'); ?>" alt="Custom Home Elevation" loading="lazy">
                    <span class="svc-card-img-label">Duplex Home &middot; Dusk Elevation</span>
                </div>
            </div>
            <div class="svc-card-features">
                <?php
                $feats = [
                    ['t' => 'Personalized Design Process',
                     'icon' => '<path d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4z"/>',
                     'd' => 'Collaborate closely with our experienced architects and designers to customize every aspect of your home, from layout and interior finishes to exterior aesthetics.'],
                    ['t' => 'Exceptional Craftsmanship',
                     'icon' => '<path d="M12 2l3 6.5 7 .6-5.3 4.6L18.2 21 12 17.3 5.8 21l1.5-7.3L2 9.1l7-.6z"/>',
                     'd' => 'We pride ourselves on superior craftsmanship and attention to detail, ensuring the highest quality standards are maintained throughout the building process.'],
                    ['t' => 'Transparent Communication',
                     'icon' => '<path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>',
                     'd' => 'Enjoy clear and open communication at every stage of your project, with regular updates and consultations to ensure your vision is realized.'],
                    ['t' => 'Quality Assurance',
                     'icon' => '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                     'd' => 'Our commitment to excellence includes rigorous quality control measures and inspections, ensuring your custom home is built to last and meets the highest industry standards.'],
                ];
                foreach ($feats as $f): ?>
                <div class="svc-feat-item">
                    <div class="svc-feat-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><?php echo $f['icon']; ?></svg>
                    </div>
                    <h4><?php echo $f['t']; ?></h4>
                    <p><?php echo $f['d']; ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ===================== WHY CHOOSE ===================== -->
<section class="svc-why">
    <div class="svc-container">
        <div class="svc-why-grid" data-anim="fadeIn">
            <div class="svc-why-text">
                <h2>Why Choose a Custom Home?</h2>
                <p>A custom home from Nivi Homes offers the ultimate opportunity to create a living space that is uniquely yours. Whether you desire a contemporary masterpiece, a traditional sanctuary, or a blend of styles, our team is dedicated to transforming your vision into a reality. With meticulous attention to detail and a focus on quality craftsmanship, we ensure your custom home not only meets but exceeds your expectations.</p>
            </div>
            <div class="svc-why-media">
                <div class="svc-card-img-bg"></div>
                <div class="svc-card-img">
                    <img src="<?php echo asset('images/services/ch-why-choose.webp'); ?>" alt="Custom Home Interior" loading="lazy">
                    <span class="svc-card-img-label">Custom Home &middot; Interior Concept</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== CTA ===================== -->
<section class="svc-cta">
    <div class="svc-cta-inner">
        <h2>Ready to build your dream home? Contact us today for a free consultation</h2>
        <p>and start your journey towards a custom home that perfectly reflects your lifestyle and preferences!</p>
        <a class="svc-cta-btn" href="contact.php">Contact us</a>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
