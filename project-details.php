<?php
$CURRENT_PAGE = 'projects';

// Backend (read-only, no session needed on public pages)
define('NIVI_NO_SESSION', true);
require_once __DIR__ . '/app/bootstrap.php';
require_once __DIR__ . '/app/projects.php';

// Load the published project by slug; fall back to the projects list if missing.
$slug = trim($_GET['p'] ?? '');
$prj  = $slug !== '' ? project_by_slug($slug) : null;
if (!$prj) {
    header('Location: projects.php');
    exit;
}

$images   = project_images((int) $prj['id']);
$features = project_features((int) $prj['id']);

$PAGE_TITLE = $prj['title'] . ' | Nivi Homes';
require __DIR__ . '/includes/header.php';

$ic_name = '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/><path d="M9 21v-6h6v6"/></svg>';
$ic_area = '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="1"/><path d="M3 9h3M3 15h3M18 3v3M9 3v3M21 9h-3M21 15h-3M18 21v-3M9 21v-3"/></svg>';
$ic_type = '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/><path d="M9 9v.01M9 12v.01M9 15v.01M9 18v.01"/></svg>';
$ic_loc  = '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>';

$coverUrl = !empty($prj['cover_image']) ? upload_public($prj['cover_image']) : '';
?>

<!-- ===================== PROJECT TOP (cover + info) ===================== -->
<section class="pj-top">
    <div class="container">
        <div class="pj-top-grid">
            <div class="pj-cover" data-anim="fadeInLeft">
                <?php if ($coverUrl): ?>
                <div class="pj-cover-wrap">
                    <img src="<?php echo e($coverUrl); ?>" alt="<?php echo e($prj['title']); ?>" loading="lazy">
                </div>
                <?php endif; ?>
            </div>
            <div class="pj-info" data-anim="fadeInRight">
                <span class="pj-eyebrow">&mdash; Completed Build</span>
                <h2 class="pj-title"><?php echo e($prj['title']); ?></h2>
                <div class="pj-info-grid">
                    <div class="pj-box">
                        <span class="pj-ic"><?php echo $ic_name; ?></span>
                        <div class="pj-box-text"><h3>Project Name</h3><p><?php echo e($prj['title']); ?></p></div>
                    </div>
                    <div class="pj-box">
                        <span class="pj-ic"><?php echo $ic_area; ?></span>
                        <div class="pj-box-text"><h3>Build up area</h3><p><?php echo e($prj['build_up_area'] ?: '—'); ?></p></div>
                    </div>
                    <div class="pj-box">
                        <span class="pj-ic"><?php echo $ic_type; ?></span>
                        <div class="pj-box-text"><h3>Building Type</h3><p><?php echo e($prj['building_type'] ?: '—'); ?></p></div>
                    </div>
                    <div class="pj-box">
                        <span class="pj-ic"><?php echo $ic_loc; ?></span>
                        <div class="pj-box-text"><h3>Location</h3><p><?php echo e($prj['location'] ?: '—'); ?></p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (trim((string) $prj['short_description']) !== '' || trim((string) $prj['description']) !== '' || $features): ?>
<!-- ===================== DESCRIPTION / FEATURES (only when present) ===================== -->
<section class="pj-detail">
    <div class="container">
        <?php if (trim((string) $prj['short_description']) !== ''): ?>
            <p class="pj-lead" data-anim="fadeInUp"><?php echo nl2br(e($prj['short_description'])); ?></p>
        <?php endif; ?>
        <?php if (trim((string) $prj['description']) !== ''): ?>
            <div class="pj-desc" data-anim="fadeInUp"><?php echo nl2br(e($prj['description'])); ?></div>
        <?php endif; ?>
        <?php if ($features): ?>
            <ul class="pj-features" data-anim="fadeInUp">
                <?php foreach ($features as $ft): ?>
                <li><?php echo e($ft['feature']); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- ===================== GALLERY ===================== -->
<section class="pj-gallery-sec">
    <div class="container">
        <h3 class="pj-gallery-title">Project Gallery</h3>
        <div class="pj-gallery">
            <?php foreach ($images as $img):
                $src = upload_public($img['filename']);
                // Build a readable caption from the filename (strip extension, replace separators)
                $caption = pathinfo($img['filename'], PATHINFO_FILENAME);
                $caption = str_replace(['-', '_'], ' ', $caption);
                $caption = ucwords($caption);
            ?>
            <a class="pj-gallery-item" href="<?php echo e($src); ?>" data-lightbox="<?php echo e($src); ?>">
                <img src="<?php echo e($src); ?>" alt="<?php echo e($caption); ?>" loading="lazy">
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
