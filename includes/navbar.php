<?php
/** Sticky top navigation with multi-level dropdowns. Expects $NAV, $CURRENT_PAGE. */
?>
<header class="site-header" id="siteHeader">
    <div class="container header-inner">
        <div class="brand">
            <a href="index.php" aria-label="Nivi Homes home" class="brand-inner">
                <img src="<?php echo asset('images/logo/logo.png'); ?>" alt="Nivi Homes logo" class="brand-logo" width="233" height="109">
            </a>
        </div>

        <button class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false" aria-controls="primaryNav">
            <span></span><span></span><span></span>
        </button>

        <nav class="primary-nav" id="primaryNav" aria-label="Primary">
            <ul class="nav-menu">
                <?php foreach ($NAV as $item):
                    $hasChildren = !empty($item['children']);
                    $isActive = ($CURRENT_PAGE === ($item['key'] ?? ''));
                ?>
                <li class="nav-item<?php echo $hasChildren ? ' has-dropdown' : ''; ?><?php echo $isActive ? ' active' : ''; ?>">
                    <a class="nav-link" href="<?php echo $item['url']; ?>">
                        <?php echo $item['label']; ?>
                        <?php if ($hasChildren): ?><span class="caret" aria-hidden="true"></span><?php endif; ?>
                    </a>
                    <?php if ($hasChildren): ?>
                        <button class="submenu-toggle" aria-label="Toggle submenu" aria-expanded="false"></button>
                        <ul class="dropdown">
                            <?php foreach ($item['children'] as $child): ?>
                            <li><a href="<?php echo $child['url']; ?>"><?php echo $child['label']; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
</header>
<div class="nav-backdrop" id="navBackdrop" hidden></div>
