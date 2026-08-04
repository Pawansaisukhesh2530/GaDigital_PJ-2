/* main.js - scroll reveal, back-to-top, lazy image niceties */
(function () {
    'use strict';

    /* ---------- Scroll reveal ---------- */
    var revealEls = document.querySelectorAll('[data-reveal]');
    if ('IntersectionObserver' in window && revealEls.length) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });
        revealEls.forEach(function (el) { io.observe(el); });
    } else {
        revealEls.forEach(function (el) { el.classList.add('revealed'); });
    }

    /* ---------- Elementor-style entrance animations ([data-anim]) ---------- */
    var animEls = document.querySelectorAll('[data-anim]');
    if (animEls.length) {
        animEls.forEach(function (el) { el.classList.add('anim-hidden'); });
        if ('IntersectionObserver' in window) {
            var ao = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) return;
                    var el = entry.target;
                    var name = el.getAttribute('data-anim');
                    var delay = parseInt(el.getAttribute('data-anim-delay'), 10) || 0;
                    el.style.animationDelay = delay + 'ms';
                    el.classList.remove('anim-hidden');
                    el.classList.add('anim-run', 'anim-' + name);
                    ao.unobserve(el);
                });
            }, { threshold: 0.15 });
            animEls.forEach(function (el) { ao.observe(el); });
        } else {
            animEls.forEach(function (el) { el.classList.remove('anim-hidden'); });
        }
    }

    /* ---------- Section-level auto-reveal (subtle fade-up for sections without explicit animation) ---------- */
    var sections = document.querySelectorAll('main > section, .section, .inc-sec, .pj-top, .pj-detail, .pj-gallery-sec, .story-row, .values-section');
    if ('IntersectionObserver' in window && sections.length) {
        sections.forEach(function (sec) {
            // Skip sections that already have explicit reveal/anim attributes
            if (sec.hasAttribute('data-reveal') || sec.hasAttribute('data-anim') || sec.classList.contains('revealed') || sec.classList.contains('hero')) return;
            // Skip container sections whose children handle their own animations
            if (sec.classList.contains('inc-page') || sec.classList.contains('inc-spec-section') || sec.classList.contains('abt-section') || sec.classList.contains('abt-values-section') || sec.classList.contains('wb-section') || sec.classList.contains('wb-apart-section')) return;
            sec.classList.add('section-reveal');
        });
        var sectionObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('section-revealed');
                    sectionObs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
        document.querySelectorAll('.section-reveal').forEach(function (el) { sectionObs.observe(el); });
    }

    /* ---------- Banner overlay mouse-track (drifts opposite the cursor) ---------- */
    var banner = document.querySelector('.page-banner');
    if (banner && window.matchMedia('(pointer:fine)').matches) {
        var MAX = 14; // px, subtle (Elementor mouse-track speed 1)
        banner.addEventListener('mousemove', function (e) {
            var r = banner.getBoundingClientRect();
            var dx = (e.clientX - r.left) / r.width - 0.5;   // -0.5 .. 0.5
            var dy = (e.clientY - r.top) / r.height - 0.5;
            // opposite direction
            banner.style.setProperty('--ov-x', (-dx * 2 * MAX).toFixed(1) + 'px');
            banner.style.setProperty('--ov-y', (-dy * 2 * MAX).toFixed(1) + 'px');
        });
        banner.addEventListener('mouseleave', function () {
            banner.style.setProperty('--ov-x', '0px');
            banner.style.setProperty('--ov-y', '0px');
        });
    }

    /* ---------- Back to top ---------- */
    var btt = document.getElementById('backToTop');
    if (btt) {
        window.addEventListener('scroll', function () {
            btt.classList.toggle('show', window.scrollY > 500);
        }, { passive: true });
        btt.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
})();
