<?php
// admin/admin_footer.php
?>
            </div>
        </div>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple active nav script
        document.addEventListener("DOMContentLoaded", function() {
            var currentUrl = window.location.href;
            var navLinks = document.querySelectorAll('.admin-sidebar .nav-link');
            navLinks.forEach(function(link) {
                if (currentUrl.includes(link.getAttribute('href'))) {
                    link.classList.add('active');
                    link.classList.remove('text-muted');
                }
            });
        });
    </script>
</body>
</html>
