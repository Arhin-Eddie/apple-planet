<?php
// includes/functions.php

function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function is_admin_logged_in() {
    return isset($_SESSION['admin_id']);
}

function require_admin() {
    if (!is_admin_logged_in()) {
        redirect(BASE_URL . 'admin/login.php');
    }
}

function format_price($price) {
    return '$' . number_format($price, 2);
}

function get_cart_count() {
    $count = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
    }
    return $count;
}
