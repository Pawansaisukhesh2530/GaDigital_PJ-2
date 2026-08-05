<?php
$CURRENT_PAGE = 'designs';
$PAGE_TITLE = 'Home Designs | Nivi Homes';
require __DIR__ . '/includes/header.php';

$designs = [
    ['name' => 'Akira 21',   'type' => 'single', 'beds' => 4, 'baths' => 2.5, 'cars' => 2, 'img' => 'akira-21',  'pdf' => asset('pdfs/akira-21.pdf')],
    ['name' => 'Melora 31',  'type' => 'single', 'beds' => 4, 'baths' => 2.5, 'cars' => 2, 'img' => 'melora-31', 'pdf' => asset('pdfs/meliora.pdf')],
    ['name' => 'Sophea 22',  'type' => 'single', 'beds' => 3, 'baths' => 2.5, 'cars' => 2, 'img' => 'sophea-22', 'pdf' => asset('pdfs/sophea-22.pdf')],
    ['name' => 'Elyra 35',   'type' => 'double', 'beds' => 5, 'baths' => 3,   'cars' => 2, 'img' => 'elyra-35',  'pdf' => asset('pdfs/elyra.pdf')],
    ['name' => 'Olympia 39', 'type' => 'double', 'beds' => 5, 'baths' => 3,   'cars' => 2, 'img' => 'olympia-39','pdf' => asset('pdfs/olympia-39.pdf')],
    ['name' => 'Chiron 28',  'type' => 'double', 'beds' => 5, 'baths' => 3,   'cars' => 2, 'img' => 'chiron-28', 'pdf' => asset('pdfs/chiron-28.pdf')],
];

$slug = function ($n) { return strtolower(str_replace(' ', '-', $n)); };

$ic_bed  = '<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M21 10.78V8a2 2 0 0 0-2-2h-5a2 2 0 0 0-1 .28A2 2 0 0 0 10 6H5a2 2 0 0 0-2 2v2.78A2 2 0 0 0 2 12.5V19h2v-2h16v2h2v-6.5a2 2 0 0 0-1-1.72ZM11 10H5V8h5a1 1 0 0 1 1 1Zm8 0h-6V9a1 1 0 0 1 1-1h5Z"/></svg>';
$ic_bath = '<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M7 8a2 2 0 0 1 4 0h2a4 4 0 0 0-8 0v4H2v3a4 4 0 0 0 3 3.87V21h2v-2h10v2h2v-2.13A4 4 0 0 0 22 15v-3H7Zm13 7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-1h16Z"/></svg>';
$ic_car  = '<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M18.92 6.01A1.5 1.5 0 0 0 17.5 5h-11a1.5 1.5 0 0 0-1.42 1.01L3 12v8a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1h12v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-8ZM6.85 7h10.3l1.04 3H5.81ZM6.5 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm11 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Z"/></svg>';
?>

<!-- ===================== HERO (dark) ===================== -->
<section class="ss-hero" style="background-image:url('<?php echo asset('images/designs/hero-single.webp'); ?>')">
    <div class="container">
        <h2 data-anim="fadeInUp">Home Designs</h2>
        <p data-anim="fadeInUp">Explore our thoughtfully designed home plans, crafted for modern living. From single storey simplicity to double storey grandeur, each design reflects quality craftsmanship and functional elegance.</p>
    </div>
</section>

<!-- ===================== FILTER BAR ===================== -->
<section class="ss-filter">
    <div class="container">
        <div class="ss-filter-group">
            <h4>Bed Rooms:</h4>
            <div class="ss-opts">
                <button class="ss-opt" data-key="bed" data-val="3">3</button>
                <button class="ss-opt" data-key="bed" data-val="4">4</button>
                <button class="ss-opt" data-key="bed" data-val="5">5</button>
                <button class="ss-opt is-active" data-key="bed" data-val="all">All</button>
            </div>
        </div>
        <div class="ss-filter-group">
            <h4>Bath Rooms:</h4>
            <div class="ss-opts">
                <button class="ss-opt" data-key="bath" data-val="2.5">2.5</button>
                <button class="ss-opt" data-key="bath" data-val="3">3</button>
                <button class="ss-opt is-active" data-key="bath" data-val="all">All</button>
            </div>
        </div>
        <div class="ss-filter-group">
            <h4>Cars:</h4>
            <div class="ss-opts">
                <button class="ss-opt" data-key="cars" data-val="2">2</button>
                <button class="ss-opt is-active" data-key="cars" data-val="all">All</button>
            </div>
        </div>
        <div class="ss-filter-group">
            <h4>Type:</h4>
            <div class="ss-opts">
                <button class="ss-opt" data-key="type" data-val="single">Single</button>
                <button class="ss-opt" data-key="type" data-val="double">Double</button>
                <button class="ss-opt is-active" data-key="type" data-val="all">All</button>
            </div>
        </div>
    </div>
</section>

<!-- ===================== PROJECT CARDS ===================== -->
<section class="ss-projects">
    <div class="container">
        <div class="ss-grid">
            <?php foreach ($designs as $d): ?>
            <article class="ss-card" data-bed="<?php echo $d['beds']; ?>" data-bath="<?php echo $d['baths']; ?>" data-cars="<?php echo $d['cars']; ?>" data-type="<?php echo $d['type']; ?>" data-anim="fadeInUp">
                <div class="ss-card-media">
                    <a href="design-details.php?d=<?php echo $slug($d['name']); ?>">
                        <img src="<?php echo asset('images/designs/' . $d['img'] . '.webp'); ?>" alt="<?php echo $d['name']; ?>" loading="lazy">
                    </a>
                </div>
                <div class="ss-card-body">
                    <h2 class="ss-card-title"><?php echo $d['name']; ?></h2>
                    <div class="ss-specs">
                        <span class="ss-spec"><?php echo $ic_bed; ?><em><?php echo $d['beds']; ?></em></span>
                        <span class="ss-spec"><?php echo $ic_bath; ?><em><?php echo $d['baths']; ?></em></span>
                        <span class="ss-spec"><?php echo $ic_car; ?><em><?php echo $d['cars']; ?></em></span>
                    </div>
                    <div class="ss-card-actions">
                        <a class="ss-btn" href="design-details.php?d=<?php echo $slug($d['name']); ?>">Click here</a>
                        <a class="ss-btn-outline" href="<?php echo $d['pdf']; ?>" target="_blank" rel="noopener">Download Pdf</a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
(function () {
    var opts = document.querySelectorAll('.ss-opt');
    var cards = document.querySelectorAll('.ss-card');
    var active = { bed: 'all', bath: 'all', cars: 'all', type: 'all' };
    function apply() {
        cards.forEach(function (c) {
            var show = Object.keys(active).every(function (k) {
                return active[k] === 'all' || c.getAttribute('data-' + k) === active[k];
            });
            c.style.display = show ? '' : 'none';
        });
    }
    opts.forEach(function (o) {
        o.addEventListener('click', function () {
            var key = o.getAttribute('data-key');
            active[key] = o.getAttribute('data-val');
            document.querySelectorAll('.ss-opt[data-key="' + key + '"]').forEach(function (s) { s.classList.remove('is-active'); });
            o.classList.add('is-active');
            apply();
        });
    });
})();
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
