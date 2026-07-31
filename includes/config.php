<?php
/**
 * Global site configuration.
 *
 * $SITE and $SOCIAL are now loaded dynamically from the `settings` table
 * (managed in the admin Settings page). The hardcoded arrays below are the
 * fallback defaults used only if the database/settings are unavailable, so
 * the site never breaks. $NAV and $GALLERY remain static structure.
 */

// Backend (db + settings). Loaded without starting a session so public
// pages that only include the header stay session-free.
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/settings.php';

// ---- Fallback defaults (original hardcoded values) ----
$SITE_DEFAULTS = [
    'name'       => 'Nivi Homes',
    'email'      => 'hemachandra@gadigitalsolutions.com',
    'phone'      => '+61 411 468 309',
    'phone_href' => '+61411468309',
    'phone2'     => '',
    'address'    => '32/33-39 Veron st, Wentworthville, NSW, 2145',
    'hours'      => 'Monday - Friday, 07:00 AM to 5:00 PM',
    'map'        => 'https://www.google.com/maps?q=-33.80874252319336,150.9775848388672&z=17&hl=en',
    'map_embed'  => 'https://maps.google.com/maps?q=-33.80874252319336,150.9775848388672&z=16&output=embed',
];
$SOCIAL_DEFAULTS = [
    'facebook'  => 'https://www.facebook.com/profile.php?id=61560965706586',
    'instagram' => 'https://www.instagram.com/nivihomes01/',
    'twitter'   => 'https://x.com/NiviHomes',
    'pinterest' => 'https://in.pinterest.com/nivihomes/',
    'youtube'   => 'https://www.youtube.com/channel/UCWkJkbbuacTgj94lbQJOatA',
    'linkedin'  => '',
];

// ---- Live values from the database (fall back to defaults) ----
$SITE   = settings_site($SITE_DEFAULTS);
$SOCIAL = settings_social($SOCIAL_DEFAULTS);

/**
 * Primary navigation with dropdown children.
 * `active` is matched against $CURRENT_PAGE set by each page.
 */
$NAV = [
    ['label' => 'Home', 'url' => 'index.php', 'key' => 'home'],
    ['label' => 'About Nivi', 'url' => '#', 'key' => 'about', 'children' => [
        ['label' => 'Nivi Homes Story', 'url' => 'about.php'],
        ['label' => 'Why Build With Us?', 'url' => 'why-build-with-us.php'],
    ]],
    ['label' => 'Our Inclusions', 'url' => 'inclusions.php', 'key' => 'inclusions'],
    ['label' => 'Our Services', 'url' => 'services.php', 'key' => 'services', 'children' => [
        ['label' => 'Custom Homes', 'url' => 'service-details.php?s=custom-homes'],
        ['label' => 'Duplex Homes', 'url' => 'service-details.php?s=duplex-homes'],
        ['label' => 'Knock Down Rebuilds', 'url' => 'service-details.php?s=knock-down-rebuilds'],
        ['label' => 'Granny Flats', 'url' => 'service-details.php?s=granny-flats'],
    ]],
    ['label' => 'Projects', 'url' => 'projects.php', 'key' => 'projects'],
    ['label' => 'Our Designs', 'url' => 'single-storey-projects.php', 'key' => 'designs', 'children' => [
        ['label' => 'Single Storey Projects', 'url' => 'single-storey-projects.php'],
        ['label' => 'Double Storey Projects', 'url' => 'double-storey-projects.php'],
    ]],
    ['label' => 'Get In Touch', 'url' => 'contact.php', 'key' => 'contact'],
];

/**
 * Footer gallery thumbnails (each links to the full image).
 * Uses real photos from published projects (covers first for variety, then
 * gallery images), loaded from the database. Falls back to the bundled
 * static thumbnails if the database is unavailable, so the footer never breaks.
 * Paths are relative to the /assets folder (rendered via asset()).
 */
$GALLERY = [];
try {
    $imgs = [];
    // Project cover images first — one per project gives the best variety.
    foreach (db()->query("SELECT cover_image FROM projects WHERE status='published' AND cover_image <> '' ORDER BY display_order ASC, id ASC")->fetchAll(PDO::FETCH_COLUMN) as $f) {
        if (!in_array($f, $imgs, true)) { $imgs[] = $f; }
    }
    // Top up with gallery images until we have 8.
    if (count($imgs) < 8) {
        foreach (db()->query("SELECT pi.filename FROM project_images pi JOIN projects p ON p.id = pi.project_id WHERE p.status='published' AND pi.filename <> '' ORDER BY p.display_order ASC, pi.sort_order ASC, pi.id ASC")->fetchAll(PDO::FETCH_COLUMN) as $f) {
            if (count($imgs) >= 8) { break; }
            if (!in_array($f, $imgs, true)) { $imgs[] = $f; }
        }
    }
    $GALLERY = array_map(fn($f) => 'uploads/projects/' . $f, array_slice($imgs, 0, 8));
} catch (Throwable $e) {
    $GALLERY = [];
}
// Fallback to the bundled static thumbnails if no project images are available.
if (!$GALLERY) {
    $GALLERY = array_map(fn($f) => 'images/gallery/' . $f . '.webp', [
        'image-2', 'image-1-1', 'custom-home-1', 'Knock-Down-Rebuilds',
        'granny-flats', 'dupulex', 'custom-home', 'image-1',
    ]);
}

if (!function_exists('asset')) {
    /** Build an asset URL relative to project root. */
    function asset($path) { return 'assets/' . ltrim($path, '/'); }
}
