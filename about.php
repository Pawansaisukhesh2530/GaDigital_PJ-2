<?php
$CURRENT_PAGE = 'about';
$PAGE_TITLE = 'About Us | Nivi Homes';
require __DIR__ . '/includes/header.php';
?>

<!-- ===================== PAGE BANNER ===================== -->
<?php
$BANNER_TITLE = 'About Us';
$BANNER_CRUMB = '<a href="index.php">Home</a> &gt; About';
$BANNER_IMG = asset('images/banners/about-banner.webp');
require __DIR__ . '/includes/banner.php';
?>

<!-- ===================== ABOUT US (image left + text right) ===================== -->
<section class="abt-section">
    <div class="abt-container">
        <div class="abt-grid" data-anim="fadeIn">
            <div class="abt-media">
                <div class="abt-img-offset"></div>
                <div class="abt-img-card">
                    <img src="<?php echo asset('images/gallery/image-1-1.webp'); ?>" alt="Nivi Homes Completed Build" loading="lazy">
                </div>
            </div>
            <div class="abt-text">
                <h2 class="abt-heading">About us</h2>
                <p>Every meaningful journey begins with intention.</p>
                <p>In 2024, <strong>Nirvana Homes</strong> was established with a clear purpose &mdash; to build thoughtfully designed living spaces rooted in quality, structure, and long-term value. It wasn&rsquo;t created to chase volume. It was created to build responsibly.</p>
                <p>From this foundation emerged <strong>Nivi Homes</strong> &mdash; the residential brand through which our projects are introduced and brought to life.</p>
                <p>If Nirvana Homes is the vision and backbone, Nivi Homes is the experience you interact with.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===================== OUR STORY (text left + image right) ===================== -->
<section class="abt-section">
    <div class="abt-container">
        <div class="abt-grid abt-grid-reverse" data-anim="fadeIn">
            <div class="abt-text">
                <h2 class="abt-heading">Our Story</h2>
                <p>When we began, we asked a simple question:</p>
                <p>Why do so many homes look impressive on the outside but fall short in everyday living?</p>
                <p>The answer shaped our approach.</p>
                <p>We decided that every home under Nivi Homes would be built with clarity in planning, efficiency in space, and intention in design. Not oversized promises. Not rushed execution. Just disciplined construction and thoughtful detail.</p>
                <p>Because a home is not built for brochures.</p>
                <p><strong>It is built for mornings in the kitchen.</strong></p>
                <p><strong>For late-night conversations.</strong></p>
                <p><strong>For quiet growth and loud celebrations.</strong></p>
                <p>That responsibility guided the creation of Nivi Homes as a focused, customer-facing brand &mdash; designed to deliver homes with transparency, structured execution, and long-term livability.</p>
            </div>
            <div class="abt-media">
                <div class="abt-img-offset"></div>
                <div class="abt-img-card">
                    <img src="<?php echo asset('images/gallery/image-2.webp'); ?>" alt="Nivi Homes Completed Build" loading="lazy">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== OUR VALUES ===================== -->
<section class="abt-values-section">
    <div class="abt-container">
        <div class="abt-values-header" data-anim="fadeIn">
            <div class="inc-label"><span class="inc-label-line"></span><span class="inc-label-text">What We Stand On</span></div>
            <h2 class="abt-values-title">Our Values</h2>
            <p class="abt-values-subtitle">At Nivi Homes, our values are the cornerstone of everything we do. They guide our decisions, inspire our actions, and define our commitment to you, our valued client. Here are the core values that shape our company:</p>
        </div>
        <div class="abt-values-list">
            <?php
            $values = [
                ['t' => 'Intentional Quality',
                 'd' => 'We don&rsquo;t believe in rushed construction or cosmetic excellence. Quality begins in planning &mdash; in structural clarity, disciplined execution, and materials chosen for durability, not just appearance. Every decision is made with long-term livability in mind.',
                 'svg' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M8 8h8M8 12h6M8 16h6"/>'],
                ['t' => 'Integrity and Transparency',
                 'd' => 'No inflated promises. No unclear terms. No hidden processes. We believe trust is built before the foundation is laid. Clear documentation, transparent communication, and honest commitments form the core of how we operate.',
                 'svg' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/>'],
                ['t' => 'Customer-First Thinking',
                 'd' => 'A home is deeply personal. That&rsquo;s why we listen before we build. Understanding lifestyle, space requirements, and future needs allows us to create homes that serve real families &mdash; not just floor plans on paper.',
                 'svg' => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.9"/>'],
            ];
            foreach ($values as $v): ?>
            <article class="abt-value-row" data-anim="fadeIn">
                <div class="abt-value-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><?php echo $v['svg']; ?></svg>
                </div>
                <div class="abt-value-content">
                    <h4><?php echo $v['t']; ?></h4>
                    <p><?php echo $v['d']; ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
