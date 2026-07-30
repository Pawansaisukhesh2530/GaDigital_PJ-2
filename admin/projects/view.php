<?php
/** Admin - View Project (read-only). */
require __DIR__ . '/../init.php';
require_admin();

$ADMIN_BASE = '../';
$ROOT       = $ADMIN_BASE . '../';

$id = (int) ($_GET['id'] ?? 0);
$project = project_find($id);
if (!$project) {
    flash_set('flash_error', 'Project not found.');
    redirect('index.php');
}
$images   = project_images($id);
$features = project_features($id);

$PAGE       = 'projects';
$PAGE_TITLE = $project['title'];
require __DIR__ . '/../partials/header.php';
?>
<div class="page-head">
    <h2 class="page-title"><?php echo e($project['title']); ?></h2>
    <div class="nowrap">
        <a class="btn btn-primary" href="edit.php?id=<?php echo $id; ?>">Edit</a>
        <a class="btn btn-ghost" href="index.php">&larr; Back</a>
    </div>
</div>

<div class="view-grid">
    <div class="view-main">
        <?php if (!empty($project['cover_image'])): ?>
            <img class="view-cover" src="<?php echo e($ROOT . upload_public($project['cover_image'])); ?>" alt="">
        <?php endif; ?>

        <dl class="view-dl">
            <dt>Slug</dt><dd><?php echo e($project['slug']); ?></dd>
            <dt>Location</dt><dd><?php echo e($project['location'] ?: '—'); ?></dd>
            <dt>Project Type</dt><dd><?php echo e($project['building_type'] ?: '—'); ?></dd>
            <dt>Building Area</dt><dd><?php echo e($project['build_up_area'] ?: '—'); ?></dd>
            <dt>Status</dt><dd><span class="badge <?php echo $project['status'] === 'published' ? 'badge-green' : 'badge-grey'; ?>"><?php echo e(ucfirst($project['status'])); ?></span></dd>
            <dt>Featured</dt><dd><?php echo (int) $project['is_featured'] ? 'Yes' : 'No'; ?></dd>
            <dt>Created</dt><dd><?php echo e(date('d M Y H:i', strtotime($project['created_at']))); ?></dd>
            <dt>Updated</dt><dd><?php echo e(date('d M Y H:i', strtotime($project['updated_at']))); ?></dd>
        </dl>

        <?php if ($project['short_description']): ?>
            <h3 class="section-label">Short Description</h3>
            <p><?php echo nl2br(e($project['short_description'])); ?></p>
        <?php endif; ?>
        <?php if ($project['description']): ?>
            <h3 class="section-label">Full Description</h3>
            <p><?php echo nl2br(e($project['description'])); ?></p>
        <?php endif; ?>
    </div>

    <div class="view-side">
        <h3 class="section-label">Features (<?php echo count($features); ?>)</h3>
        <?php if ($features): ?>
            <ul class="view-features">
                <?php foreach ($features as $ft): ?><li><?php echo e($ft['feature']); ?></li><?php endforeach; ?>
            </ul>
        <?php else: ?><p class="muted">None.</p><?php endif; ?>
    </div>
</div>

<h3 class="section-label">Gallery (<?php echo count($images); ?>)</h3>
<?php if ($images): ?>
    <div class="gallery-grid">
        <?php foreach ($images as $img): ?>
        <div class="gallery-item">
            <img src="<?php echo e($ROOT . upload_public($img['filename'])); ?>" alt="">
        </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p class="empty">No gallery images.</p>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
