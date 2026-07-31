<?php
// Dedicated service pages — redirect the old service URLs to them.
$__redirects = [
    'why-build-with-us'   => 'why-build-with-us.php',
    'custom-homes'        => 'custom-homes.php',
    'duplex-homes'        => 'duplex-homes.php',
    'knock-down-rebuilds' => 'knock-down-rebuilds.php',
    'granny-flats'        => 'granny-flats.php',
];
if (isset($__redirects[$_GET['s'] ?? ''])) {
    header('Location: ' . $__redirects[$_GET['s']], true, 301);
    exit;
}

$CURRENT_PAGE = 'services';
require __DIR__ . '/includes/config.php';

$SERVICES = [
    'custom-homes' => [
        'title' => 'Custom Homes',
        'img' => asset('images/services/custom-homes.webp'),
        'intro' => 'Designed to reflect your unique style and preferences, our custom homes are built around the way you live.',
        'body' => 'At Nivi Homes, a custom home is a true collaboration. Our engineers, architects and designers work closely with you to translate your vision into a home that balances beauty, function and long-term value. Every layout, finish and detail is considered so the finished result feels unmistakably yours.',
        'points' => ['Fully personalised floor plans', 'Premium fixtures and finishes', 'Transparent pricing and process', 'Dedicated project management', 'Quality craftsmanship throughout'],
    ],
    'duplex-homes' => [
        'title' => 'Duplex Homes',
        'img' => asset('images/services/duplex.webp'),
        'intro' => 'Maximizing space and functionality without compromising on aesthetics.',
        'body' => 'Duplex homes are an excellent way to make the most of your land, whether for investment, dual living or extended family. We design smart, stylish duplexes that maximise every square metre while maintaining privacy, comfort and modern appeal.',
        'points' => ['Efficient dual-occupancy designs', 'Ideal for investment or family living', 'Modern, space-smart layouts', 'Compliant with council requirements', 'Excellent rental and resale potential'],
    ],
    'knock-down-rebuilds' => [
        'title' => 'Knock Down Rebuilds',
        'img' => asset('images/services/knock-down.webp'),
        'intro' => 'Transforming existing properties into modern, efficient homes.',
        'body' => 'Love your location but not your home? A knock down rebuild lets you stay in the neighbourhood you love while building a brand new home tailored to your needs. We manage the entire process, from demolition through to handover, making it seamless and stress-free.',
        'points' => ['Stay in your preferred location', 'Brand new, modern home', 'Full demolition management', 'Energy-efficient design', 'End-to-end project handling'],
    ],
    'granny-flats' => [
        'title' => 'Granny Flats',
        'img' => asset('images/services/granny-flats.webp'),
        'intro' => 'Versatile living solutions designed for comfort and convenience.',
        'body' => 'Our granny flats are perfect for extended family, guests or additional rental income. Thoughtfully designed to feel spacious and comfortable, they deliver self-contained living within a compact, cost-effective footprint.',
        'points' => ['Self-contained living spaces', 'Great additional income stream', 'Comfortable, functional layouts', 'Fast, cost-effective builds', 'Quality inclusions as standard'],
    ],
    'why-build-with-us' => [
        'title' => 'Why Build With Us?',
        'img' => asset('images/hero/banner-1.webp'),
        'intro' => 'Building with Nivi Homes means partnering with a team that treats your home like their own.',
        'body' => 'We combine quality craftsmanship, honest communication and innovative design to deliver homes that stand the test of time. From your first consultation to the day you receive your keys, we guide you with transparency and care at every stage.',
        'points' => ['Experienced, dedicated team', 'Prestigious inclusions as standard', 'Transparent, fixed pricing', 'Meticulous attention to detail', 'Exceptional customer service'],
    ],
];

$key = $_GET['s'] ?? 'custom-homes';
$svc = $SERVICES[$key] ?? $SERVICES['custom-homes'];
$PAGE_TITLE = $svc['title'] . ' | Nivi Homes';
require __DIR__ . '/includes/header.php';
?>

<?php
$BANNER_TITLE = $svc['title'];
$BANNER_CRUMB = '<a href="index.php">Home</a> &gt; <a href="services.php">Our Services</a> &gt; ' . $svc['title'];
$BANNER_IMG = asset('images/banners/services-banner.webp');
require __DIR__ . '/includes/banner.php';
?>

<section class="section">
    <div class="container">
        <div class="detail-grid">
            <div class="detail-media" data-reveal="left">
                <img src="<?php echo $svc['img']; ?>" alt="<?php echo $svc['title']; ?>" loading="lazy">
            </div>
            <div class="detail-content" data-reveal="right">
                <span class="section-eyebrow">Service</span>
                <h2><?php echo $svc['title']; ?></h2>
                <p><strong><?php echo $svc['intro']; ?></strong></p>
                <p><?php echo $svc['body']; ?></p>
                <ul class="check-list">
                    <?php foreach ($svc['points'] as $p): ?>
                    <li><?php echo $p; ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="contact.php" class="btn btn-primary" style="margin-top:10px;">Enquire Now</a>
            </div>
        </div>
    </div>
</section>

<section class="section section-soft">
    <div class="container text-center" data-reveal>
        <h2 class="section-title">Explore Our Other Services</h2>
        <div class="designs-tabs" style="margin-top:30px;">
            <a href="service-details.php?s=custom-homes" class="design-tab">Custom Homes</a>
            <a href="service-details.php?s=duplex-homes" class="design-tab">Duplex Homes</a>
            <a href="service-details.php?s=knock-down-rebuilds" class="design-tab">Knock Down Rebuilds</a>
            <a href="service-details.php?s=granny-flats" class="design-tab">Granny Flats</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
