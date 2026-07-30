/* gallery.js - lightbox for footer gallery + design tab filtering */
(function () {
    'use strict';

    /* ---------- Lightbox ---------- */
    var links = document.querySelectorAll('.footer-gallery-item, [data-lightbox]');
    if (links.length) {
        var box = document.createElement('div');
        box.className = 'lightbox';
        box.innerHTML = '<button class="lightbox-close" aria-label="Close">&times;</button><img alt="Preview">';
        box.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.9);display:none;align-items:center;justify-content:center;z-index:3000;padding:30px;';
        var img = box.querySelector('img');
        img.style.cssText = 'max-width:90%;max-height:88vh;border-radius:8px;box-shadow:0 20px 60px rgba(0,0,0,.5);';
        var closeBtn = box.querySelector('.lightbox-close');
        closeBtn.style.cssText = 'position:absolute;top:20px;right:28px;font-size:40px;color:#fff;background:none;line-height:1;cursor:pointer;';
        document.body.appendChild(box);

        function open(src) { img.src = src; box.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
        function close() { box.style.display = 'none'; document.body.style.overflow = ''; }

        links.forEach(function (a) {
            a.addEventListener('click', function (e) {
                e.preventDefault();
                open(a.getAttribute('href') || a.dataset.lightbox);
            });
        });
        closeBtn.addEventListener('click', close);
        box.addEventListener('click', function (e) { if (e.target === box) close(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
    }

    /* ---------- Design tab filter ---------- */
    var tabs = document.querySelectorAll('.design-tab');
    if (tabs.length) {
        var cards = document.querySelectorAll('.design-card');
        function filter(type) {
            cards.forEach(function (c) {
                var show = type === 'all' || c.dataset.type === type;
                c.style.display = show ? '' : 'none';
            });
        }
        tabs.forEach(function (t) {
            t.addEventListener('click', function () {
                tabs.forEach(function (x) { x.classList.remove('active'); });
                t.classList.add('active');
                filter(t.dataset.filter);
            });
        });
        // Honour ?type= from URL
        var params = new URLSearchParams(location.search);
        var type = params.get('type');
        if (type) {
            var match = document.querySelector('.design-tab[data-filter="' + type + '"]');
            if (match) match.click();
        }
    }
})();
