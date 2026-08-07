<?php
// checkout.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/config/database.php';

require_customer();

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart)) {
    redirect(BASE_URL . 'cart.php');
}

$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += ($item['price'] * $item['quantity']);
}

$error = '';
$customer_id = $_SESSION['customer_id'];

// Fetch customer details to pre-fill
$stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = sanitize_input($_POST['address'] ?? '');
    $payment_method = sanitize_input($_POST['payment_method'] ?? '');
    
    if (empty($address)) {
        $error = "Shipping address is required.";
    } elseif (empty($payment_method)) {
        $error = "Please select a demo payment method.";
    } else {
        // Begin Transaction
        $conn->begin_transaction();
        
        try {
            // Insert Order
            $status = 'Pending';
            $stmt = $conn->prepare("INSERT INTO orders (customer_id, address, total_amount, status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isds", $customer_id, $address, $subtotal, $status);
            $stmt->execute();
            $order_id = $conn->insert_id;
            
            // Insert Order Items
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            
            // Update product stock
            $update_stock_stmt = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
            
            foreach ($cart as $item) {
                $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
                $stmt->execute();
                
                $update_stock_stmt->bind_param("ii", $item['quantity'], $item['id']);
                $update_stock_stmt->execute();
            }
            
            $conn->commit();
            
            // Clear cart
            $_SESSION['cart'] = [];
            
            redirect(BASE_URL . 'order-success.php?id=' . $order_id);
            
        } catch (Exception $e) {
            $conn->rollback();
            $error = "Order processing failed. Please try again.";
        }
    }
}
?>

<div class="container my-5">
    <div class="row g-5">
        <div class="col-lg-7">
            <h3 class="fw-bold mb-4">Checkout</h3>
            
            <div class="alert alert-info border-0 rounded-4 shadow-sm mb-4">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Demo Mode:</strong> This checkout has been simulated for demonstration purposes. No real payment will be processed.
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="checkout.php">
                <h5 class="fw-bold mb-3 mt-4">Contact Information</h5>
                <div class="row g-3 text-muted small">
                    <div class="col-sm-6">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($customer['first_name']) ?>" disabled>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($customer['last_name']) ?>" disabled>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control bg-light" value="<?= htmlspecialchars($customer['email']) ?>" disabled>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3 mt-5">Shipping Address</h5>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-bold text-muted">Full Address *</label>
                        <textarea name="address" class="form-control" rows="3" required placeholder="123 Apple Street, Tech City, TC 90210"></textarea>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3 mt-5">Payment Method</h5>
                <div class="bg-light p-4 rounded-4 border border-light">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="payment_method" id="pay1" value="Credit Card" required>
                        <label class="form-check-label fw-bold" for="pay1">
                            <i class="bi bi-credit-card me-2 text-primary"></i> Credit/Debit Card (Demo)
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="payment_method" id="pay2" value="Mobile Money">
                        <label class="form-check-label fw-bold" for="pay2">
                            <i class="bi bi-phone me-2 text-primary"></i> Mobile Money (Demo)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="pay3" value="Cash on Delivery">
                        <label class="form-check-label fw-bold" for="pay3">
                            <i class="bi bi-cash-stack me-2 text-primary"></i> Cash on Delivery (Demo)
                        </label>
                    </div>
                </div>
                
                <div class="mt-5">
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">Place Order</button>
                </div>
            </form>
        </div>
        
        <div class="col-lg-5">
            <div class="card bg-light border-0" style="border-radius: 8px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Order Summary</h5>
                    
                    <ul class="list-unstyled mb-4">
                        <?php foreach($cart as $item): ?>
                        <li class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-secondary me-2 rounded-circle"><?= $item['quantity'] ?></span>
                                <span class="small text-muted"><?= htmlspecialchars($item['name']) ?></span>
                            </div>
                            <span class="small fw-bold"><?= format_price($item['price'] * $item['quantity']) ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <hr class="border-secondary">
                    
                    <div class="d-flex justify-content-between mt-4">
                        <span class="fw-bold fs-5">Total</span>
                        <span class="fw-bold fs-5 text-primary"><?= format_price($subtotal) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
