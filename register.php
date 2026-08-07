<?php
// register.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/config/database.php';

if (is_customer_logged_in()) {
    redirect(BASE_URL . 'account.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = sanitize_input($_POST['first_name'] ?? '');
    $last_name = sanitize_input($_POST['last_name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $phone = sanitize_input($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $error = "First name, last name, email, and password are required.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check duplicate email
        $stmt = $conn->prepare("SELECT id FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "An account with this email already exists.";
        } else {
            // Hash password and insert
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO customers (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
            $insert->bind_param("sssss", $first_name, $last_name, $email, $phone, $hashed);
            if ($insert->execute()) {
                $_SESSION['customer_id'] = $conn->insert_id;
                redirect(BASE_URL . 'account.php');
            } else {
                $error = "Failed to create account. Please try again.";
            }
        }
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <h3 class="fw-bold mb-1 text-center">Create Account</h3>
                <p class="text-muted small text-center mb-4">Join Apple Planet for a faster checkout.</p>

                <?php if ($error): ?>
                    <div class="alert alert-danger py-2 small"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="register.php">
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">First Name *</label>
                            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Last Name *</label>
                            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Email Address *</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Password * (Min 6 characters)</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Confirm Password *</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Sign Up</button>
                </form>
                
                <div class="text-center mt-4">
                    <span class="text-muted small">Already have an account? <a href="<?= BASE_URL ?>login.php" class="text-primary text-decoration-none fw-bold">Log In</a></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
