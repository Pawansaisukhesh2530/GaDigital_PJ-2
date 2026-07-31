<?php
$CURRENT_PAGE = 'inclusions';
$PAGE_TITLE = 'Our Inclusions | Nivi Homes';
require __DIR__ . '/includes/header.php';

/* ---------- Helpers ---------- */
function inc_ul($items, $class = '') {
    echo '<ul class="inc-ul' . ($class ? ' ' . $class : '') . '">';
    foreach ($items as $it) {
        echo '<li>' . $it . '</li>';
    }
    echo '</ul>';
}

/* ---------- Content (verbatim from live site) ---------- */
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
    'Polyurethane bulkhead above overhead cupboards.',
    'Pot drawer below microwave',
    'Designer splash-back glass to underside of overhead cupboards.',
    'Splash Back Window (On Selected Designs',
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
    'underground three-phase electrical connection to the home up to 6 meters',
    'Up to 50 LED ceiling lights throughout the house',
    'Clipsal iconic series PowerPoints up to 35, including indoor and outdoor',
    '2 fa&ccedil;ade lights provision',
    'Clipsal 2 smoke detector Alaram',
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
    'Tiles To The Alfersco',
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
    'Corinthian 2040mm Flush Pre-hung Hollow Core Door With Paint Finis',
    'Panel Lift Garage Door With 2 Remote Controls And A Wall Mount',
    'Panel Lift Garage Door With 2 Remote Controls And A Wall Mount',
    'Gainsborough Angular Passage Set Door Handles',
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
    'Benchtop and Cabinetry' => [
        '40mm Engineered Stone to Kitchen Benchtop',
        '20mm Engineered Stone to Vanities',
        'Polyurethane Finish to Kitchen Cabinets and Overhead Cupboards',
        'Soft Close Action to Drawers and Cabinet Doors',
        'Pull-Out Kitchen Sink Mixer Tap',
        'Designer Glass/Window Splashback to Underside of Overhead Cupboards',
    ],
    'Pantry and Fridge' => [
        'Timber Hinged Door (as per plans)',
        '4 Rows of Solid Melamine Shelving in Walk-In Pantry (if shown on plans)',
        'Fridge Provision with Water Connection and Overhead Cupboard',
    ],
    'Laundry' => [
        'Quality Laundry Cabinetry with Stone Benchtop',
        '45-liter Stainless Steel Sink with Mixer',
        'Tiled Splashback Above Benchtop (1 tile height, 600&times;600 tile size)',
        'Skirting Tiles to the Remainder of Laundry Walls (1 tile height)',
    ],
    'Electrical' => [
        '3 Television/Data Points',
        '30 Power Points (internal and external)',
        '50 LED Ceiling Downlights Throughout the House',
        '2-Way Light Switch on Staircase and Hallway',
        '2 Up-Down Lights on Front Fa&ccedil;ade',
        '3 Breakfast Light Provisions Over Kitchen Island',
        'Sunshine 3-in-1 Fan, Light, and Heat Lamp in Ensuite and Bathrooms',
        '2 Smoke Detectors',
        'Earth Leakage Safety Switches in Meter Box',
        'Circuit Breakers in Meter Box',
    ],
    'Additional Features' => [
        'Sliding Door to Rear of Living or Dining',
        '1 Gas Point and 1 Power Point',
        'Covered Alfresco with Recessed Ceiling',
        'Tiled Alfresco Area',
    ],
    'Bathroom &amp; Ensuite' => [
        '20mm Stone Bench-Tops to Wall-Hung Vanity',
        'Designer Ceramic Bowls',
        'Soft Close Action to Drawers',
        'Coloured Plug and Waste to Outlets',
        'Polyurethane to Vanity Cupboards',
        'Stylish Chrome Single Lever Mixers to Vanities, Bathtub, and Showers',
        'Modern Rimless Toilet Suite with Soft Close Action Seats',
        'Tiled Shower Niche to Ensuite and Bathrooms',
        '2-in-1 Dual Hand-Held Multi-Function Shower Head Set',
        'Frameless Polished Edge Mirrors &ndash; 600mm Wide',
        'Deluxe Free Standing Bathtub up to 1700mm Length (Main Bathroom Only)',
        'Semi Frameless Shower Screen with Pivot Door (Clear Toughened Safety Glass)',
        'Coloured Double Towel Rails, Rings, and Toilet Roll Holders',
        'Chrome Single Lever Mixer',
    ],
    'Safety &amp; Security' => [
        'Burglar System',
        'Remote Control to Panel Lift Garage Door',
        'Video Intercom Bell',
    ],
    'Main Entrance Door' => [
        'Hinged Sunburst Entrance Door &ndash; 1200mm x 2400mm (Builder Range Solid Timber)',
        'Modern Push &amp; Pull Handle (Builder Range)',
        'Digital Lock',
    ],
    'Air Conditioning' => [
        'Ducted Daikin Air Conditioning with 4 Zones and 2 Controllers',
    ],
    'Internal' => [
        '2700mm Height to Ground Floor and 2450mm to First Floor (in lieu of standard)',
        'Walk-in Robe with 1 Set of Drawers and Shelves',
        '2 Sliding Mirror Robes with Single Shelf to Other Bedrooms',
    ],
    'External' => [
        'Engineer-Designed H1-Class Concrete with Waffle Pod Slab on Ground',
        'Engineered H2 Treated Timber Frame and Roof Trusses',
        'Face Brick from PGH or Austral with Off-White Mortar',
        'Colorbond Roof from Nivi Standard Range',
        'Maintenance-Free Metal Fascia and Gutter',
    ],
    'Balcony' => [
        'Frameless Glass Balustrade to External Balcony as Per the Plan (applicable for double-storey only)',
    ],
    'Alfresco ' => [
        'Provide Sliding Door to Rear of Living/Dining',
        'Provide 1 Gas and 1 Double PowerPoint',
        'Provide Covered (Roof Over) Alfresco with Recessed Ceiling',
        'Provide Tiles to the Concrete Slab',
    ],
    'Flooring and Wall Tiles' => [
        '600&times;600 Tiles to Main House, 300&times;600 to Washrooms and Ensuites',
        'Full-Height Tiling to Entire Bathrooms from Nivi &ldquo;Standard&rdquo; Range',
        'Provide 1 Height Tile Skirting to WC/Powder Room',
        'Ceramic Floor Tiles to Entry, Lounge, Living, Kitchen, Walk-In Pantry, and Dining',
        'Wall-to-Wall Laminate or Carpet from Nivi Standard Range',
    ],
    'Other Essentials' => [
        'BASIX Assessment and Fees',
        'Fire Retardant Sarking to Underside of Roof Tiles',
        'Slimline Rainwater Tank (Size to Be Determined as per BASIX)',
        'Ceiling Insulation R3.5',
        'Wall Insulation R2.0',
        '1 Gas Bayonet Point to Living Area',
        'Rheem Continuous Flow Gas Hot Water System',
    ],
    'Staircase and Staircase Balustrade' => [
        'Timber Handrail (Stained) with Vertical Metal Balustrade',
        'MDF Treads and Risers (for Carpet or Balcony Laminated Timber Floor) &ndash; applicable for double-storey only',
    ],
    'Paint' => [
        'Dulux Three-Coat Paint System to Walls',
        'Two-Coat Paint System to Ceilings',
        '2 Feature Walls',
    ],
    'Driveway' => [
        'Coloured Concrete Driveway &ndash; 40sqm for Single Garage, 50sqm for Double Garage',
    ],
];

$disclaimer = 'Photographs &amp; graphic impressions are representative only to finishes available and may not reflect the finish to the standard design and may also depict fixtures, finishes &amp; features not supplied by Nivi Homes, such as, but not limited to, landscaping &amp; outdoor features, furnishing, decorative items, and window furnishing. Dimensions are approximate only and may vary according to fa&ccedil;ade. Nivi Homes reserves the right to revise specifications, inclusions, materials, and suppliers without notice.';
?>

<!-- ===================== PAGE BANNER ===================== -->
<?php
$BANNER_TITLE = 'Our Inclusions';
$BANNER_CRUMB = '<a href="index.php">Home</a> &gt; Our Inclusions';
$BANNER_IMG = asset('images/banners/inclusions-banner.webp');
require __DIR__ . '/includes/banner.php';
?>

<!-- ===================== STANDARD INCLUSIONS ===================== -->
<section class="inc-sec">
    <div class="container">
        <div class="inc-standard" data-anim="fadeIn">
            <h2 class="inc-eyebrow">Standard Inclusions</h2>
            <h2 class="inc-h2">Create Beautiful Memories Every Day</h2>
            <h2 class="inc-h3">A Journey With Nirvana</h2>
            <p class="inc-lead">Our inclusions are prestigious for other builders but STANDARD for our build.</p>
            <?php inc_ul($standard, 'disc'); ?>
        </div>
    </div>
</section>

<!-- ===================== KITCHEN / BATHROOM ===================== -->
<section class="inc-sec">
    <div class="container">
        <div class="inc-two">
            <div class="inc-col" data-anim="fadeIn">
                <h2 class="inc-head">Kitchen</h2>
                <p class="inc-intro">Functional, yet modern kitchen with these inclusions:</p>
                <?php inc_ul($kitchen); ?>
                <img class="inc-img inc-img-lg" src="<?php echo asset('images/inclusions/Picture.webp'); ?>" alt="Kitchen" loading="lazy">
                <img class="inc-img inc-img-lg" src="<?php echo asset('images/inclusions/Picture-1.webp'); ?>" alt="Kitchen" loading="lazy">
            </div>
            <div class="inc-col" data-anim="fadeIn">
                <h2 class="inc-head">Bathroom</h2>
                <div class="inc-img-row">
                    <img class="inc-img" src="<?php echo asset('images/inclusions/Picture-2.webp'); ?>" alt="Bathroom" loading="lazy">
                    <img class="inc-img" src="<?php echo asset('images/inclusions/Picture-3.webp'); ?>" alt="Bathroom" loading="lazy">
                </div>
                <?php inc_ul($bathroom); ?>
                <h2 class="inc-head inc-head-mt">Bespoke Bathroom Features For Your Home</h2>
                <?php inc_ul($bespoke); ?>
            </div>
        </div>
    </div>
</section>

<!-- ===================== ELECTRICAL ===================== -->
<section class="inc-sec">
    <div class="container">
        <div class="inc-two inc-two-mid">
            <div class="inc-col" data-anim="fadeIn">
                <h2 class="inc-head">Electrical</h2>
                <?php inc_ul($electrical); ?>
            </div>
            <div class="inc-col inc-col-img" data-anim="fadeIn">
                <img class="inc-img inc-img-xl" src="<?php echo asset('images/inclusions/Picture-4-911x1024.webp'); ?>" alt="Electrical" loading="lazy">
            </div>
        </div>
    </div>
</section>

<!-- ===================== ALFRESCO / LAUNDRY ===================== -->
<section class="inc-sec">
    <div class="container">
        <div class="inc-two">
            <div class="inc-col" data-anim="fadeIn">
                <h2 class="inc-head">Alfresco</h2>
                <?php inc_ul($alfresco); ?>
                <img class="inc-img inc-img-xl" src="<?php echo asset('images/inclusions/Picture-6-1024x583.webp'); ?>" alt="Alfresco" loading="lazy">
            </div>
            <div class="inc-col" data-anim="fadeIn">
                <h2 class="inc-head">Laundry</h2>
                <?php inc_ul($laundry); ?>
                <img class="inc-img inc-img-xl" src="<?php echo asset('images/inclusions/Picture-5-1024x568.webp'); ?>" alt="Laundry" loading="lazy">
            </div>
        </div>
    </div>
</section>

<!-- ===================== EXTERNAL FACADE / DOORS ===================== -->
<section class="inc-sec">
    <div class="container">
        <div class="inc-three">
            <div class="inc-col" data-anim="fadeIn">
                <h2 class="inc-head">External Facade</h2>
                <?php inc_ul($facade); ?>
            </div>
            <div class="inc-col inc-col-img" data-anim="fadeIn">
                <img class="inc-img inc-img-mid" src="<?php echo asset('images/inclusions/Picture-7-850x1024.webp'); ?>" alt="External facade" loading="lazy">
            </div>
            <div class="inc-col" data-anim="fadeIn">
                <h2 class="inc-head">Doors &amp; Handles</h2>
                <?php inc_ul($doors); ?>
            </div>
        </div>
    </div>
</section>

<!-- ===================== DETAILED GRID ===================== -->
<section class="inc-sec">
    <div class="container">
        <div class="inc-grid">
            <?php
            $keys = array_keys($grid);
            $columns = [
                array_slice($keys, 0, 6),
                array_slice($keys, 6, 6),
                array_slice($keys, 12),
            ];
            foreach ($columns as $ci => $col): ?>
            <div class="inc-grid-col" data-anim="fadeIn">
                <?php foreach ($col as $k): ?>
                <div class="inc-block">
                    <h2 class="inc-gold"><?php echo trim($k); ?></h2>
                    <?php inc_ul($grid[$k], 'disc'); ?>
                </div>
                <?php endforeach; ?>
                <?php if ($ci === 2): ?>
                <div class="inc-block">
                    <h2 class="inc-gold">Disclaimer</h2>
                    <p class="inc-disclaimer-text"><?php echo $disclaimer; ?></p>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
