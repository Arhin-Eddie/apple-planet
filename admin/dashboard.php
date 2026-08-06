<?php
// admin/dashboard.php
require_once __DIR__ . '/admin_header.php';

// Fetch stats
$stats = [
    'products' => 0,
    'categories' => 0,
    'orders' => 0,
    'revenue' => 0,
    'pending' => 0
];

$res = $conn->query("SELECT COUNT(*) as count FROM products");
if ($row = $res->fetch_assoc()) $stats['products'] = $row['count'];

$res = $conn->query("SELECT COUNT(*) as count FROM categories");
if ($row = $res->fetch_assoc()) $stats['categories'] = $row['count'];

$res = $conn->query("SELECT COUNT(*) as count, SUM(total_amount) as rev FROM orders");
if ($row = $res->fetch_assoc()) {
    $stats['orders'] = $row['count'];
    $stats['revenue'] = $row['rev'] ?: 0;
}

$res = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'Pending'");
if ($row = $res->fetch_assoc()) $stats['pending'] = $row['count'];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Dashboard</h3>
    <span class="text-muted"><?= date('F j, Y') ?></span>
</div>

<div class="row g-4 mb-5">
    <div class="col-sm-6 col-lg-3">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1 fw-bold text-uppercase">Total Revenue</p>
                    <h3 class="fw-bold m-0"><?= format_price($stats['revenue']) ?></h3>
                </div>
                <div class="p-2 bg-success bg-opacity-10 rounded text-success">
                    <i class="bi bi-currency-dollar fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1 fw-bold text-uppercase">Total Orders</p>
                    <h3 class="fw-bold m-0"><?= number_format($stats['orders']) ?></h3>
                </div>
                <div class="p-2 bg-primary bg-opacity-10 rounded text-primary">
                    <i class="bi bi-box-seam fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1 fw-bold text-uppercase">Pending Orders</p>
                    <h3 class="fw-bold m-0 text-warning"><?= number_format($stats['pending']) ?></h3>
                </div>
                <div class="p-2 bg-warning bg-opacity-10 rounded text-warning">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1 fw-bold text-uppercase">Products</p>
                    <h3 class="fw-bold m-0"><?= number_format($stats['products']) ?></h3>
                </div>
                <div class="p-2 bg-info bg-opacity-10 rounded text-info">
                    <i class="bi bi-grid fs-5"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold m-0">Recent Orders</h5>
        <a href="<?= BASE_URL ?>admin/orders.php" class="btn btn-sm btn-outline-secondary">View All</a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $recent_orders = $conn->query("
                    SELECT o.id, o.order_date, o.status, o.total_amount, c.first_name, c.last_name 
                    FROM orders o 
                    JOIN customers c ON o.customer_id = c.id 
                    ORDER BY o.order_date DESC LIMIT 5
                ");
                if ($recent_orders->num_rows > 0):
                    while($row = $recent_orders->fetch_assoc()):
                        $status_badge = match($row['status']) {
                            'Pending' => 'bg-warning text-dark',
                            'Processing' => 'bg-primary',
                            'Completed' => 'bg-success',
                            'Cancelled' => 'bg-danger',
                            default => 'bg-secondary'
                        };
                ?>
                <tr>
                    <td class="fw-bold">#<?= str_pad($row['id'], 6, '0', STR_PAD_LEFT) ?></td>
                    <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                    <td class="text-muted small"><?= date('M j, Y', strtotime($row['order_date'])) ?></td>
                    <td><span class="badge <?= $status_badge ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                    <td class="text-end fw-bold"><?= format_price($row['total_amount']) ?></td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No recent orders found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
