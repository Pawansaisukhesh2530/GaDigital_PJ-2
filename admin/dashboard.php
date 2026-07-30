<?php
/** Admin dashboard - overview cards + recent lists + quick actions. */
require __DIR__ . '/init.php';
require_admin();

$PAGE       = 'dashboard';
$PAGE_TITLE = 'Dashboard';

$counts = project_counts();
$enqCounts = enquiry_counts();
$recentProjects = project_recent(5);
$recentEnquiries = db()->query(
    'SELECT name, email, is_read, created_at FROM enquiries ORDER BY created_at DESC, id DESC LIMIT 5'
)->fetchAll();

$cards = [
    ['Total Projects',    $counts['total'],       '<path d="M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z"/>'],
    ['Featured Projects', $counts['featured'],    '<path d="M12 2l3 6.5 7 .6-5.3 4.6L18.2 21 12 17.3 5.8 21l1.5-7.3L2 9.1l7-.6z"/>'],
    ['Published',         $counts['published'],   '<path d="M20 6L9 17l-5-5"/>'],
    ['Draft',             $counts['draft'],       '<path d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4z"/>'],
    ['Total Enquiries',   $enqCounts['total'],    '<path d="M4 4h16v12H5.2L4 17.2z"/>'],
    ['Unread Enquiries',  $enqCounts['unread'],   '<path d="M4 4h16v16H4zM4 4l8 7 8-7"/>'],
];

require __DIR__ . '/partials/header.php';
?>

<div class="stat-grid stat-grid-6">
    <?php foreach ($cards as [$label, $num, $icon]): ?>
    <div class="stat-card">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><?php echo $icon; ?></svg>
        </div>
        <div class="stat-meta">
            <div class="num"><?php echo (int) $num; ?></div>
            <div class="label"><?php echo e($label); ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Quick actions -->
<div class="quick-actions">
    <a class="qa" href="projects/create.php"><span class="qa-plus">+</span> Add Project</a>
    <a class="qa" href="projects/index.php">View Projects</a>
    <a class="qa" href="enquiries/index.php">View Enquiries</a>
    <a class="qa" href="settings.php">Settings</a>
</div>

<div class="panel-grid">
    <!-- Recent Projects -->
    <div class="panel">
        <div class="panel-head">
            <h2>Recent Projects</h2>
            <a class="badge badge-grey" href="projects/index.php">View all</a>
        </div>
        <div class="panel-body">
            <?php if ($recentProjects): ?>
                <?php foreach ($recentProjects as $p): ?>
                <div class="list-row">
                    <div>
                        <div><a href="projects/edit.php?id=<?php echo (int) $p['id']; ?>"><?php echo e($p['title']); ?></a></div>
                        <div class="muted"><?php echo e($p['location'] ?: '&mdash;'); ?></div>
                    </div>
                    <div>
                        <?php if ((int) $p['is_featured'] === 1): ?><span class="badge badge-gold">Featured</span> <?php endif; ?>
                        <span class="badge <?php echo $p['status'] === 'published' ? 'badge-green' : 'badge-grey'; ?>"><?php echo e(ucfirst($p['status'])); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty">No projects yet. <a href="projects/create.php">Add your first project</a>.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Enquiries -->
    <div class="panel">
        <div class="panel-head">
            <h2>Recent Enquiries</h2>
            <a class="badge badge-grey" href="enquiries/index.php">View all</a>
        </div>
        <div class="panel-body">
            <?php if ($recentEnquiries): ?>
                <?php foreach ($recentEnquiries as $en): ?>
                <div class="list-row">
                    <div>
                        <div><?php echo e($en['name']); ?></div>
                        <div class="muted"><?php echo e($en['email']); ?></div>
                    </div>
                    <div>
                        <span class="badge <?php echo (int) $en['is_read'] === 0 ? 'badge-gold' : 'badge-grey'; ?>"><?php echo (int) $en['is_read'] === 0 ? 'Unread' : 'Read'; ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty">No enquiries yet. Submissions from the contact form will show here.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
