/* gallery.js - Professional lightbox gallery with navigation, zoom, fullscreen, share */
(function () {
    'use strict';

    /* ---------- Lightbox ---------- */
    var items = [];
    var currentIndex = 0;
    var scale = 1;
    var isFullscreen = false;
    var touchStartX = 0;
    var touchStartY = 0;
    var touchEndX = 0;

    // Collect all lightbox-enabled links
    var links = document.querySelectorAll('.footer-gallery-item, [data-lightbox], .pj-gallery-item');
    if (!links.length) return;

    links.forEach(function (a) {
        items.push(a.getAttribute('href') || a.dataset.lightbox || a.querySelector('img')?.src || '');
    });

    // Create lightbox DOM
    var box = document.createElement('div');
    box.className = 'lb';
    box.setAttribute('role', 'dialog');
    box.setAttribute('aria-label', 'Image lightbox');
    box.innerHTML =
        '<div class="lb-overlay"></div>' +
        '<div class="lb-content">' +
            '<img class="lb-img" alt="Gallery image" draggable="false">' +
        '</div>' +
        '<div class="lb-toolbar">' +
            '<button class="lb-btn lb-zoom-in" aria-label="Zoom in" title="Zoom in">' +
                '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>' +
            '</button>' +
            '<button class="lb-btn lb-zoom-out" aria-label="Zoom out" title="Zoom out">' +
                '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg>' +
            '</button>' +
            '<button class="lb-btn lb-fullscreen" aria-label="Fullscreen" title="Fullscreen">' +
                '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3m0 18h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3"/></svg>' +
            '</button>' +
            '<button class="lb-btn lb-share" aria-label="Share" title="Share">' +
                '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>' +
            '</button>' +
            '<button class="lb-btn lb-close" aria-label="Close" title="Close">' +
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
            '</button>' +
        '</div>' +
        '<button class="lb-nav lb-prev" aria-label="Previous image">' +
            '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>' +
        '</button>' +
        '<button class="lb-nav lb-next" aria-label="Next image">' +
            '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>' +
        '</button>' +
        '<div class="lb-counter"></div>';
    document.body.appendChild(box);

    var overlay = box.querySelector('.lb-overlay');
    var img = box.querySelector('.lb-img');
    var counter = box.querySelector('.lb-counter');
    var content = box.querySelector('.lb-content');

    function open(index) {
        currentIndex = index;
        scale = 1;
        updateImage();
        box.classList.add('lb-active');
        document.body.style.overflow = 'hidden';
    }

    function close() {
        box.classList.remove('lb-active');
        document.body.style.overflow = '';
        scale = 1;
        img.style.transform = '';
        exitFullscreen();
    }

    function updateImage() {
        img.style.opacity = '0';
        img.style.transform = 'scale(1)';
        scale = 1;
        setTimeout(function () {
            img.src = items[currentIndex];
            img.style.opacity = '1';
        }, 150);
        counter.textContent = (currentIndex + 1) + ' / ' + items.length;
        // Hide nav if only one image
        box.querySelector('.lb-prev').style.display = items.length <= 1 ? 'none' : '';
        box.querySelector('.lb-next').style.display = items.length <= 1 ? 'none' : '';
    }

    function prev() {
        currentIndex = (currentIndex - 1 + items.length) % items.length;
        updateImage();
    }

    function next() {
        currentIndex = (currentIndex + 1) % items.length;
        updateImage();
    }

    function zoomIn() {
        scale = Math.min(scale + 0.5, 4);
        img.style.transform = 'scale(' + scale + ')';
    }

    function zoomOut() {
        scale = Math.max(scale - 0.5, 0.5);
        img.style.transform = 'scale(' + scale + ')';
    }

    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            box.requestFullscreen().catch(function () {});
            isFullscreen = true;
        } else {
            exitFullscreen();
        }
    }

    function exitFullscreen() {
        if (document.fullscreenElement) {
            document.exitFullscreen().catch(function () {});
        }
        isFullscreen = false;
    }

    function share() {
        var url = items[currentIndex];
        if (navigator.share) {
            navigator.share({ title: 'Nivi Homes Gallery', url: url }).catch(function () {});
        } else if (navigator.clipboard) {
            navigator.clipboard.writeText(window.location.origin + '/' + url).then(function () {
                showToast('Link copied to clipboard');
            }).catch(function () {
                fallbackCopy(url);
            });
        } else {
            fallbackCopy(url);
        }
    }

    function fallbackCopy(text) {
        var ta = document.createElement('textarea');
        ta.value = window.location.origin + '/' + text;
        ta.style.cssText = 'position:fixed;left:-9999px';
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        showToast('Link copied to clipboard');
    }

    function showToast(msg) {
        var toast = document.createElement('div');
        toast.className = 'lb-toast';
        toast.textContent = msg;
        box.appendChild(toast);
        setTimeout(function () { toast.classList.add('lb-toast-show'); }, 10);
        setTimeout(function () {
            toast.classList.remove('lb-toast-show');
            setTimeout(function () { toast.remove(); }, 300);
        }, 2000);
    }

    // Event bindings
    links.forEach(function (a, i) {
        a.addEventListener('click', function (e) {
            e.preventDefault();
            open(i);
        });
    });

    overlay.addEventListener('click', close);
    box.querySelector('.lb-close').addEventListener('click', close);
    box.querySelector('.lb-prev').addEventListener('click', prev);
    box.querySelector('.lb-next').addEventListener('click', next);
    box.querySelector('.lb-zoom-in').addEventListener('click', zoomIn);
    box.querySelector('.lb-zoom-out').addEventListener('click', zoomOut);
    box.querySelector('.lb-fullscreen').addEventListener('click', toggleFullscreen);
    box.querySelector('.lb-share').addEventListener('click', share);

    // Keyboard navigation
    document.addEventListener('keydown', function (e) {
        if (!box.classList.contains('lb-active')) return;
        switch (e.key) {
            case 'Escape': close(); break;
            case 'ArrowLeft': prev(); break;
            case 'ArrowRight': next(); break;
            case '+': case '=': zoomIn(); break;
            case '-': zoomOut(); break;
            case 'f': toggleFullscreen(); break;
        }
    });

    // Touch/swipe gestures
    content.addEventListener('touchstart', function (e) {
        touchStartX = e.changedTouches[0].screenX;
        touchStartY = e.changedTouches[0].screenY;
    }, { passive: true });

    content.addEventListener('touchend', function (e) {
        touchEndX = e.changedTouches[0].screenX;
        var diffX = touchStartX - touchEndX;
        var diffY = Math.abs(touchStartY - e.changedTouches[0].screenY);
        // Only trigger if horizontal swipe is dominant
        if (Math.abs(diffX) > 50 && diffY < 100) {
            if (diffX > 0) next();
            else prev();
        }
    }, { passive: true });

    // Prevent image drag
    img.addEventListener('dragstart', function (e) { e.preventDefault(); });

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
        var params = new URLSearchParams(location.search);
        var type = params.get('type');
        if (type) {
            var match = document.querySelector('.design-tab[data-filter="' + type + '"]');
            if (match) match.click();
        }
    }
})();
