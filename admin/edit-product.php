<?php
// admin/edit-product.php
require_once __DIR__ . '/admin_header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) redirect(BASE_URL . 'admin/products.php');

$error = '';
$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) redirect(BASE_URL . 'admin/products.php');
$product = $res->fetch_assoc();

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
    
    $image = $product['image'];
    
    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../assets/uploads/products/';
        
        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $tmp_name = $_FILES['image']['tmp_name'];
        $name = basename($_FILES['image']['name']);
        
        $new_name = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $name);
        
        if (move_uploaded_file($tmp_name, $upload_dir . $new_name)) {
            $image = $new_name;
        }
    }
    
    if (empty($product_name) || empty($price) || empty($category_id) || empty($brand)) {
        $error = "Category, Brand, Name, and Price are required.";
    } else {
        $stmt = $conn->prepare("UPDATE products SET category_id=?, brand=?, product_name=?, description=?, specifications=?, price=?, quantity=?, badge=?, is_featured=?, image=? WHERE id=?");
        $stmt->bind_param("isssssisisi", $category_id, $brand, $product_name, $description, $specifications, $price, $quantity, $badge, $is_featured, $image, $id);
        
        if ($stmt->execute()) {
            redirect(BASE_URL . 'admin/products.php?success=1');
        } else {
            $error = "Failed to update product.";
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Edit Product</h3>
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
                    <input type="text" name="product_name" class="form-control" id="floatingName" placeholder="Product Name" value="<?= htmlspecialchars($product['product_name']) ?>" required>
                    <label for="floatingName">Product Name *</label>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($product['description']) ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Specifications (One per line)</label>
                    <textarea name="specifications" class="form-control" rows="4"><?= htmlspecialchars($product['specifications']) ?></textarea>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <input type="text" name="brand" class="form-control" id="floatingBrand" placeholder="Brand" value="<?= htmlspecialchars($product['brand']) ?>" required>
                    <label for="floatingBrand">Brand *</label>
                </div>
                
                <div class="form-floating mb-3">
                    <select name="category_id" class="form-select" id="floatingCategory" required>
                        <option value="">Select Category</option>
                        <?php while($cat = $categories->fetch_assoc()): ?>
                            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                    <label for="floatingCategory">Category *</label>
                </div>
                
                <div class="form-floating mb-3">
                    <input type="number" name="price" class="form-control" id="floatingPrice" placeholder="Price" step="0.01" min="0" value="<?= htmlspecialchars($product['price']) ?>" required>
                    <label for="floatingPrice">Price ($) *</label>
                </div>
                
                <div class="form-floating mb-3">
                    <input type="number" name="quantity" class="form-control" id="floatingQuantity" placeholder="Quantity" value="<?= htmlspecialchars($product['quantity']) ?>" min="0" required>
                    <label for="floatingQuantity">Quantity *</label>
                </div>
                
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold mb-2">Product Image</label>
                    
                    <div id="image-dropzone" class="image-dropzone mb-2">
                        <i class="bi bi-image text-muted fs-3 mb-2 d-block"></i>
                        <span class="text-muted small fw-bold">Click to upload or drag and drop</span>
                        <br>
                        <span class="text-muted" style="font-size: 0.75rem;">JPG, PNG, WebP (Max 2MB)</span>
                    </div>
                    
                    <input type="file" name="image" id="image-input" class="d-none" accept="image/jpeg, image/png, image/webp">
                    
                    <div id="image-preview-container" class="d-flex align-items-center mt-2 p-2 bg-light rounded border border-light">
                        <?php if($product['image']): ?>
                            <img id="image-preview" src="<?= BASE_URL ?>assets/uploads/products/<?= htmlspecialchars($product['image']) ?>" alt="Preview" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" class="me-3 border">
                            <span id="image-name" class="text-muted small text-truncate" style="max-width: 200px;">Current Image</span>
                        <?php else: ?>
                            <img id="image-preview" src="" alt="Preview" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; display: none;" class="me-3 border">
                            <span id="image-name" class="text-muted small text-truncate" style="max-width: 200px;">No image selected</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="form-floating mb-4">
                    <input type="text" name="badge" class="form-control" id="floatingBadge" placeholder="Badge" value="<?= htmlspecialchars($product['badge'] ?? '') ?>">
                    <label for="floatingBadge">Badge (Optional, e.g. SALE)</label>
                </div>
                
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" <?= isset($product['is_featured']) && $product['is_featured'] ? 'checked' : '' ?>>
                    <label class="form-check-label text-muted small fw-bold" for="is_featured">
                        Show in Hero Slider
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-2">Update Product</button>
            </div>
        </div>
    </form>
</div>

<!-- Scripts -->
<script src="<?= BASE_URL ?>admin/assets/js/product-form.js"></script>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
