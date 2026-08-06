<?php
// admin/products.php
require_once __DIR__ . '/admin_header.php';

$products = $conn->query("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    ORDER BY p.created_at DESC
");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Products</h3>
    <a href="<?= BASE_URL ?>admin/add-product.php" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Product
    </a>
</div>

<?php if(isset($_GET['success'])): ?><div class="alert alert-success">Product saved successfully.</div><?php endif; ?>
<?php if(isset($_GET['deleted'])): ?><div class="alert alert-success">Product deleted successfully.</div><?php endif; ?>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($products->num_rows > 0): while($row = $products->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php if($row['image']): ?>
                        <img src="<?= BASE_URL ?>assets/uploads/products/<?= htmlspecialchars($row['image']) ?>" alt="img" style="width: 40px; height: 40px; object-fit: contain; border: 1px solid var(--border); border-radius: 4px; background: white;">
                        <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px; border: 1px solid var(--border); border-radius: 4px;"><i class="bi bi-image"></i></div>
                        <?php endif; ?>
                    </td>
                    <td class="fw-bold">
                        <?= htmlspecialchars($row['product_name']) ?>
                    </td>
                    <td class="text-muted"><?= htmlspecialchars($row['category_name']) ?></td>
                    <td class="text-muted"><?= htmlspecialchars($row['brand']) ?></td>
                    <td class="fw-bold"><?= format_price($row['price']) ?></td>
                    <td>
                        <?php if($row['quantity'] > 0): ?>
                            <span class="badge bg-success"><?= $row['quantity'] ?></span>
                        <?php else: ?>
                            <span class="badge bg-danger">Out of stock</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a href="<?= BASE_URL ?>admin/edit-product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-secondary me-2">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form method="POST" action="delete-product.php" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="7" class="text-center py-4 text-muted">No products found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
