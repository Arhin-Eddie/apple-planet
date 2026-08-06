<?php
// product.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    redirect(BASE_URL . 'products.php');
}

$stmt = $conn->prepare("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirect(BASE_URL . 'products.php');
}

$product = $result->fetch_assoc();
?>

<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>products.php" class="text-decoration-none text-muted">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['product_name']) ?></li>
        </ol>
    </nav>

    <div class="row g-5 mt-2">
        <!-- Product Image -->
        <div class="col-md-6">
            <div class="bg-light rounded p-5 text-center h-100 d-flex align-items-center justify-content-center" style="border: 1px solid var(--border);">
                <?php if($product['image']): ?>
                <img src="<?= BASE_URL ?>assets/uploads/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" class="img-fluid" style="max-height: 500px;" onerror="this.src='https://placehold.co/600x600/eeeeee/cccccc?text=No+Image'">
                <?php else: ?>
                <img src="https://placehold.co/600x600/eeeeee/cccccc?text=No+Image" alt="Placeholder" class="img-fluid">
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Product Details -->
        <div class="col-md-6">
            <span class="text-muted text-uppercase small fw-bold tracking-wide"><?= htmlspecialchars($product['brand']) ?></span>
            <h1 class="fw-bold mb-3"><?= htmlspecialchars($product['product_name']) ?></h1>
            <h3 class="fw-normal mb-4"><?= format_price($product['price']) ?></h3>
            
            <p class="text-muted mb-4 lh-lg">
                <?= nl2br(htmlspecialchars($product['description'])) ?>
            </p>
            
            <?php if ($product['specifications']): ?>
            <div class="mb-4 p-4 bg-light rounded" style="border: 1px solid var(--border);">
                <h6 class="fw-bold mb-3">Specifications</h6>
                <ul class="list-unstyled mb-0 text-muted small lh-lg">
                    <?php 
                    $specs = explode("\n", $product['specifications']);
                    foreach($specs as $spec):
                        if (trim($spec) != ''):
                    ?>
                    <li><i class="bi bi-check2 me-2"></i> <?= htmlspecialchars($spec) ?></li>
                    <?php endif; endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <div class="mt-5">
                <?php if ($product['quantity'] > 0): ?>
                <p class="text-success small fw-bold mb-3"><i class="bi bi-check-circle-fill me-1"></i> In Stock</p>
                <form action="<?= BASE_URL ?>cart-action.php" method="POST" class="add-to-cart-form d-flex align-items-center gap-3">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    
                    <div style="width: 100px;">
                        <input type="number" name="quantity" class="form-control" value="1" min="1" max="<?= $product['quantity'] ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-primary px-5 py-2">Add to Cart</button>
                </form>
                <?php else: ?>
                <p class="text-danger fw-bold"><i class="bi bi-x-circle-fill me-1"></i> Out of Stock</p>
                <button class="btn btn-secondary px-5" disabled>Unavailable</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
