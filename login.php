<?php
// login.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/config/database.php';

if (is_customer_logged_in()) {
    redirect(BASE_URL . 'account.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please enter your email and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $customer = $res->fetch_assoc();
            // Need to handle legacy customers that might not have a password
            if (empty($customer['password'])) {
                $error = "This account was created without a password during a previous checkout. Please contact support.";
            } elseif (password_verify($password, $customer['password'])) {
                $_SESSION['customer_id'] = $customer['id'];
                
                // If they came from cart, maybe redirect back to checkout. But simple redirect to account is fine.
                redirect(BASE_URL . 'account.php');
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <h3 class="fw-bold mb-1 text-center">Welcome Back</h3>
                <p class="text-muted small text-center mb-4">Sign in to your Apple Planet account.</p>

                <?php if ($error): ?>
                    <div class="alert alert-danger py-2 small"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Email Address</label>
                        <input type="email" name="email" class="form-control" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Sign In</button>
                </form>
                
                <div class="text-center mt-4">
                    <span class="text-muted small">Don't have an account? <a href="<?= BASE_URL ?>register.php" class="text-primary text-decoration-none fw-bold">Sign Up</a></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
