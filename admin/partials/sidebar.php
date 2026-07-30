<?php
/** Admin sidebar navigation. Expects $PAGE for active state. */
$nav = [
    'dashboard' => ['Dashboard', 'dashboard.php',        '<path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>'],
    'projects'  => ['Projects',  'projects/index.php',   '<path d="M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z"/>'],
    'enquiries' => ['Enquiries', 'enquiries/index.php',  '<path d="M4 4h16v12H5.2L4 17.2z"/>'],
    'settings'  => ['Settings',  'settings.php',         '<path d="M12 8a4 4 0 100 8 4 4 0 000-8zm9 4a7 7 0 01-.1 1.2l2 1.6-2 3.4-2.4-1a7 7 0 01-2 1.2l-.4 2.6H10l-.4-2.6a7 7 0 01-2-1.2l-2.4 1-2-3.4 2-1.6A7 7 0 013 12c0-.4 0-.8.1-1.2l-2-1.6 2-3.4 2.4 1a7 7 0 012-1.2L10 3h4l.4 2.6a7 7 0 012 1.2l2.4-1 2 3.4-2 1.6c.1.4.1.8.1 1.2z"/>'],
    'account'   => ['My Account','account.php',          '<circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 016-6h4a6 6 0 016 6v1"/>'],
];
?>
<aside class="sidebar" id="adminSidebar">
    <div class="sidebar-brand">NIVI<span>.</span> Admin</div>
    <nav class="sidebar-nav">
        <?php foreach ($nav as $key => [$label, $file, $icon]): ?>
        <a href="<?php echo e(admin_url($file)); ?>" class="<?php echo $PAGE === $key ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><?php echo $icon; ?></svg>
            <span><?php echo e($label); ?></span>
        </a>
        <?php endforeach; ?>
    </nav>
    <div class="sidebar-foot">
        <a href="<?php echo e(admin_url('logout.php')); ?>" class="btn btn-ghost btn-block">Log out</a>
    </div>
</aside>
