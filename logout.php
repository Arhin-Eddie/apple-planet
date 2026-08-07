<?php
// logout.php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/includes/functions.php';

if (isset($_SESSION['customer_id'])) {
    unset($_SESSION['customer_id']);
}
redirect(BASE_URL);
