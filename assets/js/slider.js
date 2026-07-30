/* slider.js - autoplay hero image slider with dots + arrows */
(function () {
    'use strict';

    var hero = document.querySelector('[data-slider]');
    if (!hero) return;

    var slides = Array.prototype.slice.call(hero.querySelectorAll('.hero-slide'));
    var dotsWrap = hero.querySelector('.hero-dots');
    var prev = hero.querySelector('.hero-arrow.prev');
    var next = hero.querySelector('.hero-arrow.next');
    if (slides.length === 0) return;

    var index = 0;
    var timer = null;
    var interval = parseInt(hero.getAttribute('data-interval'), 10) || 5000;

    // Build dots
    var dots = [];
    if (dotsWrap) {
        slides.forEach(function (_, i) {
            var d = document.createElement('button');
            d.className = 'hero-dot' + (i === 0 ? ' is-active' : '');
            d.setAttribute('aria-label', 'Go to slide ' + (i + 1));
            d.addEventListener('click', function () { go(i); restart(); });
            dotsWrap.appendChild(d);
            dots.push(d);
        });
    }

    function go(i) {
        slides[index].classList.remove('is-active');
        if (dots[index]) dots[index].classList.remove('is-active');
        index = (i + slides.length) % slides.length;
        slides[index].classList.add('is-active');
        if (dots[index]) dots[index].classList.add('is-active');
    }

    function nextSlide() { go(index + 1); }
    function start() { timer = setInterval(nextSlide, interval); }
    function stop() { clearInterval(timer); }
    function restart() { stop(); start(); }

    if (next) next.addEventListener('click', function () { nextSlide(); restart(); });
    if (prev) prev.addEventListener('click', function () { go(index - 1); restart(); });

    hero.addEventListener('mouseenter', stop);
    hero.addEventListener('mouseleave', start);

    // Basic touch swipe
    var startX = 0;
    hero.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
    hero.addEventListener('touchend', function (e) {
        var dx = e.changedTouches[0].clientX - startX;
        if (Math.abs(dx) > 50) { dx < 0 ? nextSlide() : go(index - 1); restart(); }
    });

    start();
})();
