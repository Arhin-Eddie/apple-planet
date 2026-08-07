<?php
// processing.php
ob_start();
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/database.php';

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($order_id === 0) {
    redirect(BASE_URL);
}

// Fetch order total
$stmt = $conn->prepare("SELECT total_amount FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirect(BASE_URL);
}

$order = $result->fetch_assoc();
?>

<div class="d-flex flex-column justify-content-center align-items-center min-vh-100 bg-light">
    <div class="text-center p-5 bg-white rounded-4 shadow-sm" style="max-width: 400px; width: 100%;">
        <div class="mb-4">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        
        <h4 class="fw-bold mb-1">Apple Planet</h4>
        <p class="text-muted small mb-4">Processing your order...</p>
        
        <div class="p-3 bg-light rounded-3 mb-4 text-start border">
            <span class="d-block text-muted small fw-bold text-uppercase mb-1">Order Total</span>
            <span class="fs-3 fw-bold text-dark"><?= format_price($order['total_amount']) ?></span>
        </div>
        
        <p id="processing-step" class="text-primary fw-bold mb-0 fade-in-out">Preparing order...</p>
    </div>
</div>

<style>
.fade-in-out {
    animation: fadeInOut 0.6s ease-in-out;
}
@keyframes fadeInOut {
    0% { opacity: 0; transform: translateY(5px); }
    100% { opacity: 1; transform: translateY(0); }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const steps = [
        "Preparing order...",
        "Confirming demo payment...",
        "Finalizing purchase..."
    ];
    
    let currentStep = 0;
    const stepEl = document.getElementById('processing-step');
    
    const interval = setInterval(() => {
        currentStep++;
        if (currentStep < steps.length) {
            // Re-trigger animation
            stepEl.classList.remove('fade-in-out');
            void stepEl.offsetWidth; // trigger reflow
            stepEl.classList.add('fade-in-out');
            
            stepEl.textContent = steps[currentStep];
        } else {
            clearInterval(interval);
            window.location.href = "<?= BASE_URL ?>order-success.php?id=<?= $order_id ?>";
        }
    }, 700); // ~2 seconds total for 3 steps
});
</script>

</body>
</html>
