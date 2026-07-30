<?php
/** Admin - Projects list with search + filters + drag-and-drop ordering. */
require __DIR__ . '/../init.php';
require_admin();

// ---- AJAX: persist a new drag-and-drop order ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'reorder') {
    csrf_check();
    $order = $_POST['order'] ?? [];
    header('Content-Type: application/json');
    if (is_array($order) && $order) {
        project_reorder($order);
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['ok' => false, 'error' => 'No order supplied.']);
    }
    exit;
}

$PAGE       = 'projects';
$PAGE_TITLE = 'Projects';
$ADMIN_BASE = '../';
$ROOT       = $ADMIN_BASE . '../';

$filters = [
    'search'   => input('search'),
    'status'   => input('status'),
    'featured' => input('featured'),
];
$projects = projects_list($filters);

// Drag-reordering only makes sense over the full, unfiltered list.
$isReorderable = ($filters['search'] === '' && $filters['status'] === '' && $filters['featured'] === '');

require __DIR__ . '/../partials/header.php';
?>

<div class="page-head">
    <div>
        <p class="page-sub"><?php echo count($projects); ?> project<?php echo count($projects) === 1 ? '' : 's'; ?></p>
    </div>
    <a class="btn btn-primary" href="create.php"><span class="qa-plus">+</span> Add Project</a>
</div>

<form class="filter-bar" method="get" action="index.php">
    <input type="search" name="search" placeholder="Search title or location&hellip;" value="<?php echo e($filters['search']); ?>">
    <select name="status">
        <option value="">All statuses</option>
        <option value="published" <?php echo $filters['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
        <option value="draft" <?php echo $filters['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
    </select>
    <select name="featured">
        <option value="">All</option>
        <option value="1" <?php echo $filters['featured'] === '1' ? 'selected' : ''; ?>>Featured only</option>
        <option value="0" <?php echo $filters['featured'] === '0' ? 'selected' : ''; ?>>Not featured</option>
    </select>
    <button type="submit" class="btn btn-ghost">Filter</button>
    <?php if ($filters['search'] !== '' || $filters['status'] !== '' || $filters['featured'] !== ''): ?>
        <a class="btn btn-ghost" href="index.php">Reset</a>
    <?php endif; ?>
</form>

<?php if ($isReorderable && count($projects) > 1): ?>
    <p class="reorder-hint"><span class="drag-handle" aria-hidden="true">&#9776;</span> Drag rows by the handle to reorder how projects appear on the website. Changes save automatically.</p>
<?php elseif (!$isReorderable): ?>
    <p class="reorder-hint muted">Clear the search &amp; filters to drag-and-drop reorder projects.</p>
<?php endif; ?>

<div class="table-wrap">
    <table class="data-table" id="projectsTable"
           data-reorder="<?php echo $isReorderable ? '1' : '0'; ?>"
           data-url="index.php"
           data-csrf="<?php echo e(csrf_token()); ?>">
        <thead>
            <tr>
                <?php if ($isReorderable): ?><th class="th-drag" aria-label="Reorder"></th><?php endif; ?>
                <th>Cover</th>
                <th>Title</th>
                <th>Location</th>
                <th>Status</th>
                <th>Featured</th>
                <th>Updated</th>
                <th class="ta-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $cols = $isReorderable ? 8 : 7;
            if (!$projects): ?>
                <tr><td colspan="<?php echo $cols; ?>" class="empty">No projects found. <a href="create.php">Add a project</a>.</td></tr>
            <?php else: foreach ($projects as $p): ?>
            <tr data-id="<?php echo (int) $p['id']; ?>"<?php echo $isReorderable ? ' draggable="true"' : ''; ?>>
                <?php if ($isReorderable): ?>
                <td class="drag-cell"><span class="drag-handle" title="Drag to reorder">&#9776;</span></td>
                <?php endif; ?>
                <td>
                    <?php if (!empty($p['cover_image'])): ?>
                        <img class="thumb" src="<?php echo e($ROOT . upload_public($p['cover_image'])); ?>" alt="">
                    <?php else: ?>
                        <span class="thumb thumb-empty">&mdash;</span>
                    <?php endif; ?>
                </td>
                <td><strong><?php echo e($p['title']); ?></strong><br><span class="muted"><?php echo e($p['slug']); ?></span></td>
                <td><?php echo e($p['location'] ?: '—'); ?></td>
                <td><span class="badge <?php echo $p['status'] === 'published' ? 'badge-green' : 'badge-grey'; ?>"><?php echo e(ucfirst($p['status'])); ?></span></td>
                <td><?php echo (int) $p['is_featured'] === 1 ? '<span class="badge badge-gold">Yes</span>' : '<span class="muted">No</span>'; ?></td>
                <td class="muted"><?php echo e(date('d M Y', strtotime($p['updated_at']))); ?></td>
                <td class="ta-right nowrap">
                    <a class="btn-sm btn-ghost" href="view.php?id=<?php echo (int) $p['id']; ?>">View</a>
                    <a class="btn-sm btn-ghost" href="edit.php?id=<?php echo (int) $p['id']; ?>">Edit</a>
                    <form class="inline-form js-delete" method="post" action="delete.php">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo (int) $p['id']; ?>">
                        <button type="submit" class="btn-sm btn-danger" data-title="<?php echo e($p['title']); ?>">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<div class="toast" id="toast" role="status" aria-live="polite"></div>

<script src="<?php echo $ADMIN_BASE; ?>assets/admin.js"></script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
