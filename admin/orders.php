<?php
// admin/orders.php
require_once __DIR__ . '/admin_header.php';

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = (int)($_POST['order_id'] ?? 0);
    $status = sanitize_input($_POST['status'] ?? '');
    
    $valid_statuses = ['Pending', 'Processing', 'Completed', 'Cancelled'];
    
    if ($order_id > 0 && in_array($status, $valid_statuses)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        if ($stmt->execute()) {
            $success = "Order #{$order_id} status updated to {$status}.";
        }
    }
}

$orders = $conn->query("
    SELECT o.id, o.total_amount, o.status, o.order_date, c.first_name, c.last_name, c.email 
    FROM orders o 
    JOIN customers c ON o.customer_id = c.id 
    ORDER BY o.order_date DESC
");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Orders</h3>
</div>

<?php if($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="text-end">Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($orders->num_rows > 0): while($row = $orders->fetch_assoc()): 
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
                    <td>
                        <div class="fw-bold"><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></div>
                        <div class="text-muted small"><?= htmlspecialchars($row['email']) ?></div>
                    </td>
                    <td class="text-muted"><?= date('M j, Y H:i', strtotime($row['order_date'])) ?></td>
                    <td class="fw-bold"><?= format_price($row['total_amount']) ?></td>
                    <td><span class="badge <?= $status_badge ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                    <td class="text-end">
                        <form method="POST" class="d-flex justify-content-end align-items-center gap-2">
                            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                            <select name="status" class="form-select form-select-sm" style="width: auto;">
                                <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Processing" <?= $row['status'] == 'Processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="Completed" <?= $row['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="Cancelled" <?= $row['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">No orders found</h5>
                        <p class="text-muted mb-0">When customers place orders, they will appear here.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
