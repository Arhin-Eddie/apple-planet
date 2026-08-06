<?php
// index.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/config/database.php';

// Fetch featured products (latest 4)
$stmt = $conn->prepare("SELECT * FROM products ORDER BY created_at DESC LIMIT 4");
$stmt->execute();
$featured_products = $stmt->get_result();

// Fetch categories
$stmt_cat = $conn->prepare("SELECT * FROM categories LIMIT 6");
$stmt_cat->execute();
$categories = $stmt_cat->get_result();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1 class="hero-title">Welcome to Apple Planet</h1>
        <p class="hero-subtitle">Your premier destination for independent, multi-brand electronics. Discover the latest in tech.</p>
        <a href="<?= BASE_URL ?>products.php" class="btn btn-primary btn-lg px-5">Shop Now</a>
    </div>
</section>

<div class="container my-5">
    <!-- Categories -->
    <div class="d-flex justify-content-between align-items-end mb-4">
        <h3 class="fw-bold mb-0">Shop by Category</h3>
    </div>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3 mb-5">
        <?php while($cat = $categories->fetch_assoc()): ?>
        <div class="col">
            <a href="<?= BASE_URL ?>products.php?category=<?= $cat['id'] ?>" class="text-decoration-none">
                <div class="card h-100 text-center py-3 border-0 bg-light" style="border-radius: 12px; transition: background-color 0.2s;">
                    <div class="card-body">
                        <h6 class="card-title mb-0 text-dark"><?= htmlspecialchars($cat['name']) ?></h6>
                    </div>
                </div>
            </a>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Featured Products -->
    <div class="d-flex justify-content-between align-items-end mb-4">
        <h3 class="fw-bold mb-0">New Arrivals</h3>
        <a href="<?= BASE_URL ?>products.php" class="text-decoration-none text-muted small">View all</a>
    </div>
    
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
        <?php while($product = $featured_products->fetch_assoc()): ?>
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
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
