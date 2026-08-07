<?php
// account.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/config/database.php';

require_customer();

$customer_id = $_SESSION['customer_id'];

// Fetch customer info
$stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();

// Fetch orders
$stmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY order_date DESC");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<div class="container my-5">
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="fw-bold m-0">My Account</h2>
            <p class="text-muted">Welcome back, <?= htmlspecialchars($customer['first_name']) ?>.</p>
        </div>
    </div>

    <div class="row g-5">
        <!-- Sidebar Menu / Info -->
        <div class="col-lg-4">
            <div class="card border-0 bg-light p-4 rounded-4 mb-4">
                <h5 class="fw-bold mb-3">Account Information</h5>
                <ul class="list-unstyled mb-0 text-muted small">
                    <li class="mb-2"><strong class="text-dark">Name:</strong> <?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?></li>
                    <li class="mb-2"><strong class="text-dark">Email:</strong> <?= htmlspecialchars($customer['email']) ?></li>
                    <li class="mb-2"><strong class="text-dark">Phone:</strong> <?= htmlspecialchars($customer['phone'] ?: 'N/A') ?></li>
                    <li><strong class="text-dark">Member Since:</strong> <?= date('F Y', strtotime($customer['created_at'])) ?></li>
                </ul>
            </div>
            
            <div class="list-group border-0 rounded-4 shadow-sm">
                <a href="#" class="list-group-item list-group-item-action py-3 active border-0" aria-current="true">
                    <i class="bi bi-box-seam me-2"></i> My Orders
                </a>
                <a href="#" class="list-group-item list-group-item-action py-3 text-muted border-0">
                    <i class="bi bi-person me-2"></i> Edit Profile (Coming Soon)
                </a>
                <a href="#" class="list-group-item list-group-item-action py-3 text-muted border-0">
                    <i class="bi bi-geo-alt me-2"></i> Saved Addresses (Coming Soon)
                </a>
                <a href="<?= BASE_URL ?>logout.php" class="list-group-item list-group-item-action py-3 text-danger border-0">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8">
            <h4 class="fw-bold mb-4">Order History</h4>
            
            <?php if ($orders->num_rows > 0): ?>
                <div class="table-responsive bg-white rounded-4 shadow-sm border border-light">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th class="border-0 px-4 py-3">Order ID</th>
                                <th class="border-0 py-3">Date</th>
                                <th class="border-0 py-3">Total</th>
                                <th class="border-0 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = $orders->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-4 py-3 fw-bold">#<?= $order['id'] ?></td>
                                    <td class="py-3 text-muted small"><?= date('M d, Y', strtotime($order['order_date'])) ?></td>
                                    <td class="py-3 fw-bold"><?= format_price($order['total_amount']) ?></td>
                                    <td class="py-3">
                                        <?php
                                        $badgeClass = 'bg-secondary';
                                        if ($order['status'] === 'Completed') $badgeClass = 'bg-success';
                                        if ($order['status'] === 'Processing') $badgeClass = 'bg-primary';
                                        if ($order['status'] === 'Cancelled') $badgeClass = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2 fw-normal">
                                            <?= htmlspecialchars($order['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5 bg-light rounded-4">
                    <i class="bi bi-bag-x text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No orders yet</h5>
                    <p class="text-muted small">When you place an order, it will appear here.</p>
                    <a href="<?= BASE_URL ?>products.php" class="btn btn-outline-primary mt-2">Start Shopping</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
