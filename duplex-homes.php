<?php
$CURRENT_PAGE = 'services';
$PAGE_TITLE = 'Duplex Homes | Nivi Homes';
require __DIR__ . '/includes/header.php';
?>

<!-- ===================== PAGE BANNER ===================== -->
<?php
$BANNER_TITLE = 'Duplex Homes';
$BANNER_CRUMB = '<a href="index.php">Home</a> &gt; <a href="services.php">Our Services</a> &gt; Duplex Homes';
$BANNER_IMG = asset('images/banners/duplex-banner.jpg');
require __DIR__ . '/includes/banner.php';
?>

<!-- ===================== INTRO ===================== -->
<section class="ch-intro">
    <div class="container">
        <p data-anim="fadeIn">Experience the versatility and practicality of duplex living with Nivi Homes&rsquo; duplex home solutions. Our duplex designs are crafted to provide distinct living spaces within a single structure, offering flexibility for homeowners and potential rental income opportunities. Whether you&rsquo;re looking to maximize your property&rsquo;s potential or accommodate multi-generational living, our duplex homes are designed to meet your unique needs.</p>
    </div>
</section>

<!-- ===================== IMAGE + FEATURES (2x2) ===================== -->
<section class="ch-features">
    <div class="container">
        <div class="ch-features-grid">
            <div class="ch-features-media" data-anim="fadeInLeft">
                <img src="<?php echo asset('images/services/dh-image.png'); ?>" alt="Duplex homes" loading="lazy">
            </div>
            <div class="ch-feat-grid">
                <?php
                $feats = [
                    ['t' => 'Optimized Space Utilization',
                     'd' => 'Our duplex homes maximize living space efficiency while maintaining privacy and functionality for each unit, ideal for families or investors seeking versatile living arrangements.'],
                    ['t' => 'Architectural Excellence',
                     'd' => 'Enjoy innovative design and architectural integrity with our duplex homes, featuring modern layouts and aesthetic appeal that enhance property value and curb appeal.'],
                    ['t' => 'Investment Potential',
                     'd' => 'Duplex homes offer significant investment potential through rental income opportunities or dual occupancy, providing financial flexibility and long-term value appreciation.'],
                    ['t' => 'Customization Options',
                     'd' => 'Customize your duplex home design with flexible floor plans, interior finishes, and exterior features to create a cohesive living environment that meets your specific preferences and lifestyle requirements.'],
                ];
                foreach ($feats as $f): ?>
                <div class="ch-feat" data-anim="fadeIn">
                    <h5><?php echo $f['t']; ?></h5>
                    <p><?php echo $f['d']; ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ===================== WHY CHOOSE A DUPLEX HOME ===================== -->
<section class="ch-why">
    <div class="container">
        <div class="ch-why-grid">
            <div class="ch-why-media" data-anim="fadeInLeft">
                <img src="<?php echo asset('images/services/dh-image.png'); ?>" alt="Why choose a duplex home" loading="lazy">
            </div>
            <div class="ch-why-text" data-anim="fadeInRight">
                <h3>Why Choose a Duplex Home?</h3>
                <p>A duplex home from Nivi Homes provides the perfect balance of privacy and shared living space, making it an ideal choice for families looking to accommodate multiple generations or homeowners interested in generating rental income. With our commitment to architectural innovation and quality construction, you can trust that your duplex home will not only meet but exceed your expectations for style, functionality, and investment potential.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===================== CTA ===================== -->
<section class="ch-cta" style="background-image:url('<?php echo asset('images/services/dh-cta-bg.png'); ?>')">
    <div class="container" data-anim="fadeIn">
        <h3>Ready to explore the possibilities of custom or duplex homes? Contact us today for a free consultation and let us bring your vision to life!</h3>
        <p>for a free consultation and let us bring your vision to life!</p>
        <a class="ch-cta-btn" href="contact.php">Contact us</a>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
