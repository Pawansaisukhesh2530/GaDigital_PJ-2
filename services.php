<?php
$CURRENT_PAGE = 'services';
$PAGE_TITLE = 'Our Services | Nivi Homes';
require __DIR__ . '/includes/header.php';
?>

<!-- ===================== PAGE BANNER ===================== -->
<?php
$BANNER_TITLE = 'Our Services';
$BANNER_CRUMB = '<a href="index.php">Home</a> &gt; Our Services';
$BANNER_IMG = asset('images/banners/services-banner.jpg');
require __DIR__ . '/includes/banner.php';
?>

<!-- ===================== INTRO ===================== -->
<section class="srv-intro">
    <div class="container">
        <h4 data-anim="fadeIn">Nivi Homes offers a comprehensive range of residential solutions tailored to meet your needs</h4>
    </div>
</section>

<!-- ===================== SERVICE TILES (2x2 full-width) ===================== -->
<section class="srv-tiles">
    <?php
    $services = [
        ['t' => 'Custom Homes',        'img' => 'srv-custom-homes.png', 'pos' => 'left top',    'k' => 'custom-homes'],
        ['t' => 'Duplex Homes',        'img' => 'srv-duplex.png',       'pos' => 'center',      'k' => 'duplex-homes'],
        ['t' => 'Knock Down Rebuilds', 'img' => 'srv-knock-down.png',   'pos' => 'center',      'k' => 'knock-down-rebuilds'],
        ['t' => 'Granny Flats',        'img' => 'srv-granny-flats.png', 'pos' => 'center',      'k' => 'granny-flats'],
    ];
    foreach ($services as $s): ?>
    <div class="srv-tile" style="background-image:url('<?php echo asset('images/services/' . $s['img']); ?>'); background-position:<?php echo $s['pos']; ?>" data-anim="fadeIn">
        <div class="srv-tile-inner">
            <h3><?php echo $s['t']; ?></h3>
            <a class="srv-readmore" href="service-details.php?s=<?php echo $s['k']; ?>">Read More</a>
        </div>
    </div>
    <?php endforeach; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
