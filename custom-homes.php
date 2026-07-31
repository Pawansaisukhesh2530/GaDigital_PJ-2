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
<section class="ch-intro">
    <div class="container">
        <p data-anim="fadeIn">Design your dream home from the ground up with Nivi Homes&rsquo; custom home building services. We specialize in creating bespoke residences that reflect your unique vision, lifestyle, and architectural preferences. From initial concept to final construction, our team is dedicated to delivering a home that exceeds your expectations in every detail.</p>
    </div>
</section>

<!-- ===================== IMAGE + FEATURES (2x2) ===================== -->
<section class="ch-features">
    <div class="container">
        <div class="ch-features-grid">
            <div class="ch-features-media" data-anim="fadeInLeft">
                <img src="<?php echo asset('images/services/ch-features.webp'); ?>" alt="Custom homes" loading="lazy">
            </div>
            <div class="ch-feat-grid">
                <?php
                $feats = [
                    ['t' => 'Personalized Design Process', 'anim' => 'fadeIn',
                     'icon' => '<path d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4z"/>',
                     'd' => 'Collaborate closely with our experienced architects and designers to customize every aspect of your home, from layout and interior finishes to exterior aesthetics.'],
                    ['t' => 'Exceptional Craftsmanship', 'anim' => 'fadeIn',
                     'icon' => '<path d="M12 2l3 6.5 7 .6-5.3 4.6L18.2 21 12 17.3 5.8 21l1.5-7.3L2 9.1l7-.6z"/>',
                     'd' => 'We pride ourselves on superior craftsmanship and attention to detail, ensuring the highest quality standards are maintained throughout the building process.'],
                    ['t' => 'Transparent Communication', 'anim' => 'fadeIn',
                     'icon' => '<path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>',
                     'd' => 'Enjoy clear and open communication at every stage of your project, with regular updates and consultations to ensure your vision is realized.'],
                    ['t' => 'Quality Assurance', 'anim' => 'fadeIn',
                     'icon' => '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                     'd' => 'Our commitment to excellence includes rigorous quality control measures and inspections, ensuring your custom home is built to last and meets the highest industry standards.'],
                ];
                foreach ($feats as $f): ?>
                <div class="ch-feat" data-anim="<?php echo $f['anim']; ?>">
                    <div class="ch-feat-icon"><svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><?php echo $f['icon']; ?></svg></div>
                    <h5><?php echo $f['t']; ?></h5>
                    <p><?php echo $f['d']; ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ===================== WHY CHOOSE A CUSTOM HOME ===================== -->
<section class="ch-why">
    <div class="container">
        <div class="ch-why-grid">
            <div class="ch-why-media" data-anim="fadeInLeft">
                <img src="<?php echo asset('images/services/ch-why-choose.webp'); ?>" alt="Why choose a custom home" loading="lazy">
            </div>
            <div class="ch-why-text" data-anim="fadeInRight">
                <h3>Why Choose a Custom Home?</h3>
                <p>A custom home from Nivi Homes offers the ultimate opportunity to create a living space that is uniquely yours. Whether you desire a contemporary masterpiece, a traditional sanctuary, or a blend of styles, our team is dedicated to transforming your vision into a reality. With meticulous attention to detail and a focus on quality craftsmanship, we ensure your custom home not only meets but exceeds your expectations.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===================== CTA ===================== -->
<section class="ch-cta" style="background-image:url('<?php echo asset('images/services/ch-cta-bg.webp'); ?>')">
    <div class="container" data-anim="fadeIn">
        <h3>Ready to build your dream home? Contact us today for a free consultation</h3>
        <p>and start your journey towards a custom home that perfectly reflects your lifestyle and preferences!</p>
        <a class="ch-cta-btn" href="contact.php">Contact us</a>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
