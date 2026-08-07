<?php
// admin/admin_header.php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Apple Planet</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <style>
        body { background-color: var(--secondary-bg); }
        .admin-sidebar { min-height: 100vh; background-color: var(--background); border-right: 1px solid var(--border); }
        .admin-sidebar .nav-link { color: var(--muted); font-weight: 500; padding: 0.75rem 1.25rem; border-radius: 6px; margin-bottom: 0.25rem; }
        .admin-sidebar .nav-link:hover, .admin-sidebar .nav-link.active { background-color: var(--secondary-bg); color: var(--foreground); }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 admin-sidebar">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-4 text-white min-vh-100">
                    <a href="<?= BASE_URL ?>admin/dashboard.php" class="d-flex align-items-center pb-3 mb-md-3 me-md-auto text-decoration-none w-100 border-bottom">
                        <span class="fs-5 d-none d-sm-inline fw-bold text-dark">Apple Planet</span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
                        <li class="nav-item w-100">
                            <a href="<?= BASE_URL ?>admin/dashboard.php" class="nav-link px-0 align-middle">
                                <i class="fs-5 bi-speedometer2"></i> <span class="ms-1 d-none d-sm-inline">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item w-100">
                            <a href="<?= BASE_URL ?>admin/orders.php" class="nav-link px-0 align-middle">
                                <i class="fs-5 bi-box-seam"></i> <span class="ms-1 d-none d-sm-inline">Orders</span>
                            </a>
                        </li>
                        <li class="nav-item w-100">
                            <a href="<?= BASE_URL ?>admin/products.php" class="nav-link px-0 align-middle">
                                <i class="fs-5 bi-grid"></i> <span class="ms-1 d-none d-sm-inline">Products</span>
                            </a>
                        </li>
                        <li class="nav-item w-100">
                            <a href="<?= BASE_URL ?>admin/categories.php" class="nav-link px-0 align-middle">
                                <i class="fs-5 bi-tags"></i> <span class="ms-1 d-none d-sm-inline">Categories</span>
                            </a>
                        </li>
                        <li class="nav-item w-100">
                            <a href="<?= BASE_URL ?>admin/settings.php" class="nav-link px-0 align-middle">
                                <i class="fs-5 bi-gear"></i> <span class="ms-1 d-none d-sm-inline">Settings</span>
                            </a>
                        </li>
                    </ul>
                    <hr>
                    <div class="dropdown pb-4 w-100 text-center text-sm-start mt-auto">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-4 me-2"></i>
                            <span class="d-none d-sm-inline mx-1">Admin</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>" target="_blank">View Store</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>admin/profile.php">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>admin/logout.php">Sign out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Main Content -->
            <div class="col py-4 px-4 px-md-5 bg-light">
