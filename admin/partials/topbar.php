<?php
/** Admin topbar. Expects $PAGE_TITLE and $__user (from header.php). */
$__initial = strtoupper(substr($__user['username'] ?? 'A', 0, 1));
?>
<header class="topbar">
    <div class="topbar-left">
        <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle navigation menu" aria-controls="adminSidebar" aria-expanded="false">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
        </button>
        <h1><?php echo e($PAGE_TITLE); ?></h1>
    </div>
    <div class="topbar-user">
        <span>Welcome, <strong><?php echo e($__user['username'] ?? 'Admin'); ?></strong></span>
        <span class="avatar"><?php echo e($__initial); ?></span>
    </div>
</header>
