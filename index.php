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

// Fetch featured products for Hero Slider
$stmt_hero = $conn->prepare("SELECT * FROM products WHERE is_featured = 1 ORDER BY created_at DESC LIMIT 5");
$stmt_hero->execute();
$hero_products = $stmt_hero->get_result();
?>

<!-- Dynamic Hero Carousel -->
<?php if($hero_products->num_rows > 0): ?>
<section class="hero-carousel-section pb-5 pt-4">
    <div class="container fade-in-up stagger-1">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner rounded-4 shadow-sm" style="background-color: var(--secondary-bg);">
                <?php 
                $active = true;
                while($hero = $hero_products->fetch_assoc()): 
                ?>
                <div class="carousel-item <?= $active ? 'active' : '' ?>">
                    <div class="row align-items-center" style="min-height: 400px; padding: 3rem 4rem;">
                        <div class="col-md-6 mb-4 mb-md-0 text-center text-md-start">
                            <h1 class="hero-title mb-3" style="font-size: 2.5rem; letter-spacing: -1px;"><?= htmlspecialchars($hero['product_name']) ?></h1>
                            <p class="text-muted mb-4 lead" style="font-size: 1.1rem;"><?= htmlspecialchars($hero['brand']) ?> Collection. Premium design meets unparalleled performance.</p>
                            <a href="<?= BASE_URL ?>product.php?id=<?= $hero['id'] ?>" class="btn btn-primary px-4 py-2" style="border-radius: 4px;">Shop Now</a>
                        </div>
                        <div class="col-md-6 text-center">
                            <?php if($hero['image']): ?>
                                <img src="<?= BASE_URL ?>assets/uploads/products/<?= htmlspecialchars($hero['image']) ?>" class="img-fluid" style="max-height: 320px; object-fit: contain; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1)); transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" alt="<?= htmlspecialchars($hero['product_name']) ?>">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php 
                $active = false;
                endwhile; 
                ?>
            </div>
            <?php if($hero_products->num_rows > 1): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" style="width: 5%;">
                <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(1);"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" style="width: 5%;">
                <span class="carousel-control-next-icon" aria-hidden="true" style="filter: invert(1);"></span>
                <span class="visually-hidden">Next</span>
            </button>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

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
                <div class="product-img-wrapper">
                    <?php if(!empty($product['badge'])): ?>
                        <span class="product-badge"><?= htmlspecialchars($product['badge']) ?></span>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>product.php?id=<?= $product['id'] ?>">
                        <?php if($product['image']): ?>
                        <img src="<?= BASE_URL ?>assets/uploads/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" onerror="this.src='https://placehold.co/400x400/eeeeee/cccccc?text=No+Image'">
                        <?php else: ?>
                        <img src="https://placehold.co/400x400/eeeeee/cccccc?text=No+Image" alt="Placeholder">
                        <?php endif; ?>
                    </a>
                    <div class="product-actions">
                        <button type="button" class="action-btn quick-view-btn" data-id="<?= $product['id'] ?>" title="Quick View">
                            <i class="bi bi-eye"></i>
                        </button>
                        <form action="<?= BASE_URL ?>cart-action.php" method="POST" class="m-0">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="action-btn" title="Add to Bag">
                                <i class="bi bi-bag"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="product-info">
                    <span class="product-brand"><?= htmlspecialchars($product['brand']) ?></span>
                    <a href="<?= BASE_URL ?>product.php?id=<?= $product['id'] ?>" class="product-title">
                        <?= htmlspecialchars($product['product_name']) ?>
                    </a>
                    <span class="product-price"><?= format_price($product['price']) ?></span>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
