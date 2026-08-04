<?php
$CURRENT_PAGE = 'services';
$PAGE_TITLE = 'Knock Down Rebuilds | Nivi Homes';
require __DIR__ . '/includes/header.php';
?>

<?php
$BANNER_TITLE = 'Knock Down Rebuilds';
$BANNER_CRUMB = '<a href="index.php">Home</a> &gt; <a href="services.php">Our Services</a> &gt; Knock Down Rebuilds';
$BANNER_IMG = asset('images/banners/knock-down-banner.webp');
require __DIR__ . '/includes/banner.php';
?>

<!-- ===================== INTRO ===================== -->
<section class="svc-intro">
    <div class="svc-container">
        <p data-anim="fadeIn">Transform your current property into your dream home with Nivi Homes&rsquo; knock down rebuild services. Whether you&rsquo;ve outgrown your current space or want to update an older property, our rebuild solutions offer a fresh start without leaving your desired neighborhood. From concept design to construction completion, we manage every aspect of the rebuild process to deliver a seamless and rewarding experience.</p>
    </div>
</section>

<!-- ===================== FEATURES (bordered card) ===================== -->
<section class="svc-features">
    <div class="svc-container">
        <div class="svc-card" data-anim="fadeIn">
            <div class="svc-card-media">
                <div class="svc-card-img-bg"></div>
                <div class="svc-card-img">
                    <img src="<?php echo asset('images/services/kdr-features.webp'); ?>" alt="Knock Down Rebuild" loading="lazy">
                    <span class="svc-card-img-label">Knock Down Rebuild &middot; Concept</span>
                </div>
            </div>
            <div class="svc-card-features">
                <?php
                $feats = [
                    ['t' => 'Comprehensive Rebuild Expertise',
                     'icon' => '<path d="M3 21h18M9 8h1M9 12h1M9 16h1M14 8h1M14 12h1M14 16h1M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/>',
                     'd' => 'Benefit from our expertise in knock down rebuild projects, including site assessment, demolition, planning approvals, and construction management, ensuring a smooth and efficient process.'],
                    ['t' => 'Custom Home Design',
                     'icon' => '<path d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4z"/>',
                     'd' => 'Work closely with our skilled architects and designers to create a custom home design that maximizes your property&rsquo;s potential and reflects your personal style and preferences.'],
                    ['t' => 'Energy Efficiency and Sustainability',
                     'icon' => '<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8"/>',
                     'd' => 'Incorporate sustainable building practices and energy-efficient features into your new home design, reducing environmental impact and ongoing utility costs.'],
                    ['t' => 'Quality Assurance',
                     'icon' => '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                     'd' => 'Our commitment to quality extends to every aspect of your rebuild project, with strict quality control measures and inspections to ensure superior craftsmanship and long-term durability.'],
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
                <h2>Why Choose a Knock Down Rebuild?</h2>
                <p>A knock down rebuild with Nivi Homes allows you to stay in the location you love while enjoying the benefits of a brand-new home tailored to your lifestyle and preferences. Whether you&rsquo;re looking to modernize an older property or want to create a more energy-efficient living environment, our comprehensive rebuild services ensure your vision for a dream home becomes a reality with minimal disruption and maximum satisfaction.</p>
            </div>
            <div class="svc-why-media">
                <div class="svc-card-img-bg"></div>
                <div class="svc-card-img">
                    <img src="<?php echo asset('images/services/kdr-why.webp'); ?>" alt="Knock Down Rebuild" loading="lazy">
                    <span class="svc-card-img-label">Knock Down Rebuild &middot; Concept</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== CTA ===================== -->
<section class="svc-cta" style="background-image:url('<?php echo asset('images/services/kdr-cta-bg.webp'); ?>')">
    <div class="svc-cta-inner" data-anim="fadeIn">
        <h2>Ready to rebuild your dream home? Contact us today for a consultation</h2>
        <p>and discover how we can transform your existing property into the home of your dreams!</p>
        <a class="svc-cta-btn" href="contact.php">Contact us</a>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
