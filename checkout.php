<?php
// checkout.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/config/database.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart)) {
    redirect(BASE_URL . 'cart.php');
}

$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += ($item['price'] * $item['quantity']);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = sanitize_input($_POST['first_name'] ?? '');
    $last_name = sanitize_input($_POST['last_name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $phone = sanitize_input($_POST['phone'] ?? '');
    $address = sanitize_input($_POST['address'] ?? '');
    
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($address)) {
        $error = "All fields are required.";
    } else {
        // Begin Transaction
        $conn->begin_transaction();
        
        try {
            // Check if customer exists
            $stmt = $conn->prepare("SELECT id FROM customers WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $res = $stmt->get_result();
            
            if ($res->num_rows > 0) {
                $customer = $res->fetch_assoc();
                $customer_id = $customer['id'];
            } else {
                // Insert new customer
                $stmt = $conn->prepare("INSERT INTO customers (first_name, last_name, email, phone) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $first_name, $last_name, $email, $phone);
                $stmt->execute();
                $customer_id = $conn->insert_id;
            }
            
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
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="checkout.php">
                <h5 class="fw-bold mb-3 mt-4">Contact Information</h5>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3 mt-5">Shipping Address</h5>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Full Address</label>
                        <textarea name="address" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                
                <div class="mt-5">
                    <button type="submit" class="btn btn-primary btn-lg w-100">Place Order</button>
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
                        <span class="fw-bold fs-5"><?= format_price($subtotal) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
