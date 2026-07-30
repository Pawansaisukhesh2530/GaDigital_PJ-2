<?php
$CURRENT_PAGE = 'projects';
$PAGE_TITLE = 'Project Videos & Tours | Nivi Homes';

// Backend (read-only, no session needed on public pages)
define('NIVI_NO_SESSION', true);
require_once __DIR__ . '/app/bootstrap.php';
require_once __DIR__ . '/app/projects.php';

$projects = projects_published();   // published, ordered by display_order

require __DIR__ . '/includes/header.php';
?>

<!-- ===================== PROJECT VIDEOS & TOURS GRID ===================== -->
<section class="pt-section">
    <div class="container">
        <div class="pt-grid">
            <?php foreach ($projects as $p):
                $link = 'project-details.php?p=' . urlencode($p['slug']);
                $cover = !empty($p['cover_image']) ? upload_public($p['cover_image']) : asset('images/project-tours/rustum.jpg');
            ?>
            <article class="pt-card" data-anim="fadeInUp">
                <a class="pt-media" href="<?php echo e($link); ?>">
                    <img src="<?php echo e($cover); ?>" alt="<?php echo e($p['title']); ?>" loading="lazy">
                </a>
                <h3><a href="<?php echo e($link); ?>"><?php echo e($p['title']); ?></a></h3>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
