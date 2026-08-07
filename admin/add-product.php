<?php
// admin/add-product.php
require_once __DIR__ . '/admin_header.php';

$error = '';

$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = (int)$_POST['category_id'];
    $brand = sanitize_input($_POST['brand']);
    $product_name = sanitize_input($_POST['product_name']);
    $description = sanitize_input($_POST['description']);
    $specifications = sanitize_input($_POST['specifications']);
    $price = (float)$_POST['price'];
    $quantity = (int)$_POST['quantity'];
    $badge = sanitize_input($_POST['badge'] ?? '');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    $image = '';
    
    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../assets/uploads/products/';
        $tmp_name = $_FILES['image']['tmp_name'];
        $name = basename($_FILES['image']['name']);
        
        // simple unique name
        $new_name = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $name);
        
        if (move_uploaded_file($tmp_name, $upload_dir . $new_name)) {
            $image = $new_name;
        }
    }
    
    if (empty($product_name) || empty($price) || empty($category_id) || empty($brand)) {
        $error = "Category, Brand, Name, and Price are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO products (category_id, brand, product_name, description, specifications, price, quantity, badge, is_featured, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssisis", $category_id, $brand, $product_name, $description, $specifications, $price, $quantity, $badge, $is_featured, $image);
        
        if ($stmt->execute()) {
            redirect(BASE_URL . 'admin/products.php?success=1');
        } else {
            $error = "Failed to add product.";
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Add Product</h3>
    <a href="<?= BASE_URL ?>admin/products.php" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="admin-card">
    <form method="POST" enctype="multipart/form-data">
        <div class="row g-4">
            <div class="col-md-8">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Product Name *</label>
                    <input type="text" name="product_name" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="5"></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Specifications (One per line)</label>
                    <textarea name="specifications" class="form-control" rows="4"></textarea>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Brand *</label>
                    <input type="text" name="brand" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Category *</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        <?php while($cat = $categories->fetch_assoc()): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Price ($) *</label>
                    <input type="number" name="price" class="form-control" step="0.01" min="0" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Quantity *</label>
                    <input type="number" name="quantity" class="form-control" value="0" min="0" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold">Product Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Badge (Optional)</label>
                    <input type="text" name="badge" class="form-control" placeholder="e.g. NEW, SALE">
                </div>
                
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1">
                    <label class="form-check-label text-muted small fw-bold" for="is_featured">
                        Show in Hero Slider
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-2">Save Product</button>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
