/* contact.js - client-side validation, char counter, accordion */
(function () {
    'use strict';

    /* ---------- Contact form ---------- */
    var form = document.getElementById('contactForm');
    if (form) {
        var message = form.querySelector('#message');
        var counter = form.querySelector('.char-count');
        var max = 180;

        if (message && counter) {
            var update = function () {
                if (message.value.length > max) message.value = message.value.slice(0, max);
                counter.textContent = message.value.length + ' / ' + max;
            };
            message.addEventListener('input', update);
            update();
        }

        // Client-side check for instant feedback; the server always validates too.
        // On a valid form we let the native POST proceed to the backend.
        form.addEventListener('submit', function (e) {
            var note = form.querySelector('.form-note');
            var valid = true;

            form.querySelectorAll('[required]').forEach(function (field) {
                var ok = field.value.trim() !== '';
                if (field.type === 'email') ok = ok && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value);
                field.style.borderColor = ok ? '' : '#e53935';
                if (!ok) valid = false;
            });

            if (!valid) {
                e.preventDefault();
                if (note) { note.textContent = 'Please complete the required fields correctly.'; note.className = 'form-note error'; }
                return;
            }

            // Valid: allow the real submission, but guard against double-submit.
            var btn = form.querySelector('button[type="submit"]');
            if (btn) { btn.disabled = true; btn.dataset.label = btn.textContent; btn.textContent = 'Sending…'; }
        });
    }

    /* ---------- Accordion (inclusions) ---------- */
    var items = document.querySelectorAll('.accordion-item');
    items.forEach(function (item) {
        var header = item.querySelector('.accordion-header');
        var body = item.querySelector('.accordion-body');
        if (!header || !body) return;
        header.addEventListener('click', function () {
            var isOpen = item.classList.contains('open');
            if (isOpen) {
                item.classList.remove('open');
                body.style.maxHeight = null;
            } else {
                item.classList.add('open');
                body.style.maxHeight = body.scrollHeight + 'px';
            }
        });
    });
})();
