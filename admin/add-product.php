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
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $name = basename($_FILES['image']['name']);
        $new_name = time() . '_main_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $name);
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_name)) {
            $image = $new_name;
        }
    }
    
    // Handle hover image upload
    $hover_image = '';
    if (isset($_FILES['hover_image']) && $_FILES['hover_image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../assets/uploads/products/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $name = basename($_FILES['hover_image']['name']);
        $new_name = time() . '_hover_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $name);
        
        if (move_uploaded_file($_FILES['hover_image']['tmp_name'], $upload_dir . $new_name)) {
            $hover_image = $new_name;
        }
    }
    
    if (empty($product_name) || empty($price) || empty($category_id) || empty($brand)) {
        $error = "Category, Brand, Name, and Price are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO products (category_id, brand, product_name, description, specifications, price, quantity, badge, is_featured, image, hover_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssisiss", $category_id, $brand, $product_name, $description, $specifications, $price, $quantity, $badge, $is_featured, $image, $hover_image);
        
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
                <div class="form-floating mb-3">
                    <input type="text" name="product_name" class="form-control" id="floatingName" placeholder="Product Name" value="<?= htmlspecialchars($_POST['product_name'] ?? '') ?>" required>
                    <label for="floatingName">Product Name *</label>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Specifications (One per line)</label>
                    <textarea name="specifications" class="form-control" rows="4"><?= htmlspecialchars($_POST['specifications'] ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <input type="text" name="brand" class="form-control" id="floatingBrand" placeholder="Brand" value="<?= htmlspecialchars($_POST['brand'] ?? '') ?>" required>
                    <label for="floatingBrand">Brand *</label>
                </div>
                
                <div class="form-floating mb-3">
                    <select name="category_id" class="form-select" id="floatingCategory" required>
                        <option value="">Select Category</option>
                        <?php while($cat = $categories->fetch_assoc()): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                    <label for="floatingCategory">Category *</label>
                </div>
                
                <div class="form-floating mb-3">
                    <input type="number" name="price" class="form-control" id="floatingPrice" placeholder="Price" step="0.01" min="0" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" required>
                    <label for="floatingPrice">Price ($) *</label>
                </div>
                
                <div class="form-floating mb-3">
                    <input type="number" name="quantity" class="form-control" id="floatingQuantity" placeholder="Quantity" value="<?= htmlspecialchars($_POST['quantity'] ?? '') ?>" min="0" required>
                    <label for="floatingQuantity">Quantity *</label>
                </div>
                
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold mb-0">Primary Image *</label>
                    <div class="form-text small mb-2 mt-0">Main product image</div>
                    
                    <div id="image-dropzone" class="image-dropzone mb-2">
                        <i class="bi bi-image text-muted fs-3 mb-2 d-block"></i>
                        <span class="text-muted small fw-bold">Click to upload or drag and drop</span>
                        <br>
                        <span class="text-muted" style="font-size: 0.75rem;">JPG, PNG, WebP (Max 2MB)</span>
                    </div>
                    
                    <input type="file" name="image" id="image-input" class="d-none" accept="image/jpeg, image/png, image/webp" required>
                    
                    <div id="image-preview-container" class="d-none d-flex align-items-center mt-2 p-2 bg-light rounded border border-light">
                        <img id="image-preview" src="" alt="Preview" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" class="me-3 border">
                        <span id="image-name" class="text-muted small text-truncate" style="max-width: 200px;"></span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold mb-0">Hover Image (Optional)</label>
                    <div class="form-text small mb-2 mt-0">Shown when users hover over the product</div>
                    
                    <div id="hover-dropzone" class="image-dropzone mb-2">
                        <i class="bi bi-front text-muted fs-3 mb-2 d-block"></i>
                        <span class="text-muted small fw-bold">Click to upload or drag and drop</span>
                        <br>
                        <span class="text-muted" style="font-size: 0.75rem;">JPG, PNG, WebP (Max 2MB)</span>
                    </div>
                    
                    <input type="file" name="hover_image" id="hover-input" class="d-none" accept="image/jpeg, image/png, image/webp">
                    
                    <div id="hover-preview-container" class="d-none d-flex align-items-center mt-2 p-2 bg-light rounded border border-light">
                        <img id="hover-preview" src="" alt="Preview" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" class="me-3 border">
                        <span id="hover-name" class="text-muted small text-truncate" style="max-width: 200px;"></span>
                    </div>
                </div>
                
                <div class="form-floating mb-4">
                    <input type="text" name="badge" class="form-control" id="floatingBadge" placeholder="Badge" value="<?= htmlspecialchars($_POST['badge'] ?? '') ?>">
                    <label for="floatingBadge">Badge (Optional, e.g. NEW)</label>
                </div>
                
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" <?= isset($_POST['is_featured']) ? 'checked' : '' ?>>
                    <label class="form-check-label text-muted small fw-bold" for="is_featured">
                        Show in Hero Slider
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-2">Add Product</button>
            </div>
        </div>
    </form>
</div>

<!-- Scripts -->
<script src="<?= BASE_URL ?>admin/assets/js/product-form.js"></script>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
