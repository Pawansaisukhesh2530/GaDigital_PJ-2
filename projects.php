<?php
$CURRENT_PAGE = 'projects';
$PAGE_TITLE = 'Projects & Completed Builds | Nivi Homes';

// Backend (read-only, no session needed on public pages)
define('NIVI_NO_SESSION', true);
require_once __DIR__ . '/app/bootstrap.php';
require_once __DIR__ . '/app/projects.php';

$projects = projects_published();   // published, ordered by display_order

require __DIR__ . '/includes/header.php';
?>

<!-- ===================== PAGE BANNER ===================== -->
<?php
$BANNER_TITLE = 'Completed Projects';
$BANNER_CRUMB = '<a href="index.php">Home</a> &gt; Completed Projects';
$BANNER_IMG = asset('images/banners/services-banner.webp');
require __DIR__ . '/includes/banner.php';
?>

<!-- ===================== PROJECTS & COMPLETED BUILDS ===================== -->
<section class="cp-section">
    <div class="container">
        <div class="text-center" data-reveal>
            <span class="section-eyebrow">Our Work</span>
            <h2 class="section-title">Projects &amp; Completed Builds</h2>
        </div>

        <div class="cp-grid">
            <?php foreach ($projects as $i => $p):
                $link = 'project-details.php?p=' . urlencode($p['slug']);
                $cover = !empty($p['cover_image']) ? upload_public($p['cover_image']) : asset('images/project-tours/rustum.webp');
                $num = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
            ?>
            <article class="cp-card" data-anim="fadeInUp">
                <span class="cp-card-num"><?php echo $num; ?></span>
                <a class="cp-card-media" href="<?php echo e($link); ?>">
                    <img src="<?php echo e($cover); ?>" alt="<?php echo e($p['title']); ?>" loading="lazy">
                </a>
                <h3 class="cp-card-title"><a href="<?php echo e($link); ?>"><?php echo e($p['title']); ?> <span class="cp-arrow">&rarr;</span></a></h3>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
