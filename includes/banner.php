<?php
/**
 * Reusable inner-page banner.
 * Solid #282828 base + #141414 overlay + background image that drifts opposite
 * the cursor (mouse-track parallax), matching the original Elementor banner.
 *
 * Expects (set before including):
 *   $BANNER_TITLE  - heading text
 *   $BANNER_CRUMB  - breadcrumb HTML (optional)
 *   $BANNER_IMG    - background image asset path (optional)
 */
$__img = $BANNER_IMG ?? asset('images/banners/about-banner.jpg');
?>
<section class="page-banner">
    <div class="page-banner__bg" style="background-image:url('<?php echo $__img; ?>')"></div>
    <h1><?php echo $BANNER_TITLE ?? ''; ?></h1>
    <?php if (!empty($BANNER_CRUMB)): ?>
        <p class="breadcrumb"><?php echo $BANNER_CRUMB; ?></p>
    <?php endif; ?>
</section>
