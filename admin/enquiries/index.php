<?php
/** Admin - Enquiries list: search, read/unread filter, mark read/unread, delete. */
require __DIR__ . '/../init.php';
require_admin();

// ---- POST actions (CSRF-protected) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id     = (int) ($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';
    if ($id && enquiry_find($id)) {
        switch ($action) {
            case 'mark_read':   enquiry_mark_read($id, true);  flash_set('flash_success', 'Enquiry marked as read.');   break;
            case 'mark_unread': enquiry_mark_read($id, false); flash_set('flash_success', 'Enquiry marked as unread.'); break;
            case 'delete':      enquiry_delete($id);           flash_set('flash_success', 'Enquiry deleted.');          break;
        }
    }
    // Preserve current search/filter on redirect.
    $qs = http_build_query(array_filter(['search' => input('search'), 'read' => input('read')], fn($x) => $x !== ''));
    redirect('index.php' . ($qs ? '?' . $qs : ''));
}

$PAGE       = 'enquiries';
$PAGE_TITLE = 'Enquiries';
$ADMIN_BASE = '../';

$filters = ['search' => input('search'), 'read' => input('read')];
$rows    = enquiries_list($filters);
$counts  = enquiry_counts();

require __DIR__ . '/../partials/header.php';
?>

<div class="page-head">
    <div>
        <p class="page-sub"><?php echo (int) $counts['total']; ?> total &middot; <?php echo (int) $counts['unread']; ?> unread</p>
    </div>
</div>

<form class="filter-bar" method="get" action="index.php">
    <input type="search" name="search" placeholder="Search name, email, phone or message&hellip;" value="<?php echo e($filters['search']); ?>">
    <select name="read">
        <option value="">All enquiries</option>
        <option value="0" <?php echo $filters['read'] === '0' ? 'selected' : ''; ?>>Unread only</option>
        <option value="1" <?php echo $filters['read'] === '1' ? 'selected' : ''; ?>>Read only</option>
    </select>
    <button type="submit" class="btn btn-ghost">Filter</button>
    <?php if ($filters['search'] !== '' || $filters['read'] !== ''): ?>
        <a class="btn btn-ghost" href="index.php">Reset</a>
    <?php endif; ?>
</form>

<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th>From</th>
                <th>Phone</th>
                <th>Message</th>
                <th>Status</th>
                <th>Received</th>
                <th class="ta-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$rows): ?>
                <tr><td colspan="6" class="empty">No enquiries found.</td></tr>
            <?php else: foreach ($rows as $en):
                $isUnread = (int) $en['is_read'] === 0; ?>
            <tr class="<?php echo $isUnread ? 'row-unread' : ''; ?>">
                <td>
                    <strong><?php echo e($en['name']); ?></strong><br>
                    <a class="muted" href="mailto:<?php echo e($en['email']); ?>"><?php echo e($en['email']); ?></a>
                </td>
                <td><?php echo e($en['phone'] ?: '—'); ?></td>
                <td class="cell-msg"><?php echo e(mb_strimwidth($en['message'], 0, 70, '…')); ?></td>
                <td>
                    <?php if ($isUnread): ?><span class="badge badge-gold">Unread</span><?php else: ?><span class="badge badge-grey">Read</span><?php endif; ?>
                </td>
                <td class="muted nowrap"><?php echo e(date('d M Y, H:i', strtotime($en['created_at']))); ?></td>
                <td class="ta-right nowrap">
                    <a class="btn-sm btn-ghost" href="view.php?id=<?php echo (int) $en['id']; ?>">View</a>
                    <form class="inline-form" method="post" action="index.php">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo (int) $en['id']; ?>">
                        <?php if ($isUnread): ?>
                            <input type="hidden" name="action" value="mark_read">
                            <button type="submit" class="btn-sm btn-ghost">Mark read</button>
                        <?php else: ?>
                            <input type="hidden" name="action" value="mark_unread">
                            <button type="submit" class="btn-sm btn-ghost">Mark unread</button>
                        <?php endif; ?>
                    </form>
                    <form class="inline-form" method="post" action="index.php">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo (int) $en['id']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn-sm btn-danger js-confirm" data-confirm="Delete this enquiry? This cannot be undone.">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<script src="<?php echo $ADMIN_BASE; ?>assets/admin.js"></script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
