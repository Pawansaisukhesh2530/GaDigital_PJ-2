<?php
/** Admin - Add Project (core fields + optional cover). Gallery/features on edit. */
require __DIR__ . '/../init.php';
require_admin();

$PAGE       = 'projects';
$PAGE_TITLE = 'Add Project';
$ADMIN_BASE = '../';

$errors = [];
$v = ['status' => 'published'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $res    = project_validate($_POST);
    $v      = $res['data'];
    $errors = $res['errors'];

    // Resolve slug (unique).
    $slugBase = $v['slug'] !== '' ? $v['slug'] : $v['title'];
    $v['slug'] = project_unique_slug($slugBase);

    // Validate optional cover BEFORE creating the record.
    $coverName = '';
    if (!empty($_FILES['cover_image']['name'])) {
        $up = upload_store($_FILES['cover_image'], 'cover');
        if (!$up['ok']) {
            $errors['cover_image'] = $up['error'];
        } else {
            $coverName = $up['filename'];
        }
    }

    if (!$errors) {
        $v['cover_image'] = $coverName;
        $id = project_create($v);
        flash_set('flash_success', 'Project created. You can now add gallery images and features.');
        redirect('edit.php?id=' . $id);
    }
}

require __DIR__ . '/../partials/header.php';
?>
<div class="page-head">
    <h2 class="page-title">Add Project</h2>
    <a class="btn btn-ghost" href="index.php">&larr; Back to list</a>
</div>

<form method="post" action="create.php" enctype="multipart/form-data" class="card-form" id="projectForm">
    <?php echo csrf_field(); ?>

    <?php require __DIR__ . '/_form-fields.php'; ?>

    <div class="field field-wide">
        <label for="cover_image">Cover Image</label>
        <input type="file" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp">
        <?php if (isset($errors['cover_image'])): ?><span class="field-err"><?php echo e($errors['cover_image']); ?></span><?php endif; ?>
        <span class="hint">JPG, PNG or WEBP, up to 5&nbsp;MB.</span>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Create Project</button>
        <a class="btn btn-ghost" href="index.php">Cancel</a>
    </div>
    <p class="hint">Gallery images and features can be added after the project is created.</p>
</form>

<script src="<?php echo $ADMIN_BASE; ?>assets/admin.js"></script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
