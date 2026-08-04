<?php
$CURRENT_PAGE = 'about';
$PAGE_TITLE = 'Why Build With Us? | Nivi Homes';
require __DIR__ . '/includes/header.php';
?>

<!-- ===================== PAGE BANNER ===================== -->
<?php
$BANNER_TITLE = 'Why Build With Us?';
$BANNER_CRUMB = '<a href="index.php">Home</a> &gt; Why Build With Us?';
$BANNER_IMG = asset('images/banners/why-build-banner.webp');
require __DIR__ . '/includes/banner.php';
?>

<!-- ===================== INTRO (image left + text right) ===================== -->
<section class="wb-section">
    <div class="wb-container">
        <div class="wb-intro-grid" data-anim="fadeIn">
            <div class="wb-intro-media">
                <div class="abt-img-offset"></div>
                <div class="abt-img-card">
                    <img src="<?php echo asset('images/why-build/images-1.webp'); ?>" alt="Living Room Featured Build" loading="lazy">
                </div>
            </div>
            <div class="wb-intro-text">
                <p>Building a home is not a casual decision &mdash; it&rsquo;s a financial commitment, an emotional investment, and a long-term responsibility. We approach every project with discipline, transparency, and thoughtful execution. From planning and approvals to construction and handover, every stage follows defined processes designed to protect your investment and deliver lasting value. We don&rsquo;t chase volume &mdash; we focus on building homes that stand strong, function intelligently, and feel right to live in.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===================== WHAT SETS US APART ===================== -->
<section class="wb-apart-section">
    <div class="wb-container">
        <div class="wb-apart-grid" data-anim="fadeIn">
            <div class="wb-apart-media">
                <div class="abt-img-offset"></div>
                <div class="abt-img-card">
                    <img src="<?php echo asset('images/why-build/images-2.webp'); ?>" alt="Living Room Interior Detail" loading="lazy">
                </div>
            </div>
            <div class="wb-apart-content">
                <div class="inc-label"><span class="inc-label-line"></span><span class="inc-label-text">What Sets Us Apart</span></div>
                <h2 class="wb-apart-title">What Sets Us Apart</h2>
                <p class="wb-apart-desc">The leadership behind Nirvana Homes understands what makes a project endure &mdash; legally, structurally, and financially.</p>
            </div>
        </div>

        <div class="wb-features-grid">
            <?php
            $features = [
                ['t' => 'Structured Foundation', 'icon' => 'icon-3.webp',
                 'd' => 'Nirvana Homes operates as the holding and parent organization, ensuring governance, financial clarity, and strategic oversight behind every project. You&rsquo;re not dealing with an unstructured builder &mdash; you&rsquo;re backed by an organized system.'],
                ['t' => 'Thoughtful Planning', 'icon' => 'icon-2.webp',
                 'd' => 'Every layout is designed for real living &mdash; efficient space utilization, functional flow, and modern aesthetics that remain relevant over time.'],
                ['t' => 'Transparent Execution', 'icon' => 'icon-1.webp',
                 'd' => 'Clear documentation. Defined timelines. Straight communication. We eliminate ambiguity so you always know where your project stands.'],
                ['t' => 'Quality with Accountability', 'icon' => 'icon-5.webp',
                 'd' => 'From material selection to finishing details, we maintain disciplined construction standards &mdash; because a home should feel just as solid years later as it did on handover day.'],
            ];
            foreach ($features as $f): ?>
            <article class="wb-feat-card" data-anim="fadeIn">
                <div class="wb-feat-icon">
                    <img src="<?php echo asset('images/why-build/' . $f['icon']); ?>" alt="<?php echo $f['t']; ?>" width="20" height="20" loading="lazy">
                </div>
                <h4 class="wb-feat-title"><?php echo $f['t']; ?></h4>
                <p class="wb-feat-desc"><?php echo $f['d']; ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
