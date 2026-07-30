<?php
/**
 * Shared core project fields.
 * Expects: $v (values array), $errors (array).
 */
$errors = $errors ?? [];
$v = $v ?? [];
$val = fn(string $k, $d = '') => e($v[$k] ?? $d);
$err = fn(string $k) => isset($errors[$k]) ? '<span class="field-err">' . e($errors[$k]) . '</span>' : '';
?>
<div class="form-grid">
    <div class="field field-wide">
        <label for="title">Title <span class="req">*</span></label>
        <input type="text" id="title" name="title" value="<?php echo $val('title'); ?>" required>
        <?php echo $err('title'); ?>
    </div>

    <div class="field field-wide">
        <label for="slug">Slug <span class="hint">(auto-generated from title, editable)</span></label>
        <input type="text" id="slug" name="slug" value="<?php echo $val('slug'); ?>" placeholder="auto-generated">
        <?php echo $err('slug'); ?>
    </div>

    <div class="field">
        <label for="location">Location</label>
        <input type="text" id="location" name="location" value="<?php echo $val('location'); ?>">
    </div>
    <div class="field">
        <label for="building_type">Project Type / Building Type</label>
        <input type="text" id="building_type" name="building_type" value="<?php echo $val('building_type'); ?>" placeholder="e.g. Double Storey">
    </div>

    <div class="field">
        <label for="build_up_area">Building Area</label>
        <input type="text" id="build_up_area" name="build_up_area" value="<?php echo $val('build_up_area'); ?>" placeholder="e.g. 34 sqs">
    </div>

    <div class="field field-wide">
        <label for="short_description">Short Description</label>
        <textarea id="short_description" name="short_description" rows="2"><?php echo $val('short_description'); ?></textarea>
    </div>

    <div class="field field-wide">
        <label for="description">Full Description</label>
        <textarea id="description" name="description" rows="6"><?php echo $val('description'); ?></textarea>
    </div>

    <div class="field">
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="published" <?php echo ($v['status'] ?? 'published') === 'published' ? 'selected' : ''; ?>>Published</option>
            <option value="draft" <?php echo ($v['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
        </select>
    </div>
    <div class="field field-check">
        <label class="checkbox">
            <input type="checkbox" name="is_featured" value="1" <?php echo !empty($v['is_featured']) ? 'checked' : ''; ?>>
            <span>Mark as Featured</span>
        </label>
    </div>
</div>
