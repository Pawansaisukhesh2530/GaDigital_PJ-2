<?php
$CURRENT_PAGE = 'designs';
require_once __DIR__ . '/app/bootstrap.php';     // session + db + helpers
require_once __DIR__ . '/app/enquiries.php';
require_once __DIR__ . '/app/mail.php';
require_once __DIR__ . '/includes/config.php';   // $SITE / $NAV / asset()

/**
 * Design catalogue. `dims` is an ordered [label => value] map so the
 * dimensions table renders in the same order as the source design sheet.
 */
$DESIGNS = [
    'elyra-35' => [
        'name' => 'Elyra 35', 'type' => 'Double Storey', 'beds' => 5, 'baths' => 3, 'cars' => 2,
        'img' => 'elyra-35', 'pdf' => asset('pdfs/elyra.pdf'),
        'desc' => 'The Elyra-35 brings grandeur to your doorstep with an elevated lifestyle across two expansive levels. With a luxurious master suite, spacious living areas, and a striking facade, this design is built for those who love to entertain, host, and truly enjoy home life.',
        'dims' => ['Internal' => '255.58 M&sup2;', 'Garage' => '34.22 M&sup2;', 'Alfresco' => '16.98 M&sup2;', 'Porch' => '9.31 M&sup2;', 'Balcony' => '7.19 M&sup2;', 'Min.Lot Width' => '10 M', 'Min.Lot Depth' => '29.5 M'],
        'total' => '323.28 M&sup2; | 34.80 SQ',
    ],
    'olympia-39' => [
        'name' => 'Olympia 39', 'type' => 'Double Storey', 'beds' => 5, 'baths' => 3, 'cars' => 2,
        'img' => 'olympia-39', 'pdf' => asset('pdfs/olympia-39.pdf'),
        'desc' => 'Bold and sophisticated, the Olympia-39 redefines high-end living. From a showstopping entrance to its chef-style kitchen and spa-inspired bathrooms, every corner of this home whispers luxury. Designed for families who dream big and live large.',
        'dims' => ['Internal' => '301.65 M&sup2;', 'Garage' => '34.22 M&sup2;', 'Alfresco' => '13.4 M&sup2;', 'Porch' => '5 M&sup2;', 'Balcony' => '9.3 M&sup2;', 'Min.Lot Width' => '12.5 M', 'Min.Lot Depth' => '25.5 M'],
        'total' => '368.57 M&sup2; | 39.67 SQ',
    ],
    'akira-21' => [
        'name' => 'Akira 21', 'type' => 'Single Storey', 'beds' => 4, 'baths' => 2.5, 'cars' => 2,
        'img' => 'akira-21', 'pdf' => asset('pdfs/akira-21.pdf'),
        'desc' => 'Compact yet cleverly designed, the Akira-21 is perfect for first-home buyers or downsizers. It balances practical living with a modern edge, offering effortless movement from the kitchen to the lounge, making it ideal for easy everyday living and low-maintenance lifestyles.',
        'dims' => ['Internal' => '143 M&sup2;', 'Garage' => '33.64 M&sup2;', 'Alfresco' => '13.16 M&sup2;', 'Porch' => '4.2 M&sup2;', 'Min.Lot Width' => '12.5 M', 'Min.Lot Depth' => '30 M'],
        'total' => '194 M&sup2; | 20.88 SQ',
    ],
    'melora-31' => [
        'name' => 'Melora 31', 'type' => 'Single Storey', 'beds' => 4, 'baths' => 2.5, 'cars' => 2,
        'img' => 'melora-31', 'pdf' => asset('pdfs/meliora.pdf'),
        'desc' => 'Blending elegance and smart design, the Melora-31 maximizes every square metre. The thoughtful layout allows for natural light to flood the central living space, while generous bedrooms offer privacy and comfort &mdash; making it the ideal forever home for modern families.',
        'dims' => ['Internal' => '191.15 M&sup2;', 'Garage' => '34.05 M&sup2;', 'Alfresco' => '13.1 M&sup2;', 'Porch' => '45.5 M&sup2;', 'Courtyard' => '7 M&sup2;', 'Min.Lot Width' => '12.5 M', 'Min.Lot Depth' => '30 M'],
        'total' => '290.8 M&sup2; | 31.3 SQ',
    ],
    'chiron-28' => [
        'name' => 'Chiron 28', 'type' => 'Double Storey', 'beds' => 5, 'baths' => 3, 'cars' => 2,
        'img' => 'chiron-28', 'pdf' => asset('pdfs/chiron-28.pdf'),
        'desc' => 'A statement in contemporary family living, the Chiron-28 delivers double the space and twice the impact. With private retreats upstairs and lively shared spaces below, this design is perfect for growing families who want functionality without compromising style.',
        'dims' => ['Internal' => '214.97 M&sup2;', 'Garage' => '33.81 M&sup2;', 'Porch' => '7.88 M&sup2;', 'Balcony' => '3.54 M&sup2;', 'Min.Lot Width' => '10 M', 'Min.Lot Depth' => '25.65 M'],
        'total' => '260.2 M&sup2; | 28.00 SQ',
    ],
    'sophea-22' => [
        'name' => 'Sophea 22', 'type' => 'Single Storey', 'beds' => 3, 'baths' => 2.5, 'cars' => 2,
        'img' => 'sophea-22', 'pdf' => asset('pdfs/sophea-22.pdf'),
        'desc' => 'Designed for seamless living, the Sophea-22 offers a harmonious flow between indoor and outdoor spaces. With expansive bedrooms and a sunlit open-plan kitchen and living zone, this home is a sanctuary for families who value comfort and simplicity on a single level.',
        'dims' => ['Internal' => '142.24 M&sup2;', 'Garage' => '33.6 M&sup2;', 'Alfresco' => '25.9 M&sup2;', 'Porch' => '4.2 M&sup2;', 'Min.Lot Width' => '10 M', 'Min.Lot Depth' => '30 M'],
        'total' => '204.94 M&sup2; | 22.17 SQ',
    ],
];

$key = (isset($_GET['d']) && isset($DESIGNS[$_GET['d']])) ? $_GET['d'] : 'elyra-35';
$d   = $DESIGNS[$key];

// ---- Enquiry form handling (same pipeline as the Contact page) ----
$errors    = [];
$formError = '';
$old       = ['fullName' => '', 'email' => '', 'phone' => '', 'message' => ''];
$sent      = isset($_GET['sent']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = [
        'fullName' => (string) ($_POST['fullName'] ?? ''),
        'email'    => (string) ($_POST['email'] ?? ''),
        'phone'    => (string) ($_POST['phone'] ?? ''),
        'message'  => (string) ($_POST['message'] ?? ''),
    ];
    if (!csrf_verify($_POST['_csrf'] ?? null)) {
        $formError = 'Your session expired. Please try again.';
    } elseif (trim((string) ($_POST['website'] ?? '')) !== '') {
        redirect('design-details.php?d=' . $key . '&sent=1');
    } elseif (!empty($_SESSION['last_enquiry_at']) && (time() - $_SESSION['last_enquiry_at']) < 20) {
        $formError = 'You just sent a message. Please wait a moment before sending another.';
    } else {
        $res    = enquiry_validate($_POST);
        $errors = $res['errors'];
        if (!$errors) {
            $ip = substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45);
            $id = enquiry_create($res['data'], $ip);
            $_SESSION['last_enquiry_at'] = time();
            mail_send_enquiry(array_merge($res['data'], ['id' => $id, 'created_at' => date('Y-m-d H:i:s')]));
            redirect('design-details.php?d=' . $key . '&sent=1');
        }
    }
}

$PAGE_TITLE = $d['name'] . ' | Nivi Homes Designs';

// Spec icons (match the storey listing pages)
$ic_bed  = '<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M21 10.78V8a2 2 0 0 0-2-2h-5a2 2 0 0 0-1 .28A2 2 0 0 0 10 6H5a2 2 0 0 0-2 2v2.78A2 2 0 0 0 2 12.5V19h2v-2h16v2h2v-6.5a2 2 0 0 0-1-1.72ZM11 10H5V8h5a1 1 0 0 1 1 1Zm8 0h-6V9a1 1 0 0 1 1-1h5Z"/></svg>';
$ic_bath = '<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M7 8a2 2 0 0 1 4 0h2a4 4 0 0 0-8 0v4H2v3a4 4 0 0 0 3 3.87V21h2v-2h10v2h2v-2.13A4 4 0 0 0 22 15v-3H7Zm13 7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-1h16Z"/></svg>';
$ic_car  = '<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M18.92 6.01A1.5 1.5 0 0 0 17.5 5h-11a1.5 1.5 0 0 0-1.42 1.01L3 12v8a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1h12v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-8ZM6.85 7h10.3l1.04 3H5.81ZM6.5 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm11 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Z"/></svg>';

require __DIR__ . '/includes/header.php';
?>

<!-- ===================== PAGE BANNER ===================== -->
<?php
$BANNER_TITLE = htmlspecialchars($d['name']);
$BANNER_CRUMB = '<a href="index.php">Home</a> &gt; <a href="designs.php">Our Designs</a> &gt; ' . htmlspecialchars($d['name']);
$BANNER_IMG   = asset('images/projects/' . $d['img'] . '.webp');
require __DIR__ . '/includes/banner.php';
?>

<!-- ===================== DESIGN DETAIL ===================== -->
<section class="dd-main section">
    <div class="container">
        <h2 class="dd-page-title text-center"><?php echo htmlspecialchars($d['name']); ?></h2>
        <div class="dd-detail-card">
            <div class="dd-grid">
                <div class="dd-info" data-anim="fadeInLeft">
                    <h3 class="dd-title"><?php echo htmlspecialchars($d['name']); ?></h3>
                    <div class="dd-specs">
                        <span class="dd-spec"><?php echo $ic_bed; ?><em><?php echo $d['beds']; ?></em></span>
                        <span class="dd-spec"><?php echo $ic_bath; ?><em><?php echo $d['baths']; ?></em></span>
                        <span class="dd-spec"><?php echo $ic_car; ?><em><?php echo $d['cars']; ?></em></span>
                    </div>
                    <p class="dd-desc"><?php echo $d['desc']; ?></p>

                    <h4 class="dd-dim-title">Dimensions</h4>
                    <ul class="dd-dims">
                        <?php foreach ($d['dims'] as $label => $val): ?>
                        <li><span><?php echo $label; ?></span><em><?php echo $val; ?></em></li>
                        <?php endforeach; ?>
                        <li class="dd-total"><span>Total</span><em><?php echo $d['total']; ?></em></li>
                    </ul>

                    <a class="btn btn-primary dd-pdf" href="<?php echo $d['pdf']; ?>" target="_blank" rel="noopener">Download Pdf</a>
                </div>
                <div class="dd-plan" data-anim="fadeInRight">
                    <img src="<?php echo asset('images/floorplans/' . $d['img'] . '.webp'); ?>" alt="<?php echo htmlspecialchars($d['name']); ?> floor plan" loading="lazy">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== DESIGN GALLERY ===================== -->
<section class="dd-gallery-sec">
    <div class="container">
        <div class="dd-gallery">
            <a class="dd-gallery-item" href="<?php echo asset('images/designs/' . $d['img'] . '.webp'); ?>">
                <img src="<?php echo asset('images/designs/' . $d['img'] . '.webp'); ?>" alt="<?php echo htmlspecialchars($d['name']); ?> · Angle 1" loading="lazy">
            </a>
            <a class="dd-gallery-item" href="<?php echo asset('images/projects/' . $d['img'] . '.webp'); ?>">
                <img src="<?php echo asset('images/projects/' . $d['img'] . '.webp'); ?>" alt="<?php echo htmlspecialchars($d['name']); ?> · Angle 2" loading="lazy">
            </a>
            <a class="dd-gallery-item" href="<?php echo asset('images/designs/' . $d['img'] . '.webp'); ?>">
                <img src="<?php echo asset('images/designs/' . $d['img'] . '.webp'); ?>" alt="<?php echo htmlspecialchars($d['name']); ?> · Angle 3" loading="lazy">
            </a>
        </div>
    </div>
</section>

<!-- ===================== CTA ENQUIRY (Reusable Component) ===================== -->
<?php
$CTA_FORM_ACTION = 'design-details.php?d=' . urlencode($key);
require __DIR__ . '/includes/cta-enquiry.php';
?>

<?php require __DIR__ . '/includes/footer.php'; ?>
