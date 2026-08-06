<?php
// order-success.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/config/database.php';

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($order_id <= 0) {
    redirect(BASE_URL);
}

// Fetch order details
$stmt = $conn->prepare("
    SELECT o.id, o.total_amount, o.order_date, c.email 
    FROM orders o 
    JOIN customers c ON o.customer_id = c.id 
    WHERE o.id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    redirect(BASE_URL);
}

$order = $res->fetch_assoc();
?>

<div class="container my-5 py-5 text-center">
    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
    <h1 class="fw-bold mt-4 mb-3">Order Successfully Placed</h1>
    <p class="text-muted fs-5 mb-5">Thank you for your purchase. Your order number is <strong>#<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></strong>.</p>
    
    <div class="card mx-auto bg-light border-0 text-start" style="max-width: 500px; border-radius: 8px;">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3">Order Details</h5>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Total Amount</span>
                <span class="fw-bold"><?= format_price($order['total_amount']) ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Date</span>
                <span class="fw-bold"><?= date('M j, Y', strtotime($order['order_date'])) ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Confirmation Email</span>
                <span class="fw-bold"><?= htmlspecialchars($order['email']) ?></span>
            </div>
        </div>
    </div>
    
    <div class="mt-5">
        <a href="<?= BASE_URL ?>" class="btn btn-outline-primary px-4 me-2">Back to Home</a>
        <a href="<?= BASE_URL ?>products.php" class="btn btn-primary px-4">Continue Shopping</a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
