<?php
$CURRENT_PAGE = 'home';
$PAGE_TITLE = 'Nivi Homes | Custom Homes, Duplexes, Knock Down Rebuilds & Granny Flats';
require __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO SLIDER (full-width 16:9 image carousel) ===================== -->
<section class="hero" data-slider data-interval="3000" aria-label="Featured homes">
    <div class="hero-slides">
        <div class="hero-slide is-active" style="background-image:url('<?php echo asset('images/hero/banner-4.webp'); ?>')"></div>
        <div class="hero-slide" style="background-image:url('<?php echo asset('images/hero/banner-2.webp'); ?>')"></div>
        <div class="hero-slide" style="background-image:url('<?php echo asset('images/hero/banner-4-v2.webp'); ?>')"></div>
        <div class="hero-slide" style="background-image:url('<?php echo asset('images/hero/banner-1.webp'); ?>')"></div>
        <div class="hero-slide" style="background-image:url('<?php echo asset('images/hero/banner-3.webp'); ?>')"></div>
    </div>
    <button class="hero-arrow prev" aria-label="Previous slide"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg></button>
    <button class="hero-arrow next" aria-label="Next slide"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 6l6 6-6 6"/></svg></button>
    <div class="hero-dots" role="tablist" aria-label="Slide navigation"></div>
</section>

<!-- ===================== DISCOVER / INTRO (image left + text right) ===================== -->
<section class="intro">
    <div class="container">
        <div class="intro-grid">
            <div class="intro-media" data-reveal="left">
                <div class="intro-img-offset"></div>
                <div class="intro-img-card">
                    <img src="<?php echo asset('images/backgrounds/clients-say.webp'); ?>" alt="Discover Nivi Homes" loading="lazy" width="768" height="512">
                    <span class="intro-img-label">Living Room &middot; Nivi Homes</span>
                </div>
            </div>
            <div class="intro-content" data-reveal="right">
                <h2 class="section-title">Discover Nivi Homes</h2>
                <p>At Nivi Homes, we believe a home is more than just a structure&mdash;it&rsquo;s a sanctuary where memories are made and futures are built. Founded on the principles of quality craftsmanship, integrity, and customer satisfaction, we are dedicated to turning your dream home vision into reality. Our experienced team of engineers, architects, designers, and skilled workers share a collective commitment to excellence in every project we undertake. From custom homes and duplexes to knock down rebuilds and granny flats, we work closely with you to ensure every detail reflects your unique style and needs. Welcome to Nivi Homes, where every detail matters and your dream home awaits.</p>
                <a href="about.php" class="btn btn-outline">Read More</a>
            </div>
        </div>
    </div>
</section>

<!-- ===================== OUR SERVICES (dark head + 2x2 image tiles) ===================== -->
<section class="services-section">
    <div class="services-head">
        <div class="services-head-inner" data-reveal>
            <div class="services-head-left">
                <span class="section-eyebrow">Nivi Homes</span>
                <h2 class="section-title white">Our Services</h2>
            </div>
            <div class="services-head-right">
                <p class="services-intro">Nivi Homes offers a comprehensive range of residential solutions tailored to meet your needs:</p>
            </div>
        </div>
    </div>
    <div class="services-grid" data-reveal>
        <?php
        $services = [
            ['t' => 'Custom Homes', 'img' => 'custom-homes', 'k' => 'custom-homes', 'd' => 'Designed to reflect your unique style and preferences'],
            ['t' => 'Duplex Homes', 'img' => 'duplex', 'k' => 'duplex-homes', 'd' => 'Maximizing space and functionality without compromising on aesthetics.'],
            ['t' => 'Knock Down Rebuilds', 'img' => 'knock-down', 'k' => 'knock-down-rebuilds', 'd' => 'Transforming existing properties into modern, efficient homes.'],
            ['t' => 'Granny Flats', 'img' => 'granny-flats', 'k' => 'granny-flats', 'd' => 'Versatile living solutions designed for comfort and convenience.'],
        ];
        foreach ($services as $s): ?>
        <div class="service-card" style="background-image:url('<?php echo asset('images/services/' . $s['img'] . '.webp'); ?>')">
            <h3><?php echo $s['t']; ?></h3>
            <p><?php echo $s['d']; ?></p>
            <a class="btn btn-primary" href="service-details.php?s=<?php echo $s['k']; ?>">Read More</a>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ===================== WHY CHOOSE ===================== -->
<section class="why-choose">
    <div class="container">
        <div class="text-center" data-reveal>
            <h2 class="section-title gold">Why Choose Nivi Homes?</h2>
        </div>
        <div class="features-grid" data-reveal>
            <?php
            $features = [
                ['t' => 'Tailored Solutions', 'variant' => 'fc-dark', 'd' => 'We specialize in creating bespoke homes that are uniquely tailored to fit your lifestyle and preferences, ensuring every aspect reflects your individuality.',
                 'img' => '6-web.jpg', 'svg' => '<path d="M12 2l2.4 7.4H22l-6 4.5 2.3 7.1L12 16.6 5.7 21l2.3-7.1-6-4.5h7.6z"/>'],
                ['t' => 'Attention to Detail', 'variant' => 'fc-light', 'd' => 'Our meticulous attention to detail sets us apart, ensuring every corner of your home is crafted with precision and care, from the foundation to the finishing touches.',
                 'img' => 'architectural.jpg', 'svg' => '<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>'],
                ['t' => 'Innovative Designs', 'variant' => 'fc-light', 'd' => 'We stay ahead of trends and innovations in home design, offering creative solutions that enhance functionality and aesthetics while meeting modern lifestyle needs.',
                 'img' => 'architectural.jpg', 'svg' => '<path d="M9 18h6M10 22h4M12 2a7 7 0 00-4 12.7c.6.5 1 1.3 1 2.1h6c0-.8.4-1.6 1-2.1A7 7 0 0012 2z"/>'],
                ['t' => 'Exceptional Customer Service', 'variant' => 'fc-dark2', 'd' => 'Our dedicated team is committed to providing exceptional customer service, guiding you through every step of the building process with transparency and responsiveness.',
                 'img' => 'interior.jpg', 'svg' => '<path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>'],
            ];
            foreach ($features as $i => $f): ?>
            <article class="feature-card <?php echo $f['variant']; ?>" style="--hover-img:url('../images/why-choose/<?php echo $f['img']; ?>')">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><?php echo $f['svg']; ?></svg>
                </div>
                <h3><?php echo $f['t']; ?></h3>
                <p><?php echo $f['d']; ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
