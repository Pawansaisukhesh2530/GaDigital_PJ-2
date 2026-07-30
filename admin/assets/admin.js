/* Admin panel behaviours: slug auto-gen, confirms, gallery drag-reorder. */
(function () {
    'use strict';

    /* ---- Slug auto-generate from title (until slug is edited manually) ---- */
    var title = document.getElementById('title');
    var slug = document.getElementById('slug');
    if (title && slug) {
        var slugTouched = slug.value.trim() !== '';
        slug.addEventListener('input', function () { slugTouched = true; });
        title.addEventListener('input', function () {
            if (slugTouched) return;
            slug.value = title.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        });
    }

    /* ---- Delete-project confirmation (list) ---- */
    document.querySelectorAll('form.js-delete').forEach(function (f) {
        f.addEventListener('submit', function (e) {
            var btn = f.querySelector('[data-title]');
            var name = btn ? btn.getAttribute('data-title') : 'this project';
            if (!window.confirm('Delete "' + name + '"?\nThis removes its images and features and cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    /* ---- Generic confirm buttons ---- */
    document.querySelectorAll('.js-confirm').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            var msg = btn.getAttribute('data-confirm') || 'Are you sure?';
            if (!window.confirm(msg)) e.preventDefault();
        });
    });

    /* ---- Toast helper ---- */
    function showToast(msg, ok) {
        var t = document.getElementById('toast');
        if (!t) { return; }
        t.textContent = msg;
        t.className = 'toast show' + (ok === false ? ' toast-error' : '');
        clearTimeout(t._timer);
        t._timer = setTimeout(function () { t.className = 'toast'; }, 2600);
    }

    /* ---- Projects table drag-and-drop reorder ---- */
    var pTable = document.getElementById('projectsTable');
    if (pTable && pTable.getAttribute('data-reorder') === '1') {
        var tbody = pTable.querySelector('tbody');
        var rowDrag = null;

        tbody.querySelectorAll('tr[draggable="true"]').forEach(function (row) {
            row.addEventListener('dragstart', function () { rowDrag = row; row.classList.add('row-dragging'); });
            row.addEventListener('dragend', function () {
                row.classList.remove('row-dragging');
                saveRowOrder();
            });
        });

        tbody.addEventListener('dragover', function (e) {
            e.preventDefault();
            if (!rowDrag) { return; }
            var after = rowAfter(tbody, e.clientY);
            if (after == null) { tbody.appendChild(rowDrag); }
            else { tbody.insertBefore(rowDrag, after); }
        });

        function rowAfter(container, y) {
            var rows = [].slice.call(container.querySelectorAll('tr[draggable="true"]:not(.row-dragging)'));
            var closest = { offset: -Infinity, el: null };
            rows.forEach(function (r) {
                var box = r.getBoundingClientRect();
                var offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) { closest = { offset: offset, el: r }; }
            });
            return closest.el;
        }

        function saveRowOrder() {
            var ids = [].slice.call(tbody.querySelectorAll('tr[data-id]')).map(function (r) { return r.getAttribute('data-id'); });
            var body = new URLSearchParams();
            body.append('action', 'reorder');
            body.append('_csrf', pTable.getAttribute('data-csrf') || '');
            ids.forEach(function (id) { body.append('order[]', id); });
            fetch(pTable.getAttribute('data-url'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'fetch' },
                body: body.toString()
            })
            .then(function (r) { return r.json(); })
            .then(function (d) { showToast(d && d.ok ? 'Order saved.' : 'Could not save order.', d && d.ok); })
            .catch(function () { showToast('Could not save order.', false); });
        }
    }

    /* ---- Gallery drag-and-drop reorder ---- */
    var grid = document.getElementById('galleryGrid');
    if (grid) {
        var dragEl = null;

        grid.querySelectorAll('.gallery-item').forEach(function (item) {
            item.addEventListener('dragstart', function () { dragEl = item; item.classList.add('dragging'); });
            item.addEventListener('dragend', function () {
                item.classList.remove('dragging');
                saveOrder();
            });
        });

        grid.addEventListener('dragover', function (e) {
            e.preventDefault();
            var after = getAfter(grid, e.clientX, e.clientY);
            if (!dragEl) return;
            if (after == null) grid.appendChild(dragEl);
            else grid.insertBefore(dragEl, after);
        });

        function getAfter(container, x, y) {
            var els = [].slice.call(container.querySelectorAll('.gallery-item:not(.dragging)'));
            var closest = { offset: -Infinity, el: null };
            els.forEach(function (child) {
                var box = child.getBoundingClientRect();
                var offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) closest = { offset: offset, el: child };
            });
            return closest.el;
        }

        function saveOrder() {
            var ids = [].slice.call(grid.querySelectorAll('.gallery-item')).map(function (i) { return i.getAttribute('data-id'); });
            var csrf = grid.querySelector('input[name="_csrf"]');
            var body = new URLSearchParams();
            body.append('action', 'reorder_images');
            body.append('_csrf', csrf ? csrf.value : '');
            ids.forEach(function (id) { body.append('order[]', id); });
            fetch(grid.getAttribute('data-reorder-url'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'fetch' },
                body: body.toString()
            }).catch(function () { /* silent: order still persists on next manual save */ });
        }
    }
})();
