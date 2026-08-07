<?php
// admin/profile.php
require_once __DIR__ . '/admin_header.php';

$success = '';
$error = '';
$admin_id = $_SESSION['admin_id'];

// Fetch current admin info
$stmt = $conn->prepare("SELECT username FROM admins WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($username)) {
        $error = "Username is required.";
    } elseif (!empty($new_password) && $new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        if (!empty($new_password)) {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE admins SET username = ?, password = ? WHERE id = ?");
            $update_stmt->bind_param("ssi", $username, $hashed, $admin_id);
        } else {
            $update_stmt = $conn->prepare("UPDATE admins SET username = ? WHERE id = ?");
            $update_stmt->bind_param("si", $username, $admin_id);
        }
        
        if ($update_stmt->execute()) {
            $success = "Profile updated successfully.";
            $admin['username'] = $username;
        } else {
            $error = "Failed to update profile (username might be taken).";
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Admin Profile</h3>
</div>

<?php if($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <div class="admin-card">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Username *</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($admin['username']) ?>" required>
                </div>
                
                <hr class="my-4">
                <h5 class="fw-bold mb-3 fs-6">Change Password</h5>
                <p class="text-muted small mb-3">Leave blank if you do not want to change your password.</p>
                
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">New Password</label>
                    <input type="password" name="new_password" class="form-control">
                </div>
                
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control">
                </div>
                
                <button type="submit" class="btn btn-primary py-2 px-4">Update Profile</button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
