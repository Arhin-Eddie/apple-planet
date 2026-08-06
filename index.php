<?php
// index.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/config/database.php';

// Fetch featured products (latest 8 for a nice carousel)
$stmt = $conn->prepare("SELECT * FROM products ORDER BY created_at DESC LIMIT 8");
$stmt->execute();
$featured_products = $stmt->get_result();

// Fetch categories
$stmt_cat = $conn->prepare("SELECT * FROM categories LIMIT 8");
$stmt_cat->execute();
$categories = $stmt_cat->get_result();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container fade-in-up stagger-1">
        <h1 class="hero-title">Welcome to Apple Planet</h1>
        <p class="hero-subtitle">Your premier destination for independent, multi-brand electronics. Discover the latest in tech.</p>
        <a href="<?= BASE_URL ?>products.php" class="btn btn-primary btn-lg px-5">Shop Now</a>
    </div>
</section>

<div class="container my-5 overflow-hidden">
    <!-- Categories (Horizontal Carousel) -->
    <div class="d-flex justify-content-between align-items-end mb-4 fade-in-up stagger-2">
        <h3 class="fw-bold mb-0">Shop by Category</h3>
    </div>
    <div class="horizontal-scroll mb-5 fade-in-up stagger-2">
        <?php while($cat = $categories->fetch_assoc()): ?>
        <div class="col" style="max-width: 150px; flex: 0 0 auto;">
            <a href="<?= BASE_URL ?>products.php?category=<?= $cat['id'] ?>" class="text-decoration-none">
                <div class="card h-100 text-center py-3 border-0 bg-light" style="border-radius: 12px; transition: transform 0.2s, background-color 0.2s;">
                    <div class="card-body px-2 py-3">
                        <h6 class="card-title mb-0 text-dark small fw-bold"><?= htmlspecialchars($cat['name']) ?></h6>
                    </div>
                </div>
            </a>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Featured Products (Horizontal Carousel) -->
    <div class="d-flex justify-content-between align-items-end mb-4 mt-5 fade-in-up stagger-3">
        <h3 class="fw-bold mb-0">New Arrivals</h3>
        <a href="<?= BASE_URL ?>products.php" class="text-decoration-none text-muted small">View all</a>
    </div>
    
    <div class="horizontal-scroll pb-4 fade-in-up stagger-3">
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
