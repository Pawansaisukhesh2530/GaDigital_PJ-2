<?php
$CURRENT_PAGE = 'inclusions';
$PAGE_TITLE = 'Our Inclusions | Nivi Homes';
require __DIR__ . '/includes/header.php';

/* ---------- Content arrays (verbatim from live site) ---------- */
$standard = [
    'H1 waffle pod slab.',
    '2700mm ceiling height to ground floor and 2400mm ceiling height in first floor.',
    'Color bond roof.',
    'Linear 90mm cornice throughout home (excludes bathroom, ensuites, and WC).',
    'Stained timber handrail and Newel post with MDF Riser and treads for laminated timber or carpet finish. Metal balustrade applicable for double-storey houses.',
    'DAIKIN ducted air conditioning system.',
    '2x 2040mm stacker door to family and balcony.',
    '600x600mm tiles throughout the ground floor, including porch, balcony &amp; alfresco.',
    'Premium Carpet or laminate flooring throughout the 1st floor.',
    'Downlight throughout the house.',
    'Bosch appliances (builder&rsquo;s range).',
    'Polytec walk in wardrobe for master bedroom',
];

$kitchen = [
    '40mm engineered stone benchtops and waterfall model island from builder&rsquo;s range.',
    'Polyurethane to kitchen bench and overhead cupboards with soft close action to drawers and cabinet doors.',
    'Designer pull-out kitchen sink mixer tap.',
    'Pot drawer below microwave',
    'Designer splash-back glass to underside of overhead cupboards.',
    'Splash Back Window (On Selected Designs)',
    'Double bowl undermount sink.',
    '4 rows of solid Melamine shelving for walk in pantry (if applicable).',
    'Fridge provision with water connection and overhead cupboard',
    'Builder&rsquo;s range 900mm Bosch cooktop',
    '900mm integrated range hood.',
    'Freestanding Dishwasher.',
    'Two pull-out bins.',
];

$bathroom = [
    '300x600mm floor to ceiling tiles to all bathrooms.',
    '20mm vanity bench tops (builder&rsquo;s range)',
    'Polyurethane to vanity cupboards with soft close action to drawers and cabinet doors.',
    'Client choice of tap colour, builder&rsquo;s range chrome/ gunmetal/ black/gold',
    'Oval 400 top mount basin with pop-up waste of client&rsquo;s colour choice (builder&rsquo;s range) with single lever mixtures.',
    '6mm polished edge mirrors with one row of tiles between vanity and underside of mirror',
    'Rimless toilet suite with soft close action seats',
    'Deluxe free-standing bathtub up to 1700mm length (main bathroom only).',
    '200mm square rainfall- from builder&rsquo;s range tapware colour.',
];

$bespoke = [
    '200mm Square Rainfall Twin Shower Station Top Water Inlet',
    'Plush Tall Basin Mixer',
    'Round Swivel Sink Mixer.',
    'Large Drop-in Laundry Tub 600L X 500W X250H',
    'Wall Mixer for Bath Spout',
    'Wall Mixer for Shower',
    '600mm Double Towel Rail.',
    'Round Hand Towel Ring.',
    'Toilet Roll Holder.',
    'Bathroom Vanity Counter Bench Top Basin Wash Bowl.',
    'Rheem 26L Gas Continuous Flow with Recess Box.',
    'Slimline Rainwater tank',
];

$electrical = [
    'Underground three-phase electrical connection to the home up to 6 meters',
    'Up to 50 LED ceiling lights throughout the house',
    'Clipsal iconic series PowerPoints up to 35, including indoor and outdoor',
    '2 fa&ccedil;ade lights provision',
    'Clipsal 2 smoke detector Alarm',
    'LED lights to garage',
    'Sunshine 3 in 1 Fan, light heat lamp to ensuite and bathrooms.',
    'Earth Leakage safety switches to meter box, circuit breakers to meter box.',
    '2 data points',
    '3 kitchen island power points',
    '2 outdoor sensor lights',
    'Power point and noggins for void chandelier',
    'Micron Intercom system',
    'Electrical meter box Recessed into the wall',
];

$alfresco = [
    'Sliding Door To Rear Of Living Or Dining',
    '1 Gas And 1 Power-Point',
    'Covered (Roof Over) Alfresco With Recessed Ceiling',
    'Tiles To The Alfresco',
];

$laundry = [
    'Quality Laundry Cabinetry With Stone Benchtop and 45 Liter Stainless Steel Sink With Mixer',
    'Tiled Splash Back Above Bench Top (1 Tile Height)',
    'Skirting Tiles To Be Installed To Rest Of Laundry Walls (1 Tile Height)',
];

$facade = [
    'T2 Blue Termite Protected Structural Pine Framing And Roof Trusses',
    'Colour bond roof(pitch roof)',
    'Translucent Glazing To Bathroom, Ensuite, Powder Room And WC Windows',
    'Nivi Homes Exclusive Range Austral And PGH Bricks From For A Brick Veneer Construction',
    'Maintenance Free Fascia And Gutter',
    'Glass Balustrade To Balcony (Subject To Design) (Applicable for double Storey home only)',
    'Acrylic Render To Front Pillars H. 6mm Thick Glass To Aluminum Windows And Sliding Doors',
    'Colour Concrete Driveway 40sqm for single garage and 50sqm for double garage',
    'Builder range choice of garage door with 2 remotes',
];

$doors = [
    '2340mm X 1200mm High Stained Front Entry Door With solid Timber(from Builder Range)',
    'Solid Timber Frame With Full Glass To Pantry',
    'Solid External Door With Half Duracote Clear Glass',
    'Corinthian 2040mm Flush Pre-hung Hollow Core Door With Paint Finish',
    'Panel Lift Garage Door With 2 Remote Controls And A Wall Mount',
    'Gainsborough Angular Internal Privacy Set Door Handles To Bathroom, Ensuite, Powder Room, WC And Master Bedroom',
    '75mm Wall Stops with Rubber Buffer In Satin Stainless Finish To All Internal Access Doors',
    '600mm Chrome Back-To-Back Pull Handle with Gainsborough Smooth Square Double Cylinder Deadbolt And Gainsborough Heavy Duty Roller To Front Entry Door',
    'Black or Chrome Hardware.',
];

/* ---------- Detailed grid ---------- */
$grid = [
    'Kitchen Appliances &ndash; Bosch (Builder Range)' => [
        '900mm Cooktop', '900mm Rangehood', 'Dishwasher', '600mm Microwave Oven', '600mm Oven',
    ],
    'Bathroom &amp; Ensuite' => [
        '20mm stone bench-tops to vanity',
        'Designer ceramic bowls',
        'Soft close action to drawers',
        'Coloured plug and waste to outlets',
        'Stylish chrome single lever mixers',
        'Modern rimless toilet suite',
        'Tiled shower niche',
        '2-in-1 dual hand-held shower head set',
        'Frameless polished edge mirrors &ndash; 600mm',
        'Deluxe free-standing bathtub to 1700mm',
        'Semi frameless shower screen, pivot door',
        'Coloured double towel rails &amp; rings',
    ],
    'Balcony' => [
        'Frameless glass balustrade to external balcony as per plan (double-storey only)',
    ],
    'Alfresco' => [
        'Sliding door to rear of living/dining',
        '1 gas and 1 double power point',
        'Covered (roof over) alfresco, recessed ceiling',
        'Tiles to the concrete slab',
    ],
    'Flooring &amp; Wall Tiles' => [
        '600&times;600 tiles to main house, 300&times;600 to washrooms/ensuites',
        'Full-height tiling to bathroom &ndash; Nivi Standard range',
        '1 height tile skirting to WC / powder room',
        'Ceramic floor tiles to entry, lounge, living, kitchen, pantry, dining',
        'Wall-to-wall laminate or carpet &ndash; Nivi Standard range',
    ],
    'Other Essentials' => [
        'BASIX assessment and fees',
        'Fire retardant sarking under roof tiles',
        'Slimline rainwater tank (per BASIX)',
        'Ceiling insulation R3.5',
        'Wall insulation R2.0',
        '1 gas bayonet point to living area',
        'Rheem continuous flow gas hot water',
    ],
    'Benchtop &amp; Cabinetry' => [
        '40mm engineered stone to kitchen benchtop',
        '20mm engineered stone to vanities',
        'Polyurethane finish to kitchen cabinets',
        'Soft close action to drawers and doors',
        'Pull-out kitchen sink mixer tap',
        'Designer glass splashback',
    ],
    'Pantry &amp; Fridge' => [
        'Timber hinged door (as per plans)',
        '4 row melamine shelving in walk-in pantry',
        'Fridge provision with water connection and overhead cupboard',
    ],
    'Laundry' => [
        'Quality laundry cabinetry with stone benchtop',
        '45L stainless steel sink with mixer tap',
        'Tiled splashback above bench top',
        'Skirting tiles to remainder of walls',
    ],
    'Electrical' => [
        '2 television / data points',
        '35 power points, internal and external',
        '50 LED ceiling downlights throughout',
        '2-way light switch on staircase and hallway',
        '2 up-down lights on front facade',
        '3 breakfast light provisions over island',
        'Sunshine 3-in-1 fan, light and heat lamp',
        '2 smoke detectors',
        'Earth leakage switches in meter box',
    ],
    'Safety &amp; Security' => [
        'Burglar system',
        'Remote control to panel lift garage door',
        'Video intercom bell',
    ],
    'Main Entrance Door' => [
        'Hinged sunburst entrance door &ndash; 1200 x 2400mm, builder range solid timber',
        'Modern push &amp; pull handle',
        'Digital lock',
    ],
    'Air Conditioning' => [
        'Ducted Daikin air conditioning with 4 zones and 2 controllers',
    ],
    'Internal' => [
        '2700mm ceiling to ground floor, 2400mm to first floor',
        'Walk-in robe with 2 sets of drawers and shelves',
        '2 sliding mirror robes to all other bedrooms',
    ],
    'External' => [
        'Engineer-designed waffle pod slab on ground',
        'Engineered H2 treated timber frame and roof trusses',
        'Face brick from PGH or Austral, off-white mortar',
        'Colorbond roof &ndash; Nivi Standard range',
        'Maintenance-free metal fascia and gutter',
    ],
    'Staircase &amp; Balustrade' => [
        'Timber handrail (stained) with vertical metal balustrade',
        'MDF treads and risers for carpet or laminated timber floor &ndash; double-storey only',
    ],
    'Paint' => [
        'Dulux three-coat paint system to walls',
        'Two-coat paint system to ceilings',
        '2 feature walls',
    ],
    'Driveway' => [
        'Coloured concrete driveway &ndash; 40sqm single garage, 50sqm double garage',
    ],
    'Additional Features' => [
        'Sliding door to rear of living or dining',
        '1 gas point and 1 power point',
        'Covered alfresco with recessed ceiling',
        'Tiled alfresco area',
    ],
];

$disclaimer = 'Photographs &amp; graphic impressions are representative only of finishes available and may not reflect the finish of the standard design, and may also depict features, finishes &amp; textures not supplied by Nivi Homes, such as but not limited to landscaping, pools and outdoor furnishing. Dimensions are approximate only and may vary according to fa&ccedil;ade selection. Nivi Homes reserves the right to revise specifications, inclusions, materials and suppliers without notice.';

/* Sidebar nav items */
$nav_items = [
    ['num' => '01', 'label' => 'Standard', 'id' => 'standard'],
    ['num' => '02', 'label' => 'Kitchen', 'id' => 'kitchen'],
    ['num' => '03', 'label' => 'Bathroom', 'id' => 'bathroom'],
    ['num' => '04', 'label' => 'Electrical', 'id' => 'electrical'],
    ['num' => '05', 'label' => 'Alfresco &amp; Laundry', 'id' => 'alfresco-laundry'],
    ['num' => '06', 'label' => 'Facade &amp; Doors', 'id' => 'facade-doors'],
    ['num' => '07', 'label' => 'Full Schedule', 'id' => 'full-schedule'],
    ['num' => '08', 'label' => 'Disclaimer', 'id' => 'disclaimer'],
];
?>

<!-- ===================== PAGE BANNER ===================== -->
<?php
$BANNER_TITLE = 'Our Inclusions';
$BANNER_CRUMB = '<a href="index.php">Home</a> &gt; Our Inclusions';
$BANNER_IMG = asset('images/banners/inclusions-banner.webp');
require __DIR__ . '/includes/banner.php';
?>

<!-- ===================== INCLUSIONS CONTENT ===================== -->
<section class="inc-page">
    <div class="inc-layout">

        <!-- SIDEBAR NAV -->
        <aside class="inc-sidebar" id="inc-sidebar">
            <nav class="inc-nav">
                <?php foreach ($nav_items as $item): ?>
                <a href="#<?php echo $item['id']; ?>" class="inc-nav-link">
                    <span class="inc-nav-num"><?php echo $item['num']; ?></span>
                    <span class="inc-nav-label"><?php echo $item['label']; ?></span>
                </a>
                <?php endforeach; ?>
            </nav>
            <div class="inc-nav-note">
                <p>Every inclusion below is standard &mdash; not an upgrade &mdash; on every Nivi Homes build.</p>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="inc-main">

            <!-- SECTION 01: STANDARD -->
            <section class="inc-section" id="standard" data-anim="fadeIn">
                <div class="inc-section-header">
                    <div class="inc-label"><span class="inc-label-line"></span><span class="inc-label-text">01 &middot; Foundation to Finish</span></div>
                    <h2 class="inc-title">Standard Inclusions</h2>
                    <p class="inc-subtitle"><em>Create beautiful memories every day</em> &mdash; a journey with Nivi. Our inclusions are prestigious for other builders, but standard for our build.</p>
                </div>
                <div class="inc-list">
                    <?php foreach ($standard as $item): ?>
                    <div class="inc-list-item"><span class="inc-dash"></span><span><?php echo $item; ?></span></div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- SECTION 02: KITCHEN -->
            <section class="inc-section" id="kitchen" data-anim="fadeIn">
                <div class="inc-section-header">
                    <div class="inc-label"><span class="inc-label-line"></span><span class="inc-label-text">02 &middot; The Heart of the Home</span></div>
                    <h2 class="inc-title">Kitchen</h2>
                    <p class="inc-subtitle">Functional, yet modern &mdash; every Nivi kitchen ships with these inclusions as standard.</p>
                </div>
                <div class="inc-list">
                    <?php foreach ($kitchen as $item): ?>
                    <div class="inc-list-item"><span class="inc-dash"></span><span><?php echo $item; ?></span></div>
                    <?php endforeach; ?>
                </div>
                <div class="inc-image-pair">
                    <div class="inc-image-card">
                        <img src="<?php echo asset('images/inclusions/Picture.webp'); ?>" alt="Engineered Stone &amp; Waterfall Island" loading="lazy">
                    </div>
                    <div class="inc-image-card">
                        <img src="<?php echo asset('images/inclusions/Picture-1.webp'); ?>" alt="Pull-Out Mixer &amp; Cooktop" loading="lazy">
                    </div>
                </div>
            </section>

            <!-- SECTION 03: BATHROOM -->
            <section class="inc-section" id="bathroom" data-anim="fadeIn">
                <div class="inc-section-header">
                    <div class="inc-label"><span class="inc-label-line"></span><span class="inc-label-text">03 &middot; Wet Areas</span></div>
                    <h2 class="inc-title">Bathroom</h2>
                    <p class="inc-subtitle">Floor-to-ceiling tiling, soft-close cabinetry, and a tapware colour of your choosing across every wet area.</p>
                </div>
                <div class="inc-two-col">
                    <div>
                        <h3 class="inc-h3">Standard Fitout</h3>
                        <div class="inc-list inc-list-sm">
                            <?php foreach ($bathroom as $item): ?>
                            <div class="inc-list-item"><span class="inc-dash"></span><span><?php echo $item; ?></span></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div>
                        <h3 class="inc-h3">Bespoke Features</h3>
                        <div class="inc-list inc-list-sm">
                            <?php foreach ($bespoke as $item): ?>
                            <div class="inc-list-item"><span class="inc-dash"></span><span><?php echo $item; ?></span></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="inc-image-pair">
                    <div class="inc-image-card">
                        <img src="<?php echo asset('images/inclusions/Picture-2.webp'); ?>" alt="300×600 Wet-Area Tile" loading="lazy">
                    </div>
                    <div class="inc-image-card">
                        <img src="<?php echo asset('images/inclusions/Picture-3.webp'); ?>" alt="Bathroom Feature" loading="lazy">
                    </div>
                </div>
            </section>

            <!-- SECTION 04: ELECTRICAL -->
            <section class="inc-section" id="electrical" data-anim="fadeIn">
                <div class="inc-section-header">
                    <div class="inc-label"><span class="inc-label-line"></span><span class="inc-label-text">04 &middot; Power &amp; Light</span></div>
                    <h2 class="inc-title">Electrical</h2>
                    <p class="inc-subtitle">A three-phase connection and a lighting plan built for how the house is actually lived in.</p>
                </div>
                <div class="inc-split">
                    <div class="inc-list">
                        <?php foreach ($electrical as $item): ?>
                        <div class="inc-list-item"><span class="inc-dash"></span><span><?php echo $item; ?></span></div>
                        <?php endforeach; ?>
                    </div>
                    <div class="inc-image-card inc-image-card-tall">
                        <img src="<?php echo asset('images/inclusions/Picture-4-911x1024.webp'); ?>" alt="Statement Pendant Provision" loading="lazy">
                    </div>
                </div>
            </section>

            <!-- SECTION 05: ALFRESCO & LAUNDRY -->
            <section class="inc-section" id="alfresco-laundry" data-anim="fadeIn">
                <div class="inc-section-header">
                    <div class="inc-label"><span class="inc-label-line"></span><span class="inc-label-text">05 &middot; Living Beyond the Walls</span></div>
                    <h2 class="inc-title">Alfresco &amp; Laundry</h2>
                    <p class="inc-subtitle">Two of the most-used spaces in the home, finished to the same standard as everywhere else.</p>
                </div>
                <div class="inc-two-col">
                    <div>
                        <h3 class="inc-h3">Alfresco</h3>
                        <div class="inc-list inc-list-sm">
                            <?php foreach ($alfresco as $item): ?>
                            <div class="inc-list-item"><span class="inc-dash"></span><span><?php echo $item; ?></span></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div>
                        <h3 class="inc-h3">Laundry</h3>
                        <div class="inc-list inc-list-sm">
                            <?php foreach ($laundry as $item): ?>
                            <div class="inc-list-item"><span class="inc-dash"></span><span><?php echo $item; ?></span></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="inc-image-pair">
                    <div class="inc-image-card">
                        <img src="<?php echo asset('images/inclusions/Picture-6-1024x583.webp'); ?>" alt="Covered Alfresco" loading="lazy">
                    </div>
                    <div class="inc-image-card">
                        <img src="<?php echo asset('images/inclusions/Picture-5-1024x568.webp'); ?>" alt="Laundry Stone Benchtop" loading="lazy">
                    </div>
                </div>
            </section>

            <!-- SECTION 06: FACADE & DOORS -->
            <section class="inc-section" id="facade-doors" data-anim="fadeIn">
                <div class="inc-section-header">
                    <div class="inc-label"><span class="inc-label-line"></span><span class="inc-label-text">06 &middot; The Envelope</span></div>
                    <h2 class="inc-title">External Facade &amp; Doors</h2>
                    <p class="inc-subtitle">What the street sees, and what protects everything behind it.</p>
                </div>
                <div class="inc-two-col">
                    <div>
                        <h3 class="inc-h3">External Facade</h3>
                        <div class="inc-list inc-list-sm">
                            <?php foreach ($facade as $item): ?>
                            <div class="inc-list-item"><span class="inc-dash"></span><span><?php echo $item; ?></span></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div>
                        <h3 class="inc-h3">Doors &amp; Handles</h3>
                        <div class="inc-list inc-list-sm">
                            <?php foreach ($doors as $item): ?>
                            <div class="inc-list-item"><span class="inc-dash"></span><span><?php echo $item; ?></span></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="inc-image-pair">
                    <div class="inc-image-card">
                        <img src="<?php echo asset('images/inclusions/Picture-7-850x1024.webp'); ?>" alt="Face Brick Veneer" loading="lazy">
                    </div>
                    <div class="inc-image-card">
                        <img src="<?php echo asset('images/inclusions/Picture.webp'); ?>" alt="Solid Timber Entry Door" loading="lazy">
                    </div>
                </div>
            </section>

        </main>
    </div>
</section>

<!-- ===================== SECTION 07: FULL SPEC SUMMARY ===================== -->
<section class="inc-spec-section" id="full-schedule" data-anim="fadeIn">
    <div class="inc-spec-container">
        <div class="inc-spec-header">
            <div class="inc-label inc-label-dark"><span class="inc-label-line"></span><span class="inc-label-text">07 &middot; The Complete Schedule</span></div>
            <h2 class="inc-spec-title">Standard Specifications, <span class="inc-spec-accent">room by room</span></h2>
            <p class="inc-spec-subtitle">Everything above, indexed for reference &mdash; the same finish schedule your site supervisor works from.</p>
        </div>
        <div class="inc-spec-grid">
            <?php foreach ($grid as $heading => $items): ?>
            <div class="inc-spec-card">
                <h4 class="inc-spec-card-title"><?php echo $heading; ?></h4>
                <ul class="inc-spec-card-list">
                    <?php foreach ($items as $item): ?>
                    <li><span class="inc-spec-dash">&mdash;</span><?php echo $item; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===================== SECTION 08: DISCLAIMER ===================== -->
<section class="inc-disclaimer-section" id="disclaimer" data-anim="fadeIn">
    <div class="inc-disclaimer-container">
        <div class="inc-label"><span class="inc-label-line"></span><span class="inc-label-text">08 &middot; Fine Print</span></div>
        <p class="inc-disclaimer-body"><?php echo $disclaimer; ?></p>
    </div>
</section>

<script>
(function() {
    'use strict';
    var links = document.querySelectorAll('.inc-nav-link');
    var sections = document.querySelectorAll('.inc-section, .inc-spec-section, .inc-disclaimer-section');
    if (!links.length || !sections.length) return;

    function setActive() {
        var scrollY = window.scrollY + 120;
        var current = '';
        sections.forEach(function(sec) {
            if (sec.offsetTop <= scrollY) current = sec.id;
        });
        links.forEach(function(link) {
            link.classList.toggle('active', link.getAttribute('href') === '#' + current);
        });
    }
    window.addEventListener('scroll', setActive, { passive: true });
    setActive();

    // Smooth scroll for sidebar links
    links.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                window.scrollTo({ top: target.offsetTop - 20, behavior: 'smooth' });
            }
        });
    });
})();
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
