        </div><!-- /.content -->
        <footer class="admin-foot">
            &copy; <?php echo date('Y'); ?> Nivi Homes Admin &middot; Backend management panel
        </footer>
    </div><!-- /.main -->
</div><!-- /.admin -->
<script>
/* Mobile sidebar toggle (UI only) */
(function () {
    var toggle   = document.getElementById('sidebarToggle');
    var sidebar  = document.getElementById('adminSidebar');
    var backdrop = document.getElementById('adminBackdrop');
    if (!toggle || !sidebar) return;
    function setOpen(open) {
        sidebar.classList.toggle('open', open);
        if (backdrop) backdrop.classList.toggle('show', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    }
    toggle.addEventListener('click', function () { setOpen(!sidebar.classList.contains('open')); });
    if (backdrop) backdrop.addEventListener('click', function () { setOpen(false); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setOpen(false); });
    sidebar.addEventListener('click', function (e) { if (e.target.closest('a')) setOpen(false); });
})();
</script>
</body>
</html>
