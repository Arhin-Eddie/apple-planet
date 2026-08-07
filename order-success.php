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

<style>
.success-checkmark {
    font-size: 5rem;
    animation: scaleFadeIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    opacity: 0;
    transform: scale(0.5);
}

@keyframes scaleFadeIn {
    0% {
        opacity: 0;
        transform: scale(0.5);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}
</style>

<div class="container my-5 py-5 text-center fade-in-up">
    <i class="bi bi-check-circle-fill text-success success-checkmark mb-2 d-inline-block"></i>
    <h1 class="fw-bold mt-3 mb-2" style="letter-spacing: -0.5px;">Order Successfully Placed</h1>
    <p class="text-muted fs-5 mb-5">Thank you for your purchase.</p>
    
    <div class="card mx-auto bg-white shadow-sm text-start" style="max-width: 450px; border-radius: 12px; border: 1px solid var(--border);">
        <div class="card-body p-4 p-md-5">
            <h5 class="fw-bold mb-4 text-center">Order Receipt</h5>
            
            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                <span class="text-muted small text-uppercase fw-bold">Order Number</span>
                <span class="fw-bold">#<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></span>
            </div>
            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                <span class="text-muted small text-uppercase fw-bold">Date</span>
                <span class="fw-bold"><?= date('M j, Y', strtotime($order['order_date'])) ?></span>
            </div>
            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                <span class="text-muted small text-uppercase fw-bold">Payment Method</span>
                <span class="fw-bold">Demo Payment</span>
            </div>
            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                <span class="text-muted small text-uppercase fw-bold">Order Status</span>
                <span class="badge bg-primary rounded-pill px-3">Pending</span>
            </div>
            <div class="d-flex justify-content-between pt-2">
                <span class="text-dark fw-bold">Total Amount</span>
                <span class="fw-bold fs-5 text-primary"><?= format_price($order['total_amount']) ?></span>
            </div>
        </div>
    </div>
    
    <div class="mt-5">
        <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary px-4 py-2 me-2 rounded-1 fw-bold tracking-wider">BACK TO HOME</a>
        <a href="<?= BASE_URL ?>products.php" class="btn btn-primary px-4 py-2 rounded-1 fw-bold tracking-wider">CONTINUE SHOPPING</a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
