/* menu.js - mobile navigation, dropdown toggles, sticky header */
(function () {
    'use strict';

    var toggle = document.getElementById('navToggle');
    var nav = document.getElementById('primaryNav');
    var backdrop = document.getElementById('navBackdrop');
    var header = document.getElementById('siteHeader');

    function openMenu() {
        nav.classList.add('open');
        toggle.classList.add('open');
        toggle.setAttribute('aria-expanded', 'true');
        if (backdrop) { backdrop.hidden = false; requestAnimationFrame(function () { backdrop.classList.add('show'); }); }
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        nav.classList.remove('open');
        toggle.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
        if (backdrop) { backdrop.classList.remove('show'); setTimeout(function () { backdrop.hidden = true; }, 300); }
        document.body.style.overflow = '';
    }

    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            nav.classList.contains('open') ? closeMenu() : openMenu();
        });
    }
    if (backdrop) backdrop.addEventListener('click', closeMenu);

    // Mobile submenu accordions
    document.querySelectorAll('.submenu-toggle').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var item = btn.closest('.nav-item');
            var willOpen = !item.classList.contains('submenu-open');
            item.classList.toggle('submenu-open', willOpen);
            btn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        });
    });

    // Close mobile menu when a real link is clicked
    nav && nav.querySelectorAll('a[href]:not([href="#"])').forEach(function (a) {
        a.addEventListener('click', function () {
            if (window.innerWidth <= 1100) closeMenu();
        });
    });

    // Close on escape / resize back to desktop
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeMenu(); });
    window.addEventListener('resize', function () { if (window.innerWidth > 1100) closeMenu(); });

    // Sticky header shadow
    if (header) {
        var onScroll = function () { header.classList.toggle('scrolled', window.scrollY > 40); };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }
})();
