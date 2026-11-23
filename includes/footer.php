<?php
/**
 * Nusantara Cargo Express (NCE)
 * Footer Template Modern
 */
declare(strict_types=1);
?>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top py-3 px-4 text-center text-muted small mt-auto">
        <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center">
            <span>&copy; <?= date('Y') ?> <strong><?= APP_NAME ?></strong></span>
        </div>
    </footer>
</div>
</div>

<!-- Bootstrap 5 Bundle JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Mobile Sidebar Drawer Handler -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('mobileMenuToggle');
        const sidebar   = document.getElementById('sidebarMenu');
        const backdrop  = document.getElementById('mobileBackdrop');

        if (toggleBtn && sidebar && backdrop) {
            function openSidebar() {
                sidebar.classList.add('show');
                backdrop.classList.add('show');
            }

            function closeSidebar() {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
            }

            toggleBtn.addEventListener('click', openSidebar);
            backdrop.addEventListener('click', closeSidebar);
        }
    });
</script>

<!-- App Interactive Script -->
<script src="assets/js/app.js"></script>
</body>
</html>