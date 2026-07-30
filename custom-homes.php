<?php
$CURRENT_PAGE = 'services';
$PAGE_TITLE = 'Custom Homes | Nivi Homes';
require __DIR__ . '/includes/header.php';
?>

<!-- ===================== PAGE BANNER ===================== -->
<?php
$BANNER_TITLE = 'Custom Homes';
$BANNER_CRUMB = '<a href="index.php">Home</a> &gt; <a href="services.php">Our Services</a> &gt; Custom Homes';
$BANNER_IMG = asset('images/banners/custom-homes-banner.jpg');
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
                <img src="<?php echo asset('images/services/ch-features.png'); ?>" alt="Custom homes" loading="lazy">
            </div>
            <div class="ch-feat-grid">
                <?php
                $feats = [
                    ['t' => 'Personalized Design Process', 'anim' => 'fadeIn',
                     'd' => 'Collaborate closely with our experienced architects and designers to customize every aspect of your home, from layout and interior finishes to exterior aesthetics.'],
                    ['t' => 'Exceptional Craftsmanship', 'anim' => 'fadeIn',
                     'd' => 'We pride ourselves on superior craftsmanship and attention to detail, ensuring the highest quality standards are maintained throughout the building process.'],
                    ['t' => 'Transparent Communication', 'anim' => 'fadeIn',
                     'd' => 'Enjoy clear and open communication at every stage of your project, with regular updates and consultations to ensure your vision is realized.'],
                    ['t' => 'Quality Assurance', 'anim' => 'fadeIn',
                     'd' => 'Our commitment to excellence includes rigorous quality control measures and inspections, ensuring your custom home is built to last and meets the highest industry standards.'],
                ];
                foreach ($feats as $f): ?>
                <div class="ch-feat" data-anim="<?php echo $f['anim']; ?>">
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
                <img src="<?php echo asset('images/services/ch-why-choose.png'); ?>" alt="Why choose a custom home" loading="lazy">
            </div>
            <div class="ch-why-text" data-anim="fadeInRight">
                <h3>Why Choose a Custom Home?</h3>
                <p>A custom home from Nivi Homes offers the ultimate opportunity to create a living space that is uniquely yours. Whether you desire a contemporary masterpiece, a traditional sanctuary, or a blend of styles, our team is dedicated to transforming your vision into a reality. With meticulous attention to detail and a focus on quality craftsmanship, we ensure your custom home not only meets but exceeds your expectations.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===================== CTA ===================== -->
<section class="ch-cta" style="background-image:url('<?php echo asset('images/services/ch-cta-bg.png'); ?>')">
    <div class="container" data-anim="fadeIn">
        <h3>Ready to build your dream home? Contact us today for a free consultation</h3>
        <p>and start your journey towards a custom home that perfectly reflects your lifestyle and preferences!</p>
        <a class="ch-cta-btn" href="contact.php">Contact us</a>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
