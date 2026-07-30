<?php
/** Admin - Edit Project: details, cover, gallery (upload/delete/reorder), features. */
require __DIR__ . '/../init.php';
require_admin();

$ADMIN_BASE = '../';
$ROOT       = $ADMIN_BASE . '../';

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$project = project_find($id);
if (!$project) {
    flash_set('flash_error', 'Project not found.');
    redirect('index.php');
}

$errors = [];
$v = $project;   // pre-fill form with current values

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';

    switch ($action) {

        case 'reorder_images':   // AJAX (fetch) - returns JSON
            $order = $_POST['order'] ?? [];
            if (is_array($order)) {
                project_images_reorder($id, $order);
            }
            header('Content-Type: application/json');
            echo json_encode(['ok' => true]);
            exit;

        case 'save_details':
            $res    = project_validate($_POST);
            $v      = $res['data'];
            $errors = $res['errors'];
            $slugBase = $v['slug'] !== '' ? $v['slug'] : $v['title'];
            $v['slug'] = project_unique_slug($slugBase, $id);

            // Optional cover replacement.
            $newCover = null;
            if (!empty($_FILES['cover_image']['name'])) {
                $up = upload_store($_FILES['cover_image'], 'cover');
                if (!$up['ok']) {
                    $errors['cover_image'] = $up['error'];
                } else {
                    $newCover = $up['filename'];
                }
            }
            if (!$errors) {
                project_update($id, $v);
                if ($newCover !== null) {
                    if (!empty($project['cover_image'])) {
                        upload_delete($project['cover_image']);
                    }
                    project_set_cover($id, $newCover);
                }
                flash_set('flash_success', 'Project details saved.');
                redirect('edit.php?id=' . $id);
            }
            // else: fall through and re-render with $errors + $v
            break;

        case 'upload_gallery':
            $ok = 0; $fail = 0;
            $files = $_FILES['gallery'] ?? null;
            if ($files && is_array($files['name'])) {
                $count = count($files['name']);
                for ($i = 0; $i < $count; $i++) {
                    if (($files['error'][$i] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                        continue;
                    }
                    $one = [
                        'name'     => $files['name'][$i],
                        'type'     => $files['type'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'error'    => $files['error'][$i],
                        'size'     => $files['size'][$i],
                    ];
                    $up = upload_store($one, 'gallery');
                    if ($up['ok']) { project_image_add($id, $up['filename']); $ok++; }
                    else { $fail++; }
                }
            }
            if ($ok)  { flash_set('flash_success', "$ok image(s) uploaded." . ($fail ? " $fail failed." : '')); }
            elseif ($fail) { flash_set('flash_error', "$fail image(s) could not be uploaded."); }
            else  { flash_set('flash_error', 'No images selected.'); }
            redirect('edit.php?id=' . $id);

        case 'delete_image':
            $imgId = (int) ($_POST['image_id'] ?? 0);
            $img = project_image_find($imgId);
            if ($img && (int) $img['project_id'] === $id) {
                project_image_delete($imgId);
                flash_set('flash_success', 'Image deleted.');
            }
            redirect('edit.php?id=' . $id);

        case 'add_feature':
            $feat = trim($_POST['feature'] ?? '');
            if ($feat !== '') {
                project_feature_add($id, mb_substr($feat, 0, 200));
                flash_set('flash_success', 'Feature added.');
            } else {
                flash_set('flash_error', 'Feature text cannot be empty.');
            }
            redirect('edit.php?id=' . $id);

        case 'update_feature':
            $fid  = (int) ($_POST['feature_id'] ?? 0);
            $feat = trim($_POST['feature'] ?? '');
            $row  = project_feature_find($fid);
            if ($row && (int) $row['project_id'] === $id && $feat !== '') {
                project_feature_update($fid, mb_substr($feat, 0, 200));
                flash_set('flash_success', 'Feature updated.');
            }
            redirect('edit.php?id=' . $id);

        case 'delete_feature':
            $fid = (int) ($_POST['feature_id'] ?? 0);
            $row = project_feature_find($fid);
            if ($row && (int) $row['project_id'] === $id) {
                project_feature_delete($fid);
                flash_set('flash_success', 'Feature removed.');
            }
            redirect('edit.php?id=' . $id);

        case 'move_feature':
            $fid = (int) ($_POST['feature_id'] ?? 0);
            $dir = ($_POST['dir'] ?? '') === 'up' ? 'up' : 'down';
            project_feature_move($id, $fid, $dir);
            redirect('edit.php?id=' . $id);
    }
}

$images   = project_images($id);
$features = project_features($id);

$PAGE       = 'projects';
$PAGE_TITLE = 'Edit: ' . $project['title'];
require __DIR__ . '/../partials/header.php';
?>
<div class="page-head">
    <h2 class="page-title">Edit Project</h2>
    <div class="nowrap">
        <a class="btn btn-ghost" href="view.php?id=<?php echo $id; ?>">View</a>
        <a class="btn btn-ghost" href="index.php">&larr; Back</a>
    </div>
</div>

<!-- ============ Details ============ -->
<form method="post" action="edit.php?id=<?php echo $id; ?>" enctype="multipart/form-data" class="card-form" id="projectForm">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="action" value="save_details">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <h3 class="section-label">Project Details</h3>
    <?php require __DIR__ . '/_form-fields.php'; ?>

    <div class="field field-wide">
        <label for="cover_image">Cover Image</label>
        <?php if (!empty($project['cover_image'])): ?>
            <div class="cover-preview"><img src="<?php echo e($ROOT . upload_public($project['cover_image'])); ?>" alt="Current cover"></div>
        <?php endif; ?>
        <input type="file" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp">
        <?php if (isset($errors['cover_image'])): ?><span class="field-err"><?php echo e($errors['cover_image']); ?></span><?php endif; ?>
        <span class="hint">Uploading a new image replaces the current cover.</span>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Details</button>
    </div>
</form>

<!-- ============ Gallery ============ -->
<div class="card-form">
    <h3 class="section-label">Gallery Images</h3>

    <form method="post" action="edit.php?id=<?php echo $id; ?>" enctype="multipart/form-data" class="gallery-upload">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="action" value="upload_gallery">
        <input type="file" name="gallery[]" accept="image/jpeg,image/png,image/webp" multiple required>
        <button type="submit" class="btn btn-primary">Upload Images</button>
        <span class="hint">Select multiple. JPG, PNG, WEBP up to 5&nbsp;MB each. Up to 20 per upload &mdash; add more in another batch (they append).</span>
    </form>

    <?php if ($images): ?>
    <p class="hint">Drag thumbnails to reorder. Order saves automatically.</p>
    <div class="gallery-grid" id="galleryGrid" data-reorder-url="edit.php?id=<?php echo $id; ?>">
        <?php echo csrf_field(); ?>
        <?php foreach ($images as $img): ?>
        <div class="gallery-item" draggable="true" data-id="<?php echo (int) $img['id']; ?>">
            <img src="<?php echo e($ROOT . upload_public($img['filename'])); ?>" alt="">
            <form method="post" action="edit.php?id=<?php echo $id; ?>" class="gallery-del">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="delete_image">
                <input type="hidden" name="image_id" value="<?php echo (int) $img['id']; ?>">
                <button type="submit" class="img-del js-confirm" data-confirm="Delete this image?">&times;</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <p class="empty">No gallery images yet.</p>
    <?php endif; ?>
</div>

<!-- ============ Features ============ -->
<div class="card-form">
    <h3 class="section-label">Features / Amenities</h3>

    <form method="post" action="edit.php?id=<?php echo $id; ?>" class="feature-add">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="action" value="add_feature">
        <input type="text" name="feature" placeholder="e.g. Swimming Pool" maxlength="200" required>
        <button type="submit" class="btn btn-primary">Add Feature</button>
    </form>

    <?php if ($features): ?>
    <ul class="feature-list">
        <?php foreach ($features as $fi => $ft): ?>
        <li>
            <form method="post" action="edit.php?id=<?php echo $id; ?>" class="feature-row">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="feature_id" value="<?php echo (int) $ft['id']; ?>">
                <input type="text" name="feature" value="<?php echo e($ft['feature']); ?>" maxlength="200">
                <div class="feature-btns">
                    <button type="submit" name="action" value="move_feature" class="btn-sm btn-ghost" title="Move up" <?php echo $fi === 0 ? 'disabled' : ''; ?> onclick="this.form.dir.value='up'">&uarr;</button>
                    <button type="submit" name="action" value="move_feature" class="btn-sm btn-ghost" title="Move down" <?php echo $fi === count($features) - 1 ? 'disabled' : ''; ?> onclick="this.form.dir.value='down'">&darr;</button>
                    <button type="submit" name="action" value="update_feature" class="btn-sm btn-ghost">Save</button>
                    <button type="submit" name="action" value="delete_feature" class="btn-sm btn-danger js-confirm" data-confirm="Delete this feature?">Delete</button>
                </div>
                <input type="hidden" name="dir" value="down">
            </form>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
        <p class="empty">No features added yet.</p>
    <?php endif; ?>
</div>

<script src="<?php echo $ADMIN_BASE; ?>assets/admin.js"></script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
