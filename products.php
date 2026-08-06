<?php
// products.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/config/database.php';

// Build Query
$query = "SELECT * FROM products WHERE 1=1";
$params = [];
$types = "";

if (isset($_GET['category']) && is_numeric($_GET['category'])) {
    $query .= " AND category_id = ?";
    $params[] = $_GET['category'];
    $types .= "i";
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $query .= " AND (product_name LIKE ? OR brand LIKE ? OR description LIKE ?)";
    array_push($params, $search, $search, $search);
    $types .= "sss";
}

$query .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$products = $stmt->get_result();

// Get categories for filter sidebar
$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");
?>

<div class="container my-5">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 bg-light p-4" style="border-radius: 8px;">
                <h5 class="fw-bold mb-3">Categories</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <a href="<?= BASE_URL ?>products.php" class="text-decoration-none text-muted <?= !isset($_GET['category']) ? 'fw-bold text-dark' : '' ?>">All Products</a>
                    </li>
                    <?php while($cat = $categories->fetch_assoc()): ?>
                    <li class="mb-2">
                        <a href="<?= BASE_URL ?>products.php?category=<?= $cat['id'] ?>" class="text-decoration-none text-muted <?= (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'fw-bold text-dark' : '' ?>">
                            <?= htmlspecialchars($cat['name']) ?>
                        </a>
                    </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
        
        <!-- Product Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold m-0">
                    <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                        Search: "<?= htmlspecialchars($_GET['search']) ?>"
                    <?php else: ?>
                        Products
                    <?php endif; ?>
                </h2>
                <span class="text-muted small"><?= $products->num_rows ?> results</span>
            </div>

            <?php if ($products->num_rows > 0): ?>
            <div class="row row-cols-2 row-cols-md-3 g-4">
                <?php while($product = $products->fetch_assoc()): ?>
                <div class="col">
                    <div class="product-card">
                        <a href="<?= BASE_URL ?>product.php?id=<?= $product['id'] ?>">
                            <div class="product-img-wrapper">
                                <?php if($product['image']): ?>
                                <img src="<?= BASE_URL ?>assets/uploads/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" onerror="this.src='https://placehold.co/400x400/eeeeee/cccccc?text=No+Image'">
                                <?php else: ?>
                                <img src="https://placehold.co/400x400/eeeeee/cccccc?text=No+Image" alt="Placeholder">
                                <?php endif; ?>
                            </div>
                        </a>
                        <div class="product-info">
                            <span class="product-brand"><?= htmlspecialchars($product['brand']) ?></span>
                            <a href="<?= BASE_URL ?>product.php?id=<?= $product['id'] ?>" class="product-title">
                                <?= htmlspecialchars($product['product_name']) ?>
                            </a>
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-3">
                                <span class="product-price"><?= format_price($product['price']) ?></span>
                                <form action="<?= BASE_URL ?>cart-action.php" method="POST" class="add-to-cart-form m-0">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-outline-primary btn-sm">Add</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-search" style="font-size: 3rem; color: var(--border);"></i>
                <h4 class="mt-3">No products found</h4>
                <p class="text-muted">Try adjusting your filters or search term.</p>
                <a href="<?= BASE_URL ?>products.php" class="btn btn-outline-primary mt-2">Clear Filters</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
