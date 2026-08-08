<?php
// includes/footer.php
?>
    <footer class="app-footer mt-5 py-5 border-top">
        <div class="container text-center text-muted">
            <p class="mb-1">&copy; <?= date('Y') ?> Apple Planet. All rights reserved.</p>
            <p class="small mb-0">Premium independent electronics retailer.</p>
        </div>
    </footer>

    <!-- Quick View Modal -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 10;"></button>
                <div class="row g-0">
                    <div class="col-md-6 bg-light d-flex align-items-center justify-content-center p-4">
                        <img src="" id="qv-image" class="img-fluid" alt="Product Image" style="max-height: 400px; object-fit: contain;">
                    </div>
                    <div class="col-md-6 p-4 p-md-5 d-flex flex-column justify-content-center">
                        <span id="qv-brand" class="text-uppercase text-muted small fw-bold tracking-wider mb-2"></span>
                        <h3 id="qv-title" class="fw-bold mb-3" style="font-size: 2rem; letter-spacing: -1px;"></h3>
                        <div id="qv-price" class="fs-4 fw-bold mb-4"></div>
                        <p id="qv-description" class="text-muted small mb-4" style="line-height: 1.6;"></p>
                        
                        <form action="<?= BASE_URL ?>cart-action.php" method="POST" class="add-to-cart-form mt-auto">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" id="qv-id" value="">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-1 fw-bold tracking-wider">ADD TO BAG</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?= BASE_URL ?>assets/js/script.js?v=<?= filemtime(__DIR__ . '/../assets/js/script.js') ?>"></script>
</body>
</html>
