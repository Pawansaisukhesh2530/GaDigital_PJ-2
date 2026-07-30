<?php
/** Admin - View a single enquiry (auto-marks as read). */
require __DIR__ . '/../init.php';
require_admin();

$ADMIN_BASE = '../';

// POST actions from this page (mark unread / delete).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $pid    = (int) ($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';
    if ($pid && enquiry_find($pid)) {
        if ($action === 'mark_unread') { enquiry_mark_read($pid, false); flash_set('flash_success', 'Enquiry marked as unread.'); redirect('index.php'); }
        if ($action === 'delete')      { enquiry_delete($pid);           flash_set('flash_success', 'Enquiry deleted.');          redirect('index.php'); }
    }
    redirect('index.php');
}

$id  = (int) ($_GET['id'] ?? 0);
$en  = enquiry_find($id);
if (!$en) {
    flash_set('flash_error', 'Enquiry not found.');
    redirect('index.php');
}

// Viewing an unread enquiry marks it read.
if ((int) $en['is_read'] === 0) {
    enquiry_mark_read($id, true);
    $en['is_read'] = 1;
}

$PAGE       = 'enquiries';
$PAGE_TITLE = 'Enquiry from ' . $en['name'];
require __DIR__ . '/../partials/header.php';
?>
<div class="page-head">
    <h2 class="page-title">Enquiry Details</h2>
    <a class="btn btn-ghost" href="index.php">&larr; Back to enquiries</a>
</div>

<div class="card-form">
    <dl class="view-dl">
        <dt>Name</dt><dd><?php echo e($en['name']); ?></dd>
        <dt>Email</dt><dd><a href="mailto:<?php echo e($en['email']); ?>"><?php echo e($en['email']); ?></a></dd>
        <dt>Phone</dt><dd><?php echo e($en['phone'] ?: '—'); ?></dd>
        <dt>Received</dt><dd><?php echo e(date('d M Y, H:i', strtotime($en['created_at']))); ?></dd>
        <dt>IP Address</dt><dd class="muted"><?php echo e($en['ip_address'] ?: '—'); ?></dd>
        <dt>Status</dt><dd><span class="badge badge-grey">Read</span></dd>
    </dl>

    <h3 class="section-label">Message</h3>
    <p class="enquiry-message"><?php echo nl2br(e($en['message'])); ?></p>

    <div class="form-actions">
        <a class="btn btn-primary" href="mailto:<?php echo e($en['email']); ?>?subject=Re:%20Your%20enquiry%20with%20Nivi%20Homes">Reply by Email</a>
        <form class="inline-form" method="post" action="view.php?id=<?php echo $id; ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="action" value="mark_unread">
            <button type="submit" class="btn btn-ghost">Mark as Unread</button>
        </form>
        <form class="inline-form" method="post" action="view.php?id=<?php echo $id; ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="action" value="delete">
            <button type="submit" class="btn btn-ghost js-confirm" data-confirm="Delete this enquiry? This cannot be undone.">Delete</button>
        </form>
    </div>
</div>

<script src="<?php echo $ADMIN_BASE; ?>assets/admin.js"></script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
