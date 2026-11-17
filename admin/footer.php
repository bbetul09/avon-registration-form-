    </main>

    <script>
    (function() {
        const toggle   = document.getElementById('sidebarToggle');
        const sidebar  = document.querySelector('.admin-sidebar');
        const overlay  = document.getElementById('sidebarOverlay');

        function checkMobile() {
            if (window.innerWidth <= 768) {
                toggle.style.display = 'flex';
            } else {
                toggle.style.display = 'none';
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            }
        }

        toggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });

        window.addEventListener('resize', checkMobile);
        checkMobile();
    })();
    </script>
</body>
</html>
