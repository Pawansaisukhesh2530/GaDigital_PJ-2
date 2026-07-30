<?php
$CURRENT_PAGE = 'designs';
$PAGE_TITLE = 'Home Designs | Nivi Homes';
require __DIR__ . '/includes/header.php';

$designs = [
    ['name' => 'Elyra 35',   'type' => 'double', 'beds' => 5, 'baths' => 3,   'cars' => 2, 'img' => 'elyra-35',  'pdf' => asset('pdfs/elyra.pdf')],
    ['name' => 'Olympia 39', 'type' => 'double', 'beds' => 5, 'baths' => 3,   'cars' => 2, 'img' => 'olympia-39','pdf' => asset('pdfs/olympia-39.pdf')],
    ['name' => 'Akira 21',   'type' => 'single', 'beds' => 4, 'baths' => 2.5, 'cars' => 2, 'img' => 'akira-21',  'pdf' => asset('pdfs/akira-21.pdf')],
    ['name' => 'Melora 31',  'type' => 'single', 'beds' => 4, 'baths' => 2.5, 'cars' => 2, 'img' => 'melora-31', 'pdf' => asset('pdfs/meliora.pdf')],
    ['name' => 'Chiron 28',  'type' => 'double', 'beds' => 5, 'baths' => 3,   'cars' => 2, 'img' => 'chiron-28', 'pdf' => asset('pdfs/chiron-28.pdf')],
    ['name' => 'Sophea 22',  'type' => 'single', 'beds' => 3, 'baths' => 2.5, 'cars' => 2, 'img' => 'sophea-22', 'pdf' => asset('pdfs/sophea-22.pdf')],
];

$slug = function ($n) { return strtolower(str_replace(' ', '-', $n)); };
$bed = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 18v-6a2 2 0 012-2h14a2 2 0 012 2v6M3 18h18M3 18v2M21 18v2M6 10V7a1 1 0 011-1h4a1 1 0 011 1v3"/></svg>';
$bath = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 12h16v3a4 4 0 01-4 4H8a4 4 0 01-4-4v-3zM6 12V6a2 2 0 012-2 2 2 0 012 2"/></svg>';
$car = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 13l1.5-4.5A2 2 0 018.4 7h7.2a2 2 0 011.9 1.5L19 13M5 13h14v4H5v-4zM7 17v2M17 17v2"/><circle cx="7.5" cy="15" r=".6"/><circle cx="16.5" cy="15" r=".6"/></svg>';
?>

<?php
$BANNER_TITLE = 'Home Designs';
$BANNER_CRUMB = '<a href="index.php">Home</a> &gt; Our Designs';
$BANNER_IMG = asset('images/projects/elyra-35.jpg');
require __DIR__ . '/includes/banner.php';
?>

<section class="section">
    <div class="container">
        <div class="text-center" data-reveal>
            <span class="section-eyebrow">Browse Our Range</span>
            <h2 class="section-title">Single &amp; Double Storey Designs</h2>
            <p class="section-subtitle">Explore our thoughtfully designed home plans, crafted for modern living.</p>
        </div>

        <div class="designs-tabs">
            <button class="design-tab active" data-filter="all">All</button>
            <button class="design-tab" data-filter="single">Single Storey</button>
            <button class="design-tab" data-filter="double">Double Storey</button>
        </div>

        <div class="designs-grid">
            <?php foreach ($designs as $i => $d): ?>
            <article class="design-card" data-type="<?php echo $d['type']; ?>" data-reveal data-reveal-delay="<?php echo ($i % 3) + 1; ?>">
                <a class="design-card-media" href="design-details.php?d=<?php echo $slug($d['name']); ?>">
                    <img src="<?php echo asset('images/projects/' . $d['img'] . '.jpg'); ?>" alt="<?php echo $d['name']; ?>" loading="lazy">
                </a>
                <div class="design-card-body">
                    <h3><?php echo $d['name']; ?></h3>
                    <div class="design-specs">
                        <span class="design-spec"><?php echo $bed; ?> <?php echo $d['beds']; ?></span>
                        <span class="design-spec"><?php echo $bath; ?> <?php echo $d['baths']; ?></span>
                        <span class="design-spec"><?php echo $car; ?> <?php echo $d['cars']; ?></span>
                    </div>
                    <div class="design-actions">
                        <a href="design-details.php?d=<?php echo $slug($d['name']); ?>" class="btn btn-primary">Click here</a>
                        <a href="<?php echo $d['pdf']; ?>" target="_blank" rel="noopener" class="btn btn-outline">Download Pdf</a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
