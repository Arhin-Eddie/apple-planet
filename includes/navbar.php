<?php
// includes/navbar.php
?>
<nav class="navbar navbar-expand-lg sticky-top app-navbar">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>">Apple Planet</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>products.php">Products</a>
                </li>
            </ul>
            
            <form class="d-flex me-3" action="<?= BASE_URL ?>products.php" method="GET">
                <div class="input-group input-group-sm app-search">
                    <input class="form-control border-end-0" type="search" name="search" placeholder="Search..." aria-label="Search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button class="btn border border-start-0 bg-transparent text-muted" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            <ul class="navbar-nav">
                <?php if (is_customer_logged_in()): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fw-bold text-dark" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person me-1"></i> My Account
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="accountDropdown">
                        <li><a class="dropdown-item py-2" href="<?= BASE_URL ?>account.php">Dashboard</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="<?= BASE_URL ?>logout.php">Logout</a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>register.php">Sign Up</a>
                </li>
                <?php endif; ?>
                
                <li class="nav-item ms-lg-3">
                    <a class="nav-link position-relative" href="<?= BASE_URL ?>cart.php">
                        <i class="bi bi-bag"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark cart-count">
                            <?= get_cart_count() ?>
                            <span class="visually-hidden">items in cart</span>
                        </span>
                    </a>
                </li>
                
                <?php if (is_admin_logged_in()): ?>
                <li class="nav-item dropdown ms-3 border-start ps-3">
                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-shield-lock"></i> Admin
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>admin/dashboard.php">Dashboard</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>admin/logout.php">Logout</a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item ms-3 border-start ps-3 d-none d-lg-block">
                    <a class="nav-link text-muted" style="font-size: 0.85rem;" href="<?= BASE_URL ?>admin/login.php">Admin</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Toast Container for Notifications -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="bi bi-bag-check-fill text-success me-2"></i>
            <strong class="me-auto">Cart Updated</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Item added to your cart.
        </div>
    </div>
</div>
