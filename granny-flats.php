<?php
$CURRENT_PAGE = 'services';
$PAGE_TITLE = 'Granny Flats | Nivi Homes';
require __DIR__ . '/includes/header.php';
?>

<?php
$BANNER_TITLE = 'Granny Flats';
$BANNER_CRUMB = '<a href="index.php">Home</a> &gt; <a href="services.php">Our Services</a> &gt; Granny Flats';
$BANNER_IMG = asset('images/banners/granny-banner.webp');
require __DIR__ . '/includes/banner.php';
?>

<!-- ===================== INTRO ===================== -->
<section class="svc-intro">
    <div class="svc-container">
        <p data-anim="fadeIn">Discover the versatility and convenience of granny flats with Nivi Homes. Whether you need additional space for aging parents, a home office, or rental income, our granny flats are designed to offer comfort, functionality, and aesthetic appeal. With customizable options and efficient construction processes, we deliver granny flats that enhance your property&rsquo;s value and provide practical living solutions.</p>
    </div>
</section>

<!-- ===================== WHY CHOOSE ===================== -->
<section class="svc-why" style="padding-top:70px;">
    <div class="svc-container">
        <div class="svc-why-grid svc-why-grid-flip" data-anim="fadeIn">
            <div class="svc-why-media">
                <div class="svc-card-img-bg"></div>
                <div class="svc-card-img">
                    <img src="<?php echo asset('images/services/granny-top.webp'); ?>" alt="Granny Flat Exterior" loading="lazy">
                    <span class="svc-card-img-label">Granny Flat &middot; Exterior</span>
                </div>
            </div>
            <div class="svc-why-text">
                <h2>Why Choose a Granny Flat?</h2>
                <p>A granny flat from Nivi Homes offers a cost-effective and flexible solution for expanding your living space or generating rental income without the need for major property alterations. Whether you&rsquo;re looking to accommodate family members, create additional income opportunities, or enhance your property&rsquo;s value, our granny flats are designed to meet your needs with efficiency, quality, and personalized style.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===================== COST ARTICLE ===================== -->
<section class="svc-article">
    <div class="svc-container" data-anim="fadeIn">
        <p>The cost of building a granny flat in Sydney can vary widely, with prices starting as low as $100,000. However, these budget-friendly options often come with hidden costs or compromises in quality. Realistically, a well-built, turn-key granny flat that is both comfortable and functional will cost between $180,000 and $300,000. This price includes everything from earthworks to plumbing, electrical, insulation, and painting, ensuring the space is livable and inviting for a family member or tenant. Pre-fab or kit homes may seem like a cheaper alternative, but additional costs for installation, services, and finishing quickly add up.</p>
        <p>Key factors that influence costs include the level of finishes, site-specific challenges like earthworks, and additional features like retaining walls or decking. To avoid surprises, it&rsquo;s crucial to ask what is included in the quoted price and clarify potential variations. Design and approvals also contribute to expenses, typically ranging from $10,000 to $20,000. Custom-designed granny flats are worth considering, as they maximize the site&rsquo;s potential, such as solar access, ventilation, and aesthetic appeal, creating a more comfortable living space.</p>
        <p>Cost-saving options include converting an existing structure, like a garage, or taking on DIY tasks such as painting or landscaping. For those planning to rent out their granny flat, the investment can pay off well. For example, a 2-bedroom granny flat in Sydney&rsquo;s North Shore can fetch over $650 per week in rent, making it a profitable addition to your property.</p>
        <p>Ultimately, the price you pay will reflect the quality of the finished home. Research thoroughly, ask the right questions, and work with a reputable builder to ensure you get a granny flat that meets your needs and adds value to your property.</p>
    </div>
</section>

<!-- ===================== GALLERY ===================== -->
<section class="svc-gallery">
    <div class="svc-container">
        <div class="svc-gallery-grid" data-anim="fadeIn">
            <?php
            $gf_labels = ['Street View', 'Entry', 'Facade', 'Bedroom', 'Bathroom Vanity', 'Shower Detail'];
            for ($i = 1; $i <= 6; $i++): ?>
            <div class="svc-gallery-item">
                <img src="<?php echo asset('images/granny/gf-' . $i . '.webp'); ?>" alt="Granny flat <?php echo $gf_labels[$i-1]; ?>" loading="lazy">
                <span class="svc-card-img-label">Granny Flat &middot; <?php echo $gf_labels[$i-1]; ?></span>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<!-- ===================== CTA ===================== -->
<section class="svc-cta" style="background-image:url('<?php echo asset('images/services/granny-cta-bg.webp'); ?>')">
    <div class="svc-cta-inner" data-anim="fadeIn">
        <h2>Ready to enhance your property with a custom granny flat?</h2>
        <p>Contact us today for a consultation and let us create a versatile and stylish addition that meets your specific needs!</p>
        <a class="svc-cta-btn" href="contact.php">Contact us</a>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
