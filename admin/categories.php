<?php
// admin/categories.php
require_once __DIR__ . '/admin_header.php';

$error = '';
$success = '';

// Handle Add / Edit / Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $name = sanitize_input($_POST['name'] ?? '');
        if ($name) {
            $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->bind_param("s", $name);
            if ($stmt->execute()) $success = "Category added successfully.";
            else $error = "Failed to add category.";
        } else {
            $error = "Category name required.";
        }
    } elseif ($action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        $name = sanitize_input($_POST['name'] ?? '');
        if ($id > 0 && $name) {
            $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->bind_param("si", $name, $id);
            if ($stmt->execute()) $success = "Category updated successfully.";
            else $error = "Failed to update category.";
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) $success = "Category deleted successfully.";
            else $error = "Failed to delete category.";
        }
    }
}

$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Categories</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="bi bi-plus-lg me-1"></i> Add Category
    </button>
</div>

<?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($categories->num_rows > 0): while($row = $categories->fetch_assoc()): ?>
                <tr>
                    <td class="text-muted">#<?= $row['id'] ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($row['name']) ?></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary me-2" onclick="editCategory(<?= $row['id'] ?>, '<?= htmlspecialchars(addslashes($row['name'])) ?>')" data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category? All related products will also be deleted.');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="3" class="text-center py-4 text-muted">No categories found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0" style="border-radius: 8px;">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Category Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0" style="border-radius: 8px;">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_cat_id">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Category Name</label>
                        <input type="text" name="name" id="edit_cat_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editCategory(id, name) {
    document.getElementById('edit_cat_id').value = id;
    document.getElementById('edit_cat_name').value = name;
}
</script>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
