<?php
// cart.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$subtotal = 0;
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4">Your Cart</h2>

    <?php if (empty($cart)): ?>
    <div class="text-center py-5">
        <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
        <h4 class="mt-3">Your cart is empty</h4>
        <p class="text-muted">Looks like you haven't added anything yet.</p>
        <a href="<?= BASE_URL ?>products.php" class="btn btn-primary mt-3 px-4">Continue Shopping</a>
    </div>
    <?php else: ?>
    <div class="row g-5">
        <div class="col-lg-8">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cart as $item): 
                            $item_total = $item['price'] * $item['quantity'];
                            $subtotal += $item_total;
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if($item['image']): ?>
                                    <img src="<?= BASE_URL ?>assets/uploads/products/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: contain; border-color: var(--border);">
                                    <?php else: ?>
                                    <div class="bg-light me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border: 1px solid var(--border); border-radius: 4px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <a href="<?= BASE_URL ?>product.php?id=<?= $item['id'] ?>" class="text-decoration-none fw-bold text-dark">
                                            <?= htmlspecialchars($item['name']) ?>
                                        </a>
                                        <div class="mt-1">
                                            <form action="<?= BASE_URL ?>cart-action.php" method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="remove">
                                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                                <button type="submit" class="btn btn-link text-danger p-0 small text-decoration-none" style="font-size: 0.8rem;">Remove</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td><?= format_price($item['price']) ?></td>
                            <td>
                                <form action="<?= BASE_URL ?>cart-action.php" method="POST" class="d-flex align-items-center">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                    <input type="number" name="quantity" class="form-control form-control-sm text-center me-2" value="<?= $item['quantity'] ?>" min="1" style="width: 60px;">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-clockwise"></i></button>
                                </form>
                            </td>
                            <td class="text-end fw-bold"><?= format_price($item_total) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card bg-light border-0" style="border-radius: 8px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Order Summary</h5>
                    
                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Subtotal</span>
                        <span><?= format_price($subtotal) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    
                    <hr class="my-4 border-secondary">
                    
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold fs-5"><?= format_price($subtotal) ?></span>
                    </div>
                    
                    <a href="<?= BASE_URL ?>checkout.php" class="btn btn-primary w-100 py-3 fw-bold">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
